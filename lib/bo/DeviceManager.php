<?php
if (!isset($RELATIVE_PATH_DOTS) || $RELATIVE_PATH_DOTS == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
class DeviceManager extends VersionedManager {
    public function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function getlastupdateddatefordevices($customerno) {
        $devices = array();
        $Query = "SELECT unit.unitno, devices.deviceid,vehicle.vehicleid,
            devices.uid, vehicle.vehicleno, devices.lastupdated, devices.customerno, vehicle.overspeed_limit from devices
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            where devices.customerno = %d AND unit.trans_statusid NOT IN (10,22)";
        $devicesQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->vehicleid = $row['vehicleid'];
                $device->uid = $row['uid'];
                $device->unitno = $row['unitno'];
                $device->vehicleno = $row['vehicleno'];
                $device->lastupdated = $row['lastupdated'];
                $device->overspeed_limit = $row['overspeed_limit'];
                $device->customerno = $row['customerno'];
                $devices[] = $device;
            }
            return $devices;
        }
    }

    public function getzones($customerno) {
        $devices = array();
        $Query = "SELECT name, districtid from district
            where district.customerno = %d order by name";
        $devicesQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->name = $row['name'];
                $device->zoneid = $row['districtid'];
                $devices[] = $device;
            }
            return $devices;
        }
    }

    public function getvehtype($customerno) {
        $new = 0;
        $repossessed = 0;
        $undefined = 0;
        $total = 0;
        $employee = 0;
        $Query = "SELECT vehicle_type from description
            INNER JOIN vehicle ON description.vehicleid=vehicle.vehicleid
            where vehicle.customerno = %d AND vehicle.isdeleted=0";
        $devicesQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row["vehicle_type"] == '1') {
                    $new += 1;
                    $total += 1;
                } elseif ($row["vehicle_type"] == '2') {
                    $repossessed += 1;
                    $total += 1;
                } elseif ($row["vehicle_type"] == '3') {
                    $employee += 1;
                    $total += 1;
                } else {
                    $undefined += 1;
                    $total += 1;
                }
            }
            $device = new VODevices();
            $device->new = $new;
            $device->repossessed = $repossessed;
            $device->employee = $employee;
            $device->undefined = $undefined;
            $device->total = $total;
            return $device;
        }
    }

    public function getvehpurpose($customerno) {
        $employeectc = 0;
        $branch = 0;
        $zone = 0;
        $regional = 0;
        $ho = 0;
        $total = 0;
        $undefined = 0;
        $Query = "SELECT vehicle_purpose from description
            INNER JOIN vehicle ON description.vehicleid=vehicle.vehicleid
            where vehicle.customerno = %d AND vehicle.isdeleted=0";
        $devicesQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row["vehicle_purpose"] == '1') {
                    $employeectc += 1;
                    $total += 1;
                } elseif ($row["vehicle_purpose"] == '2') {
                    $branch += 1;
                    $total += 1;
                } elseif ($row["vehicle_purpose"] == '3') {
                    $zone += 1;
                    $total += 1;
                } elseif ($row["vehicle_purpose"] == '4') {
                    $regional += 1;
                    $total += 1;
                } elseif ($row["vehicle_purpose"] == '5') {
                    $ho += 1;
                    $total += 1;
                } else {
                    $undefined += 1;
                    $total += 1;
                }
            }
            $device = new VODevices();
            $device->ctc = $employeectc;
            $device->branch = $branch;
            $device->zone = $zone;
            $device->region = $regional;
            $device->ho = $ho;
            $device->undefined = $undefined;
            $device->total = $total;
            return $device;
        }
    }

    public function gettotalnodevices($customerno) {
        $total = 0;
        $Query = "SELECT count(*) as total from vehicle
            where vehicle.customerno = %d AND vehicle.isdeleted=0";
        $devicesQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $total = $row["total"];
            }
            return $total;
        }
    }

    public function getnovehmandate($manyear, $customerno) {
        $total = 0;
        $Query = "SELECT count(*) as total from vehicle
            where vehicle.customerno = %d AND vehicle.manufacturing_year = %d AND vehicle.isdeleted=0";
        $devicesQuery = sprintf($Query, $customerno, $manyear);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $total = $row["total"];
            }
            return $total;
        }
    }

    public function getnovehpurdate($puryear, $customerno) {
        $total = 0;
        $Query = "SELECT count(*) as total from vehicle
            where vehicle.customerno = %d AND YEAR(purchase_date) = '%s' AND vehicle.isdeleted=0";
        $devicesQuery = sprintf($Query, $customerno, $puryear);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $total = $row["total"];
            }
            return $total;
        }
    }

    public function getvehregistered($customerno) {
        $registered = 0;
        $notregistered = 0;
        $Query = "SELECT vehicleno from vehicle
            where vehicle.customerno = %d AND vehicle.isdeleted=0";
        $devicesQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row["vehicleno"] == 'DBH/NR/1') {
                    $notregistered += 1;
                } else {
                    $registered += 1;
                }
            }
            $device = new VODevices();
            $device->registered = $registered;
            $device->notregistered = $notregistered;
            return $device;
        }
    }

    public function getmanufacturingyear($customerno) {
        $devices = array();
        $Query = "SELECT distinct(manufacturing_year) from vehicle
            where vehicle.customerno = %d AND vehicle.isdeleted=0 order by manufacturing_year";
        $devicesQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        $noYearRowToBeAdded = 0;
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row["manufacturing_year"] == '0') {
                    $noYearRowToBeAdded = 1;
                } else {
                    $device = new VODevices();
                    $device->year = $row["manufacturing_year"];
                    $devices[] = $device;
                }
            }
            if ($noYearRowToBeAdded == 1) {
                $device = new VODevices();
                $device->year = "Not Provided";
                $devices[] = $device;
            }
            return $devices;
        }
    }

    public function getpurchaseyear($customerno) {
        $devices = array();
        $Query = "SELECT YEAR(purchase_date) as pur_year from vehicle
            where vehicle.customerno = %d AND vehicle.isdeleted=0 GROUP BY YEAR(purchase_date)";
        $devicesQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        $noYearRowToBeAdded = 0;
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row["pur_year"] == '0000') {
                    $noYearRowToBeAdded = 1;
                } else {
                    $device = new VODevices();
                    $device->year = $row["pur_year"];
                    $devices[] = $device;
                }
            }
            if ($noYearRowToBeAdded == 1) {
                $device = new VODevices();
                $device->year = "Not Provided";
                $devices[] = $device;
            }
            return $devices;
        }
    }

    public function getnoveh($zoneid, $customerno) {
        $total = 0;
        $Query = "SELECT count(*) as total from vehicle
            INNER JOIN `group` ON `group`.groupid = vehicle.groupid
            INNER JOIN city ON city.cityid = `group`.cityid
            INNER JOIN district ON district.districtid = city.districtid
            where district.districtid = %d AND vehicle.customerno = %d AND vehicle.isdeleted=0";
        $devicesQuery = sprintf($Query, $zoneid, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $total = $row["total"];
            }
            return $total;
        }
    }

    public function getnovehnotreg($zoneid, $customerno) {
        $total = 0;
        $Query = "SELECT count(*) as total from vehicle
            INNER JOIN `group` ON `group`.groupid = vehicle.groupid
            INNER JOIN city ON city.cityid = `group`.cityid
            INNER JOIN district ON district.districtid = city.districtid
            where district.districtid = %d AND vehicle.customerno = %d AND vehicle.vehicleno = 'DBH/NR/1' AND vehicle.isdeleted=0";
        $devicesQuery = sprintf($Query, $zoneid, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $total = $row["total"];
            }
            return $total;
        }
    }

    public function getvehinsured($customerno) {
        $insured = 0;
        $Query = "SELECT count(*) as insured from vehicle
                INNER JOIN insurance ON vehicle.vehicleid=insurance.vehicleid
                WHERE vehicle.customerno=%d AND vehicle.isdeleted=0";
        $insuredQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($insuredQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $insured = $row["insured"];
            }
        }
        return $insured;
    }

    public function getveh_notinsured($customerno) {
        $notinsured = 0;
        $total = 0;
        $insure = $this->getvehinsured($customerno);
        $Query1 = "SELECT count(*) as total from vehicle
                  WHERE vehicle.customerno=%d AND vehicle.isdeleted=0";
        $totQuery = sprintf($Query1, $customerno);
        $this->_databaseManager->executeQuery($totQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $total = $row['total'];
            }
        }
        $notinsured = $total - $insure;
        return $notinsured;
    }

    public function getveh_notinsuredzone($customerno, $zoneid) {
        $zoneinsured = 0;
        $Query = "SELECT count(*) as zoneinsured from vehicle
            INNER JOIN `group` ON `group`.groupid = vehicle.groupid
            INNER JOIN city ON city.cityid = `group`.cityid
            INNER JOIN district ON district.districtid = city.districtid
            WHERE district.districtid = %d AND vehicle.customerno=%d AND is_insured=0 AND vehicle.isdeleted=0";
        $deviceQuery = sprintf($Query, $zoneid, $customerno);
        $this->_databaseManager->executeQuery($deviceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $zoneinsured = $row['zoneinsured'];
            }
        }
        return $zoneinsured;
    }

    public function getveh_insureamtzone($customerno, $zoneid) {
        $zoneamt = 0;
        $Query = "SELECT sum(value) as zoneamt from insurance
             INNER JOIN vehicle ON insurance.vehicleid=vehicle.vehicleid
            INNER JOIN `group` ON `group`.groupid = vehicle.groupid
            INNER JOIN city ON city.cityid = `group`.cityid
            INNER JOIN district ON district.districtid = city.districtid
            WHERE district.districtid = %d AND vehicle.customerno=%d AND vehicle.isdeleted=0";
        $deviceQuery = sprintf($Query, $zoneid, $customerno);
        $this->_databaseManager->executeQuery($deviceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $zoneamt = $row['zoneamt'];
            }
        }
        return $zoneamt;
    }

    public function getinsurance_sums($customerno) {
        $Query = "SELECT sum(value) as val,sum(premium) as prem FROM `insurance`
             INNER JOIN vehicle ON insurance.vehicleid=vehicle.vehicleid
             WHERE vehicle.customerno=%d AND vehicle.isdeleted=0";
        $devicesQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $device = new VODevices();
            $device->value = $row['val'];
            $device->premium = $row['prem'];
            $device->percent = round((($row['prem'] / $row['val']) * 100), 2);
            return $device;
        }
    }

    public function getinsurance_expiry($customerno, $days) {
        $firstDayOfPrevMonth = date('Y-m-d', strtotime("first day of last month"));
        $lastDayOfPrevMonth = date("Y-m-t", strtotime($firstDayOfPrevMonth));
        //$today = date("Y-m-d");
        $days30 = date("Y-m-d", strtotime($lastDayOfPrevMonth . '+30 days'));
        $days45 = date("Y-m-d", strtotime($lastDayOfPrevMonth . '+45 days'));
        $days60 = date("Y-m-d", strtotime($lastDayOfPrevMonth . '+60 days'));
        $days75 = date("Y-m-d", strtotime($lastDayOfPrevMonth . '+75 days'));
        $days90 = date("Y-m-d", strtotime($lastDayOfPrevMonth . '+90 days'));
        $expiry = 0;
        $Query = "SELECT count(end_date) as expiry FROM `insurance`
             INNER JOIN vehicle ON insurance.vehicleid=vehicle.vehicleid
             WHERE vehicle.customerno=%d AND vehicle.isdeleted=0 ";
        if ($days == 30) {
            $Query .= "AND end_date < '$days30'";
            $devicesQuery = sprintf($Query, $customerno);
        }
        if ($days == 45) {
            $Query .= "AND end_date BETWEEN '$days30' AND '$days45'";
            $devicesQuery = sprintf($Query, $customerno);
        }
        if ($days == 60) {
            $Query .= "AND end_date BETWEEN '$days45' AND '$days60'";
            $devicesQuery = sprintf($Query, $customerno);
        }
        if ($days == 75) {
            $Query .= "AND end_date BETWEEN '$days60' AND '$days75'";
            $devicesQuery = sprintf($Query, $customerno);
        }
        if ($days == 90) {
            $Query .= "AND end_date BETWEEN '$days75' AND '$days90'";
            $devicesQuery = sprintf($Query, $customerno);
        }
        if ($days == 100) {
//Above 90 for reference days=100 is taken
            $Query .= "AND end_date > '$days90'";
            $devicesQuery = sprintf($Query, $customerno);
        }
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $expiry = $row['expiry'];
        }
        return $expiry;
    }

    public function getdetails_nicerglobe($customerno) {
        $devices = array();
        $Query = "SELECT devices.devicelat, vehicle.vehicleno, devices.devicelong, vehicle.curspeed, devices.directionchange, devices.lastupdated, devices.ignition from devices
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            where devices.customerno = %d AND unit.trans_statusid NOT IN (10,22)";
        $devicesQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->devicelat = $row["devicelat"];
                $device->devicelong = $row["devicelong"];
                $device->curspeed = $row["curspeed"];
                $device->directionchange = $row["directionchange"];
                $device->lastupdated = date("Y/m/d H:i:s", strtotime("-330 minutes", strtotime($row["lastupdated"])));
                $device->ignition = $row["ignition"];
                $device->vehicleno = $row["vehicleno"];
                $devices[] = $device;
            }
            return $devices;
        }
    }

    public function getlastupdateddatefordevicesreason($customerno) {
        $devices = array();
        $Query = "SELECT vehicle.nodata_alert, vehicle.groupid, unit.unitno, devices.deviceid,vehicle.vehicleid,
            devices.uid, vehicle.vehicleno, devices.lastupdated, devices.customerno, devices.ignition, devices.powercut, devices.tamper, devices.gsmstrength, devices.gprsregister, customer.customercompany from devices
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN " . DB_PARENT . ".customer ON devices.customerno = customer.customerno
            where devices.customerno = %d AND customer.use_maintenance = '0' AND unit.trans_statusid NOT IN (10,22) AND vehicle.nodata_alert <> 2 AND vehicle.isdeleted = 0";
        $devicesQuery = sprintf($Query, $customerno);
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
                $devices[] = $device;
            }
            return $devices;
        }
    }

    public function get_all_devices($user_groups = null) {
        $devices = Array();
        $Query = "SELECT devices.deviceid, vehicle.vehicleno,devices.installdate,vehicle.vehicleid, devices.invoiceno,
            devices.devicekey,simcard.simcardno as phone,devices.expirydate,devices.uid,devices.po_no,devices.po_date,
            devices.warrantyexpiry, unit.unitno, vehicle.groupid, registeredon,district.name as dname,
            group.groupname, group.code, city.name as cname,devices.device_invoiceno,
            Now() as today, devices.baselat,devices.baselng,devices.installlat,devices.installlng,devices.devicelat,devices.devicelong,
            devices.lastupdated
            FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            LEFT JOIN `group` ON vehicle.groupid=`group`.groupid
            LEFT JOIN city ON `group`.cityid=city.cityid
            LEFT JOIN district ON city.districtid=district.districtid
            LEFT JOIN state ON district.stateid=state.stateid
            LEFT JOIN nation ON state.nationid=nation.nationid
            LEFT OUTER JOIN simcard ON devices.simcardid = simcard.id
            where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22)";
        if ($user_groups != null) {
            $Query .= " AND vehicle.groupid in ($user_groups) ";
            $devicesQuery = sprintf($Query, $this->_Customerno);
        } else {
            if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
                $Query .= " AND vehicle.groupid =%d";
                $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
            } else {
                $devicesQuery = sprintf($Query, $this->_Customerno);
            }
        }
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->devicekey = $row['devicekey'];
                $device->uid = $row['uid'];
                $device->unitno = $row['unitno'];
                $device->phone = $row['phone'];
                $device->device_invoiceno = $row['device_invoiceno'];
                $device->vehicleno = $row['vehicleno'];
                $device->vehicleid = $row['vehicleid'];
                $device->today = $row["today"];
                $device->expirydate = $row["expirydate"];
                $device->registeredon = $row["registeredon"];
                $device->installdate = $row["installdate"];
                $device->groupid = $row["groupid"];
                $device->groupname = $row["groupname"];
                $device->code = $row["code"];
                $device->invoiceno = $row["invoiceno"];
                $device->pono = $row["po_no"];
                $device->podate = $row["po_date"];
                $device->warranty = $row["warrantyexpiry"];
                $device->city = $row["cname"];
                $device->district = $row["dname"];
                $device->devicelat = $row["devicelat"];
                $device->devicelong = $row["devicelong"];
                $device->baselat = $row["baselat"];
                $device->baselng = $row["baselng"];
                $device->installlat = $row["installlat"];
                $device->installlng = $row["installlng"];
                $device->lastupdated = $row["lastupdated"];
                $devices[] = $device;
            }
            return $devices;
        }
        return null;
    }

    public function get_filtered_devices($nation_id, $state_id, $district_id, $city_id, $group_id) {
        $devices = Array();
        $Query = "SELECT devices.deviceid, vehicle.vehicleno,devices.installdate,vehicle.vehicleid, devices.invoiceno,
            devices.devicekey,simcard.simcardno as phone,devices.expirydate,devices.uid,devices.po_no,devices.po_date, devices.warrantyexpiry, unit.unitno, vehicle.groupid,
            registeredon,district.name as dname,city.name as cname, devices.device_invoiceno, Now() as today
            ,devices.baselat,devices.baselng,devices.installlat,devices.installlng
            FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN `group` ON vehicle.groupid=`group`.groupid
            INNER JOIN city ON `group`.cityid=city.cityid
            INNER JOIN district ON city.districtid=district.districtid
            INNER JOIN state ON district.stateid=state.stateid
            INNER JOIN nation ON state.nationid=nation.nationid
            LEFT OUTER JOIN simcard ON devices.simcardid = simcard.id
            where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22)";
        if ($group_id != '') {
            $Query .= " AND vehicle.groupid=$group_id ";
            $devicesQuery = sprintf($Query, $this->_Customerno);
        }
        if ($city_id != '') {
            $Query .= " AND city.cityid=$city_id ";
            $devicesQuery = sprintf($Query, $this->_Customerno);
        }
        if ($district_id != '') {
            $Query .= " AND district.districtid=$district_id ";
            $devicesQuery = sprintf($Query, $this->_Customerno);
        }
        if ($state_id != '') {
            $Query .= " AND state.stateid=$state_id ";
            $devicesQuery = sprintf($Query, $this->_Customerno);
        }
        if ($nation_id != '') {
            $Query .= " AND nation.nationid=$nation_id ";
            $devicesQuery = sprintf($Query, $this->_Customerno);
        } else {
            $devicesQuery = sprintf($Query, $this->_Customerno);
        }
        $Query .= "ORDER BY vehicle.vehicleno ASC";
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->devicekey = $row['devicekey'];
                $device->uid = $row['uid'];
                $device->unitno = $row['unitno'];
                $device->phone = $row['phone'];
                $device->device_invoiceno = $row['device_invoiceno'];
                $device->vehicleno = $row['vehicleno'];
                $device->vehicleid = $row['vehicleid'];
                $device->today = $row["today"];
                $device->expirydate = $row["expirydate"];
                $device->registeredon = $row["registeredon"];
                $device->installdate = $row["installdate"];
                $device->groupid = $row["groupid"];
                $device->invoiceno = $row["invoiceno"];
                $device->pono = $row["po_no"];
                $device->podate = $row["po_date"];
                $device->warranty = $row["warrantyexpiry"];
                $device->city = $row["cname"];
                $device->district = $row["dname"];
                $device->baselat = $row["baselat"];
                $device->baselng = $row["baselng"];
                $device->installlat = $row["installlat"];
                $device->installlng = $row["installlng"];
                $devices[] = $device;
            }
            return $devices;
        }
        return null;
    }

    /**
     * To get count of installed devices of the particular customer between these days
     * @return int
     */
    public function get_all_devices_count() {
        $devices = Array();
        $Query = "SELECT count(devices.deviceid) total_installed FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22) ";
        //AND devices.installdate >= '$start_date' and devices.installdate <= '$end_date'
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
            $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $devicesQuery = sprintf($Query, $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($devicesQuery);
        $count = $this->_databaseManager->get_nextRow();
        return $count['total_installed'];
    }

    public function get_all_simcard_with_devices() {
        $day = date("Y-m-d H:i:s", strtotime("-7 days"));
        $devices = Array();
        $Query = "SELECT devices.deviceid,devices.simcardid,devices.uid,devices.lastupdated,devices.customerno,customer.customeremail,simcard.simcardno FROM `simcard`
            INNER JOIN devices ON simcard.id = devices.simcardid
            INNER JOIN " . DB_PARENT . ".customer ON devices.customerno = customer.customerno
            where devices.lastupdated <= '$day' AND simcard.trans_statusid NOT IN (10,22)";
        $devicesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->simcardid = $row['simcardid'];
                $device->simcardno = $row['simcardno'];
                $device->uid = $row['uid'];
                $device->lastupdated = $row["lastupdated"];
                $device->customerno = $row["customerno"];
                $device->customeremail = $row["customeremail"];
                $devices[] = $device;
            }
            return $devices;
        }
        return null;
    }

    public function updateSimcardstatus($simcardid, $simcardno, $uid, $customerno) {
        $SQLunit = sprintf("UPDATE unit SET trans_statusid='6' WHERE uid=%d", Sanitise::Long($uid));
        $this->_databaseManager->executeQuery($SQLunit);
        $SQLsimcard = sprintf("UPDATE simcard SET trans_statusid='14' WHERE id=%d", Sanitise::Long($simcardid));
        $this->_databaseManager->executeQuery($SQLsimcard);
        $today = date("Y-m-d H:i:s");
        $SQLthunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
    `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`)
    VALUES (
    $customerno, '%d', 0, 0, '%s', 6, '%s', '%s', '%s', '%s')", $uid, Sanitise::DateTime($today), "Suspect by: " . $uid, "", "", "");
        $this->_databaseManager->executeQuery($SQLthunit);
        $SQLthsim = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
    `customerno` ,`simcard_id`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`)
    VALUES (
    $customerno, '%d', 0, 1, '%s', 14, '%s','%s','%s','%s')", $simcardid, Sanitise::DateTime($today), "Suspect " . $simcardno, Sanitise::String($simcardno), "", "");
        $this->_databaseManager->executeQuery($SQLthsim);
    }

    public function get_all_devices_expirydate($customerno) {
        $devices = Array();
        $time = date("Y-m-d", strtotime("-30 Days"));
        $Query = "SELECT devices.deviceid,
            devices.devicekey,simcard.simcardno as phone,devices.expirydate,devices.uid,unit.unitno,
            registeredon, Now() as today FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            LEFT OUTER JOIN simcard ON devices.simcardid = simcard.id
            where devices.customerno=%d AND devices.expirydate = '$time' AND unit.trans_statusid NOT IN (10,22)";
        $devicesQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
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

    public function get_device($uid) {
        //$devices = Array();
        $Query = "SELECT devices.deviceid,simcard.simcardno,
            devices.devicekey,devices.expirydate,devices.uid,unit.unitno,
            registeredon, Now() as today FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid
            where devices.customerno=%d and devices.uid=%d AND unit.trans_statusid NOT IN (10,22)";
        $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($uid));
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->devicekey = $row['devicekey'];
                $device->uid = $row['uid'];
                $device->unitno = $row['unitno'];
                $device->phone = $row['simcardno'];
                $device->today = $row["today"];
                $device->expirydate = $row["expirydate"];
                $device->registeredon = $row["registeredon"];
            }
            return $device;
        }
        return null;
    }

    public function getalldevicesformonitoring() {
        $devices = Array();
        $Query = "select d.deviceid, d.devicelat, d.devicelong,
            v.vehicleid, v.vehicleno, ea.overspeed AS overspeed_status,
            ea.tamper AS tamper_status, ea.powercut AS powercut_status, ea.ac AS ac_status,
            ia.status AS email_status, ea.temp AS temp_status, d.status, d.powercut, d.tamper,
            u.acsensor, u.digitalio, d.ignition, ia.count, ia.last_check, ia.last_status,
            d.lastupdated, d.uid, u.tempsen1, u.analog1, a.artname, a.maxtemp, a.mintemp,
            u.tempsen2, u.analog2, u.analog4, aa.aci_status, u.is_ac_opp
            FROM devices d
            INNER JOIN unit u ON u.uid = d.uid
            INNER JOIN vehicle v on v.vehicleid = u.vehicleid
            INNER JOIN eventalerts ea ON ea.vehicleid = v.vehicleid
            INNER JOIN ignitionalert ia ON ia.vehicleid = v.vehicleid
            LEFT OUTER JOIN articlemanage am ON am.vehicleid = v.vehicleid
            LEFT OUTER JOIN article a ON a.artid = am.artid
            LEFT OUTER JOIN acalerts aa ON aa.vehicleid = v.vehicleid
            where d.customerno = %d AND u.trans_statusid NOT IN (10,22)";
        $devicesQuery = sprintf($Query, $this->_Customerno);
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
                    $device->overspeedstatus = $row['overspeed_status'];
                    $device->tamperstatus = $row['tamper_status'];
                    $device->powercutstatus = $row['powercut_status'];
                    $device->status = $row['status'];
                    $device->powercut = $row['powercut'];
                    $device->tamper = $row['tamper'];
                    $device->acsensor = $row['acsensor'];
                    $device->digitalio = $row['digitalio'];
                    $device->ac_status = $row['ac_status'];
                    $device->ignition = $row['ignition'];
                    $device->ignition_status = $row['count'];
                    $device->ignition_last_status = $row['last_status'];
                    $device->ignition_last_check = $row['last_check'];
                    $device->ignition_email_status = $row['email_status'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->uid = $row['uid'];
                    $device->tempsen1 = $row['tempsen1'];
                    if ($device->tempsen1 == 1) {
                        $device->temp = $row['analog1'];
                        $device->artname = $row['artname'];
                        $device->maxtemp = $row['maxtemp'];
                        $device->mintemp = $row['mintemp'];
                    }
                    $device->tempsen2 = $row['tempsen2'];
                    if ($device->tempsen2 == 1) {
                        $device->temp = $row['analog2'];
                        $device->artname = $row['artname'];
                        $device->maxtemp = $row['maxtemp'];
                        $device->mintemp = $row['mintemp'];
                    }
                    $device->analog4 = $row['analog4'];
                    $device->temp_status = $row['temp_status'];
                    $device->aci_status = $row['aci_status'];
                    $device->is_ac_opp = $row['is_ac_opp'];
                    $devices[] = $device;
                }
            }
            return $devices;
        }
        return NULL;
    }

    public function getalldevicesformonitoringwithchk() {
        $devices = Array();
        $Query = "select devices.deviceid,devices.devicelat,devices.devicelong,
            vehicle.vehicleid,vehicle.vehicleno,checkpoint.cgeolat,checkpoint.cgeolong,
            checkpointmanage.conflictstatus,checkpoint.cname,checkpointmanage.cmid,checkpoint.crad,
            devices.uid, checkpoint.checkpointid, devices.lastupdated from devices
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN checkpointmanage ON checkpointmanage.vehicleid = vehicle.vehicleid
            INNER JOIN checkpoint ON checkpoint.checkpointid = checkpointmanage.checkpointid
            where devices.customerno = %d AND checkpointmanage.isdeleted = 0 AND unit.trans_statusid NOT IN (10,22)";
        $devicesQuery = sprintf($Query, $this->_Customerno);
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
                    $device->lastupdated = $row['lastupdated'];
                    $device->cgeolat = $row['cgeolat'];
                    $device->cgeolong = $row['cgeolong'];
                    $device->conflictstatus = $row['conflictstatus'];
                    $device->checkpointid = $row['checkpointid'];
                    $device->cname = $row['cname'];
                    $device->cmid = $row['cmid'];
                    $device->crad = $row['crad'];
                    $devices[] = $device;
                }
            }
            return $devices;
        }
        return NULL;
    }

    public function checkforvalidity() {
        $devices = Array();
        $Query = "SELECT deviceid,expirydate, Now() as today FROM `devices` where customerno=%d";
        $devicesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->today = $row["today"];
                $device->expirydate = $row["expirydate"];
                $devices[] = $device;
            }
            return $devices;
        }
        return null;
    }

    public function devicesformapping() {
        $devices = Array();
        if (isset($_SESSION['use_maintenance']) && $_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
            $Query = "SELECT *,vehicle.vehicleid, vehicle.vehicleno FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN driver ON vehicle.driverid = driver.driverid";
            if ($_SESSION['roleid'] == '2') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                    INNER JOIN city on city.cityid = `group`.cityid
                    INNER JOIN district on district.districtid = city.districtid
                    INNER JOIN state on state.stateid = district.stateid
                    where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22) and vehicle.kind<>'Warehouse' AND state.stateid=%d AND `group`.isdeleted=0";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= " ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '3') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                    INNER JOIN city on city.cityid = `group`.cityid
                    INNER JOIN district on district.districtid = city.districtid
                    where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22)and vehicle.kind<>'Warehouse' AND district.districtid=%d AND `group`.isdeleted=0";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= " ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '4') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                    INNER JOIN city on city.cityid = `group`.cityid
                    where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22) and vehicle.kind<>'Warehouse' AND city.cityid=%d AND `group`.isdeleted=0";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= " ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '8') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                    where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22) and vehicle.kind<>'Warehouse' AND `group`.isdeleted=0";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= " ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno);
                }
            } else {
                $Query .= " where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22)and vehicle.kind<>'Warehouse' ";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= " ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno);
                }
            }
        } elseif (isset($_SESSION['ecodeid'])) {
            $Query = "SELECT * ,vehicle.vehicleid, vehicle.vehicleno FROM `devices`
                    INNER JOIN unit ON unit.uid = devices.uid
                    INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                    INNER JOIN ecodeman ON ecodeman.vehicleid = unit.vehicleid
                    INNER JOIN driver ON vehicle.driverid = driver.driverid
                    where devices.customerno=%d AND ecodeman.ecodeid=%d AND unit.trans_statusid NOT IN (10,22)and vehicle.kind<>'Warehouse' ORDER BY vehicle.vehicleno";
            $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['e_id']);
        } elseif (isset($_SESSION['roleid']) && $_SESSION['roleid'] == '43') {
            //elseif (isset($_SESSION['roleid']) && $this->VehicleUsermappingExists($_SESSION['userid'])==true) {
            $Query = "SELECT * ,vehicle.vehicleid, vehicle.vehicleno FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN driver ON vehicle.driverid = driver.driverid";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid = %d";
            } else {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . "";
            }
            $Query .= " where devices.customerno=%d and vehmap.isdeleted = 0 AND unit.trans_statusid NOT IN (10,22)and vehicle.kind<>'Warehouse' ";
            $Query .= " ORDER BY vehicle.vehicleno";
            if ($_SESSION['groupid'] != 0) {
                $devicesQuery = sprintf($Query, $_SESSION['groupid'], $this->_Customerno);
            } else {
                $devicesQuery = sprintf($Query, $this->_Customerno);
            }
        } else {
            $Query = "SELECT *,vehicle.vehicleid, vehicle.vehicleno FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN driver ON vehicle.driverid = driver.driverid
                where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22)and vehicle.kind<>'Warehouse' ";
            if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
                $Query .= " AND vehicle.groupid =%d";
            }
            $Query .= " ORDER BY vehicle.vehicleno";
            if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
                $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
            } else {
                $devicesQuery = sprintf($Query, $this->_Customerno);
            }
        }
        //echo $devicesQuery;
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['uid'] > 0) {
                    $device->vehicleno = $row['vehicleno'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->type = $row['kind'];
                    $device->deviceid = $row['deviceid'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->drivername = $row['drivername'];
                    $device->driverphone = $row['driverphone'];
                    $device->curspeed = $row['curspeed'];
                    $device->overspeed_limit = $row['overspeed_limit'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->type = $row['kind'];
                    $device->ignition = $row['ignition'];
                    $device->status = $row['status'];
                    $device->tempsen1 = $row['tempsen1'];
                    $device->tempsen2 = $row['tempsen2'];
                    $device->temp1_min = $row['temp1_min'];
                    $device->temp1_max = $row['temp1_max'];
                    $device->temp2_min = $row['temp2_min'];
                    $device->temp2_max = $row['temp2_max'];
                    $device->tempsen1 = $row['tempsen1'];
                    $device->analog1 = $row["analog1"];
                    $device->analog2 = $row["analog2"];
                    $device->analog3 = $row["analog3"];
                    $device->analog4 = $row["analog4"];
                    $device->stoppage_flag = $row['stoppage_flag'];
                    $device->stoppage_transit_time = $row['stoppage_transit_time'];
                    $device->directionchange = $row['directionchange'];
                    $devices[] = $device;
                }
            }
            return $devices;
        }
        return null;
    }

    public function devicesformapping_wh() {
        $devices = Array();
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
            $Query = "SELECT * FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN driver ON vehicle.driverid = driver.driverid";
            if ($_SESSION['roleid'] == '2') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                    INNER JOIN city on city.cityid = `group`.cityid
                    INNER JOIN district on district.districtid = city.districtid
                    INNER JOIN state on state.stateid = district.stateid
                    where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22) and vehicle.kind='Warehouse' AND state.stateid=%d AND `group`.isdeleted=0";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= " ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '3') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                    INNER JOIN city on city.cityid = `group`.cityid
                    INNER JOIN district on district.districtid = city.districtid
                    where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22) and vehicle.kind='Warehouse' AND district.districtid=%d AND `group`.isdeleted=0";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= " ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '4') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                    INNER JOIN city on city.cityid = `group`.cityid
                    where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22) and vehicle.kind='Warehouse' AND city.cityid=%d AND `group`.isdeleted=0";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= " ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '8') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                    where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22) and vehicle.kind='Warehouse' AND `group`.isdeleted=0";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= " ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno);
                }
            } else {
                $Query .= " where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22)and vehicle.kind='Warehouse' ";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= " ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno);
                }
            }
        } elseif (isset($_SESSION['ecodeid'])) {
            $Query = "SELECT * FROM `devices`
                    INNER JOIN unit ON unit.uid = devices.uid
                    INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                    INNER JOIN ecodeman ON ecodeman.vehicleid = unit.vehicleid
                    INNER JOIN driver ON vehicle.driverid = driver.driverid
                    where devices.customerno=%d AND ecodeman.ecodeid=%d AND unit.trans_statusid NOT IN (10,22) and vehicle.kind='Warehouse' ORDER BY vehicle.vehicleno";
            $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['e_id']);
        } elseif (isset($_SESSION['roleid']) && $this->VehicleUsermappingExists($_SESSION['userid']) == true) {
            $Query = "SELECT * FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN driver ON vehicle.driverid = driver.driverid";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid = %d";
            } else {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . "";
            }
            $Query .= " where devices.customerno=%d and vehmap.isdeleted = 0 AND unit.trans_statusid NOT IN (10,22)and vehicle.kind='Warehouse' ";
            $Query .= " ORDER BY vehicle.vehicleno";
            if ($_SESSION['groupid'] != 0) {
                $devicesQuery = sprintf($Query, $_SESSION['groupid'], $this->_Customerno);
            } else {
                $devicesQuery = sprintf($Query, $this->_Customerno);
            }
        } else {
            $Query = "SELECT * FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN driver ON vehicle.driverid = driver.driverid
                where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22)and vehicle.kind='Warehouse' ";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " AND vehicle.groupid =%d";
            }
            $Query .= " ORDER BY vehicle.vehicleno";
            if ($_SESSION['groupid'] != 0) {
                $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
            } else {
                $devicesQuery = sprintf($Query, $this->_Customerno);
            }
        }
        $devicesQuery;
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['uid'] > 0) {
                    $device->vehicleno = $row['vehicleno'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->deviceid = $row['deviceid'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->drivername = $row['drivername'];
                    $device->driverphone = $row['driverphone'];
                    $device->curspeed = $row['curspeed'];
                    $device->overspeed_limit = $row['overspeed_limit'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->type = $row['kind'];
                    $device->ignition = $row['ignition'];
                    $device->status = $row['status'];
                    $device->temp1_min = $row['temp1_min'];
                    $device->temp1_max = $row['temp1_max'];
                    $device->temp2_min = $row['temp2_min'];
                    $device->temp2_max = $row['temp2_max'];
                    $device->tempsen1 = $row['tempsen1'];
                    $device->tempsen2 = $row['tempsen2'];
                    $device->analog1 = $row["analog1"];
                    $device->analog2 = $row["analog2"];
                    $device->analog3 = $row["analog3"];
                    $device->analog4 = $row["analog4"];
                    $device->stoppage_flag = $row['stoppage_flag'];
                    $device->stoppage_transit_time = $row['stoppage_transit_time'];
                    $device->directionchange = $row['directionchange'];
                    $devices[] = $device;
                }
            }
            return $devices;
        }
        return null;
    }

    public function devicesforwarehousemapping() {
        $devices = Array();
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
            $Query = "SELECT * FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN warehouse ON warehouse.vehicleid = unit.vehicleid
                ";
            if ($_SESSION['roleid'] == '2') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = warehouse.groupid
                    INNER JOIN city on city.cityid = `group`.cityid
                    INNER JOIN district on district.districtid = city.districtid
                    INNER JOIN state on state.stateid = district.stateid
                    where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22) AND state.stateid=%d AND `group`.isdeleted=0";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND warehouse.groupid =%d";
                }
                $Query .= " ORDER BY warehouse.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '3') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = warehouse.groupid
                    INNER JOIN city on city.cityid = `group`.cityid
                    INNER JOIN district on district.districtid = city.districtid
                    where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22) AND district.districtid=%d AND `group`.isdeleted=0";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND warehouse.groupid =%d";
                }
                $Query .= " ORDER BY warehouse.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '4') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = warehouse.groupid
                    INNER JOIN city on city.cityid = `group`.cityid
                    where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22) AND city.cityid=%d AND `group`.isdeleted=0";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND warehouse.groupid =%d";
                }
                $Query .= " ORDER BY warehouse.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '8') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = warehouse.groupid
                    where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22) AND `group`.isdeleted=0";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND warehouse.groupid =%d";
                }
                $Query .= " ORDER BY warehouse.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno);
                }
            } else {
                $Query .= " where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22)";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND warehouse.groupid =%d";
                }
                $Query .= " ORDER BY warehouse.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno);
                }
            }
        } elseif (isset($_SESSION['ecodeid'])) {
            $Query = "SELECT * FROM `devices`
                    INNER JOIN unit ON unit.uid = devices.uid
                    INNER JOIN warehouse ON warehouse.vehicleid = unit.vehicleid
                    INNER JOIN ecodeman ON ecodeman.vehicleid = unit.vehicleid
                    where devices.customerno=%d AND ecodeman.ecodeid=%d AND unit.trans_statusid NOT IN (10,22) ORDER BY vehicle.vehicleno";
            $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['e_id']);
        } else {
            $Query = "SELECT * FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN warehouse ON warehouse.vehicleid = unit.vehicleid
                where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22)";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " AND warehouse.groupid =%d";
            }
            $Query .= " ORDER BY warehouse.vehicleno";
            if ($_SESSION['groupid'] != 0) {
                $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
            } else {
                $devicesQuery = sprintf($Query, $this->_Customerno);
            }
        }
        //echo $devicesQuery;
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['uid'] > 0) {
                    if ($row['devicelat'] > 0 & $row['devicelong'] > 0) {
                        $device->vehicleno = $row['vehicleno'];
                        $device->vehicleid = $row['vehicleid'];
                        $device->deviceid = $row['deviceid'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->curspeed = $row['curspeed'];
                        $device->overspeed_limit = $row['overspeed_limit'];
                        $device->lastupdated = $row['lastupdated'];
                        $device->type = $row['kind'];
                        $device->ignition = $row['ignition'];
                        $device->status = $row['status'];
                        $device->stoppage_flag = $row['stoppage_flag'];
                        $device->stoppage_transit_time = $row['stoppage_transit_time'];
                        $device->directionchange = $row['directionchange'];
                        $devices[] = $device;
                    }
                }
            }
            return $devices;
        }
        return null;
    }

    public function getvehicle_renewals() {
        $today = date('Y-m-d');
        $renewaldata = Array();
        $vm = new VehicleManager($_SESSION['customerno']);
        $groups = $vm->getUserGroups($_SESSION['customerno'], $_SESSION['userid']);
        $Query = "SELECT description.seatcapacity,vehicle.other_upload1,vehicle.other_upload2,vehicle.other_upload3,vehicle.vehicleno,vehicle.vehicleid,tax.from_date,tax.to_date, valert.puc_expiry,valert.reg_expiry,valert.insurance_expiry,valert.other1_expiry,valert.other2_expiry, valert.other3_expiry,`group`.groupname
                                FROM vehicle left outer join valert on valert.vehicleid = vehicle.vehicleid AND valert.customerno=%d left outer join tax on tax.vehicleid = vehicle.vehicleid AND tax.isdeleted=0 AND tax.customerno=%d LEFT JOIN `group` ON `group`.groupid=vehicle.groupid LEFT JOIN `description` ON description.vehicleid = vehicle.vehicleid
                                where vehicle.customerno =%d AND vehicle.isdeleted=0";
        if ($_SESSION['groupid'] != '0') {
            $Query .= " AND vehicle.groupid = %d ";
        } else {
            if ($groups[0] != 0) {
                $groupid_ids = implode(',', $groups);
                $Query .= " AND vehicle.groupid in (" . $groupid_ids . ") ";
            }
        }
        $Query .= " Order by vehicleno DESC ";
        if ($_SESSION['groupid'] != '0') {
            $SQL = sprintf($Query, $_SESSION['customerno'], $_SESSION['customerno'], $_SESSION['customerno'], $_SESSION['groupid']);
        } else {
            $SQL = sprintf($Query, $_SESSION['customerno'], $_SESSION['customerno'], $_SESSION['customerno']);
        }
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                //reg date diff
                if ($row['reg_expiry'] != "0000-00-00 00:00:00" && $row['reg_expiry'] != "") {
                    $reg_expiry = date('d-M-Y', strtotime($row['reg_expiry']));
                } else {
                    $reg_expiry = "N/A";
                }
                //tax date diff
                if ($row['to_date'] != "0000-00-00" && $row['to_date'] != "") {
                    $taxtodate = date('d-M-Y', strtotime($row['to_date']));
                    $diff_tax = date_SDiff_cmn($row['to_date'], $today, 'GMT', 'm');
                } else {
                    $taxtodate = $diff_tax = "N/A";
                }
                // insurance diff
                if ($row['insurance_expiry'] != "0000-00-00 00:00:00" && $row['insurance_expiry'] != "") {
                    $insurance_expiry = date('d-M-Y', strtotime($row['insurance_expiry']));
                    $diff_insurance_exp = date_SDiff_cmn($row['insurance_expiry'], $today, 'GMT', 'm');
                } else {
                    $insurance_expiry = $diff_insurance_exp = "N/A";
                }
                // other1 diff--permit
                if ($row['other1_expiry'] != "0000-00-00 00:00:00" && $row['other1_expiry'] != "") {
                    $other1_expiry = date('d-M-Y', strtotime($row['other1_expiry']));
                    $diff_other1_exp = date_SDiff_cmn($row['other1_expiry'], $today, 'GMT', 'm');
                } else {
                    $other1_expiry = $diff_other1_exp = "N/A";
                }
                // other3 diff--fitness
                if ($row['other3_expiry'] != "0000-00-00 00:00:00" && $row['other3_expiry'] != "") {
                    $other3_expiry = date('d-M-Y', strtotime($row['other3_expiry']));
                    $diff_other3_exp = date_SDiff_cmn($row['other3_expiry'], $today, 'GMT', 'm');
                } else {
                    $other3_expiry = $diff_other3_exp = "N/A";
                }
                // PUC diff
                if ($row['puc_expiry'] != "0000-00-00 00:00:00" && $row['puc_expiry'] != "") {
                    $puc_expiry = date('d-M-Y', strtotime($row['puc_expiry']));
                    $diff_puc_exp = date_SDiff_cmn($puc_expiry, $today, 'GMT', 'm');
                } else {
                    $puc_expiry = $diff_puc_exp = "N/A";
                }
                $device->vehicleno = $row['vehicleno'];
                $device->seatcapacity = $row['seatcapacity'];
                $device->vehicleid = $row['vehicleid'];
                $device->tax_from_date = $row['from_date'];
                $device->tax_to_date = $row['to_date'];
                $device->valert_reg_expiry = $row['reg_expiry'];
                $device->valert_insurance_expiry = $row['insurance_expiry'];
                $device->other_upload1 = $row['other_upload1'];
                $device->other2_expiry = $row['other2_expiry'];
                $device->other_upload2 = $row['other_upload2'];
                $device->other_upload3 = $row['other_upload3'];
                $device->grname = $row['groupname'];
                $device->reg_expiry = $reg_expiry;
                $device->taxtodate = $taxtodate;
                $device->diff_tax = $diff_tax;
                $device->insurance_expiry = $insurance_expiry;
                $device->diff_insurance_exp = $diff_insurance_exp;
                $device->other1_expiry = $other1_expiry;
                $device->diff_other1_exp = $diff_other1_exp;
                $device->other3_expiry = $other3_expiry;
                $device->diff_other3_exp = $diff_other3_exp;
                $device->puc_expiry = $puc_expiry;
                $device->diff_puc_exp = $diff_puc_exp;
                $renewaldata[] = $device;
            }
            return $renewaldata;
        }
    }

    public function devicesformapping_byId($srhstring) {
        $devices = Array();
        $vehicle_ses = '';
        $list = " vehicle.vehicleid
            ,vehicle.customerno
            ,vehicle.vehicleno
            ,vehicle.kind
            ,vehicle.overspeed_limit
            ,unit.uid
            ,unit.unitno
            ,`group`.groupname
            ,driver.drivername
            ,driver.driverphone
            ,devices.deviceid
            ,devices.devicelat
            ,devices.devicelong
            ,vehicle.lastupdated
            ,devices.ignition
            ,devices.status
            ,vehicle.curspeed
            ,vehicle.stoppage_flag
            ,vehicle.stoppage_transit_time
            ,devices.directionchange ";
        if ($_SESSION['switch_to'] == 3) {
            $vehicle_ses = " AND vehicle.kind = 'Warehouse'";
        } else {
            $vehicle_ses = " AND vehicle.kind <> 'Warehouse' ";
        }
        if (isset($_SESSION['ecodeid'])) {
            $Query = "SELECT $list FROM `devices`
                    INNER JOIN unit ON unit.uid = devices.uid
                    INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                    INNER JOIN ecodeman ON ecodeman.vehicleid = unit.vehicleid
                    INNER JOIN driver ON vehicle.driverid = driver.driverid
                    LEFT JOIN `group` on vehicle.groupid = `group`.groupid
                    where devices.customerno=%d  AND ecodeman.ecodeid=%d
                    AND unit.trans_statusid NOT IN (10,22)
                    AND vehicle.isdeleted = 0
                    AND vehicleno LIKE '%s' $vehicle_ses "
                . " AND ecodeman.isdeleted=0 "
                . " ORDER BY vehicle.vehicleno";
            $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['e_id'], Sanitise::String($srhstring));
        } elseif (isset($_SESSION['roleid']) && $this->VehicleUsermappingExists($_SESSION['userid']) == true) {
            $Query = "SELECT * FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN driver ON vehicle.driverid = driver.driverid
               LEFT JOIN `group` on vehicle.groupid = `group`.groupid";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid = %d";
            } else {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . "";
            }
            $Query .= " where devices.customerno=%d  and vehmap.isdeleted = 0 "
                . " AND unit.trans_statusid NOT IN (10,22) "
                . " AND vehicle.isdeleted = 0 "
                . " AND vehicleno LIKE '%s' $vehicle_ses ";
            $Query .= " ORDER BY vehicle.vehicleno";
            if ($_SESSION['groupid'] != 0) {
                $devicesQuery = sprintf($Query, $_SESSION['groupid'], $this->_Customerno, Sanitise::String($srhstring));
            } else {
                $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring));
            }
        } else {
            $Query = "SELECT $list FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN driver ON vehicle.driverid = driver.driverid
                LEFT JOIN `group` on vehicle.groupid = `group`.groupid
                where (devices.customerno=%d )
                AND unit.trans_statusid NOT IN (10,22)
                AND vehicle.isdeleted = 0
                AND vehicleno LIKE '%s' $vehicle_ses ";
            if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
                $Query .= " AND vehicle.groupid =%d";
            }
            $Query .= " ORDER BY vehicle.vehicleno";
            if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
                $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring), $_SESSION['groupid']);
            } else {
                $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring));
            }
        }
        //echo $devicesQuery;
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['uid'] > 0) {
                    $device->vehicleno = $row['vehicleno'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->deviceid = $row['deviceid'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->drivername = $row['drivername'];
                    $device->driverphone = $row['driverphone'];
                    $device->curspeed = $row['curspeed'];
                    $device->overspeed_limit = $row['overspeed_limit'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->type = $row['kind'];
                    $device->ignition = $row['ignition'];
                    $device->status = $row['status'];
                    $device->stoppage_flag = $row['stoppage_flag'];
                    $device->stoppage_transit_time = $row['stoppage_transit_time'];
                    $device->directionchange = $row['directionchange'];
                    $device->groupname = $row['groupname'];
                    $devices[] = $device;
                }
            }
            return $devices;
        }
        return null;
    }

    public function maintenance_vehicles($srhstring) {
        $devices = Array();
        $vm = new VehicleManager($_SESSION['customerno']);
        $groups = $vm->getUserGroups($_SESSION['customerno'], $_SESSION['userid']);
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
            $Query = "SELECT vehicleid, vehicleno from vehicle where customerno=%d AND vehicleno LIKE '%s' AND isdeleted = 0";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " AND vehicle.groupid =%d";
            } else {
                if ($groups[0] != 0) {
                    $groupid_ids = implode(',', $groups);
                    $Query .= " AND vehicle.groupid in (" . $groupid_ids . ") ";
                }
            }
            $Query .= " ORDER BY vehicle.vehicleno";
            if ($_SESSION['groupid'] != 0) {
                $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring), $_SESSION['groupid']);
            } else {
                $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring));
            }
        }
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['vehicleid'] > 0) {
                    $device->vehicleno = $row['vehicleno'];
                    $device->vehicleid = $row['vehicleid'];
                    $devices[] = $device;
                }
            }
            return $devices;
        }
        return null;
    }

    public function devicesformapping_idleStatus() {
        $devices = Array();
        $lastupdate = date('Y-m-d H:i:s', time() - 3600);
        $Query = "SELECT * FROM `devices`
                    INNER JOIN vehicle ON vehicle.uid = devices.uid
                    where devices.customerno=%d";
        $Query .= " AND devices.lastupdated > '%s' AND vehicle.stoppage_flag = 0";
        $devicesQuery = sprintf($Query, $this->_Customerno, $lastupdate);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['uid'] > 0) {
                    $device->deviceid = $devicesQuery;
                    $device->lastupdated = $row['lastupdated'];
                    $device->stoppage_transit_time = $row['stoppage_transit_time'];
                    $devices[] = $device;
                }
            }
            return $devices;
        }
        return null;
    }

    public function devicesformapping_acsensor() {
        $devices = Array();
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
            $Query = "SELECT * FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid";
            $devicesQuery = sprintf($Query, $this->_Customerno);
            if ($_SESSION['roleid'] == '2') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                        INNER JOIN city on city.cityid = `group`.cityid
                        INNER JOIN district on district.districtid = city.districtid
                        INNER JOIN state on state.stateid = district.stateid
                        where devices.customerno=%d AND unit.acsensor=1 AND unit.trans_statusid NOT IN (10,22) AND state.stateid=%d AND `group`.isdeleted=0";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '3') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                        INNER JOIN city on city.cityid = `group`.cityid
                        INNER JOIN district on district.districtid = city.districtid
                        where devices.customerno=%d AND unit.acsensor=1 AND unit.trans_statusid NOT IN (10,22) AND district.districtid=%d AND `group`.isdeleted=0 ";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '4') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                        INNER JOIN city on city.cityid = `group`.cityid
                        where devices.customerno=%d AND unit.acsensor=1 AND unit.trans_statusid NOT IN (10,22) AND city.cityid=%d AND `group`.isdeleted=0 ";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '8') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                        where devices.customerno=%d AND unit.acsensor=1 AND unit.trans_statusid NOT IN (10,22)";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno);
                }
            } else {
                $Query .= " where devices.customerno=%d AND unit.acsensor=1 AND unit.trans_statusid NOT IN (10,22)";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno);
                }
            }
        } elseif ($_SESSION['ecodeid']) {
            $Query = "SELECT * FROM `devices`
                    INNER JOIN unit ON unit.uid = devices.uid
                    INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                    INNER JOIN ecodeman ON ecodeman.vehicleid = unit.vehicleid
                    INNER JOIN driver ON vehicle.driverid = driver.driverid
                    WHERE devices.customerno=%d  AND ecodeman.ecodeid=%d AND unit.acsensor=1 AND unit.trans_statusid NOT IN (10,22)
                    ORDER BY vehicle.vehicleno";
            $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['e_id']);
        } else {
            $Query = "SELECT * FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid
            where devices.customerno=%d AND unit.acsensor=1 AND unit.trans_statusid NOT IN (10,22) ";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " AND vehicle.groupid =%d";
            }
            $Query .= "  ORDER BY vehicle.vehicleno";
            if ($_SESSION['groupid'] != 0) {
                $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
            } else {
                $devicesQuery = sprintf($Query, $this->_Customerno);
            }
        }
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['uid'] > 0) {
                    if ($row['devicelat'] > 0 & $row['devicelong'] > 0) {
                        $device->vehicleno = $row['vehicleno'];
                        $device->vehicleid = $row['vehicleid'];
                        $device->deviceid = $row['deviceid'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->drivername = $row['drivername'];
                        $device->driverphone = $row['driverphone'];
                        $device->curspeed = $row['curspeed'];
                        $device->lastupdated = $row['lastupdated'];
                        $device->type = $row['kind'];
                        $device->ignition = $row['ignition'];
                        $device->status = $row['status'];
                        $device->directionchange = $row['directionchange'];
                        $devices[] = $device;
                    }
                }
            }
            return $devices;
        }
        return null;
    }

    public function devicesformapping_acsensor_byId($srhstring) {
        $devices = Array();
        $vehicle_ses = '';
        if ($_SESSION['switch_to'] == 3) {
            $vehicle_ses = " AND vehicle.kind = 'Warehouse'";
        } else {
            $vehicle_ses = " AND vehicle.kind <> 'Warehouse' ";
        }
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
            $Query = "SELECT * FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid";
            $devicesQuery = sprintf($Query, $this->_Customerno);
            if ($_SESSION['roleid'] == '2') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                        INNER JOIN city on city.cityid = `group`.cityid
                        INNER JOIN district on district.districtid = city.districtid
                        INNER JOIN state on state.stateid = district.stateid
                        where devices.customerno=%d AND unit.acsensor=1 AND unit.trans_statusid NOT IN (10,22) AND state.stateid=%d AND vehicleno LIKE '%s' $vehicle_ses AND `group`.isdeleted=0";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], Sanitise::String($srhstring), $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], Sanitise::String($srhstring));
                }
            } elseif ($_SESSION['roleid'] == '3') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                        INNER JOIN city on city.cityid = `group`.cityid
                        INNER JOIN district on district.districtid = city.districtid
                        where devices.customerno=%d AND unit.acsensor=1 AND unit.trans_statusid NOT IN (10,22) AND district.districtid=%d AND vehicleno LIKE '%s' $vehicle_ses AND `group`.isdeleted=0 ";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], Sanitise::String($srhstring), $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], Sanitise::String($srhstring));
                }
            } elseif ($_SESSION['roleid'] == '4') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                        INNER JOIN city on city.cityid = `group`.cityid
                        where devices.customerno=%d AND unit.acsensor=1 AND unit.trans_statusid NOT IN (10,22) AND city.cityid=%d AND vehicleno LIKE '%s' $vehicle_ses AND `group`.isdeleted=0 ";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], Sanitise::String($srhstring), $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], Sanitise::String($srhstring));
                }
            } elseif ($_SESSION['roleid'] == '8') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                        where devices.customerno=%d AND unit.acsensor=1 AND unit.trans_statusid NOT IN (10,22) AND vehicleno LIKE '%s' $vehicle_ses ";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring), $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring));
                }
            } else {
                $Query .= " where devices.customerno=%d AND unit.acsensor=1 AND unit.trans_statusid NOT IN (10,22) AND vehicleno LIKE '%s' $vehicle_ses";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring), $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring));
                }
            }
        } elseif (isset($_SESSION['ecodeid'])) {
            $Query = "SELECT * FROM `devices`
                    INNER JOIN unit ON unit.uid = devices.uid
                    INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                    INNER JOIN ecodeman ON ecodeman.vehicleid = unit.vehicleid
                    INNER JOIN driver ON vehicle.driverid = driver.driverid
                    WHERE devices.customerno=%d  AND ecodeman.ecodeid=%d AND unit.acsensor=1 AND unit.trans_statusid NOT IN (10,22) AND vehicleno LIKE '%s' $vehicle_ses
                    ORDER BY vehicle.vehicleno";
            $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['e_id'], Sanitise::String($srhstring));
        } elseif (isset($_SESSION['roleid']) && $this->VehicleUsermappingExists($_SESSION['userid']) == true) {
            $Query = "SELECT * FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid = %d";
            } else {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . "";
            }
            $Query .= " where devices.customerno=%d and vehmap.isdeleted = 0 AND unit.acsensor=1 AND unit.trans_statusid NOT IN (10,22) AND vehicleno LIKE '%s' $vehicle_ses";
            $Query .= "  ORDER BY vehicle.vehicleno";
            if ($_SESSION['groupid'] != 0) {
                $devicesQuery = sprintf($Query, $_SESSION['groupid'], $this->_Customerno, Sanitise::String($srhstring));
            } else {
                $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring));
            }
        } else {
            $Query = "SELECT * FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid
            where devices.customerno=%d AND unit.acsensor=1 AND unit.trans_statusid NOT IN (10,22) AND vehicleno LIKE '%s' $vehicle_ses";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " AND vehicle.groupid =%d";
            }
            $Query .= "  ORDER BY vehicle.vehicleno";
            if ($_SESSION['groupid'] != 0) {
                $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring), $_SESSION['groupid']);
            } else {
                $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring));
            }
        }
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['uid'] > 0) {
                    if ($row['devicelat'] > 0 & $row['devicelong'] > 0) {
                        $device->vehicleno = $row['vehicleno'];
                        $device->vehicleid = $row['vehicleid'];
                        $device->deviceid = $row['deviceid'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->drivername = $row['drivername'];
                        $device->driverphone = $row['driverphone'];
                        $device->curspeed = $row['curspeed'];
                        $device->lastupdated = $row['lastupdated'];
                        $device->type = $row['kind'];
                        $device->ignition = $row['ignition'];
                        $device->status = $row['status'];
                        $device->directionchange = $row['directionchange'];
                        $devices[] = $device;
                    }
                }
            }
            return $devices;
        }
        return null;
    }

    public function devicesformapping_extrasensor_byId($srhstring) {
        $devices = Array();
        $vehicle_ses = '';
        if ($_SESSION['switch_to'] == 3) {
            $vehicle_ses = " AND vehicle.kind = 'Warehouse'";
        } else {
            $vehicle_ses = " AND vehicle.kind <> 'Warehouse' ";
        }
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
            $Query = "SELECT * FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid";
            $devicesQuery = sprintf($Query, $this->_Customerno);
            if ($_SESSION['roleid'] == '2') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                        INNER JOIN city on city.cityid = `group`.cityid
                        INNER JOIN district on district.districtid = city.districtid
                        INNER JOIN state on state.stateid = district.stateid
                        where devices.customerno=%d AND unit.extra_digital<>0 AND unit.trans_statusid NOT IN (10,22) AND state.stateid=%d AND vehicleno LIKE '%s' $vehicle_ses AND `group`.isdeleted=0";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], Sanitise::String($srhstring), $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], Sanitise::String($srhstring));
                }
            } elseif ($_SESSION['roleid'] == '3') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                        INNER JOIN city on city.cityid = `group`.cityid
                        INNER JOIN district on district.districtid = city.districtid
                        where devices.customerno=%d AND unit.extra_digital<>0 AND unit.trans_statusid NOT IN (10,22) AND district.districtid=%d AND vehicleno LIKE '%s' $vehicle_ses AND `group`.isdeleted=0 ";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], Sanitise::String($srhstring), $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], Sanitise::String($srhstring));
                }
            } elseif ($_SESSION['roleid'] == '4') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                        INNER JOIN city on city.cityid = `group`.cityid
                        where devices.customerno=%d AND unit.extra_digital<>0 AND unit.trans_statusid NOT IN (10,22) AND city.cityid=%d AND vehicleno LIKE '%s' $vehicle_ses AND `group`.isdeleted=0 ";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], Sanitise::String($srhstring), $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], Sanitise::String($srhstring));
                }
            } elseif ($_SESSION['roleid'] == '8') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                        where devices.customerno=%d AND unit.extra_digital<>0 AND unit.trans_statusid NOT IN (10,22) AND vehicleno LIKE '%s' $vehicle_ses";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring), $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring));
                }
            } else {
                $Query .= " where devices.customerno=%d AND unit.extra_digital<>0 AND unit.trans_statusid NOT IN (10,22) AND vehicleno LIKE '%s' $vehicle_ses";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring), $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring));
                }
            }
        } elseif (isset($_SESSION['ecodeid'])) {
            $Query = "SELECT * FROM `devices`
                    INNER JOIN unit ON unit.uid = devices.uid
                    INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                    INNER JOIN ecodeman ON ecodeman.vehicleid = unit.vehicleid
                    INNER JOIN driver ON vehicle.driverid = driver.driverid
                    WHERE devices.customerno=%d  AND ecodeman.ecodeid=%d AND unit.extra_digital<>0 AND unit.trans_statusid NOT IN (10,22) AND vehicleno LIKE '%s' $vehicle_ses
                    ORDER BY vehicle.vehicleno";
            $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['e_id'], Sanitise::String($srhstring));
        } elseif (isset($_SESSION['roleid']) && $this->VehicleUsermappingExists($_SESSION['userid']) == true) {
            $Query = "SELECT * FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid = %d";
            } else {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . "";
            }
            $Query .= " where devices.customerno=%d and vehmap.isdeleted = 0 AND unit.extra_digital<>0 AND unit.trans_statusid NOT IN (10,22) AND vehicleno LIKE '%s' $vehicle_ses";
            $Query .= "  ORDER BY vehicle.vehicleno";
            if ($_SESSION['groupid'] != 0) {
                $devicesQuery = sprintf($Query, $_SESSION['groupid'], $this->_Customerno, Sanitise::String($srhstring));
            } else {
                $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring));
            }
        } else {
            $Query = "SELECT * FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid
            where devices.customerno=%d AND unit.extra_digital<>0 AND unit.trans_statusid NOT IN (10,22) AND vehicleno LIKE '%s' $vehicle_ses";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " AND vehicle.groupid =%d";
            }
            $Query .= "  ORDER BY vehicle.vehicleno";
            if ($_SESSION['groupid'] != 0) {
                $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring), $_SESSION['groupid']);
            } else {
                $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring));
            }
        }
        //echo $devicesQuery;
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['uid'] > 0) {
                    if ($row['devicelat'] > 0 & $row['devicelong'] > 0) {
                        $device->vehicleno = $row['vehicleno'];
                        $device->vehicleid = $row['vehicleid'];
                        $device->deviceid = $row['deviceid'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->drivername = $row['drivername'];
                        $device->driverphone = $row['driverphone'];
                        $device->curspeed = $row['curspeed'];
                        $device->lastupdated = $row['lastupdated'];
                        $device->type = $row['kind'];
                        $device->ignition = $row['ignition'];
                        $device->status = $row['status'];
                        $device->directionchange = $row['directionchange'];
                        $devices[] = $device;
                    }
                }
            }
            return $devices;
        }
        return null;
    }

    public function door_sensor_devices($srhstring) {
        $devices = Array();
        $vehicle_ses = '';
        if ($_SESSION['switch_to'] == 3) {
            $vehicle_ses = " AND vehicle.kind = 'Warehouse'";
        } else {
            $vehicle_ses = " AND vehicle.kind <> 'Warehouse' ";
        }
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
            $Query = "SELECT * FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid";
            $devicesQuery = sprintf($Query, $this->_Customerno);
            if ($_SESSION['roleid'] == '2') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                        INNER JOIN city on city.cityid = `group`.cityid
                        INNER JOIN district on district.districtid = city.districtid
                        INNER JOIN state on state.stateid = district.stateid
                        where devices.customerno=%d AND unit.doorsensor=1 AND state.stateid=%d AND vehicleno LIKE '%s' $vehicle_ses AND `group`.isdeleted=0";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], Sanitise::String($srhstring), $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], Sanitise::String($srhstring));
                }
            } elseif ($_SESSION['roleid'] == '3') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                        INNER JOIN city on city.cityid = `group`.cityid
                        INNER JOIN district on district.districtid = city.districtid
                        where devices.customerno=%d AND unit.doorsensor=1 AND district.districtid=%d AND vehicleno LIKE '%s' $vehicle_ses AND `group`.isdeleted=0 ";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], Sanitise::String($srhstring), $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], Sanitise::String($srhstring));
                }
            } elseif ($_SESSION['roleid'] == '4') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                        INNER JOIN city on city.cityid = `group`.cityid
                        where devices.customerno=%d AND unit.doorsensor=1 AND city.cityid=%d AND vehicleno LIKE '%s' $vehicle_ses AND `group`.isdeleted=0 ";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], Sanitise::String($srhstring), $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], Sanitise::String($srhstring));
                }
            } elseif ($_SESSION['roleid'] == '8') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                        where devices.customerno=%d AND unit.doorsensor=1 AND vehicleno LIKE '%s' $vehicle_ses";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring), $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring));
                }
            } else {
                $Query .= " where devices.customerno=%d AND unit.doorsensor=1 AND vehicleno LIKE '%s' $vehicle_ses";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                $Query .= "  ORDER BY vehicle.vehicleno";
                if ($_SESSION['groupid'] != 0) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring), $_SESSION['groupid']);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring));
                }
            }
        } elseif (isset($_SESSION['ecodeid'])) {
            $Query = "SELECT * FROM `devices`
                    INNER JOIN unit ON unit.uid = devices.uid
                    INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                    INNER JOIN ecodeman ON ecodeman.vehicleid = unit.vehicleid
                    WHERE devices.customerno=%d  AND ecodeman.ecodeid=%d
                    AND unit.doorsensor=1 AND vehicleno LIKE '%s' $vehicle_ses
                    ORDER BY vehicle.vehicleno";
            $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['e_id'], Sanitise::String($srhstring));
        } elseif (isset($_SESSION['roleid']) && $this->VehicleUsermappingExists($_SESSION['userid']) == true) {
            $Query = "SELECT * FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid = %d";
            } else {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . "";
            }
            $Query .= " where devices.customerno=%d and vehmap.isdeleted = 0 AND unit.doorsensor=1 AND vehicleno LIKE '%s' $vehicle_ses";
            $Query .= "  ORDER BY vehicle.vehicleno";
            if ($_SESSION['groupid'] != 0) {
                $devicesQuery = sprintf($Query, $_SESSION['groupid'], $this->_Customerno, Sanitise::String($srhstring));
            } else {
                $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring));
            }
        } else {
            $Query = "SELECT * FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            where devices.customerno=%d AND unit.doorsensor=1 AND vehicleno LIKE '%s' $vehicle_ses";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " AND vehicle.groupid =%d";
            }
            $Query .= "  ORDER BY vehicle.vehicleno";
            if ($_SESSION['groupid'] != 0) {
                $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring), $_SESSION['groupid']);
            } else {
                $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring));
            }
        }
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->vehicleno = $row['vehicleno'];
                $device->vehicleid = $row['vehicleid'];
                $device->deviceid = $row['deviceid'];
                $devices[] = $device;
            }
            return $devices;
        }
        return null;
    }

    public function devicesformappingwithecode($ecodeid) {
        $devices = Array();
        $Query = "SELECT vehicle.vehicleno,vehicle.vehicleid,devices.deviceid,
            devices.devicelat,devices.devicelong,driver.drivername,driver.driverphone,vehicle.curspeed,
            devices.lastupdated,vehicle.kind,devices.ignition,devices.status,devices.directionchange,
            devices.uid,elixiacode.expirydate FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid
            INNER JOIN ecodeman ON ecodeman.vehicleid = vehicle.vehicleid
            INNER JOIN elixiacode ON elixiacode.id = ecodeman.ecodeid
            where elixiacode.ecode=%d AND unit.trans_statusid NOT IN (10,22)";
        $devicesQuery = sprintf($Query, Sanitise::long($ecodeid));
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['uid'] > 0) {
                    if ($row['devicelat'] > 0 & $row['devicelong'] > 0) {
                        $checkstatus = $this->checkvalidity($row["expirydate"]);
                        if ($checkstatus == true) {
                            $device->vehicleno = $row['vehicleno'];
                            $device->vehicleid = $row['vehicleid'];
                            $device->deviceid = $row['deviceid'];
                            $device->devicelat = $row['devicelat'];
                            $device->devicelong = $row['devicelong'];
                            $device->drivername = $row['drivername'];
                            $device->driverphone = $row['driverphone'];
                            $device->curspeed = $row['curspeed'];
                            $device->lastupdated = $row['lastupdated'];
                            $device->type = $row['kind'];
                            $device->ignition = $row['ignition'];
                            $device->status = $row['status'];
                            $device->directionchange = $row['directionchange'];
                            $devices[] = $device;
                        }
                    }
                }
            }
            return $devices;
        }
        return null;
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

    public function deviceformapping($vehicleid) {
        $Query = "SELECT * FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid
            INNER JOIN customer ON customer.customerno = vehicle.customerno
            LEFT JOIN `group` on vehicle.groupid = `group`.groupid
            where devices.customerno=%d AND vehicle.vehicleid=%d AND unit.trans_statusid NOT IN (10,22) ORDER BY devices.lastupdated ASC";
        $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::long($vehicleid));
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['uid'] > 0) {
                    if (($row['devicelat'] > 0 & $row['devicelong'] > 0) || $row['kind'] == 'Warehouse') {
                        $device->vehicleno = $row['vehicleno'];
                        $device->vehicleid = $row['vehicleid'];
                        $device->deviceid = $row['deviceid'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->drivername = $row['drivername'];
                        $device->driverphone = $row['driverphone'];
                        $device->curspeed = $row['curspeed'];
                        $device->overspeed_limit = $row['overspeed_limit'];
                        $device->lastupdated = $row['lastupdated'];
                        $device->type = $row['kind'];
                        $device->ignition = $row['ignition'];
                        $device->status = $row['status'];
                        $device->directionchange = $row['directionchange'];
                        $device->extbatt = $row["extbatt"];
                        $device->inbatt = $row["inbatt"];
                        $device->stoppage_flag = $row["stoppage_flag"];
                        $device->stoppage_transit_time = $row["stoppage_transit_time"];
                        $device->gsmstrength = $row["gsmstrength"];
                        $device->description = $row["description"];
                        $device->groupname = $row["groupname"];
                        $device->analog1 = $row["analog1"];
                        $device->analog2 = $row["analog2"];
                        $device->analog3 = $row["analog3"];
                        $device->analog4 = $row["analog4"];
                        $device->tempsen1 = $row["tempsen1"];
                        $device->tempsen2 = $row["tempsen2"];
                        $device->tempsen3 = $row["tempsen3"];
                        $device->tempsen4 = $row["tempsen4"];
                        $device->n1 = $row["n1"];
                        $device->n2 = $row["n2"];
                        $device->n3 = $row["n3"];
                        $device->n4 = $row["n4"];
                        $device->temp_sensors = $row["temp_sensors"];
                        $device->use_humidity = $row["use_humidity"];
                        $device->humidity = $row["humidity"];
                        $device->get_conversion = $row["get_conversion"];
                        return $device;
                    }
                }
            }
        }
        return null;
    }

    public function deviceformapping_warehouse($vehicleid) {
        $Query = "SELECT * FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN warehouse ON warehouse.vehicleid = unit.vehicleid
            where devices.customerno=%d AND vehicle.vehicleid=%d AND unit.trans_statusid NOT IN (10,22) ORDER BY devices.lastupdated ASC";
        $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::long($vehicleid));
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['uid'] > 0) {
                    if ($row['devicelat'] > 0 & $row['devicelong'] > 0) {
                        $device->vehicleno = $row['vehicleno'];
                        $device->vehicleid = $row['vehicleid'];
                        $device->deviceid = $row['deviceid'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->curspeed = $row['curspeed'];
                        $device->overspeed_limit = $row['overspeed_limit'];
                        $device->lastupdated = $row['lastupdated'];
                        $device->type = $row['kind'];
                        $device->ignition = $row['ignition'];
                        $device->status = $row['status'];
                        $device->directionchange = $row['directionchange'];
                        $device->extbatt = $row["extbatt"];
                        $device->inbatt = $row["inbatt"];
                        $device->stoppage_flag = $row["stoppage_flag"];
                        $device->stoppage_transit_time = $row["stoppage_transit_time"];
                        $device->gsmstrength = $row["gsmstrength"];
                        $device->description = $row["description"];
                        return $device;
                    }
                }
            }
        }
        return null;
    }

    public function deviceformappings($vehicleids = null) {
        $devices = Array();
        $list = "vehicle.vehicleno
            ,vehicle.ignition_wirecut
            ,vehicle.kind
            ,vehicle.vehicleid
            ,vehicle.chkpoint_status
            ,vehicle.checkpoint_timestamp
            ,checkpoint.cname
            ,devices.deviceid
            ,devices.devicelat
            ,devices.devicelong
            ,driver.drivername
            ,driver.driverphone
            ,driver.driverid
            ,vehicle.overspeed_limit
            ,devices.lastupdated as dlastupdated
            ,vehicle.stoppage_flag
            ,vehicle.stoppage_transit_time
            ,devices.ignition
            ,devices.status
            ,devices.directionchange
            ,devices.tamper
            ,vehicle.curspeed
            ,unit.uid
            ,unit.unitno
            ,devices.powercut
            ,unit.msgkey
            ,unit.acsensor
            ,unit.is_door_opp
            ,unit.digitalio
            ,unit.digitalioupdated
            ,unit.door_digitalioupdated
            ,unit.is_ac_opp
            ,unit.analog1
            ,unit.analog2
            ,unit.analog3
            ,unit.analog4
            ,unit.get_conversion
            ,vehicle.temp1_mute
            ,vehicle.temp2_mute
            ,vehicle.temp3_mute
            ,vehicle.temp4_mute
            ,vehicle.temp1_min
            ,vehicle.temp2_min
            ,vehicle.temp3_min
            ,vehicle.temp4_min
            ,vehicle.temp1_max
            ,vehicle.temp2_max
            ,vehicle.temp3_max
            ,vehicle.temp4_max
            ,unit.tempsen1
            ,unit.tempsen2
            ,unit.tempsen3
            ,unit.tempsen4
            ,unit.n1
            ,unit.n2
            ,unit.n3
            ,unit.n4
            ,customer.use_geolocation
            ,customer.temp_sensors
            ,customer.use_humidity
            ,unit.humidity
            ,vehicle.description
            ,unit.extra_digitalioupdated
            ,unit.extra2_digitalioupdated
            ,unit.extra_digital
            , unit.setcom
            , g1.gensetno as genset1
            , g2.gensetno as genset2
            , t1.transmitterno as transmitter1
            , t2.transmitterno as transmitter2
            , devices.gpsfixed
            , vehicle.checkpointId
            , vehicle.routeDirection
            , unit.door_digitalio
            , unit.isDoorExt
            ,vehicle.sequenceno
            , vehicle_common_status_master.status AS vehicle_common_status
            , vehicle_common_status_master.color_code
            , unit.isGensetExt
            ";
        // if (!isset($_SESSION['ecodeid']) && $this->VehicleUsermappingExists($_SESSION['userid'])!=true) {
        if (!isset($_SESSION['ecodeid']) && $_SESSION['roleid'] != 43) {
            $Query = "SELECT $list FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = devices.customerno
                INNER JOIN driver ON vehicle.driverid = driver.driverid
                LEFT JOIN vehicle_common_status_master ON vehicle_common_status_master.id = vehicle.vehicle_status
                LEFT JOIN checkpoint ON checkpoint.checkpointid= vehicle.checkpointId and checkpoint.customerno = vehicle.customerno
                LEFT JOIN vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child = $this->_Customerno
                LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid
                LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid
                LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid
                LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid
                where (devices.customerno=%d || devices.customerno = vr.parent) and vehicle.kind<>'Warehouse' AND unit.trans_statusid NOT IN (10,22)";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " AND vehicle.groupid =%d";
            }
            if ($vehicleids != null) {
                $Query .= " AND vehicle.vehicleid IN (%s)";
            }
            $Query .= " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.sequenceno ASC, devices.lastupdated ASC";
            if ($_SESSION['groupid'] != 0) {
                if ($vehicleids != null) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid'], $vehicleids);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                }
            } else {
                if ($vehicleids != null) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $vehicleids);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno);
                }
            }
        } elseif (isset($_SESSION['roleid']) && $_SESSION['roleid'] == 43) {
            // elseif (isset($_SESSION['roleid']) && $this->VehicleUsermappingExists($_SESSION['userid'])==true) {
            $Query = "SELECT $list FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = devices.customerno
                INNER JOIN driver ON vehicle.driverid = driver.driverid
                LEFT JOIN vehicle_common_status_master ON vehicle_common_status_master.id = vehicle.vehicle_status
                LEFT JOIN checkpoint ON checkpoint.checkpointid= vehicle.checkpointId and checkpoint.customerno = vehicle.customerno
                LEFT JOIN vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child = $this->_Customerno
                LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid
                LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid
                LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid
                LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid = %d";
            } else {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . "";
            }
            $Query .= " where devices.customerno=%d and vehmap.isdeleted = 0 and vehicle.kind<>'Warehouse' AND unit.trans_statusid NOT IN (10,22)";
            if ($vehicleids != null) {
                $Query .= " AND vehicle.vehicleid IN (%s)";
            }
            $Query .= " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.sequenceno ASC, devices.lastupdated ASC";
            if ($_SESSION['groupid'] != 0) {
                if ($vehicleids != null) {
                    $devicesQuery = sprintf($Query, $_SESSION['groupid'], $this->_Customerno, $vehicleids);
                } else {
                    $devicesQuery = sprintf($Query, $_SESSION['groupid'], $this->_Customerno);
                }
            } else {
                if ($vehicleids != null) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $vehicleids);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno);
                }
            }
        } else {
            $Query = "SELECT $list  FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN ecodeman ON ecodeman.vehicleid = unit.vehicleid
                INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = devices.customerno
                INNER JOIN driver ON vehicle.driverid = driver.driverid
                LEFT JOIN vehicle_common_status_master ON vehicle_common_status_master.id = vehicle.vehicle_status
                LEFT JOIN checkpoint ON checkpoint.checkpointid= vehicle.checkpointId and checkpoint.customerno = vehicle.customerno
                LEFT JOIN vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child = $this->_Customerno
                LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid
                LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid
                LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid
                LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid
                where (ecodeman.customerno=%d || ecodeman.customerno = vr.parent) and vehicle.kind<>'Warehouse' AND ecodeman.ecodeid=%d AND ecodeman.isdeleted=0 AND unit.trans_statusid NOT IN (10,22)";
            if ($vehicleids != null) {
                $Query .= " AND vehicle.vehicleid IN (%s)";
            }
            $Query .= " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.sequenceno ASC, devices.lastupdated ASC";
            if ($vehicleids != null) {
                $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['e_id'], $vehicleids);
            } else {
                $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['e_id']);
            }
        }
        //echo $devicesQuery;
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['uid'] > 0) {
                    //if($row['devicelat']>0 & $row['devicelong']>0)
                    //{
                    $device->vehicleno = $row['vehicleno'];
                    $device->kind = $row['kind'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->deviceid = $row['deviceid'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->driverid = $row['driverid'];
                    $device->drivername = $row['drivername'];
                    $device->driverphone = $row['driverphone'];
                    $device->overspeed_limit = $row['overspeed_limit'];
                    if ($row['dlastupdated'] != '0000-00-00 00:00:00') {
                        $device->lastupdated_store = $row['dlastupdated'];
                        $device->lastupdated = $row['dlastupdated'];
                    } else {
                        $device->lastupdated_store = $row['dlastupdated'];
                        $device->lastupdated = $row['dlastupdated'];
                    }
                    $device->type = $row['kind'];
                    $device->stoppage_flag = $row['stoppage_flag'];
                    $device->stoppage_transit_time = $row['stoppage_transit_time'];
                    $device->ignition = $row['ignition'];
                    $device->status = $row['status'];
                    $device->directionchange = $row['directionchange'];
                    $device->tamper = $row["tamper"];
                    $device->curspeed = $row["curspeed"];
                    $device->unitno = $row["unitno"];
                    $device->powercut = $row["powercut"];
                    $device->msgkey = $row["msgkey"];
                    $device->acsensor = $row["acsensor"];
                    $device->is_door_opp = $row['is_door_opp'];
                    $device->digitalio = $row["digitalio"];
                    $device->digitalioupdated = $row["digitalioupdated"];
                    $device->door_digitalioupdated = $row["door_digitalioupdated"];
                    $device->isacopp = $row["is_ac_opp"];
                    $device->analog1 = $row["analog1"];
                    $device->analog2 = $row["analog2"];
                    $device->analog3 = $row["analog3"];
                    $device->analog4 = $row["analog4"];
                    $device->get_conversion = $row["get_conversion"];
                    $device->temp1_mute = $row['temp1_mute'];
                    $device->temp2_mute = $row['temp2_mute'];
                    $device->temp3_mute = $row['temp3_mute'];
                    $device->temp4_mute = $row['temp4_mute'];
                    $device->temp1_min = $row['temp1_min'];
                    $device->temp1_max = $row['temp1_max'];
                    $device->temp2_min = $row['temp2_min'];
                    $device->temp2_max = $row['temp2_max'];
                    $device->temp3_min = $row['temp3_min'];
                    $device->temp3_max = $row['temp3_max'];
                    $device->temp4_min = $row['temp4_min'];
                    $device->temp4_max = $row['temp4_max'];
                    $device->tempsen1 = $row["tempsen1"];
                    $device->tempsen2 = $row["tempsen2"];
                    $device->tempsen3 = $row["tempsen3"];
                    $device->tempsen4 = $row["tempsen4"];
                    $device->n1 = $row["n1"];
                    $device->n2 = $row["n2"];
                    $device->n3 = $row["n3"];
                    $device->n4 = $row["n4"];
                    $device->use_geolocation = $row["use_geolocation"];
                    $device->temp_sensors = $row["temp_sensors"];
                    $device->use_humidity = $row["use_humidity"];
                    $device->humidity = $row["humidity"];
                    $device->chkpoint_status = $row['chkpoint_status'];
                    $device->cname = $row['cname'];
                    $device->checkpoint_timestamp = $row['checkpoint_timestamp'];
                    $device->description = isset($row["description"]) ? $row["description"] : "";
                    $device->ignition_wirecut = $row["ignition_wirecut"];
                    $device->genset1 = $row["genset1"];
                    $device->genset2 = $row["genset2"];
                    $device->transmitter1 = $row["transmitter1"];
                    $device->transmitter2 = $row["transmitter2"];
                    $device->setcom = $row["setcom"];
                    $device->extra_digitalioupdated = $row["extra_digitalioupdated"];
                    $device->extra2_digitalioupdated = $row["extra2_digitalioupdated"];
                    $device->gpsfixed = $row["gpsfixed"];
                    $device->checkpointId = $row["checkpointId"];
                    $device->routeDirection = $row["routeDirection"];
                    $device->door_digitalio = $row['door_digitalio'];
                    $device->isDoorExt = $row['isDoorExt'];
                    $device->deviceStatus = $this->getVehicleStatus($device);
                    $device->vehicle_status = $row['vehicle_common_status'];
                    $device->vehicle_status_color_code = $row['color_code'];
                    $device->extra_digital = $row["extra_digital"];
                    if (isset($row['isGensetExt']) && $row['isGensetExt'] == 1) {
                        if (isset($row['analog1']) && $row['analog1'] > 0) {
                            $device->extra_digital = 1;
                        } else {
                            $device->extra_digital = 0;
                        }
                    }
                    $devices[] = $device;
                    //}
                }
            }
        }
        /* echo"Data in array:<pre>";
        print_r($devices); exit(); */
        return $devices;
    }

    public function getVehicleStatus($device) {
        $status = 0;
        $temp_coversion = new TempConversion();
        $temp_coversion->unit_type = $device->get_conversion;
        $date = new Date();
        $directionfile = round((int) $device->directionchange / 10);
        if ($device->type == 'Warehouse') {
            $ServerIST_less1 = new DateTime();
            $ServerIST_less1->modify('-60 minutes');
            $lastupdated = new DateTime($device->lastupdated);
            if ($lastupdated < $ServerIST_less1) {
                $status = "inactive";
            } else {
                if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 1) {
                    $temp = '';
                    $s = "analog" . $device->tempsen1;
                    if ($device->tempsen1 != 0 && $device->$s != 0) {
                        $temp_coversion->rawtemp = $device->$s;
                        $temp = getTempUtil($temp_coversion);
                    } else {
                        $temp = '';
                    }
                    if ($temp != '' && ($temp < $device->temp1_min || $temp > $device->temp1_max)) {
                        $status = "conflict";
                    } else {
                        $status = "on";
                    }
                } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 2) {
                    $temp1 = '';
                    $temp2 = '';
                    $s = "analog" . $device->tempsen1;
                    if ($device->tempsen1 != 0 && $device->$s != 0) {
                        $temp_coversion->rawtemp = $device->$s;
                        $temp1 = getTempUtil($temp_coversion);
                    } else {
                        $temp1 = '';
                    }
                    $s = "analog" . $device->tempsen2;
                    if ($device->tempsen2 != 0 && $device->$s != 0) {
                        $temp_coversion->rawtemp = $device->$s;
                        $temp2 = getTempUtil($temp_coversion);
                    } else {
                        $temp2 = '';
                    }
                    if ($temp1 != '' && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max)) {
                        $status = "conflict";
                    } elseif ($temp2 != '' && ($temp2 < $device->temp2_min || $temp2 > $device->temp2_max)) {
                        $status = "conflict";
                    } else {
                        $status = "on";
                    }
                } else {
                    $status = "on";
                }
            }
        } else {
            $ServerIST_less1 = new DateTime();
            $ServerIST_less1->modify('-60 minutes');
            $lastupdated = new DateTime($device->lastupdated);
            if ($lastupdated < $ServerIST_less1) {
                $status = "Inactive";
            } elseif ($device->ignition == '0') {
                $status = "IdleIgnOff";
            } else {
                if ($device->curspeed > $device->overspeed_limit) {
                    $status = "Overspeed";
                } else {
                    if ($device->stoppage_flag == '0') {
                        $status = "Idle";
                    } else {
                        $status = "Running";
                    }
                }
            }
        }
        return $status;
    }

    public function vhclRmnDtls($vehicleid) {
        $devices = Array();
        $Query = "SELECT  vehicle.vehicleno
                        , vehicle.chkpoint_status
                        , unit.unitno
                        , unit.is_buzzer
                        , unit.is_mobiliser
                        , unit.is_freeze
                        , `group`.groupname
                        , `group`.groupid
                        , driver.drivername
                        , checkpoint.cname
                        , driver.driverphone FROM `devices`
                            INNER JOIN unit ON unit.uid = devices.uid
                            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                            LEFT JOIN `group` ON `group`.groupid=vehicle.groupid
                            LEFT JOIN driver ON driver.driverid = vehicle.driverid
                            LEFT JOIN checkpoint ON checkpoint.checkpointid = vehicle.checkpointId
                            where vehicle.vehicleid=%d
                            ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.sequenceno ASC ";
        $devicesQuery = sprintf($Query, $vehicleid);
//        echo $devicesQuery;
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $device = new stdClass();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device->unitno = $row["unitno"];
                $device->vehicleno = $row["vehicleno"];
                $device->is_mobiliser = $row["is_mobiliser"];
                $device->is_buzzer = $row["is_buzzer"];
                $device->is_freeze = $row["is_freeze"];
                $device->groupname = $row["groupname"];
                $device->groupid = $row["groupid"];
                $device->drivername = $row["drivername"];
                $device->driverphone = $row["driverphone"];
                $device->cname = $row["cname"];
                $device->chkpoint_status = $row["chkpoint_status"];
                $devices[] = $device;
            }
        }
        return $devices;
    }

    public function deviceformappings_wh($vehicleids = null) {
        $devices = Array();
        $list = "vehicle.vehicleno
            ,vehicle.kind
            ,vehicle.customerno
            ,vehicle.vehicleid
            ,devices.deviceid
            ,devices.devicelat
            ,devices.devicelong
            ,driver.drivername
            ,driver.driverphone
            ,vehicle.overspeed_limit
            ,devices.lastupdated as dlastupdated
            ,vehicle.stoppage_flag
            ,vehicle.stoppage_transit_time
            ,devices.ignition
            ,devices.status
            ,devices.directionchange
            ,devices.tamper
            ,vehicle.curspeed
            ,unit.uid
            ,unit.unitno
            ,devices.powercut
            ,unit.msgkey
            ,unit.acsensor
            ,unit.is_door_opp
            ,unit.digitalio
            ,unit.digitalioupdated
            ,unit.door_digitalioupdated
            ,unit.is_ac_opp
            ,unit.analog1
            ,unit.analog2
            ,unit.analog3
            ,unit.analog4
            ,vehicle.temp1_mute
            ,vehicle.temp2_mute
            ,vehicle.temp3_mute
            ,vehicle.temp4_mute
            ,vehicle.temp1_min
            ,vehicle.temp2_min
            ,vehicle.temp3_min
            ,vehicle.temp4_min
            ,vehicle.temp1_max
            ,vehicle.temp2_max
            ,vehicle.temp3_max
            ,vehicle.temp4_max
            ,unit.tempsen1
            ,unit.tempsen2
            ,unit.tempsen3
            ,unit.tempsen4
            ,unit.get_conversion
            ,unit.n1
            ,unit.n2
            ,unit.n3
            ,unit.n4
            ,customer.use_geolocation
            ,customer.temp_sensors
            ,customer.use_humidity
            ,unit.humidity
            ,vehicle.description";
        if (!isset($_SESSION['ecodeid']) && $this->VehicleUsermappingExists($_SESSION['userid']) != true) {
            $Query = "SELECT $list  FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = devices.customerno
                INNER JOIN driver ON vehicle.driverid = driver.driverid
                LEFT JOIN vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child = $this->_Customerno
                where ( devices.customerno=%d || devices.customerno = vr.parent) and vehicle.kind='Warehouse' AND unit.trans_statusid NOT IN (10,22)";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " AND vehicle.groupid =%d";
            }
            if ($vehicleids != null) {
                $Query .= " AND vehicle.vehicleid IN (%s)";
            }
            $Query .= " ORDER BY devices.lastupdated ASC";
            if ($_SESSION['groupid'] != 0) {
                if ($vehicleids != null) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid'], $vehicleids);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                }
            } else {
                if ($vehicleids != null) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $vehicleids);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno);
                }
            }
        } elseif (isset($_SESSION['roleid']) && $this->VehicleUsermappingExists($_SESSION['userid']) == true) {
            $Query = "SELECT $list FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN customer ON customer.customerno = devices.customerno
                INNER JOIN driver ON vehicle.driverid = driver.driverid
                LEFT JOIN vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child = $this->_Customerno";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid = %d";
            } else {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . "";
            }
            $Query .= " where (devices.customerno=%d || devices.customerno = vr.parent) and vehmap.isdeleted = 0 and vehicle.kind='Warehouse' AND unit.trans_statusid NOT IN (10,22)";
            if ($vehicleids != null) {
                $Query .= " AND vehicle.vehicleid IN (%s)";
            }
            $Query .= " ORDER BY devices.lastupdated ASC, devices.customerno";
            if ($_SESSION['groupid'] != 0) {
                if ($vehicleids != null) {
                    $devicesQuery = sprintf($Query, $_SESSION['groupid'], $this->_Customerno, $vehicleids);
                } else {
                    $devicesQuery = sprintf($Query, $_SESSION['groupid'], $this->_Customerno);
                }
            } else {
                if ($vehicleids != null) {
                    $devicesQuery = sprintf($Query, $this->_Customerno, $vehicleids);
                } else {
                    $devicesQuery = sprintf($Query, $this->_Customerno);
                }
            }
        } else {
            $Query = "SELECT $list FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN ecodeman ON ecodeman.vehicleid = unit.vehicleid
                INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = devices.customerno
                INNER JOIN driver ON vehicle.driverid = driver.driverid
                LEFT JOIN vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child = $this->_Customerno
                where (ecodeman.customerno=%d || ecodeman.customerno = vr.parent) and vehicle.kind='Warehouse' AND ecodeman.ecodeid=%d AND unit.trans_statusid NOT IN (10,22)";
            if ($vehicleids != null) {
                $Query .= " AND vehicle.vehicleid IN (%s)";
            }
            $Query .= " ORDER BY devices.lastupdated ASC, ecodeman.customerno";
            if ($vehicleids != null) {
                $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['e_id'], $vehicleids);
            } else {
                $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['e_id']);
            }
        }
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['uid'] > 0) {
                    //if($row['devicelat']>0 & $row['devicelong']>0)
                    //{
                    $device->vehicleno = $row['vehicleno'];
                    $device->customerno = $row['customerno'];
                    $device->kind = $row['kind'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->deviceid = $row['deviceid'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->drivername = $row['drivername'];
                    $device->driverphone = $row['driverphone'];
                    $device->overspeed_limit = $row['overspeed_limit'];
                    $device->lastupdated = $row['dlastupdated'];
                    $device->lastupdated_store = $row['dlastupdated'];
                    $device->type = $row['kind'];
                    $device->stoppage_flag = $row['stoppage_flag'];
                    $device->stoppage_transit_time = $row['stoppage_transit_time'];
                    $device->ignition = $row['ignition'];
                    $device->status = $row['status'];
                    $device->directionchange = $row['directionchange'];
                    $device->tamper = $row["tamper"];
                    $device->curspeed = $row["curspeed"];
                    $device->unitno = $row["unitno"];
                    $device->powercut = $row["powercut"];
                    $device->msgkey = $row["msgkey"];
                    $device->acsensor = $row["acsensor"];
                    $device->is_door_opp = $row['is_door_opp'];
                    $device->digitalio = $row["digitalio"];
                    $device->digitalioupdated = $row["digitalioupdated"];
                    $device->door_digitalioupdated = $row["digitalioupdated"];
                    $device->isacopp = $row["is_ac_opp"];
                    $device->analog1 = $row["analog1"];
                    $device->analog2 = $row["analog2"];
                    $device->analog3 = $row["analog3"];
                    $device->analog4 = $row["analog4"];
                    $device->temp1_mute = $row['temp1_mute'];
                    $device->temp2_mute = $row['temp2_mute'];
                    $device->temp3_mute = $row['temp3_mute'];
                    $device->temp4_mute = $row['temp4_mute'];
                    $device->temp1_min = $row['temp1_min'];
                    $device->temp1_max = $row['temp1_max'];
                    $device->temp2_min = $row['temp2_min'];
                    $device->temp2_max = $row['temp2_max'];
                    $device->temp3_min = $row['temp3_min'];
                    $device->temp3_max = $row['temp3_max'];
                    $device->temp4_min = $row['temp4_min'];
                    $device->temp4_max = $row['temp4_max'];
                    $device->tempsen1 = $row["tempsen1"];
                    $device->tempsen2 = $row["tempsen2"];
                    $device->tempsen3 = $row["tempsen3"];
                    $device->tempsen4 = $row["tempsen4"];
                    $device->get_conversion = $row["get_conversion"];
                    $device->humidity = $row["humidity"];
                    $device->n1 = $row["n1"];
                    $device->n2 = $row["n2"];
                    $device->n3 = $row["n3"];
                    $device->n4 = $row["n4"];
                    $device->use_geolocation = $row["use_geolocation"];
                    $device->temp_sensors = $row["temp_sensors"];
                    $device->use_humidity = $row["use_humidity"];
                    $device->description = $row["description"];
                    $device->deviceStatus = $this->getVehicleStatus($device);
                    $devices[] = $device;
                }
            }
        }
        return $devices;
    }

    public function deviceformappings_warehouses() {
        $devices = Array();
        if (!isset($_SESSION['ecodeid'])) {
            $Query = "SELECT *, customer.use_geolocation, devices.lastupdated,unit.get_conversion as dlastupdated FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN warehouse ON warehouse.vehicleid = unit.vehicleid
                INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = devices.customerno
               LEFT OUTER JOIN driver ON warehouse.driverid = driver.driverid
                where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22)";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " AND vehicle.groupid =%d";
            }
            $Query .= " ORDER BY devices.lastupdated ASC";
            if ($_SESSION['groupid'] != 0) {
                $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
            } else {
                $devicesQuery = sprintf($Query, $this->_Customerno);
            }
        } else {
            $Query = "SELECT *, customer.use_geolocation, devices.lastupdated,unit.get_conversion as dlastupdated FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN warehouse ON warehouse.vehicleid = unit.vehicleid
                INNER JOIN ecodeman ON ecodeman.vehicleid = unit.vehicleid
                INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = devices.customerno
               LEFT OUTER JOIN driver ON warehouse.driverid = driver.driverid
                where ecodeman.customerno=%d AND ecodeman.ecodeid=%d AND unit.trans_statusid NOT IN (10,22)";
            $Query .= " ORDER BY devices.lastupdated ASC";
            $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['e_id']);
        }
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['uid'] > 0) {
                    //if($row['devicelat']>0 & $row['devicelong']>0)
                    //{
                    $device->vehicleno = $row['vehicleno'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->deviceid = $row['deviceid'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->drivername = $row['drivername'];
                    $device->driverphone = $row['driverphone'];
                    $device->overspeed_limit = $row['overspeed_limit'];
                    $device->lastupdated = $row['dlastupdated'];
                    $device->lastupdated_store = $row['dlastupdated'];
                    $device->type = $row['kind'];
                    $device->stoppage_flag = $row['stoppage_flag'];
                    $device->stoppage_transit_time = $row['stoppage_transit_time'];
                    $device->ignition = $row['ignition'];
                    $device->status = $row['status'];
                    $device->directionchange = $row['directionchange'];
                    $device->tamper = $row["tamper"];
                    $device->curspeed = $row["curspeed"];
                    $device->unitno = $row["unitno"];
                    $device->powercut = $row["powercut"];
                    $device->msgkey = $row["msgkey"];
                    $device->acsensor = $row["acsensor"];
                    $device->is_door_opp = $row['is_door_opp'];
                    $device->digitalio = $row["digitalio"];
                    $device->digitalioupdated = $row["digitalioupdated"];
                    $device->isacopp = $row["is_ac_opp"];
                    $device->analog1 = $row["analog1"];
                    $device->analog2 = $row["analog2"];
                    $device->analog3 = $row["analog3"];
                    $device->analog4 = $row["analog4"];
                    $device->get_conversion = $row["get_conversion"];
                    $device->tempsen1 = $row["tempsen1"];
                    $device->tempsen2 = $row["tempsen2"];
                    $device->use_geolocation = $row["use_geolocation"];
                    $device->temp_sensors = $row["temp_sensors"];
                    $device->description = $row["description"];
                    $devices[] = $device;
                    //}
                }
            }
        }
        return $devices;
    }

    public function deviceformappingforecode($vehicleid) {
        $Query = "SELECT * FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid
            where vehicle.vehicleid=%d AND unit.trans_statusid NOT IN (10,22) ORDER BY devices.lastupdated ASC";
        $devicesQuery = sprintf($Query, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['uid'] > 0) {
                    if ($row['devicelat'] > 0 & $row['devicelong'] > 0) {
                        $device->vehicleno = $row['vehicleno'];
                        $device->vehicleid = $row['vehicleid'];
                        $device->deviceid = $row['deviceid'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->drivername = $row['drivername'];
                        $device->driverphone = $row['driverphone'];
                        $device->curspeed = $row['curspeed'];
                        $device->lastupdated = $row['lastupdated'];
                        $device->type = $row['kind'];
                        $device->ignition = $row['ignition'];
                        $device->status = $row['status'];
                        $device->directionchange = $row['directionchange'];
                        $devices[] = $device;
                    }
                }
            }
            return $device;
        }
        return null;
    }

    public function deviceforfence($fenceid) {
        $devices = Array();
        $Query = "SELECT * FROM `devices`
            INNER JOIN vehicle ON vehicle.devicekey = devices.devicekey
            INNER JOIN driver ON vehicle.driverid = driver.driverid
            INNER JOIN fence ON vehicle.vehicleid = fence.vehicleid
            where devices.customerno=%d and fence.fenceid=%d";
        $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($fenceid));
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['uid'] > 0) {
                    if ($row['devicelat'] > 0 & $row['devicelong'] > 0) {
                        $device->vehicleno = $row['vehicleno'];
                        $device->vehicleid = $row['vehicleid'];
                        $device->deviceid = $row['deviceid'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->drivername = $row['drivername'];
                        $device->driverphone = $row['driverphone'];
                        $device->curspeed = $row['curspeed'];
                        $device->lastupdated = $row['lastupdated'];
                        $device->type = $row['kind'];
                        $devices[] = $device;
                    }
                }
            }
            return $devices;
        }
        return null;
    }

    public function getdevicesforrtd() {
        $devices = Array();
        $Query = "SELECT unit.acsensor, vehicle.vehicleno, unit.unitno,
            devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,
            unit.digitalio, devices.gsmstrength, devices.lastupdated, devices.registeredon,
            unit.tempsen1,unit.tempsen2,devices.deviceid FROM `devices`
            INNER JOIN vehicle ON vehicle.uid = devices.uid
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            WHERE devices.`customerno` =%d AND vehicle.isdeleted=0 AND unit.trans_statusid NOT IN (10,22) ORDER BY devices.lastupdated DESC";
        $devicesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->vehicleno = $row['vehicleno'];
                $device->unitno = $row['unitno'];
                $device->tamper = $row['tamper'];
                $device->powercut = $row['powercut'];
                $device->inbatt = $row['inbatt'];
                $device->analog1 = $row['analog1'];
                $device->analog2 = $row['analog2'];
                $device->digitalio = $row['digitalio'];
                $device->gsmstrength = $row['gsmstrength'];
                $device->acsensor = $row['acsensor'];
                $device->tempsen1 = $row['tempsen1'];
                $device->tempsen2 = $row['tempsen2'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $device->lastupdated = $row['lastupdated'];
                } else {
                    $device->lastupdated = $row['registeredon'];
                }
                $devices[] = $device;
            }
            return $devices;
        }
        return null;
    }

    public function getsimforrtd() {
        $devices = Array();
        $Query = "SELECT devices.deviceid, vehicle.vehicleno,
            unit.unitno, devices.gpsfixed, devices.gsmstrength,
            devices.gsmregister, devices.gprsregister,devices.lastupdated,
            simcard.simcardno,
            devices.registeredon FROM `devices`
            LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid
            INNER JOIN vehicle ON vehicle.uid = devices.uid
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            WHERE devices.`customerno` =%d AND vehicle.isdeleted=0 AND unit.trans_statusid NOT IN (10,22) ";
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
        }
        $Query .= " ORDER BY devices.lastupdated DESC";
        if ($_SESSION['groupid'] != 0) {
            $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $devicesQuery = sprintf($Query, $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->vehicleno = $row['vehicleno'];
                $device->phone = $row['simcardno'];
                $device->unitno = $row['unitno'];
                $device->gpsfixed = $row['gpsfixed'];
                $device->gsmstrength = $row['gsmstrength'];
                $device->gsmregister = $row['gsmregister'];
                $device->gprsregister = $row['gprsregister'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $device->lastupdated = $row['lastupdated'];
                } else {
                    $device->lastupdated = $row['registeredon'];
                }
                $devices[] = $device;
            }
            return $devices;
        }
        return null;
    }

    public function getmiscforrtd() {
        $devices = Array();
        $Query = "SELECT devices.deviceid, vehicle.vehicleno,
            unit.unitno, devices.status, devices.`online/offline`,
            unit.analog3, unit.analog4,unit.commandkey,unit.commandkeyval,
            devices.lastupdated, driver.driverphone, devices.registeredon FROM devices
            INNER JOIN vehicle ON vehicle.uid = devices.uid
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            WHERE devices.`customerno` =%d AND vehicle.isdeleted=0 AND unit.trans_statusid NOT IN (10,22) ORDER BY devices.lastupdated DESC";
        $devicesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->vehicleno = $row['vehicleno'];
                $device->unitno = $row['unitno'];
                $device->status = $row['status'];
                $device->online_offline = $row['online/offline'];
                $device->analog3 = $row['analog3'];
                $device->analog4 = $row['analog4'];
                $device->commandkey = $row['commandkey'];
                $device->commandkeyval = $row['commandkeyval'];
                $device->phone = $row['driverphone'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $device->lastupdated = $row['lastupdated'];
                } else {
                    $device->lastupdated = $row['registeredon'];
                }
                $devices[] = $device;
            }
            return $devices;
        }
        return null;
    }

    public function getmiscforhistory($vehicleid) {
        $devices = Array();
        $devicesQuery = sprintf("SELECT devicehistory.deviceid, vehicle.vehicleno,
            unithistory.unitno, devicehistory.status, devicehistory.`online/offline`,
            unithistory.analog3, unithistory.analog4, unithistory.digitalio, unithistory.commandkey,
            unithistory.commandkeyval, devicehistory.lastupdated, devicehistory.ignition
            FROM `devicehistory`
            LEFT OUTER JOIN devices ON devices.devicekey = devicehistory.devicekey
            LEFT OUTER JOIN vehicle ON vehicle.devicekey = devicehistory.devicekey
            LEFT OUTER JOIN unithistory ON unithistory.dhid = devicehistory.id
            WHERE devicehistory.`customerno` =%s AND vehicle.vehicleid =%s
            ORDER BY devicehistory.lastupdated ASC", $this->_Customerno, $vehicleid);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['ignition'] == '0' && $row['status'] != 'J') {
                    $device->deviceid = $row['deviceid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->unitno = $row['unitno'];
                    $device->status = 'E';
                    $device->online_offline = $row['online/offline'];
                    $device->analog3 = $row['analog3'];
                    $device->analog4 = $row['analog4'];
                    $device->digitalio = $row['digitalio'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->commandkey = $row['commandkey'];
                    $device->commandkeyval = $row['commandkeyval'];
                    $devices[] = $device;
                } else {
                    $device->deviceid = $row['deviceid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->unitno = $row['unitno'];
                    $device->status = $row['status'];
                    $device->online_offline = $row['online/offline'];
                    $device->analog3 = $row['analog3'];
                    $device->analog4 = $row['analog4'];
                    $device->digitalio = $row['digitalio'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->commandkey = $row['commandkey'];
                    $device->commandkeyval = $row['commandkeyval'];
                    $devices[] = $device;
                }
            }
            return array_reverse($devices);
        }
        return null;
    }

//    public function modunit($uid,$phoneno)
    //    {
    //        $SQL = sprintf("UPDATE devices SET phone='%s' WHERE uid=%d AND customerno=%d",
    // Sanitise::String($phoneno), Sanitise::Long($uid), $this->_Customerno);
    //        $this->_databaseManager->executeQuery($SQL);
    //    }
    //
    public function DeviceInfo($vehicleid) {
        $devicesQuery = sprintf("SELECT vehicle.vehicleno,unit.unitno,devices.customerno, unit.tempsen1, unit.tempsen2, unit.acsensor,vehicle.kind
            FROM `devices`
            INNER JOIN vehicle ON vehicle.uid = devices.uid
            INNER JOIN unit ON devices.uid = unit.uid
            WHERE devices.customerno = %s AND vehicle.vehicleid=%s AND unit.trans_statusid NOT IN (10,22)", $this->_Customerno, $vehicleid);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->unitno = $row['unitno'];
                $device->vehicleno = $row['vehicleno'];
                $device->tempsen1 = $row['tempsen1'];
                $device->tempsen2 = $row['tempsen2'];
                $device->acsensor = $row['acsensor'];
                $device->type = $row['kind'];
                return $device;
            }
        }
        return null;
    }

    //Cron Queries
    //Overspeeding
    public function markoverspeeding($vehicleid) {
        $Query = "Update eventalerts Set `overspeed`=1 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function marknormalspeeding($vehicleid) {
        $Query = "Update eventalerts Set `overspeed`=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    //Ignition
    public function markignitionstatus($device, $count) {
        $Query = "Update ignitionalert Set `count`=%d, `last_status` = %d WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($count), Sanitise::Long($device->ignition), Sanitise::Long($device->vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function changelastigcheck($device) {
        $Query = "Update ignitionalert SET last_check = '%s' Where vehicleid =%d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::DateTime($device->lastupdated), Sanitise::String($device->vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markignitionon($vehicleid) {
        $Query = "Update ignitionalert Set `count`=0, status = 1 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markignitionoff($vehicleid) {
        $Query = "Update ignitionalert Set `count`=0, status = 0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    //PowerCut
    public function markpowercut($vehicleid) {
        $Query = "Update eventalerts Set `powercut`=1 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markpowerin($vehicleid) {
        $Query = "Update eventalerts Set `powercut`=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    //Tampering
    public function marktampered($vehicleid) {
        $Query = "Update eventalerts Set `tamper`=1 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markuntampered($vehicleid) {
        $Query = "Update eventalerts Set `tamper`=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    //AC
    public function markacon($vehicleid) {
        $Query = "Update eventalerts Set `ac`=1 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markacoff($vehicleid) {
        $Query = "Update eventalerts Set `ac`=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function marktempon($vehicleid) {
        $Query = "Update eventalerts Set `temp`=1 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function marktempoff($vehicleid) {
        $Query = "Update eventalerts Set `temp`=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getdeviceignitionstatusforhistory($deviceid, $date) {
        $devices = Array();
        $Query = "SELECT devicehistory.deviceid, devicehistory.ignition,
            devicehistory.status,devicehistory.lastupdated from devicehistory WHERE devicehistory.customerno = %d
            AND devicehistory.deviceid= %d and DATE(devicehistory.lastupdated) between '%s' and '%s'
            ORDER BY devicehistory.lastupdated ASC";
        $devicesQuery = sprintf($Query, $this->_Customerno, $deviceid, $date, $date);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->deviceid = $row['deviceid'];
                $device->ignition = $row['ignition'];
                $device->status = $row['status'];
                $device->lastupdated = $row['lastupdated'];
                $devices[] = $device;
            }
            return $devices;
        }
        return null;
    }

    //Travel Report
    public function getvehiclenofromdeviceid($vehicleid) {
        $Query = 'select vehicleno from vehicle WHERE vehicleid = %d';
        $deviceQuery = sprintf($Query, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($deviceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row['vehicleno'];
            }
        }
        return NULL;
    }

    public function getacinfo($vehicleid) {
        $Query = 'SELECT * from acalerts INNER JOIN ' . DB_PARENT . '.customer ON customer.customerno = acalerts.customerno where vehicleid=%d';
        $deviceQuery = sprintf($Query, Sanitise::String($vehicleid));
        $this->_databaseManager->executeQuery($deviceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->ac_sen_id = $row['acalertid'];
                $device->firstcheck = $row['firstcheck'];
                $device->last_ignition = $row['last_ignition'];
                $device->aci_time = $row['aci_time'];
                return $device;
            }
        }
        return NULL;
    }

    public function addacinfo($device) {
        $Query = "INSERT INTO ac_sensor (firstcheck,last_ignition,customerno,deviceid) VALUES ('%s',%d,%d,%d)";
        $deviceQuery = sprintf($Query, $device->lastupdated, $device->ignition, $this->_Customerno, $device->deviceid);
        $this->_databaseManager->executeQuery($deviceQuery);
    }

    public function updateacinfo($time, $ignition, $deviceid) {
        $Query = "UPDATE ac_sensor SET firstcheck='%s',last_ignition=%d where deviceid=%d and customerno=%d";
        $deviceQuery = sprintf($Query, $time, Sanitise::Long($ignition), Sanitise::Long($deviceid), $this->_Customerno);
        $this->_databaseManager->executeQuery($deviceQuery);
    }

    public function updateacistatus($deviceid, $status) {
        $Query = 'UPDATE devices SET aci_status=%d where deviceid=%d and customerno=%d';
        $deviceQuery = sprintf($Query, $status, $deviceid, $this->_Customerno);
        $this->_databaseManager->executeQuery($deviceQuery);
    }

    public function get_ac_data_for_date($date, $deviceid) {
        $Query = "SELECT unithistory.digitalio,devicehistory.ignition,devicehistory.lastupdated FROM devicehistory ";
        $Query .= " INNER JOIN unithistory ON unithistory.dhid = devicehistory.id";
        $Query .= " WHERE devicehistory.id = %d AND DATE(devicehistory.lastupdated) BETWEEN '%s' AND '%s' ";
        $Query .= " AND devicehistory.customerno = %d AND devicehistory.status!='H' AND devicehistory.status!='F' ";
        $Query .= " ORDER BY devicehistory.lastupdated ASC";
        $deviceQuery = sprintf($Query, $deviceid, $date, $date, $this->_Customerno);
        $this->_databaseManager->executeQuery($deviceQuery);
        $devices = array();
        $laststatus = array();
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if (@$laststatus['digitalio'] != $row['digitalio'] || @$laststatus['ig'] != $row['ignition']) {
                    $device = new VODevices();
                    $device->digitalio = $row['digitalio'];
                    $device->ignition = $row['ignition'];
                    $device->lastupdated = $row['lastupdated'];
                    $laststatus['ig'] = $row['ignition'];
                    $laststatus['digitalio'] = $row['digitalio'];
                    $devices[] = $device;
                }
            }
            return $devices;
        }
    }

    public function get_last_row_ac_data_for_date($date, $deviceid) {
        $Query = "SELECT unithistory.digitalio,devicehistory.ignition,devicehistory.lastupdated FROM devicehistory";
        $Query .= " INNER JOIN unithistory ON unithistory.dhid = devicehistory.id";
        $Query .= " WHERE devicehistory.id = %d AND DATE(devicehistory.lastupdated) BETWEEN '%s' AND '%s' ";
        $Query .= " AND devicehistory.customerno = %d AND devicehistory.status!='H' AND devicehistory.status!='F'";
        $Query .= " ORDER BY devicehistory.lastupdated DESC Limit 0,1";
        $deviceQuery = sprintf($Query, $deviceid, $date, $date, $this->_Customerno);
        $this->_databaseManager->executeQuery($deviceQuery);
        $devices = array();
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->digitalio = $row['digitalio'];
                $device->ignition = $row['ignition'];
                $device->lastupdated = $row['lastupdated'];
                $devices[] = $device;
            }
            return $devices;
        }
    }

    public function get_unitno_by_vehicle_id($vehicleid) {
        $sql = "select unitno from unit where vehicleid=" . $vehicleid . "";
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row['unitno'];
            }
        } else {
            return 0;
        }
    }

    public function get_vehicle_by_vehicle_id($vehicleid) {
        $sql = "select vehicleno from vehicle where vehicleid=" . $vehicleid . "";
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row['vehicleno'];
            }
        } else {
            return 0;
        }
    }

    public function getallalerttype() {
        $sql = "select * from " . DB_PARENT . ".status";
        $this->_databaseManager->executeQuery($sql);
        $status = array();
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->id = $row['id'];
                $device->status = $row['status'];
                $status[] = $device;
            }
            return $status;
        }
        return NULL;
    }

    public function updatenodata_alert($vehicleid, $nodataalert) {
        $Query = 'UPDATE vehicle SET nodata_alert=%d where vehicleid=%d and customerno=%d';
        $deviceQuery = sprintf($Query, $nodataalert, $vehicleid, $this->_Customerno);
        $this->_databaseManager->executeQuery($deviceQuery);
    }

    public function GetDevice_byId($id) {
        $Query = "SELECT devices.deviceid FROM vehicle
               INNER JOIN unit ON vehicle.uid = unit.uid
               INNER JOIN devices ON unit.uid = devices.uid
               WHERE vehicle.customerno=%d AND vehicle.vehicleid = %d";
        $SQL = sprintf($Query, $this->_Customerno, Sanitise::Long($id));
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            return $row['deviceid'];
        }
    }

    public function add_inactive_count($cnt, $date) {
        $Query = "INSERT INTO inactive_device VALUES (null,$cnt, '$date')";
        $this->_databaseManager->executeQuery($Query);
    }

    public function get_last_inactive_count() {
        $Query = "SELECT * FROM inactive_device order by track_id desc limit 1";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            return array('last_time' => $row['entry_time'], 'last_count' => $row['total_inactive']);
        } else {
            return null;
        }
    }

    public function get_odometer_reading($vehicleid, $customerno) {
        $today = date("Y-m-d");
        $Query = "SELECT first_odometer, last_odometer, max_odometer FROM dailyreport where vehicleid = $vehicleid and customerno=$customerno and daily_date='$today'";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            return array(
                'first_odo' => $row['first_odometer'],
                'cur_odo' => $row['last_odometer'],
                'max_odo' => $row['max_odometer']
            );
        } else {
            return null;
        }
    }

    public function pullcontractdetails($customerno) {
        $today = date('Y-m-d');
        $device = new VODevices();
        $Query = "SELECT c.totalsms, c.customerno, c.customername, c.smsleft, c.customercompany,count(unit.unitno) AS cunit FROM " . DB_PARENT . ".customer c
                 LEFT OUTER JOIN unit ON c.customerno=unit.customerno and unit.trans_statusid not in(10,22)
                 Where c.customerno = %d";
        $SQL = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $device->unit = $row['cunit'];
        } else {
            $device->unit = 0;
        }
        $SQL2 = sprintf("SELECT sum(pending_amt) as pending_amount FROM " . DB_PARENT . ".invoice WHERE customerno = %d", $customerno);
        $this->_databaseManager->executeQuery($SQL2);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $device->amount = $row["pending_amount"];
            if ($row["pending_amount"] == "" || $row["pending_amount"] == "0") {
                $device->amount = 0;
            }
        } else {
            $device->amount = 0;
        }
        $SQL3 = sprintf("SELECT devices.deviceid,devices.customerno,devices.uid,count(simcardid) AS sim FROM devices
                     INNER JOIN unit ON unit.uid =devices.uid AND unit.trans_statusid =5
                     WHERE devices.customerno = %d", $customerno);
        $this->_databaseManager->executeQuery($SQL3);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $device->simcard = $row["sim"];
            if ($row["sim"] == "" || $row["sim"] == "0") {
                $device->simcard = 0;
            }
        } else {
            $device->simcard = 0;
        }
        $SQL3 = sprintf("SELECT devices.deviceid,devices.customerno,devices.uid,count(simcardid) AS sim FROM devices
                     INNER JOIN unit ON unit.uid =devices.uid AND unit.trans_statusid =5
                     WHERE devices.customerno = %d", $customerno);
        $this->_databaseManager->executeQuery($SQL3);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $device->simcard = $row["sim"];
            if ($row["sim"] == "" || $row["sim"] == "0") {
                $device->simcard = 0;
            }
        } else {
            $device->simcard = 0;
        }
        $SQL4 = sprintf("SELECT devices.expirydate from devices WHERE devices.customerno = %d and devices.expirydate >= '%s' Order by expirydate ASC limit 1", $customerno, $today);
        $this->_databaseManager->executeQuery($SQL4);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $device->expiry = $row["expirydate"];
            if ($row["expirydate"] == "" || $row["expirydate"] == "0000-00-00") {
                $device->expiry = '';
            }
        } else {
            $device->expiry = '';
        }
        $SQL5 = sprintf("SELECT count(devices.deviceid) as did from devices
            LEFT OUTER JOIN unit ON devices.uid=unit.uid and unit.trans_statusid not in(10,22)
              WHERE devices.customerno = %d and devices.expirydate = '%s' ", $customerno, $device->expiry);
        $this->_databaseManager->executeQuery($SQL5);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $device->expire = $row["did"];
            if ($row["did"] == "" || $row["did"] == "0") {
                $device->expire = 0;
            }
        } else {
            $device->expire = 0;
        }
        $SQL6 = sprintf("SELECT count(deviceid) as didr from devices
            inner join unit ON devices.uid=unit.uid and unit.trans_statusid not in(10,22)
            WHERE devices.customerno = %d and devices.expirydate < '%s' ", $customerno, $device->expiry);
        $this->_databaseManager->executeQuery($SQL6);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $device->expired = $row["didr"];
        } else {
            $device->expired = 0;
        }
        $ServerIST = new DateTime();
        $ServerIST->modify('-60 minutes');
        $active = 0;
        $inactive = 0;
        $SQL7 = sprintf("SELECT lastupdated from devices
            inner join unit ON devices.uid=unit.uid and unit.trans_statusid not in(10,22)
            WHERE devices.customerno = %d", $customerno);
        $this->_databaseManager->executeQuery($SQL7);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device->lastupdated = new DateTime($row["lastupdated"]);
                if ($device->lastupdated < $ServerIST) {
                    $inactive++;
                } else {
                    $active++;
                }
            }
            $device->inactive = $inactive;
            $device->active = $active;
        }
        $inwarranty = 0;
        $SQL8 = sprintf("SELECT warrantyexpiry from devices
            inner join unit ON devices.uid=unit.uid and unit.trans_statusid not in(10,22)
            WHERE devices.customerno = %d", $customerno);
        $this->_databaseManager->executeQuery($SQL8);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device->inwarranty = $row["warrantyexpiry"];
                if ($device->inwarranty > $today) {
                    $inwarranty++;
                }
            }
            $device->warranty = $inwarranty;
        }
        $SQL9 = sprintf("SELECT sum(paid_amt) as paid_amount FROM invoice WHERE customerno = %d", $customerno);
        $this->_databaseManager->executeQuery($SQL9);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $device->paid = $row["paid_amount"];
            if ($row["paid_amount"] == "" || $row["paid_amount"] == "0") {
                $device->paid = 0;
            }
        } else {
            $device->paid = 0;
        }
        $new_install = 0;
        $SQL10 = sprintf("SELECT registeredon from devices
            inner join unit ON devices.uid=unit.uid and unit.trans_statusid not in(10,22)
            WHERE devices.customerno = %d", $customerno);
        $this->_databaseManager->executeQuery($SQL10);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device->registeredon = Date('Y-m-d', strtotime($row["registeredon"]));
                if ($device->registeredon == $today) {
                    $new_install++;
                }
            }
        } else {
            $new_install = 0;
        }
        $device->new_install = $new_install;
        return $device;
    }

    public function pullinvoicedetails($customerno) {
        $devices = array();
        $Query = "SELECT devices.deviceid, vehicle.vehicleno,vehicle.vehicleid, inv.inv_amt,inv.pending_amt,inv.paid_amt,inv.paymentdate,inv.invoiceno,inv.status,`group`.groupname,
                  vehicle.groupid,simcard.simcardno as phone,devices.uid,devices.po_no,devices.po_date, unit.unitno,district.name as dname,city.name as cname FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            LEFT JOIN invoice as inv ON devices.device_invoiceno=inv.invoiceno
            LEFT JOIN `group` ON vehicle.groupid=`group`.groupid
            LEFT JOIN city ON `group`.cityid=city.cityid
            LEFT JOIN district ON city.districtid=district.districtid
            LEFT JOIN state ON district.stateid=state.stateid
            LEFT JOIN nation ON state.nationid=nation.nationid
            LEFT JOIN simcard ON devices.simcardid = simcard.id
            where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22)";
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
            $devicesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $devicesQuery = sprintf($Query, $this->_Customerno);
        }
        $Query .= "ORDER BY vehicle.vehicleno ASC";
        $this->_databaseManager->executeQuery($devicesQuery);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $Datacap = new VODevices();
                $Datacap->invoiceno = $row['invoiceno'];
                $Datacap->invamt = $row['inv_amt'];
                $Datacap->status = $row['status'];
                $Datacap->pamt = $row['pending_amt'];
                $Datacap->paid_amt = $row['paid_amt'];
                if (isset($row['paymentdate']) && $row['paymentdate'] != '0000-00-00') {
// to provide empty date if not inserted
                    $Datacap->paydate = date("d-m-Y", strtotime($row['paymentdate']));
                } else {
                    $Datacap->paydate = '';
                }
                $Datacap->pono = $row["po_no"];
                $Datacap->podate = $row["po_date"];
                $Datacap->city = $row["cname"];
                $Datacap->district = $row["dname"];
                $Datacap->unitno = $row['unitno'];
                $Datacap->phone = $row['phone'];
                $Datacap->vehicleno = $row['vehicleno'];
                $Datacap->grpname = $row['groupname'];
                $Datacap->groupid = $row['groupid'];
                $Datacap->paidamt = $row['paid_amt'];
                $devices[] = $Datacap;
            }
            return $devices;
        }
    }

    public function pullfiltered_invoicedetails($nation_id, $state_id, $district_id, $city_id, $group_id) {
        $devices = array();
        $Query = "SELECT devices.deviceid, vehicle.vehicleno,vehicle.vehicleid, inv.inv_amt,inv.pending_amt,inv.paid_amt,inv.paymentdate,inv.invoiceno,inv.status,`group`.groupname,
                  vehicle.groupid,simcard.simcardno as phone,devices.uid,devices.po_no,devices.po_date, unit.unitno,district.name as dname,city.name as cname FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            LEFT JOIN invoice as inv ON devices.device_invoiceno=inv.invoiceno
            LEFT JOIN `group` ON vehicle.groupid=`group`.groupid
            LEFT JOIN city ON `group`.cityid=city.cityid
            LEFT JOIN district ON city.districtid=district.districtid
            LEFT JOIN state ON district.stateid=state.stateid
            LEFT JOIN nation ON state.nationid=nation.nationid
            LEFT JOIN simcard ON devices.simcardid = simcard.id
            where devices.customerno=%d AND unit.trans_statusid NOT IN (10,22)";
        if ($group_id != '') {
            $Query .= " AND vehicle.groupid=$group_id ";
            $devicesQuery = sprintf($Query, $this->_Customerno);
        }
        if ($city_id != '') {
            $Query .= " AND city.cityid=$city_id ";
            $devicesQuery = sprintf($Query, $this->_Customerno);
        }
        if ($district_id != '') {
            $Query .= " AND district.districtid=$district_id ";
            $devicesQuery = sprintf($Query, $this->_Customerno);
        }
        if ($state_id != '') {
            $Query .= " AND state.stateid=$state_id ";
            $devicesQuery = sprintf($Query, $this->_Customerno);
        }
        if ($nation_id != '') {
            $Query .= " AND nation.nationid=$nation_id ";
            $devicesQuery = sprintf($Query, $this->_Customerno);
        } else {
            $devicesQuery = sprintf($Query, $this->_Customerno);
        }
        $Query .= "ORDER BY vehicle.vehicleno ASC";
        $this->_databaseManager->executeQuery($devicesQuery);
        $this->_databaseManager->executeQuery($devicesQuery);
        //echo $devicesQuery;
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $Datacap = new VODevices();
                $Datacap->invoiceno = $row['invoiceno'];
                $Datacap->invamt = $row['inv_amt'];
                $Datacap->status = $row['status'];
                $Datacap->pamt = $row['pending_amt'];
                $Datacap->paid_amt = $row['paid_amt'];
                if (isset($row['paymentdate']) && $row['paymentdate'] != '0000-00-00') {
// to provide empty date if not inserted
                    $Datacap->paydate = date("d-m-Y", strtotime($row['paymentdate']));
                } else {
                    $Datacap->paydate = '';
                }
                $Datacap->pono = $row["po_no"];
                $Datacap->podate = $row["po_date"];
                $Datacap->city = $row["cname"];
                $Datacap->district = $row["dname"];
                $Datacap->unitno = $row['unitno'];
                $Datacap->phone = $row['phone'];
                $Datacap->vehicleno = $row['vehicleno'];
                $Datacap->grpname = $row['groupname'];
                $Datacap->groupid = $row['groupid'];
                $Datacap->paidamt = $row['paid_amt'];
                $devices[] = $Datacap;
            }
            return $devices;
        }
    }

    public function pullservicedetails($vehicleid, $customerno) {
        $devices = array();
        $SQL = sprintf("SELECT th.allot_teamid, team_allot_teamid.name as allot_name, th.customerno, t.status, th.transaction, team_teamid.name, th.trans_time, th.thid, th.simcardno, th.invoiceno, th.expirydate, unit.type_value FROM " . DB_PARENT . ".trans_history th LEFT OUTER JOIN " . DB_PARENT . ".trans_status t ON t.id = th.statusid INNER JOIN unit ON unit.uid = th.unitid INNER JOIN " . DB_PARENT . ".team team_teamid ON team_teamid.teamid = th.teamid LEFT OUTER JOIN " . DB_PARENT . ".team team_allot_teamid ON team_allot_teamid.teamid = th.allot_teamid WHERE th.type=0 AND th.vehicleid = %d and th.statusid = 5 ORDER BY trans_time DESC", $vehicleid);
        //$SQL = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $Datacap = new VODevices();
                //$x++;
                if ($row["allot_teamid"] == 0) {
                    $Datacap->status = $row["status"];
                } else {
                    $Datacap->status = $row["status"] . ": " . $row["allot_name"];
                }
                $Datacap->customerno = $row["customerno"];
                $Datacap->transaction = $row["transaction"];
                $Datacap->name = $row["name"];
                $Datacap->trans_time = date("d-m-Y H:i", strtotime($row["trans_time"]));
                $Datacap->thid = $row["thid"];
                $Datacap->simcardno = $row["simcardno"];
                $Datacap->invoiceno = $row["invoiceno"];
                if ($row["expirydate"] == "0000-00-00") {
                    $Datacap->expirydate = "";
                } else {
                    $Datacap->expirydate = date("d-m-Y", strtotime($row["expirydate"]));
                }
                $devices[] = $Datacap;
            }
            return $devices;
        }
    }

    public function pullids($customerno) {
        $devices = array();
        $Query = "Select * from unit where customerno=%d";
        $SQL = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $Datacap = new VODevices();
                $Datacap->uid = $row['uid'];
                $Datacap->vid = $row['vehicleid'];
                $devices[] = $Datacap;
            }
            return $devices;
        }
    }

    public function setids($ids, $customerno) {
        $devices = array();
        $Query = "Update " . DB_PARENT . ".trans_history SET vehicleid=%d where unitid=%d ";
        foreach ($ids as $id) {
            $SQL = sprintf($Query, $id->vid, $id->uid);
            echo "<br/>";
            $this->_databaseManager->executeQuery($SQL);
        }
    }

    public function getEmailList($term) {
        $term = "%" . $term . "%";
        $Query = "SELECT eid,email_id FROM  " . DB_ELIXIATECH . ".report_email_list WHERE isdeleted=0 and customerno IN (%d,0) and email_id LIKE '%s'";
        $sqlQuery = sprintf($Query, $this->_Customerno, $term);
        $this->_databaseManager->executeQuery($sqlQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $data = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $json["value"] = $row["email_id"];
                $json["eid"] = $row["eid"];
                array_push($data, $json);
            }
            return json_encode($data);
        }
    }

    public function getEmailListforTech($term) {
        $term = "%" . $term . "%";
        $Query = "SELECT eid,email_id FROM  " . DB_ELIXIATECH . ".report_email_list WHERE isdeleted=0 and customerno IN (%d,0) and email_id LIKE '%s'";
        $sqlQuery = sprintf($Query, $this->_Customerno, $term);
        $this->_databaseManager->executeQuery($sqlQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $data = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $json["value"] = $row["email_id"];
                $json["eid"] = $row["eid"];
                array_push($data, $json);
            }
            return json_encode($data);
        }
    }

    public function getEmailsForTeam($term, $customerno) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'0','" . $term . "','" . $customerno . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_EMAILS . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        if (!empty($arrResult)) {
            $data = array();
            foreach ($arrResult as &$row) {
                $json["value"] = $row["email_id"];
                $json["eid"] = $row["eid"];
                array_push($data, $json);
            }
        }
        return json_encode($data);
    }

    public function insertEmailIdForTeam($email, $customerno) {
        $today = date('Y-m-d H:i:s');
        $userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : '0';
        $QUERY = "SELECT eid FROM " . DB_ELIXIATECH . ".report_email_list WHERE email_id LIKE '%" . $email . "%' AND customerno = " . $customerno;
        $this->_databaseManager->executeQuery($QUERY);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            return $row['eid'];
        }
        $Query = "INSERT INTO " . DB_ELIXIATECH . ".report_email_list(customerno,email_id,created_on,created_by) VALUES('%d','%s','%s','%d')";
        $sqlQuery = sprintf($Query, $customerno, $email, $today, $userid);
        $this->_databaseManager->executeQuery($sqlQuery);
        $ret = $this->_databaseManager->get_insertedId();
        return $ret;
    }

    public function insertEmailId($email, $customerno) {
        $today = date('Y-m-d H:i:s');
        $userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : '0';
        $QUERY = "SELECT eid FROM  " . DB_PARENT . ".report_email_list WHERE email_id LIKE '%" . $email . "%'";
        $this->_databaseManager->executeQuery($QUERY);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            return $row['eid'];
        }
        $Query = "INSERT INTO  " . DB_PARENT . ".report_email_list(customerno,email_id,created_on,created_by) VALUES('%d','%s','%s','%d')";
        $sqlQuery = sprintf($Query, $customerno, $email, $today, $userid);
        $this->_databaseManager->executeQuery($sqlQuery);
        $ret = $this->_databaseManager->get_insertedId();
        return $ret;
    }

    public function insertEmailIdforTech($email, $customerno) {
        $today = date('Y-m-d H:i:s');
        $userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : '0';
        $QUERY = "SELECT eid FROM  " . DB_ELIXIATECH . ".report_email_list WHERE email_id LIKE '%" . $email . "%'";
        $this->_databaseManager->executeQuery($QUERY);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            return $row['eid'];
        }
        $Query = "INSERT INTO  " . DB_ELIXIATECH . ".report_email_list(customerno,email_id,created_on,created_by) VALUES('%d','%s','%s','%d')";
        $sqlQuery = sprintf($Query, $customerno, $email, $today, $userid);
        $this->_databaseManager->executeQuery($sqlQuery);
        $ret = $this->_databaseManager->get_insertedId();
        return $ret;
    }

    public function devicesForRealtimeDataReport($customer) {
        $devices = array();
        $list = "vehicle.vehicleno
            ,vehicle.kind
            ,vehicle.vehicleid
            ,vehicle.groupid
            ,vehicle.overspeed_limit
            ,vehicle.stoppage_flag
            ,vehicle.stoppage_transit_time
            ,vehicle.curspeed
            ,vehicle.temp1_mute
            ,vehicle.temp2_mute
            ,vehicle.temp3_mute
            ,vehicle.temp4_mute
            ,vehicle.temp1_min
            ,vehicle.temp2_min
            ,vehicle.temp3_min
            ,vehicle.temp4_min
            ,vehicle.temp1_max
            ,vehicle.temp2_max
            ,vehicle.temp3_max
            ,vehicle.temp4_max
            ,vehicle.description
            ,driver.drivername
            ,driver.driverphone
            ,driver.driverid
            ,devices.deviceid
            ,devices.devicelat
            ,devices.devicelong
            ,devices.lastupdated as dlastupdated
            ,devices.ignition
            ,devices.status
            ,devices.directionchange
            ,devices.tamper
            ,devices.powercut
            ,unit.uid
            ,unit.unitno
            ,unit.msgkey
            ,unit.acsensor
            ,unit.is_door_opp
            ,unit.digitalio
            ,unit.digitalioupdated
            ,unit.door_digitalioupdated
            ,unit.is_ac_opp
            ,unit.analog1
            ,unit.analog2
            ,unit.analog3
            ,unit.analog4
            ,unit.get_conversion
            ,unit.tempsen1
            ,unit.tempsen2
            ,unit.tempsen3
            ,unit.tempsen4
            ,unit.n1
            ,unit.n2
            ,unit.n3
            ,unit.n4
            ,unit.is_buzzer
            ,unit.is_mobiliser
            ,unit.is_freeze
            ,unit.humidity
            ,customer.use_geolocation
            ,customer.use_humidity
            ,customer.temp_sensors
            ,unit.extra_digitalioupdated
            ,unit.extra2_digitalioupdated
            ,unit.extra_digital
            ,unit.setcom
            , g1.gensetno as genset1
            , g2.gensetno as genset2
            , t1.transmitterno as transmitter1
            , t2.transmitterno as transmitter2";
        if ($this->VehicleUsermappingExists($_SESSION['userid']) != true) {
            $Query = "SELECT $list FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = devices.customerno
                INNER JOIN driver ON vehicle.driverid = driver.driverid
                LEFT JOIN vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child = $this->_Customerno
                LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid
                LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid
                LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid
                LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid
                where (devices.customerno=%d || devices.customerno = vr.parent)  AND unit.trans_statusid NOT IN (10,22)";
            if ($customer->userGroups[0] != 0) {
                $group_in = implode(',', $customer->userGroups);
                $Query .= " AND vehicle.groupid IN (%s)";
            }
            $Query .= " ORDER BY devices.lastupdated ASC";
            if ($customer->userGroups[0] != 0) {
                $vehiclesQuery = sprintf($Query, $this->_Customerno, $group_in);
            } else {
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            }
        } else {
            $Query = "SELECT $list FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = devices.customerno
                INNER JOIN driver ON vehicle.driverid = driver.driverid
                LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid
                LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid
                LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid
                LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid
                LEFT JOIN vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child = $this->_Customerno";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid = %d";
            } else {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . "";
            }
            $Query .= " where devices.customerno=%d and vehmap.isdeleted = 0  AND unit.trans_statusid NOT IN (10,22)";
            if ($customer->userGroups[0] != 0) {
                $group_in = implode(',', $customer->userGroups);
                $Query .= " AND vehicle.groupid IN (%s)";
            }
            $Query .= " ORDER BY devices.lastupdated ASC";
            if ($customer->userGroups[0] != 0) {
                $vehiclesQuery = sprintf($Query, $this->_Customerno, $group_in);
            } else {
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            }
        }
        //echo $vehiclesQuery;die();
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['uid'] > 0) {
                    //if($row['devicelat']>0 & $row['devicelong']>0)
                    //{
                    $device->vehicleno = $row['vehicleno'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->groupid = $row['groupid'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->driverid = $row['driverid'];
                    if ($row['dlastupdated'] != '0000-00-00 00:00:00') {
                        $device->lastupdated_store = $row['dlastupdated'];
                        $device->lastupdated = $row['dlastupdated'];
                    } else {
                        $device->lastupdated_store = $row['dlastupdated'];
                        $device->lastupdated = $row['dlastupdated'];
                    }
                    $device->stoppage_flag = $row['stoppage_flag'];
                    $device->stoppage_transit_time = $row['stoppage_transit_time'];
                    $device->ignition = $row['ignition'];
                    $device->status = $row['status'];
                    $device->curspeed = $row["curspeed"];
                    $device->uid = $row["uid"];
                    $device->unitno = $row["unitno"];
                    $device->powercut = $row["powercut"];
                    $device->acsensor = $row["acsensor"];
                    $device->is_door_opp = $row['is_door_opp'];
                    $device->digitalio = $row["digitalio"];
                    $device->digitalioupdated = $row["digitalioupdated"];
                    $device->door_digitalioupdated = $row["door_digitalioupdated"];
                    $device->isacopp = $row["is_ac_opp"];
                    $device->analog1 = $row["analog1"];
                    $device->analog2 = $row["analog2"];
                    $device->analog3 = $row["analog3"];
                    $device->analog4 = $row["analog4"];
                    $device->get_conversion = $row["get_conversion"];
                    $device->temp1_mute = $row['temp1_mute'];
                    $device->temp2_mute = $row['temp2_mute'];
                    $device->temp3_mute = $row['temp3_mute'];
                    $device->temp4_mute = $row['temp4_mute'];
                    $device->temp1_min = $row['temp1_min'];
                    $device->temp1_max = $row['temp1_max'];
                    $device->temp2_min = $row['temp2_min'];
                    $device->temp2_max = $row['temp2_max'];
                    $device->temp3_min = $row['temp3_min'];
                    $device->temp3_max = $row['temp3_max'];
                    $device->temp4_min = $row['temp4_min'];
                    $device->temp4_max = $row['temp4_max'];
                    $device->tempsen1 = $row["tempsen1"];
                    $device->tempsen2 = $row["tempsen2"];
                    $device->tempsen3 = $row["tempsen3"];
                    $device->tempsen4 = $row["tempsen4"];
                    $device->n1 = $row["n1"];
                    $device->n2 = $row["n2"];
                    $device->n3 = $row["n3"];
                    $device->n4 = $row["n4"];
                    $device->is_buzzer = $row["is_buzzer"];
                    $device->is_mobiliser = $row["is_mobiliser"];
                    $device->is_freeze = $row["is_freeze"];
                    $device->humidity = $row["humidity"];
                    $device->use_humidity = $row["use_humidity"];
                    $device->kind = $row["kind"];
                    $device->extra_digitalioupdated = $row["extra_digitalioupdated"];
                    $device->extra2_digitalioupdated = $row["extra2_digitalioupdated"];
                    $device->extra_digital = $row["extra_digital"];
                    $device->genset1 = $row["genset1"];
                    $device->genset2 = $row["genset2"];
                    $device->transmitter1 = $row["transmitter1"];
                    $device->transmitter2 = $row["transmitter2"];
                    $device->setcom = $row["setcom"];
                    $devices[] = $device;
                    //}
                }
            }
        }
        return $devices;
    }

    public function insertRealtimeData($vehicle, $customerno, $userid, $todaysdate) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        //Prepare parameters
        $sp_params = "'" . $vehicle->vehicleid . "'"
        . ",'" . $vehicle->uid . "'"
        . ",'" . $vehicle->groupid . "'"
        . ",'" . $vehicle->driverid . "'"
        . ",'" . $vehicle->lastupdated . "'"
        . ",'" . $vehicle->status . "'"
        . ",'" . $vehicle->location . "'"
        . ",'" . $vehicle->speed . "'"
        . ",'" . $vehicle->distance . "'"
        . ",'" . $vehicle->power . "'"
        . ",'" . $vehicle->ac_status . "'"
        . ",'" . $vehicle->door_status . "'"
        . ",'" . $vehicle->temperature1 . "'"
        . ",'" . $vehicle->temperature2 . "'"
        . ",'" . $vehicle->temperature3 . "'"
        . ",'" . $vehicle->temperature4 . "'"
        . ",'" . $vehicle->genset1 . "'"
        . ",'" . $vehicle->genset2 . "'"
        . ",'" . $vehicle->humidity . "'"
        . ",'" . $vehicle->is_buzzer . "'"
        . ",'" . $vehicle->is_mobiliser . "'"
        . ",'" . $vehicle->is_freeze . "'"
            . ",'" . $customerno . "'"
            . ",'" . $userid . "'"
            . ",'" . $todaysdate . "'";
        $queryCallSP = "CALL " . speedConstants::SP_INSERTREALTIMEDATA . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP);
        $this->_databaseManager->ClosePDOConn($pdo);
    }

    public function getRealtimeData($customerno, $userid, $todaysdate) {
        $arrResult = null;
        $pdo = $this->_databaseManager->CreatePDOConn();
        //Prepare parameters
        $sp_params = "'" . $customerno . "'"
            . ",'" . $userid . "'"
            . ",'" . $todaysdate . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_REALTIMEDATA . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        return $arrResult;
    }

    public function addStoppageReason($objReasonDetails) {
        $reasonid = 0;
        $today = date('Y-m-d H:i:s');
        $Query = "INSERT INTO vehicleStoppageReason (vehicleid, starttime, endtime, lat, lng, reasonid, customerno, created_by, created_on) VALUES (%d,'%s','%s','%s','%s','%s',%d,%d,'%s')";
        $deviceQuery = sprintf($Query, $objReasonDetails->vehicleid, $objReasonDetails->starttime, $objReasonDetails->endtime, $objReasonDetails->lat, $objReasonDetails->lng, $objReasonDetails->reason, $objReasonDetails->customerno, $objReasonDetails->userid, $today);
        $this->_databaseManager->executeQuery($deviceQuery);
        $reasonid = $this->_databaseManager->get_insertedId();
        return $reasonid;
    }

    public function updateStoppageReason($objReasonDetails) {
        $noOfRowsAffected = 0;
        $today = date('Y-m-d H:i:s');
        $Query = "UPDATE vehicleStoppageReason SET reasonid=%d, updated_by=%d, updated_on='%s'  WHERE sid=%d AND customerno=%d AND isdeleted = 0";
        $deviceQuery = sprintf($Query, $objReasonDetails->reason, $objReasonDetails->userid, $today, $objReasonDetails->sid, $objReasonDetails->customerno);
        $this->_databaseManager->executeQuery($deviceQuery);
        $noOfRowsAffected = $this->_databaseManager->get_affectedRows();
        return $noOfRowsAffected;
    }

    public function getStoppageReason($objReason) {
        $arrReason = null;
        $Query = "SELECT sr.reason, vsr.sid, sr.reasonid FROM vehicleStoppageReason vsr
        INNER JOIN stoppageReason sr on sr.reasonid = vsr.reasonid
        WHERE vehicleid=%d AND starttime = '%s' AND endtime ='%s'  AND lat = '%f' AND lng = '%f' AND vsr.customerno=%d  AND vsr.isdeleted = 0 ORDER BY sid DESC LIMIT 1";
        $deviceQuery = sprintf($Query, $objReason->vehicleid, $objReason->starttime, $objReason->endtime, $objReason->lat, $objReason->lng, $objReason->customerno);
        $this->_databaseManager->executeQuery($deviceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $reason = new stdClass();
                $reason->reason = $row['reason'];
                $reason->sid = $row['sid'];
                $reason->reasonid = $row['reasonid'];
                $arrReason[] = $reason;
            }
        }
        return $arrReason;
    }

    public function getStoppageReasonMaster($objReason) {
        $arrReason = array();
        //$objReason->lat = "%".$objReason->lat."%";
        //$objReason->lng = "%".$objReason->lng."%";
        /*$Query = "SELECT sr.reasonid, sr.reason FROM stoppageReason sr
        WHERE sr.customerno IN (0,$objReason->customerno)  AND sr.isdeleted = 0 ORDER BY reasonid ASC";
         */
        if ($objReason->customerno == 132) {
            $Query = "SELECT sr.reasonid, sr.reason FROM stoppageReason sr
                      WHERE sr.customerno IN ($objReason->customerno)  AND sr.isdeleted = 0  ORDER BY reasonid ASC";
        } else {
            $Query = "SELECT sr.reasonid, sr.reason FROM stoppageReason sr
                      WHERE sr.customerno IN (0,$objReason->customerno)  AND sr.isdeleted = 0 ORDER BY reasonid ASC";
        }
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $objList = new stdClass();
                $objList->reasonid = $row['reasonid'];
                $objList->reason = $row['reason'];
                $arrReason[] = $objList;
            }
        }
        return $arrReason;
    }

    public function getInvoiceBillDetails($objBill) {
        $devices = array();
        $Query = "SELECT inv.invoiceno,inv.inv_amt, inv.inv_date, inv.paymentdate
                FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN invoice as inv ON devices.device_invoiceno=inv.invoiceno
                WHERE devices.customerno=%d
                AND unit.customerno=%d
                AND vehicle.customerno=%d
                AND inv.customerno=%d
                AND devices.device_invoiceno = '%s'
                AND inv.invoiceno = '%s'
                AND unit.trans_statusid NOT IN (10,22)
                LIMIT 1";
        $devicesQuery = sprintf($Query, $objBill->customerNo, $objBill->customerNo, $objBill->customerNo, $objBill->customerNo, $objBill->deviceInvoiceNo, $objBill->deviceInvoiceNo);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $Datacap = new VODevices();
                $Datacap->invoiceNo = $row['invoiceno'];
                $Datacap->invoiceAmount = $row['inv_amt'];
                $Datacap->invoiceDate = $row['inv_date'];
                $Datacap->paymentDate = '';
                if (isset($row['paymentdate']) && $row['paymentdate'] != '0000-00-00') {
                    $Datacap->paymentDate = date("Y-m-d", strtotime($row['paymentdate']));
                }
                $devices[] = $Datacap;
            }
        }
        return $devices;
    }

    public function getDeviceTransHistory($objDevice) {
        $history = array();
        $SQL = "SELECT thid, unitid, trans_time, transaction, statusid, comments
                FROM trans_history
                WHERE unitid=%d AND customerno=%d and statusid=%d ORDER BY thid ASC";
        $Query = sprintf($SQL, $objDevice->unitid, $objDevice->customerno, $objDevice->statusid);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $objHistory = new stdClass();
                $objHistory->thid = $row['thid'];
                $objHistory->unitid = $row['unitid'];
                $objHistory->trans_time = $row['trans_time'];
                $objHistory->transaction = $row['transaction'];
                $objHistory->statusid = $row['statusid'];
                $objHistory->comments = $row['comments'];
                $history[] = $objHistory;
            }
        }
        return $history;
    }

    public function getDetailsForExpeditors($vehicleids) {
        $devices = Array();
        $list = "vehicle.vehicleno
            ,devices.devicelat
            ,devices.devicelong
            ,devices.lastupdated as dlastupdated
            ,unit.uid
            ,unit.unitno
            ,tripdetails.triplogno
            ,tripdetails.is_tripend
            ,tripdetails.statusdate
            ,tripdetails.entrytime
            ,etd.expId
            ,etd.shipmentId
            ,etd.referenceNumber
            ,etd.shipmentInitiated
            ,etd.shipmentCompleted
            ,vehicle.customerno
            ";
        $Query = "SELECT $list FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid
            INNER JOIN tripdetails ON tripdetails.vehicleid = vehicle.vehicleid
            INNER JOIN expeditorsTripDetails etd ON etd.tripid = tripdetails.tripid
            WHERE vehicle.kind <> 'Warehouse'
            AND vehicle.customerno =%d
            AND vehicle.vehicleid IN (%s)
            AND unit.trans_statusid NOT IN (10,22)
            AND tripdetails.isdeleted = 0
            AND etd.shipmentCompleted = 0
            ORDER BY devices.lastupdated ASC";
        $devicesQuery = sprintf($Query, $this->_Customerno, $vehicleids);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new stdClass();
                if ($row['uid'] > 0) {
                    $device->vehicleno = $row['vehicleno'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->dlastupdated = $row['dlastupdated'];
                    $device->uid = $row["uid"];
                    $device->unitno = $row["unitno"];
                    $device->triplogno = $row["triplogno"];
                    $device->is_tripend = $row["is_tripend"];
                    $device->statusdate = $row["statusdate"];
                    $device->entrytime = $row["entrytime"];
                    $device->expId = $row["expId"];
                    $device->shipmentId = $row["shipmentId"];
                    $device->referenceNumber = $row["referenceNumber"];
                    $device->shipmentInitiated = $row["shipmentInitiated"];
                    $device->shipmentCompleted = $row["shipmentCompleted"];
                    $device->customerNo = $row["customerno"];
                    $devices[] = $device;
                }
            }
        }
        return $devices;
    }

    public function VehicleUsermappingExists($userid) {
        $status = false;
        $Query = 'SELECT vu.userid FROM vehicleusermapping as vu WHERE vu.userid=%d AND vu.customerno=%d AND vu.isdeleted=0';
        $vehiclesQuery = sprintf($Query, $userid, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $status = true;
        }
        return $status;
    }

    public function deviceDetailsForHeatMap() {
        $devices = array();
        $Query = "SELECT devices.devicelat,devices.devicelong
            FROM devices
            WHERE devices.devicelat <> '0.000000' AND devices.devicelong <> '0.000000'  ";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new stdClass();
                $device->devicelat = $row['devicelat'];
                $device->devicelong = $row['devicelong'];
                $devices[] = $device;
            }
            return $devices;
        }
    }

    public function getUserList($term) {
        $term = "%" . $term . "%";
        $Query = "SELECT userid as uid,realname FROM user
                   WHERE isdeleted=0 and customerno IN (%d,0) and
                   realname LIKE '%s' AND username != '%s'";
        $sqlQuery = sprintf($Query, $this->_Customerno, $term, $_SESSION['username']);
        $this->_databaseManager->executeQuery($sqlQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $data = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $json["value"] = $row["realname"];
                $json["uid"] = $row["uid"];
                array_push($data, $json);
            }
            return json_encode($data);
        }
    }

    public function getDeviceNumber($vehicleNo) {
        $vehicleNo = "%" . $vehicleNo . "%";
        $Query = "SELECT deviceid FROM devices
                    INNER JOIN unit ON devices.uid = unit.uid
                    INNER JOIN vehicle ON devices.uid = vehicle.uid WHERE vehicle.vehicleNo LIKE '%s' AND devices.customerno = %d ";
        $deviceid = '';
        $sqlQuery = sprintf($Query, $vehicleNo, $this->_Customerno);
        $this->_databaseManager->executeQuery($sqlQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $data = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $deviceid = $row["deviceid"];
            }
        }
        return $deviceid;
    }

    /* public function getStoppageReasonPerLoc($objReason) {
    $arrReason = null;
    $Query = "SELECT sr.reason, vsr.sid, sr.reasonid FROM vehicleStoppageReason vsr
    INNER JOIN stoppageReason sr on sr.reasonid = vsr.reasonid
    WHERE vehicleid=%d AND starttime = '%s' AND vsr.customerno=%d  AND vsr.isdeleted = 0 ORDER BY sid DESC LIMIT 1";
    $deviceQuery = sprintf($Query, $objReason->vehicleid, $objReason->starttime, $objReason->customerno);
    $this->_databaseManager->executeQuery($deviceQuery);
    if ($this->_databaseManager->get_rowCount() > 0) {
    while ($row = $this->_databaseManager->get_nextRow()) {
    $reason = new stdClass();
    $reason->reason = $row['reason'];
    $reason->sid = $row['sid'];
    $reason->reasonid = $row['reasonid'];
    $arrReason[] = $reason;
    }
    }
    return $arrReason;
     */
    public function getStoppageReasonPerLoc($objReason) {
        $arrReason = null;
        $Query = "SELECT sr.reason, vsr.sid, sr.reasonid , vsr.remarks FROM vehicleStoppageReason vsr
        INNER JOIN stoppageReason sr on sr.reasonid = vsr.reasonid
        WHERE vehicleid=%d AND starttime = '%s' AND vsr.customerno=%d  AND vsr.isdeleted = 0 ORDER BY sid DESC LIMIT 1";
        $deviceQuery = sprintf($Query, $objReason->vehicleid, $objReason->starttime, $objReason->customerno);
        $this->_databaseManager->executeQuery($deviceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $reason = new stdClass();
                $reason->reason = $row['reason'];
                $reason->sid = $row['sid'];
                $reason->reasonid = $row['reasonid'];
                $reason->remarks = $row['remarks'];
                $arrReason[] = $reason;
            }
        }
        return $arrReason;
    }

    public function getApiToken($obj) {
        //print_r($obj);
        $arrToken = array();
        $Query = "SELECT logId, authToken, clientSecretKey, validityDate, customerno
        FROM apiTokenLog
        WHERE customerno=%d AND DATE(validityDate) >= DATE('%s') AND isdeleted = 0 ORDER BY logId DESC LIMIT 1";
        $tokenQuery = sprintf($Query, $obj->customerno, $obj->todaysdate);
        $this->_databaseManager->executeQuery($tokenQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $token = new stdClass();
                $token->logId = $row['logId'];
                $token->authToken = $row['authToken'];
                $token->clientSecretKey = $row['clientSecretKey'];
                $token->validityDate = $row['validityDate'];
                $token->customerno = $row['customerno'];
                $arrToken[] = $token;
            }
        }
        //print_r($arrToken); die();
        return $arrToken;
    }

    public function insertApiToken($obj) {
        $logId = 0;
        $today = date('Y-m-d H:i:s');
        $Query = "INSERT INTO apiTokenLog (authToken, clientSecretKey, validityDate, customerno, created_on)
        VALUES ('%s','%s','%s',%d,'%s')";
        $tokenQuery = sprintf($Query, $obj->authToken, $obj->clientSecretKey, $obj->validityDate, $obj->customerno, $obj->todaysdate);
        $this->_databaseManager->executeQuery($tokenQuery);
        $logId = $this->_databaseManager->get_insertedId();
        return $logId;
    }

    public function getDeviceDetails($objDeviceRequest) {
        $consigneeId = null;
        $arrDeviceDetails = array();
        $sp_params = "'" . $objDeviceRequest->pageindex . "'"
        . ",'" . $objDeviceRequest->pagesize . "'"
        . "," . $objDeviceRequest->customerno . ""
        . "," . $objDeviceRequest->iswarehouse . ""
        . ",'" . $objDeviceRequest->searchstring . "'"
        . ",'" . $objDeviceRequest->groupid . "'"
        . ",'" . $objDeviceRequest->userkey . "'"
        . ",'" . $objDeviceRequest->isRequiredThirdParty . "'"
            . ",'" . $consigneeId . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_VEHICLEWAREHOUSE_DETAILS . "($sp_params)";
        $this->_databaseManager->executeQuery($queryCallSP);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $arrDeviceDetails[] = $row;
            }
        }
        return $arrDeviceDetails;
    }

    public function updateExpeditorTripDetails($objRequest) {
        $noOfRowsAffected = 0;
        if (isset($objRequest->shipmentCompleted) && $objRequest->shipmentCompleted == 1) {
            $sql = "UPDATE expeditorsTripDetails SET shipmentCompleted = %d , updatedOn= '%s' WHERE expId = %d AND referenceNumber  = '%s' AND customerNo = %d AND isDeleted = 0 ";
            $sqlQuery = sprintf($sql, $objRequest->shipmentCompleted, $objRequest->datetime, $objRequest->expId, $objRequest->referenceNumber, $objRequest->customerNo);
            $this->_databaseManager->executeQuery($sqlQuery);
            $noOfRowsAffected = $this->_databaseManager->get_affectedRows();
        } elseif (isset($objRequest->shipmentInitiated) && $objRequest->shipmentInitiated == 1) {
            $sql = "UPDATE expeditorsTripDetails SET shipmentInitiated = %d , updatedOn= '%s' WHERE expId = %d AND referenceNumber  = '%s' AND customerNo = %d AND isDeleted = 0 ";
            $sqlQuery = sprintf($sql, $objRequest->shipmentInitiated, $objRequest->datetime, $objRequest->expId, $objRequest->referenceNumber, $objRequest->customerNo);
            $this->_databaseManager->executeQuery($sqlQuery);
            $noOfRowsAffected = $this->_databaseManager->get_affectedRows();
        } elseif (isset($objRequest->shipmentId) && $objRequest->shipmentId != '') {
            $sql = "UPDATE expeditorsTripDetails SET shipmentId = '%s' , updatedOn= '%s' WHERE expId = %d AND referenceNumber  = '%s' AND customerNo = %d AND isDeleted = 0 ";
            $sqlQuery = sprintf($sql, $objRequest->shipmentId, $objRequest->datetime, $objRequest->expId, $objRequest->referenceNumber, $objRequest->customerNo);
            $this->_databaseManager->executeQuery($sqlQuery);
            $noOfRowsAffected = $this->_databaseManager->get_affectedRows();
        }
        return $noOfRowsAffected;
    }

    public function getNightDriveDetails($reportTime = '', $type = '') {
        if ($reportTime != '' && $type != '') {
            $query = "SELECT start_time,end_time,threshold_distance,customerno,createdOn FROM night_drive_details WHERE " . $type . "='%s' ";
            $sqlQuery = sprintf($query, $reportTime);
        } else {
            $query = "SELECT start_time,end_time,threshold_distance,customerno,createdOn FROM night_drive_details";
            $sqlQuery = sprintf($query);
        }
//echo $sqlQuery;
        $this->_databaseManager->executeQuery($sqlQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $details = new stdClass();
                $details->start_time = $row['start_time'];
                $details->end_time = $row['end_time'];
                $details->threshold_distance = $row['threshold_distance'];
                $details->customerno = $row['customerno'];
                $details->createdon = $row['createdOn'];
                $arrDetails[] = $details;
            }
            return $arrDetails;
        } else {
            return null;
        }
    }

    public function getNightDriveDetailsForReport($customer) {
        $query = "SELECT * FROM night_drive_details WHERE customerno ='%d' ";
        $sqlQuery = sprintf($query, $customer);
        $this->_databaseManager->executeQuery($sqlQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $details = array();
                $details['start_time'] = $row['start_time'];
                $details['end_time'] = $row['end_time'];
                $details['threshold_distance '] = $row['threshold_distance'];
                $details['customerno'] = $row['customerno'];
                $details['createdon'] = $row['createdOn'];
            }
            return $details;
        } else {
            return null;
        }
    }

    public function getWarehouseClubMapping($vehicleId, $customerNo) {
        $arrClubData = array();
        $query = "SELECT parentId,childId,customerNo FROM warehouseClubMappinig WHERE parentId=%d AND  customerno ='%d' AND isdeleted=0";
        $sqlQuery = sprintf($query, $vehicleId, $customerNo);
        $this->_databaseManager->executeQuery($sqlQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $details = array();
                $details['parentId'] = $row['parentId'];
                $details['childId'] = $row['childId'];
                $details['customerNo'] = $row['customerNo'];
                $arrClubData[] = $details;
            }
        }
        return $arrClubData;
    }

    public function getNightDriveEndDetails($deviceObject) {
        $nightlastodometer_query = " SELECT last_odometer,first_odometer,end_lat,end_long,last_online_updated
                                      FROM dailyreport
                                      WHERE uid = %d AND vehicleid = %d
                                      AND daily_date = %s AND customerno = %d
                                      ORDER BY last_online_updated DESC LIMIT 1";
        $devicesQuery = sprintf($nightlastodometer_query, $deviceObject->uid, $deviceObject->vehicleid, $deviceObject->currentdate, $deviceObject->customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $result = $db->query($nightlastodometer_query);
            if ($result !== false) {
                $arrQueryResult = $result->fetchAll();
                $output['last_odometer'] = $arrQueryResult[0]['last_odometer'];
                $output['first_odometer'] = $arrQueryResult[0]['first_odometer'];
                $output['maxodometer'] = $arrQueryResult[0]['maxodometer'];
                $output['topspeed_time'] = $arrQueryResult[0]['last_online_updated'];
                if (($last_odometer - $first_odometer) < 0) {
                    $output['night_lastmaxodometer'] = $maxodometer + $last_odometer;
                } else {
                    $output['night_lastmaxodometer'] = $last_odometer;
                }
                $output['night_end_lat'] = $arrQueryResult[0]['end_lat'];
                $output['night_end_long'] = $arrQueryResult[0]['end_long'];
                //unset($arrQueryResult);
            }
            return $output;
        } else {
            return null;
        }
    }

    public function getNightDriveFirstDetils($deviceObject) {
        $nightlastodometer_query = "SELECT night_first_odometer,night_first_lat, night_first_long ,max_odometer
                                      FROM dailyreport
                                      WHERE uid = %d  AND vehicleid = %d
                                      AND daily_date = %s AND customerno = %d
                                      ORDER BY last_online_updated DESC LIMIT 1";
        $devicesQuery = sprintf($nightlastodometer_query, $deviceObject->uid, $deviceObject->vehicleid, $deviceObject->previousDay, $deviceObject->customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            if ($result !== false) {
                $arrQueryResult = $result->fetchAll();
                $output['night_firstodometer'] = $arrQueryResult[0]['night_first_odometer'];
                $output['night_first_lat'] = $arrQueryResult[0]['night_first_lat'];
                $output['night_first_long'] = $arrQueryResult[0]['night_first_long'];
                $output['night_firstmaxodometer'] = $arrQueryResult[0]['maxodometer'];
            }
            return $output;
        } else {
            return null;
        }
    }

    public function getWeekendDriveDetails($deviceObject) {
        $weekend_lastodo_query = " SELECT last_odometer AS weekend_lastodometer
                                    FROM dailyreport
                                    WHERE uid = %d  AND vehicleid = %d AND daily_date = %s AND customerno = %d
                                    ORDER BY last_online_updated ASC LIMIT 1";
        $devicesQuery = sprintf($weekend_lastodo_query, $deviceObject->uid, $deviceObject->vehicleid, $deviceObject->dayAfterCurrentDay, $deviceObject->customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            if ($result !== false) {
                $arrQueryResult = $result->fetchAll();
                $output['weekend_lastodometer'] = $arrQueryResult[0]['weekend_lastodometer'];
            }
        }
        $weekend_lastmaxodo_query = "SELECT max_odometer AS maxodometer FROM dailyreport
                                       WHERE uid = %d  AND vehicleid = %d AND daily_date = %s AND customerno = %d ";
        $devicesQuery = sprintf($weekend_lastmaxodo_query, $deviceObject->uid, $deviceObject->vehicleid, $deviceObject->dayAfterCurrentDay, $deviceObject->customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            if ($result !== false) {
                $arrQueryResult = $result->fetchAll();
                $output['weekend_lastmaxodometer'] = $arrQueryResult[0]['maxodometer'];
            }
        }
        if (isset($output) && !empty($output)) {
            return $output;
        } else {
            return null;
        }
    }

    public function addStoppageOtherReason($objReasonDetails) {
        $reasonid = 0;
        $today = date('Y-m-d H:i:s');
        $Query = "INSERT INTO vehicleStoppageReason (vehicleid, starttime, endtime, lat, lng, reasonid, remarks, customerno, created_by, created_on) VALUES (%d,'%s','%s','%s','%s','%s','%s',%d,%d,'%s')";
        $deviceQuery = sprintf($Query, $objReasonDetails->vehicleid, $objReasonDetails->starttime, $objReasonDetails->endtime, $objReasonDetails->lat, $objReasonDetails->lng, $objReasonDetails->reason, $objReasonDetails->remarks, $objReasonDetails->customerno, $objReasonDetails->userid, $today);
        $this->_databaseManager->executeQuery($deviceQuery);
        $reasonid = $this->_databaseManager->get_insertedId();
        return $reasonid;
    }
}