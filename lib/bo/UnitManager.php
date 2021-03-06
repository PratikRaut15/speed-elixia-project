<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
require_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
class UnitManager extends VersionedManager {
    public function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function getunits() {
        $units = Array();
        $Query = "select * from unit where customerno =%d AND unit.trans_statusid NOT IN (10,22)";
        $unitQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $unit = new VOUnit();
                $unit->uid = $row['uid'];
                $unit->unitno = $row['unitno'];
                $unit->customerno = $row['customerno'];
                $unit->vehicleid = $row['vehicleid'];
                $unit->analog1 = $row['analog1'];
                $unit->analog2 = $row['analog2'];
                $unit->analog3 = $row['analog3'];
                $unit->analog4 = $row['analog4'];
                $unit->digitalio = $row['digitalio'];
                $units[] = $unit;
            }
            return $units;
        }
        return NULL;
    }

    public function getvehiclefromunit($unitid) {
        $Query = "SELECT unit.*,v.vehicleno from unit
                 INNER JOIN vehicle v on v.uid = unit.uid
                 WHERE unit.customerno =%d AND unit.uid=%s";
        $unitQuery = sprintf($Query, $this->_Customerno, $unitid);
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $unit = new VOUnit();
                $unit->uid = $row['uid'];
                $unit->unitno = $row['unitno'];
                $unit->customerno = $row['customerno'];
                $unit->vehicleid = $row['vehicleid'];
                $unit->vehicleno = $row['vehicleno'];
                $unit->analog1 = $row['analog1'];
                $unit->analog2 = $row['analog2'];
                $unit->analog3 = $row['analog3'];
                $unit->analog4 = $row['analog4'];
                $unit->digitalio = $row['digitalio'];
            }
            return $unit;
        }
        return NULL;
    }

    public function getunitsforcustomer() {
        $units = array();
        $Query = "select unit.uid,unit.unitno,unit.customerno,devices.deviceid,
            vehicle.vehicleid from unit INNER JOIN devices ON devices.uid = unit.uid
            INNER JOIN vehicle ON vehicle.uid=unit.uid
            where unit.customerno =%d AND unit.trans_statusid NOT IN (10,22)";
        $unitQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $unit = new VOUnit();
                $unit->uid = $row['uid'];
                $unit->unitno = $row['unitno'];
                $unit->customerno = $row['customerno'];
                $unit->deviceid = $row['deviceid'];
                $unit->vehicleid = $row['vehicleid'];
                $units[] = $unit;
            }
            return $units;
        }
        return NULL;
    }

    public function getunithistoryinformation($unitno, $date) {
        $units = Array();
        $unitQuery = sprintf("select * from unithistory where customerno =%d and unitno=%s and DATE(lastupdated) between '%s' and '%s'", $this->_Customerno, $unitno, $date, $date);
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $unit = new VOUnit();
                $unit->uhid = $row['uhid'];
                $unit->uid = $row['uid'];
                $unit->unitno = $row['unitno'];
                $unit->customerno = $row['customerno'];
                $unit->vehicleid = $row['vehicleid'];
                $unit->analog1 = $row['analog1'];
                $unit->analog2 = $row['analog2'];
                $unit->analog3 = $row['analog3'];
                $unit->analog4 = $row['analog4'];
                $unit->digitalio = $row['digitalio'];
                $unit->lastupdated = $row['lastupdated'];
                $unit->dhid = $row['dhid'];
                $unit->vhid = $row['vhid'];
                $units[] = $unit;
            }
            return $units;
        }
        return NULL;
    }

    public function getunitno($vehicleid) {
        $Query = 'SELECT unitno FROM unit WHERE customerno = %d AND vehicleid = %d';
        $unitQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $unitno = $row['unitno'];
                return $unitno;
            }
        }
        return NULL;
    }

    public function getunitnofromdeviceid($vehicleid) {
        $Query = 'SELECT unit.unitno FROM unit
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            WHERE unit.customerno = %d AND vehicle.vehicleid = %d';
        $unitQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $unitno = $row['unitno'];
                return $unitno;
            }
        }
        return NULL;
    }

    public function getuidfromdeviceid($deviceid) {
        $Query = 'SELECT unit.unitno FROM unit
            INNER JOIN devices ON devices.uid = unit.uid
            WHERE unit.customerno = %d AND devices.deviceid = %d';
        $unitQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($deviceid));
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $unitno = $row['unitno'];
                return $unitno;
            }
        }
        return NULL;
    }

    public function getuid_all() {
        $uids = Array();
        $Query = 'SELECT unit.uid, unit.unitno, devices.deviceid,vehicle.vehicleid FROM unit
            INNER JOIN devices on devices.uid = unit.uid
            INNER JOIN vehicle on vehicle.uid = unit.uid
            WHERE unit.customerno = %d
            AND  vehicle.customerno = %d
            AND  devices.customerno = %d
            AND  vehicle.isdeleted = 0';
        $unitQuery = sprintf($Query, $this->_Customerno, $this->_Customerno, $this->_Customerno);
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $uid = new VOUnit();
                $uid->vehicleid = $row['vehicleid'];
                $uid->uid = $row['uid'];
                $uid->unitno = $row['unitno'];
                $uid->deviceid = $row['deviceid'];
                $uids[] = $uid;
            }
            return $uids;
        }
        return NULL;
    }

    public function getextrafromdeviceid($deviceid) {
        $Query = 'SELECT unit.extra_digital FROM unit
            INNER JOIN devices ON devices.uid = unit.uid
            WHERE unit.customerno = %d AND devices.deviceid = %d';
        $unitQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($deviceid));
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $extra_digital = $row['extra_digital'];
                return $extra_digital;
            }
        }
        return NULL;
    }

    public function getunitdetailsfromdeviceid($deviceid) {
        $Query = 'SELECT    vehicle.vehicleno
                            ,vehicle.fuel_min_volt
                            , vehicle.fuel_max_volt
                            , vehicle.fuelcapacity
                            ,vehicle.max_voltage
                            ,vehicle.kind
                            ,vehicle.hum_min
                            ,vehicle.hum_max
                            ,unit.unitno
                            , unit.tempsen1
                            , unit.tempsen2
                            ,unit.tempsen3
                            ,unit.tempsen4
                            ,unit.n1
                            ,unit.n2
                            , unit.n3
                            ,unit.n4
                            ,unit.analog1
                            ,unit.analog2
                            , unit.analog3
                            ,unit.analog4
                            ,unit.get_conversion
                            ,unit.humidity
                            ,unit.acsensor
                            , unit.fuelsensor
                            , unit.is_ac_opp
                            ,unit.uid
                            ,vehicle.vehicleid
                            ,unit.isDoorExt
                            ,unit.is_door_opp
                FROM        unit
                INNER JOIN devices ON devices.uid = unit.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                WHERE       unit.customerno = %d AND devices.deviceid = %d';
        $unitQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($deviceid));
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $unit = new VOUnit();
                $unit->uid = $row['uid'];
                $unit->vehicleid = $row['vehicleid'];
                $unit->kind = $row['kind'];
                $unit->unitno = $row['unitno'];
                $unit->vehicleno = $row['vehicleno'];
                $unit->tempsen1 = $row['tempsen1'];
                $unit->tempsen2 = $row['tempsen2'];
                $unit->tempsen3 = $row['tempsen3'];
                $unit->tempsen4 = $row['tempsen4'];
                $unit->n1 = $row['n1'];
                $unit->n2 = $row['n2'];
                $unit->n3 = $row['n3'];
                $unit->n4 = $row['n4'];
                $unit->analog1 = $row['analog1'];
                $unit->analog2 = $row['analog2'];
                $unit->analog3 = $row['analog3'];
                $unit->analog4 = $row['analog4'];
                $unit->get_conversion = $row['get_conversion'];
                $unit->humidity = $row['humidity'];
                $unit->acsensor = $row['acsensor'];
                $unit->fuelsensor = $row['fuelsensor'];
                $unit->fuelcapacity = $row['fuelcapacity'];
                $unit->maxvoltage = $row['max_voltage'];
                $unit->hum_min = $row['hum_min'];
                $unit->hum_max = $row['hum_max'];
                $unit->fuel_min_volt = $row['fuel_min_volt'];
                $unit->fuel_max_volt = $row['fuel_max_volt'];
                $unit->isacopp = $row['is_ac_opp'];
                $unit->isDoorExt = $row['isDoorExt'];
                $unit->is_door_opp = $row['is_door_opp'];
                return $unit;
            }
        }
        return NULL;
    }

    public function getunitdetailsfromdeviceid_cron($deviceid) {
        $result = array();
        $Query = 'SELECT vehicle.vehicleno,vehicle.fuel_min_volt, vehicle.fuel_max_volt, vehicle.fuelcapacity,vehicle.max_voltage,vehicle.kind
            ,vehicle.hum_min,vehicle.hum_max,unit.unitno, unit.tempsen1, unit.tempsen2,unit.tempsen3,unit.tempsen4
            ,unit.n1,unit.n2, unit.n3,unit.n4,unit.analog1,unit.analog2, unit.analog3,unit.analog4,unit.get_conversion
            ,unit.humidity,unit.acsensor, unit.fuelsensor, unit.is_ac_opp,unit.uid,vehicle.vehicleid
            FROM unit
            INNER JOIN devices ON devices.uid = unit.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            WHERE unit.customerno = %d AND devices.deviceid = %d';
        $unitQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($deviceid));
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $unit = new VOUnit();
                $unit->uid = $row['uid'];
                $unit->vehicleid = $row['vehicleid'];
                $unit->kind = $row['kind'];
                $unit->unitno = $row['unitno'];
                $unit->vehicleno = $row['vehicleno'];
                $unit->tempsen1 = $row['tempsen1'];
                $unit->tempsen2 = $row['tempsen2'];
                $unit->tempsen3 = $row['tempsen3'];
                $unit->tempsen4 = $row['tempsen4'];
                $unit->n1 = $row['n1'];
                $unit->n2 = $row['n2'];
                $unit->n3 = $row['n3'];
                $unit->n4 = $row['n4'];
                $unit->analog1 = $row['analog1'];
                $unit->analog2 = $row['analog2'];
                $unit->analog3 = $row['analog3'];
                $unit->analog4 = $row['analog4'];
                $unit->get_conversion = $row['get_conversion'];
                $unit->humidity = $row['humidity'];
                $unit->acsensor = $row['acsensor'];
                $unit->fuelsensor = $row['fuelsensor'];
                $unit->fuelcapacity = $row['fuelcapacity'];
                $unit->maxvoltage = $row['max_voltage'];
                $unit->hum_min = $row['hum_min'];
                $unit->hum_max = $row['hum_max'];
                $unit->fuel_min_volt = $row['fuel_min_volt'];
                $unit->fuel_max_volt = $row['fuel_max_volt'];
                $unit->isacopp = $row['is_ac_opp'];
                $result[] = $unit;
            }
        }
        return $result;
    }

    public function getunitdetailsfromvehid($deviceid) {
        $Query = 'SELECT vehicle.fuel_min_volt,
        vehicle.fuel_max_volt,
        vehicle.fuelcapacity,
        vehicle.max_voltage,
        vehicle.fuelMaxVoltCapacity,
        unit.fuelsensor,
        vehicle.temp1_min,
        vehicle.temp1_max,
        vehicle.temp2_min,
        vehicle.temp2_max,
        vehicle.temp3_min,
        vehicle.temp3_max,
        vehicle.temp4_min,
        vehicle.temp4_max,
        unit.unitno,
        unit.tempsen1,
        unit.tempsen2,
        unit.tempsen3,
        unit.tempsen4,
        unit.get_conversion,
        vehicle.overspeed_limit,
        devices.deviceid,
        vehicle.vehicleno
        FROM unit
        INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
        INNER JOIN devices on devices.uid = unit.uid
        WHERE unit.customerno = %d AND unit.vehicleid = %d';
        $unitQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($deviceid));
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
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
                $unit->get_conversion = $row['get_conversion'];
                $unit->fuelsensor = $row['fuelsensor'];
                $unit->fuelcapacity = $row['fuelcapacity'];
                $unit->maxvoltage = $row['max_voltage'];
                $unit->fuel_min_volt = $row['fuel_min_volt'];
                $unit->fuel_max_volt = $row['fuel_max_volt'];
                $unit->fuelMaxVoltCapacity = $row['fuelMaxVoltCapacity'];
                $unit->overspeed_limit = $row['overspeed_limit'];
                $unit->deviceid = $row['deviceid'];
                $unit->vehicleno = $row['vehicleno'];
                return $unit;
            }
        }
        return NULL;
    }

    public function getuidfromvehicleid($vehicleid) {
        $Query = 'SELECT unitno FROM unit
            WHERE customerno = %d AND vehicleid = %d';
        $unitQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $unitno = $row['unitno'];
                return $unitno;
            }
        }
        return NULL;
    }

    public function get_conversionfromUnitno($unitno) {
        $Query = 'SELECT get_conversion FROM unit
            WHERE customerno = %d AND unitno = %d';
        $unitQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($unitno));
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $get_conversion = $row['get_conversion'];
                return $get_conversion;
            }
        }
        return NULL;
    }

    public function getacinvertval($unitno) {
        $Query = "SELECT acsensor,is_ac_opp FROM unit WHERE customerno = %d AND unitno = '%s'";
        $unitQuery = sprintf($Query, $this->_Customerno, Sanitise::String($unitno));
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $acopp['0'] = $row['is_ac_opp'];
                $acopp['1'] = $row['acsensor'];
                return $acopp;
            }
        }
        return NULL;
    }

    public function getDoorStatus($unitno) {
        $Query = 'SELECT doorsensor,is_door_opp FROM unit WHERE customerno = %d AND unitno = %s';
        $unitQuery = sprintf($Query, $this->_Customerno, Sanitise::String($unitno));
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return array(
                    'doorsensor' => $row['doorsensor'],
                    'is_door_op' => $row['is_door_opp']
                );
            }
        }
        return NULL;
    }

    public function getvehicleidbydeviceid($deviceid) {
        $vehicleiddata = array();
        $Query = 'SELECT u.vehicleid FROM devices as d left join unit as u on d.uid = u.uid WHERE d.customerno = %d AND d.deviceid = %d';
        $unitQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($deviceid));
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicleiddata = array(
                    'vehicleid' => $row['vehicleid']
                );
                return $vehicleiddata;
            }
        }
        return NULL;
    }

    public function getunitmutedetails($vehicleid, $uid) {
        $mutearr = array();
        $Query = 'SELECT
            muteid,
            vehicleid,
            uid,
            customerno,
            temp_type,
            mute_starttime,
            mute_endtime
            FROM temp_mute
            WHERE temp_mute.customerno = %d AND temp_mute.vehicleid = %d AND temp_mute.uid = %d';
        $Sql = sprintf($Query
            , $this->_Customerno
            , Sanitise::Long($vehicleid)
            , Sanitise::Long($uid));
        $this->_databaseManager->executeQuery($Sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $unit = new stdClass();
                $unit->muteid = $row['muteid'];
                $unit->uid = $row['uid'];
                $unit->vehicleid = $row['vehicleid'];
                $unit->customerno = $row['customerno'];
                $unit->temp_type = $row['temp_type'];
                $unit->mute_starttime = $row['mute_starttime'];
                $unit->mute_endtime = $row['mute_endtime'];
                if ($row['mute_endtime'] == '0000-00-00 00:00:00') {
                    $unit->mute_endtime = date(speedConstants::DEFAULT_TIMESTAMP);
                }
                $mutearr[] = $unit;
            }
            return $mutearr;
        }
        return NULL;
    }

    public function getunitdetailsbyunitno($unitno) {
        $Query = "SELECT uid"
            . ", doorsensor, is_door_opp"
            . ", acsensor, is_ac_opp"
            . " FROM unit "
            . " WHERE unitno = '%s'";
        $unitQuery = sprintf($Query, Sanitise::String($unitno));
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return array(
                    'uid' => $row['uid'],
                    'doorsensor' => $row['doorsensor'],
                    'is_door_op' => $row['is_door_opp'],
                    'is_ac_opp' => $row['is_ac_opp'],
                    'acsensor' => $row['acsensor']
                );
            }
        }
        return NULL;
    }

    public function getTemperatureNonComplianceData($objTemp) {
        $tempData = array();
        $SQL = "SELECT tnc.vehicleid, v.vehicleno, tnc.uid, tnc.nc_st as starttime, tnc.nc_et as endtime, tnc.`timestamp`, tnc.customerno, tnc.temp_type, g.groupname
        FROM temp_non_compliance as tnc
        INNER JOIN vehicle as v on v.vehicleid = tnc.vehicleid
        INNER JOIN unit as u on u.uid = tnc.uid
        LEFT OUTER JOIN `group` as g  on g.groupid = v.groupid
        WHERE tnc.customerno = %d and ((DATE(tnc.`timestamp`) = '%s') OR (DATE(nc_st) = '%s') OR (nc_et='0000-00-00 00:00:00' and DATE(nc_st) <> '%s'))
        AND v.customerno= %d
        AND u.customerno=%d
        AND v.isdeleted=0 order By v.kind, tnc.`nc_st` DESC  ";
        $Query = sprintf($SQL, $objTemp->customerno, $objTemp->startDate, $objTemp->startDate, $objTemp->todaysDate, $objTemp->customerno, $objTemp->customerno);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $objData = new stdClass();
                $objData->vehicleid = $row['vehicleid'];
                $objData->vehicleno = $row['vehicleno'];
                $objData->unitid = $row['uid'];
                $objData->startTime = $row['starttime'];
                $objData->endTime = $row['endtime'];
                $objData->customerno = $row['customerno'];
                $objData->created_on = $row['timestamp'];
                $objData->groupName = ($row['groupname'] != '') ? $row['groupname'] : 'Ungrouped';
                $objData->temperature = "Temperature " . $row['temp_type'];
                $tempData[] = $objData;
            }
        }
        return $tempData;
    }

    public function setCommand($objCommandDetails) {
        $isUpdated = 0;
        $setcom = 1;
        if (isset($objCommandDetails->setcom)) {
            $setcom = $objCommandDetails->setcom;
        }
        $updateCmdQuery = "UPDATE unit SET setcom=%d, command = '%s' where uid = %d";
        $formattedQuery = sprintf($updateCmdQuery, $setcom, $objCommandDetails->command, $objCommandDetails->uid);
        $this->_databaseManager->executeQuery($formattedQuery);
        $noOfRowAffected = $this->_databaseManager->get_affectedRows();
        if ($noOfRowAffected > 0) {
            $isUpdated = 1;
        }
    }

    public function getUnitDetails_Listener($objRequest) {
        /*
        #################################################################
        ##### DO NOT CHANGE THE SEQUENCE OF PARAMS IN SELECT QUERY ######
        #################################################################
         */
        $Query = "SELECT unit.uid, unit.customerno, unitno, command, setcom FROM unit INNER JOIN devices on unit.uid = devices.uid where unit.unitno = '%s'";
        $unitQuery = sprintf($Query, Sanitise::String($objRequest->unitNo));
        $pdo = $this->_databaseManager->CreatePDOConn();
        $unitDetails = $pdo->query($unitQuery)->fetchAll();
        $this->_databaseManager->ClosePDOConn($pdo);
        return $unitDetails;
    }

    public function getReqdDeviceDetailsBeforeUpdate_Listener($arrUnitDetail) {
        $unitid = $arrUnitDetail[0];
        $customerno = $arrUnitDetail[1];
        /*
        #################################################################
        ##### DO NOT CHANGE THE SEQUENCE OF PARAMS IN SELECT QUERY ######
        #################################################################
         */
        $Query = "SELECT tz.timediff, v.vehicleid, v.vehicleno, v.driverid, v.overspeed_limit, v.stoppage_odometer"
            . ",v.stoppage_flag, u.digitalio, v.odometer, d.deviceid , v.ignition_wirecut, v.kind, u.hasDeliverySwitch, u.analog1 "
            . "FROM vehicle v "
            . "INNER JOIN unit u ON u.uid = v.uid "
            . "INNER JOIN devices d ON u.uid = d.uid "
            . "INNER JOIN customer c ON u.customerno = u.customerno "
            . "INNER JOIN timezone tz ON tz.tid = c.timezone "
            . "WHERE v.uid = %d "
            . "AND c.customerno = %d "
            . "AND v.customerno = %d "
            . "AND u.customerno = %d "
            . "AND d.customerno = %d LIMIT 1";
        $unitQuery = sprintf($Query, $unitid, $customerno, $customerno, $customerno, $customerno);
        $pdo = $this->_databaseManager->CreatePDOConn();
        $arrReqdDeviceDetails = $pdo->query($unitQuery)->fetch();
        #Close the connection
        $this->_databaseManager->ClosePDOConn($pdo);
        return $arrReqdDeviceDetails;
    }

    public function UpdateDeviceDataInMySQL_Listener($objDeviceData) {
        $isUpdated = 0;
        /*
        Usually, when timestamp is invalid, GPSfixed should be "V". But, sometimes it is A. Hence,hardcoding to "A"
        If timestamp is not valid we would not like to consider any other data except lastupdated.
         */
        if ($objDeviceData->isPacketTimeValid == 0) {
            $gpsfixed = "V";
        }
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sp_params = "" . $objDeviceData->unitid . ""
        . ",'" . $objDeviceData->vehicleid . "'"
        . ",'" . $objDeviceData->devicelat . "'"
        . ",'" . $objDeviceData->devicelong . "'"
        . ",'" . $objDeviceData->altitude . "'"
        . ",'" . $objDeviceData->directionchange . "'"
        . ",'" . $objDeviceData->satv . "'"
        . ",'" . $objDeviceData->inbatt . "'"
        . ",'" . $objDeviceData->hwv . "'"
        . ",'" . $objDeviceData->swv . "'"
        . ",'" . $objDeviceData->msgid . "'"
        . ",'" . $objDeviceData->status . "'"
        . ",'" . $objDeviceData->ignition . "'"
        . ",'" . $objDeviceData->powercut . "'"
        . ",'" . $objDeviceData->tamper . "'"
        . ",'" . $objDeviceData->isOffline . "'"
        . ",'" . $objDeviceData->gpsfixed . "'"
        . ",'" . $objDeviceData->gsmstrength . "'"
        . ",'" . $objDeviceData->gsmregister . "'"
        . ",'" . $objDeviceData->gprsregister . "'"
        . ",'" . $objDeviceData->extbatt . "'"
        . ",'" . $objDeviceData->odometer . "'"
        . ",'" . $objDeviceData->stoppage_odometer . "'"
        . ",'" . $objDeviceData->stoppage_flag . "'"
        . ",'" . $objDeviceData->speed . "'"
        . ",'" . $objDeviceData->analog1 . "'"
        . ",'" . $objDeviceData->analog2 . "'"
        . ",'" . $objDeviceData->analog3 . "'"
        . ",'" . $objDeviceData->analog4 . "'"
        . ",'" . $objDeviceData->digitalio . "'"
        . ",'" . $objDeviceData->commandkey . "'"
        . ",'" . $objDeviceData->commandkeyval . "'"
        . ",'" . $objDeviceData->alterremark . "'"
        . ",'" . $objDeviceData->customerno . "'"
        . ",'" . $objDeviceData->timestamp . "'"
            . "," . '@isUpdated';
        $queryCallSP = $this->_databaseManager->PrepareSP("listener_update_device_details", $sp_params);
        $pdo->query($queryCallSP);
        $outputParamsQuery = "SELECT @isUpdated AS isUpdated";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $isUpdated = $outputResult['isUpdated'];
        $this->_databaseManager->ClosePDOConn($pdo);
        return $isUpdated;
    }

    public function cron_getunitForCust($customerno) {
        $units = array();
        $Query = "SELECT unit.unitno from unit
            INNER JOIN devices ON devices.uid = unit.uid
            INNER JOIN vehicle ON vehicle.uid=unit.uid
            where unit.customerno =%d AND unit.trans_statusid NOT IN (10,22)";
        $unitQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $unit = new VOUnit();
                $unit->unitno = $row['unitno'];
                $units[] = $unit;
            }
            return $units;
        }
        return NULL;
    }

    public function getUnitDetailByUnitNo($unitNo) {
        $Query = "SELECT
                 unit.uid,unit.unitno,v.vehicleid,v.vehicleno,v.kind,v.groupid,g.groupname,unit.customerno,d.deviceid,unit.get_conversion,unit.tempsen1,unit.tempsen2,c.use_humidity
                 from unit
                 INNER JOIN vehicle v on v.uid = unit.uid
                 INNER JOIN devices d on d.uid = unit.uid
                 INNER JOIN customer c on c.customerno = unit.customerno
                 LEFT JOIN `group` g on g.groupid = v.groupid
                 WHERE unit.customerno =%d AND unit.unitno=%s";
        $unitQuery = sprintf($Query, $this->_Customerno, $unitNo);
        $this->_databaseManager->executeQuery($unitQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $unit = new VOUnit();
                $unit->uid = $row['uid'];
                $unit->vehicleid = $row['vehicleid'];
                $unit->deviceid = $row['deviceid'];
                $unit->unitno = $row['unitno'];
                $unit->vehicleno = $row['vehicleno'];
                $unit->customerno = $row['customerno'];
                $unit->groupid = $row['groupid'];
                $unit->groupname = $row['groupname'];
                $unit->kind = $row['kind'];
                $unit->get_conversion = $row['get_conversion'];
                $unit->tempsen1 = $row['tempsen1'];
                $unit->tempsen2 = $row['tempsen2'];
                $unit->use_humidity = $row['use_humidity'];
            }
            return $unit;
        }
        return NULL;
    }

    public function insertUnitBackdatedRealtimeDate($objRequest) {
        $Query = "INSERT INTO mdlzRealTimeDump
        (vehicleid
        ,vehicleno
        ,unitid
        ,unitno
        ,groupid
        ,groupname
        ,lat
        ,`long`
        ,temp1
        ,temp2
        ,kind
        ,customerno
        ,lastupdated
        ,timestamp
        )
        VALUES (%d,'%s',%d,'%s',%d,'%s','%s','%s','%s','%s','%s',%d,'%s','%s')";
        $SQL = sprintf($Query,
            Sanitise::Long($objRequest->vehicleId),
            Sanitise::String($objRequest->vehicleNo),
            Sanitise::Long($objRequest->unitId),
            Sanitise::String($objRequest->unitNo),
            Sanitise::Long($objRequest->groupId),
            Sanitise::String($objRequest->groupName),
            Sanitise::String($objRequest->lat),
            Sanitise::String($objRequest->long),
            Sanitise::String($objRequest->temp1),
            Sanitise::String($objRequest->temp2),
            Sanitise::String($objRequest->kind),
            Sanitise::Long($objRequest->customerNo),
            Sanitise::DateTime($objRequest->lastupdated),
            Sanitise::DateTime($objRequest->timestamp)
        );
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getUnitId($vehcileId) {
        $Query = 'select unit.unitno from vehicle INNER JOIN unit on vehicle.uid = unit.uid where vehicle.vehicleid=' . $vehcileId;
        // $unitQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($deviceid));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $unitno = $row['unitno'];
                return $unitno;
            }
        }
        return NULL;
    }

    public function getDeviceIdFromUnitNumber($unitno) {
        $Query = 'select devices.deviceid from devices inner join unit on unit.uid = devices.uid where unitno="' . $unitno . '"';
        // $unitQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($deviceid));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $unitno = $row['deviceid'];
                return $unitno;
            }
        }
        return NULL;
    }
}