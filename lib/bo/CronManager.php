<?php

if (!isset($RELATIVE_PATH_DOTS) || $RELATIVE_PATH_DOTS == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
include_once $RELATIVE_PATH_DOTS . 'config.inc.php';
include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';

class usergcm {
}

class tasks {
}

class CronManager {
    private $_databaseManager = null;

    public function __construct() {
        if ($this->_databaseManager == null) {
            $this->_databaseManager = new DatabaseManager();
        }
    }

    public function getlastupdateddatefordevicesreason($nodata) {
        $devices = array();
        $Query = "SELECT simcard.simcardno, relationship_manager.manager_name, vehicle.nodata_alert, vehicle.groupid, unit.unitno, devices.deviceid,vehicle.vehicleid,
            devices.uid, vehicle.vehicleno, devices.lastupdated, devices.customerno, devices.ignition, devices.powercut, devices.tamper, devices.gsmstrength,
            devices.gprsregister, customer.customercompany, vehicle.isVehicleResetCmdSent
            FROM  devices
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN " . DB_PARENT . ".customer ON devices.customerno = customer.customerno
            LEFT OUTER JOIN " . DB_PARENT . ".relationship_manager ON customer.rel_manager = relationship_manager.rid
            LEFT OUTER JOIN simcard ON devices.simcardid = simcard.id
            WHERE customer.use_tracking = 1
            AND customer.customerno NOT IN (1)
            AND unit.trans_statusid NOT IN (10,22)
            AND vehicle.nodata_alert = %d
            AND vehicle.isdeleted = 0";
        $devicesQuery = sprintf($Query, $nodata);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->vehicleid = $row['vehicleid'];
                $device->uid = $row['uid'];
                $device->unitno = $row['unitno'];
                $device->vehicleno = $row['vehicleno'];
                $device->groupid = $row['groupid'];
                $device->lastupdated = $row['lastupdated'];
                $device->customerno = $row['customerno'];
                $device->ignition = $row['ignition'];
                $device->powercut = $row['powercut'];
                $device->nodata_alert = $row['nodata_alert'];
                $device->tamper = $row['tamper'];
                $device->gsmstrength = $row['gsmstrength'];
                $device->gprsregister = $row['gprsregister'];
                $device->customercompany = $row['customercompany'];
                $device->relman = $row['manager_name'];
                $device->simcardno = $row['simcardno'];
                $device->isVehicleResetCmdSent = $row['isVehicleResetCmdSent'];
                $devices[] = $device;
            }
            return $devices;
        }
    }

    public function getalldevices() {
        $time = date("Y-m-d H:i:s", strtotime("-60 seconds"));
        //$time = "2013-12-10 13:29:00";
        $devices = array();
        $Query = "select d.devicelat, d.devicelong,d.lastupdated, d.uid,
            v.vehicleid, v.vehicleno, ea.tamper AS tamper_status, d.tamper,ea.powercut AS powercut_status,
            ea.overspeed AS overspeed_status, ea.ac AS ac_status, u.acsensor, u.digitalio, u.is_ac_opp, d.status,
            d.powercut, d.customerno
            FROM devices d
            INNER JOIN unit u ON u.uid = d.uid
            INNER JOIN vehicle v on v.vehicleid = u.vehicleid
            INNER JOIN eventalerts ea ON ea.vehicleid = v.vehicleid
            where u.trans_statusid NOT IN (10,22) AND d.lastupdated >= '$time'";
        $devicesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row['uid'] > 0) {
                    $device = new VODevices();
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->tamperstatus = $row['tamper_status'];
                    $device->tamper = $row['tamper'];
                    $device->customerno = $row['customerno'];
                    $device->powercut = $row['powercut'];
                    $device->powercut_status = $row['powercut_status'];
                    $device->status = $row['status'];
                    $device->overspeed_status = $row['overspeed_status'];
                    $device->acsensor = $row['acsensor'];
                    $device->digitalio = $row['digitalio'];
                    $device->ac_status = $row['ac_status'];
                    $device->is_ac_opp = $row['is_ac_opp'];
                    $devices[] = $device;
                }
            }
            return $devices;
        }
        return NULL;
    }

    public function getalldevices_freeze() {
        $time = date("Y-m-d H:i:s", strtotime("-60 seconds"));
        //$time = '2019-08-29 12:46:43';
        $devices = array();
        $Query = "select d.devicelat, d.devicelong,d.lastupdated, d.uid,u.unitno,
            v.vehicleid, v.vehicleno, ea.tamper AS tamper_status, d.tamper,ea.powercut AS powercut_status,
            ea.overspeed AS overspeed_status, ea.ac AS ac_status, u.acsensor, u.digitalio, u.is_ac_opp, d.status,
            d.powercut, d.customerno, d.ignition
            FROM devices d
            INNER JOIN unit u ON u.uid = d.uid
            INNER JOIN vehicle v on v.vehicleid = u.vehicleid
			INNER JOIN eventalerts ea ON ea.vehicleid = v.vehicleid
			where u.trans_statusid NOT IN (10,22) AND u.is_freeze=1 AND d.lastupdated >= '$time'";
        //where u.trans_statusid NOT IN (10,22) AND u.is_freeze=1 AND d.lastupdated >= '$time' and d.customerno = 135";
        $devicesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row['uid'] > 0) {
                    $device = new VODevices();
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->tamperstatus = $row['tamper_status'];
                    $device->tamper = $row['tamper'];
                    $device->customerno = $row['customerno'];
                    $device->powercut = $row['powercut'];
                    $device->powercut_status = $row['powercut_status'];
                    $device->status = $row['status'];
                    $device->overspeed_status = $row['overspeed_status'];
                    $device->acsensor = $row['acsensor'];
                    $device->digitalio = $row['digitalio'];
                    $device->ac_status = $row['ac_status'];
                    $device->is_ac_opp = $row['is_ac_opp'];
                    $device->uid = $row['uid'];
                    $device->unitno = $row['unitno'];
                    $device->ignition = $row['ignition'];
                    $devices[] = $device;
                }
            }
            return $devices;
        }
        return NULL;
    }

    public function getdevicedata($vehicleid, $customerno) {
        $Query = "select d.devicelat, d.devicelong, d.uid
            FROM devices d
            INNER JOIN unit u ON u.uid = d.uid
            INNER JOIN vehicle v on v.vehicleid = u.vehicleid
            where v.vehicleid = %d AND d.customerno = %d AND u.trans_statusid NOT IN (10,22)";
        $devicesQuery = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row['uid'] > 0) {
                    $device = new VODevices();
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                }
            }
            return $device;
        }
        return NULL;
    }

    public function getsimdata_cron() {
        //$time = date("Y-m-d H:i:s", strtotime("-60 seconds"));
        $time = '2014-02-01 11:49:00';
        $devices = array();
        $Query = "SELECT id,type,phoneno,message,requesttime FROM `simdata`
            where requesttime >= '$time' AND `is_processed`=0 AND `success` = 0";
        $devicesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->id = $row['id'];
                $device->type = $row['type'];
                $device->phoneno = $row['phoneno'];
                $device->message = $row['message'];
                $device->requesttime = $row["requesttime"];
                $devices[] = $device;
            }
            return $devices;
        }
        return null;
    }

    public function getvehicles_cron($vehicleno, $customerno) {
        $vehicle = '%' . $vehicleno . '%';
        $devices = array();
        $Query = "SELECT vehicleid,vehicleno FROM  `vehicle` WHERE `vehicleno` LIKE  '%s' AND  `customerno` =%d AND  `isdeleted` =0";
        $devicesQuery = sprintf($Query, Sanitise::String($vehicle), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->vehicleid = $row['vehicleid'];
                $device->vehicleno = $row['vehicleno'];
                $devices[] = $device;
            }
            return $devices;
        }
        return null;
    }

    public function get_fe_next_day_tasks() {
        $apt_date = date('Y-m-d', strtotime("+ 1 day"));
        $tasks = array();
        $Q = "SELECT b.bucketid, b.vehicleid, v.vehicleno, b.vehicleno as vehno, b.location, sp.timeslot, b.purposeid, c.customercompany, cp.person_name, cp.cp_phone1,
        b.fe_id, te.phone, te.name,u.unitno, s.simcardno
              FROM " . DB_PARENT . ".bucket b
                INNER JOIN " . DB_PARENT . ".customer c ON c.customerno = b.customerno
                INNER JOIN " . DB_PARENT . ".sp_timeslot sp ON sp.tsid = b.timeslotid
                INNER JOIN " . DB_PARENT . ".contactperson_details cp ON cp.cpdetailid = b.coordinatorid
                LEFT OUTER JOIN " . DB_PARENT . ".team te ON te.teamid = b.fe_id
                LEFT OUTER JOIN vehicle v ON v.vehicleid = b.vehicleid
                LEFT OUTER JOIN simcard s ON s.id = b.simcardid
                LEFT OUTER JOIN unit u ON u.uid = b.unitid
                WHERE b.apt_date = '" . $apt_date . "' AND b.status = 4";
        $this->_databaseManager->executeQuery($Q);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $task = new tasks();
                if ($row['vehicleid'] == 0) {
                    $task->vehicleno = $row["vehno"];
                } else {
                    $task->vehicleno = $row["vehicleno"];
                }
                $task->location = $row["location"];
                $task->timeslot = $row["timeslot"];
                if ($row['purposeid'] == 1) {
                    $task->purpose = "New Installation";
                }
                if ($row['purposeid'] == 2) {
                    $task->purpose = "Repair";
                }
                if ($row['purposeid'] == 3) {
                    $task->purpose = "Removal";
                }
                if ($row['purposeid'] == 4) {
                    $task->purpose = "Replacement";
                }
                $task->customercompany = $row["customercompany"];
                $task->person_name = $row["person_name"];
                $task->cp_phone1 = $row["cp_phone1"];
                $task->fe_id = $row["fe_id"];
                $task->phone = $row["phone"];
                $task->name = $row['name'];
                $task->unitno = $row['unitno'];
                $task->simcardno = $row['simcardno'];
                $task->bucketid = $row['bucketid'];
                $tasks[] = $task;
            }
            return $tasks;
        }
        return null;
    }

    public function get_compliance() {
        $apt_date = date('Y-m-d', strtotime("- 1 day"));
        $tasks = array();
        $Q = "SELECT b.customerno, b.status, b.is_compliance, r.reason, b.remarks, b.bucketid, b.vehicleid, v.vehicleno, b.vehicleno as vehno, b.location, sp.timeslot,
        b.purposeid, c.customercompany, cp.person_name, cp.cp_phone1, b.fe_id, te.phone, te.name,u.unitno, s.simcardno
              FROM " . DB_PARENT . ".bucket b
                INNER JOIN " . DB_PARENT . ".customer c ON c.customerno = b.customerno
                INNER JOIN " . DB_PARENT . ".sp_timeslot sp ON sp.tsid = b.timeslotid
                INNER JOIN " . DB_PARENT . ".contactperson_details cp ON cp.cpdetailid = b.coordinatorid
                LEFT OUTER JOIN " . DB_PARENT . ".team te ON te.teamid = b.fe_id
                LEFT OUTER JOIN vehicle v ON v.vehicleid = b.vehicleid
                LEFT OUTER JOIN simcard s ON s.id = b.simcardid
                LEFT OUTER JOIN unit u ON u.uid = b.unitid
                LEFT OUTER JOIN " . DB_PARENT . ".nc_reason r ON r.reasonid = b.reasonid
                WHERE b.apt_date = '" . $apt_date . "' AND b.status IN (2,3,5)";
        $this->_databaseManager->executeQuery($Q);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $task = new tasks();
                if ($row['vehicleid'] == 0) {
                    $task->vehicleno = $row["vehno"];
                } else {
                    $task->vehicleno = $row["vehicleno"];
                }
                $task->location = $row["location"];
                $task->timeslot = $row["timeslot"];
                if ($row['purposeid'] == 1) {
                    $task->purpose = "New Installation";
                }
                if ($row['purposeid'] == 2) {
                    $task->purpose = "Repair";
                }
                if ($row['purposeid'] == 3) {
                    $task->purpose = "Removal";
                }
                if ($row['purposeid'] == 4) {
                    $task->purpose = "Replacement";
                }
                $task->customercompany = $row["customercompany"];
                $task->person_name = $row["person_name"];
                $task->cp_phone1 = $row["cp_phone1"];
                $task->fe_id = $row["fe_id"];
                $task->phone = $row["phone"];
                $task->name = $row['name'];
                $task->unitno = $row['unitno'];
                $task->simcardno = $row['simcardno'];
                $task->bucketid = $row['bucketid'];
                if ($row['status'] == 2) {
                    $task->status = "Successful";
                }
                if ($row['status'] == 3) {
                    $task->status = "Unsuccessful";
                }
                if ($row['status'] == 5) {
                    $task->status = "Cancelled";
                }
                if ($row["is_compliance"] == 1) {
                    $task->is_compliance = "Compliant";
                }
                if ($row["is_compliance"] == 2) {
                    $task->is_compliance = "Not Compliant";
                }
                $task->reason = $row['reason'];
                $task->remarks = $row['remarks'];
                $task->customerno = $row['customerno'];
                $tasks[] = $task;
            }
            return $tasks;
        }
        return null;
    }

    public function get_compliance_percentage() {
        $apt_date = date('Y-m-d', strtotime("- 1 day"));
        $x = 0;
        $y = 0;
        $tasks = array();
        $Q = "SELECT bucketid, is_compliance
              FROM" . DB_PARENT . ".bucket b
                WHERE b.apt_date = '" . $apt_date . "' AND b.status IN (2,3,5)";
        $this->_databaseManager->executeQuery($Q);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row['is_compliance'] == 1) {
                    $x++;
                }
                $y++;
            }
            return round($x / $y * 100);
        }
        return null;
    }

    public function get_compliance_percentage_month() {
        $apt_date = date('m', strtotime("- 1 day"));
        $x = 0;
        $y = 0;
        $tasks = array();
        $Q = "SELECT bucketid, is_compliance
              FROM" . DB_PARENT . ".bucket b
                WHERE MONTH(b.apt_date) = '" . $apt_date . "' AND b.status IN (2,3,5)";
        $this->_databaseManager->executeQuery($Q);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row['is_compliance'] == 1) {
                    $x++;
                }
                $y++;
            }
            return round($x / $y * 100);
        }
        return null;
    }

    public function get_next_day_allocation() {
        $apt_date = date('Y-m-d', strtotime("+ 1 day"));
        $tasks = array();
        $Q = "SELECT b.bucketid, b.customerno, b.vehicleid, v.vehicleno, b.vehicleno as vehno, b.location, sp.timeslot, b.purposeid, c.customercompany, cp.person_name,
        cp.cp_phone1, b.fe_id, te.phone, te.name,u.unitno, s.simcardno
              FROM" . DB_PARENT . ".bucket b
                INNER JOIN " . DB_PARENT . ".customer c ON c.customerno = b.customerno
                INNER JOIN " . DB_PARENT . ".sp_timeslot sp ON sp.tsid = b.timeslotid
                LEFT OUTER JOIN " . DB_PARENT . ".contactperson_details cp ON cp.cpdetailid = b.coordinatorid
                LEFT OUTER JOIN " . DB_PARENT . ".team te ON te.teamid = b.fe_id
                LEFT OUTER JOIN vehicle v ON v.vehicleid = b.vehicleid
                LEFT OUTER JOIN simcard s ON s.id = b.simcardid
                LEFT OUTER JOIN unit u ON u.uid = b.unitid
                WHERE b.apt_date = '" . $apt_date . "' AND b.status = 4";
        $this->_databaseManager->executeQuery($Q);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $task = new tasks();
                if ($row['vehicleid'] == 0) {
                    $task->vehicleno = $row["vehno"];
                } else {
                    $task->vehicleno = $row["vehicleno"];
                }
                $task->location = $row["location"];
                $task->timeslot = $row["timeslot"];
                $task->customerno = $row["customerno"];
                if ($row['purposeid'] == 1) {
                    $task->purpose = "New Installation";
                }
                if ($row['purposeid'] == 2) {
                    $task->purpose = "Repair";
                }
                if ($row['purposeid'] == 3) {
                    $task->purpose = "Removal";
                }
                if ($row['purposeid'] == 4) {
                    $task->purpose = "Replacement";
                }
                $task->customercompany = $row["customercompany"];
                $task->person_name = $row["person_name"];
                $task->cp_phone1 = $row["cp_phone1"];
                $task->fe_id = $row["fe_id"];
                $task->phone = $row["phone"];
                $task->name = $row['name'];
                $task->unitno = $row['unitno'];
                $task->simcardno = $row['simcardno'];
                $task->bucketid = $row['bucketid'];
                $tasks[] = $task;
            }
            return $tasks;
        }
        return null;
    }

    public function get_next_day_bucket() {
        $apt_date = date('Y-m-d', strtotime("+ 1 day"));
        $tasks = array();
        $Q = "SELECT b.bucketid, b.customerno, b.vehicleid, v.vehicleno, b.vehicleno as vehno, b.location, sp.timeslot, b.purposeid, c.customercompany, cp.person_name,
                        cp.cp_phone1, b.priority
              FROM" . DB_PARENT . ".bucket b
                INNER JOIN " . DB_PARENT . ".customer c ON c.customerno = b.customerno
                INNER JOIN " . DB_PARENT . ".sp_timeslot sp ON sp.tsid = b.timeslotid
                LEFT OUTER JOIN " . DB_PARENT . ".contactperson_details cp ON cp.cpdetailid = b.coordinatorid
                LEFT OUTER JOIN vehicle v ON v.vehicleid = b.vehicleid
                WHERE b.apt_date = '" . $apt_date . "'";
        $this->_databaseManager->executeQuery($Q);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $task = new tasks();
                $task->customerno = $row["customerno"];
                if ($row['vehicleid'] == 0) {
                    $task->vehicleno = $row["vehno"];
                } else {
                    $task->vehicleno = $row["vehicleno"];
                }
                if ($row['priority'] == 1) {
                    $task->priority = "High";
                }
                if ($row['priority'] == 2) {
                    $task->priority = "Medium";
                }
                if ($row['priority'] == 3) {
                    $task->priority = "Low";
                }
                $task->location = $row["location"];
                $task->timeslot = $row["timeslot"];
                if ($row['purposeid'] == 1) {
                    $task->purpose = "New Installation";
                }
                if ($row['purposeid'] == 2) {
                    $task->purpose = "Repair";
                }
                if ($row['purposeid'] == 3) {
                    $task->purpose = "Removal";
                }
                if ($row['purposeid'] == 4) {
                    $task->purpose = "Replacement";
                }
                $task->customercompany = $row["customercompany"];
                $task->person_name = $row["person_name"];
                $task->cp_phone1 = $row["cp_phone1"];
                $task->bucketid = $row['bucketid'];
                $tasks[] = $task;
            }
            return $tasks;
        }
        return null;
    }

    public function get_sms_details() {
        $devices = array();
        $Query = "SELECT count(*) as teamcount, team.teamid, team.name, team.phone FROM `unit` INNER JOIN " . DB_PARENT . ".team ON team.teamid = unit.teamid
        WHERE unit.teamid <> 0 GROUP BY unit.teamid";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->count = $row["teamcount"];
                $device->teamid = $row["teamid"];
                $device->phone = $row["phone"];
                $device->name = $row["name"];
                $devices[] = $device;
            }
            return $devices;
        }
        return null;
    }

    public function get_sms_details_sim() {
        $devices = array();
        $Query = "SELECT count(*) as teamcount, team.teamid, team.name, team.phone FROM `simcard` INNER JOIN " . DB_PARENT . ".team ON team.teamid = simcard.teamid
        WHERE simcard.teamid <> 0 GROUP BY simcard.teamid";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->count = $row["teamcount"];
                $device->teamid = $row["teamid"];
                $device->phone = $row["phone"];
                $device->name = $row["name"];
                $devices[] = $device;
            }
            return $devices;
        }
        return null;
    }

    public function get_sms_unit_details($teamid) {
        $devices = "";
        $Query = "SELECT unitno FROM `unit` WHERE teamid = %d ";
        $devicesQuery = sprintf($Query, Sanitise::Long($teamid));
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($devices == "") {
                    $devices = $row["unitno"];
                } else {
                    $devices .= ", " . $row["unitno"];
                }
            }
            return $devices;
        }
        return null;
    }

    public function get_sms_sim_details($teamid) {
        $devices = "";
        $Query = "SELECT simcardno FROM `simcard` WHERE teamid = %d ";
        $devicesQuery = sprintf($Query, Sanitise::Long($teamid));
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($devices == "") {
                    $devices = $row["simcardno"];
                } else {
                    $devices .= ", " . $row["simcardno"];
                }
            }
            return $devices;
        }
        return null;
    }

    public function checkphoneno($phoneno) {
        $SQL = sprintf("SELECT customer_no FROM smstrack WHERE `phoneno` = '$phoneno'");
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customer = new VOCustomer();
                $customer->customer_no = $row["customer_no"];
            }
            return $customer;
        }
        return false;
    }

    public function checkphoneinuser($phoneno) {
        $SQL = sprintf("SELECT customerno FROM user WHERE `phone` = '$phoneno'");
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customer = new VOCustomer();
                $customer->customerno = $row["customerno"];
            }
            return $customer;
        }
        return false;
    }

    public function getalldevicesforignition() {
        $devices = array();

        $time = date("Y-m-d H:i:s");

        //$time = "2013-12-30 18:10:00";
        /*
        $Query = "SELECT
        ia.ignchgtime, ia.count, ia.last_check, ia.last_status, ia.status AS email_status
        , ia.ignontime,ia.prev_odometer_reading,ia.prev_odometer_time, ia.running_count, ia.idleignon_count
        , d.deviceid, d.devicelat, d.devicelong, d.status,d.ignition ,d.lastupdated, d.customerno, d.uid
        ,v.vehicleid, v.vehicleno ,v.odometer
        FROM devices d
        INNER JOIN unit u ON u.uid = d.uid
        INNER JOIN vehicle v on v.vehicleid = u.vehicleid
        INNER JOIN ignitionalert ia ON ia.vehicleid = v.vehicleid
        where u.trans_statusid NOT IN (10,22)
        AND ((d.lastupdated >= '" . $time . "') || (d.lastupdated >= '" . $cairo_diff . "' AND d.customerno = " . speedConstants::CUSTNO_MONGINISCAIRO . "))";
         */

        $Query = "SELECT ia.ignchgtime, ia.count, ia.last_check, ia.last_status, ia.status AS email_status , ia.ignontime,ia.prev_odometer_reading,ia.prev_odometer_time, ia.running_count, ia.idleignon_count , d.deviceid, d.devicelat, d.devicelong, d.status,d.ignition ,d.lastupdated, d.customerno, d.uid ,v.vehicleid, v.vehicleno ,v.odometer, u.is_freeze,c.timezone,tz.timediff,
		    DATE_ADD('" . $time . "', INTERVAL tz.timediff SECOND) as ctTimeZoneTimestamp
            FROM unit u
            INNER JOIN devices d ON u.uid = d.uid
            INNER JOIN vehicle v on v.vehicleid = u.vehicleid
            INNER JOIN customer c on c.customerno = u.customerno
            INNER JOIN timezone tz on tz.tid = c.timezone
            INNER JOIN ignitionalert ia ON ia.vehicleid = v.vehicleid
            WHERE u.customerno NOT IN(1)
            and u.trans_statusid NOT IN (10,22)
            AND v.kind <> 'Warehouse'
            AND v.isdeleted = 0
            AND u.unitno NOT LIKE 'D%'
            AND ( d.lastupdated >= DATE_ADD(DATE_ADD('" . $time . "', INTERVAL tz.timediff SECOND), INTERVAL -60 SECOND) )";
        $devicesQuery = $Query;
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row['uid'] > 0) {
                    $device = new VODevices();
                    $device->deviceid = $row['deviceid'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->ignition_count = $row['count'];
                    $device->ignchgtime = $row['ignchgtime'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->ignition_last_check = $row['last_check'];
                    $device->ignition_last_status = $row['last_status'];
                    $device->ignition = $row['ignition'];
                    $device->ignition_email_status = $row['email_status'];
                    $device->status = $row['status'];
                    $device->customerno = $row['customerno'];
                    $device->uid = $row['uid'];
                    $device->ignontime = $row['ignontime'];
                    $device->prev_odometer_reading = $row['prev_odometer_reading'];
                    $device->prev_odometer_time = $row['prev_odometer_time'];
                    $device->odometer = $row['odometer'];
                    $device->running_count = $row['running_count'];
                    $device->idleignon_count = $row['idleignon_count'];
                    $device->isfreeze = $row['is_freeze'];
                    $device->ctTimeZoneTimestamp = $row['ctTimeZoneTimestamp'];
                    $devices[] = $device;
                }
            }
            return $devices;
        }
        return NULL;
    }

    /* Function written by Ranjeet for fetching all devices of warehouse starts here */
    public function getAllDevicesForWarehouseIgnition() {
        $devices = array();
        $time = date(speedConstants::DEFAULT_TIMESTAMP, strtotime("-60 seconds"));

        $date1 = new DateTime();
        $date1->setTimezone(new DateTimeZone(speedConstants::CAIRO_TIMEZONE));
        $cairo_dt = $date1->format(speedConstants::DEFAULT_TIMESTAMP);
        $cairo_diff = date(speedConstants::DEFAULT_TIMESTAMP, strtotime("-60 seconds", strtotime($cairo_dt)));

        $Query = "SELECT ia.ignchgtime, ia.count, ia.last_check, ia.last_status, ia.status AS email_status , ia.ignontime,ia.prev_odometer_reading,ia.prev_odometer_time, ia.running_count, ia.idleignon_count , d.deviceid, d.devicelat, d.devicelong, d.status,d.ignition ,d.lastupdated, d.customerno, d.uid ,v.vehicleid, v.vehicleno ,v.odometer, u.is_freeze
            FROM unit u
            INNER JOIN devices d ON u.uid = d.uid
            INNER JOIN vehicle v on v.vehicleid = u.vehicleid
            INNER JOIN ignitionalert ia ON ia.vehicleid = v.vehicleid
            WHERE u.customerno NOT IN(1)
            and u.trans_statusid NOT IN (10,22)
            AND v.kind = 'Warehouse'
            AND v.isdeleted = 0
            AND u.unitno NOT LIKE 'D%'
            /*AND ((d.lastupdated >= '" . $time . "') || (d.lastupdated >= '" . $cairo_diff . "' AND d.customerno = " . speedConstants::CUSTNO_MONGINISCAIRO . "))*/";
        $devicesQuery = $Query;
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row['uid'] > 0) {
                    $device = new VODevices();
                    $device->deviceid = $row['deviceid'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->ignition_count = $row['count'];
                    $device->ignchgtime = $row['ignchgtime'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->ignition_last_check = $row['last_check'];
                    $device->ignition_last_status = $row['last_status'];
                    $device->ignition = $row['ignition'];
                    $device->ignition_email_status = $row['email_status'];
                    $device->status = $row['status'];
                    $device->customerno = $row['customerno'];
                    $device->uid = $row['uid'];
                    $device->ignontime = $row['ignontime'];
                    $device->prev_odometer_reading = $row['prev_odometer_reading'];
                    $device->prev_odometer_time = $row['prev_odometer_time'];
                    $device->odometer = $row['odometer'];
                    $device->running_count = $row['running_count'];
                    $device->idleignon_count = $row['idleignon_count'];
                    $device->isfreeze = $row['is_freeze'];
                    $devices[] = $device;
                }
            }
            return $devices;
        }
        return NULL;
    }

    /* Function written by Ranjeet for fetching all devices of warehouse ends here */
    public function getdevicedataforignition($vehicleid, $customerno) {
        $Query = "select `status`,`ignchgtime` from `ignitionalert` where `vehicleid` = %d AND `customerno` = %d";
        $devicesQuery = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->status = $row['status'];
                $device->ignition_chg_time = $row['ignchgtime'];
            }
            return $device;
        }
        return NULL;
    }

    public function getNameForTempCron($nid, $customerno) {
        $Query = "Select * from nomens where nid=%d and customerno=%d";
        $SQL = sprintf($Query, $nid, $customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row['name'];
            }
        }
        return null;
    }

    public function getalldevicesfortempsensor() {
        $devices = array();
        $time = date("Y-m-d H:i:s", strtotime("-120 seconds"));
        $Query = "select    d.deviceid
                            , d.devicelat
                            , d.devicelong
                            , ea.temp
                            , ea.temp2
                            , ea.temp3
                            , ea.temp4
                            , v.vehicleid
                            , v.vehicleno
                            , d.status
                            , c.temp_sensors
                            , c.customerno
                            , u.uid
                            , atum.temp1_intv
                            , atum.temp2_intv
                            , atum.temp3_intv
                            , atum.temp4_intv
                            , d.lastupdated
                            , u.tempsen1
                            , u.tempsen2
                            , u.tempsen3
                            , u.tempsen4
                            , u.n1
                            , u.n2
                            , u.n3
                            , u.n4
                            , u.analog1
                            , u.analog2
                            , u.analog3
                            , u.analog4
                            , v.temp1_min
                            , v.temp1_max
                            , v.temp2_min
                            , v.temp2_max
                            , v.temp3_min
                            , v.temp3_max
                            , v.temp4_min
                            , v.temp4_max
                            , v.temp1_mute
                            , v.temp2_mute
                            , v.temp3_mute
                            , v.temp4_mute
                            , u.get_conversion
                            , c.use_humidity
                            , v.kind
                            , atum.userid
                    FROM    devices d
                    INNER JOIN unit u ON u.uid = d.uid
                    INNER JOIN " . DB_PARENT . ".customer c ON c.customerno = d.customerno
                    INNER JOIN vehicle v on v.vehicleid = u.vehicleid
                    INNER JOIN eventalerts ea ON ea.vehicleid = v.vehicleid
                    INNER JOIN alertTempUserMapping atum ON atum.uid = u.uid
                    where   u.trans_statusid NOT IN (10,22)
                    AND     c.temp_sensors > 0
                    AND     d.customerno NOT IN (1)
                    AND     atum.isdeleted = 0
                    AND     d.lastupdated >= '$time'";
        $devicesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->uid = $row['uid'];
                $device->deviceid = $row['deviceid'];
                $device->devicelat = $row['devicelat'];
                $device->devicelong = $row['devicelong'];
                $device->vehicleid = $row['vehicleid'];
                $device->vehicleno = $row['vehicleno'];
                $device->lastupdated = $row['lastupdated'];
                $device->status = $row['status'];
                $device->tempsen1 = $row['tempsen1'];
                $device->tempsen2 = $row['tempsen2'];
                $device->tempsen3 = $row['tempsen3'];
                $device->tempsen4 = $row['tempsen4'];
                $device->analog1 = $row['analog1'];
                $device->analog2 = $row['analog2'];
                $device->analog3 = $row['analog3'];
                $device->analog4 = $row['analog4'];
                $device->n1 = $row['n1'];
                $device->n2 = $row['n2'];
                $device->n3 = $row['n3'];
                $device->n4 = $row['n4'];
                $device->temp1_min = $row['temp1_min'];
                $device->temp1_max = $row['temp1_max'];
                $device->temp2_min = $row['temp2_min'];
                $device->temp2_max = $row['temp2_max'];
                $device->temp3_min = $row['temp3_min'];
                $device->temp3_max = $row['temp3_max'];
                $device->temp4_min = $row['temp4_min'];
                $device->temp4_max = $row['temp4_max'];
                $device->temp_sensors = $row['temp_sensors'];
                $device->temp_status = $row['temp'];
                $device->temp2_status = $row['temp2'];
                $device->temp3_status = $row['temp3'];
                $device->temp4_status = $row['temp4'];
                $device->temp1_intv = $row['temp1_intv'];
                $device->temp2_intv = $row['temp2_intv'];
                $device->temp3_intv = $row['temp3_intv'];
                $device->temp4_intv = $row['temp4_intv'];
                $device->customerno = $row['customerno'];
                $device->kind = $row['kind'];
                $device->get_conversion = $row['get_conversion'];
                $device->use_humidity = $row['use_humidity'];
                $device->temp1_mute = $row['temp1_mute'];
                $device->temp2_mute = $row['temp2_mute'];
                $device->temp3_mute = $row['temp3_mute'];
                $device->temp4_mute = $row['temp4_mute'];
                $device->userid = $row['userid'];
                $devices[] = $device;
            }
            return $devices;
        }
        return NULL;
    }

    public function getalldevicesfortempsensor_fassos() {
        $devices = array();
        $time = date("Y-m-d H:i:s", strtotime("-60 seconds"));
        $Query = "select d.deviceid, d.devicelat, d.devicelong, ea.temp, ea.temp2,
            v.vehicleid, v.vehicleno, d.status, c.temp_sensors, c.customerno, u.uid, u.temp1_intv, u.temp2_intv,
            d.lastupdated, u.tempsen1, u.tempsen2, u.analog1, u.analog2, u.analog3, u.analog4, v.temp1_min, v.temp1_max,v.temp2_min, v.temp2_max,
            u.get_conversion, c.use_humidity, v.kind
            FROM devices d
            INNER JOIN unit u ON u.uid = d.uid
            INNER JOIN " . DB_PARENT . ".customer c ON c.customerno = d.customerno
            INNER JOIN vehicle v on v.vehicleid = u.vehicleid
            INNER JOIN eventalerts ea ON ea.vehicleid = v.vehicleid

            where u.trans_statusid NOT IN (10,22) AND c.temp_sensors > 0 and d.customerno = 177 ";
        // AND d.lastupdated >= '$time'
        $devicesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->uid = $row['uid'];
                $device->deviceid = $row['deviceid'];
                $device->devicelat = $row['devicelat'];
                $device->devicelong = $row['devicelong'];
                $device->vehicleid = $row['vehicleid'];
                $device->vehicleno = $row['vehicleno'];
                $device->lastupdated = $row['lastupdated'];
                $device->status = $row['status'];
                $device->tempsen1 = $row['tempsen1'];
                $device->tempsen2 = $row['tempsen2'];
                $device->analog1 = $row['analog1'];
                $device->analog2 = $row['analog2'];
                $device->analog3 = $row['analog3'];
                $device->analog4 = $row['analog4'];
                $device->temp1_min = $row['temp1_min'];
                $device->temp1_max = $row['temp1_max'];
                $device->temp2_min = $row['temp2_min'];
                $device->temp2_max = $row['temp2_max'];
                $device->temp_sensors = $row['temp_sensors'];
                $device->temp_status = $row['temp'];
                $device->temp2_status = $row['temp2'];
                $device->temp1_intv = $row['temp1_intv'];
                $device->temp2_intv = $row['temp2_intv'];
                $device->customerno = $row['customerno'];
                $device->kind = $row['kind'];
                $device->get_conversion = $row['get_conversion'];
                $device->use_humidity = $row['use_humidity'];
                $devices[] = $device;
            }
            return $devices;
        }
        return NULL;
    }

    public function get_all_stoppage_users() {
        $users = array();
        $Query = "SELECT * FROM stoppage_alerts WHERE isdeleted = 0";
        $devicesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $user = new stdClass();
                $user->customerno = $row['customerno'];
                $user->userid = $row['userid'];
                $user->is_chk_sms = $row['is_chk_sms'];
                $user->is_chk_email = $row['is_chk_email'];
                $user->is_trans_email = $row['is_trans_email'];
                $user->is_trans_sms = $row['is_trans_sms'];
                $user->chkmins = $row['chkmins'];
                $user->transmins = $row['transmins'];
                $user->alert_sent = $row['alert_sent'];
                $user->vehicleid = $row['vehicleid'];
                $users[] = $user;
            }
            return $users;
        }
        return NULL;
    }

    /**
     * New function for stoppage alert
     * @return \stdClass
     */
    public function get_stoppage_user_veh_details() {
        $users = array();
        $Query = "SELECT a.customerno,a.userid, a.is_chk_sms,a.is_chk_email,a.is_trans_email,a.is_trans_sms,a.chkmins,a.transmins,a.alert_sent,a.vehicleid,
            v.vehicleno,v.odometer,v.stoppage_odometer,v.lastupdated,v.stoppage_flag,v.stoppage_transit_time,
            d.devicelat, d.devicelong
            FROM stoppage_alerts as a
            left join vehicle as v on v.vehicleid=a.vehicleid and v.isdeleted=0 AND v.uid != 0
            left JOIN devices as d ON d.uid = v.uid
            WHERE a.isdeleted = 0 AND a.alert_sent = 0 AND v.stoppage_flag = 0";
        $devicesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $user = new stdClass();
                $user->customerno = $row['customerno'];
                $user->userid = $row['userid'];
                $user->is_chk_sms = $row['is_chk_sms'];
                $user->is_chk_email = $row['is_chk_email'];
                $user->is_trans_email = $row['is_trans_email'];
                $user->is_trans_sms = $row['is_trans_sms'];
                $user->chkmins = $row['chkmins'];
                $user->transmins = $row['transmins'];
                $user->alert_sent = $row['alert_sent'];
                $user->vehicleid = $row['vehicleid'];
                $user->vehicleno = $row['vehicleno'];
                $user->odometer = $row['odometer'];
                $user->stoppage_odometer = $row['stoppage_odometer'];
                $user->lastupdated = $row['lastupdated'];
                $user->stoppage_flag = $row['stoppage_flag'];
                $user->stoppage_transit_time = $row['stoppage_transit_time'];
                $user->devicelat = $row['devicelat'];
                $user->devicelong = $row['devicelong'];
                $users[] = $user;
            }
            return $users;
        }
        return NULL;
    }

    /* Stoppage Alert Correction */

    public function get_stoppage_user() {
        $users = array();
        $Query = "SELECT DISTINCT(sa.userid), sa.customerno, sa.is_chk_sms, sa.is_chk_email, sa.is_trans_email, sa.is_trans_sms, sa.chkmins, sa.transmins,
            sa.alert_sent, sa.customerno
            FROM stoppage_alerts as sa
            INNER JOIN user u on u.userid = sa.userid
            WHERE sa.isdeleted = 0 AND sa.alert_sent = 0 AND u.isdeleted=0";
        $devicesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $user = new stdClass();
                $user->userid = $row['userid'];
                $user->customerno = $row['customerno'];
                $user->is_chk_sms = $row['is_chk_sms'];
                $user->is_chk_email = $row['is_chk_email'];
                $user->is_trans_email = $row['is_trans_email'];
                $user->is_trans_sms = $row['is_trans_sms'];
                $user->chkmins = $row['chkmins'];
                $user->transmins = $row['transmins'];
                $user->alert_sent = $row['alert_sent'];
                $users[] = $user;
            }
            return $users;
        }
        return NULL;
    }

    public function getCheckPointWiseUsers() {
        $users = [];
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sp_params = "";
        /* $sp_params = "'" . $request->customerList . "'"
        .",'" . $request->todaysdate . "'"; */
        $queryCallSP = $this->PrepareSP(speedConstants::SP_FETCH_CHECKPOINTWISE_USER, $sp_params);
        $dataSet = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        if (count($dataSet) > 0) {
            foreach ($dataSet as $key => $value) {
                $user = new stdClass();
                $user->stoppage_alert_checkpoint = $value['stoppage_alert_checkpoint'];
                $user->is_chk_sms = $value['is_chk_sms'];
                $user->is_trans_sms = $value['is_trans_sms'];
                $user->is_chk_email = $value['is_chk_email'];
                $user->chkmins = $value['chkmins'];
                $user->transmins = $value['transmins'];
                $user->vehicleid = $value['vehicleid'];
                $user->customerno = $value['customerno'];
                $user->userid = $value['userid'];
                $users[] = $user;
            }
            return $users;
        } else {
            return NULL;
        }
        //echo"Data with query count is:".count($dataSet)."<pre>"; print_r((object)$dataSet); exit();
    }

    public function get_all_details($userid) {
        $Query = "SELECT * FROM stoppage_alerts INNER JOIN user ON user.userid = stoppage_alerts.userid WHERE stoppage_alerts.userid = %d AND stoppage_alerts.isdeleted = 0 AND user.isdeleted=0";
        $devicesQuery = sprintf($Query, Sanitise::Long($userid));
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $user = new VOUser();
                $user->is_chk_sms = $row['is_chk_sms'];
                $user->is_chk_email = $row['is_chk_email'];
                $user->is_chk_telephone = $row['is_chk_telephone'];
                $user->is_trans_email = $row['is_trans_email'];
                $user->is_trans_sms = $row['is_trans_sms'];
                $user->is_trans_telephone = $row['is_trans_telephone'];
                $user->start_alert_time = $row['start_alert'];
                $user->stop_alert_time = $row['stop_alert'];
                $user->email = $row['email'];
                $user->phone = $row['phone'];
                $user->userkey = $row['userkey'];
                $user->notification_status = $row['notification_status'];
                $user->speed_mobilenotification = $row['speed_mobilenotification'];
                $user->power_mobilenotification = $row['power_mobilenotification'];
                $user->tamper_mobilenotification = $row['tamper_mobilenotification'];
                $user->chk_mobilenotification = $row['chk_mobilenotification'];
                $user->ignition_mobilenotification = $row['ignition_mobilenotification'];
                $user->aci_mobilenotification = $row['aci_mobilenotification'];
                $user->temp_mobilenotification = $row['temp_mobilenotification'];
                $user->panic_mobilenotification = $row['panic_mobilenotification'];
                $user->immob_mobilenotification = $row['immob_mobilenotification'];
                $user->door_mobilenotification = $row['door_mobilenotification'];
                $user->harsh_break_mobilenotification = $row['harsh_break_mobilenotification'];
                $user->high_acce_mobilenotification = $row['high_acce_mobilenotification'];
                $user->sharp_turn_mobilenotification = $row['sharp_turn_mobilenotification'];
                $user->fuel_alert_mobilenotification = $row['fuel_alert_mobilenotification'];
                $user->gcmid = $row['gcmid'];
                return $user;
            }
        }
        return NULL;
    }

    public function getunfilteredusersforcustomer($customerid) {
        $users = array();
        $usersQuery = sprintf("SELECT role,email FROM  `user`  where customerno=%d AND isdeleted=0", Validator::escapeCharacters($customerid));
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row["role"] != "Administrator") {
                    $user = new VOUser();
                    $user->email = $row['email'];
                    $users[] = $user;
                }
            }
            return $users;
        }
        return null;
    }

    public function get_grouped_vehicles($userid, $customerno) {
        $vehicles = array();
        $isgroup = false;
        $Query = "SELECT groupid FROM groupman WHERE customerno = %d AND userid = %d";
        $devicesQuery = sprintf($Query, Sanitise::Long($customerno), Sanitise::Long($userid));
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $isgroup = true;
        }
        if ($isgroup == true) {
            $Query = "SELECT vehicle.stoppage_flag, vehicle.vehicleid, vehicle.vehicleno, vehicle.odometer, vehicle.stoppage_odometer,
                vehicle.lastupdated, vehicle.stoppage_transit_time, devices.devicelat, devices.devicelong FROM vehicle
                INNER JOIN devices ON devices.uid = vehicle.uid
                INNER JOIN groupman ON vehicle.groupid = groupman.groupid
                WHERE vehicle.isdeleted = 0 AND vehicle.customerno = %d AND groupman.userid = %d and groupman.isdeleted = 0 ";
            $devicesQuery = sprintf($Query, Sanitise::Long($customerno), Sanitise::Long($userid));
        } else {
            $Query = "SELECT vehicle.stoppage_flag, vehicle.vehicleid, vehicle.vehicleno, vehicle.odometer, vehicle.stoppage_odometer,
                vehicle.lastupdated, vehicle.stoppage_transit_time, devices.devicelat, devices.devicelong FROM vehicle
                INNER JOIN devices ON devices.uid = vehicle.uid
                WHERE vehicle.isdeleted = 0 AND vehicle.customerno = %d";
            $devicesQuery = sprintf($Query, Sanitise::Long($customerno));
        }
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->odometer = $row['odometer'];
                $vehicle->stoppage_odometer = $row['stoppage_odometer'];
                $vehicle->lastupdated = $row['lastupdated'];
                $vehicle->devicelat = $row['devicelat'];
                $vehicle->devicelong = $row['devicelong'];
                $vehicle->stoppage_flag = $row['stoppage_flag'];
                $vehicle->stoppage_transit_time = $row['stoppage_transit_time'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return NULL;
    }

    public function get_chkptdet($vehicleid, $customerno) {
        $checkpoints = array();
        $Query = "SELECT checkpointmanage.checkpointid, checkpointmanage.conflictstatus, checkpoint.cname FROM checkpointmanage
        INNER JOIN checkpoint ON checkpoint.checkpointid = checkpointmanage.checkpointid
        WHERE checkpointmanage.vehicleid = %d AND checkpointmanage.isdeleted = 0 AND  checkpointmanage.customerno = %d
        ORDER BY checkpointmanage.timestamp DESC LIMIT 1";
        $devicesQuery = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $chkpt = new VOCheckpoint();
                $chkpt->checkpointid = $row['checkpointid'];
                $chkpt->cname = $row['cname'];
                $chkpt->conflictstatus = $row['conflictstatus'];
                $checkpoints[] = $chkpt;
                //return $chkpt;
            }
        }
        return $checkpoints;
    }

    public function pull_chk_mysql_data($chkid, $customerno) {
        $Query = "select checkpoint.cgeolat, checkpoint.cgeolong,checkpoint.crad,checkpoint.cname from checkpoint
            where checkpoint.isdeleted =0 AND checkpoint.checkpointid = %d AND checkpoint.customerno = %d";
        $devicesQuery = sprintf($Query, $chkid, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VOCheckpoint();
                $checkpoint->cname = $row['cname'];
                $checkpoint->cgeolong = $row['cgeolong'];
                $checkpoint->cgeolat = $row['cgeolat'];
                $checkpoint->crad = $row['crad'];
            }
            return $checkpoint;
        }
        return NULL;
    }

    public function pull_chk_mysql_data_royal($chkid, $customerno, $is_chkid) {
        $Query = "select checkpoint.cgeolat, checkpoint.cgeolong,checkpoint.crad from checkpoint
            where checkpoint.isdeleted =0 AND checkpoint.checkpointid = %d AND checkpoint.customerno = %d and checkpointid in(" . $is_chkid . ")";
        $devicesQuery = sprintf($Query, $chkid, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VOCheckpoint();
                $checkpoint->cgeolong = $row['cgeolong'];
                $checkpoint->cgeolat = $row['cgeolat'];
                $checkpoint->crad = $row['crad'];
            }
            return $checkpoint;
        }
        return NULL;
    }

    public function get_all_checkpoints($customerno) {
        $Query = "select checkpoint.cgeolat,checkpoint.cgeolong,checkpoint.cname,
            checkpoint.crad,checkpoint.checkpointid,checkpoint.chktype from checkpoint
            where customerno = %d AND checkpoint.isdeleted =0 ";
        $devicesQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->cname = $row['cname'];
                $device->cgeolat = $row['cgeolat'];
                $device->cgeolong = $row['cgeolong'];
                $device->crad = $row['crad'];
                $device->chkid = $row['checkpointid'];
                $device->chktype = $row['chktype'];
                $devices[] = $device;
            }
            return $devices;
        }
        return NULL;
    }

    public function get_all_checkpoints_royal($customerno, $is_chkid) {
        $Query = "select checkpoint.cgeolat,checkpoint.cgeolong,
            checkpoint.crad,checkpoint.checkpointid,checkpoint.chktype from checkpoint
            where customerno = %d AND checkpoint.isdeleted =0 and checkpointid in(" . $is_chkid . ")";
        $devicesQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->cgeolat = $row['cgeolat'];
                $device->cgeolong = $row['cgeolong'];
                $device->crad = $row['crad'];
                $device->chkid = $row['checkpointid'];
                $device->chktype = $row['chktype'];
                $devices[] = $device;
            }
            return $devices;
        }
        return NULL;
    }

    public function getalldeviceswithchkpforcron($customerExceptionList = NULL, $customerList = NULL) {
        $devices = array();
        $time = date("Y-m-d H:i:s", strtotime("-60 seconds"));
        $Query = "select devices.deviceid,devices.devicelat,devices.devicelong,
            vehicle.vehicleid,vehicle.vehicleno,vehicle.checkpointId AS vehChkPtId,checkpoint.cgeolat,checkpoint.cgeolong,
            checkpoint.cname,checkpointmanage.cmid,checkpoint.crad,
            devices.customerno, devices.uid, checkpoint.checkpointid, devices.lastupdated,
            checkpoint.phoneno,checkpoint.email,checkpoint.isSms,checkpoint.isEmail,checkpoint.eta
            , checkpointmanage.conflictstatus, checkpointmanage.inTime, checkpointmanage.outTime, checkpointmanage.isDelayExpected, vehicle.routeDirection
            , checkpoint.chktype,checkpointmanage.isErp,user.userkey
            from devices
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON unit.vehicleid = vehicle.vehicleid
            INNER JOIN checkpointmanage ON checkpointmanage.vehicleid = vehicle.vehicleid
            INNER JOIN checkpoint ON checkpoint.checkpointid = checkpointmanage.checkpointid
            INNER JOIN user ON user.customerno = checkpoint.customerno
            where checkpoint.isdeleted =0
            AND checkpoint.checkPointCategory=1
            AND checkpointmanage.isdeleted = 0
            AND unit.trans_statusid NOT IN (10,22)";
        if (isset($customerList)) {
            $Query .= " AND checkpoint.customerno IN (" . $customerList . ") ";
        }
        if (isset($customerExceptionList)) {
            $Query .= " AND checkpoint.customerno NOT IN (" . $customerExceptionList . ") ";
        }

        $Query .= " AND devices.lastupdated >= '$time' "
            . " ORDER BY checkpointmanage.conflictstatus ASC";
        //$Query .= " AND devices.customerno = 745"

        $devicesQuery = sprintf($Query); //echo"query is: ".$devicesQuery; exit();
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row['uid'] > 0) {
                    $device = new stdClass();
                    $device->deviceid = $row['deviceid'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->vehChkPtId = $row['vehChkPtId'];
                    $device->customerno = $row['customerno'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->cgeolat = $row['cgeolat'];
                    $device->cgeolong = $row['cgeolong'];
                    $device->conflictstatus = $row['conflictstatus'];
                    $device->checkpointid = $row['checkpointid'];
                    $device->cname = $row['cname'];
                    $device->cmid = $row['cmid'];
                    $device->crad = $row['crad'];
                    $device->phoneno = $row['phoneno'];
                    $device->email = $row['email'];
                    $device->isSms = $row['isSms'];
                    $device->isEmail = $row['isEmail'];
                    $device->eta = $row['eta'];
                    $device->inTime = $row['inTime'];
                    $device->outTime = $row['outTime'];
                    $device->isDelayExpected = $row['isDelayExpected'];
                    $device->routeDirection = $row['routeDirection'];
                    $device->chktype = $row['chktype'];
                    /** Added By Pratik Raut */
                    $device->isErp = $row['isErp'];
                    $device->userkey = $row['userkey'];
                    /** Added By Pratik Raut */
                    $devices[] = $device;
                }
            }
            return $devices;
        }
        return NULL;
    }

    /* Following function is written for fetching all devices for checkpoint wise stoppage alerts */
    public function getAllDevicesForCheckPointWiseAlerts(string $customerExceptionList = NULL, string $customerList = NULL) {
        $devices = array();
        $time = date("Y-m-d H:i:s", strtotime("-60 seconds"));
        $Query = "SELECT
            devices.deviceid,
            devices.devicelat,
            devices.devicelong,
            vehicle.vehicleid,
            vehicle.vehicleno,
            vehicle.checkpointId AS vehChkPtId,
            checkpoint.cgeolat,
            checkpoint.cgeolong,
            checkpoint.cname,
            checkpointwise_stoppage_alerts.id AS csaid,
            checkpointwise_stoppage_alerts.stoppageTimeInMinutes as checkPointStoppageTime,
            checkpoint.crad,
            devices.customerno,
            devices.uid,
            checkpoint.checkpointid,
            devices.lastupdated,
            checkpoint.phoneno,
            checkpoint.email,
            checkpoint.isSms,
            checkpoint.isEmail,
            checkpoint.eta,
            vehicle.routeDirection,
            vehicle.chkpoint_status,
            TIMESTAMPDIFF(MINUTE,vehicle.stoppage_transit_time,vehicle.lastupdated) as stoppageInterval,
            checkpointwise_stoppage_alerts.customerNo,
            checkpoint.chktype
        FROM
            devices
                INNER JOIN
            unit ON unit.uid = devices.uid
                INNER JOIN
            vehicle ON unit.vehicleid = vehicle.vehicleid
                INNER JOIN
            checkpointwise_stoppage_alerts ON checkpointwise_stoppage_alerts.vehicleid = vehicle.vehicleid
                INNER JOIN
            checkpoint ON checkpoint.checkpointid = checkpointwise_stoppage_alerts.checkPointId
        WHERE
            checkpoint.isdeleted = 0
                AND unit.trans_statusid NOT IN (10 , 22)";
        if (isset($customerList)) {
            $Query .= " AND checkpoint.customerno IN (" . $customerList . ") ";
        }
        if (isset($customerExceptionList)) {
            $Query .= " AND checkpoint.customerno NOT IN (" . $customerExceptionList . ") ";
        }
        // Below first line is commented for development and testing purpose on local, it should be uncommented while pushing to UAT or Live server
        $Query .= " AND devices.lastupdated >= '$time'
                    AND checkpointwise_stoppage_alerts.isAlertSent=0";
        //$Query .= " AND checkpointwise_stoppage_alerts.isAlertSent=0";
        /* $Query .= " AND devices.lastupdated >= '$time' "
        . " ORDER BY checkpointmanage.conflictstatus ASC"; */
        $devicesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row['uid'] > 0) {
                    $device = new stdClass();
                    $device->deviceid = $row['deviceid'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->vehChkPtId = $row['vehChkPtId'];
                    $device->customerno = $row['customerno'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->cgeolat = $row['cgeolat'];
                    $device->cgeolong = $row['cgeolong'];
                    /* $device->conflictstatus = $row['conflictstatus']; */
                    $device->checkpointid = $row['checkpointid'];
                    $device->cname = $row['cname'];
                    $device->cmid = $row['csaid'];
                    $device->crad = $row['crad'];
                    $device->phoneno = $row['phoneno'];
                    $device->email = $row['email'];
                    $device->isSms = $row['isSms'];
                    $device->isEmail = $row['isEmail'];
                    $device->eta = $row['eta'];
                    /* $device->inTime = $row['inTime'];
                    $device->outTime = $row['outTime'];
                     */
                    $device->routeDirection = $row['routeDirection'];
                    $device->chktype = $row['chktype'];
                    $device->checkPointStoppageTime = $row['checkPointStoppageTime'];
                    $device->chkpoint_status = $row['chkpoint_status'];
                    $device->stoppageInterval = $row['stoppageInterval'];
                    $device->customerNo = $row['customerNo'];
                    $devices[] = $device;
                }
            }
            return json_decode(json_encode($devices), true);
        }
        return NULL;
    }

    /* Following function is written for updating isALertSent=0 in checkpointwise_stoppage_alert table */
    public function updateIsALertSentForCheckPointWiseStoppageAlerts($checkpointid, $vehicleid, $customerno) {
        $Query = "Update checkpointwise_stoppage_alerts Set `isAlertSent`= 0 WHERE checkPointId=%d AND customerNo=%d AND vehicleId=%d";
        $SQL = sprintf($Query, Sanitise::long($checkpointid), Sanitise::long($customerno), Sanitise::long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function checkintoroute($chkid, $customerno, $vehicleid) {
        $list = NULL;
        $chkpts = array();
        $Query = "SELECT * FROM routeman
                 inner join vehiclerouteman on routeman.routeid = vehiclerouteman.routeid
                 where vehiclerouteman.vehicleid=%d
                 and routeman.checkpointid=%d
                 and routeman.customerno=%d
                 and routeman.isdeleted=0
                 AND vehiclerouteman.isdeleted=0
                 group by rmid";
        $routeQuery = sprintf($Query, $vehicleid, $chkid, $customerno);
        $this->_databaseManager->executeQuery($routeQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $chkpt = new VOCheckpoint();
                $chkpt->rmid = $row['rmid'];
                $chkpt->routeid = $row['routeid'];
                $chkpt->checkpointid = $row['checkpointid'];
                $chkpt->sequence = $row['sequence'];
                $chkpt->customerno = $row['customerno'];
                $chkpts[] = $chkpt;
            }
            //return $chkpts;
            if (isset($chkpts) && !empty($chkpts)) {
                $Querychk = "SELECT routeman.routeid, routeman.rmid, routeman.checkpointid, routeman.sequence
                            , checkpoint.cname, checkpoint.cgeolat, checkpoint.cgeolong, checkpoint.eta
                            , checkpoint.phoneno, checkpoint.email, checkpoint.isSms, checkpoint.isEmail
                            FROM routeman
                            left outer join checkpoint on checkpoint.checkpointid = routeman.checkpointid
                            where routeman.routeid=%d and routeman.customerno=%d and routeman.isdeleted=0 ";

                foreach ($chkpts as $chk) {
                    if ($customerno == speedConstants::CUSTNO_RKFOODLANDS && $chk->sequence == 1) {
                        $Querychk .= "and routeman.sequence > " . $chk->sequence;
                    } else {
                        $Querychk .= "and routeman.sequence = " . ($chk->sequence + 1);
                    }
                    $SQL = sprintf($Querychk, $chk->routeid, $customerno);
                    $this->_databaseManager->executeQuery($SQL);
                    if ($this->_databaseManager->get_rowCount() > 0) {
                        while ($row = $this->_databaseManager->get_nextRow()) {
                            $ch = new VOCheckpoint();
                            $ch->checkpintid = $row['checkpointid'];
                            $ch->routeid = $row['routeid'];
                            $ch->rmid = $row['rmid'];
                            $ch->cname = $row['cname'];
                            $ch->cgeolat = $row['cgeolat'];
                            $ch->cgeolong = $row['cgeolong'];
                            $ch->phoneno = $row['phoneno'];
                            $ch->email = $row['email'];
                            $ch->isSms = $row['isSms'];
                            $ch->isEmail = $row['isEmail'];
                            $ch->sequence = $row['sequence'];
                            $ch->eta = $row['eta'];
                            $list[] = $ch;
                        }
                    }
                }
            }
        }
        return $list;
    }

    public function getPhoneNumbersForRouteCheckpoint($objRequest) {
        $arrPhoneNos = array();
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sp_params = "'" . $objRequest->routeid . "'"
        . ",'" . $objRequest->checkpointid . "'"
        . ",'" . $objRequest->customerno . "'"
        ;
        $queryCallSP = $this->PrepareSP(speedConstants::SP_GET_PHONENO_ROUTE_CHKPT, $sp_params);
        $arrPhoneNos = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        return $arrPhoneNos;
    }

    public function updateRouteEtaStatus($rmid, $etaStatus, $customerno) {
        $QUERY = "UPDATE routeman SET etaStatus = '" . $etaStatus . "' WHERE rmid = " . $rmid . " AND customerno = " . $customerno;
        $SQL = sprintf($QUERY);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getVehicleCheckpointRoute($chkid, $customerno, $vehicleid) {
        $chkpts = null;
        $Query = "SELECT * FROM routeman
                INNER JOIN vehiclerouteman on routeman.routeid = vehiclerouteman.routeid
                WHERE vehiclerouteman.vehicleid=%d and routeman.checkpointid=%d and routeman.customerno=%d and routeman.isdeleted=0 AND vehiclerouteman.isdeleted=0
                ORDER BY rmid DESC limit 1";
        $routeQuery = sprintf($Query, $vehicleid, $chkid, $customerno);
        $this->_databaseManager->executeQuery($routeQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $chkpt = new VOCheckpoint();
                $chkpt->rmid = $row['rmid'];
                $chkpt->routeid = $row['routeid'];
                $chkpt->checkpointid = $row['checkpointid'];
                $chkpt->sequence = $row['sequence'];
                $chkpt->customerno = $row['customerno'];
                $chkpts[] = $chkpt;
            }
        }
        return $chkpts;
    }

    public function storeSMS($phoneno, $message, $customerno, $checkpointid, $cname, $vehicleno) {
        $today = date('Y-m-d H:i:s');
        $Query = "INSERT INTO " . DB_PARENT . ".storesms(customerno, checkpointid, cname, vehicleno, phone, message, timestamp)values(%d, %d, '%s', '%s', '%s', '%s', '%s')";
        $SQL = sprintf($Query, $customerno, $checkpointid, $cname, $vehicleno, $phoneno, $message, $today);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function elixiacodeExpire($customerno, $vehicleid) {
        $chkpts = array();
        $list = array();
        $Query = "select * from ecodeman where customerno=%d and vehicleid=%d and isdeleted=0";
        $routeQuery = sprintf($Query, $customerno, $vehicleid);
        $this->_databaseManager->executeQuery($routeQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $chkpt = new VOCheckpoint();
                $chkpt->ecodeid = $row['ecodeid'];
                $chkpt->vehicleid = $row['vehicleid'];
                $chkpts[] = $chkpt;
            }
            //return $chkpts;
            if (!empty($chkpts)) {
                $Query = "Update elixiacode SET isdeleted=1 where id=%d and customerno=%d ";
                foreach ($chkpts as $chk) {
                    $SQL = sprintf($Query, $chk->ecodeid, $customerno);
                    $this->_databaseManager->executeQuery($SQL);
                }
            }
            if (!empty($chkpts)) {
                $Query = "Update ecodeman SET isdeleted=1 where ecodeid=%d and customerno=%d and vehicleid=%d ";
                foreach ($chkpts as $chk) {
                    $SQL = sprintf($Query, $chk->ecodeid, $customerno, $vehicleid);
                    $this->_databaseManager->executeQuery($SQL);
                }
            }
        }
    }

    public function checkpointupdate($path, $starttime, $endtime, $vehicleid, $checkpointid) {
        $db = new PDO($path);
        /* TODO: Test the below logic and compare with existing logic as it seems CROSS JOIN in existing logic doesn't work sometimes */
        /*
        $chkPt = array();
        $outQuery = "select chkrepid as outchkrepid, chkid as outchkid, status as outstatus, date as outdate "
        . " from V" . $vehicleid . " WHERE chkid = " . $checkpointid . " and status = 1 "
        . " and date BETWEEN '" . $starttime . "' AND '" . $endtime . "' ORDER BY date DESC limit 1";
        $outResult = $db->query($outQuery)->fetchAll();
        if (isset($outResult)) {
        foreach ($outResult as $row) {
        $chkPt['outchkrepid'] = isset($row["outchkrepid"]) ? $row["outchkrepid"] : NULL;
        $chkPt['outchkid'] = isset($row["outchkid"]) ? $row["outchkid"] : NULL;
        $chkPt['outstatus'] = isset($row["outstatus"]) ? $row["outstatus"] : NULL;
        $chkPt['outdate'] = isset($row["outdate"]) ? $row["outdate"] : NULL;
        }
        }
        $inQuery = "select chkrepid as inchkrepid, chkid as inchkid, status as instatus, date as indate "
        . " from V" . $vehicleid . " WHERE chkid = " . $checkpointid . " and status = 0 "
        . " and date BETWEEN '" . $starttime . "' AND '" . $endtime . "' ORDER BY date DESC limit 1";
        $inResult = $db->query($inQuery)->fetchAll();
        if (isset($inResult)) {
        foreach ($inResult as $row) {
        $chkPt['inchkrepid'] = isset($row["inchkrepid"]) ? $row["inchkrepid"] : NULL;
        $chkPt['inchkid'] = isset($row["inchkid"]) ? $row["inchkid"] : NULL;
        $chkPt['instatus'] = isset($row["instatus"]) ? $row["instatus"] : NULL;
        $chkPt['indate'] = isset($row["indate"]) ? $row["indate"] : NULL;
        }
        }
        return $chkpt;
         */

        $Query = "select outChkPt.*, inChkPt.* FROM "
            . " (select chkrepid as outchkrepid, chkid as outchkid, status as outstatus, date as outdate "
            . " from V" . $vehicleid . " WHERE chkid = " . $checkpointid . " and status = 1 "
            . " and date BETWEEN '" . $starttime . "' AND '" . $endtime . "' ORDER BY date DESC limit 1) as outChkPt "
            . " CROSS JOIN "
            . " (select chkrepid as inchkrepid, chkid as inchkid, status as instatus, date as indate "
            . " from V" . $vehicleid . " WHERE chkid = " . $checkpointid . " and status = 0 "
            . " and date BETWEEN '" . $starttime . "' AND '" . $endtime . "' ORDER BY date DESC limit 1) as inChkPt ";
        $result = $db->query($Query);
        return $result;

        /*
    $Query = "select * from V" . $vehicleid . " WHERE chkid = $checkpointid and date BETWEEN '" . $starttime . "' AND '" . $endtime . "' ORDER BY date DESC limit 2";
    $result = $db->query($Query);
     */
    }

    public function getofflinedata() {
        $chkpts = array();
        $Query = "SELECT * FROM `chk_offline`
            where latitude <> 0 AND longitude <> 0  AND customerno <> 1 ORDER BY lastupdated ASC";
        $devicesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $chkpt = new VOCheckpoint();
                $chkpt->customerno = $row['customerno'];
                $chkpt->lastupdated = $row['lastupdated'];
                $chkpt->vehicleid = $row['vehicleid'];
                $chkpt->latitude = $row['latitude'];
                $chkpt->longitude = $row['longitude'];
                $chkpts[] = $chkpt;
            }
            return $chkpts;
        }
        return NULL;
    }

    public function getusergcm_fromcustomers($customerno) {
        $gcms = array();
        $Query = "SELECT gcmid FROM  `user`  WHERE gcmid <> '' AND customerno = %d";
        $devicesQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $gcm = new usergcm();
                $gcm->gcmid = $row['gcmid'];
                $gcms[] = $gcm;
            }
            return $gcms;
        }
        return NULL;
    }

    public function getalldeviceswithgeofencesforcrons() {
        $devices = array();
        //$time = date("Y-m-d H:i:s", strtotime("-60 seconds"));
        //$time = "2013-12-30 18:10:00";

        /* $Query = "SELECT devices.deviceid, devices.devicelat, devices.devicelong, vehicle.vehicleid, vehicle.vehicleno,
        devices.customerno, devices.uid, fenceman.conflictstatus, fence.fencename, fenceman.fmid, fence.fenceid,
        devices.lastupdated FROM devices
        INNER JOIN unit ON unit.uid = devices.uid
        INNER JOIN vehicle ON unit.vehicleid = vehicle.vehicleid
        INNER JOIN fenceman ON vehicle.vehicleid = fenceman.vehicleid
        INNER JOIN fence ON fenceman.fenceid = fence.fenceid
        WHERE fence.isdeleted =0
        AND fenceman.isdeleted =0
        AND unit.trans_statusid NOT IN (10,22,73,328,64)
        # AND devices.lastupdated >= '$time'
        AND devices.customerno in (2,59,73,328,64)
        AND unit.customerno in (2,59,73,328,64)
        AND vehicle.customerno in (2,59,73,328,64)
        AND fenceman.customerno in (2,59,73,328,64)
        AND fence.customerno in (2,59,73,328,64)
         *///
        $Query = "SELECT
            devices.deviceid,
            devices.devicelat,
            devices.devicelong,
            vehicle.vehicleid,
            vehicle.vehicleno,
            devices.customerno,
            devices.uid,
            /*fenceman.conflictstatus,
            fence.fencename,*/
            checkpointmanage.conflictstatus,
            checkpoint.cname,
            /*fenceman.fmid,
            fence.fenceid,*/
            checkpointmanage.cmid,
            checkpoint.checkpointid,
            devices.lastupdated,
            checkpoint.polygonLatLongJson
        FROM
            devices
                INNER JOIN
            unit ON unit.uid = devices.uid
                INNER JOIN
            vehicle ON unit.vehicleid = vehicle.vehicleid
                INNER JOIN
            /*fenceman ON vehicle.vehicleid = fenceman.vehicleid*/
            checkpointmanage ON vehicle.vehicleid = checkpointmanage.vehicleid
                INNER JOIN
            /*fence ON fenceman.fenceid = fence.fenceid*/
            checkpoint ON checkpointmanage.checkpointid = checkpoint.checkpointid
        WHERE
           /* fence.isdeleted = 0
                AND fenceman.isdeleted = 0 */
                checkpoint.isdeleted = 0
                AND checkpointmanage.isdeleted = 0
                AND unit.trans_statusid NOT IN (10 , 22, 73, 328, 64)
               /* AND devices.lastupdated >= '$time'*/
                AND devices.customerno IN (2 , 59, 73, 328, 64)
                AND unit.customerno IN (2 , 59, 73, 328, 64)
                AND vehicle.customerno IN (2 , 59, 73, 328, 64)
               /* AND fenceman.customerno IN (2 , 59, 73, 328, 64)
                AND fence.customerno IN (2 , 59, 73, 328, 64); */

               AND checkpointmanage.customerno IN (2 , 59, 73, 328, 64)
               AND checkpoint.customerno IN (2 , 59, 73, 328, 64)
               AND checkpoint.checkPointCategory=2";

        $devicesQuery = sprintf($Query);
        $callSp = 'CALL get_polygondata_for_cronConflict("2013-12-30 18:10:00")';

        /* $this->_databaseManager->executeQuery($devicesQuery); */
        $this->_databaseManager->executeQuery($callSp);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row['uid'] > 0) {
                    /*  $device = new VODevices();
                    $device->deviceid = $row['deviceid'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->customerno = $row['customerno'];
                    $device->conflictstatus = $row['conflictstatus'];
                    $device->fencename = $row['fencename'];
                    $device->fmid = $row['fmid'];
                    $device->fenceid = $row['fenceid'];
                    $device->lastupdated = $row['lastupdated'];
                     */

                    $device = new stdClass();
                    $device->deviceid = $row['deviceid'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->customerno = $row['customerno'];
                    $device->conflictstatus = $row['conflictstatus'];
                    $device->fencename = $row['cname'];
                    $device->fmid = $row['cmid'];
                    $device->fenceid = $row['checkpointid'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->polygonLatLongJson = $row['polygonLatLongJson'];
                    $devices[] = $device;
                }
            }
            //echo "Devices Data: <pre>"; print_r($devices); exit();
            return $devices;
        }
        return NULL;
    }

    public function getallchkpforcronbyvehicleid($vehicleid) {
        $chkpts = array();
        $Query = "select checkpoint.cname,checkpoint.cgeolat,checkpoint.cgeolong,
            checkpointmanage.conflictstatus,checkpointmanage.cmid,checkpoint.crad,
            checkpoint.checkpointid from checkpoint
            INNER JOIN checkpointmanage ON checkpointmanage.checkpointid = checkpoint.checkpointid
            where checkpointmanage.vehicleid = '%d' AND checkpoint.isdeleted = 0 AND checkpointmanage.isdeleted = 0";
        $devicesQuery = sprintf($Query, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $chkpt = new VODevices();
                $chkpt->cname = $row['cname'];
                $chkpt->cgeolat = $row['cgeolat'];
                $chkpt->cgeolong = $row['cgeolong'];
                $chkpt->conflictstatus = $row['conflictstatus'];
                $chkpt->cmid = $row['cmid'];
                $chkpt->crad = $row['crad'];
                $chkpt->checkpointid = $row['checkpointid'];
                $chkpts[] = $chkpt;
            }
            return $chkpts;
        }
        return NULL;
    }

    public function getallgeofencesforcronbyvehicleid($vehicleid) {
        $geofences = array();
        $Query = "select fence.fenceid,fence.fencename, fenceman.conflictstatus from fence
            INNER JOIN fenceman ON fenceman.fenceid = fence.fenceid
            where fenceman.vehicleid=%d AND fence.isdeleted=0 AND fenceman.isdeleted=0";
        $fenceQuery = sprintf($Query, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($fenceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $geofence = new VOFence();
                $geofence->fenceid = $row['fenceid'];
                $geofence->fencename = $row['fencename'];
                $geofence->conflictstatus = $row['conflictstatus'];
                $geofences[] = $geofence;
            }
            return $geofences;
        }
        return NULL;
    }

    public function get_geofence_from_fenceid($fenceid) {
        $geofence = array();
        $Query = "SELECT geolat,geolong FROM `geofence` WHERE fenceid=%d AND isdeleted=0";
        $geofenceQuery = sprintf($Query, Sanitise::Long($fenceid));
        $_SESSION['query'] = $geofenceQuery;
        $this->_databaseManager->executeQuery($geofenceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $geofencepart = new VOGeofence();
                $geofencepart->geolat = $row['geolat'];
                $geofencepart->geolong = $row['geolong'];
                $geofence[] = $geofencepart;
            }
            return $geofence;
        }
        return null;
    }

    // Tamper
    public function marktampered($vehicleid, $customerno) {
        $Query = "Update eventalerts Set `tamper`=1 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markuntampered($vehicleid, $customerno) {
        $Query = "Update eventalerts Set `tamper`=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    //PowerCut
    public function markpowercut($vehicleid, $customerno) {
        $Query = "Update eventalerts Set `powercut`=1 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markpowerin($vehicleid, $customerno) {
        $Query = "Update eventalerts Set `powercut`=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    //Overspeeding
    public function markoverspeeding($vehicleid, $customerno) {
        $Query = "Update eventalerts Set `overspeed`=1 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function marknormalspeeding($vehicleid, $customerno) {
        $Query = "Update eventalerts Set `overspeed`=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    //AC Sensor
    public function markacon($vehicleid, $customerno) {
        $Query = "Update eventalerts Set `ac`=1 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markacoff($vehicleid, $customerno) {
        $Query = "Update eventalerts Set `ac`=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    // Checkpoint
    public function markoutsidechk($cmid, $customerno, $outtime) {
        $Query = "Update checkpointmanage Set `conflictstatus`=1, `outTime`='%s' WHERE cmid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::DateTime($outtime), Sanitise::long($cmid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markinsidechk($cmid, $customerno, $intime) {
        $Query = "Update checkpointmanage Set `conflictstatus`=0, `inTime`='%s', `outTime`=NULL WHERE cmid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::DateTime($intime), Sanitise::long($cmid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markVehicleCheckpointOut($objData) {
        $Query = "Update vehicle Set `chkpoint_status`=1, checkpoint_timestamp='%s' WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::DateTime($objData->lastupdated), Sanitise::long($objData->vehicleid), Sanitise::Long($objData->customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markVehicleCheckpointIn($objData) {
        $Query = "Update vehicle Set `checkpointId`=%d,`chkpoint_status`=0, checkpoint_timestamp='%s' WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($objData->chkid), Sanitise::DateTime($objData->lastupdated), Sanitise::long($objData->vehicleid), Sanitise::Long($objData->customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markoutsidefence($fenceid, $vehicleid, $customerno) {
        $Query = "Update fenceman Set conflictstatus=1 WHERE fenceid = %d AND vehicleid=%d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($fenceid), Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markinsidefence($fenceid, $vehicleid, $customerno) {
        $Query = "Update fenceman Set conflictstatus=0 WHERE fenceid = %d AND vehicleid=%d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($fenceid), Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    //Ignition
    public function markignitionstatus($count, $idleignon_count, $running_count, $ignition, $vehicleid, $customerno) {
        $Query = "Update ignitionalert Set `count` = %d, `running_count` = %d, `idleignon_count` = %d, `last_status` = %d WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($count), Sanitise::Long($running_count), Sanitise::Long($idleignon_count), Sanitise::Long($ignition), Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function changelastigcheck($lastupdated, $vehicleid, $customerno) {
        $Query = "Update ignitionalert SET last_check = '%s' Where vehicleid =%d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::DateTime($lastupdated), Sanitise::String($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markignitionon($vehicleid, $customerno, $markignitionon) {
        $Query = "Update ignitionalert Set `count`=0, `running_count` = 0, `idleignon_count` = 0, status = 1, ignontime='%s' WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::DateTime($markignitionon), Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function mark_ignchgtime($chgigntime, $vehicleid, $customerno) {
        $Query = "Update ignitionalert Set `ignchgtime` = '%s' WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, $chgigntime, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markignitionoff($vehicleid, $customerno) {
        $Query = "Update ignitionalert Set `count`=0, `running_count` = 0, `idleignon_count` = 0, status = 0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    //temp sensor
    public function marktempon($vehicleid, $customerno, $type = NULL, $userid = NULL) {
        if (isset($type)) {
            $Query = "Update advtempeventalerts Set `temp" . $type . "`=1 WHERE vehicleid = %d AND userid = %d AND customerno = %d";
        } else {
            $Query = "Update eventalerts Set `temp`=1 WHERE vehicleid = %d AND customerno = %d";
        }
        if (isset($userid)) {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($userid), Sanitise::Long($customerno));
        } else {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        }
        $this->_databaseManager->executeQuery($SQL);
    }

    public function marktempoff($vehicleid, $customerno, $type = NULL, $userid = NULL) {
        if (isset($type)) {
            $Query = "Update advtempeventalerts Set `temp" . $type . "`=0 WHERE vehicleid = %d AND userid = %d AND customerno = %d";
        } else {
            $Query = "Update eventalerts Set `temp`=0 WHERE vehicleid = %d AND customerno = %d";
        }
        if (isset($userid)) {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($userid), Sanitise::Long($customerno));
        } else {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        }
        $this->_databaseManager->executeQuery($SQL);
    }

    public function marktemp2on($vehicleid, $customerno, $type = NULL, $userid = NULL) {
        if (isset($type)) {
            $Query = "Update advtempeventalerts Set `temp2" . $type . "`=1 WHERE vehicleid = %d AND userid = %d AND customerno = %d";
        } else {
            $Query = "Update eventalerts Set `temp2`=1 WHERE vehicleid = %d AND customerno = %d";
        }
        if (isset($userid)) {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($userid), Sanitise::Long($customerno));
        } else {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        }
        $this->_databaseManager->executeQuery($SQL);
    }

    public function marktemp3on($vehicleid, $customerno, $type = NULL, $userid = NULL) {
        if (isset($type)) {
            $Query = "Update advtempeventalerts Set `temp3" . $type . "`=1 WHERE vehicleid = %d AND userid = %d AND customerno = %d";
        } else {
            $Query = "Update eventalerts Set `temp3`=1 WHERE vehicleid = %d AND customerno = %d";
        }
        if (isset($userid)) {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($userid), Sanitise::Long($customerno));
        } else {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        }
        $this->_databaseManager->executeQuery($SQL);
    }

    public function marktemp4on($vehicleid, $customerno, $type = NULL, $userid = NULL) {
        if (isset($type)) {
            $Query = "Update advtempeventalerts Set `temp4" . $type . "`=1 WHERE vehicleid = %d AND userid = %d AND customerno = %d";
        } else {
            $Query = "Update eventalerts Set `temp4`=1 WHERE vehicleid = %d AND customerno = %d";
        }
        if (isset($userid)) {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($userid), Sanitise::Long($customerno));
        } else {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        }
        $this->_databaseManager->executeQuery($SQL);
    }

    public function marktemp2off($vehicleid, $customerno, $type = NULL, $userid = NULL) {
        if (isset($type)) {
            $Query = "Update advtempeventalerts Set `temp2" . $type . "`=0 WHERE vehicleid = %d AND userid = %d AND customerno = %d";
        } else {
            $Query = "Update eventalerts Set `temp2`=0 WHERE vehicleid = %d AND customerno = %d";
        }
        if (isset($userid)) {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($userid), Sanitise::Long($customerno));
        } else {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        }
        $this->_databaseManager->executeQuery($SQL);
    }

    public function marktemp3off($vehicleid, $customerno, $type = NULL, $userid = NULL) {
        if (isset($type)) {
            $Query = "Update advtempeventalerts Set `temp3" . $type . "`=0 WHERE vehicleid = %d AND userid = %d AND customerno = %d";
        } else {
            $Query = "Update eventalerts Set `temp3`=0 WHERE vehicleid = %d AND customerno = %d";
        }
        if (isset($userid)) {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($userid), Sanitise::Long($customerno));
        } else {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        }
        $this->_databaseManager->executeQuery($SQL);
    }

    public function marktemp4off($vehicleid, $customerno, $type = NULL, $userid = NULL) {
        if (isset($type)) {
            $Query = "Update advtempeventalerts Set `temp4" . $type . "`=0 WHERE vehicleid = %d AND userid = %d AND customerno = %d";
        } else {
            $Query = "Update eventalerts Set `temp4`=0 WHERE vehicleid = %d AND customerno = %d";
        }
        if (isset($userid)) {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($userid), Sanitise::Long($customerno));
        } else {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        }
        $this->_databaseManager->executeQuery($SQL);
    }

    public function marktempInton($uid, $date, $customerno, $userid) {
        $Query = "  Update  alertTempUserMapping
                    SET     temp1_intv = '%s'
                    WHERE   uid = %d
                    AND     userid = %d
                    AND     customerno = %d";
        $SQL = sprintf($Query, Sanitise::DateTime($date), Sanitise::Long($uid), Sanitise::Long($userid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function marktempIntoff($uid, $date, $customerno, $userid) {
        $date1 = '0000-00-00 00:00:00';
        $Query = "  Update  alertTempUserMapping
                    SET     temp1_intv = '%s'
                    WHERE   uid = %d
                    AND     userid = %d
                    AND     customerno = %d";
        $SQL = sprintf($Query, Sanitise::DateTime($date1), Sanitise::Long($uid), Sanitise::Long($userid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function marktemp2Inton($uid, $date, $customerno, $userid) {
        $Query = "  Update  alertTempUserMapping
                    SET     temp2_intv = '%s'
                    WHERE   uid = %d
                    AND     userid = %d
                    AND     customerno = %d";
        $SQL = sprintf($Query, Sanitise::DateTime($date), Sanitise::Long($uid), Sanitise::Long($userid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function marktemp3Inton($uid, $date, $customerno, $userid) {
        $Query = "  Update  alertTempUserMapping
                    SET     temp3_intv = '%s'
                    WHERE   uid = %d
                    AND     userid = %d
                    AND     customerno = %d";
        $SQL = sprintf($Query, Sanitise::DateTime($date), Sanitise::Long($uid), Sanitise::Long($userid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function marktemp4Inton($uid, $date, $customerno, $userid) {
        $Query = "  Update  alertTempUserMapping
                    SET     temp4_intv = '%s'
                    WHERE   uid = %d
                    AND     userid = %d
                    AND     customerno = %d";
        $SQL = sprintf($Query, Sanitise::DateTime($date), Sanitise::Long($uid), Sanitise::Long($userid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function marktemp2Intoff($uid, $date, $customerno, $userid) {
        $date1 = '0000-00-00 00:00:00';
        $Query = "  Update  alertTempUserMapping
                    SET     temp2_intv = '%s'
                    WHERE   uid = %d
                    AND     userid = %d
                    AND     customerno = %d";
        $SQL = sprintf($Query, Sanitise::DateTime($date1), Sanitise::Long($uid), Sanitise::Long($userid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function marktemp3Intoff($uid, $date, $customerno, $userid) {
        $date1 = '0000-00-00 00:00:00';
        $Query = "  Update  alertTempUserMapping
                    SET     temp3_intv = '%s'
                    WHERE   uid = %d
                    AND     userid = %d
                    AND     customerno = %d";
        $SQL = sprintf($Query, Sanitise::DateTime($date1), Sanitise::Long($uid), Sanitise::Long($userid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function marktemp4Intoff($uid, $date, $customerno, $userid) {
        $date1 = '0000-00-00 00:00:00';
        $Query = "  Update  alertTempUserMapping
                    SET     temp4_intv = '%s'
                    WHERE   uid = %d
                    AND     userid = %d
                    AND     customerno = %d";
        $SQL = sprintf($Query, Sanitise::DateTime($date1), Sanitise::Long($uid), Sanitise::Long($userid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getTempInterval($customerno) {
        $result = array();
        $resultArr = array();
        $Query = "  SELECT  tempinterval as tempInterval
                            , GROUP_CONCAT(userid) AS useridList
                    FROM    user
                    WHERE   customerno = %d
                    AND     isdeleted = 0
                    GROUP BY tempinterval";
        $SQL = sprintf($Query, Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $resultArr['tempInterval'] = $row['tempInterval'];
                $resultArr['useridList'] = $row['useridList'];
                $result[] = $resultArr;
            }
        }
        return $result;
    }

    public function UpdateSimData($simdata) {
        $Query = "Update simdata Set `system_msg`='%s',`customerno`=%d, `is_processed`=1 WHERE `id` = %d";
        $SQL = sprintf($Query, Sanitise::String($simdata->msg), Sanitise::Long($simdata->customerno), Sanitise::Long($simdata->id));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function SuccessSimData($simdata) {
        $Query = "Update simdata Set `system_msg`='%s',`customerno`=%d, `vehicleid`=%d, `success`=%d, `is_processed`=1 WHERE `id` = %d";
        $SQL = sprintf($Query, Sanitise::String($simdata->msg), Sanitise::Long($simdata->customerno), Sanitise::Long($simdata->vehicleid), Sanitise::Long($simdata->success), Sanitise::Long($simdata->id));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function UpdateSimDataLoc($simdata) {
        $Query = "Update simdata Set `lat`=%f, `long`=%f, `system_msg`='%s',`customerno`=%d, `vehicleid`=%d, `success`=%d, `is_processed`=1 WHERE `id` = %d";
        $SQL = sprintf($Query, Sanitise::Float($simdata->lat), Sanitise::Float($simdata->long), Sanitise::String($simdata->msg), Sanitise::Long($simdata->customerno), Sanitise::Long($simdata->vehicleid), Sanitise::Long($simdata->success), Sanitise::Long($simdata->id));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function update_reset_vehicle($stoppage_odometer, $stoppage_transit_time, $vehicleid, $customerno) {
        $Query = "Update vehicle Set `stoppage_odometer`=%d,`stoppage_transit_time`='%s' WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($stoppage_odometer), Sanitise::DateTime($stoppage_transit_time), Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function update_sent_alert($vehicleid, $userid, $customerno, $status) {
        $Query = "Update stoppage_alerts Set `alert_sent`=%d WHERE vehicleid = %d AND userid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($status), Sanitise::Long($vehicleid), Sanitise::Long($userid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function delete_chk_offline() {
        $Query = "DELETE FROM chk_offline";
        $this->_databaseManager->executeQuery($Query);
    }

    public function getfreezedata($vehicleid) {
        $Query = "select * from freezelog where vehicleid=" . $vehicleid . " AND isdeleted=0 order by fid desc limit 1";
        $freezeQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($freezeQuery);
        $freezedata = array();
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $freezedata = array(
                    'freezelat' => $row['devicelat'],
                    'freezelong' => $row['devicelong'],
                    'userid' => $row['createdby'],
                    'odometer' => $row['odometer'],
                    'isAlertSent' => $row['isAlertSent']
                );
            }
            return $freezedata;
        }
        return $freezedata;
    }

    public function getdailyreoport_freeze($vehicleid) {
        $today = date('Y-m-d');
        $Query = "select last_odometer,max_odometer from dailyreport where vehicleid=" . $vehicleid . " AND daily_date='" . $today . "'";
        $freezeQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($freezeQuery);
        $freezedata = array();
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $freezedata = array(
                    'last_odometer' => $row['last_odometer'],
                    'max_odometer' => $row['max_odometer']
                );
            }
            return $freezedata;
        }
        return $freezedata;
    }

    public function getCronCheckpointExceptions($currentTime) {
        $arrException = array();
        $Query = "SELECT cpe.chkExpId,cpe.exceptionId,cpe.checkpointId,cpe.vehicleId,cpe.startTime,cpe.endTime,cpe.exceptionType,cpe.isSend,cpe.exceptionName,
                    cpe.customerno, c.cname as checkpointName, c.cgeolat,c.cgeolong,c.crad,
                    v.vehicleno as vehicleNo,
                    d.deviceid,d.devicelat,d.devicelong,d.lastupdated
                  FROM checkPointException as cpe
                  INNER JOIN checkpoint as c on c.checkpointid = cpe.checkpointId
                  INNER JOIN vehicle as v on v.vehicleid = cpe.vehicleId
                  INNER JOIN devices as d on d.uid = v.uid
                  WHERE  cpe.isSend = 0
                  AND '$currentTime' between cpe.startTime AND cpe.endTime
                  AND cpe.isdeleted = 0
                  AND c.isdeleted=0
                  AND v.isdeleted=0";
        $usersQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $exception = new stdClass();
                $exception->chkExpId = $row['chkExpId'];
                $exception->exceptionId = $row['exceptionId'];
                $exception->exceptionName = $row['exceptionName'];
                $exception->checkpointId = $row['checkpointId'];
                $exception->vehicleId = $row['vehicleId'];
                $exception->startTime = $row['startTime'];
                $exception->endTime = $row['endTime'];
                $exception->exceptionType = $row['exceptionType'];
                $exception->exceptionTypeName = '';
                if ($exception->exceptionType == 1) {
                    $exception->exceptionTypeName = "IN";
                } elseif ($exception->exceptionType == 2) {
                    $exception->exceptionTypeName = "OUT";
                }
                $exception->isSend = $row['isSend'];
                $exception->vehicleNo = $row['vehicleNo'];
                $exception->checkpointName = $row['checkpointName'];
                $exception->customerno = $row['customerno'];
                $exception->cgeolat = $row['cgeolat'];
                $exception->cgeolong = $row['cgeolong'];
                $exception->crad = $row['crad'];
                $exception->deviceid = $row['deviceid'];
                $exception->devicelat = $row['devicelat'];
                $exception->devicelong = $row['devicelong'];
                $exception->lastupdated = $row['lastupdated'];
                $arrException[] = $exception;
            }
        }
        return $arrException;
    }

    public function getVehicleRouteDetails($vehicleId, $customerno) {
        $vehicleRouteDetails = array();
        $sql = "SELECT c.checkpointid, c.cname, c.phoneno, c.email, rm.routeid, rm.sequence, c.isSms, c.isEmail
            FROM `checkpointmanage` cm
            INNER JOIN vehiclerouteman vrm ON vrm.vehicleid = cm.vehicleid and vrm.isdeleted = 0
            INNER JOIN routeman rm ON rm.routeid = vrm.routeid AND rm.checkpointid = cm.checkpointid and rm.isdeleted = 0
            INNER JOIN checkpoint c on c.checkpointid = rm.checkpointid and c.isdeleted=0
            WHERE cm.vehicleid = %d AND c.customerno = %d AND cm.isdeleted = 0 ORDER BY rm.sequence";
        $Query = sprintf($sql, $vehicleId, $customerno);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $objRouteDetails = new stdClass();
                $objRouteDetails->checkpointid = $row['checkpointid'];
                $objRouteDetails->checkpointname = $row['cname'];
                $objRouteDetails->phoneno = $row['phoneno'];
                $objRouteDetails->email = $row['email'];
                $objRouteDetails->routeid = $row['routeid'];
                $objRouteDetails->sequence = $row['sequence'];
                $objRouteDetails->isSms = $row['isSms'];
                $objRouteDetails->isEmail = $row['isEmail'];
                $vehicleRouteDetails[] = $objRouteDetails;
            }
        }
        return $vehicleRouteDetails;
    }

    public function markCheckpointDelay($cmid, $customerno) {
        $Query = "Update checkpointmanage Set `isDelayExpected`=1 WHERE cmid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($cmid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function unsetCheckpointDelay($customerno, $vehicleid, $routeid) {
        $Query = "Update checkpointmanage cm
        INNER JOIN vehiclerouteman vrm ON vrm.vehicleid = cm.vehicleid and vrm.isdeleted = 0
        INNER JOIN routeman rm ON rm.routeid = vrm.routeid AND rm.checkpointid = cm.checkpointid and rm.isdeleted = 0
        SET `isDelayExpected`=0
        WHERE cm.vehicleid = %d
        AND rm.routeid = %d
        AND cm.customerno = %d
        AND vrm.customerno = %d
        AND rm.customerno = %d
        AND cm.isdeleted=0";
        $SQL = sprintf($Query, Sanitise::long($vehicleid), Sanitise::long($routeid), Sanitise::Long($customerno), Sanitise::Long($customerno), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getStoppageVehiclesByGroup($groups, $objUser) {
        $vehicles = Array();
        $Query = 'SELECT v.vehicleid, v.vehicleno, v.odometer, v.stoppage_odometer, v.lastupdated, v.stoppage_flag, v.stoppage_transit_time,
            d.devicelat, d.devicelong, TIMESTAMPDIFF(MINUTE,v.stoppage_transit_time,v.lastupdated) as stoppageInterval, v.checkpointId, v.chkpoint_status, c.cname,sa.alert_sent
        FROM vehicle v
        INNER JOIN stoppage_alerts sa on sa.vehicleid = v.vehicleid and sa.userid = ' . $objUser->userid . '
        INNER JOIN unit on unit.vehicleid=v.vehicleid
        INNER JOIN devices d on d.uid=v.uid
        LEFT JOIN `group` ON `group`.groupid = v.groupid
        LEFT JOIN checkpoint c on c.checkpointid = v.checkpointid and v.checkpointId <> 0
        WHERE (v.customerno=%d )
        AND v.isdeleted=0
        and v.kind<> "Warehouse"
        and unit.trans_statusid NOT IN (10,22)
        AND sa.isdeleted=0
        AND sa.alert_sent = 0
        AND (CASE
            WHEN v.checkpointId = 0 THEN TIMESTAMPDIFF(MINUTE,v.stoppage_transit_time,v.lastupdated) > ' . $objUser->transmins .
        ' ELSE  TIMESTAMPDIFF(MINUTE,v.stoppage_transit_time,v.lastupdated) > ' . $objUser->chkmins . ' END )';
        if (isset($groups) && $groups[0] == 0) {
            $vehiclesQuery = sprintf($Query, $objUser->customerno);
        } else {
            $group_in = implode(',', $groups);
            $Query .= " AND v.groupid in (%s)";
            $vehiclesQuery = sprintf($Query, $objUser->customerno, $group_in);
        }
        echo $vehiclesQuery = $vehiclesQuery;die();
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new stdClass();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->odometer = $row['odometer'];
                $vehicle->stoppage_odometer = $row['stoppage_odometer'];
                $vehicle->lastupdated = $row['lastupdated'];
                $vehicle->stoppage_flag = $row['stoppage_flag'];
                $vehicle->stoppage_transit_time = $row['stoppage_transit_time'];
                $vehicle->devicelat = $row['devicelat'];
                $vehicle->devicelong = $row['devicelong'];
                $vehicle->checkpointId = $row['checkpointId'];
                $vehicle->checkpointName = $row['cname'];
                $vehicle->stoppageInterval = $row['stoppageInterval'];
                $vehicle->alert_sent = $row['alert_sent'];
                $vehicle->chkpoint_status = $row['chkpoint_status'];

                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function PrepareSP($sp_name, $sp_params) {
        return "call " . $sp_name . "(" . $sp_params . ");";
    }

    public function resetSmsCount() {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sp_params = "";
        $queryCallSP = $this->PrepareSP(speedConstants::SP_RESET_SMS_COUNT, $sp_params);
        $pdo->query($queryCallSP);
        $this->_databaseManager->ClosePDOConn($pdo);
    }

    public function getSMSLockedVehicleUser() {
        $Query = "SELECT sm.logid,sm.vehicleid,sm.userid,sm.customerno,sm.createdon,`vehicle`.vehicleno FROM `smslocklog` sm
                    LEFT OUTER JOIN `vehicle` ON `vehicle`.vehicleid=sm.vehicleid
                    WHERE ismailsent = 0 AND issmssent = 0";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $vehicle = Array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicles['logid'] = $row['logid'];
                $vehicles['vehicleid'] = $row['vehicleid'];
                $vehicles['userid'] = $row['userid'];
                $vehicles['customerno'] = $row['customerno'];
                $vehicles['createdon'] = $row['createdon'];
                $vehicles['vehicleno'] = $row['vehicleno'];
                $vehicle[] = $vehicles;
            }
            return $vehicle;
        } else {
            return NULL;
        }
    }

    public function getUserOfVehicle($vehicleid) {
        $Query = "SELECT groupid FROM `vehicle` WHERE vehicleid=%d";
        $vehiclesQuery = sprintf($Query, $vehicleid);
        $user = array();
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $groupid = $row['groupid'];
                if ($groupid > 0) {
                    $Query1 = "SELECT `user`.realname,`user`.email,`user`.phone from `group`
                            INNER JOIN `user` ON `user`.userid=`group`.userid
                            WHERE `group`.groupid=%d AND `user`.role='Administrator' AND `user`.isdeleted=0  AND (`user`.email <> '' OR `user`.phone <> '') ORDER BY `user`.userid ASC LIMIT 1";
                    $userdetails = sprintf($Query1, $groupid);
                    $this->_databaseManager->executeQuery($userdetails);
                    if ($this->_databaseManager->get_rowCount() > 0) {
                        while ($row1 = $this->_databaseManager->get_nextRow()) {
                            $user['realname'] = $row1['realname'];
                            $user['email'] = $row1['email'];
                            $user['phone'] = $row1['phone'];
                        }
                    } else {
                        $Query3 = "SELECT `user`.realname,`user`.email,`user`.phone from `vehicle`
                            INNER JOIN `user` ON `user`.customerno=`vehicle`.customerno
                            WHERE `vehicle`.vehicleid=%d AND `user`.role='Administrator' AND `user`.isdeleted=0 AND (`user`.email <> '' OR `user`.phone <> '') ORDER BY `user`.userid ASC LIMIT 1";
                        $finalQuery = sprintf($Query3, $vehicleid);
                        $this->_databaseManager->executeQuery($finalQuery);
                        if ($this->_databaseManager->get_rowCount() > 0) {
                            while ($row2 = $this->_databaseManager->get_nextRow()) {
                                $user['realname'] = $row2['realname'];
                                $user['email'] = $row2['email'];
                                $user['phone'] = $row2['phone'];
                            }
                        }
                    }
                } else {
                    $Query2 = "SELECT `user`.realname,`user`.email,`user`.phone from `vehicle`
                            INNER JOIN `user` ON `user`.customerno=`vehicle`.customerno
                            WHERE `vehicle`.vehicleid=%d AND `user`.role='Administrator' AND `user`.isdeleted=0 AND (`user`.email <> '' OR `user`.phone <> '') ORDER BY `user`.userid ASC LIMIT 1";
                    $finalQuery = sprintf($Query2, $vehicleid);
                    $this->_databaseManager->executeQuery($finalQuery);
                    if ($this->_databaseManager->get_rowCount() > 0) {
                        while ($row2 = $this->_databaseManager->get_nextRow()) {
                            $user['realname'] = $row2['realname'];
                            $user['email'] = $row2['email'];
                            $user['phone'] = $row2['phone'];
                        }
                    }
                }
                return $user;
            }
        }
    }

    public function getSmsLogVehicle($vehicleid, $time) {
        $Query = "  SELECT   mobileno
                            ,message
                            ,inserted_datetime
                    FROM    `smslog`
                    WHERE   vehicleid=%d
                    AND     inserted_datetime > DATE_SUB('%s', INTERVAL 1 HOUR)
                    ORDER BY inserted_datetime ASC";
        $vehiclesQuery = sprintf($Query, $vehicleid, $time);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $logs = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $log['mobileno'] = $row['mobileno'];
                $log['message'] = $row['message'];
                $log['inserted_datetime'] = $row['inserted_datetime'];
                $logs[] = $log;
            }
        }
        return $logs;
    }

    public function getSmsLogUser($userid, $time) {
        $Query = "  SELECT   mobileno
                            ,message
                            ,inserted_datetime
                    FROM    `smslog`
                    WHERE   userid=%d
                    AND     inserted_datetime > DATE_SUB('%s', INTERVAL 1 HOUR)
                    ORDER BY inserted_datetime ASC";
        $vehiclesQuery = sprintf($Query, $userid, $time);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $logs = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $log['mobileno'] = $row['mobileno'];
                $log['message'] = $row['message'];
                $log['inserted_datetime'] = $row['inserted_datetime'];
                $logs[] = $log;
            }
        }
        return $logs;
    }

    public function getUserDetails($userid, $customerno) {
        $Query = "SELECT realname,username,email,phone from `user`
                    WHERE role='Administrator' AND isdeleted=0 AND (email <> '' OR phone <> '') AND userid=%d LIMIT 1";
        $finalQuery = sprintf($Query, $userid);
        $user = array();
        $this->_databaseManager->executeQuery($finalQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row2 = $this->_databaseManager->get_nextRow()) {
                $user['realname'] = $row2['realname'];
                $user['username'] = $row2['username'];
                $user['email'] = $row2['email'];
                $user['phone'] = $row2['phone'];
            }
        } else {
            $Query1 = "SELECT realname,username,email,phone from `user` WHERE role='Administrator' AND isdeleted=0 AND (email <> '' OR phone <> '') AND customerno=%d ORDER BY userid ASC LIMIT 1";
            $finalQuery = sprintf($Query1, $customerno);
            $this->_databaseManager->executeQuery($finalQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row2 = $this->_databaseManager->get_nextRow()) {
                    $user['realname'] = $row2['realname'];
                    $user['username'] = $row2['username'];
                    $user['email'] = $row2['email'];
                    $user['phone'] = $row2['phone'];
                }
            }
        }
        return $user;
    }

    public function isSmsLockMailSent($logid) {
        $QUERY = "UPDATE `smslocklog` SET ismailsent = 1 WHERE logid = " . $logid;
        $SQL = sprintf($QUERY);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function isSmsLockSMSSent($logid) {
        $QUERY = "UPDATE `smslocklog` SET issmssent = 1 WHERE logid = " . $logid;
        $SQL = sprintf($QUERY);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getSmsLockReport() {
        $yesterday = date("Y-m-d", strtotime('yesterday'));
        $yesterday = "%" . $yesterday . "%";
        $Query = "SELECT sm.customerno
            ,sm.userid
            ,sm.vehicleid
            ,sm.createdby
            ,sm.createdon
            ,sm.updatedby
            ,sm.updatedon
            ,vehicle.vehicleno
            ,`user`.realname
            FROM smslocklog sm
                LEFT OUTER JOIN vehicle ON vehicle.vehicleid=sm.vehicleid
                LEFT OUTER JOIN `user` ON `user`.userid=sm.userid
                WHERE createdon LIKE '%s'";
        $finalQuery = sprintf($Query, $yesterday);
        $this->_databaseManager->executeQuery($finalQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $logs = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $log['customerno'] = $row['customerno'];
                $log['userid'] = $row['userid'];
                $log['realname'] = $row['realname'];
                $log['vehicleid'] = $row['vehicleid'];
                $log['vehicleno'] = $row['vehicleno'];
                $log['createdby'] = $row['createdby'];
                $log['createdon'] = $row['createdon'];
                $log['updatedby'] = $row['updatedby'];
                $log['updatedon'] = $row['updatedon'];
                $logs[] = $log;
            }
            return $logs;
        } else {
            return NULL;
        }
    }

    public function getExpiredTicket() {
        $today = date("Y-m-d");
        $Query = "  SELECT  team.`name`,COUNT(a.ticketid) count
                    FROM   " . DB_PARENT . ".sp_ticket_details a
                    INNER JOIN( SELECT  ticketid
                                        , MAX(uid) max_ID
                                FROM    " . DB_PARENT . ".sp_ticket_details
                                GROUP BY ticketid
                                ) b ON a.ticketid = b.ticketid AND a.uid = b.max_ID AND a.`status` <> 2
                    INNER JOIN " . DB_PARENT . ".sp_ticket ON sp_ticket.ticketid=a.ticketid
                    INNER JOIN " . DB_PARENT . ".team ON team.teamid=a.allot_to
                    WHERE sp_ticket.eclosedate < CURDATE()
                    GROUP BY a.allot_to
                    ORDER BY team.`name` ASC";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $logs = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $log['name'] = $row['name'];
                $log['count'] = $row['count'];
                $logs[] = $log;
            }
            return $logs;
        } else {
            return NULL;
        }
    }

    public function getOperationCallLog() {
        $yesterday = date("Y-m-d", strtotime('yesterday'));
        $yesterday = "%" . $yesterday . "%";
        $Query = "  SELECT      team.name, bs.type as buckettype, th.remark
                                ,oldunit.unitno as oldunit
                                ,newunit.unitno as newunit
                                ,oldvehicle.vehicleno as oldvehicle
                                ,newvehicle.vehicleno as newvehicle
                                ,oldsimcard.simcardno as oldsim
                                ,newsimcard.simcardno as newsim
                                ,trans_type.`type`
                                ,th.createdon
                                ,created.name as createdby
                                ,th.customerno
                                ,c.customercompany
                    FROM    `trans_history_new` th
                    INNER JOIN " . DB_PARENT . ". team ON team.teamid=th.teamid
                    INNER JOIN  " . DB_PARENT . ".team created ON created.teamid=th.createdby
                    INNER JOIN trans_type ON trans_type.id=th.`transtypeid`
                    LEFT JOIN   unit oldunit ON (oldunit.uid=th.oldunitid)
                    LEFT JOIN   unit newunit ON (newunit.uid=th.newunitid)
                    LEFT JOIN   vehicle oldvehicle ON (oldvehicle.vehicleid=th.oldvehicleid)
                    LEFT JOIN   vehicle newvehicle ON (newvehicle.vehicleid=th.newvehicleid)
                    LEFT JOIN   simcard oldsimcard ON (oldsimcard.id=th.oldsimcardid)
                    LEFT JOIN   simcard newsimcard ON (newsimcard.id=th.newsimcardid)
                    LEFT JOIN   " . DB_PARENT . ".bucket_status bs ON bs.id = th.bucketstatusid
                    LEFT JOIN   customer c ON c.customerno=th.customerno
                    WHERE th.createdon LIKE '%s' ORDER BY team.name";
        $finalQuery = sprintf($Query, $yesterday);
        $this->_databaseManager->executeQuery($finalQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $logs = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $log['name'] = $row['name'];
                $log['oldunit'] = $row['oldunit'];
                $log['newunit'] = $row['newunit'];
                $log['oldvehicle'] = $row['oldvehicle'];
                $log['newvehicle'] = $row['newvehicle'];
                $log['oldsim'] = $row['oldsim'];
                $log['newsim'] = $row['newsim'];
                $log['type'] = $row['type'];
                $log['createdon'] = $row['createdon'];
                $log['createdby'] = $row['createdby'];
                $log['buckettype'] = $row['buckettype'];
                $log['remark'] = $row['remark'];
                $log['customerno'] = $row['customerno'];
                $log['customercompany'] = $row['customercompany'];
                $logs[] = $log;
            }
            return $logs;
        } else {
            return NULL;
        }
    }

    public function getRouteFenceDevices() {
        $devices = array();
        $time = date("Y-m-d H:i:s", strtotime("-60 seconds"));
        //$time = "2013-12-30 18:10:00";
        $Query = "SELECT devices.deviceid, devices.devicelat, devices.devicelong, vehicle.vehicleid, vehicle.vehicleno,
                   devices.customerno, devices.uid, routefenceman.conflictstatus, routefence.fencename, routefenceman.fmid, routefence.fenceid,
                    devices.lastupdated FROM devices
                    INNER JOIN unit ON unit.uid = devices.uid
                    INNER JOIN vehicle ON unit.vehicleid = vehicle.vehicleid
                    INNER JOIN routefenceman ON vehicle.vehicleid = routefenceman.vehicleid
                    INNER JOIN routefence ON routefenceman.fenceid = routefence.fenceid
                    WHERE routefence.isdeleted =0
                    AND routefenceman.isdeleted =0
                    AND unit.trans_statusid NOT IN (10,22,73,328)
                    AND devices.customerno in (2,59,73,328)
                    AND unit.customerno in (2,59,73,328)
                    AND vehicle.customerno in (2,59,73,328)
                    AND routefenceman.customerno in (2,59,73,328)
                    AND routefence.customerno in (2,59,73,328)
                    AND devices.lastupdated >= '$time'
                    "; //
        /* AND devices.lastupdated >= '$time' */
        $devicesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row['uid'] > 0) {
                    $device = new stdClass();
                    $device->deviceid = $row['deviceid'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->customerno = $row['customerno'];
                    $device->conflictstatus = $row['conflictstatus'];
                    $device->fencename = $row['fencename'];
                    $device->fmid = $row['fmid'];
                    $device->fenceid = $row['fenceid'];
                    $device->lastupdated = $row['lastupdated'];
                    $devices[] = $device;
                }
            }
            return $devices;
        }
        return NULL;
    }

    public function markRouteFenceOut($fenceid, $vehicleid, $customerno) {
        $Query = "Update routefenceman Set conflictstatus=1 WHERE fenceid = %d AND vehicleid=%d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($fenceid), Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markRouteFenceIn($fenceid, $vehicleid, $customerno) {
        $Query = "Update routefenceman Set conflictstatus=0 WHERE fenceid = %d AND vehicleid=%d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($fenceid), Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markVehicleReset($vehicleid, $customerno) {
        $Query = "Update vehicle Set isVehicleResetCmdSent=1 WHERE vehicleid=%d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getMonthlySubscCust($month) {
//         $Query = sprintf("  SELECT  customerno, generate_invoice_month
//                     FROM    customer
//                     WHERE   (renewal = %d OR (renewal = -3 AND lease_duration = %d)) AND customerno IN (87,265,273,371,357,323,3,448,444,493,583,588,590)
// ;", Sanitise::Long($month), Sanitise::Long($month));
         $Query = " SELECT  customerno, generate_invoice_month
                    FROM    customer ";
        // echo $Query;die();
        $this->_databaseManager->executeQuery($Query);
        // $row = $this->_databaseManager->get_nextRow();
        // return $row;
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customer[] = $row;
            }
            return $customer;
        } else {
            return NULL;
        }
    }


public function getLedgerOfCustomer($customerno) {
        if (!empty($customerno)) {
            // $customerno = implode(',', $customerno);
            $QUERY = sprintf("  select  lc.ledgerid
                                from    ledger_cust_mapping lc
                                INNER JOIN ledger ON ledger.ledgerid=lc.ledgerid
                                where   lc.isdeleted = 0
                                AND     ledger.invoice_hold = 0
                                AND     lc.customerno IN (%s)", Sanitise::String($customerno));
            }
            else
            {
                
                $QUERY = sprintf("  select  lc.ledgerid
                                from    ledger_cust_mapping lc
                                INNER JOIN ledger ON ledger.ledgerid=lc.ledgerid
                                where   lc.isdeleted = 0
                                AND     ledger.invoice_hold = 0");           
            }

            
            // echo $QUERY; die();
            $this->_databaseManager->executeQuery($QUERY);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()) {
                    $ledgerid = $row['ledgerid'];
                }
                // print_r( $ledgerid); exit;
                return $ledgerid;
            }
        // }
        return NULL;
    }

    public function getProformaData($ledgerid) {
        $end_date = "%2018-06%";
        $QUERY = sprintf("  SELECT  lv.`ledgerid`
                                    ,lv.`customerno`
                                    ,`customer`.`unit_msp`
                                    ,count(lv.`customerno`) AS count1
                                    ,SUM(`customer`.`unit_msp`) AS total
                                    ,d.`end_date`
                            FROM    `ledger_veh_mapping` lv
                            INNER JOIN `customer` ON `customer`.`customerno` = lv.`customerno`
                            INNER JOIN `vehicle` ON `vehicle`.vehicleid = lv.vehicleid AND `vehicle`.invoice_hold = 0
                            INNER JOIN `unit` u ON u.vehicleid = vehicle.vehicleid AND u.trans_statusid IN (5,6)
                            INNER JOIN `devices` d ON d.uid = u.uid
                            INNER JOIN `simcard` s ON s.id = d.simcardid AND s.trans_statusid IN (13,14)
                            where   lv.`ledgerid` = %d and lv.`isdeleted` = 0
                            group by lv.`customerno`;", $ledgerid);
        $this->_databaseManager->executeQuery($QUERY);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $data['customerno'] = $row['customerno'];
                $data['total'] = $row['total'];
                $data['count1'] = $row['count1'];
                $data['end_date'] = $row['end_date'];
            }
            $sql = "select pi_id from `proforma_invoice` order by pi_id desc limit 1";
            $this->_databaseManager->executeQuery($sql);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row1 = $this->_databaseManager->get_nextRow()) {
                    $data['invoiceid'] = $row1['pi_id'] + 1;
                }
            }
            $sql1 = sprintf("SELECT d.deviceid
                            FROM    ledger_veh_mapping lv
                            INNER JOIN unit u ON u.vehicleid = lv.vehicleid
                            INNER JOIN devices d ON d.uid = u.uid
                            where   lv.ledgerid= %d
                            AND     lv.isdeleted = 0;", $ledgerid);
            $this->_databaseManager->executeQuery($sql1);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row1 = $this->_databaseManager->get_nextRow()) {
                    $device[] = $row1['deviceid'];
                }
            }
            $data['deviceid'] = $device;
            return $data;
        } else {
            return NULL;
        }
    }

    public function getInvoiceData($ledgerid, $month = NULL) {
        if ($month != NULL) {
            $end_date = $month;
        } else {
            $end_date = '2019-09';
        }

        $QUERY = "SELECT  lv.`ledgerid`
                                    ,lv.`customerno`
                                    ,`customer`.`unit_msp`
                                    ,`customer`.`lease_price`
                                    ,count(lv.`customerno`) AS count1
                                    ,SUM(`customer`.`unit_msp`) AS total
                                    ,SUM(`customer`.`lease_price`) AS total1
                                    ,d.`end_date`
                                    ,l.`state_code`
                                    ,sgc.`state`
                                    ,customer.`renewal`
                            FROM    `ledger_veh_mapping` lv
                            INNER JOIN `ledger` l ON l.`ledgerid` = lv.`ledgerid`
                            INNER JOIN `state_gst_code` sgc ON sgc.codeid = l.`state_code`
                            INNER JOIN `customer` ON `customer`.`customerno` = lv.`customerno`
                            INNER JOIN `vehicle` ON `vehicle`.vehicleid = lv.vehicleid AND `vehicle`.invoice_hold = 0
                            INNER JOIN `unit` u ON u.vehicleid = vehicle.vehicleid AND u.trans_statusid IN (5,6)
                            INNER JOIN `devices` d ON d.uid = u.uid
                            INNER JOIN `simcard` s ON s.id = d.simcardid AND s.trans_statusid IN (13,14)
                            where   lv.`ledgerid` = $ledgerid
                            AND DATE_FORMAT(d.`end_date`,'%Y-%m') = '" . $end_date . "'
                            AND     lv.`isdeleted` = 0
                            AND vehicle.kind != 'Warehouse'
                            group by lv.`customerno`
                            UNION
                            SELECT  lv.`ledgerid`
                                    ,lv.`customerno`
                                    ,`customer`.`warehouse_msp` as unit_msp
                                    ,`customer`.`lease_price`
                                    ,count(lv.`customerno`) AS count1
                                    ,SUM(`customer`.`warehouse_msp`) AS total
                                    ,SUM(`customer`.`lease_price`) AS total1
                                    ,d.`end_date`
                                    ,l.`state_code`
                                    ,sgc.`state`
                                    ,customer.`renewal`
                            FROM    `ledger_veh_mapping` lv
                            INNER JOIN `ledger` l ON l.`ledgerid` = lv.`ledgerid`
                            INNER JOIN `state_gst_code` sgc ON sgc.codeid = l.`state_code`
                            INNER JOIN `customer` ON `customer`.`customerno` = lv.`customerno`
                            INNER JOIN `vehicle` ON `vehicle`.vehicleid = lv.vehicleid AND `vehicle`.invoice_hold = 0
                            INNER JOIN `unit` u ON u.vehicleid = vehicle.vehicleid AND u.trans_statusid IN (5,6)
                            INNER JOIN `devices` d ON d.uid = u.uid
                            INNER JOIN `simcard` s ON s.id = d.simcardid AND s.trans_statusid IN (13,14)
                            where   lv.`ledgerid` = $ledgerid
                            AND DATE_FORMAT(d.`end_date`,'%Y-%m') = '" . $end_date . "'
                            AND     lv.`isdeleted` = 0
                            AND vehicle.kind = 'Warehouse'
                            group by lv.`customerno`";

        //$QUERY = sprintf("  ;", Sanitise::Long($ledgerid), Sanitise::String($end_date), Sanitise::Long($ledgerid), Sanitise::String($end_date));
        $this->_databaseManager->executeQuery($QUERY);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $data['customerno'] = $row['customerno'];
                if ($row['unit_msp'] == 0 || $row['unit_msp'] == '') {
                    $data['unit_msp'] = isset($data['unit_msp']) ? $data['unit_msp'] + $row['lease_price'] : $row['lease_price'];
                } else {
                    $data['unit_msp'] = isset($data['unit_msp']) ? $data['unit_msp'] + $row['unit_msp'] : $row['unit_msp'];
                }
                if ($row['total'] == 0 || $row['total'] == '') {
                    $data['total'] = isset($data['total']) ? $data['total'] + $row['total1'] : $row['total1'];
                } else {
                    $data['total'] = isset($data['total']) ? $data['total'] + $row['total'] : $row['total'];
                }
                $data['count1'] = isset($data['count1']) ? $data['count1'] + $row['count1'] : $row['count1'];
                $data['end_date'] = $row['end_date'];
                $data['state_code'] = $row['state_code'];
                $data['state'] = $row['state'];
                $data['renewal'] = $row['renewal'];
            }
            $sql = "select invoiceid from `invoice` order by invoiceid desc limit 1";
            // echo "<pre>";
            // echo $sql; die();
            $this->_databaseManager->executeQuery($sql);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row1 = $this->_databaseManager->get_nextRow()) {
                    $data['invoiceid'] = $row1['invoiceid'] + 1;
                }
            }
            $sql1 = "SELECT d.deviceid
                                    ,u.uid
                                    ,u.vehicleid
                                    ,vehicle.vehicleno
                            FROM    ledger_veh_mapping lv
                            INNER JOIN `ledger` l ON l.`ledgerid` = lv.`ledgerid`
                            INNER JOIN `state_gst_code` sgc ON sgc.codeid = l.`state_code`
                            INNER JOIN `customer` ON `customer`.`customerno` = lv.`customerno`
                            INNER JOIN `vehicle` ON `vehicle`.vehicleid = lv.vehicleid AND `vehicle`.invoice_hold = 0
                            INNER JOIN `unit` u ON u.vehicleid = vehicle.vehicleid AND u.trans_statusid IN (5,6)
                            INNER JOIN `devices` d ON d.uid = u.uid
                            INNER JOIN `simcard` s ON s.id = d.simcardid AND s.trans_statusid IN (13,14)
                            where   lv.ledgerid= $ledgerid
                            AND DATE_FORMAT(d.`end_date`,'%Y-%m') = '" . $end_date . "'
                            AND     lv.isdeleted = 0";
            //$sql1 = sprintf(";", Sanitise::Long($ledgerid), Sanitise::String());
                            // echo $QUERY;
                            
                              // echo "<pre>";
                              //   echo $sql1; die();
            $this->_databaseManager->executeQuery($sql1);
            if ($this->_databaseManager->get_rowCount() > 0) {
                $device = array();
                while ($row1 = $this->_databaseManager->get_nextRow()) {
                    $device[] = $row1['deviceid'];
                    $vehicle['uid'] = $row1['uid'];
                    $vehicle['vehicleid'] = $row1['vehicleid'];
                    $vehicle['vehicleno'] = $row1['vehicleno'];
                    $vehicles[] = $vehicle;
                }
                $data['deviceid'] = $device;
                $data['vehicleid'] = $vehicles;
            }
            return $data;
        } else {
            return NULL;
        }
    }

    public function getScheduledInvoiceData() {}
    public function getLastProformaId() {
        $QUERY = sprintf("  SELECT  `pi_id`
                            FROM    `proforma_invoice`
                            ORDER BY `pi_id` DESC
                            LIMIT   1");
        $this->_databaseManager->executeQuery($QUERY);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $id = $row['pi_id'];
            }
            return $id;
        } else {
            return NULL;
        }
    }

    public function getMonthlyProformaDetails() {
        $user = 'elixir%';
        $QUERY = sprintf("  SELECT   pi.customerno
                                    ,pi.invoiceid
                                    ,cd.cp_email1 as emailid
                                    ,cd.cp_phone1 as mobileno
                                    ,u.userkey
                                    ,u.userid
                                    ,l.ledgername
                    FROM    `invoice` pi
                    LEFT JOIN `ledger` l ON l.ledgerid = pi.ledgerid
                    LEFT JOIN `contactperson_details` cd ON cd.customerno = pi.customerno
                    LEFT JOIN `user` u ON u.customerno = pi.customerno AND trim(u.username) LIKE '%s'
                    WHERE   pi.`is_mail_sent` = 0
                    AND cd.isdeleted = 0
                    AND cd.typeid IN (1,2)
                    GROUP BY pi.ledgerid;", Sanitise::String($user));

        $this->_databaseManager->executeQuery($QUERY);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $customers = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customer['customerno'] = $row['customerno'];
                $customer['invoiceid'] = $row['invoiceid'];
                $customer['emailid'] = $row['emailid'];
                $customer['mobileno'] = $row['mobileno'];
                $customer['userkey'] = $row['userkey'];
                $customer['userid'] = $row['userid'];
                $customer['ledgername'] = $row['ledgername'];
                $customers[] = $customer;
            }
            return $customers;
        } else {
            return NULL;
        }
    }

    public function getProformaInvoice($id) {
        $QUERY = sprintf("  SELECT   *
                                    ,l.ledgername
                                    ,l.address1
                                    ,l.address2
                                    ,l.address3
                                    ,l.st_no
                                    ,l.vat_no
                                    ,l.cst_no
                                    ,c.unit_msp
                            FROM    `proforma_invoice` p
                            LEFT OUTER JOIN ledger l ON l.ledgerid = p.ledgerid
                            LEFT OUTER JOIN customer c ON c.customerno = p.customerno
                            WHERE pi_id = %d", $id);
        $this->_databaseManager->executeQuery($QUERY);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $customers = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $details['ledgerid'] = $row['ledgerid'];
                $details['inv_amt'] = $row['inv_amt'];
                $details['quantity'] = $row['quantity'];
                $details['unit_msp'] = $row['unit_msp'];
                $details['tax'] = $row['tax'];
                $details['is_taxed'] = $row['is_taxed'];
                $details['start_date'] = $row['start_date'];
                $details['end_date'] = $row['end_date'];
                $details['ledgername'] = $row['ledgername'];
                $details['address1'] = $row['address1'];
                $details['address2'] = $row['address2'];
                $details['address3'] = $row['address3'];
                $details['st_no'] = $row['st_no'];
                $details['vat_no'] = $row['vat_no'];
                $details['cst_no'] = $row['cst_no'];
                $details['invoiceno'] = $row['invoiceno'];
                $details['inv_date'] = $row['inv_date'];
                $details['payment_due_date'] = $row['payment_due_date'];
            }
            return $details;
        } else {
            return NULL;
        }
    }

    public function getTaxInvoice($id) {
        $QUERY = sprintf("SELECT   l.ledgerid
                                    ,p.inv_amt
                                    ,p.quantity
                                    ,p.tax
                                    ,p.start_date
                                    ,p.end_date
                                    ,p.invoiceno
                                    ,p.inv_date
                                    ,p.inv_expiry
                                    ,p.product_id
                                    ,l.ledgername
                                    ,l.address1
                                    ,l.address2
                                    ,l.address3
                                    ,l.gst_no
                                    ,l.pan_no
                                    ,c.unit_msp
                                    ,c.warehouse_msp
                                    ,c.lease_price
                                    ,c.unitprice
                                    ,c.subsc_disc
                                    ,c.renewal
                                    ,u.userkey
                                    ,sgc.state
                                    ,sgc.codeid
                            FROM    `invoice` p
                            LEFT OUTER JOIN ledger l ON l.ledgerid = p.ledgerid
                            LEFT OUTER JOIN state_gst_code sgc ON sgc.codeid = l.state_code
                            LEFT OUTER JOIN customer c ON c.customerno = p.customerno
                            INNER JOIN user u ON u.customerno = c.customerno
                            WHERE p.invoiceid = %d AND u.role='elixir'
                            ", $id);

        $this->_databaseManager->executeQuery($QUERY);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $customers = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $details['ledgerid'] = $row['ledgerid'];
                $details['inv_amt'] = $row['inv_amt'];
                $details['quantity'] = $row['quantity'];
                if ($row['unit_msp'] == NULL || $row['unit_msp'] == 0) {
                    $details['unit_msp'] = $row['lease_price'];
                } else {
                    $details['unit_msp'] = $row['unit_msp'];
                }
                if ($row['warehouse_msp'] == NULL || $row['warehouse_msp'] == 0) {
                    $details['warehouse_msp'] = $row['lease_price'];
                } else {
                    $details['warehouse_msp'] = $row['warehouse_msp'];
                }
                $details['discount'] = $row['subsc_disc'];
                $details['renewal'] = $row['renewal'];
                $details['tax'] = $row['tax'];
                $details['start_date'] = $row['start_date'];
                $details['end_date'] = $row['end_date'];
                $details['ledgername'] = $row['ledgername'];
                $details['address1'] = $row['address1'];
                $details['address2'] = $row['address2'];
                $details['address3'] = $row['address3'];
                $details['codeid'] = $row['codeid'];
                $details['state'] = $row['state'];
                $details['gst_no'] = $row['gst_no'];
                $details['pan_no'] = $row['pan_no'];
                $details['invoiceno'] = $row['invoiceno'];
                $details['inv_date'] = $row['inv_date'];
                $details['inv_expiry'] = $row['inv_expiry'];
                $details['product_id'] = $row['product_id'];
                $details['unitprice'] = $row['unitprice'];
                $details['userkey'] = SHA1($row['userkey']);
            }
            return $details;
        } else {
            return NULL;
        }
    }

    public function getTaxInvoiceForErp($id) {
        $pdo = $this->_databaseManager->CreatePDOConnForTech();
        $queryCallSP = "SELECT inv_desc from elixiatech.invoice_reminders where invoiceid = $id";
        $result = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $QUERY = sprintf("SELECT   l.ledgerid
                                    ,ir.inv_amt
                                    ,p.inv_amt as totalAmt
                                    ,p.quantity
                                    ,p.tax
                                    ,p.start_date
                                    ,p.end_date
                                    ,p.invoiceno
                                    ,p.inv_date
                                    ,p.inv_expiry
                                    ,p.product_id
                                    ,p.comment
                                    ,l.ledgername
                                    ,l.address1
                                    ,l.address2
                                    ,l.address3
                                    ,l.gst_no
                                    ,l.pan_no
                                    ,c.unit_msp
                                    ,c.warehouse_msp
                                    ,c.lease_price
                                    ,c.unitprice
                                    ,c.subsc_disc
                                    ,c.renewal
                                    ,sgc.state
                                    ,sgc.codeid
                            FROM    `invoice` p
                            INNER JOIN elixiatech.invoice_reminders ir ON ir.invoiceid = p.invoiceid
                            LEFT OUTER JOIN ledger l ON l.ledgerid = p.ledgerid
                            LEFT OUTER JOIN state_gst_code sgc ON sgc.codeid = l.state_code
                            LEFT OUTER JOIN customer c ON c.customerno = p.customerno
                            WHERE p.invoiceid = %d
                            ", $id);

        $this->_databaseManager->executeQuery($QUERY);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $customers = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $details['ledgerid'] = $row['ledgerid'];
                $details['inv_amt'] = $row['inv_amt'];
                $details['discount'] = $row['subsc_disc'];
                $details['renewal'] = $row['renewal'];
                $details['tax'] = $row['tax'];
                $details['start_date'] = $row['start_date'];
                $details['end_date'] = $row['end_date'];
                $details['ledgername'] = $row['ledgername'];
                $details['address1'] = $row['address1'];
                $details['address2'] = $row['address2'];
                $details['address3'] = $row['address3'];
                $details['codeid'] = $row['codeid'];
                $details['state'] = $row['state'];
                $details['gst_no'] = $row['gst_no'];
                $details['pan_no'] = $row['pan_no'];
                $details['invoiceno'] = $row['invoiceno'];
                $details['inv_date'] = $row['inv_date'];
                $details['inv_expiry'] = $row['inv_expiry'];
                $details['product_id'] = $row['product_id'];
                $details['comment'] = $row['comment'];
                $details['desc'] = $result['inv_desc'];
                $details['totalAmt'] = $row['totalAmt'];
            }
            return $details;
        } else {
            return NULL;
        }
    }

    public function getCreditNoteData($id) {
        $pdo = $this->_databaseManager->CreatePDOConnForTech();
        $QUERY = sprintf("SELECT  cn.* 
                            FROM    `credit_note` cn
                            WHERE cn.credit_note_id = %d
                            ", $id);

        $this->_databaseManager->executeQuery($QUERY);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $customers = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $details['credit_note_id'] = $row['credit_note_id'];
                $details['credit_note_no'] = $row['credit_note_no'];
                $details['customerno'] = $row['customerno'];
                $details['invoiceno'] = $row['invoiceno'];
                $details['credit_amount'] = $row['credit_amount'];
                $details['reason'] = $row['reason'];
                $details['status'] = $row['status'];
                $details['requested_date'] = $row['requested_date'];
                $details['approved_date'] = $row['approved_date'];
            }
            return $details;
        } else {
            return NULL;
        }
    }

    public function getLedgerVehicle($ledgerid) {
        $QUERY = sprintf("  SELECT  `vehicle`.vehicleno
                            FROM    `ledger_veh_mapping` lv
                            INNER JOIN `customer` ON `customer`.`customerno` = lv.`customerno`
                            INNER JOIN `vehicle` ON `vehicle`.vehicleid = lv.vehicleid AND `vehicle`.invoice_hold = 0
                            INNER JOIN `unit` u ON u.vehicleid = vehicle.vehicleid
                            INNER JOIN `devices` d ON d.uid = u.uid
                            INNER JOIN `simcard` ON `simcard`.id = d.simcardid
                            WHERE   lv.`ledgerid` = %d
                            AND     lv.`isdeleted` = 0
                            GROUP BY `vehicle`.vehicleid", $ledgerid);
        $this->_databaseManager->executeQuery($QUERY);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle[] = $row['vehicleno'];
            }
            return $vehicle;
        } else {
            return NULL;
        }
    }

    public function getInvoiceVehicle($invoiceid) {
        $QUERY = sprintf("  SELECT  ivm.vehicleno, v.kind,d.installdate
                            FROM    `invoice_vehicle_mapping` ivm
                            INNER JOIN vehicle v ON v.vehicleid = ivm.vehicleid
                            INNER JOIN devices d ON d.uid = v.uid
                            WHERE   `invoiceid` = %d
                            AND     ivm.`isdeleted` = 0
                            AND     v.isdeleted = 0
                            ORDER BY v.kind ASC, ivm.vehicleno ASC", $invoiceid);
        $this->_databaseManager->executeQuery($QUERY);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $objVehDetail = new stdClass();
                $objVehDetail->vehicleno = $row['vehicleno'];
                $objVehDetail->kind = $row['kind'];
                $objVehDetail->expiry_date = date('d-m-Y', strtotime('+1 months', strtotime($row['installdate'])));
                $vehicle[] = $objVehDetail;
            }
            return $vehicle;
        } else {
            return NULL;
        }
    }

    public function updateProformaProcessed($id) {
        $QUERY = sprintf("  UPDATE  `invoice`
                            SET     `is_mail_sent` = 1
                            WHERE   `invoiceid`=%d", $id);
        $this->_databaseManager->executeQuery($QUERY);
    }

    public function dailySalesReport() {
        $day = date('l');
        $dayCount = 1;
        $dateStr = date('Y-m-d', strtotime('-1 day'));
        if ($day == 'Monday' || $day == 'Sunday') {
            $dateStr = date('Y-m-d', strtotime('last saturday'));
        }
        $yesterday = $dateStr;
        $sales = array();
        $salesQuery = sprintf("
        SELECT  sp.pipelineid,sp.pipeline_history_id
                                            ,sp.company_name
                                            ,`sales_product`.product_name
                                            ,COALESCE(t.name,'N.A.') as name
                                            ,s.stage_name AS newstage
                                            ,COALESCE(sh.stage_name,'New Pipeline') AS oldstage
                                            ,sp.timestamp
                                            ,COALESCE(t.name,'N.A.') as name
                                            ,sp.remarks
                                            ,sph.stageid
                                            ,COALESCE(tc.name,'N.A.') AS tcreator
                                    FROM    `sales_pipeline_history` sp
                                    LEFT JOIN `sales_pipeline_history` sph ON sph.pipeline_history_id = (SELECT max(pipeline_history_id) FROM sales_pipeline_history WHERE pipeline_history_id < sp.pipeline_history_id AND pipelineid = sp.pipelineid)
                                    LEFT JOIN `sales_stage` s ON s.stageid = sp.stageid
                                    LEFT JOIN `sales_stage` sh ON sh.stageid = sph.stageid
                                    LEFT JOIN `sales_product` ON `sales_product`.productid = sp.productid
                                    LEFT JOIN " . DB_ELIXIATECH . ".`team` t ON t.teamid = sp.teamid
                                    LEFT JOIN " . DB_ELIXIATECH . ".`team` tc ON tc.teamid = sp.teamid_creator
                                    WHERE DATE(sp.timestamp)=DATE('%s')
                                    ORDER BY sp.teamid ASC, sp.timestamp desc", $yesterday);
        $this->_databaseManager->executeQuery($salesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $sale["pipelineid"] = $row['pipelineid'];
                $sale["company_name"] = $row['company_name'];
                $sale["product_name"] = $row['product_name'];
                $sale["name"] = $row['name'];
                $sale["newstage"] = $row['newstage'];
                $sale["oldstage"] = $row['oldstage'];
                $sale["timestamp"] = $row['timestamp'];
                $sale["teamid_creator"] = $row['tcreator'];
                $sale["remarks"] = $row["remarks"];
                $sales[] = $sale;
            }
            return $sales;
        }
        return null;
    }

    public function dailySalesReportForSR() {
        $db = new DatabaseManager();
        $today = date('Y-m-d H:i:s');
        $pdo = $db->CreatePDOConn();
        $day = date('l');
        $dayCount = 1;
        $dateStr = date('Y-m-d', strtotime('-1 day'));
        if ($day == 'Monday' || $day == 'Sunday') {
            $dateStr = date('Y-m-d', strtotime('last saturday'));
        }
        $yesterday = $dateStr;
        $SRQuery = "Select teamid,name,email FROM " . DB_ELIXIATECH . ".team where department_id = 4 ";
        $this->_databaseManager->executeQuery($SRQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $SRs[] = $row;
            }
        }
        $ret = array();
        //$i=0;
        if (count($SRs) > 0) {
            foreach ($SRs as $sr) {
                $SRs = array();
                $sales = array();
                $records = array();
                $queryCallSP = sprintf(" SELECT  sp.pipelineid,sp.pipeline_history_id
                                        ,sp.company_name
                                        ,`sales_product`.product_name
                                        ,s.stage_name AS newstage
                                        ,COALESCE(sh.stage_name,'New Pipeline') AS oldstage
                                        ,sp.timestamp
                                        ,COALESCE(t.name,'N.A.') as name
                                        ,sp.remarks
                                        ,sph.stageid
                                        ,COALESCE(tc.name,'N.A.') AS teamid_creator
                                FROM    `sales_pipeline_history` sp
                                LEFT JOIN `sales_pipeline_history` sph ON sph.pipeline_history_id = (SELECT max(pipeline_history_id) FROM sales_pipeline_history WHERE pipeline_history_id < sp.pipeline_history_id AND pipelineid = sp.pipelineid)
                                LEFT JOIN `sales_stage` s ON s.stageid = sp.stageid
                                LEFT JOIN `sales_stage` sh ON sh.stageid = sph.stageid
                                LEFT JOIN `sales_product` ON `sales_product`.productid = sp.productid
                                LEFT JOIN " . DB_ELIXIATECH . ".`team` t ON t.teamid = sp.teamid
                                LEFT JOIN " . DB_ELIXIATECH . ".`team` tc ON tc.teamid = sp.teamid_creator
                                WHERE  sp.teamid = %d AND DATE(sp.timestamp)=DATE('%s')
                                ORDER BY sp.teamid ASC, sp.timestamp desc", $sr['teamid'], $yesterday);
                $result = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
                $return['sales'] = $result;
                $return['details'] = $sr;
                $ret[] = $return;
            }
            //print_r($ret);
            return $ret;
        }
        return null;
    }

    public function sendRemindersForSR() {
        $db = new DatabaseManager();
        $SQLreminder = sprintf("SELECT sr.reminderid,sr.reminder_datetime,sr.content,sr.pipelineid,sr.contactid,sr.timestamp
                                    ,t.email,t.name as cronset,te.name as creator
                                    ,COALESCE(sp.company_name,'Company name not entered.') as company_name
            FROM " . DB_PARENT . ".sales_reminder as sr
                left join " . DB_ELIXIATECH . ".team as t
                    ON sr.contactid = t.teamid
                left join " . DB_ELIXIATECH . ".team te
                    ON sr.teamid_creator = te.teamid
                left join " . DB_PARENT . ".sales_pipeline sp ON sp.pipelineid = sr.pipelineid
            where sr.isdeleted=0 AND status<>1");
        $db->executeQuery($SQLreminder);

        $details2 = array();
        if ($db->get_rowCount() > 0) {
            $x = 1;
            $delete_url = "";
            while ($row = $db->get_nextRow()) {
                $reminderdatetime = date('d-m-Y H:i:s', strtotime($row["reminder_datetime"]));
                $userdetails = new stdClass();
                $reminderid = $row["reminderid"];
                $userdetails->srno = $x;
                $userdetails->reminder_datetime = date('d-m-Y H:i:s', strtotime($reminderdatetime));
                $userdetails->content = $row["content"];
                $userdetails->pipelineid = $row["pipelineid"];
                $userdetails->reminder_send_to_name = $row["cronset"];
                $userdetails->timestamp = date('d-m-Y H:i:s', strtotime($row["timestamp"]));
                $userdetails->contactid = $row["contactid"];
                $userdetails->pipelineid = $row["pipelineid"];
                $userdetails->reminderid = $row["reminderid"];
                $userdetails->emailId = $row["email"];
                $userdetails->creator = $row["creator"];
                $userdetails->company_name = $row["company_name"];
                $details2[] = $userdetails;
                $x++;
            }
        }
        return $details2;
    }

    public function setReminderComplete($reminderid) {
        $db = new DatabaseManager();
        $updateReminder = sprintf("UPDATE " . DB_PARENT . ".sales_reminder SET status = 1 WHERE reminderid = " . $reminderid);
        $db->executeQuery($updateReminder);
    }

    public function revivePipeline($pipelineid) {
        //echo $pipelineid;
        $db = new DatabaseManager();
        $today = date('Y-m-d H:i:s');
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $pipelineid . "',"
            . "'1',"
            . "'" . $today . "'";
        $queryCallSP = "CALL " . speedConstants::SP_REVIVE_PIPELINE . "($sp_params)";
        $result = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
    }

    public function autoFreezePipeline($pipelineid) {
        $db = new DatabaseManager();
        $today = date('Y-m-d H:i:s');
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $pipelineid . "',"
            . "'1',"
            . "'" . $today . "'";
        $queryCallSP = "CALL " . speedConstants::SP_AUTOFREEZE_PIPELINE . "($sp_params)";
        $result = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
    }

    public function fetchStalePipelines() {
        $today = date('Y-m-d H:i:s');
        $stalePipelines = sprintf("SELECT sp.pipelineid,t.name,sp.company_name,sp.timestamp AS lastUpdatedOn,DATEDIFF('" . $today . "',sp.timestamp) as daysSinceLastUpdate
            FROM speed.sales_pipeline sp
            LEFT JOIN elixiatech.team t ON t.teamid = sp.teamid
            WHERE stageid NOT IN (8,9,10,12) AND sp.teamid IN (115,118,119,120)
            ORDER BY timestamp desc");
        $this->_databaseManager->executeQuery($stalePipelines);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $pipelines = array();
            $pipeline = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $pipeline["pipelineid"] = $row["pipelineid"];
                $pipeline["company_name"] = $row["company_name"];
                $pipelines[] = $pipeline;
            }
            foreach ($pipelines as $pipeline) {
                $this->autoFreezePipeline($pipeline["pipelineid"]);
            }
            return $pipelines;
        } else {
            return null;
        }
    }

    public function fetchFrozenPipelines() {
        $today = date('Y-m-d H:i:s');
        $salesQuery = sprintf(" SELECT  pipelineid,company_name,revive_date,timestamp
                                FROM    " . DB_PARENT . ".sales_pipeline
                                WHERE  isdeleted = 0 AND revive_date <= '" . $today . "' AND revive_date IS NOT NULL AND revive_date <> '0000-00-00 00:00:00' AND stageid = 9");
        $this->_databaseManager->executeQuery($salesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $pipelines = array();
            $pipeline = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $pipeline["pipelineid"] = $row["pipelineid"];
                $pipeline["company_name"] = $row["company_name"];
                $pipelines[] = $pipeline;
            }
            foreach ($pipelines as $pipeline) {
                //$this->revivePipeline($pipeline["pipelineid"]);
            }
            return $pipelines;
        } else {
            return null;
        }
    }

    public function getAllCustomer() {
        $salesQuery = sprintf(" SELECT  lc.ledgerid
                                        ,lc.customerno
                                        ,l.ledgername
                                FROM    ledger_cust_mapping lc
                                INNER JOIN customer c ON c.customerno = lc.customerno
                                INNER JOIN ledger l ON l.ledgerid = lc.ledgerid
                                WHERE   lc.isdeleted = 0 AND c.renewal NOT IN (-1,-2);");
        $this->_databaseManager->executeQuery($salesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $customer = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $cust["ledgerid"] = $row['ledgerid'];
                $cust["ledgername"] = $row['ledgername'];
                $cust["customerno"] = $row['customerno'];
                $customer[] = $cust;
            }
            return $customer;
        } else {
            return null;
        }
    }

    public function getAvgCreditDays($ledgerid) {
        $salesQuery = sprintf(" SELECT  inv_date
                                        ,paymentdate
                                FROM    invoice
                                WHERE   ledgerid = %d
                                AND     status LIKE 'Paid'
                                AND     isdeleted = 0
                                AND     paymentdate IS NOT NULL
                                AND     paymentdate <> '0000-00-00'
                                ORDER BY invoiceid asc", $ledgerid);
        $this->_databaseManager->executeQuery($salesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $i = 0;
            $total = 0;
            while ($row = $this->_databaseManager->get_nextRow()) {
                $i++;
                $diff = floor((strtotime($row['paymentdate']) - strtotime($row['inv_date'])) / (60 * 60 * 24));
                $total += $diff;
            }
            $avg = floor($total / $i);
            return $avg;
        } else {
            return null;
        }
    }

    public function ledgerPendingAmt($ledgerid) {
        $salesQuery = sprintf(" SELECT  i.customerno
                                        ,c.customercompany
                                        ,i.ledgerid
                                        ,l.ledgername
                                        ,SUM(i.pending_amt) AS total
                                FROM    invoice i
                                INNER JOIN ledger l ON l.ledgerid = i.ledgerid
                                INNER JOIN customer c ON c.customerno = i.customerno
                                WHERE   i.ledgerid = %d
                                AND     LOWER(i.status) LIKE 'pending'
                                AND     i.isdeleted = 0
                                GROUP BY i.ledgerid", $ledgerid);
        $this->_databaseManager->executeQuery($salesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $ledger = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $ledg['customerno'] = $row['customerno'];
                $ledg['customercompany'] = $row['customercompany'];
                $ledg['ledgerid'] = $row['ledgerid'];
                $ledg['ledgername'] = $row['ledgername'];
                $total = $row['total'];
                $total1 = 0;
                $Query = sprintf("SELECT  SUM(cn.inv_amt) AS total1
                                FROM    credit_note cn
                                WHERE   cn.ledgerid = %d
                                AND     LOWER(cn.status) LIKE 'pending'
                                AND     cn.isdeleted = 0
                                GROUP BY cn.ledgerid", $row['ledgerid']);
                $this->_databaseManager->executeQuery($Query);
                if ($this->_databaseManager->get_rowCount() > 0) {
                    while ($row = $this->_databaseManager->get_nextRow()) {
                        $total1 = $row['total1'];
                    }
                }
                $ledg['total'] = $total - $total1;
            }
            return $ledg;
        } else {
            return null;
        }
    }

    public function checkVehicleExistsInRoute($chkid, $customerno, $vehicleid) {
        $chkpts = array();
        $Query = "SELECT routeman.rmid,routeman.routeid,routeman.checkpointid,routeman.sequence,routeman.customerno,route.routetype FROM routeman
                 inner join vehiclerouteman on routeman.routeid = vehiclerouteman.routeid
                 left join route on route.routeid = vehiclerouteman.routeid
                 where vehiclerouteman.vehicleid=%d
                 and routeman.checkpointid=%d
                 and routeman.customerno=%d
                 and routeman.isdeleted=0
                 AND vehiclerouteman.isdeleted=0
                 group by rmid";
        $routeQuery = sprintf($Query, $vehicleid, $chkid, $customerno);
        $this->_databaseManager->executeQuery($routeQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $chkpt = new VOCheckpoint();
                $chkpt->rmid = $row['rmid'];
                $chkpt->routeid = $row['routeid'];
                $chkpt->checkpointid = $row['checkpointid'];
                $chkpt->sequence = $row['sequence'];
                $chkpt->customerno = $row['customerno'];
                $chkpt->routetype = $row['routetype'];
                $chkpts[] = $chkpt;
            }
        }
        return $chkpts;
    }

    public function checkRouteSequence($customerno, $chkpts) {
        $list = array();

        if (isset($chkpts) && !empty($chkpts)) {
            $Querychk = "SELECT routeman.routeid, routeman.rmid, routeman.checkpointid, routeman.sequence
                        , checkpoint.cname, checkpoint.cgeolat, checkpoint.cgeolong, checkpoint.eta
                        , checkpoint.phoneno, checkpoint.email, checkpoint.isSms, checkpoint.isEmail
                        FROM routeman
                        left outer join checkpoint on checkpoint.checkpointid = routeman.checkpointid
                        where routeman.routeid=%d and routeman.customerno=%d and routeman.isdeleted=0 ";

            foreach ($chkpts as $chk) {
                if ($customerno == speedConstants::CUSTNO_RKFOODLANDS && $chk->sequence == 1) {
                    $Querychk .= "and routeman.sequence > " . $chk->sequence;
                } else {
                    $Querychk .= "and routeman.sequence = " . ($chk->sequence + 1);
                }
                $SQL = sprintf($Querychk, $chk->routeid, $customerno);
                $this->_databaseManager->executeQuery($SQL);
                if ($this->_databaseManager->get_rowCount() > 0) {
                    while ($row = $this->_databaseManager->get_nextRow()) {
                        $ch = new VOCheckpoint();
                        $ch->checkpintid = $row['checkpointid'];
                        $ch->routeid = $row['routeid'];
                        $ch->rmid = $row['rmid'];
                        $ch->cname = $row['cname'];
                        $ch->cgeolat = $row['cgeolat'];
                        $ch->cgeolong = $row['cgeolong'];
                        $ch->phoneno = $row['phoneno'];
                        $ch->email = $row['email'];
                        $ch->isSms = $row['isSms'];
                        $ch->isEmail = $row['isEmail'];
                        $ch->sequence = $row['sequence'];
                        $ch->eta = $row['eta'];
                        $list[] = $ch;
                    }
                }
            }
        }

        return $list;
    }

    public function getLedgerPDF($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();

        $arrResult = array();
        $sp_params =
        "'" . $obj->ledgerid . "'" .
        ",'" . $obj->fromdate . "'" .
        ",'" . $obj->todate . "'";

        $queryCallSP = "CALL " . speedConstants::SP_GET_LEDGER_DETAILS . "($sp_params)";
        $queryCallSP1 = "CALL " . speedConstants::SP_GET_LEDGER_PAYMENT_DETAILS . "($sp_params)";

        $arrResult[0] = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $arrResult[1] = $pdo->query($queryCallSP1)->fetchall(PDO::FETCH_ASSOC);

        return $arrResult;
    }

    public function getOpeningBalance($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();

        $sp_params =
        "'" . $obj->ledgerid . "'" .
        ",'" . $obj->fromdate . "'";

        $queryCallSP = "CALL " . speedConstants::SP_GET_OPENING_BALANCE . "($sp_params)";

        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        return $arrResult;
    }

    public function getAllLedger() {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();

        $queryCallSP = "CALL " . speedConstants::SP_GET_ALL_LEDGERS;
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        foreach ($arrResult as $ledgers) {
            $ledgerid[] = $ledgers['ledgerid'];
        }
        return $ledgerid;
    }

    public function getCustomerHardwareInvoice($ledgerid) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();

        $queryCallSP = "CALL " . speedConstants::SP_GET_ALL_HARDWARE_INVOICES . "($ledgerid)";

        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        if ($arrResult != NULL) {
            foreach ($arrResult as $data) {
                $invoices['ledgerid'] = $data['ledgerid'];
                $invoices['customerno'] = $data['customerno'];
                $invoices['unitprice'] = $data['unitprice'];
                $invoices['count1'] = $data['count1'];
                $invoices['total'] = $data['total'];
                $invoices['state_code'] = $data['state_code'];
                $invoices['state'] = $data['state'];
            }

            $sql = "select invoiceid from `invoice` order by invoiceid desc limit 1";
            $this->_databaseManager->executeQuery($sql);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row1 = $this->_databaseManager->get_nextRow()) {
                    $invoices['invoiceid'] = $row1['invoiceid'] + 1;
                }
            }

            $sql1 = sprintf("SELECT d.deviceid
                                    ,u.uid
                                    ,u.vehicleid
                                    ,vehicle.vehicleno
                            FROM    ledger_veh_mapping lv
                            INNER JOIN `ledger` l ON l.`ledgerid` = lv.`ledgerid`
                            INNER JOIN `state_gst_code` sgc ON sgc.codeid = l.`state_code`
                            INNER JOIN `customer` ON `customer`.`customerno` = lv.`customerno`
                            INNER JOIN `vehicle` ON `vehicle`.vehicleid = lv.vehicleid AND `vehicle`.invoice_hold = 0
                            INNER JOIN `unit` u ON u.vehicleid = vehicle.vehicleid AND u.trans_statusid IN (5,6)
                            INNER JOIN `devices` d ON d.uid = u.uid
                            INNER JOIN `simcard` s ON s.id = d.simcardid AND s.trans_statusid IN (13,14)
                            where   lv.ledgerid= %d
                            AND     lv.isdeleted = 0;", Sanitise::Long($ledgerid));

            $this->_databaseManager->executeQuery($sql1);
            if ($this->_databaseManager->get_rowCount() > 0) {
                $device = array();
                while ($row1 = $this->_databaseManager->get_nextRow()) {
                    $device[] = $row1['deviceid'];
                    $vehicle['uid'] = $row1['uid'];
                    $vehicle['vehicleid'] = $row1['vehicleid'];
                    $vehicle['vehicleno'] = $row1['vehicleno'];
                    $vehicles[] = $vehicle;
                }
                $invoices['deviceid'] = $device;
                $invoices['vehicleid'] = $vehicles;
            }
            return $invoices;
        } else {
            return NULL;
        }
    }

    public function updateVehicleRouteDirection($objData) {
        $Query = "Update vehicle Set routeDirection=%d WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($objData->directionStatus), Sanitise::Long($objData->vehicleid), Sanitise::Long($objData->customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function updateTripCount($objData) {
        $Query = "UPDATE dailyreport Set trip_count=trip_count+1 WHERE vehicleid = %d AND customerno = %d AND daily_date = '%s'";
        $SQL = sprintf($Query, Sanitise::Long($objData->vehicleId), Sanitise::Long($objData->customerno), Sanitise::Date($objData->daily_date));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function fetchInvoiceReminders() {
        return 'Here in fetchInvoiceReminders from CronManager.php';exit();
        $today = date('Y-m-d H:i:s');
        $pdo = $this->_databaseManager->CreatePDOConnForTech();
        $sp_params = "'$today'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_INVOICE_REMINDERS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        return $arrResult;
    }

    public function getVehicleNo($vehicleId, $customerNo) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "" . $vehicleId . "" .
            "," . $customerNo . "";
        $queryCallSP = "CALL " . speedConstants::SP_GET_VEHICLE_NUMBER_BY_VEHICLEID . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        if (isset($arrResult[0]['vehicleNo'])) {
            return $arrResult[0]['vehicleNo'];
        } else {
            return null;
        }
    }

    public function getPendingTimesheets() {
        $today = date('Y-m-d H:i:s');
        $today = date('Y-m-d H:i:s', (strtotime('-1 day', strtotime($today))));

        $pdo = $this->_databaseManager->CreatePDOConnForTech();
        $query = "SELECT distinct t.name,t.teamId,t.email,COALESCE(SEC_TO_TIME(sum(`time`)),'00:00:00') as totalHours
                FROM team t
                left outer join locked_timesheets lt ON t.teamId = lt.teamId AND lt.date = date('" . $today . "')
                left outer join timeSheet ts ON ts.teamId = t.teamId AND ts.date = date('" . $today . "')
                where  t.isTimed = 1 and lt.teamId IS NULL
                group by t.teamId";
        $result['unlockedTimesheets'] = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
        $message = "People who haven't locked their timesheets : (" . $today . ")";
        $message .= "<style>td{border-left:1px solid black;border-top:1px solid black;}table{border-right:1px solid black;border-bottom:1px solid black;}</style><div><table><thead><th>Name</th><th>Total Hours</th>";
        $response = array();
        $unlockedArray = array();
        $lockedArray = array();
        foreach ($result['unlockedTimesheets'] as $person) {
            $unlockedArray[] = $person;
            $message .= "<tr>";
            $message .= "<td>";
            $message .= $person['name'];
            $message .= "</td>";
            $message .= "<td>";
            $message .= $person['totalHours'];
            $message .= "</td>";
            $message .= "</tr>";
        }
        $message .= "</table><br>";
        $query = "SELECT distinct t.name,t.teamId,COALESCE(SEC_TO_TIME(sum(`time`)),'00:00:00') as totalHours
                FROM team t
                left outer join locked_timesheets lt ON t.teamId = lt.teamId AND lt.date = date('" . $today . "')
                left outer join timeSheet ts ON ts.teamId = t.teamId AND ts.date = date('" . $today . "')
                where  t.isTimed = 1 and lt.teamId IS NOT NULL
                group by t.teamId";
        $result['lockedTimesheets'] = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
        $message .= "People who have locked their timesheets : (" . $today . ")";
        $message .= "<div><table><thead><th>Name</th><th>Total Hours</th>";
        foreach ($result['lockedTimesheets'] as $person) {
            $lockedArray[] = $person;
            $message .= "<tr>";
            $message .= "<td>";
            $message .= $person['name'];
            $message .= "</td>";
            $message .= "<td>";
            $message .= $person['totalHours'];
            $message .= "</td>";
            $message .= "</tr>";
        }
        //echo $message;
        $this->_databaseManager->ClosePDOConn($pdo);
        $response['unlocked'] = $unlockedArray;
        $response['locked'] = $lockedArray;
        $response['consolidated'] = $message;
        return $response;
    }

    public function getTimedMembers() {
        $pdo = $this->_databaseManager->CreatePDOConnForTech();
        $query = "SELECT name,email,teamid FROM team WHERE isTimed = 1 and is_deleted = 0;";
        $result['members'] = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDummyUnits() {
        $Query = '  SELECT  uid
                            ,unitno
                            ,customerno
                    FROM    unit
                    WHERE   customerno IN (644,524,646,206,353,523,674,643,632,613)
                    AND     unitno LIKE "D%"
                    OR customerno = 895';
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $detail = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $unit = new VOUnit();
                $unit->uid = $row['uid'];
                $unit->unitno = $row['unitno'];
                $unit->customerno = $row['customerno'];
                $detail[] = $unit;
            }
            return $detail;
        }
        return NULL;
    }

    public function insertDuplicateSqliteData($request) {
        $today = date('Y-m-d H:i:s');
        $Query = "  INSERT INTO duplicateSqliteData(unitid
                        , sqlitedate
                        , customerno
                        , created_on)
                    VALUES (%d,'%s',%d,'%s')";
        $SQL = sprintf($Query, $request->uid, $request->date, $request->customerno, $today);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markAdvTemperatureInterval($request) {
        if (!empty($request)) {
            $column = '';
            if ($request->type = 1) {
                $column = 'temp' . $request->tempSensor . '_intv_sms';
            }
            if ($request->type = 2) {
                $column = 'temp' . $request->tempSensor . '_intv_email';
            }
            $interval = '';
            if ($request->switch == 0) {
                $interval = '0000-00-00 00:00:00';
            }
            if ($request->switch == 1) {
                $interval = $request->lastupdated;
            }

            if ($column != '' && $interval != '') {
                $Query = "Update advancetempalertrange SET `%s` = '%s' WHERE vehicleid = %d AND customerno=%d AND userid = %d";
                $SQL = sprintf($Query, Sanitise::String($column), Sanitise::DateTime($interval), Sanitise::Long($request->vehicleid), Sanitise::Long($request->customerno), Sanitise::Long($request->userid));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
    }

    public function getalldevicesforAdvancetempsensor() {
        $devices = array();
        $time = date("Y-m-d H:i:s", strtotime("-120 seconds"));
        $Query = "select    d.deviceid
                            , d.devicelat
                            , d.devicelong
                            , ea.temp_sms
                            , ea.temp2_sms
                            , ea.temp3_sms
                            , ea.temp4_sms
                            , ea.temp_email
                            , ea.temp2_email
                            , ea.temp3_email
                            , ea.temp4_email
                            , v.vehicleid
                            , v.vehicleno
                            , d.status
                            , c.temp_sensors
                            , c.customerno
                            , u.uid
                            , d.lastupdated
                            , u.tempsen1
                            , u.tempsen2
                            , u.tempsen3
                            , u.tempsen4
                            , u.n1
                            , u.n2
                            , u.n3
                            , u.n4
                            , u.analog1
                            , u.analog2
                            , u.analog3
                            , u.analog4
                            , v.temp1_min
                            , v.temp1_max
                            , v.temp2_min
                            , v.temp2_max
                            , v.temp3_min
                            , v.temp3_max
                            , v.temp4_min
                            , v.temp4_max
                            , atar.temp1_min_sms
                            , atar.temp1_max_sms
                            , atar.temp2_min_sms
                            , atar.temp2_max_sms
                            , atar.temp3_min_sms
                            , atar.temp3_max_sms
                            , atar.temp4_min_sms
                            , atar.temp4_max_sms
                            , atar.temp1_min_email
                            , atar.temp1_max_email
                            , atar.temp2_min_email
                            , atar.temp2_max_email
                            , atar.temp3_min_email
                            , atar.temp3_max_email
                            , atar.temp4_min_email
                            , atar.temp4_max_email
                            , atar.temp1_intv_sms
                            , atar.temp2_intv_sms
                            , atar.temp3_intv_sms
                            , atar.temp4_intv_sms
                            , atar.temp1_intv_email
                            , atar.temp2_intv_email
                            , atar.temp3_intv_email
                            , atar.temp4_intv_email
                            , v.temp1_mute
                            , v.temp2_mute
                            , v.temp3_mute
                            , v.temp4_mute
                            , u.get_conversion
                            , c.use_humidity
                            , v.kind
                            , atar.userid
                    FROM    devices d
                    INNER JOIN unit u ON u.uid = d.uid
                    INNER JOIN " . DB_PARENT . ".customer c ON c.customerno = d.customerno
                    INNER JOIN vehicle v on v.vehicleid = u.vehicleid
                    INNER JOIN advtempeventalerts ea ON ea.vehicleid = v.vehicleid
                    INNER JOIN advancetempalertrange atar ON atar.vehicleid = v.vehicleid AND ea.userid = atar.userid AND atar.isdeleted = 0
                    where   u.trans_statusid NOT IN (10,22)
                    AND     c.temp_sensors > 0
                    AND     d.customerno NOT IN (1)
                    AND     d.lastupdated >= '$time'";
        $devicesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->uid = $row['uid'];
                $device->deviceid = $row['deviceid'];
                $device->devicelat = $row['devicelat'];
                $device->devicelong = $row['devicelong'];
                $device->vehicleid = $row['vehicleid'];
                $device->vehicleno = $row['vehicleno'];
                $device->lastupdated = $row['lastupdated'];
                $device->status = $row['status'];
                $device->tempsen1 = $row['tempsen1'];
                $device->tempsen2 = $row['tempsen2'];
                $device->tempsen3 = $row['tempsen3'];
                $device->tempsen4 = $row['tempsen4'];
                $device->analog1 = $row['analog1'];
                $device->analog2 = $row['analog2'];
                $device->analog3 = $row['analog3'];
                $device->analog4 = $row['analog4'];
                $device->n1 = $row['n1'];
                $device->n2 = $row['n2'];
                $device->n3 = $row['n3'];
                $device->n4 = $row['n4'];
                $device->temp1_min = $row['temp1_min'];
                $device->temp1_max = $row['temp1_max'];
                $device->temp2_min = $row['temp2_min'];
                $device->temp2_max = $row['temp2_max'];
                $device->temp3_min = $row['temp3_min'];
                $device->temp3_max = $row['temp3_max'];
                $device->temp4_min = $row['temp4_min'];
                $device->temp4_max = $row['temp4_max'];
                $device->temp1_min_sms = $row['temp1_min_sms'];
                $device->temp1_max_sms = $row['temp1_max_sms'];
                $device->temp2_min_sms = $row['temp2_min_sms'];
                $device->temp2_max_sms = $row['temp2_max_sms'];
                $device->temp3_min_sms = $row['temp3_min_sms'];
                $device->temp3_max_sms = $row['temp3_max_sms'];
                $device->temp4_min_sms = $row['temp4_min_sms'];
                $device->temp4_max_sms = $row['temp4_max_sms'];
                $device->temp1_min_email = $row['temp1_min_email'];
                $device->temp1_max_email = $row['temp1_max_email'];
                $device->temp2_min_email = $row['temp2_min_email'];
                $device->temp2_max_email = $row['temp2_max_email'];
                $device->temp3_min_email = $row['temp3_min_email'];
                $device->temp3_max_email = $row['temp3_max_email'];
                $device->temp4_min_email = $row['temp4_min_email'];
                $device->temp4_max_email = $row['temp4_max_email'];
                $device->temp_sensors = $row['temp_sensors'];
                $device->temp_status_sms = $row['temp_sms'];
                $device->temp2_status_sms = $row['temp2_sms'];
                $device->temp3_status_sms = $row['temp3_sms'];
                $device->temp4_status_sms = $row['temp4_sms'];
                $device->temp_status_email = $row['temp_email'];
                $device->temp2_status_email = $row['temp2_email'];
                $device->temp3_status_email = $row['temp3_email'];
                $device->temp4_status_email = $row['temp4_email'];
                $device->temp1_intv_sms = $row['temp1_intv_sms'];
                $device->temp2_intv_sms = $row['temp2_intv_sms'];
                $device->temp3_intv_sms = $row['temp3_intv_sms'];
                $device->temp4_intv_sms = $row['temp4_intv_sms'];
                $device->temp1_intv_email = $row['temp1_intv_email'];
                $device->temp2_intv_email = $row['temp2_intv_email'];
                $device->temp3_intv_email = $row['temp3_intv_email'];
                $device->temp4_intv_email = $row['temp4_intv_email'];
                $device->customerno = $row['customerno'];
                $device->kind = $row['kind'];
                $device->get_conversion = $row['get_conversion'];
                $device->use_humidity = $row['use_humidity'];
                $device->temp1_mute = $row['temp1_mute'];
                $device->temp2_mute = $row['temp2_mute'];
                $device->temp3_mute = $row['temp3_mute'];
                $device->temp4_mute = $row['temp4_mute'];
                $device->userid = $row['userid'];
                $devices[] = $device;
            }
            return $devices;
        }
        return NULL;
    }

    public function getVehiclesforMondelezDump($request) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sp_params = "'" . $request->customerList . "'"
        . ",'" . $request->todaysdate . "'";
        $queryCallSP = $this->PrepareSP(speedConstants::SP_MDLZ_DUMP_VEHICLE_DATA, $sp_params);
        $arrPhoneNos = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
    }

/* Function to fetch all devices and checkpoints for updating tripstatus in tripdetails table starts here */
    public function getAllDevicesForTripDetails($customerExceptionList = NULL, $customerList = NULL) {
        $devices = array();
        $time = date("Y-m-d H:i:s", strtotime("-60 seconds"));
        $Query = "select devices.deviceid,devices.devicelat,devices.devicelong,
            vehicle.vehicleid,vehicle.vehicleno,vehicle.checkpointId AS vehChkPtId,checkpoint.cgeolat,checkpoint.cgeolong,
            checkpoint.cname,checkpointmanage.cmid,checkpoint.crad,
            devices.customerno, devices.uid, checkpoint.checkpointid, devices.lastupdated,
            checkpoint.phoneno,checkpoint.email,checkpoint.isSms,checkpoint.isEmail,checkpoint.eta
            , checkpointmanage.conflictstatus, checkpointmanage.inTime, checkpointmanage.outTime, checkpointmanage.isDelayExpected, vehicle.routeDirection
            , checkpoint.chktype
            from devices
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON unit.vehicleid = vehicle.vehicleid
            INNER JOIN checkpointmanage ON checkpointmanage.vehicleid = vehicle.vehicleid
            INNER JOIN checkpoint ON checkpoint.checkpointid = checkpointmanage.checkpointid
            INNER JOIN tripdetails ON tripdetails.customerno = checkpointmanage.customerno AND tripdetails.vehicleid = checkpointmanage.vehicleid
            where checkpoint.isdeleted =0
            AND checkpointmanage.isdeleted = 0
            AND checkpoint.cname = 'Factory'
            AND tripdetails.isdeleted=0
            AND unit.trans_statusid NOT IN (10,22)";
        if (isset($customerList)) {
            $Query .= " AND checkpoint.customerno IN (" . $customerList . ") ";
        }
        if (isset($customerExceptionList)) {
            $Query .= " AND checkpoint.customerno NOT IN (" . $customerExceptionList . ") ";
        }
        $Query .= " AND devices.lastupdated >= '$time' "
            . " ORDER BY checkpointmanage.conflictstatus ASC";
        $devicesQuery = sprintf($Query); //echo"query is: ".$devicesQuery; exit();
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row['uid'] > 0) {
                    $device = new stdClass();
                    $device->deviceid = $row['deviceid'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->vehChkPtId = $row['vehChkPtId'];
                    $device->customerno = $row['customerno'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->cgeolat = $row['cgeolat'];
                    $device->cgeolong = $row['cgeolong'];
                    $device->conflictstatus = $row['conflictstatus'];
                    $device->checkpointid = $row['checkpointid'];
                    $device->cname = $row['cname'];
                    $device->cmid = $row['cmid'];
                    $device->crad = $row['crad'];
                    $device->phoneno = $row['phoneno'];
                    $device->email = $row['email'];
                    $device->isSms = $row['isSms'];
                    $device->isEmail = $row['isEmail'];
                    $device->eta = $row['eta'];
                    $device->inTime = $row['inTime'];
                    $device->outTime = $row['outTime'];
                    $device->isDelayExpected = $row['isDelayExpected'];
                    $device->routeDirection = $row['routeDirection'];
                    $device->chktype = $row['chktype'];
                    $devices[] = $device;
                }
            }
            return $devices;
        }
        return NULL;
    }

/* Function to fetch all devices and checkpoints for updating tripstatus in tripdetails table ends here */

    public function getOdometerResetUnits($customerno) {
        $Query = '  SELECT  uid
                            ,unitno
                            ,customerno
                    FROM    unit
                    WHERE   customerno IN (' . $customerno . ')';
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $detail = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $unit = new VOUnit();
                $unit->uid = $row['uid'];
                $unit->unitno = $row['unitno'];
                $unit->customerno = $row['customerno'];
                $detail[] = $unit;
            }
            return $detail;
        }
        return NULL;
    }

    /* Function to update the routeDirection starts here */
    public function updateRealTimeRouteDirection($checkPointIdParam, $vehicleIdParam, $inOutStatusParam, $customerNo) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sp_params = "'" . $checkPointIdParam . "'"
            . ",'" . $vehicleIdParam . "'"
            . ",'" . $inOutStatusParam . "'";
        //. ",'" . $customerNo . "'";
        /* $queryCallSP = $this->PrepareSP(speedConstants::SP_MDLZ_DUMP_VEHICLE_DATA, $sp_params); */
        $queryCallSP = $this->PrepareSP('update_vehicle_route_direction', $sp_params);
        $pdo->query($queryCallSP);
        $this->_databaseManager->ClosePDOConn($pdo);
    }

/* Function to update the routeDirection ends here */

    public function getInvoiceHoldStatus($obj) {
        $db = new DatabaseManager();
        $sp_params = "'" . $obj->customerNo . "'" .
        ",'" . $obj->statusId . "'";
        $pdo = $db->CreatePDOConn();
        $queryCallSP = "CALL " . speedConstants::SP_FETCH_INVOICE_HOLD_STATUS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function insertAlertTempUserMapping() {
        $db = new DatabaseManager();
        $today = date('Y-m-d H:i:s');
        $sp_params = "'" . $today . "'";
        $pdo = $db->CreatePDOConn();
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_ALERT_TEMP_USER_MAPPING . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function insertTempSensorSpecificAlert() {
        $db = new DatabaseManager();
        $today = date('Y-m-d H:i:s');
        $sp_params = "'" . $today . "'";
        $pdo = $db->CreatePDOConn();
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_TEMP_SENSOR_SPECIFIC_ALERT . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        return $arrResult;
    }
        
        public function getCustomerList($obj) {
            $db = new DatabaseManager();
            $sp_params = "'" . $obj->customerno . "'";
            $pdo = $db->CreatePDOConn();
            $queryCallSP = "CALL " . speedConstants::SP_GET_CUSTOMER_LIST . "(" . $sp_params . ")";
            $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
            $db->ClosePDOConn($pdo);
            return $arrResult;
	}

    public function get_payment_collection_report() {
        $todaysDate = date('Y-m-d', strtotime("+ 1 day"));
        $fromDate = date('Y-m-01');
        $pc_list = array();
        $query = "SELECT ipm.paymentdate, c.customerno, c.customercompany, te.name, ipm.paid_amt, ipm.pay_mode
                FROM " . DB_PARENT . ".invoice_payment_mapping ipm
                INNER JOIN " . DB_PARENT . ".customer c ON c.customerno = ipm.customerno
                LEFT OUTER JOIN " . DB_PARENT . ".team te ON te.teamid = ipm.teamid
                WHERE date(ipm.created_on) BETWEEN date('". $fromDate . "')  AND date('". $todaysDate . "') AND ipm.isdeleted = 0 AND ipm.status!='rejected'
                ORDER BY c.customerno";
                // echo $query; exit;
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $pc = new tasks();
                
                $pc->customercompany = $row["customercompany"];
                $pc->paymentdate = $row["paymentdate"];
                $pc->customerno = $row["customerno"];
                $pc->name = $row['name'];
                $pc->paid_amt = $row['paid_amt'];
                $pc->pay_mode = $row['pay_mode'];
                $pc_list[] = $pc;
            }
            return $pc_list;
        }
        return null;
    }

    public function updateInvoiceApproval($request,$devices) {
        
        $query=sprintf("   UPDATE `invoice_approval`
                                SET     `isapproved` = 1
                                WHERE   `iv_id` = %d;"
                    , $request);
        $this->_databaseManager->executeQuery($query);

        $query1=sprintf(" INSERT INTO `invoice` (invoiceno, customerno, ledgerid, inv_date, inv_amt, pending_amt, tax, cgst, sgst, igst, start_date, end_date, inv_expiry, product_id, `invoice`.`timestamp`, quantity, `total_amount`,`tax_amount`,`state`,`lease`, is_mail_sent)      
            SELECT invoiceno, customerno, ledgerid, inv_date, inv_amt, pending_amt, tax, cgst, sgst, igst, start_date, end_date, inv_expiry, product_id,`invoice_approval`.`timestamp`, quantity,`total_amount`,`tax_amount`,`state`,`lease`, is_mail_sent
            FROM `invoice_approval`
            WHERE isapproved = 1 and `iv_id` = %d;",$request);
            $result=$this->_databaseManager->executeQuery($query1);
         $invoiceid = $this->_databaseManager->get_insertedId($result);
         // print_r($invoiceid); exit;
         
         if (isset($devices) && !empty($devices)) {
             $query2= sprintf(" UPDATE `devices` t2,
                    (   SELECT `start_date`, `end_date`, `expirydate`, `invoiceno`
                        FROM invoice t1
                        WHERE invoiceid= %d
                    ) t1
                    SET t2.`start_date` = t1.`start_date`,t2.`end_date` = t1.`end_date`,t2.`expirydate` = t1.`expirydate`,t2.`invoiceno` = t1.`invoiceno`
                    WHERE t2.`deviceid` IN ('%s');", $invoiceid,$devices);
        
         $this->_databaseManager->executeQuery($query2);
         }
         
         
         return $invoiceid;

}


    public function invoiceVehicleMapping($invoiceid,$data) {
        $datetime = date('Y-m-d H:i:s');
        $SQL = sprintf("INSERT INTO `invoice_vehicle_mapping`(`invoiceid`
                                ,`vehicleid`
                                ,`vehicleno`
                                ,`uid`
                                ,`createdon`
                                ,`isdeleted`)
                                VALUES(%d,%d,'%s',%d,'%s',0);", Sanitise::Long($invoiceid)
                        , Sanitise::Long($data['vehicleid'])
                        , Sanitise::String($data['vehicleno'])
                        , Sanitise::Long($data['uid'])
                        , Sanitise::DateTime($datetime));

                $db->executeQuery($SQL);
       
        return 1;

    }

    public function insertInvoiceApproval($request) {
        $isExist==0;
       
        if (!empty($request['start_date'])) {
            // echo '123'; exit;
         $start_date = $request['start_date'];
        }
        else
        {
           $start_date = date('Y-m-d H:i:s');
        }
        if (!empty($request['end_date'])) {
         $end_date = $request['end_date'];
        }
        else
        {
           $end_date = date('Y-m-d H:i:s');
        }
        if ($request['expiry_date']==0) {
         $request['expiry_date'] = date('Y-m-d H:i:s');
        }
        if ($request['timestamp']==0) {
         $request['timestamp']= date('Y-m-d H:i:s');
        }
        $today = date('Y-m-d H:i:s');

        $sql= sprintf(" SELECT iv_id
                           FROM `invoice_approval` 
                           WHERE `customerno`= %d AND `ledgerid`=%d AND `invoiceno`='%s';", $request['customerno'],$request['ledgerid'],$request['invoiceno']);
        $data=$this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $isExist=1;
        }
        if ($isExist==0) {
           
$query = sprintf("INSERT INTO `invoice_approval`(`invoiceno`
                                ,`customerno`
                                ,`ledgerid`
                                ,`inv_date`
                                ,`inv_amt`
                                ,`status`
                                ,`pending_amt`
                                ,`tax`
                                ,`cgst`
                                ,`sgst`
                                ,`igst`
                                ,`start_date`
                                ,`end_date`
                                ,`inv_expiry`
                                ,`product_id`
                                ,`timestamp`
                                ,`quantity`
                                ,`subscription_price`
                                ,`total_amount`
                                ,`tax_amount`
                                ,`state`
                                ,`lease`
                                ,`is_mail_sent`
                                ,`isapproved`)
                                VALUES('%s',%d,%d,'%s',%d,'pending',%d,'%d',%d,%d,%d,'%s','%s','%s',2,'%s',%d,%d,%d,%d,'%s','%s',0,0);"
                    , Sanitise::String($request['invoiceno'])
                    , Sanitise::Long($request['customerno'])
                    , Sanitise::Long($request['ledgerid'])
                    , Sanitise::DateTime($today)
                    , Sanitise::Long($request['amount'])
                    , Sanitise::Long($request['amount'])
                    , Sanitise::Long($request['taxname'])
                    , Sanitise::Long($request['tax_cgst'])
                    , Sanitise::Long($request['tax_sgst'])
                    , Sanitise::Long($request['tax_igst'])
                    , Sanitise::DateTime($start_date)
                    , Sanitise::DateTime($end_date)
                    , Sanitise::DateTime($request['expiry_date'])
                    , Sanitise::DateTime($today)
                    , Sanitise::Long($request['noofdevices'])
                    , Sanitise::Long($request['subscriptionprice'])
                    , Sanitise::Long($request['amount'])
                    , Sanitise::Long($request['taxamount'])
                    , Sanitise::Long($request['state'])
                    , Sanitise::Long($request['lease']));
       
        $result=$this->_databaseManager->executeQuery($query);
        // echo $result; exit;
        // if ($this->_databaseManager->get_rowCount() > 0) {
        //     return 1;
        // }
        // else
        // {
        //     return 0;
        // }
           }
       
}


public function getInvoiceApproval() {
        $pc_list = array();
        $query = "SELECT *
                  FROM invoice_approval
                  WHERE isapproved=0";
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $pc = new tasks();
                $pc->iv_id = $row["iv_id"];
                $pc->invoiceno = $row["invoiceno"];
                $pc->customerno = $row["customerno"];
                $pc->ledgerid = $row["ledgerid"];
                $pc->inv_date = $row['inv_date'];
                $pc->inv_amt = $row['inv_amt'];
                $pc->status = $row['status'];
                $pc->pending_amt = $row["pending_amt"];
                $pc->tax = $row["tax"];
                $pc->cgst = $row["cgst"];
                $pc->sgst = $row['sgst'];
                $pc->igst = $row['igst'];
                $pc->start_date = $row['start_date'];
                $pc->end_date = $row["end_date"];
                $pc->inv_expiry = $row["inv_expiry"];
                $pc->product_id = $row['product_id'];
                $pc->timestamp = $row['timestamp'];
                $pc->quantity = $row['quantity'];
                $pc->subscription_price = $row['subscription_price'];
                $pc->total_amount = $row['total_amount'];
                $pc->tax_amount = $row['tax_amount'];
                $pc->state = $row['state'];
                $pc->lease = $row['lease'];
                $pc->is_mail_sent = $row['is_mail_sent'];
                $pc_list[] = $pc;
            }
            return $pc_list;
        }
        return null;
    }
}

?>
