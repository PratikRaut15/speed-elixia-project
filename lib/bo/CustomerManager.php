<?php

if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
require_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';

class CustomerManager {
    private $_databaseManager = null;

    public function __construct() {
        if ($this->_databaseManager == null) {
            $this->_databaseManager = new DatabaseManager();
        }
    }

    public function PrepareSP($sp_name, $sp_params) {
        return "call " . $sp_name . "(" . $sp_params . ");";
    }

    public function customerAccepted($id) {
        $SQL = sprintf("SELECT agreedby, agreeddate FROM " . DB_PARENT . ".customer WHERE customerno= %d AND (agreedby <> '')", $id);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function acceptCustomer($id, $username) {
        $today = date("Y-m-d H:i:s");
        $SQL = sprintf("UPDATE " . DB_PARENT . ".customer SET agreedby='%s', agreeddate='%s' WHERE customerno=%d", $username, Sanitise::DateTime($today), $id);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getcustomernos() {
        $customernos = array();
        $SQL = sprintf("SELECT customerno FROM " . DB_PARENT . ".customer WHERE customerno <>1");
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customerno = $row["customerno"];
                $customernos[] = $customerno;
            }
            return $customernos;
        }
        return false;
    }

    public function get_tempsesors($customer_id) {
        $SQL = sprintf("SELECT temp_sensors FROM " . DB_PARENT . ".customer WHERE customerno =" . $customer_id);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $tempsensors = $row["temp_sensors"];
            }
            return $tempsensors;
        }
    }

    public function getcustomernos_tracking() {
        $customernos = array();
        $SQL = sprintf("SELECT customerno FROM " . DB_PARENT . ".customer WHERE customerno <>1 AND use_tracking=1");
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customerno = $row["customerno"];
                $customernos[] = $customerno;
            }
            return $customernos;
        }
        return false;
    }

    public function getcustomernos_ForMaintenance() {
        $customernos = array();
        $SQL = sprintf("SELECT customerno FROM " . DB_PARENT . ".customer WHERE customerno <> 1 AND use_maintenance=1 ");
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customerno = $row["customerno"];
                $customernos[] = $customerno;
            }
            return $customernos;
        }
        return false;
    }

    public function getcustomernos_for_smsleft() {
        $customernos = array();
        $SQL = sprintf("SELECT c.customerno
                ,c.customername
                ,c.smsleft
                ,`user`.email
                FROM " . DB_PARENT . ".customer c
                LEFT OUTER JOIN `user` ON `user`.customerno=c.customerno
                Where c.`smsleft` <= 50
                    AND c.`sms_balance_alert`='0'
                    AND `user`.role='Administrator'
                    AND `user`.isdeleted=0
                    AND `user`.`email` <> ''
                    GROUP BY c.customerno");
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customer['customerno'] = $row['customerno'];
                $customer['customername'] = $row['customername'];
                $customer['smsleft'] = $row['smsleft'];
                $customer['email'] = $row['email'];
                $customernos[] = $customer;
            }
            return $customernos;
        }
        return false;
    }

    public function getcustomernos_for_temp() {
        $customernos = array();
        $SQL = sprintf("SELECT customerno,temp_sensors FROM " . DB_PARENT . ".customer WHERE customerno <> 1 and temp_sensors <> 0 AND customerno = 177");
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customer = new VOCustomer();
                $customer->customerno = $row['customerno'];
                $customer->temp_sensor = $row['temp_sensors'];
                $customernos[] = $customer;
            }
            return $customernos;
        }
        return false;
    }

    public function getcustomerdetail() {
        $customernos = array();
        $SQL = sprintf("SELECT customerno,customername,customercompany FROM " . DB_PARENT . ".customer");
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customer = new VOCustomer();
                $customer->customerno = $row['customerno'];
                $customer->customername = $row['customername'];
                $customer->customercompany = $row['customercompany'];
                $customernos[] = $customer;
            }
            //print_r($customernos);
            return $customernos;
        }
        return false;
    }

    public function getdevicedata($uid) {
        $Query = "SELECT powercut,tamper FROM `devices`
            where `uid` = '$uid'";
        $devicesQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VOCustomer();
                $device->powercut = $row['powercut'];
                $device->tamper = $row['tamper'];
                $devices = $device;
            }
            return $devices;
        }
        return null;
    }

    public function getgroupname($uid) {
        $Query = "SELECT `group`.groupname FROM `vehicle` INNER JOIN `group` ON `group`.groupid = vehicle.groupid
            where vehicle.uid = '$uid'";
        $devicesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row['groupname'];
            }
        }
        return "N/A";
    }

    public function updateSmsMailsent($customerno) {
        $Query = "UPDATE " . DB_PARENT . ".`customer` SET `sms_balance_alert`='1' WHERE customerno=%d";
        $SQL = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function get_distinct_customerno_for_devices_expirydate() {
        $customernos = array();
        $time = date("Y-m-d", strtotime("-30 Days"));
        $Query = "SELECT distinct devices.customerno,customer.customername FROM `devices`
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = devices.customerno
            where devices.expirydate = '$time' Order by customerno ASC";
        $devicesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customer = new VOCustomer();
                $customer->customerno = $row['customerno'];
                $customer->customername = $row['customername'];
                $customernos[] = $customer;
            }
            return $customernos;
        }
        return null;
    }

    public function getcustomerno_driver_alerts() {
        $customernos = array();
        $query = "select * from driver_alerts";
        $devicesQuery = sprintf($query);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customer = new VOCustomer();
                $customer->customerno = $row['customerno'];
                $customer->userid = $row['userid'];
                $customer->driverid = $row['driverid'];
                $customernos[] = $customer;
            }
            return $customernos;
        }
        return null;
    }

    public function get_distinct_customerno_for_batch() {
        $customernos = array();
        $Query = "SELECT distinct batch.customerno FROM `batch`
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = batch.customerno order by customer.customerno DESC";
        $devicesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customer = new VOCustomer();
                $customer->customerno = $row['customerno'];
                $customernos[] = $customer;
            }
            return $customernos;
        }
        return null;
    }

    public function get_distinct_customerno_for_orders() {
        $customernos = array();
        $Query = "SELECT distinct customerno FROM delivery.`master_orders` ";
        $devicesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customer = new VOCustomer();
                $customer->customerno = $row['customerno'];
                $customernos[] = $customer;
            }
            return $customernos;
        }
        return null;
    }

    public function get_all_devices_for_expirydate() {
        $devices = array();
        $time = date("Y-m-d", strtotime("-30 Days"));
        $Query = "SELECT devices.deviceid,devices.customerno,
            devices.devicekey,simcard.simcardno as phone,devices.expirydate,devices.uid,unit.unitno,
            registeredon, Now() as today FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            LEFT OUTER JOIN simcard ON devices.simcardid = simcard.id
            where devices.expirydate = '$time' AND unit.trans_statusid NOT IN (10,22) Order by customerno ASC";
        $devicesQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->customerno = $row['customerno'];
                $device->devicekey = $row['devicekey'];
                $device->uid = $row['uid'];
                $device->unitno = $row['unitno'];
                $device->phone = $row['phone'];
                $device->today = $row["today"];
                $device->expirydate = $row["expirydate"];
                $device->registeredon = $row["registeredon"];
                $devices[] = $device;
            }
            return $devices;
        }
        return null;
    }

    public function pullsmsdetails($id) {
        $SQL = sprintf("SELECT totalsms,smsleft FROM " . DB_PARENT . ".customer WHERE customerno=%d", Sanitise::Long($id));
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customer = new VOCustomer();
                $customer->totalsms = $row["totalsms"];
                $customer->smsleft = $row["smsleft"];
            }
            return $customer;
        }
        return false;
    }

    public function pulltelephonicdetails($id) {
        $SQL = sprintf("SELECT total_tel_alert,tel_alertleft FROM " . DB_PARENT . ".customer WHERE customerno=%d", Sanitise::Long($id));
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customer = new VOCustomer();
                $customer->total_tel_alert = $row["tel_alertleft"];
                $customer->tel_alertleft = $row["tel_alertleft"];
            }
            return $customer;
        }
        return false;
    }

    public function pullvehiclesmsmdetails($vehicleid, $customerno) {
        $SQL = sprintf("SELECT sms_count,sms_lock FROM vehicle WHERE vehicleid=%d AND customerno = %d", Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->smscount = $row['sms_count'];
                $vehicle->smslock = $row['sms_lock'];
            }
            return $vehicle;
        }
        return false;
    }

    public function pullvehiclestelephonicdetails($vehicleid, $customerno) {
        $SQL = sprintf("SELECT tel_count,tel_lock FROM vehicle WHERE vehicleid=%d AND customerno = %d", Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->tel_count = $row['tel_count'];
                $vehicle->tel_lock = $row['tel_lock'];
            }
            return $vehicle;
        }
        return false;
    }

    public function getvehicles_unlocked() {
        $vehicles = array();
        $QUERY = sprintf('SELECT vehicleid, vehicleno, customerno, uid, userid FROM vehicle WHERE isdeleted=0 AND sms_lock = 0');
        $this->_databaseManager->executeQuery($QUERY);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->customerno = $row['customerno'];
                $vehicle->uid = $row['uid'];
                $vehicle->userid = $row['userid'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function updatesmsforcheckpoint($smsleft, $id) {
        $SQL = sprintf("UPDATE " . DB_PARENT . ".customer SET smsleft=%d WHERE customerno=%d", Sanitise::Long($smsleft), Sanitise::Long($id));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function updateTelephonicDetails($tel_alertleft, $id, $vehicleid) {
        $SQL = sprintf("UPDATE " . DB_PARENT . ".customer SET tel_alertleft=%d WHERE customerno=%d", Sanitise::Long($tel_alertleft), Sanitise::Long($id));
        $this->_databaseManager->executeQuery($SQL);
        $SQL = sprintf("UPDATE vehicle SET tel_count=tel_count+1 WHERE vehicleid=%d AND customerno = %d", Sanitise::Long($vehicleid), Sanitise::Long($id));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function setlock($id, $customerno) {
        $SQL = sprintf("UPDATE vehicle SET sms_lock=1 WHERE vehicleid=%d AND customerno = %d", Sanitise::Long($id), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function removelock($id, $customerno) {
        $SQL = sprintf("UPDATE vehicle SET sms_lock=0 WHERE vehicleid=%d AND customerno = %d", Sanitise::Long($id), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function setTelephonicLock($id, $customerno) {
        $SQL = sprintf("UPDATE vehicle SET tel_lock=1 WHERE vehicleid=%d AND customerno = %d", Sanitise::Long($id), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function removeTelephonicLock($id, $customerno) {
        $SQL = sprintf("UPDATE vehicle SET tel_lock=0 WHERE vehicleid=%d AND customerno = %d", Sanitise::Long($id), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function get_customer_company($customerno) {
        $SQL = sprintf("SELECT customercompany FROM " . DB_PARENT . ".customer WHERE customerno=%d", Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customer = $row['customercompany'];
            }
            return $customer;
        }
        return false;
    }

    /* ak added, for download/report.php */

    public function getcustomerdetail_byid($custid) {
        $SQL = sprintf("SELECT  `customer`.customerno
                                ,customername
                                ,customercompany
                                ,temp_sensors
                                ,use_humidity
                                ,use_door_sensor
                                ,use_ac_sensor
                                ,use_genset_sensor
                                ,use_geolocation
                                ,use_warehouse
                                ,use_tracking
                                ,use_panic
                                ,use_buzzer
                                ,use_immobiliser
                                ,use_freeze
                                ,use_extradigital
                                ,use_fuel_sensor
                                ,smsleft
                                ,`setting`.use_location_summary
                        FROM    " . DB_PARENT . ".`customer`
                        LEFT JOIN `setting` ON `setting`.customerno = `customer`.customerno AND `setting`.isdeleted = 0
                        WHERE   `customer`.customerno = %d", Sanitise::Long($custid));
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customer = new VOCustomer();
                $customer->customerno = $row['customerno'];
                $customer->customername = $row['customername'];
                $customer->customercompany = $row['customercompany'];
                $customer->temp_sensors = $row['temp_sensors'];
                $customer->use_humidity = $row['use_humidity'];
                $customer->use_door_sensor = $row['use_door_sensor'];
                $customer->use_ac_sensor = $row['use_ac_sensor'];
                $customer->use_genset_sensor = $row['use_genset_sensor'];
                $customer->use_geolocation = $row['use_geolocation'];
                $customer->use_warehouse = $row['use_warehouse'];
                $customer->use_tracking = $row['use_tracking'];
                $customer->use_panic = $row['use_panic'];
                $customer->use_buzzer = $row['use_buzzer'];
                $customer->use_immobiliser = $row['use_immobiliser'];
                $customer->use_freeze = $row['use_freeze'];
                $customer->use_extradigital = $row['use_extradigital'];
                $customer->use_fuel_sensor = $row['use_fuel_sensor'];
                $customer->smsleft = $row['smsleft'];
                $customer->use_location_summary = isset($row['use_location_summary']) ? $row['use_location_summary'] : 0;
            }
            return $customer;
        }
        return false;
    }

    public function getgroupname_new($uid, $customerno, $vehicleid = NULL) {
        if (isset($vehicleid)) {
            $Query = "  SELECT  `group`.groupname
                        FROM    `vehicle`
                        INNER JOIN `group` ON `group`.groupid = vehicle.groupid
                        where   vehicle.vehicleid = '$vehicleid' and vehicle.customerno=%d";
        } else {
            $Query = "SELECT `group`.groupname FROM `vehicle` INNER JOIN `group` ON `group`.groupid = vehicle.groupid
            where vehicle.uid = '$uid' and vehicle.customerno=%d";
        }
        $devicesQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row['groupname'];
            }
        }
        return "N/A";
    }

    public function GetUserRole($userid) {
        $SQL = "SELECT user.role,user.realname,user.username,customer.customercompany,customer.customerno from user
                Inner join " . DB_PARENT . ".customer on customer.customerno = user.customerno
                WHERE user.userid=%d";
        $Query = sprintf($SQL, $userid);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOCustomer();
                $vehicle->role = $row['role'];
                $vehicle->realname = $row['realname'];
                $vehicle->username = $row['username'];
                $vehicle->customercompany = $row['customercompany'];
                $vehicle->customerno = $row['customerno'];
                return $vehicle;
            }
        }
        return null;
    }

    public function timezone_name_cron($custom, $customerno) {
        $timezone = $custom;
        $Query = sprintf("SELECT customer.timezone as tz, timezone.timezone as zone from " . DB_PARENT . ".customer
            inner join " . DB_PARENT . ".timezone on customer.timezone = timezone.tid
            where customer.customerno=%d", Validator::escapeCharacters($customerno));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $timezone = $row['zone'];
            }
        }
        return $timezone;
    }

    public function timezone_name_cron_savesqlite($custom, $customerno) {
        $timezone = $custom;
        $Query = sprintf("SELECT customer.timezone as tz, timezone.timezone as zone from " . DB_PARENT . ".customer
            inner join " . DB_PARENT . ".timezone on customer.timezone = timezone.tid
            where customer.customerno=%d", Validator::escapeCharacters($customerno));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $timezone = $row['zone'];
            }
        }

        return $timezone;
    }

    public function getgrouplistby_customer($customerno) {
        $grouplist = array();
        $Query = "SELECT groupname,groupid FROM  `group` where customerno=%d AND isdeleted=0 ";
        $devicesQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $grouplist[] = array(
                    'groupid' => $row['groupid'],
                    'groupname' => $row['groupname']
                );
            }
            $grouplist;
        } else {
            $grouplist = NULL;
        }
        return $grouplist;
    }

    public function updateDailyReportSmsCount($vehicleid, $smscount) {
        $today = date('Y-m-d');
        //$today = "%" . $today . "%";
        $SQL = sprintf("UPDATE dailyreport SET sms_count = sms_count + %d WHERE vehicleid =%d AND daily_date = '%s'", Sanitise::Long($smscount), Sanitise::Long($vehicleid), Sanitise::String($today));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function updateDailyReportEmailCount($vehicleid) {
        $today = date('Y-m-d');
        //$today = "%" . $today . "%";
        $SQL = sprintf("UPDATE dailyreport SET email_count = email_count + 1 WHERE vehicleid = %d AND daily_date = '%s'", Sanitise::Long($vehicleid), Sanitise::String($today));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getallcustomerno() {
        $customernos = array();
        $SQL = "CALL " . speedConstants::SP_GET_ALL_CUSTOMER . "()";
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customernos[] = array(
                    'customerno' => $row['customerno'],
                    'customercompany' => $row['customercompany'],
                    'customername' => $row['customername'],
                    'smsleft' => $row['smsleft']);
            }
            $customernos;
        } else {
            $customernos = NULL;
        }
        return $customernos;
    }

    public function getCustNotAllotMngr() {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $queryCallSP = "CALL " . speedConstants::SP_GET_CUSTOMER_NOT_CRM . "()";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        if (!empty($arrResult)) {
            foreach ($arrResult as $data) {
                $customerno = new stdClass();
                $customerno->customerno = $data['customerno'];
                $customerno->customername = $data['customername'];
                $customerno->customercompany = $data['customercompany'];
                $customer[] = $customerno;
            }
            return $customer;
        } else {
            return NULL;
        }
    }

    public function getLowSmsLeftCust() {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $queryCallSP = "CALL " . speedConstants::SP_GET_LOW_SMS_LEFT_CUST . "()";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        if (!empty($arrResult)) {
            $customerno = array();
            foreach ($arrResult as $data) {
                $customerno['customerno'] = $data['customerno'];
                $customerno['customercompany'] = $data['customercompany'];
                $customerno['smsleft'] = $data['smsleft'];
                $customer[] = $customerno;
            }
            return $customer;
        } else {
            return NULL;
        }
    }

    public function getSMSConsumed($cust) {
        $comqCount = $smslCount = 0;
        $pdo = $this->_databaseManager->CreatePDOConn();
        $todaysdate = date('Y-m-d');
        $sp_params = $cust . ",'" . $todaysdate . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_SMS_CONSUME_FRM_COMQ . "($sp_params)";
        $comqCount = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $queryCallSP = "CALL " . speedConstants::SP_GET_SMS_CONSUME_FRM_SMSLOG . "($sp_params)";
        $smslCount = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        return ($comqCount['0']['count1'] + $smslCount['0']['count1']);
    }

    public function getSMSConsumedDetails($cust) {
        $data = array();
        $comqCount = $smslCount = 0;
        $pdo = $this->_databaseManager->CreatePDOConn();
        $yesterday = date('Y-m-d');
        $sp_params = $cust . ",'" . $yesterday . "'";
        $smslogcount = 0;
        $comqcount = 0;
        $data = array();
        $data1 = array();
        $data2 = array();
        $smslogdetails = array();
        $comquedetails = array();
        $queryCallSP = "CALL " . speedConstants::SP_GET_SMS_CONSUME_FRM_COMQ_DETAILS . "($sp_params)";
        $comquedetails = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        if (!empty($comquedetails)) {
            foreach ($comquedetails as $row) {
                $data1[] = array(
                    'message' => $row['message'],
                    'sendtime' => $row['timesent'],
                    'realname' => $row['realname'],
                    'vehicleno' => $row['vehicleno']
                );
            }
        }

        $queryCallSP = "CALL " . speedConstants::SP_GET_SMS_CONSUME_FRM_SMSLOG_DETAILS . "($sp_params)";
        $smslogdetails = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        if (!empty($smslogdetails)) {
            foreach ($smslogdetails as $row) {
                $data2[] = array(
                    'message' => $row['message'],
                    'sendtime' => $row['inserted_datetime'],
                    'realname' => $row['realname'],
                    'vehicleno' => $row['vehicleno']
                );
            }
        }
        $this->_databaseManager->ClosePDOConn($pdo);
        $data = array_merge($data1, $data2);
        return $data;
    }

    public function getSMSCount($customerno) {
        $smscount = 0;
        $sqlSmsGet = sprintf("SELECT smsleft FROM " . DB_PARENT . ".customer WHERE customerno=%d", $customerno);
        $this->_databaseManager->executeQuery($sqlSmsGet);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $smscount = $row['smsleft'];
        }
        return $smscount;
    }

    public function getSMSStatus($smsStatus) {
        $todaysdatetime = date('Y-m-d H:i:s');
        if ($smsStatus->userid == 0 && $smsStatus->vehicleid == 0) {
            return 1;
        } else {
            $pdo = $this->_databaseManager->CreatePDOConn();
            $sp_params = "'" . $todaysdatetime . "'"
            . ",'" . $smsStatus->customerno . "'"
            . ",'" . $smsStatus->vehicleid . "'"
            . ",'" . $smsStatus->userid . "'"
            . ",'" . $smsStatus->mobileno . "'"
            . ",'" . $smsStatus->message . "'"
            . ",'" . $smsStatus->cqid . "'"
                . "," . '@status';
            $queryCallSP = $this->PrepareSP(speedConstants::SP_GET_SMS_STATUS, $sp_params);
            $pdo->query($queryCallSP);
            $this->_databaseManager->ClosePDOConn($pdo);
            $outputParamsQuery = "SELECT @status AS status";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            return $outputResult['status'];
        }
    }

    public function sentSmsPostProcess($customerno, $phone, $message, $response, $isSMSSent, $userid, $vehicleid, $moduleid, $cqid = NULL) {
        $phncount = 0;
        $smsLogId = 0;
        $todaysdate = date("Y-m-d H:i:s");
        if (is_array($phone)) {
            foreach ($phone as $phoneno) {
                $smsLogId = $this->insertCustomerSMSLog($phoneno, $message, $response, $vehicleid, $userid, $customerno, $isSMSSent, $todaysdate, $moduleid, $cqid);
                $phncount++;
            }
        } else {
            $smsLogId = $this->insertCustomerSMSLog($phone, $message, $response, $vehicleid, $userid, $customerno, $isSMSSent, $todaysdate, $moduleid, $cqid);
            $phncount = 1;
        }

        $smsconsumed = 0;
        $smscount = 0;
        $smslen = strlen($message);
        $divide = floor($smslen / speedConstants::PER_SMS_CHARACTERS);
        $mod = $smslen % speedConstants::PER_SMS_CHARACTERS;
        if ($mod > 0) {
            $smsconsumed = $divide + 1;
        } elseif ($mod == 0) {
            $smsconsumed = $divide;
        }
        $smscount = $phncount * $smsconsumed;

        $this->updateSMSCount($smscount, $customerno);

        if ($vehicleid != 0) {
            $this->updateVehicleSmsCount($customerno, $vehicleid, $smscount);
            $this->updateDailyReportSmsCount($vehicleid, $smscount);
        }
        if ($userid != 0) {
            $this->updateUserSmsCount($customerno, $userid, $smscount);
        }
        return $smsLogId;
    }

    public function insertCustomerSMSLog($phone, $message, $response, $vehicleid, $userid, $customerno, $isSMSSent, $todaysdate, $moduleid, $cqid = NULL) {
        $smsid = 0;
        try {
            if ($cqid == NULL) {
                $cqid = 0;
            }
            $pdo = $this->_databaseManager->CreatePDOConn();
            $sp_params = "'" . $phone . "'"
                . ",'" . $message . "'"
                . ",'" . $response . "'"
                . ",'" . $vehicleid . "'"
                . ",'" . $userid . "'"
                . ",'" . $customerno . "'"
                . ",'" . $isSMSSent . "'"
                . ",'" . $todaysdate . "'"
                . ",'" . $moduleid . "'"
                . ",'" . $cqid . "'"
                . "," . '@smsid';
            $queryCallSP = $this->PrepareSP(speedConstants::SP_INSERT_SMSLOG, $sp_params);
            $pdo->query($queryCallSP);
            $this->_databaseManager->ClosePDOConn($pdo);
            $outputParamsQuery = "SELECT @smsid AS smsid";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            if (count($outputResult) > 0) {
                $smsid = $outputResult['smsid'];
            }
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($customerno, $ex, $userid, speedConstants::MODULE_VTS, __FUNCTION__);
        }
        return $smsid;
    }

    public function updateVehicleSmsCount($customerno, $vehicleid, $smscount) {
        if ($customerno != 95 && $customerno != 59) {
            $todaysdate = date("Y-m-d H:i:s");
            $pdo = $this->_databaseManager->CreatePDOConn();
            $sp_params = "'" . $todaysdate . "'"
            . ",'" . $customerno . "'"
            . ",'" . $vehicleid . "'"
            . ",'" . $smscount . "'"
            . ",'" . speedConstants::VEH_SMS_LOCK . "'";
            $queryCallSP = $this->PrepareSP(speedConstants::SP_UPDATE_VEHICLE_SMSLOCK, $sp_params);
            $pdo->query($queryCallSP);
            $this->_databaseManager->ClosePDOConn($pdo);
        }
    }

    public function updateUserSmsCount($customerno, $userid, $smscount) {
        if ($customerno != 95 && $customerno != 59) {
            $todaysdate = date("Y-m-d H:i:s");
            $pdo = $this->_databaseManager->CreatePDOConn();
            $sp_params = "'" . $todaysdate . "'"
            . ",'" . $customerno . "'"
            . ",'" . $userid . "'"
            . ",'" . $smscount . "'"
            . ",'" . speedConstants::USER_SMS_LOCK . "'";
            $queryCallSP = $this->PrepareSP(speedConstants::SP_UPDATE_USER_SMSLOCK, $sp_params);
            $pdo->query($queryCallSP);
            $this->_databaseManager->ClosePDOConn($pdo);
        }
    }

    public function insertCustomerEmailLog($objEmailLog) {
        $emailLogId = 0;
        $SQL = "INSERT INTO emailLog(emailid,emailSubject,emailMessage,vehicleid,userid,type,moduleid,customerno,isMailSent,created_on)
        VALUES('%s','%s','%s',%d,%d,%d,%d,%d,%d,'%s')";
        $Query = sprintf($SQL, $objEmailLog->email, $objEmailLog->subject, $objEmailLog->message, $objEmailLog->vehicleid, $objEmailLog->userid, $objEmailLog->type, $objEmailLog->moduleid, $objEmailLog->customerno, $objEmailLog->isMailSent, $objEmailLog->today);
        $this->_databaseManager->executeQuery($Query);
        $emailLogId = $this->_databaseManager->get_insertedId();
        return $emailLogId;
    }

    public function chkptExErrorLog($objError) {
        $errorLogId = 0;
        $SQL = "INSERT INTO chkptExErrorLog(chkptExAlertId,errorNo,customerno,created_by,created_on)VALUES(%d,%d,%d,%d,'%s')";
        $Query = sprintf($SQL, $objError->chkptExAlertId, $objError->errorNo, $objError->customerno, $objError->userid, $objError->today);
        $this->_databaseManager->executeQuery($Query);
        $errorLogId = $this->_databaseManager->get_insertedId();
        return $errorLogId;
    }

    public function getCustomerEmailId($cust) {
        $emailids = array();
        $pdo = $this->_databaseManager->CreatePDOConn();
        $SQL = "CALL " . speedConstants::SP_GET_EMAIL_IDS . "($cust)";
        $arrResult = $pdo->query($SQL)->fetchall(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        if (!empty($arrResult)) {
            foreach ($arrResult as $data) {
                array_push($emailids, $data['email']);
            }
            return $emailids;
        } else {
            $emailids = NULL;
            return $emailids;
        }
    }

    public function insertCheckpointOwnerLog($objLog) {
        $logId = 0;
        $SQL = "INSERT INTO chkptOwnerLog(smsId, emailId, checkpointId, vehicleId, routeId, customerno, created_on)VALUES(%d,%d,%d,%d,%d,%d,'%s')";
        $Query = sprintf($SQL, $objLog->smsId, $objLog->emailId, $objLog->checkpointId, $objLog->vehicleId, $objLog->routeId, $objLog->customerno, $objLog->today);
        $this->_databaseManager->executeQuery($Query);
        $logId = $this->_databaseManager->get_insertedId();
        return $logId;
    }

    public function getChkptOwnerLog($objLog) {
        $arrLog = null;
        $sql = "SELECT s.mobileno, s.message, s.issmssent, e.emailid, e.emailMessage, e.isMailSent, chkpt.cname, v.vehicleno,
            r.routename, rm.sequence, g.groupname, c.created_on as senttime
            FROM chkptOwnerLog as c
            INNER JOIN checkpoint as chkpt on chkpt.checkpointid = c.checkpointId
            INNER JOIN vehicle as v on v.vehicleid = c.vehicleId
            INNER JOIN route as r on r.routeid = c.routeid
            INNER JOIN routeman as rm on rm.routeid = c.routeid AND rm.checkpointid = c.checkpointId
            LEFT OUTER JOIN smslog as s on s.smsid = c.smsId
            LEFT OUTER JOIN emailLog as e on e.emailLogId = c.emailId
            LEFT OUTER JOIN `group` as g on g.groupid = v.groupid
            WHERE c.created_on BETWEEN '%s' AND '%s'
            AND c.customerno = %d
            AND v.customerno = %d
            AND r.customerno = %d
            AND chkpt.customerno = %d
            AND chkpt.isdeleted = 0
            AND v.isdeleted = 0
            AND r.isdeleted = 0
            AND rm.isdeleted = 0";
        $query = sprintf($sql, $objLog->logFromDate, $objLog->logToDate, $objLog->customerno, $objLog->customerno, $objLog->customerno, $objLog->customerno);
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $log = new stdClass();
                $log->groupname = isset($row['groupname']) ? $row['groupname'] : "Ungrouped";
                $log->vehicleno = $row['vehicleno'];
                $log->sequence = $row['sequence'];
                $log->cname = $row['cname'];
                $log->senttime = $row['senttime'];
                $log->mobileno = isset($row['mobileno']) ? $row['mobileno'] : '';
                $log->emailid = isset($row['emailid']) ? $row['emailid'] : '';
                $log->message = isset($row['message']) ? $row['message'] : '';
                $log->emailMessage = isset($row['emailMessage']) ? $row['emailMessage'] : '';
                $arrLog[] = $log;
            }
        }
        return $arrLog;
    }

    public function updateSMSCount($smscount, $customerno) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sqlSmsUpdate = sprintf("UPDATE customer SET smsleft=smsleft - %d WHERE customerno=%d", $smscount, $customerno);
        $pdo->query($sqlSmsUpdate);
        $this->_databaseManager->ClosePDOConn($pdo);
    }

    public function getcustomernos_cron_menuconfig($customer) {
        $ids = array();
        for ($i = 0; count($customer) > $i; $i++) {
            $ids[] = $customer[$i]['customerno'];
        }
        $test = implode(',', $ids);
        //$test = 118;
        $customernos = array();
        $SQL = sprintf("SELECT * FROM `user` WHERE `customerno` IN (" . $test . ") AND `isdeleted` = 0 ORDER BY `userid` DESC");
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customernos[] = array(
                    'customerno' => $row['customerno'],
                    'userid' => $row['userid'],
                    'roleid' => $row['roleid'],
                    'role' => $row['role']
                );
            }
            return $customernos;
        }
        return false;
    }

    public function getcustomer_maintenance() {
        $customernos = array();
        $SQL = sprintf("SELECT * FROM `customer` WHERE `use_maintenance` = 1 ORDER BY `customerno` DESC ");
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customernos[] = array(
                    'customerno' => $row['customerno']
                );
            }
            return $customernos;
        }
        return false;
    }

    public function getcustomer_fuel() {
        $customernos = array();
        $SQL = sprintf("SELECT * FROM `customer` WHERE `use_fuel_sensor` = 1 ORDER BY `customerno` DESC ");
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customernos[] = array(
                    'customerno' => $row['customerno'],
                    'customername' => $row['customername'],
                    'customercompany' => $row['customercompany']
                );
            }
            return $customernos;
        }
        return false;
    }

    public function getCustomerUseTrip() {
        $customernos = array();
        $SQL = sprintf("SELECT * FROM `customer` WHERE `use_trip` = 1 ORDER BY `customerno` DESC ");
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customernos[] = array(
                    'customerno' => $row['customerno'],
                    'customername' => $row['customername'],
                    'customercompany' => $row['customercompany']
                );
            }
            return $customernos;
        }
        return false;
    }

    public function get_customer_settings() {
        $query = "  SELECT  `customerno`
                    FROM    `setting`
                    WHERE   `use_location_summary` = 1
                    AND     `isdeleted` = 0;";
        $sqlQuery = sprintf($query);

        $this->_databaseManager->executeQuery($sqlQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $details = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                array_push($details, $row['customerno']);
            }
            return $details;
        } else {
            return null;
        }
    }

    public function getSMSCountForTeam() {
        $smslCountForTeam = 0;
        $pdo = $this->_databaseManager->CreatePDOConn();
        $todaysdate = date('2019-01-18');
        $sp_params = "'" . $todaysdate . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_SMS_CONSUME_FROM_SMSLOG_FOR_TEAM . "($sp_params)";
        $smslCountForTeam = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        return $smslCountForTeam['0']['count1'];
    }

    public function updateTripStatus($customerno, $vehicleid, $status) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sqlSmsUpdate = "UPDATE tripdetails SET tripstatusid=" . $status . " ,is_tripend='1' WHERE vehicleid=" . $vehicleid . ",customerno=" . $customerno;
        $pdo->query($sqlSmsUpdate);
        $this->_databaseManager->ClosePDOConn($pdo);
    }
}

?>
