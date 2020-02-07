<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
require_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
class tyretype {
}
class VehicleManager extends VersionedManager {
    public function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function get_all_vehicles($groupid = null) {
        /**
         * @internal Modied by Suman Sharma - 27-09-2017
         * @internal Changes- added groupid in param and used in code
         */
        $vehicles = Array();
        if (!isset($groupid) && isset($_SESSION['groupid'])) {
            $groupid = $_SESSION['groupid'];
        }
        $Query = 'SELECT * FROM vehicle WHERE customerno=%d AND isdeleted=0';
        if ($groupid != 0) {
            $Query .= " AND vehicle.groupid in (%s)";
        }
        if ($groupid != 0) {
            $vehiclesQuery = sprintf($Query, $this->_Customerno, $groupid);
        } else {
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->uid = $row['uid'];
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->odometer = $row['odometer'];
                $vehicle->lastupdated = $row['lastupdated'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->vehicleno = $row["vehicleno"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getdelboyname($vehicleid) {
        $realname = "";
        $query = "select realname from user where delivery_vehicleid=" . $vehicleid;
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $realname = $row['realname'];
            }
        }
        return $realname;
    }

    public function get_all_vehicles_byId($srhstring, $limit = null) {
        $vehicles = Array();
        $srhstring = "%" . $srhstring . "%";
        $Query = "SELECT * FROM vehicle WHERE customerno=%d AND (vehicleno LIKE '%s' OR REPLACE(vehicleNo, ' ', '') LIKE '%s') AND isdeleted=0";
        if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
        }
        if (isset($limit) && $limit > 0) {
            $Query .= " LIMIT " . $limit;
        }
        if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
            $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring), Sanitise::String($srhstring), $_SESSION['groupid']);
        } else {
            $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring), Sanitise::String($srhstring));
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                //$vehicle->devicekey = $row['devicekey'];
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->odometer = $row['odometer'];
                $vehicle->lastupdated = $row['lastupdated'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->vehicleno = $row["vehicleno"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_vehicle_by_vehno($vehNo, $limit = null) {
        $vehicles = Array();
        if (isset($vehNo) && $vehNo != '') {
            $vehNo = str_replace(" ", "", $vehNo);
            $vehNo = str_replace("-", "", $vehNo);
            $vehNo = '%' . $vehNo . '%';
            $Query = "  SELECT  v.vehicleid
                                , v.vehicleno
                                , v.extbatt
                                , v.odometer
                                , v.lastupdated
                                , v.curspeed
                                , v.driverid
                                , u.unitno
                                , d.deviceid
                                , g.groupname
                                , v.stoppage_odometer
                                , v.stoppage_flag
                        FROM    vehicle v
                        INNER JOIN unit u on u.uid = v.uid
                        INNER JOIN devices d on d.uid = u.uid
                        LEFT OUTER JOIN `group` as g on g.groupid = v.groupid
                        WHERE   v.customerno=%d
                        AND     REPLACE(REPLACE(vehicleno, ' ', ''), '-','') LIKE '%s'
                        AND     u.trans_statusid NOT IN (10,22)
                        AND     v.isdeleted=0";
            /*AND     REPLACE(vehicleno, ' ', '') LIKE '%s'*/
            if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
                $Query .= " AND vehicle.groupid =%d";
            }
            if (isset($limit) && $limit > 0) {
                $Query .= " LIMIT " . $limit;
            }
            if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
                $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($vehNo), $_SESSION['groupid']);
            } else {
                $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($vehNo));
            }
            $this->_databaseManager->executeQuery($vehiclesQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()) {
                    $vehicle = new VOVehicle();
                    $vehicle->vehicleid = $row['vehicleid'];
                    //$vehicle->devicekey = $row['devicekey'];
                    $vehicle->extbatt = $row['extbatt'];
                    $vehicle->odometer = $row['odometer'];
                    $vehicle->lastupdated = $row['lastupdated'];
                    $vehicle->curspeed = $row['curspeed'];
                    $vehicle->driverid = $row['driverid'];
                    $vehicle->vehicleno = $row["vehicleno"];
                    $vehicle->unitno = $row["unitno"];
                    $vehicle->deviceid = $row["deviceid"];
                    $vehicle->groupname = $row["groupname"];
                    $vehicle->stoppage_odometer = $row["stoppage_odometer"];
                    $vehicle->stoppage_flag = $row["stoppage_flag"];
                    $vehicles[] = $vehicle;
                }
                return $vehicles;
            }
        }
        return null;
    }

    public function get_all_vehicles_pdf($customerno) {
        $vehicles = Array();
        $Query = 'SELECT * FROM vehicle WHERE customerno=%d AND isdeleted=0';
        if (isset($_SESSION) && $_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
        }
        if (isset($_SESSION) && $_SESSION['groupid'] != 0) {
            $vehiclesQuery = sprintf($Query, $customerno, $_SESSION['groupid']);
        } else {
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
//$vehicle->devicekey = $row['devicekey'];
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->odometer = $row['odometer'];
                $vehicle->lastupdated = $row['lastupdated'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->vehicleno = $row["vehicleno"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_filter_vehicle($vehicleid) {
        $vehicles = Array();
        $Query = "SELECT * FROM vehicle WHERE vehicleno LIKE '%s' AND customerno=%d AND isdeleted=0";
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
        }
        if ($_SESSION['groupid'] != 0) {
            $vehiclesQuery = sprintf($Query, "%" . $vehicleid . "%", $this->_Customerno, $_SESSION['groupid']);
        } else {
            $vehiclesQuery = sprintf($Query, "%" . $vehicleid . "%", $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
//$vehicle->devicekey = $row['devicekey'];
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->odometer = $row['odometer'];
                $vehicle->lastupdated = $row['lastupdated'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->vehicleno = $row["vehicleno"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_all_vehicles_count($switch_to) {
        $Query = 'SELECT count(vehicle.vehicleid) as countveh FROM vehicle
        INNER JOIN unit ON unit.uid = vehicle.uid
        INNER JOIN driver ON driver.vehicleid = vehicle.vehicleid
        WHERE vehicle.customerno=%d AND vehicle.isdeleted=0';
        if ($switch_to == 3) {
            $Query .= " AND vehicle.kind ='Warehouse'";
        } else {
            $Query .= " AND vehicle.kind <> 'Warehouse'";
        }
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
        }
        if ($_SESSION['groupid'] != 0) {
            $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $count = $row["countveh"];
            }
            return $count;
        }
        return null;
    }

    public function get_all_vehicles_with_unitno() {
        $vehicles = Array();
        $Query = 'SELECT * FROM vehicle
        INNER JOIN unit on unit.vehicleid=vehicle.vehicleid
        WHERE vehicle.customerno=%d AND vehicle.isdeleted=0';
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
        }
        if ($_SESSION['groupid'] != 0) {
            $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                //$vehicle->devicekey = $row['devicekey'];
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->odometer = $row['odometer'];
                $vehicle->lastupdated = $row['lastupdated'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->vehicleno = $row["vehicleno"];
                $vehicle->unitno = $row["unitno"];
                $vehicle->uid = $row['uid'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    /**
     * Query used for getting grouped vehicles
     * @param type $groups
     */
    public function get_all_vehicles_by_group($groups, $isWarehouse = null, $fetchVehiclesForUserOnly = 0, $userId = 0) {
        $today = date("Y-m-d H:i:s");
        $vehicles = Array();
        $vehiclekind = '';
        $list = "vehicle.vehicleid
            ,vehicle.customerno
            ,vehicle.vehicleno
            ,vehicle.kind
            ,vehicle.overspeed_limit
            ,unit.acsensor
            ,unit.is_ac_opp
            ,unit.uid
            ,unit.unitno
            ,devices.deviceid
            ,unit.tempsen1
            ,unit.tempsen2
            ,unit.tempsen3
            ,unit.tempsen4
            ,vehicle.groupid
            ,vehicle.temp1_min
            ,vehicle.temp2_min
            ,vehicle.temp3_min
            ,vehicle.temp4_min
            ,vehicle.temp1_max
            ,vehicle.temp2_max
            ,vehicle.temp3_max
            ,vehicle.temp4_max
            ,unit.analog1
            ,unit.analog2
            ,unit.analog3
            ,unit.analog4
            ,unit.get_conversion
            ,vehicle.lastupdated
            ,DATEDIFF('" . $today . "',vehicle.lastupdated) as inactive_days
            ,devices.ignition
            ,vehicle.curspeed
            ,`group`.groupname
            ,vehicle.stoppage_flag
            ,unit.n1
            ,unit.n2
            ,unit.n3
            ,unit.n4
            ,vehicle.sequenceno
            ,simcard.simcardno
            ,devices.powercut
            ,devices.gsmstrength
            ,devices.gprsregister
            ,devices.tamper
            ,vehicle.stoppage_transit_time";
        if ($isWarehouse == null) {
            $vehiclekind = ' and vehicle.kind<> "Warehouse"';
        } else {
            $vehiclekind = ' and vehicle.kind="Warehouse"';
        }
        if (isset($_SESSION['ecodeid'])) {
            $Query = "SELECT $list
            FROM ecodeman
            INNER JOIN vehicle ON vehicle.vehicleid = ecodeman.vehicleid
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN elixiacode ON elixiacode.id = ecodeman.ecodeid
            LEFT JOIN `group` ON `group`.groupid = vehicle.groupid
            LEFT JOIN simcard ON simcard.id = devices.simcardid
            WHERE ecodeman.customerno =%d
            AND ecodeman.ecodeid=%d
            AND ecodeman.isdeleted=0
            AND unit.trans_statusid NOT IN (10,22)
            AND vehicle.isdeleted=0 " . $vehiclekind . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.sequenceno ASC,vehicle.lastupdated ASC";
            if ($groups[0] == 0) {
                $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['e_id']);
            } else {
                $group_in = implode(',', $groups);
                $vehiclesQuery = sprintf($Query, $group_in, $this->_Customerno, $_SESSION['e_id']);
            }
        } elseif ($fetchVehiclesForUserOnly == 1) {
            $Query = 'SELECT ' . $list . ' FROM vehicle
                INNER JOIN unit on unit.vehicleid=vehicle.vehicleid
                INNER JOIN devices on devices.uid=vehicle.uid
                LEFT JOIN `group` ON `group`.groupid = vehicle.groupid
                LEFT JOIN simcard ON simcard.id = devices.simcardid';
            $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $userId . "";
            $Query .= ' WHERE (vehicle.customerno=%d )
                AND vehicle.isdeleted=0 ' . $vehiclekind . ' and vehmap.isdeleted = 0 and unit.trans_statusid NOT IN (10,22) ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.sequenceno ASC,vehicle.lastupdated ASC';
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
        } elseif (isset($_SESSION['roleid']) && $_SESSION['roleid'] == 43) {
            // elseif (isset($_SESSION['roleid']) && $this->VehicleUsermappingExists($_SESSION['userid']) == true) {
            $Query = 'SELECT ' . $list . ' FROM vehicle
            INNER JOIN unit on unit.vehicleid=vehicle.vehicleid
            INNER JOIN devices on devices.uid=vehicle.uid
            LEFT JOIN `group` ON `group`.groupid = vehicle.groupid
            LEFT JOIN simcard ON simcard.id = devices.simcardid';
            if ($groups[0] != 0) {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid in (%s)";
            } else {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . "";
            }
            $Query .= ' WHERE (vehicle.customerno=%d )
            AND vehicle.isdeleted=0 ' . $vehiclekind . ' and vehmap.isdeleted = 0 and unit.trans_statusid NOT IN (10,22) ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.sequenceno ASC,vehicle.lastupdated ASC';
            if ($groups[0] == 0) {
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            } else {
                $group_in = implode(',', $groups);
                $vehiclesQuery = sprintf($Query, $group_in, $this->_Customerno);
            }
        } else {
            $Query = 'SELECT ' . $list . ' FROM vehicle
            INNER JOIN unit on unit.vehicleid=vehicle.vehicleid
            INNER JOIN devices on devices.uid=vehicle.uid
            LEFT JOIN `group` ON `group`.groupid = vehicle.groupid
            LEFT JOIN simcard ON simcard.id = devices.simcardid
            WHERE (vehicle.customerno=%d )
            AND vehicle.isdeleted=0 ' . $vehiclekind . ' and unit.trans_statusid NOT IN (10,22)';
            if (!array_key_exists('0', $groups) || $groups[0] == 0) {
                $Query .= " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.sequenceno ASC,vehicle.lastupdated ASC";
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            } else {
                $group_in = implode(',', $groups);
                $Query .= " AND vehicle.groupid in (%s) ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.sequenceno ASC,vehicle.lastupdated ASC";
                $vehiclesQuery = sprintf($Query, $this->_Customerno, $group_in);
            }
        }
        $vehiclesQuery = $vehiclesQuery;
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->customerno = $row['customerno'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->type = $row['kind'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->acsensor = $row['acsensor'];
                $vehicle->is_ac_opp = $row['is_ac_opp'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->tempsen1 = $row['tempsen1'];
                $vehicle->tempsen2 = $row['tempsen2'];
                $vehicle->tempsen3 = $row['tempsen3'];
                $vehicle->tempsen4 = $row['tempsen4'];
                $vehicle->groupid = $row['groupid'];
                $vehicle->temp1_min = $row['temp1_min'];
                $vehicle->temp1_max = $row['temp1_max'];
                $vehicle->temp2_min = $row['temp1_min'];
                $vehicle->temp2_max = $row['temp1_max'];
                $vehicle->temp3_min = $row['temp3_min'];
                $vehicle->temp3_max = $row['temp3_max'];
                $vehicle->temp4_min = $row['temp4_min'];
                $vehicle->temp4_max = $row['temp4_max'];
                $vehicle->analog1 = $row["analog1"];
                $vehicle->analog2 = $row["analog2"];
                $vehicle->analog3 = $row["analog3"];
                $vehicle->analog4 = $row["analog4"];
                $vehicle->get_conversion = $row["get_conversion"];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated_store = $row['lastupdated'];
                    $vehicle->lastupdated = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated_store = $row['lastupdated'];
                    $vehicle->lastupdated = $row['lastupdated'];
                }
                $vehicle->inactive_days = $row['inactive_days'];
                if ($vehicle->inactive_days <= 5) {
                    $vehicle->bucket_days = "Less Than Equal to 5";
                } elseif ($vehicle->inactive_days > 5 && $vehicle->inactive_days <= 10) {
                    $vehicle->bucket_days = "Greater Than 5 and less than Equal to 10";
                } elseif ($vehicle->inactive_days > 10 && $vehicle->inactive_days <= 20) {
                    $vehicle->bucket_days = "Greater than 10 and Less Than or Equal to 20";
                } elseif ($vehicle->inactive_days > 20 && $vehicle->inactive_days <= 30) {
                    $vehicle->bucket_days = "Greater than 20 and Less Than or Equal to 30";
                } else {
                    $vehicle->bucket_days = "Above 30";
                }
                $vehicle->ignition = $row['ignition'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->groupname = $row['groupname'];
                $vehicle->stoppage_flag = $row['stoppage_flag'];
                $vehicle->stoppage_transit_time = $row['stoppage_transit_time'];
                $vehicle->n1 = $row['n1'];
                $vehicle->n2 = $row['n2'];
                $vehicle->n3 = $row['n3'];
                $vehicle->n4 = $row['n4'];
                $vehicle->simcardno = isset($row['simcardno']) ? $row['simcardno'] : '';
                $vehicle->reason = '';
                if ($row['powercut'] == 0) {
                    $vehicle->reason = "Power Cut";
                } elseif (round((int) $row['gsmstrength'] / 31 * 100) < 30) {
                    $vehicle->reason = "Low Network";
                } elseif ($row['gprsregister'] < 14) {
                    $vehicle->reason = "Unknown Reason";
                } elseif ($row['tamper'] == 1) {
                    $vehicle->reason = "Tampered";
                } else {
                    $vehicle->reason = "Unknown Reason";
                }
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_all_vehicles_by_group_withusermapping($groups, $isWarehouse = null, $roleid, $userid) {
        $vehicles = Array();
        $vehiclekind = '';
        $list = "vehicle.vehicleid
        ,vehicle.customerno
        ,vehicle.vehicleno
        ,vehicle.kind
        ,vehicle.overspeed_limit
        ,unit.acsensor
        ,unit.is_ac_opp
        ,unit.uid
        ,unit.unitno
        ,devices.deviceid
        ,unit.tempsen1
        ,unit.tempsen2
        ,unit.tempsen3
        ,unit.tempsen4
        ,vehicle.groupid
        ,vehicle.temp1_min
        ,vehicle.temp2_min
        ,vehicle.temp3_min
        ,vehicle.temp4_min
        ,vehicle.temp1_max
        ,vehicle.temp2_max
        ,vehicle.temp3_max
        ,vehicle.temp4_max
        ,unit.analog1
        ,unit.analog2
        ,unit.analog3
        ,unit.analog4
        ,unit.get_conversion
        ,vehicle.lastupdated
        ,devices.ignition
        ,vehicle.curspeed
        ,`group`.groupname
        ,vehicle.stoppage_flag
        ,unit.n1
        ,unit.n2
        ,unit.n3
        ,unit.n4";
        if ($isWarehouse == null) {
            $vehiclekind = ' and vehicle.kind<> "Warehouse"';
        } else {
            $vehiclekind = ' and vehicle.kind="Warehouse"';
        }
        if (isset($roleid) && $this->VehicleUsermappingExists($userid) == true) {
            $Query = 'SELECT ' . $list . ' FROM vehicle
            INNER JOIN unit on unit.vehicleid=vehicle.vehicleid
            INNER JOIN devices on devices.uid=vehicle.uid
            LEFT JOIN `group` ON `group`.groupid = vehicle.groupid';
            if ($groups[0] != 0) {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $userid . " and vehmap.groupid in (%s)";
            } else {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $userid . "";
            }
            $Query .= ' WHERE (vehicle.customerno=%d )
            AND vehicle.isdeleted=0 ' . $vehiclekind . ' and vehmap.isdeleted = 0 and unit.trans_statusid NOT IN (10,22)';
            if ($groups[0] == 0) {
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            } else {
                $group_in = implode(',', $groups);
                $vehiclesQuery = sprintf($Query, $group_in, $this->_Customerno);
            }
        } else {
            $Query = 'SELECT ' . $list . ' FROM vehicle
            INNER JOIN unit on unit.vehicleid=vehicle.vehicleid
            INNER JOIN devices on devices.uid=vehicle.uid
            LEFT JOIN `group` ON `group`.groupid = vehicle.groupid
            WHERE (vehicle.customerno=%d )
            AND vehicle.isdeleted=0 ' . $vehiclekind . ' and unit.trans_statusid NOT IN (10,22)';
            if (isset($groups) && $groups[0] == 0) {
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            } else {
                $group_in = implode(',', $groups);
                $Query .= " AND vehicle.groupid in (%s)";
                $vehiclesQuery = sprintf($Query, $this->_Customerno, $group_in);
            }
        }
        $vehiclesQuery = $vehiclesQuery;
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->customerno = $row['customerno'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->type = $row['kind'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->acsensor = $row['acsensor'];
                $vehicle->is_ac_opp = $row['is_ac_opp'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->tempsen1 = $row['tempsen1'];
                $vehicle->tempsen2 = $row['tempsen2'];
                $vehicle->tempsen1 = $row['tempsen1'];
                $vehicle->tempsen2 = $row['tempsen2'];
                $vehicle->groupid = $row['groupid'];
                $vehicle->temp1_min = $row['temp1_min'];
                $vehicle->temp1_max = $row['temp1_max'];
                $vehicle->temp2_min = $row['temp1_min'];
                $vehicle->temp2_max = $row['temp1_max'];
                $vehicle->temp3_min = $row['temp3_min'];
                $vehicle->temp3_max = $row['temp3_max'];
                $vehicle->temp4_min = $row['temp4_min'];
                $vehicle->temp4_max = $row['temp4_max'];
                $vehicle->analog1 = $row["analog1"];
                $vehicle->analog2 = $row["analog2"];
                $vehicle->analog3 = $row["analog3"];
                $vehicle->analog4 = $row["analog4"];
                $vehicle->get_conversion = $row["get_conversion"];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated_store = $row['lastupdated'];
                    $vehicle->lastupdated = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated_store = $row['lastupdated'];
                    $vehicle->lastupdated = $row['lastupdated'];
                }
                $vehicle->ignition = $row['ignition'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->groupname = $row['groupname'];
                $vehicle->stoppage_flag = $row['stoppage_flag'];
                $vehicle->n1 = $row['n1'];
                $vehicle->n2 = $row['n2'];
                $vehicle->n3 = $row['n3'];
                $vehicle->n4 = $row['n4'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_all_warehouse_by_group($groups) {
        $vehicles = Array();
        $list = "vehicle.vehicleid
        ,vehicle.customerno
        ,vehicle.vehicleno
        ,vehicle.kind
        ,vehicle.overspeed_limit
        ,unit.acsensor
        ,unit.is_ac_opp
        ,unit.uid
        ,unit.unitno
        ,devices.deviceid
        ,unit.tempsen1
        ,unit.tempsen2
        ,unit.tempsen3
        ,unit.tempsen4
        ,vehicle.groupid
        ,vehicle.temp1_min
        ,vehicle.temp2_min
        ,vehicle.temp3_min
        ,vehicle.temp4_min
        ,vehicle.temp1_max
        ,vehicle.temp2_max
        ,vehicle.temp3_max
        ,vehicle.temp4_max
        ,unit.analog1
        ,unit.analog2
        ,unit.analog3
        ,unit.analog4
        ,unit.get_conversion
        ,vehicle.lastupdated
        ,devices.ignition
        ,vehicle.curspeed
        ,`group`.groupname
        ,vehicle.stoppage_flag
        ,unit.n1
        ,unit.n2
        ,unit.n3
        ,unit.n4";
        if ($this->VehicleUsermappingExists($_SESSION['userid']) == true) {
            $Query = 'SELECT ' . $list . ' FROM vehicle
            INNER JOIN unit on unit.vehicleid=vehicle.vehicleid
            INNER JOIN devices on devices.uid=vehicle.uid
            LEFT JOIN `group` ON `group`.groupid = vehicle.groupid
            LEFT JOIN vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child = ' . $this->_Customerno . '';
            if ($groups[0] != 0) {
                $Query .= ' INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = ' . $_SESSION['userid'] . ' and vehmap.groupid in (%s)';
            } else {
                $Query .= ' INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = ' . $_SESSION['userid'] . '';
            }
            $Query .= ' WHERE (vehicle.customerno =%d || vehicle.customerno = vr.parent) AND vehicle.isdeleted=0 and vehmap.isdeleted = 0 and vehicle.kind="Warehouse" and unit.trans_statusid NOT IN (10,22)';
            if ($groups[0] == 0) {
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            } else {
                $group_in = implode(',', $groups);
                $vehiclesQuery = sprintf($Query, $group_in, $this->_Customerno);
            }
        } else {
            $Query = 'SELECT ' . $list . ' FROM vehicle
            INNER JOIN unit on unit.vehicleid=vehicle.vehicleid
            INNER JOIN devices on devices.uid=vehicle.uid
            LEFT JOIN `group` ON `group`.groupid = vehicle.groupid
            LEFT JOIN vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child = ' . $this->_Customerno . '
            WHERE (vehicle.customerno =%d || vehicle.customerno = vr.parent) AND vehicle.isdeleted=0 and vehicle.kind="Warehouse" and unit.trans_statusid NOT IN (10,22)';
            if ($groups[0] == 0) {
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            } else {
                $group_in = implode(',', $groups);
                $Query .= " AND vehicle.groupid in (%s)";
                $vehiclesQuery = sprintf($Query, $this->_Customerno, $group_in);
            }
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->type = $row['kind'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->acsensor = $row['acsensor'];
                $vehicle->is_ac_opp = $row['is_ac_opp'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->tempsen1 = $row['tempsen1'];
                $vehicle->tempsen2 = $row['tempsen2'];
                $vehicle->tempsen3 = $row['tempsen3'];
                $vehicle->tempsen4 = $row['tempsen4'];
                $vehicle->groupid = $row['groupid'];
                $vehicle->temp1_min = $row['temp1_min'];
                $vehicle->temp1_max = $row['temp1_max'];
                $vehicle->temp2_min = $row['temp2_min'];
                $vehicle->temp2_max = $row['temp2_max'];
                $vehicle->temp3_min = $row['temp3_min'];
                $vehicle->temp3_max = $row['temp3_max'];
                $vehicle->temp4_min = $row['temp4_min'];
                $vehicle->temp4_max = $row['temp4_max'];
                $vehicle->analog1 = $row["analog1"];
                $vehicle->analog2 = $row["analog2"];
                $vehicle->analog3 = $row["analog3"];
                $vehicle->analog4 = $row["analog4"];
                $vehicle->get_conversion = $row["get_conversion"];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated_store = $row['lastupdated'];
                    $vehicle->lastupdated = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated_store = $row['lastupdated'];
                    $vehicle->lastupdated = $row['lastupdated'];
                }
                $vehicle->ignition = $row['ignition'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->groupname = $row['groupname'];
                $vehicle->stoppage_flag = $row['stoppage_flag'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_latlng_chkpt($chkid, $vehicleid = null) {
        $vehicleCondition = isset($vehicleid) ? " AND vrm.vehicleid = " . $vehicleid : "";
        $Query = 'SELECT c.checkpointid,c.cgeolat,c.cgeolong,c.cname, rm.rmid, rm.etaStatus
        FROM checkpoint as c
        LEFT OUTER JOIN routeman as rm on rm.checkpointid = c.checkpointid
        LEFT OUTER JOIN vehiclerouteman as vrm on vrm.routeid = rm.routeid
        WHERE c.checkpointid=%d AND c.customerno=%d  AND c.isdeleted=0 AND rm.isdeleted=0 AND vrm.isdeleted=0 ' . $vehicleCondition;
        $vehiclesQuery = sprintf($Query, $chkid, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->checkpointid = $row['checkpointid'];
                $vehicle->lat = $row['cgeolat'];
                $vehicle->long = $row['cgeolong'];
                $vehicle->checkpointname = $row['cname'];
                $vehicle->rmid = $row['rmid'];
                $vehicle->etaStatus = $row['etaStatus'];
                $vehicle->curSpeed = '';
                $vehicle->lastupdated = '';
            }
            return $vehicle;
        }
        return null;
    }

    public function get_chk_for_vehicle($vehicleid, $followRouteSequence) {
        $Query = 'SELECT DISTINCT
                        rm.checkpointid, vrm.routeid, rm.sequence
                    FROM
                        routeman AS rm
                            INNER JOIN
                        checkpoint c ON c.checkpointid = rm.checkpointid
                            INNER JOIN
                        vehiclerouteman AS vrm ON vrm.routeid = rm.routeid
                            AND vrm.vehicleid = %d
                    WHERE
                        c.customerno = %d AND c.isdeleted = 0
                            AND vrm.isdeleted = 0
                            AND rm.sequence > 0
                    ORDER BY sequence ASC';
        $vehiclesQuery = sprintf($Query, $vehicleid, $this->_Customerno);
        $checkpoints = array();
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $retArray = array();
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = array();
                $checkpoints['id'] = $row['checkpointid'];
                $checkpoints['seq'] = $row['sequence'];
                $retArray[] = $checkpoints;
            }
            return $retArray;
        }
        return null;
    }

    public function getChkEtaStatus($vehicleid) {
        $vehicleCondition = isset($vehicleid) ? " AND vrm.vehicleid = " . $vehicleid : "";
        $Query = 'SELECT c.checkpointid,c.cgeolat,c.cgeolong,c.cname, rm.rmid, rm.etaStatus
                FROM vehiclerouteman as vrm
                LEFT OUTER JOIN routeman as rm on rm.routeid = vrm.routeid
                LEFT OUTER JOIN checkpoint as c on c.checkpointid = rm.checkpointid
                INNER JOIN vehicle v ON v.vehicleid = ' . $vehicleid . '
                WHERE vrm.vehicleid = %d
                AND vrm.customerno = %d
                AND rm.etaStatus != ""
                AND vrm.isdeleted = 0 AND rm.isdeleted = 0 AND c.isdeleted = 0
                ORDER BY rm.sequence DESC LIMIT 1';
        $vehiclesQuery = sprintf($Query, $vehicleid, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->checkpointid = $row['checkpointid'];
                $vehicle->lat = $row['cgeolat'];
                $vehicle->long = $row['cgeolong'];
                $vehicle->checkpointname = $row['cname'];
                $vehicle->rmid = $row['rmid'];
                $vehicle->etaStatus = $row['etaStatus'];
                $vehicle->speed = '';
                $vehicle->lastupdated = '';
            }
            return $vehicle;
        }
        return null;
    }

    public function get_all_vehicles_latlng($routeid) {
        $vehicles = Array();
        $ecodecondition = '';
        if (isset($_SESSION['ecodeid']) && $_SESSION['ecodeid'] != 0 && $_SESSION['ecodeid'] != '') {
            $Query = "SELECT vehicle.vehicleid,vehicle.kind,devices.devicelat,unit.get_conversion,devices.directionchange,devices.devicelong,devices.ignition,vehicle.vehicleno,
            vehicle.curspeed,vehicle.lastupdated, vehicle.odometer, vehicle.overspeed_limit, unit.tempsen1, unit.tempsen2, unit.tempsen3, unit.tempsen4, unit.analog1,
        unit.analog2, unit.analog3, unit.analog4, vehicle.temp1_min, vehicle.temp1_max
        FROM `devices`
        INNER JOIN unit ON unit.uid = devices.uid
        INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
        INNER JOIN vehiclerouteman ON vehicle.vehicleid = vehiclerouteman.vehicleid
        INNER JOIN ecodeman ON ecodeman.vehicleid = vehicle.vehicleid
        INNER JOIN elixiacode ON elixiacode.id = ecodeman.ecodeid
        WHERE devices.customerno=%d AND vehicle.isdeleted = 0 AND vehiclerouteman.isdeleted = 0 AND vehiclerouteman.routeid = %d AND unit.trans_statusid NOT IN (10,22) AND elixiacode.ecode = '" . $_SESSION['ecodeid'] . "'";
        } else {
            $Query = "SELECT vehicle.vehicleid,vehicle.kind,devices.devicelat,unit.get_conversion,devices.directionchange,devices.devicelong,devices.ignition,vehicle.vehicleno,
            vehicle.curspeed,vehicle.lastupdated, vehicle.odometer, vehicle.overspeed_limit, unit.tempsen1, unit.tempsen2, unit.tempsen3, unit.tempsen4, unit.analog1,
        unit.analog2, unit.analog3, unit.analog4, vehicle.temp1_min, vehicle.temp1_max
        FROM `devices`
        INNER JOIN unit ON unit.uid = devices.uid
        INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
        INNER JOIN vehiclerouteman ON vehicle.vehicleid = vehiclerouteman.vehicleid
        WHERE devices.customerno=%d AND vehicle.isdeleted = 0 AND vehiclerouteman.isdeleted = 0 AND vehiclerouteman.routeid = %d AND unit.trans_statusid NOT IN (10,22) ";
        }
        if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
        }
        if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
            $vehiclesQuery = sprintf($Query, $this->_Customerno, $routeid, $_SESSION['groupid']);
        } else {
            $vehiclesQuery = sprintf($Query, $this->_Customerno, $routeid);
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->odometer = $row['odometer'];
                $vehicle->type = $row['kind'];
                $vehicle->directionchange = $row["directionchange"];
                $vehicle->lastupdated = $row['lastupdated'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->vehicleno = $row["vehicleno"];
                $vehicle->devicelat = $row['devicelat'];
                $vehicle->devicelong = $row['devicelong'];
                $vehicle->ignition = $row['ignition'];
                $vehicle->get_conversion = $row['get_conversion'];
                $vehicle->tempsen1 = $row['tempsen1'];
                $vehicle->tempsen2 = $row['tempsen2'];
                $vehicle->tempsen3 = $row['tempsen3'];
                $vehicle->tempsen4 = $row['tempsen4'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->analog1 = $row['analog1'];
                $vehicle->analog2 = $row['analog2'];
                $vehicle->analog3 = $row['analog3'];
                $vehicle->analog4 = $row['analog4'];
                $vehicle->temp1_min = $row['temp1_min'];
                $vehicle->temp1_max = $row['temp1_max'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_model($makeid) {
        $vehicles = Array();
        $Query = 'SELECT * FROM model WHERE make_id=%d';
        $vehiclesQuery = sprintf($Query, $makeid);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $option = '';
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $option .= "<option value='" . $row['model_id'] . "'>" . $row['name'] . "</option>";
//                $vehicle = new VOVehicle();
                //                $vehicle->vehicleid = $row['vehicleid'];
                //                //$vehicle->devicekey = $row['devicekey'];
                //                $vehicle->extbatt = $row['extbatt'];
                //                $vehicle->odometer = $row['odometer'];
                //                $vehicle->lastupdated = $row['lastupdated'];
                //                $vehicle->curspeed = $row['curspeed'];
                //                $vehicle->driverid = $row['driverid'];
                //                $vehicle->vehicleno = $row["vehicleno"];
                //                $vehicles[] = $vehicle;
            }
            return $option;
        }
        return null;
    }

    public function get_state_list($nationid) {
        $vehicles = Array();
        $Query = 'SELECT * FROM state WHERE nationid=%d AND isdeleted = 0';
        $vehiclesQuery = sprintf($Query, $nationid);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $option = '';
        $option .= "<option value=''>Select " . $_SESSION['state'] . "</option>";
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $option .= "<option value='" . $row['stateid'] . "'>" . $row['name'] . "</option>";
            }
            return $option;
        }
        return $option;
    }

    public function get_district_list($stateid) {
        $vehicles = Array();
        $Query = 'SELECT * FROM district WHERE stateid=%d AND isdeleted = 0';
        $vehiclesQuery = sprintf($Query, $stateid);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $option = '';
        $option .= "<option value=''>Select " . $_SESSION['district'] . "</option>";
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $option .= "<option value='" . $row['districtid'] . "'>" . $row['name'] . "</option>";
            }
            return $option;
        }
        return $option;
    }

    public function get_city_list($districtid) {
        $vehicles = Array();
        $Query = 'SELECT * FROM city WHERE districtid=%d AND isdeleted = 0';
        $vehiclesQuery = sprintf($Query, $districtid);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $option = '';
        $option .= "<option value=''>Select " . $_SESSION['city'] . "</option>";
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $option .= "<option value='" . $row['cityid'] . "'>" . $row['name'] . "</option>";
            }
            return $option;
        }
        return $option;
    }

    public function get_branch_list($cityid) {
        $vehicles = Array();
        $Query = 'SELECT * FROM `group` WHERE cityid=%d AND isdeleted = 0';
        $vehiclesQuery = sprintf($Query, $cityid);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $option = '';
        $option .= "<option value=''>Select " . $_SESSION['group'] . "</option>";
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $option .= "<option value='" . $row['groupid'] . "'>" . $row['groupname'] . "</option>";
            }
            return $option;
        }
        return $option;
    }

    public function get_models($makeid) {
        $vehicles = Array();
        $Query = 'SELECT * FROM model WHERE make_id=%d';
        $vehiclesQuery = sprintf($Query, $makeid);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $option = '';
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->model_id = $row['model_id'];
                $vehicle->name = $row['name'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_models_bymodelid($modelid) {
        $vehicles = Array();
        $Query = 'SELECT name FROM model WHERE model_id=%d';
        $vehiclesQuery = sprintf($Query, $modelid);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $option = '';
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $modelname = $row['name'];
            }
            return $modelname;
        }
        return null;
    }

    public function get_make() {
        $vehicles = Array();
        $Query = 'SELECT * FROM make WHERE customerno IN(0,%d) AND isdeleted=0';
        $vehiclesQuery = sprintf($Query, $_SESSION['customerno']);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->id = $row['id'];
//$vehicle->devicekey = $row['devicekey'];
                $vehicle->name = $row['name'];
                $vehicle->customerno = $row['customerno'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_makebyid($makeid) {
        //$vehicles = Array();
        $Query = 'SELECT name FROM make WHERE customerno IN(0,%d) AND id=' . $makeid . ' AND isdeleted=0';
        $vehiclesQuery = sprintf($Query, $_SESSION['customerno']);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                //$vehicle = new VOVehicle();
                $makename = $row['name'];
                //$vehicle->devicekey = $row['devicekey'];
                // $vehicle->name = $row['name'];
                //   $vehicle->customerno = $row['customerno'];
                //  $vehicles[] = $vehicle;
            }
            return $makename;
        }
        return null;
    }

    public function get_branch($branchid) {
//$vehicles = Array();
        $Query = 'SELECT city.name,group.code,group.address FROM `group` INNER JOIN city on city.cityid = group.cityid WHERE groupid=%d AND group.isdeleted = 0 AND city.isdeleted =0';
        $vehiclesQuery = sprintf($Query, $branchid);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = "<br><span class='add-on'>" . $_SESSION['city'] . "</span><input type='text' value='" . $row['name'] . "' readonly> <span class='add-on'>Code</span><input type='text' value='" . $row['code'] . "' readonly> <span class='add-on'>Address</span> <textarea readonly>" . $row['address'] . "</textarea>";
//                $vehicle = new VOVehicle();
                //                $vehicle->groupidid = $row['id'];
                //                //$vehicle->devicekey = $row['devicekey'];
                //                $vehicle->name = $row['name'];
                //                $vehicles[] = $vehicle;
            }
            return $vehicle;
        }
        return null;
    }

    public function get_vehicles_bygroupid_all() {
        $vehicles = Array();
        $Query = 'SELECT * FROM vehicle WHERE customerno=%d AND isdeleted=0';
//if($_SESSION['groupid'] != '0')
        //$Query.=" AND vehicle.groupid =%d";
        //else
        $Query .= " AND vehicle.groupid =0";
//if($_SESSION['groupid'] != '0')
        //$vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        //else
        $vehiclesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
//$vehicle->devicekey = $row['devicekey'];
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->odometer = $row['odometer'];
                $vehicle->lastupdated = $row['lastupdated'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->vehicleno = $row["vehicleno"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_groups_vehicles($groupid) {
        $vehicles = Array();
//        $Query = 'SELECT * FROM vehicle WHERE customerno=%d AND isdeleted=0 AND vehicle.groupid =%d';
        //      $Query = 'SELECT * FROM vehicle WHERE uid!=0 and customerno in (%s) AND isdeleted=0 AND vehicle.groupid in (%s)';
        /* */
        $Query = 'SELECT v.vehicleid, v.vehicleno, v.customerno, v.groupid, g.groupname, g.code as g_code,
                    g.userid as g_userid, g.cityid, c.code as city_code, c.name as city_name, c.userid as city_userid,
                    c.districtid, d.code as dist_code, d.name as dist_name, d.userid as dist_userid, v.uid, v.extbatt,
                    v.odometer, v.lastupdated, v.curspeed, v.driverid, v.average
                    FROM vehicle as v
                    left outer join `group` as g on v.groupid = g.groupid
                    left outer join city as c on c.cityid=g.cityid
                    left outer join district as d on d.districtid = c.districtid
                    WHERE v.customerno in (%s) AND v.uid!=0
                    AND v.isdeleted=0
                    AND COALESCE(g.isdeleted, 0)=0
                    AND COALESCE(c.isdeleted, 0)=0
                    AND COALESCE(d.isdeleted, 0)=0';
        if (isset($groupid) && $groupid != "") {
            $Query .= " AND v.groupid in (%s) ";
            $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($groupid));
        } else {
            $Query .= " AND v.groupid in (0) ";
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row["vehicleno"];
                $vehicle->customerno = $row["customerno"];
                $vehicle->groupid = $row["groupid"];
                $vehicle->groupname = $row["groupname"];
                $vehicle->groupcode = $row["g_code"];
                $vehicle->g_userid = $row["g_userid"];
                $vehicle->cityid = $row["cityid"];
                $vehicle->citycode = $row["city_code"];
                $vehicle->cityname = $row["city_name"];
                $vehicle->cityuserid = $row["city_userid"];
                $vehicle->districtid = $row["districtid"];
                $vehicle->districtcode = $row["dist_code"];
                $vehicle->districtname = $row["dist_name"];
                $vehicle->districtuserid = $row["dist_userid"];
                $vehicle->uid = $row['uid'];
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->odometer = $row['odometer'];
                $vehicle->lastupdated = $row['lastupdated'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->vehicleno = $row["vehicleno"];
                $vehicle->average = $row["average"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_groups_vehicles_withlatlong($groupid) {
        $vehicles = Array();
        $sortlist = '';
        if (isset($_SESSION['use_maintenance']) && isset($_SESSION['switch_to']) && $_SESSION['use_maintenance'] == '1' && $_SESSION['switch_to'] == 1) {
            $sortlist = sprintf(" AND vehicle.uid = 0 ");
        } elseif (isset($_SESSION['switch_to']) && $_SESSION['switch_to'] == "3") {
            $sortlist = sprintf(" AND vehicle.kind = 'Warehouse' ");
        } else {
            $sortlist = sprintf(" AND vehicle.kind <> 'Warehouse' ");
        }
        $Query = '  SELECT  vehicleid
                            ,vehicleno
                            ,groupid
                            ,devicelat
                            ,devicelong
                            ,deviceid
                    FROM    vehicle
                    Inner join devices on devices.uid = vehicle.uid
                    WHERE   vehicle.customerno = %d
                    AND     isdeleted = 0
                    AND     vehicle.groupid = %d' . $sortlist;
        $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($groupid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->groupid = $row["groupid"];
                $vehicle->cgeolat = $row["devicelat"];
                $vehicle->cgeolong = $row["devicelong"];
                $vehicle->deviceid = $row["deviceid"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function GetAllVehicles_withlatlong() {
        $vehicles = Array();
        $Query = 'SELECT * FROM vehicle
        Inner join devices on devices.uid = vehicle.uid
        WHERE vehicle.customerno=%d AND isdeleted=0';
        $vehiclesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->groupid = $row["groupid"];
                $vehicle->cgeolat = $row["devicelat"];
                $vehicle->cgeolong = $row["devicelong"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_groups_vehicles_ecode() {
        $vehicles = Array();
        $Query = 'SELECT * FROM vehicle
        INNER JOIN ecodeman ON ecodeman.vehicleid=vehicle.vehicleid
        WHERE ecodeman.customerno=%d AND ecodeman.ecodeid=%d AND vehicle.isdeleted=0';
        $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($_SESSION['e_id']));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->uid = $row['uid'];
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->odometer = $row['odometer'];
                $vehicle->lastupdated = $row['lastupdated'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->vehicleno = $row["vehicleno"];
                $vehicle->average = $row["average"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_all_vehiclesbyheirarchy() {
        $vehicles = Array();
        $Query = "SELECT * FROM vehicle INNER JOIN unit ON unit.uid = vehicle.uid LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid";
        if ($_SESSION["roleid"] == '2') {
            $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid
            LEFT OUTER JOIN `district` ON `district`.districtid = city.districtid
            LEFT OUTER JOIN `state` ON `state`.stateid = district.stateid ";
        }
        if ($_SESSION["roleid"] == '3') {
            $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid
            LEFT OUTER JOIN `district` ON `district`.districtid = city.districtid ";
        }
        if ($_SESSION["roleid"] == '4') {
            $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid ";
        }
        $Query .= " WHERE vehicle.customerno=%d AND vehicle.isdeleted=0 ";
        $vehiclesQuery = sprintf($Query, $this->_Customerno);
        $heir_query = "";
        if ($_SESSION['roleid'] == '2') {
            $heir_query = sprintf(" AND state.stateid = %d ", $_SESSION['heirarchy_id']);
        }
        if ($_SESSION['roleid'] == '3') {
            $heir_query = sprintf(" AND district.districtid = %d ", $_SESSION['heirarchy_id']);
        }
        if ($_SESSION['roleid'] == '4') {
            $heir_query = sprintf(" AND city.cityid = %d ", $_SESSION['heirarchy_id']);
        }
        $vehiclesQuery .= $heir_query;
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->uid = $row['uid'];
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->odometer = $row['odometer'];
                $vehicle->lastupdated = $row['lastupdated'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->vehicleno = $row["vehicleno"];
                $vehicle->groupid = $row['groupid'];
                $vehicle->city = isset($row['cityid']) ? $row['cityid'] : '';
                $vehicle->district = isset($row['districtid']) ? $row['districtid'] : '';
                $vehicle->state = isset($row['stateid']) ? $row['stateid'] : '';
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getFuelConsumed($vehicleid, $sqlitedate, $start, $current, $fuel, $fuelque) {
        $dt = 0;
        $fuelid = $fuelque;
        $average = getAverage($vehicleid);
        $travel = ($current - $start) / 1000;
        $sqlitedate1 = new DateTime($sqlitedate);
        $sqlitedate1->modify('+1 minutes');
        $sqlitedate2 = $sqlitedate1->format("Y-m-d H:i:s");
        $Query = "SELECT * FROM fuelstorrage Where customerno=%d AND vehicleid=%d AND addedon BETWEEN '%s' AND '%s'";
        $SQL = sprintf($Query, $this->_Customerno, $vehicleid, $sqlitedate, $sqlitedate2);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
//echo "ok";
            while ($row = $this->_databaseManager->get_nextRow()) {
                $check = explode('-', $fuelid);
                if (!in_array($row['fuelid'], $check)) {
                    $dt = $fuel + $row['fuel'];
                    $fuel = $dt;
                    $fuelid = $fuelid . '-' . $row['fuelid'];
                }
            }
            return array($dt, $fuelid);
        } else {
            $consume = $travel / $average;
            if ($fuel > 0) {
                $dt = $fuel - $consume;
            } else {
                $dt = $dt;
            }
            return array($dt, $fuelid);
        }
    }

    public function getAllFuelRecords() {
        $fuels = array();
        $Query = "SELECT *,fuelstorrage.dealerid as deal from fuelstorrage
        inner join vehicle on vehicle.vehicleid = fuelstorrage.vehicleid
        inner join dealer on dealer.dealerid  = fuelstorrage.dealerid
        where fuelstorrage.customerno=%d ";
        if ($_SESSION['groupid'] != '0') {
            $Query .= " AND vehicle.groupid = %d ";
        }
        $Query .= " Order by addedon DESC limit 3";
        if ($_SESSION['groupid'] != '0') {
            $SQL = sprintf($Query, $_SESSION['customerno'], $_SESSION['groupid']);
        } else {
            $SQL = sprintf($Query, $_SESSION['customerno']);
        }
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $fuel = new VOVehicle();
                $fuel->fuelid = $row['fuelid'];
                $fuel->fuel = $row['fuel'];
                $fuel->vehicleid = $row['vehicleid'];
                $fuel->vehicleno = $row['vehicleno'];
                $fuel->amount = $row['amount'];
                $fuel->rate = $row['rate'];
                $fuel->refno = $row['refno'];
                $fuel->openingkm = $row['openingkm'];
                $fuel->dealerid = $row['deal'];
                $fuel->dealername = $row['name'];
                $fuel->addedon = $row['addedon'];
                $fuels[] = $fuel;
            }
        }
        return $fuels;
    }

    public function getAllFilterdFuelRecords($vehicleno = null, $dealerid, $sdate, $edate, $sortdata_arr = NULL) {
        $searcharray = array();
        if (isset($sortdata_arr)) {
            $sortorder = isset($sortdata_arr['sortorder']) ? $sortdata_arr['sortorder'] : '';
            $sortcol = isset($sortdata_arr['sortcol']) ? $sortdata_arr['sortcol'] : '';
            $vehiclenof = isset($sortdata_arr['vehiclenof']) ? $sortdata_arr['vehiclenof'] : '';
            $transactionidf1 = isset($sortdata_arr['transactionidf']) ? $sortdata_arr['transactionidf'] : '';
            $transactionidf = substr(trim($transactionidf1), 4);
            $seatcapacityf = $sortdata_arr['seatcapacityf'];
            $fuelf = $sortdata_arr['fuelf'];
            $amountf = $sortdata_arr['amountf'];
            $ratef = $sortdata_arr['ratef'];
            $refnof = $sortdata_arr['refnof'];
            $startkmf = $sortdata_arr['startkmf'];
            $endkmf = $sortdata_arr['endkmf'];
            $netkmf = $sortdata_arr['netkmf'];
            $averagef = $sortdata_arr['averagef'];
            $dealerf = $sortdata_arr['dealerf'];
            $datef = $sortdata_arr['datef'];
            $srnof = $sortdata_arr['srnof'];
            $addamountof = $sortdata_arr['addamtf'];
            $notesof = $sortdata_arr['notesf'];
            $ofasnumberf = $sortdata_arr['ofasnumberf'];
            $chequenof = $sortdata_arr['chequenof'];
            $chequeamtf = $sortdata_arr['chequeamountf'];
            $chequedatef = $sortdata_arr['chequedatef'];
            $tdsamtf = $sortdata_arr['tdsamountf'];
            $sortcolumn = "";
            $sortcolumn1 = "";
            if (!empty($sortcol) && !empty($sortorder) && $sortorder != 'undefined') {
                if ($sortcol == 1) {
                    $sortcolumn = "vehicleno";
                    $sortcolumn1 = "vehicle.vehicleno";
                } elseif ($sortcol == 2) {
                    $sortcolumn = "fuelid";
                    $sortcolumn1 = "fuelstorrage.fuelid";
                } elseif ($sortcol == 3) {
                    $sortcolumn = "seatcapacity";
                    $sortcolumn1 = "de.seatcapacity";
                } elseif ($sortcol == 4) {
                    $sortcolumn = "fuel";
                    $sortcolumn1 = "fuelstorrage.fuel";
                } elseif ($sortcol == 5) {
                    $sortcolumn = "amount";
                    $sortcolumn1 = "fuelstorrage.amount";
                } elseif ($sortcol == 6) {
                    $sortcolumn = "rate";
                    $sortcolumn1 = "fuelstorrage.rate";
                } elseif ($sortcol == 7) {
                    $sortcolumn = "refno";
                    $sortcolumn1 = "fuelstorrage.refno";
                } elseif ($sortcol == 8) {
                    $sortcolumn = "openingkm";
                    $sortcolumn1 = " fuelstorrage.openingkm ";
                } elseif ($sortcol == 9) {
                    $sortcolumn = "endingkm";
                    $sortcolumn1 = " fuelstorrage.endingkm ";
                } elseif ($sortcol == 10) {
//arrray sort
                    $sortcolumn = "netkm";
                } elseif ($sortcol == 11) {
////arrray sort
                    $sortcolumn = "average";
                } elseif ($sortcol == 12) {
                    $sortcolumn = "dealername";
                    $sortcolumn1 = "dealer.name";
                } elseif ($sortcol == 13) {
                    $sortcolumn = "addedon";
                    $sortcolumn1 = "fuelstorrage.addedon";
                } elseif ($sortcol == 14) {
                    $sortcolumn = "additional_amount";
                    $sortcolumn1 = "fuelstorrage.additional_amount";
                } elseif ($sortcol == 15) {
                    $sortcolumn = "notes";
                    $sortcolumn1 = "fuelstorrage.notes";
                } elseif ($sortcol == 16) {
                    $sortcolumn = "ofasnumber";
                    $sortcolumn1 = "fuelstorrage.ofasnumber";
                } elseif ($sortcol == 17) {
                    $sortcolumn = "chequeno";
                    $sortcolumn1 = "fuelstorrage.chequeno";
                } elseif ($sortcol == 18) {
                    $sortcolumn = "chequeamt";
                    $sortcolumn1 = "fuelstorrage.chequeamt";
                } elseif ($sortcol == 19) {
                    $sortcolumn = "chequedate";
                    $sortcolumn1 = "fuelstorrage.chequedate";
                } elseif ($sortcol == 20) {
                    $sortcolumn = "tdsamt";
                    $sortcolumn1 = "fuelstorrage.tdsamt";
                } elseif ($sortcol == 0) {
                    //arrray sort
                    $sortcolumn = "srno";
                }
            }
        }
        if ($vehicleno != null || isset($vehicleno)) {
            $vehi = $this->get_all_vehicles_byId($vehicleno);
            if (isset($vehi[0])) {
                $vehicleid = $vehi[0]->vehicleid;
            } else {
                $vehicleid = null;
            }
        } else {
            $vehicleid = null;
        }
        $fuels = array();
        if ($sdate != '') {
            $sdate = date('Y-m-d H:i:s', $sdate);
        }
        if ($edate != '') {
            $edate = date('Y-m-d H:i:s', $edate);
        }
//echo $vehicleid;
        $Query = "SELECT vehicle.vehicleid,dealer.name,fuelstorrage.refno,fuelstorrage.rate,fuelstorrage.additional_amount,fuelstorrage.notes,
            fuelstorrage.ofasnumber,
            fuelstorrage.chequeno,
            fuelstorrage.chequeamt,
            fuelstorrage.chequedate,
            fuelstorrage.tdsamt,
            fuelstorrage.amount,fuelstorrage.fuel,fuelstorrage.addedon,de.seatcapacity,fuelstorrage.fuelid,
            vehicle.vehicleno,vehicle.groupid,vehicle.vehicleno,fuelstorrage.openingkm ,fuelstorrage.endingkm ,fuelstorrage.dealerid as deal,
            de.seatcapacity from fuelstorrage
        inner join vehicle on vehicle.vehicleid = fuelstorrage.vehicleid
        left outer join description as de ON vehicle.vehicleid = de.vehicleid
        left join dealer on dealer.dealerid  = fuelstorrage.dealerid
        where fuelstorrage.customerno= " . $_SESSION['customerno'] . " AND fuelstorrage.isdeleted=0";
        if ($vehicleid !== null) {
            $Query .= " AND fuelstorrage.vehicleid = $vehicleid ";
        }
        if ($dealerid != '-1' && $dealerid != 0) {
            $dealerid = (int) $dealerid;
            $Query .= " AND fuelstorrage.dealerid = $dealerid ";
        }
        if ($dealerid == 0) {
            $dm = new DealerManager($_SESSION['customerno']);
            $alldealer = $dm->get_all_dealers();
            if (isset($alldealer)) {
                $dealerarr = array();
                foreach ($alldealer as $alldealers) {
                    $dealerarr[] = $alldealers->dealerid;
                }
                $dealerstr = implode(",", $dealerarr);
                $Query .= sprintf(" AND fuelstorrage.dealerid IN (" . $dealerstr . ")");
            }
        }
        if ($_SESSION['groupid'] != '0') {
            $Query .= " AND vehicle.groupid ='" . $_SESSION['groupid'] . "' ";
        }
        if ($sdate != '' && $edate != '') {
            $Query .= " AND fuelstorrage.addedon between '$sdate' and '$edate'";
        }
        if (isset($sortdata_arr)) {
            if (!empty($vehiclenof) || $vehiclenof != "") {
                $Query .= " AND vehicle.vehicleno LIKE '%$vehiclenof%' ";
            }
            if (!empty($transactionidf) || $transactionidf != "") {
                $Query .= " AND fuelstorrage.fuelid LIKE '%$transactionidf%' ";
            }
            if (!empty($seatcapacityf) || $seatcapacityf != "") {
                $Query .= " AND de.seatcapacity LIKE '%$seatcapacityf%' ";
            }
            if (!empty($seatcapacityf) || $seatcapacityf != "") {
                $Query .= " AND de.seatcapacity LIKE '%$seatcapacityf%' ";
            }
            if (!empty($fuelf) || $fuelf != "") {
                $Query .= " AND  fuelstorrage.fuel LIKE '%$fuelf%' ";
            }
            if (!empty($amountf) || $amountf != "") {
                $Query .= " AND  fuelstorrage.amount LIKE '%$amountf%' ";
            }
            if (!empty($ratef) || $ratef != "") {
                $Query .= " AND  fuelstorrage.rate LIKE '%$ratef%' ";
            }
            if (!empty($refnof) || $refnof != "") {
                $Query .= " AND  fuelstorrage.refno LIKE '%$refnof%' ";
            }
            if (!empty($startkmf) || $startkmf != "") {
                $Query .= " AND  fuelstorrage.openingkm LIKE '%$startkmf%' ";
            }
            if (!empty($endkmf) || $endkmf != "") {
                $Query .= " AND  fuelstorrage.endingkm LIKE '%$endkmf%' ";
            }
            if (!empty($datef) || $datef != "") {
                $datef = date('Y-m-d', $datef);
                $Query .= " AND  fuelstorrage.addedon= '" . $datef . "' ";
            }
            if (!empty($dealerf) || $dealerf != "") {
                $Query .= " AND  dealer.name LIKE '%$dealerf%' ";
            }
            if (!empty($addamountof) || $addamountof != "") {
                $Query .= " AND  fuelstorrage.additional_amount LIKE '%$addamtof%' ";
            }
            if (!empty($notesof) || $notesof != "") {
                $Query .= " AND  fuelstorrage.notes LIKE '%$notesof%' ";
            }
            if (!empty($ofasnumberf) || $ofasnumberf != "") {
                $Query .= " AND  fuelstorrage.ofasnumber LIKE '%$ofasnumberf%' ";
            }
            if (!empty($chequenof) || $chequenof != "") {
                $Query .= " AND  fuelstorrage.chequeno LIKE '%$chequenof%' ";
            }
            if (!empty($chequeamtf) || $chequeamtf != "") {
                $Query .= " AND  fuelstorrage.chequeamt LIKE '%$chequeamtf%' ";
            }
            if (!empty($chequedatef) || $chequedatef != "") {
                $Query .= " AND  fuelstorrage.chequedate LIKE '%$chequedatef%' ";
            }
            if (!empty($tdsamtf) || $tdsamtf != "") {
                $Query .= " AND  fuelstorrage.tdsamt LIKE '%$tdsamtf%'";
            }
            if ($sortcolumn != "" && $sortorder != "" && $sortcolumn1 != "") {
                $fuelscols = array(0, 10, 11);
                $varcols = array(8, 9);
                if (!in_array($sortcol, $fuelscols)) {
//convert(fuelstorrage.openingkm, decimal)
                    if (in_array($sortcol, $varcols)) {
                        $Query .= " ORDER BY convert(" . $sortcolumn1 . ",decimal) " . strtoupper($sortorder);
                    } else {
                        $Query .= " ORDER BY " . $sortcolumn1 . " " . strtoupper($sortorder);
                    }
                } else {
                    $Query .= " ORDER BY addedon DESC ";
                }
            } else {
                $Query .= " ORDER BY addedon DESC ";
            }
        } else {
            $Query .= " ORDER BY addedon DESC ";
        }
        $SQL = $Query;
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $totalfuel = 0;
            $totalamount = 0;
            $totalnet = 0;
            $totalavg = 0;
            $row_count = $this->_databaseManager->get_rowCount();
            $consolidated_avg = 0;
            $srno = 0;
            while ($row = $this->_databaseManager->get_nextRow()) {
                $srno++;
                $fuel = new VOVehicle();
                $fuel->srno = $srno;
                $fuel->fuelid = $row['fuelid'];
                $fuel->transid = "FU00" . $fuel->fuelid;
                $fuel->fuel = $row['fuel'];
                $fuel->vehicleid = $row['vehicleid'];
                $fuel->vehicleno = $row['vehicleno'];
                $fuel->amount = $row['amount'];
                $fuel->additional_amount = $row['additional_amount'];
                $fuel->notes = $row['notes'];
                $fuel->rate = $row['rate'];
                $fuel->refno = $row['refno'];
                $fuel->openingkm = $row['openingkm'];
                $fuel->endingkm = $row['endingkm'];
                $fuel->average = number_format(round(($row['endingkm'] - $row['openingkm']) / $row['fuel'], 2), 2); //$row['average'];
                $fuel->dealerid = $row['deal'];
                $fuel->dealername = $row['name'];
                $fuel->addedon = $row['addedon'];
                $fuel->netkm = ($fuel->endingkm) - ($fuel->openingkm);
                $fuel->seatcapacity = $row['seatcapacity'];
                $fuel->ofasnumber = $row['ofasnumber'];
                $fuel->chequeno = $row['chequeno'];
                $fuel->chequeamt = $row['chequeamt'];
                $fuel->chequedate = $row['chequedate'];
                $fuel->tdsamt = $row['tdsamt'];
                $totalfuel = $totalfuel + $fuel->fuel;
                $totalamount = $totalamount + $fuel->amount;
                $totalamount = $totalamount + $fuel->additional_amount;
                $totalnet = $totalnet + $fuel->netkm;
                $totalavg = $totalavg + $fuel->average;
                $fuels[] = $fuel;
            }
            if ($row_count > 0) {
                $consolidated_avg = round($totalavg / $row_count, 2);
            }
            $totfueldata = array(
                "totalfuel" => $totalfuel,
                "totalamount" => $totalamount,
                "totalnet" => $totalnet,
                "consolavg" => $consolidated_avg
            );
        }
        $totfueldata = isset($totfueldata) ? $totfueldata : array();
        if (isset($sortdata_arr)) {
            if ($sortcolumn == "netkm" || $sortcolumn == "average" || $sortcolumn == "srno") {
                if ($sortorder == 'asc' && $sortcolumn == "netkm") {
                    usort($fuels, array($this, "netkmasc"));
                }
                if ($sortorder == 'desc' && $sortcolumn == "netkm") {
                    usort($fuels, array($this, "netkmdesc"));
                }
                if ($sortorder == 'asc' && $sortcolumn == "average") {
                    usort($fuels, array($this, "averageasc"));
                }
                if ($sortorder == 'desc' && $sortcolumn == "average") {
                    usort($fuels, array($this, "averagedesc"));
                }
                if ($sortorder == 'asc' && $sortcolumn == "srno") {
                    usort($fuels, array($this, "srnoasc"));
                }
                if ($sortorder == 'desc' && $sortcolumn == "srno") {
                    usort($fuels, array($this, "srnodesc"));
                }
            }
            if (!empty($averagef) || $averagef != "") {
                $srarr = array_filter($fuels, function ($transporterElement) use ($averagef) {
                    return $transporterElement->average == $averagef;
                });
            } elseif (!empty($netkmf) || $netkmf != "") {
                $srarr = array_filter($fuels, function ($transporterElement) use ($netkmf) {
                    return $transporterElement->netkm == $netkmf;
                });
            } elseif (!empty($srnof) || $srnof != "") {
                $srarr = array_filter($fuels, function ($transporterElement) use ($srnof) {
                    return $transporterElement->srno == $srnof;
                });
            }
            if (!empty($srarr)) {
                $fuels = $srarr;
            }
        }
        $result = array($fuels, $totfueldata);
        return $result;
    }

    public function netkmasc($a, $b) {
        return strnatcmp($a->netkm, $b->netkm); // ASC array // use with usort
    }

    public function netkmdesc($a, $b) {
        return strnatcmp($b->netkm, $a->netkm); // DESC array // use with usort
    }

    public function averageasc($a, $b) {
        return strnatcmp($a->average, $b->average); // ASC array // use with usort
    }

    public function averagedesc($a, $b) {
        return strnatcmp($b->average, $a->average); // DESC array // use with usort
    }

    public function srnoasc($a, $b) {
        return strnatcmp($a->srno, $b->srno); // ASC array // use with usort
    }

    public function srnodesc($a, $b) {
        return strnatcmp($b->srno, $a->srno); // DESC array // use with usort
    }

    public function getAverage($vehicleid) {
        $Query = "SELECT vehicleid, average FROM vehicle WHERE customerno=%d AND vehicleid = %d ";
        $SQL = sprintf($Query, $this->_Customerno, $vehicleid);
        $this->_databaseManager->executeQuery($SQL);
        $row = $this->_databaseManager->get_nextRow();
        $average = $row['average'];
        return $average;
    }

    public function get_Fuel_Refill($vehicleid, $date) {
        $Query = "SELECT SUM(fuel) as fuel FROM fuelstorrage where customerno=%d AND vehicleid=%d AND DATE(addedon)='%s'";
        $SQL = sprintf($Query, $this->_Customerno, $vehicleid, $date);
        $this->_databaseManager->executeQuery($SQL);
        $row = $this->_databaseManager->get_nextRow();
        $fuel = $row['fuel'];
        if ($fuel != '') {
            return $fuel;
        } else {
            return 0;
        }
    }

    public function getVehicleName($vehicleid) {
        $Query = "SELECT vehicleno FROM vehicle WHERE customerno=%d AND vehicleid = %d ";
        $SQL = sprintf($Query, $this->_Customerno, $vehicleid);
        $this->_databaseManager->executeQuery($SQL);
        $row = $this->_databaseManager->get_nextRow();
        $vehicleno = $row['vehicleno'];
        return $vehicleno;
    }

    public function getRemark($remarkid) {
        $Query = "SELECT name FROM " . DB_PARENT . ".remarks WHERE id=%d";
        $SQL = sprintf($Query, $remarkid);
        $this->_databaseManager->executeQuery($SQL);
        $row = $this->_databaseManager->get_nextRow();
        $vehicleno = $row['name'];
        return $vehicleno;
    }

    public function getVehicleNameByCustomer($vehicleid, $customerno) {
        $Query = "SELECT vehicleno FROM vehicle WHERE customerno=%d AND vehicleid = %d ";
        $SQL = sprintf($Query, $customerno, $vehicleid);
        $this->_databaseManager->executeQuery($SQL);
        $row = $this->_databaseManager->get_nextRow();
        $vehicleno = $row['vehicleno'];
        return $vehicleno;
    }

    public function GetAllVehicles() {
        $vehicles = Array();
        if (isset($_SESSION['ecodeid'])) {
            $Query = 'SELECT * FROM vehicle
            INNER JOIN ecodeman ON ecodeman.vehicleid=vehicle.vehicleid
            WHERE ecodeman.customerno=%d AND ecodeman.ecodeid=%d AND vehicle.isdeleted=0';
            $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($_SESSION['e_id']));
        } else {
            $Query = 'SELECT * from vehicle where customerno=%d AND isdeleted=0';
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->groupid = $row['groupid'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return NULL;
    }

    public function Get_All_Vehicles_SQLite() {
        $vehilces = array();
        if ($_SESSION['groupid'] != '0') {
            $Query = 'SELECT * from vehicle where customerno=%d AND isdeleted=0 AND groupid = %d Order by vehicleid ASC';
            $vehilcesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $Query = 'SELECT * from vehicle where customerno=%d AND isdeleted=0  Order by vehicleid ASC';
            $vehilcesQuery = sprintf($Query, $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($vehilcesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $id = $row['vehicleid'];
                $vehilces[$id]['vehicleno'] = $row['vehicleno'];
                $vehilces[$id]['groupid'] = $row['groupid'];
            }
            return $vehilces;
        }
        return NULL;
    }

    public function Get_All_Vehicles_infoheirarchy() {
        $vehicles = array();
        if ($_SESSION['groupid'] != '0') {
            $Query = 'SELECT * from vehicle where customerno=%d AND isdeleted=0 AND groupid = %d Order by vehicleid ASC';
            $vehilcesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $Query = 'SELECT a.vehicleid, a.vehicleno, a.groupid, b.groupname, b.cityid, c.name as city_name, c.districtid, d.name, d.stateid, e.name   from vehicle as a
            left join `group` as b on a.groupid=b.groupid
            left join city as c on b.cityid=c.cityid
            left join district as d on d.districtid=c.districtid
            left join state as e on e.stateid=d.stateid
            where a.customerno=%d AND a.isdeleted=0 Order by a.vehicleid ASC';
            $vehilcesQuery = sprintf($Query, $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($vehilcesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $id = $row['vehicleid'];
                $vehicles[$id]['vehicleno'] = $row['vehicleno'];
                $vehicles[$id]['groupid'] = $row['groupid'];
                $vehicles[$id]['groupname'] = $row['groupname'];
                $vehicles[$id]['cityid'] = $row['cityid'];
                $vehicles[$id]['city_name'] = $row['city_name'];
            }
            return $vehicles;
        }
        return NULL;
    }

    public function getConsumedFuel($id, $totaldist, $date) {
        $vehicles = Array();
        $Query = "SELECT sum(fuel) AS fuel, vehicleid FROM fuelstorrage where customerno=%d AND vehicleid =%d AND DATE(addedon)='%s' ";
        $SQL = sprintf($Query, $this->_Customerno, $id, $date);
        $this->_databaseManager->executeQuery($SQL);
        $row = $this->_databaseManager->get_nextRow();
        $average = $this->getAverage($id);
        if ($average) {
            $consumed = $totaldist / $average;
//$bal = $row['fuel']-$consumed;
            //$vehicle = new VOVehicle();
            //$vehicle->start = $row['fuel'];
            //$vehicle->end = $bal;
            //$vehicles[] = $vehicle;
        }
//return $vehicles;
        return $row['fuel'];
    }

    public function Get_All_Vehicles_SQLite_ByGroup($groupid) {
        $vehilces = array();
        if ($groupid == "0") {
            $Query = 'SELECT * from vehicle where customerno=%d AND isdeleted=0';
            $vehilcesQuery = sprintf($Query, $this->_Customerno);
        } else {
            $Query = 'SELECT * from vehicle where customerno=%d AND groupid=%d AND isdeleted=0';
            $vehilcesQuery = sprintf($Query, $this->_Customerno, $groupid);
        }
        $this->_databaseManager->executeQuery($vehilcesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $id = $row['vehicleid'];
                $vehilces[$id]['vehicleno'] = $row['vehicleno'];
            }
            return $vehilces;
        }
        return NULL;
    }

    public function vehicles_acsensor() {
        $devices = Array();
        $Query = "SELECT * FROM `devices`
        INNER JOIN unit ON unit.uid = devices.uid
        INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
        INNER JOIN driver ON vehicle.driverid = driver.driverid
        where devices.customerno=%d AND unit.acsensor=1 AND unit.trans_statusid NOT IN (10,22) ORDER BY devices.lastupdated ASC";
        $devicesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                if ($row['uid'] > 0) {
                    if ($row['devicelat'] > 0 & $row['devicelong'] > 0) {
                        $id = $row['vehicleid'];
                        $vehilces[$id]['vehicleno'] = $row['vehicleno'];
                    }
                }
            }
            return $vehilces;
        }
        return null;
    }

    public function get_all_vehicles_with_tempsensor() {
        $vehicles = Array();
        $Query = 'SELECT vehicle.vehicleid,vehicle.vehicleno,articlemanage.artid FROM vehicle
        INNER JOIN unit ON unit.uid = vehicle.uid LEFT OUTER JOIN articlemanage ON articlemanage.vehicleid = vehicle.vehicleid
        WHERE vehicle.customerno=%d AND vehicle.isdeleted=0 AND unit.trans_statusid NOT IN (10,22) AND (unit.tempsen1 > 0 OR unit.tempsen2 > 0)';
        $vehiclesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row["vehicleno"];
                $vehicle->artid = $row['artid'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getgroupsfromstateid($stateid) {
        $users = Array();
        $usersQuery = sprintf("SELECT groupid FROM `group`
            INNER JOIN city ON city.cityid = `group`.cityid
            INNER JOIN district ON district.districtid = city.districtid
            INNER JOIN state ON state.stateid = district.stateid
            WHERE group.isdeleted = 0 AND state.stateid = %d", Sanitise::Long($stateid));
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $users[] = $row['groupid'];
            }
            return $users;
        }
        return null;
    }

    public function getgroupsfromdistrictid($stateid) {
        $users = Array();
        $usersQuery = sprintf("SELECT groupid FROM `group`
            INNER JOIN city ON city.cityid = `group`.cityid
            INNER JOIN district ON district.districtid = city.districtid
            WHERE group.isdeleted = 0 AND district.districtid = %d", Sanitise::Long($stateid));
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $users[] = $row['groupid'];
            }
            return $users;
        }
        return null;
    }

    public function getgroupsfromcityid($stateid) {
        $users = Array();
        $usersQuery = sprintf("SELECT groupid FROM `group`
            INNER JOIN city ON city.cityid = `group`.cityid
            WHERE group.isdeleted = 0 AND city.cityid = %d", Sanitise::Long($stateid));
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $users[] = $row['groupid'];
            }
            return $users;
        }
        return null;
    }

    public function getvehgroupinfo($vehicleid) {
        $customerno = $this->_Customerno;
        $Query = "SELECT g.groupid as branchid"
            . ", g.groupname as branchname"
            . ", c.cityid as regionid"
            . ", c.name as regionname"
            . ", d.districtid as zoneid"
            . ", d.name as zonename"
            . " FROM vehicle as v"
            . " INNER JOIN `group` g ON g.groupid = v.groupid "
            . " INNER JOIN city AS c ON c.cityid = g.cityid "
            . " INNER JOIN district as d on d.districtid = c.districtid "
            . " WHERE v.customerno = %d"
            . " AND v.vehicleid = %d"
            . " AND v.isdeleted = 0"
            . " AND g.isdeleted = 0"
            . " AND c.isdeleted = 0"
            . " AND d.isdeleted = 0"
            . " LIMIT 1";
        $SQL = sprintf($Query, $customerno, $vehicleid);
        $this->_databaseManager->executeQuery($SQL);
        $row = $this->_databaseManager->get_nextRow();
        $vehGroupInfo = new stdClass();
        $vehGroupInfo->branchid = $row['branchid'];
        $vehGroupInfo->branchname = $row['branchname'];
        $vehGroupInfo->regionid = $row['regionid'];
        $vehGroupInfo->regionname = $row['regionname'];
        $vehGroupInfo->zoneid = $row['zoneid'];
        $vehGroupInfo->zonename = $row['zonename'];
        return $vehGroupInfo;
    }

    public function get_all_vehicles_with_drivers_by_groupname($kind = '') {
        $vehicles = Array();
        $groups = $this->getUserGroups($_SESSION['customerno'], $_SESSION['userid']);
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['switch_to'] == 1) {
            $Query = "SELECT vehicle.sequenceno,user.realname,vehicle.timestamp,vehicle.customerno,group.groupname,vehicle.vehicleid,
            driver.isdeleted as driverdeleted,vehicle.driverid,driver.drivername,driver.driverlicno,driver.driverphone,vehicle.vehicleno,
            maintenance_status.id as main_status_id,unit.unitno,vehicle.uid,vehicle.kind,maintenance_status.name,vehicle.lastupdated,vehicle.stoppage_flag,vehicle.stoppage_transit_time,devices.gpsfixed
            FROM vehicle
            INNER JOIN driver ON driver.vehicleid = vehicle.vehicleid
            INNER JOIN unit ON unit.uid = vehicle.uid
            INNER JOIN devices ON devices.uid = unit.uid
            LEFT OUTER JOIN `maintenance_status` ON `vehicle`.status_id = `maintenance_status`.id
            LEFT OUTER JOIN user ON user.userid = vehicle.userid
            LEFT JOIN `group` ON `group`.groupid = vehicle.groupid";
        } else {
            $Query = "SELECT vehicle.sequenceno,user.realname,vehicle.timestamp,vehicle.customerno,groupname,vehicle.vehicleid,driver.isdeleted as driverdeleted,
            vehicle.driverid,driver.drivername,driver.driverlicno,driver.driverphone,vehicle.vehicleno,
            unit.unitno,vehicle.uid,vehicle.kind,vehicle.lastupdated,vehicle.stoppage_flag,vehicle.stoppage_transit_time,devices.gpsfixed
            FROM vehicle
            INNER JOIN unit ON unit.uid = vehicle.uid
            INNER JOIN devices ON devices.uid = unit.uid
            INNER JOIN driver ON driver.vehicleid = vehicle.vehicleid
            LEFT OUTER JOIN user ON user.userid = vehicle.userid
            LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid";
        }
        $Query .= " WHERE vehicle.customerno =%d AND vehicle.isdeleted=0 and unit.trans_statusid NOT IN(10,22)";
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d ";
        } else {
            if ($groups[0] != 0) {
                $groupid_ids = implode(',', $groups);
                $Query .= " AND vehicle.groupid in (" . $groupid_ids . ") ";
            }
        }
        if ($kind != '') {
            $Query .= " AND vehicle.kind='$kind' ";
        }
        if ($_SESSION['groupid'] != 0) {
            $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
        }
        $heir_query = "";
        if ($_SESSION['roleid'] == '2') {
            $heir_query = sprintf(" AND state.stateid = %d ", $_SESSION['heirarchy_id']);
        }
        if ($_SESSION['roleid'] == '3') {
            $heir_query = sprintf(" AND district.districtid = %d ", $_SESSION['heirarchy_id']);
        }
        if ($_SESSION['roleid'] == '4') {
            $heir_query = sprintf(" AND city.cityid = %d ", $_SESSION['heirarchy_id']);
        }
        $sortlist = '';
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['switch_to'] == 1) {
            $sortlist = sprintf(" AND vehicle.uid = 0 ");
        } elseif ($_SESSION['switch_to'] == "3") {
            $sortlist = sprintf(" AND vehicle.kind = 'Warehouse' ");
        } else {
            $sortlist = sprintf(" AND vehicle.kind <> 'Warehouse' ");
        }
        $vehiclesQuery .= $heir_query;
        $vehiclesQuery .= $sortlist;
        $vehiclesQuery .= " group by vehicle.vehicleid ";
        $vehiclesQuery .= " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.sequenceno ASC, vehicle.timestamp DESC";
        //$vehiclesQuery.=" ORDER BY vehicle.timestamp DESC";
        // echo $vehiclesQuery;
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->sequenceno = $row['sequenceno'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->customerno = $row['customerno'];
                $vehicle->type = $row['kind'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverlicno = $row['driverlicno'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->realname = $row['realname'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->timestamp = $row['timestamp'];
                $vehicle->uid = $row['uid'];
                $vehicle->groupname = $row['groupname'];
                $vehicle->status_name = isset($row['name']) ? $row['name'] : null;
                if ($_SESSION['use_maintenance'] == '1' && $_SESSION['switch_to'] == 1) {
                    $vehicle->main_status_id = $row['main_status_id'];
                }
                $vehicle->lastupdated = $row['lastupdated'];
                $vehicle->stoppage_flag = $row['stoppage_flag'];
                $vehicle->stoppage_transit_time = $row['stoppage_transit_time'];
                $vehicle->gpsfixed = $row['gpsfixed'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_deleted_vehicles($kind = '') {
        $vehicles = Array();
        $groups = $this->getUserGroups($_SESSION['customerno'], $_SESSION['userid']);
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['switch_to'] == 1) {
            $Query = "SELECT vehicle.sequenceno,user.realname,vehicle.timestamp,vehicle.customerno,group.groupname,vehicle.vehicleid,
            driver.isdeleted as driverdeleted,vehicle.driverid,driver.drivername,driver.driverlicno,driver.driverphone,vehicle.vehicleno,
            maintenance_status.id as main_status_id,unit.unitno,vehicle.uid,vehicle.kind,maintenance_status.name
            FROM vehicle
                LEFT OUTER JOIN `maintenance_status` ON `vehicle`.status_id = `maintenance_status`.id
            LEFT OUTER JOIN driver ON driver.vehicleid = vehicle.vehicleid
            LEFT OUTER JOIN unit ON unit.uid = vehicle.uid
            LEFT OUTER JOIN user ON user.userid = vehicle.userid
            LEFT JOIN `group` ON `group`.groupid = vehicle.groupid";
        } else {
            $Query = "SELECT vehicle.sequenceno,user.realname,vehicle.timestamp,vehicle.customerno,groupname,vehicle.vehicleid,driver.isdeleted as driverdeleted,
            vehicle.driverid,driver.drivername,driver.driverlicno,driver.driverphone,vehicle.vehicleno,
            unit.unitno,vehicle.uid,vehicle.kind
            FROM vehicle
            LEFT JOIN unit ON unit.uid = vehicle.uid
            LEFT JOIN devices ON devices.uid = unit.uid
            LEFT JOIN driver ON driver.vehicleid = vehicle.vehicleid
            LEFT OUTER JOIN user ON user.userid = vehicle.userid
            LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid";
        }
        $Query .= " WHERE vehicle.customerno =%d AND vehicle.isdeleted=1 ";
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d ";
        } else {
            if ($groups[0] != 0) {
                $groupid_ids = implode(',', $groups);
                $Query .= " AND vehicle.groupid in (" . $groupid_ids . ") ";
            }
        }
        if ($kind != '') {
            $Query .= " AND vehicle.kind='$kind' ";
        }
        if ($_SESSION['groupid'] != 0) {
            $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
        }
        $heir_query = "";
        if ($_SESSION['roleid'] == '2') {
            $heir_query = sprintf(" AND state.stateid = %d ", $_SESSION['heirarchy_id']);
        }
        if ($_SESSION['roleid'] == '3') {
            $heir_query = sprintf(" AND district.districtid = %d ", $_SESSION['heirarchy_id']);
        }
        if ($_SESSION['roleid'] == '4') {
            $heir_query = sprintf(" AND city.cityid = %d ", $_SESSION['heirarchy_id']);
        }
        $sortlist = '';
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['switch_to'] == 1) {
            $sortlist = sprintf(" AND vehicle.uid = 0 ");
        } elseif ($_SESSION['switch_to'] == "3") {
            $sortlist = sprintf(" AND vehicle.kind = 'Warehouse' ");
        } else {
            $sortlist = sprintf(" AND vehicle.kind <> 'Warehouse' ");
        }
        $vehiclesQuery .= $heir_query;
        $vehiclesQuery .= $sortlist;
        $vehiclesQuery .= " group by vehicle.vehicleid ";
        $vehiclesQuery .= " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.sequenceno ASC, vehicle.timestamp DESC";
        //$vehiclesQuery.=" ORDER BY vehicle.timestamp DESC";
        // echo $vehiclesQuery;
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->sequenceno = $row['sequenceno'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->customerno = $row['customerno'];
                $vehicle->type = $row['kind'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverlicno = $row['driverlicno'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->realname = $row['realname'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->timestamp = $row['timestamp'];
                $vehicle->uid = $row['uid'];
                $vehicle->groupname = $row['groupname'];
                $vehicle->status_name = isset($row['name']) ? $row['name'] : null;
                if ($_SESSION['use_maintenance'] == '1' && $_SESSION['switch_to'] == 1) {
                    $vehicle->main_status_id = $row['main_status_id'];
                }
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function checkRoles($customerno, $roleid) {
        $role = false;
        $user_roles = Array();
        $roles = $this->setMaintenanceRoles($customerno);
        if (isset($roles)) {
            $user_roles = array($roles->stateRoleId, $roles->zoneRoleId, $roles->regionRoleId, $roles->cityRoleId, $roles->groupRoleId, '2', '3', '4', '8');
        }
        if (isset($user_roles) && !empty($user_roles) && in_array($roleid, $user_roles)) {
            $role = true;
        }
        return $role;
    }

    public function get_all_vehicles_with_genset($kind = '') {
        $vehicles = Array();
        $Query = "SELECT vehicle.sequenceno,user.realname,vehicle.timestamp,
        vehicle.customerno,groupname,vehicle.vehicleid,vehicle.vehicleno,
        vehicle.no_of_genset,unit.unitno,vehicle.uid,vehicle.kind,
        g1.gensetno as genset1,g2.gensetno as genset2,
        t1.transmitterno as transmitter1,t2.transmitterno as transmitter2
        FROM vehicle
        LEFT OUTER JOIN user ON user.userid = vehicle.userid
        LEFT OUTER JOIN unit ON unit.uid = vehicle.uid
        LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid
        LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid
        LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid
        LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid
        LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid ";
        $Query .= " WHERE vehicle.customerno =%d AND vehicle.isdeleted=0 ";
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d ";
        }
        if ($kind != '') {
            $Query .= " AND vehicle.kind='$kind' ";
        }
        if ($_SESSION['groupid'] != 0) {
            $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
        }
        $sortlist = '';
        if ($_SESSION['switch_to'] == "3") {
            $sortlist = sprintf(" AND vehicle.kind = 'Warehouse' ");
        } else {
            $sortlist = sprintf(" AND vehicle.kind <> 'Warehouse' ");
        }
        $vehiclesQuery .= $sortlist;
        $vehiclesQuery .= " group by vehicle.vehicleid ORDER BY vehicle.timestamp DESC";
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->sequenceno = $row['sequenceno'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->customerno = $row['customerno'];
                $vehicle->type = $row['kind'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->realname = $row['realname'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->timestamp = $row['timestamp'];
                $vehicle->uid = $row['uid'];
                $vehicle->groupname = $row['groupname'];
                $vehicle->no_of_genset = $row['no_of_genset'];
                $vehicle->genset1 = $row['genset1'];
                $vehicle->genset2 = $row['genset2'];
                $vehicle->transmitter1 = $row['transmitter1'];
                $vehicle->transmitter2 = $row['transmitter2'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getGenset($q) {
        $vehicles = Array();
        $Query = "select * from genset where gensetno LIKE '%$q%' and customerno = $this->_Customerno and isdeleted = 0";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicles[] = array(
                    'id' => $row['gensetid'],
                    'value' => $row['gensetno']
                );
            }
            return $vehicles;
        }
        return null;
    }

    public function getTransmitter($q) {
        $vehicles = Array();
        $Query = "select * from transmitter where transmitterno LIKE '%$q%' and customerno = $this->_Customerno and isdeleted = 0";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicles[] = array(
                    'id' => $row['transmitterid'],
                    'value' => $row['transmitterno']
                );
            }
            return $vehicles;
        }
        return null;
    }

//vehicle data  send to xls
    public function get_all_vehicles_xls($kind) {
        $vehkind = $kind;
        $vehicles = Array();
        if ($_SESSION['use_maintenance'] == '1') {
            $Query = "SELECT i.policy_no,id.ins_dealername,amc.paidamt,amc.agree_start_date,amc.agree_end_date,amc.total_insured_km,amc.insured_month,amc.startkm,amc.endkm,c.address,c.cost,c.date,l.marginamt,l.loanamt,l.financier,l.emiamt,l.tennure,l.start_date as loan_start_date,l.end_date as loan_end_date,i.value,i.premium,i.start_date,i.end_date,i.notes, i.claim_place,i.ncb,d.engineno,d.chasisno,d.vehicle_purpose,d.vehicle_type,d.dealerid,d.invoiceno, d.invoicedate, d.invoiceamt, d.seatcapacity, tax.amount,tax.from_date,to_date,tax.type,tax.reg_no,user.realname,vehicle.timestamp,groupname,vehicle.vehicleid,driver.isdeleted as driverdeleted,
            vehicle.expiry_date,vehicle.branchid,vehicle.serial_number,vehicle.driverid,driver.drivername,driver.driverlicno,driver.driverphone,vehicle.vehicleno,maintenance_status.id as main_status_id,vehicle.rto_location,vehicle.current_location,vehicle.authorized_signatory,vehicle.hypothecation,vehicle.fueltype,vehicle.manufacturing_year,vehicle.purchase_date,vehicle.owner_name,
            unit.unitno,de.name as dealername,vehicle.uid,vehicle.kind,maintenance_status.name,mt.meter_reading,mk.name as makename,md.name as modelname,ic.name as inscompname,vehicle.other_upload1,vehicle.other_upload2,vehicle.other_upload3,vehicle.other_upload4 ,vehicle.other_upload5 ,vehicle.other_upload6  FROM vehicle
            LEFT OUTER JOIN `maintenance_status` ON `vehicle`.status_id = `maintenance_status`.id
            LEFT OUTER JOIN `driver` ON driver.vehicleid = vehicle.vehicleid
            LEFT OUTER JOIN `unit` ON unit.uid = vehicle.uid
            LEFT OUTER JOIN `user` ON user.userid = vehicle.userid
            LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
            LEFT OUTER JOIN `maintenance` as mt ON mt.vehicleid = vehicle.vehicleid
            LEFT JOIN `tax` ON `tax`.vehicleid = vehicle.vehicleid
            LEFT JOIN `description` as d ON d.vehicleid = vehicle.vehicleid
            LEFT JOIN `dealer`as de ON d.dealerid = de.dealerid
            LEFT JOIN `insurance` as i ON i.vehicleid = vehicle.vehicleid
            LEFT JOIN `insurance_dealer` as id ON id.ins_dealerid = i.ins_dealerid
            LEFT JOIN `loan` as l ON l.vehicleid = vehicle.vehicleid
            LEFT JOIN `amc` as amc ON amc.vehicleid = vehicle.vehicleid
            LEFT JOIN `capitalization` as c ON c.vehicleid = vehicle.vehicleid
            LEFT JOIN `model` as md ON md.model_id=vehicle.modelid
            LEFT JOIN `make` as mk ON mk.id=md.make_id
            LEFT JOIN `insurance_company` as ic ON ic.id=i.companyid";
            if ($_SESSION["roleid"] == '2') {
                $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid
                LEFT OUTER JOIN `district` ON `district`.districtid = city.districtid
                LEFT OUTER JOIN `state` ON `state`.stateid = district.stateid ";
            }
            if ($_SESSION["roleid"] == '3') {
                $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid
                LEFT OUTER JOIN `district` ON `district`.districtid = city.districtid ";
            }
            if ($_SESSION["roleid"] == '4') {
                $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid ";
            }
        } else {
            $Query = "SELECT user.realname,vehicle.timestamp,groupname,vehicle.vehicleid,driver.isdeleted as driverdeleted,
            vehicle.driverid,driver.drivername,driver.driverlicno,driver.driverphone,vehicle.vehicleno,
            unit.unitno,vehicle.uid,vehicle.kind FROM vehicle
            LEFT OUTER JOIN `driver` ON driver.vehicleid = vehicle.vehicleid
            LEFT OUTER JOIN `user` ON user.userid = vehicle.userid
            LEFT OUTER JOIN `unit` ON unit.uid = vehicle.uid
            LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid ";
        }
        if ($vehkind != '') {
//for kind
            $Query .= " WHERE vehicle.customerno =%d AND vehicle.isdeleted=0 AND vehicle.kind='$kind'  ";
        } else {
            $Query .= " WHERE vehicle.customerno =%d AND vehicle.isdeleted=0";
        }
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d ";
        }
        if ($_SESSION['groupid'] != 0) {
            $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
        }
        $heir_query = "";
        if ($_SESSION['roleid'] == '2') {
            $heir_query = sprintf(" AND state.stateid = %d ", $_SESSION['heirarchy_id']);
        }
        if ($_SESSION['roleid'] == '3') {
            $heir_query = sprintf(" AND district.districtid = %d ", $_SESSION['heirarchy_id']);
        }
        if ($_SESSION['roleid'] == '4') {
            $heir_query = sprintf(" AND city.cityid = %d ", $_SESSION['heirarchy_id']);
        }
        $vehiclesQuery .= $heir_query;
        $vehiclesQuery .= " group by vehicle.vehicleid ORDER BY vehicle.timestamp DESC";
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicleid = $row['vehicleid'];
                $right_front = $left_front = $right_back_out = $left_back_out = $stepney = $right_back_in = $left_back_in = "";
                $gettyredata = array();
                $gettyredata = $this->gettyreTypedata_for_xls($vehicleid);
                foreach ($gettyredata as $row1) {
                    if ($row1['tyreid'] == 1) {
                        $right_front = $row1['serialno'];
                    }
                    if ($row1['tyreid'] == 2) {
                        $left_front = $row1['serialno'];
                    }
                    if ($row1['tyreid'] == 3) {
                        $right_back_out = $row1['serialno'];
                    }
                    if ($row1['tyreid'] == 4) {
                        $left_back_out = $row1['serialno'];
                    }
                    if ($row1['tyreid'] == 5) {
                        $stepney = $row1['serialno'];
                    }
                    if ($row1['tyreid'] == 6) {
                        $right_back_in = $row1['serialno'];
                    }
                    if ($row1['tyreid'] == 7) {
                        $left_back_in = $row1['serialno'];
                    }
                }
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->right_front = $right_front;
                $vehicle->left_front = $left_front;
                $vehicle->right_back_out = $right_back_out;
                $vehicle->left_back_out = $left_back_out;
                $vehicle->stepney = $stepney;
                $vehicle->right_back_in = $right_back_in;
                $vehicle->left_back_in = $left_back_in;
                $vehicle->fireext_srno = $row['serial_number'];
                $vehicle->fire_expiry_date = $row['expiry_date'];
                $vehicle->branchid = $row['branchid'];
                $vehicle->type = $row['kind'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverlicno = $row['driverlicno'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->realname = $row['realname'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->timestamp = $row['timestamp'];
                $vehicle->meter_reading = $row['meter_reading'];
                $vehicle->groupname = $row['groupname'];
                $vehicle->status_name = isset($row['name']) ? $row['name'] : null;
                if ($_SESSION['use_maintenance'] == '1') {
                    $vehicle->main_status_id = $row['main_status_id'];
                    $vehicle->fueltype = $row['fueltype'];
                    $vehicle->address = $row['address'];
                    $vehicle->capitalcost = $row['cost'];
                    $vehicle->capitaldate = $row['date'];
                    $vehicle->loan_marginamt = $row['marginamt'];
                    $vehicle->loan_loanamt = $row['loanamt'];
                    $vehicle->loan_financier = $row['financier'];
                    $vehicle->loan_emiamt = $row['emiamt'];
                    $vehicle->loan_tennure = $row['tennure'];
                    $vehicle->amcpaidamt = $row['paidamt'];
                    $vehicle->amc_agree_start_date = $row['agree_start_date'];
                    $vehicle->amc_agree_end_date = $row['agree_end_date'];
                    $vehicle->amc_total_insured_km = $row['total_insured_km'];
                    $vehicle->amc_insured_month = $row['insured_month'];
                    $vehicle->amc_startkm = $row['startkm'];
                    $vehicle->amc_endkm = $row['endkm'];
                    $vehicle->loan_start_date = $row['loan_start_date'];
                    $vehicle->loan_end_date = $row['loan_end_date'];
                    $vehicle->insurance_value = $row['value'];
                    $vehicle->insurance_premium = $row['premium'];
                    $vehicle->insurance_ncb = $row['ncb'];
                    $vehicle->insurance_coname = $row['inscompname'];
                    $vehicle->insurance_start_date = $row['start_date'];
                    $vehicle->insurance_end_date = $row['end_date'];
                    $vehicle->insurance_notes = $row['notes'];
                    $vehicle->insurance_claim_place = $row['claim_place'];
                    $vehicle->insurance_idv = $row['ncb'];
                    $vehicle->insuranceno = $row['policy_no'];
                    $vehicle->ins_dealername = $row['ins_dealername'];
                    $vehicle->engineno = $row['engineno'];
                    $vehicle->chasisno = $row['chasisno'];
                    $vehicle->vehicle_purpose = $row['vehicle_purpose'];
                    $vehicle->vehicle_type = $row['vehicle_type'];
                    $vehicle->dealername = $row['dealername'];
                    if ($row['manufacturing_year'] == '0') {
                        $manufact = '';
                    } else {
                        $manufact = $row['manufacturing_year'];
                    }
                    $vehicle->manufacturing_year = $manufact;
                    $vehicle->owner_name = $row['owner_name'];
                    if ($row['purchase_date'] == '1970-01-01') {
                        $purchase = '';
                    } else {
                        $purchase = date("d-m-Y", strtotime($row['purchase_date']));
                    }
                    $vehicle->purchase_date = $purchase;
                    $vehicle->invoiceno = $row['invoiceno'];
                    if ($row['invoicedate'] == '1970-01-01') {
                        $invdate = '';
                    } else {
                        $invdate = $row['invoicedate'];
                    }
                    $vehicle->invoicedate = $invdate;
                    $vehicle->invoiceamt = $row['invoiceamt'];
                    $vehicle->seatcapacity = $row['seatcapacity'];
                    $vehicle->tax_amt = $row['amount'];
                    $vehicle->tax_from_date = $row['from_date'];
                    $vehicle->tax_to_date = $row['to_date'];
                    if ($row['type'] == 1) {
                        $str = "Road Tax";
                    } elseif ($row['type'] == 2) {
                        $str = "Registration Tax";
                    } else {
                        $str = "";
                    }
                    $vehicle->tax_type = $str;
                    $vehicle->tax_reg_no = $row['reg_no'];
                    $vehicle->makename = $row['makename'];
                    $vehicle->modelname = $row['modelname'];
                    $vehicle->rtolocation = $row['rto_location'];
                    $vehicle->current_location = $row['current_location'];
                    $vehicle->authorized_signatory = $row['authorized_signatory'];
                    $vehicle->hypothecation = $row['hypothecation'];
                    $ou1 = $row['other_upload1'];
                    $ou2 = $row['other_upload2'];
                    $ou3 = $row['other_upload3'];
                    $ou4 = $row['other_upload4'];
                    $ou5 = $row['other_upload5'];
                    $ou6 = $row['other_upload6'];
                    $puc_file = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $row['vehicleid'] . "/puc.pdf";
                    $reg_file = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $row['vehicleid'] . "/registration.pdf";
                    $ins_file = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $row['vehicleid'] . "/insurance.pdf";
                    $other1_file = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $row['vehicleid'] . "/" . $ou1 . ".pdf";
                    $other2_file = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $row['vehicleid'] . "/" . $ou2 . ".pdf";
                    $other3_file = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $row['vehicleid'] . "/" . $ou3 . ".pdf";
                    $other4_file = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $row['vehicleid'] . "/" . $ou4 . ".pdf";
                    $other5_file = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $row['vehicleid'] . "/" . $ou5 . ".pdf";
                    $other6_file = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $row['vehicleid'] . "/" . $ou6 . ".pdf";
                    if (file_exists($other1_file)) {
                        $vehicle->u1 = "Yes";
                    } else {
                        $vehicle->u1 = "No";
                    }
                    if (file_exists($other2_file)) {
                        $vehicle->u2 = "Yes";
                    } else {
                        $vehicle->u2 = "No";
                    }
                    if (file_exists($other3_file)) {
                        $vehicle->u3 = "Yes";
                    } else {
                        $vehicle->u3 = "No";
                    }
                    if (file_exists($other4_file)) {
                        $vehicle->u4 = "Yes";
                    } else {
                        $vehicle->u4 = "No";
                    }
                    if (file_exists($other5_file)) {
                        $vehicle->u5 = "Yes";
                    } else {
                        $vehicle->u5 = "No";
                    }
                    if (file_exists($other6_file)) {
                        $vehicle->u6 = "Yes";
                    } else {
                        $vehicle->u6 = "No";
                    }
                    if (file_exists($puc_file)) {
                        $vehicle->puc = "Yes";
                    } else {
                        $vehicle->puc = "No";
                    }
                    if (file_exists($reg_file)) {
                        $vehicle->reg = "Yes";
                    } else {
                        $vehicle->reg = "No";
                    }
                    if (file_exists($ins_file)) {
                        $vehicle->ins = "Yes";
                    } else {
                        $vehicle->ins = "No";
                    }
                }
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_all_warehouse() {
        $vehicles = Array();
        if ($_SESSION['use_maintenance'] == '1') {
            $Query = "SELECT user.realname,vehicle.timestamp,groupname,vehicle.vehicleid,
            warehouse.driverid,warehouse.vehicleno,maintenance_status.id as main_status_id,
            unit.unitno,warehouse.uid,warehouse.kind,maintenance_status.name FROM warehouse
            LEFT OUTER JOIN `maintenance_status` ON `warehouse`.status_id = `maintenance_status`.id
            LEFT OUTER JOIN unit ON unit.uid = warehouse.uid
            LEFT OUTER JOIN user ON user.userid = warehouse.userid
            LEFT OUTER JOIN `group` ON `group`.groupid = warehouse.groupid";
            if ($_SESSION["roleid"] == '2') {
                $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid
                LEFT OUTER JOIN `district` ON `district`.districtid = city.districtid
                LEFT OUTER JOIN `state` ON `state`.stateid = district.stateid ";
            }
            if ($_SESSION["roleid"] == '3') {
                $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid
                LEFT OUTER JOIN `district` ON `district`.districtid = city.districtid ";
            }
            if ($_SESSION["roleid"] == '4') {
                $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid ";
            }
        } else {
            $Query = "SELECT user.realname,warehouse.timestamp,groupname,warehouse.vehicleid,
            warehouse.driverid,warehouse.vehicleno,
            unit.unitno,warehouse.uid,warehouse.kind FROM warehouse
            LEFT OUTER JOIN user ON user.userid = warehouse.userid
            LEFT OUTER JOIN unit ON unit.uid = warehouse.uid
            LEFT OUTER JOIN `group` ON `group`.groupid = warehouse.groupid ";
        }
        $Query .= " WHERE warehouse.customerno =%d AND unit.is_warehouse=1 AND warehouse.isdeleted=0 ";
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND warehouse.groupid =%d ";
        }
        if ($_SESSION['groupid'] != 0) {
            $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
        }
        $heir_query = "";
        if ($_SESSION['roleid'] == '2') {
            $heir_query = sprintf(" AND state.stateid = %d ", $_SESSION['heirarchy_id']);
        }
        if ($_SESSION['roleid'] == '3') {
            $heir_query = sprintf(" AND district.districtid = %d ", $_SESSION['heirarchy_id']);
        }
        if ($_SESSION['roleid'] == '4') {
            $heir_query = sprintf(" AND city.cityid = %d ", $_SESSION['heirarchy_id']);
        }
        $vehiclesQuery .= $heir_query;
        $vehiclesQuery .= " group by warehouse.vehicleid ORDER BY warehouse.timestamp DESC";
        //$vehiclesQuery.=" ORDER BY vehicle.timestamp DESC";
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->type = $row['kind'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->realname = $row['realname'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->timestamp = $row['timestamp'];
                $vehicle->uid = $row['uid'];
                $vehicle->groupname = $row['groupname'];
//$vehicle->status_name = $row['name'];
                if ($_SESSION['use_maintenance'] == '1') {
                    $vehicle->main_status_id = $row['main_status_id'];
                }
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_all_vehicles_by_groupname_for_transactions() {
        $vehicles = Array();
        $groups = $this->getUserGroups($_SESSION['customerno'], $_SESSION['userid']);
        $Query = "SELECT user.realname,vehicle.timestamp,groupname,vehicle.vehicleid,driver.isdeleted as driverdeleted,
        vehicle.driverid,driver.drivername,driver.driverlicno,driver.driverphone,vehicle.vehicleno,maintenance_status.id as main_status_id,
        unit.unitno,vehicle.uid,vehicle.kind,name FROM vehicle
        LEFT OUTER JOIN driver ON driver.vehicleid = vehicle.vehicleid
        LEFT OUTER JOIN unit ON unit.uid = vehicle.uid
        LEFT OUTER JOIN user ON user.userid = vehicle.userid
        LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
        LEFT OUTER JOIN `maintenance_status` ON `vehicle`.status_id = `maintenance_status`.id ";
        $Query .= " WHERE vehicle.customerno =%d AND vehicle.isdeleted=0 AND maintenance_status.id = 2";
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
        } else {
            if ($groups[0] != 0) {
                $groupid_ids = implode(',', $groups);
                $Query .= " AND vehicle.groupid in (" . $groupid_ids . ") ";
            }
        }
        if ($_SESSION['groupid'] != 0) {
            $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
        }
        $vehiclesQuery .= " ORDER BY vehicle.timestamp DESC";
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->type = $row['kind'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverlicno = $row['driverlicno'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->realname = $row['realname'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->timestamp = $row['timestamp'];
                $vehicle->uid = $row['uid'];
                $vehicle->groupname = $row['groupname'];
                $vehicle->status_name = $row['name'];
                if ($_SESSION['use_maintenance'] == '1') {
                    $vehicle->main_status_id = $row['main_status_id'];
                }
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_approved_vehicles() {
        $vehicles = Array();
        $groups = $this->getUserGroups($_SESSION['customerno'], $_SESSION['userid']);
        $Query = "SELECT vehicle.vehicleid,vehicle.vehicleno FROM vehicle
        LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
        LEFT OUTER JOIN `maintenance_status` ON `vehicle`.status_id = `maintenance_status`.id ";
        $Query .= " WHERE vehicle.customerno =%d AND vehicle.isdeleted=0 AND maintenance_status.id = 2";
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
        } else {
            if ($groups[0] != 0) {
                $groupid_ids = implode(',', $groups);
                $Query .= " AND vehicle.groupid in (" . $groupid_ids . ") ";
            }
        }
        if ($_SESSION['groupid'] != 0) {
            $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
        }
        $vehiclesQuery .= " ORDER BY vehicle.timestamp DESC";
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_all_vehicles_forapproval() {
        $vehicles = Array();
        $groups = $this->getUserGroups($_SESSION['customerno'], $_SESSION['userid']);
        $Query = "SELECT user.realname,vehicle.submission_date,group.groupname,vehicle.vehicleid,vehicle.vehicleno,
        maintenance_status.name FROM vehicle
        LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
        LEFT OUTER JOIN `user` ON `user`.userid = vehicle.userid
        LEFT OUTER JOIN `maintenance_status` ON `vehicle`.status_id = `maintenance_status`.id";
        if ($_SESSION['roleid'] == '1' || $_SESSION['roleid'] == '8') {
            $Query .= " WHERE vehicle.customerno =%d AND vehicle.isdeleted=0 AND vehicle.status_id = '1'";
        } elseif ($_SESSION['roleid'] == '2') {
            $Query .= " LEFT OUTER JOIN city on city.cityid = group.cityid
            LEFT OUTER JOIN district on district.districtid = city.districtid
            LEFT OUTER JOIN state on state.stateid = district.stateid";
            $Query .= " WHERE vehicle.customerno =%d AND vehicle.isdeleted=0 AND vehicle.status_id = '1' AND state.stateid = " . $_SESSION['heirarchy_id'] . " ";
        } elseif ($_SESSION['roleid'] == '3') {
            $Query .= " LEFT OUTER JOIN city on city.cityid = group.cityid
            LEFT OUTER JOIN district on district.districtid = city.districtid ";
            $Query .= " WHERE vehicle.customerno =%d AND vehicle.isdeleted=0 AND vehicle.status_id = '1' AND district.districtid = " . $_SESSION['heirarchy_id'] . " ";
        } elseif ($_SESSION['roleid'] == '4') {
            $Query .= " LEFT OUTER JOIN city on city.cityid = group.cityid";
            $Query .= " WHERE vehicle.customerno =%d AND vehicle.isdeleted=0 AND vehicle.status_id = '1' AND city.cityid = " . $_SESSION['heirarchy_id'] . " ";
        } else {
            $Query .= " WHERE vehicle.customerno =%d AND vehicle.isdeleted=0 AND vehicle.status_id = '1'";
        }
//$Query .=" WHERE vehicle.customerno =%d AND vehicle.isdeleted=0 AND vehicle.status_id = '1'";
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
        } else {
            if ($groups[0] != 0) {
                $groupid_ids = implode(',', $groups);
                $Query .= " AND vehicle.groupid in (" . $groupid_ids . ") ";
            }
        }
        if ($_SESSION['groupid'] != 0) {
            $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
        }
        $vehiclesQuery .= " ORDER BY vehicle.timestamp DESC";
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->groupname = $row['groupname'];
                $vehicle->sender = $row['realname'];
                $vehicle->status_name = $row['name'];
                if ($_SESSION['use_hierarchy'] == '1') {
                    $vehicle->approver = $_SESSION["master"];
                } else {
                    $vehicle->approver = $_SESSION["administrator"];
                }
                if ($row["submission_date"] != '0000-00-00 00:00:00') {
                    $vehicle->timestamp = date("d-m-Y", strtotime($row["submission_date"]));
                } else {
                    $vehicle->timestamp = '';
                }
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_all_vehicles_with_drivers() {
        $vehicles = Array();
        $Query = "SELECT vehicle.vehicleid,driver.isdeleted as driverdeleted,
        vehicle.driverid,driver.drivername,driver.driverlicno,driver.driverphone,vehicle.vehicleno,
        unit.unitno,vehicle.uid,article.artname,vehicle.kind FROM vehicle
        LEFT OUTER JOIN driver ON driver.vehicleid = vehicle.vehicleid
        LEFT OUTER JOIN unit ON unit.uid = vehicle.uid
        LEFT OUTER JOIN articlemanage ON articlemanage.vehicleid = vehicle.vehicleid
        LEFT OUTER JOIN article ON article.artid = articlemanage.artid
        WHERE vehicle.customerno =%d AND vehicle.isdeleted=0 ";
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
        }
        if ($_SESSION['groupid'] != 0) {
            $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
        }
//echo $vehiclesQuery;
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->type = $row['kind'];
                /* if($row['driverdeleted']==0)
                {
                $vehicle->driverid = $row['driverid'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverlicno = $row['driverlicno'];
                $vehicle->driverphone = $row['driverphone'];
                 */
                $vehicle->driverid = $row['driverid'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverlicno = $row['driverlicno'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->uid = $row['uid'];
                $vehicle->artname = $row['artname'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_all_groupedvehicles_with_drivers_for_pdf($groupid) {
        $vehicles = Array();
        $Query = "SELECT vehicle.vehicleid,driver.isdeleted as driverdeleted,
    vehicle.driverid,driver.drivername,driver.driverlicno,driver.driverphone,vehicle.vehicleno,
    unit.unitno, unit.acsensor, vehicle.uid,vehicle.kind, devices.deviceid FROM vehicle
    INNER JOIN driver ON driver.vehicleid = vehicle.vehicleid
    INNER JOIN unit ON unit.uid = vehicle.uid
    INNER JOIN devices ON devices.uid = vehicle.uid
    WHERE vehicle.customerno =%d AND vehicle.isdeleted=0 AND unit.trans_statusid NOT IN (10,22)";
        if ($groupid != '0') {
            $Query .= " AND vehicle.groupid=%d";
        }
        if ($groupid != '0') {
            $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($groupid));
        } else {
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->type = $row['kind'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverlicno = $row['driverlicno'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->uid = $row['uid'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->acsensor = $row['acsensor'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_groupedvehicle_for_cron($groupid, $vehicleid, $userid = null, $roleid = null) {
        $Query = "SELECT vehicle.vehicleid FROM vehicle
        INNER JOIN unit ON unit.uid = vehicle.uid ";
        if (isset($userid) && isset($roleid) && $roleid == 43) {
            $Query .= " INNER JOIN vehicleusermapping vm on vm.vehicleid = vehicle.vehicleid and vm.groupid = " . $groupid . " and vm.userid =  " . $userid . " ";
        }
        $Query .= " WHERE vehicle.customerno =%d AND vehicle.vehicleid =%d AND vehicle.isdeleted=0 AND unit.trans_statusid NOT IN (10,22)";
        if ($groupid != 0) {
            $Query .= " AND vehicle.groupid=%d";
        }
        $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($vehicleid), Sanitise::Long($groupid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return true;
            }
        }
        return false;
    }

    public function get_vehicle_details($vehicleid) {
        $Query = "  SELECT  u.unitno
                            ,v.vehicleno
                            ,v.uid
                            ,v.groupid
                            ,v.vehicleid
                            ,d.deviceid
                            ,g.groupname
                            ,u.tempsen1
                            ,u.tempsen2
                            ,u.tempsen3
                            ,u.tempsen4
                    FROM    vehicle as v
                    INNER JOIN unit as u on u.uid = v.uid
                    INNER JOIN devices as d on d.uid = v.uid
                    LEFT OUTER JOIN `group` as g on g.groupid = v.groupid
                    WHERE   v.customerno = %d
                    AND     v.vehicleid = %d
                    AND     u.trans_statusid NOT IN (10,22)
                    AND     v.isdeleted=0";
        $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($vehicleid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new stdClass();
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->uid = $row['uid'];
                $vehicle->groupid = $row['groupid'];
                $vehicle->groupname = $row['groupname'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->tempsen1 = $row['tempsen1'];
                $vehicle->tempsen2 = $row['tempsen2'];
                $vehicle->tempsen3 = $row['tempsen3'];
                $vehicle->tempsen4 = $row['tempsen4'];
            }
            return $vehicle;
        }
        return null;
    }

    public function get_vehicle_details_pdf($vehicleid, $customerno, $isWarehouse = null) {
        if ($isWarehouse == null) {
            $vehiclekind = '';
        } else {
            $vehiclekind = ' and vehiclekind = "Warehouse"';
        }
        $Query = "  SELECT  v.overspeed_limit
                            ,v.vehicleid
                            ,v.vehicleno
                            , driver.drivername
                            , driver.driverphone
                    FROM    `vehicle` v
                    INNER JOIN unit AS u ON u.uid = v.uid
                    INNER JOIN devices AS d ON d.uid = u.uid
                    LEFT JOIN `driver` on driver.driverid = v.driverid
                    WHERE   v.customerno = %d
                    AND     u.trans_statusid NOT IN (10,22)
                    AND     v.vehicleid = %d
                    " . $vehiclekind . "
                    AND     v.isdeleted = 0
                    GROUP BY v.vehicleid
                    ORDER BY v.sequenceno=0,v.sequenceno ASC,v.groupid ASC";
        $vehiclesQuery = sprintf($Query, $customerno, Sanitise::String($vehicleid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverphone = $row['driverphone'];
            }
            return $vehicle;
        }
        return null;
    }

    public function get_vehicle_with_driver($vehicleid) {
        $Query = "  SELECT  v.vehicleid
                            , v.kind
                            , d.drivername
                            , d.driverlicno
                            , d.driverphone
                            , v.vehicleno
                            , v.isdeleted
                            , v.overspeed_limit
                            , v.average
                            , v.fuelcapacity
                            , v.groupid
                            , v.temp1_min
                            , v.temp1_max
                            , v.temp1_allowance
                            , v.temp2_min
                            , v.temp2_max
                            , v.temp2_allowance
                            , v.temp3_min
                            , v.temp3_max
                            , v.temp3_allowance
                            , v.temp4_min
                            , v.temp4_max
                            , v.temp4_allowance
                            , v.hum_min
                            , v.hum_max
                            , u.n1
                            , u.n2
                            , u.n3
                            , u.n4
                            , trc.temp1_range1_start
                            , trc.temp1_range1_end
                            , trc.temp1_range1_color
                            , trc.temp1_range2_start
                            , trc.temp1_range2_end
                            , trc.temp1_range2_color
                            , trc.temp1_range3_start
                            , trc.temp1_range3_end
                            , trc.temp1_range3_color
                            , trc.temp1_range4_start
                            , trc.temp1_range4_end
                            , trc.temp1_range4_color
                            , trc.temp2_range1_start
                            , trc.temp2_range1_end
                            , trc.temp2_range1_color
                            , trc.temp2_range2_start
                            , trc.temp2_range2_end
                            , trc.temp2_range2_color
                            , trc.temp2_range3_start
                            , trc.temp2_range3_end
                            , trc.temp2_range3_color
                            , trc.temp2_range4_start
                            , trc.temp2_range4_end
                            , trc.temp2_range4_color
                            , trc.temp3_range1_start
                            , trc.temp3_range1_end
                            , trc.temp3_range1_color
                            , trc.temp3_range2_start
                            , trc.temp3_range2_end
                            , trc.temp3_range2_color
                            , trc.temp3_range3_start
                            , trc.temp3_range3_end
                            , trc.temp3_range3_color
                            , trc.temp3_range4_start
                            , trc.temp3_range4_end
                            , trc.temp3_range4_color
                            , trc.temp4_range1_start
                            , trc.temp4_range1_end
                            , trc.temp4_range1_color
                            , trc.temp4_range2_start
                            , trc.temp4_range2_end
                            , trc.temp4_range2_color
                            , trc.temp4_range3_start
                            , trc.temp4_range3_end
                            , trc.temp4_range3_color
                            , trc.temp4_range4_start
                            , trc.temp4_range4_end
                            , trc.temp4_range4_color
                            , v.isStaticTemp1
                            , v.isStaticTemp2
                            , v.isStaticTemp3
                            , v.isStaticTemp4
                    FROM    vehicle v
                    LEFT OUTER JOIN driver d ON d.vehicleid = v.vehicleid
                    LEFT OUTER JOIN unit u on u.vehicleid = v.vehicleid
                    LEFT OUTER JOIN  tempRangeColourMapping trc ON trc.vehicleid = v.vehicleid
                    WHERE   v.customerno = %d
                    AND     v.vehicleid = %d
                    AND     v.isdeleted = 0";
        $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($vehicleid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->type = $row['kind'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverlicno = $row['driverlicno'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->isdeleted = $row['isdeleted'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->average = $row['average'];
                $vehicle->fuelcapacity = $row['fuelcapacity'];
                $vehicle->groupid = $row['groupid'];
                $vehicle->temp1_min = $row['temp1_min'];
                $vehicle->temp1_max = $row['temp1_max'];
                $vehicle->temp1_allowance = $row['temp1_allowance'];
                $vehicle->temp2_min = $row['temp2_min'];
                $vehicle->temp2_max = $row['temp2_max'];
                $vehicle->temp2_allowance = $row['temp2_allowance'];
                $vehicle->temp3_min = $row['temp3_min'];
                $vehicle->temp3_max = $row['temp3_max'];
                $vehicle->temp3_allowance = $row['temp3_allowance'];
                $vehicle->temp4_min = $row['temp4_min'];
                $vehicle->temp4_max = $row['temp4_max'];
                $vehicle->temp4_allowance = $row['temp4_allowance'];
                $vehicle->hum_min = $row['hum_min'];
                $vehicle->hum_max = $row['hum_max'];
                $vehicle->n1 = $row['n1'];
                $vehicle->n2 = $row['n2'];
                $vehicle->n3 = $row['n3'];
                $vehicle->n4 = $row['n4'];
                $vehicle->temp1_range1_start = $row['temp1_range1_start'];
                $vehicle->temp1_range1_end = $row['temp1_range1_end'];
                $vehicle->temp1_range1_color = $row['temp1_range1_color'];
                $vehicle->temp1_range2_start = $row['temp1_range2_start'];
                $vehicle->temp1_range2_end = $row['temp1_range2_end'];
                $vehicle->temp1_range2_color = $row['temp1_range2_color'];
                $vehicle->temp1_range3_start = $row['temp1_range3_start'];
                $vehicle->temp1_range3_end = $row['temp1_range3_end'];
                $vehicle->temp1_range3_color = $row['temp1_range3_color'];
                $vehicle->temp1_range4_start = $row['temp1_range4_start'];
                $vehicle->temp1_range4_end = $row['temp1_range4_end'];
                $vehicle->temp1_range4_color = $row['temp1_range4_color'];
                $vehicle->temp2_range1_start = $row['temp2_range1_start'];
                $vehicle->temp2_range1_end = $row['temp2_range1_end'];
                $vehicle->temp2_range1_color = $row['temp2_range1_color'];
                $vehicle->temp2_range2_start = $row['temp2_range2_start'];
                $vehicle->temp2_range2_end = $row['temp2_range2_end'];
                $vehicle->temp2_range2_color = $row['temp2_range2_color'];
                $vehicle->temp2_range3_start = $row['temp2_range3_start'];
                $vehicle->temp2_range3_end = $row['temp2_range3_end'];
                $vehicle->temp2_range3_color = $row['temp2_range3_color'];
                $vehicle->temp2_range4_start = $row['temp2_range4_start'];
                $vehicle->temp2_range4_end = $row['temp2_range4_end'];
                $vehicle->temp2_range4_color = $row['temp2_range4_color'];
                $vehicle->temp3_range1_start = $row['temp3_range1_start'];
                $vehicle->temp3_range1_end = $row['temp3_range1_end'];
                $vehicle->temp3_range1_color = $row['temp3_range1_color'];
                $vehicle->temp3_range2_start = $row['temp3_range2_start'];
                $vehicle->temp3_range2_end = $row['temp3_range2_end'];
                $vehicle->temp3_range2_color = $row['temp3_range2_color'];
                $vehicle->temp3_range3_start = $row['temp3_range3_start'];
                $vehicle->temp3_range3_end = $row['temp3_range3_end'];
                $vehicle->temp3_range3_color = $row['temp3_range3_color'];
                $vehicle->temp3_range4_start = $row['temp3_range4_start'];
                $vehicle->temp3_range4_end = $row['temp3_range4_end'];
                $vehicle->temp3_range4_color = $row['temp3_range4_color'];
                $vehicle->temp4_range1_start = $row['temp4_range1_start'];
                $vehicle->temp4_range1_end = $row['temp4_range1_end'];
                $vehicle->temp4_range1_color = $row['temp4_range1_color'];
                $vehicle->temp4_range2_start = $row['temp4_range2_start'];
                $vehicle->temp4_range2_end = $row['temp4_range2_end'];
                $vehicle->temp4_range2_color = $row['temp4_range2_color'];
                $vehicle->temp4_range3_start = $row['temp4_range3_start'];
                $vehicle->temp4_range3_end = $row['temp4_range3_end'];
                $vehicle->temp4_range3_color = $row['temp4_range3_color'];
                $vehicle->temp4_range4_start = $row['temp4_range4_start'];
                $vehicle->temp4_range4_end = $row['temp4_range4_end'];
                $vehicle->temp4_range4_color = $row['temp4_range4_color'];
                $vehicle->isStaticTemp1 = $row['isStaticTemp1'];
                $vehicle->isStaticTemp2 = $row['isStaticTemp2'];
                $vehicle->isStaticTemp3 = $row['isStaticTemp3'];
                $vehicle->isStaticTemp4 = $row['isStaticTemp4'];
            }
            return $vehicle;
        }
        return null;
    }

    public function get_genset_vehicle($vehicleid) {
        $Query = "SELECT *,g1.gensetno as genset1,g2.gensetno as genset2,
    t1.transmitterno as transmitter1,t2.transmitterno as transmitter2,
    vehicle.vehicleid
    FROM vehicle
    Left join unit on unit.vehicleid = vehicle.vehicleid
    LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid
    LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid
    LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid
    LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid
    WHERE vehicle.customerno =%d AND vehicle.vehicleid=%d AND vehicle.isdeleted=0";
        $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($vehicleid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->uid = $row['uid'];
                $vehicle->type = $row['kind'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->isdeleted = $row['isdeleted'];
                $vehicle->groupid = $row['groupid'];
                $vehicle->no_of_genset = $row['no_of_genset'];
                $vehicle->genset1 = $row['genset1'];
                $vehicle->genset2 = $row['genset2'];
                $vehicle->transmitter1 = $row['transmitter1'];
                $vehicle->transmitter2 = $row['transmitter2'];
            }
            return $vehicle;
        }
        return null;
    }

    public function get_warehouse_with($vehicleid) {
        $Query = "SELECT *,warehouse.vehicleid FROM warehouse
    WHERE warehouse.customerno =%d AND warehouse.vehicleid=%d AND warehouse.isdeleted=0";
        $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($vehicleid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->type = $row['kind'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->isdeleted = $row['isdeleted'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->average = $row['average'];
                $vehicle->fuelcapacity = $row['fuelcapacity'];
                $vehicle->groupid = $row['groupid'];
                $vehicle->temp1_min = $row['temp1_min'];
                $vehicle->temp1_max = $row['temp1_max'];
                $vehicle->temp2_min = $row['temp2_min'];
                $vehicle->temp2_max = $row['temp2_max'];
            }
            return $vehicle;
        }
        return null;
    }

    public function get_vehicle_with_batch($vehicleid) {
        $Query = "SELECT * FROM batch WHERE customerno=%d AND vehicleid=%d AND isdeleted=0 Order By batchid DESC Limit 1";
        $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($vehicleid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->batchid = $row['batchid'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->customerno = $row['customerno'];
                $vehicle->batchno = $row['batchno'];
                $vehicle->dummybatchno = $row['dummybatch'];
                $vehicle->pmid = $row['pmid'];
                $vehicle->workkey = $row['workkey'];
                $vehicle->starttime = $row['starttime'];
                $vehicle->addedon = $row['addedon'];
                $vehicle->updatedon = $row['updatedon'];
                $vehicle->isdeleted = $row['isdeleted'];
            }
            return $vehicle;
        }
        return null;
    }

    public function getspeedlimit($vehicleid) {
        $Query = "SELECT `overspeed_limit` FROM `vehicle` WHERE vehicleid=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::String($vehicleid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->overspeed_limit = $row['overspeed_limit'];
            }
            return $vehicle;
        }
        return null;
    }

    public function getoverspeed_limit_deviceid($deviceid) {
        $Query = "SELECT `overspeed_limit` FROM `vehicle` INNER JOIN unit ON unit.uid = vehicle.uid INNER JOIN devices ON devices.uid = unit.uid WHERE devices.deviceid=%d AND devices.customerno = %d LIMIT 1";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($deviceid), Sanitise::Long($this->_Customerno));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $limit = $row['overspeed_limit'];
            }
            return $limit;
        }
        return null;
    }

    public function add_vehicle($vehicleno, $kind, $chkids, $fences, $userid, $groupid, $overspeed_limit, $average, $fuelcapacity, $min_temp1 = 0, $max_temp1 = 0, $min_temp2 = 0, $max_temp2 = 0, $min_temp3 = 0, $max_temp3 = 0, $min_temp4 = 0, $max_temp4 = 0, $batch = NULL, $work_key = NULL, $stime = Null, $sdate = Null, $dummybatch = Null, $sel_master = Null, $hum_min = 0, $hum_max = 0, $temp1_allowance = 0, $temp2_allowance = 0, $temp3_allowance = 0, $temp4_allowance = 0, $tempObj = Null) {
        $today = date("Y-m-d H:i:s");
        $loggedInUser = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;
        $Query = "INSERT INTO vehicle (vehicleno,kind,customerno,userid,groupid,overspeed_limit,average,fuelcapacity,temp1_min,temp1_max,temp1_allowance,temp2_min,temp2_max,temp2_allowance,temp3_min,temp3_max,temp3_allowance,temp4_min,temp4_max,temp4_allowance,timestamp,hum_min,hum_max,isStaticTemp1,isStaticTemp2,isStaticTemp3,isStaticTemp4,createdBy,createdOn)
        VALUES ('%s','%s',%d,%d,%d,%d,%d,%f,%d,%f,%f,%f,%f,%f,%f,%f,'%s',%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,'%s')";
        $SQL = sprintf($Query, Sanitise::String($vehicleno), Sanitise::String($kind), $this->_Customerno, Sanitise::Long($userid), Sanitise::Long($groupid), Sanitise::Long($overspeed_limit), Sanitise::Float($average), Sanitise::Long($fuelcapacity), Sanitise::FLoat($min_temp1), Sanitise::Float($max_temp1), Sanitise::Float($temp1_allowance), Sanitise::Float($min_temp2), Sanitise::Float($max_temp2), Sanitise::Float($temp2_allowance), Sanitise::Float($min_temp3), Sanitise::Float($max_temp3), Sanitise::Float($temp3_allowance), Sanitise::Float($min_temp4), Sanitise::Float($max_temp4), Sanitise::Float($temp4_allowance), Sanitise::DateTime($today), Sanitise::Long($hum_min), Sanitise::Long($hum_max), Sanitise::Long($tempObj->staticTemp1), Sanitise::Long($tempObj->staticTemp2), Sanitise::Long($tempObj->staticTemp3), Sanitise::Long($tempObj->staticTemp4), $loggedInUser, $today);
        $this->_databaseManager->executeQuery($SQL);
        $vehicleid = $this->_databaseManager->get_insertedId();
        //events  alerts
        $SQL = sprintf("INSERT INTO eventalerts (vehicleid,overspeed, tamper, powercut, temp,temp2, ac, customerno,createdBy,createdOn) VALUES (%d,0,0,0,0,0,0,%d,%d,'%s')", $vehicleid, $this->_Customerno, $loggedInUser, $today);
        $this->_databaseManager->executeQuery($SQL);
        // ignition alerts
        $SQL = sprintf("INSERT INTO ignitionalert (vehicleid,last_status, count, status, customerno,createdBy,createdOn) VALUES (%d,0,0,0,%d,%d,'%s')", $vehicleid, $this->_Customerno, $loggedInUser, $today);
        $this->_databaseManager->executeQuery($SQL);
        // batch No & Work Key
        if ($_SESSION['customerno'] == 15 || $_SESSION['customerno'] == 21 || $_SESSION['customerno'] == 48) {
            if ($sdate != '' || $stime != '') {
                $starttime = $sdate . ' ' . $stime;
                $starttime = date('Y-m-d H:i:s', strtotime($starttime));
            } else {
                $starttime = '';
            }
            if (!empty($batch) && !empty($work_key)) {
                $SQL = sprintf("INSERT INTO batch(vehicleid, customerno, batchno, workkey, starttime, dummybatch, pmid, addedon, isdeleted,createdBy)VALUES(%d,'%s','%s','%s','%s','%s',%d,'%s',0 ,%d)", $vehicleid, $this->_Customerno, $batch, $work_key, $starttime, $dummybatch, $sel_master, $today, $loggedInUser);
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        // checkpoints
        $Query = "INSERT INTO checkpointmanage (vehicleid,checkpointid,customerno,userid, createdBy,createdOn) VALUES ('%d','%d',%d,%d,%d,'%s')";
        foreach ($chkids as $chkid) {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($chkid), $this->_Customerno, Sanitise::Long($userid), $loggedInUser, $today);
            $this->_databaseManager->executeQuery($SQL);
        }
        // fences
        $Query = "INSERT INTO fenceman (vehicleid,fenceid,customerno,userid,createdBy,createdOn) VALUES (%d,%d,%d,%d,%d,'%s')";
        foreach ($fences as $fence) {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::long($fence), $this->_Customerno, Sanitise::Long($userid), $loggedInUser, $today);
            $this->_databaseManager->executeQuery($SQL);
        }
        if (isset($tempObj)) {
            $tempsen1 = array();
            $tempsen2 = array();
            $tempsen3 = array();
            $tempsen4 = array();
            $tempsen1 = isset($tempObj->tempSenor1) ? $tempObj->tempSenor1 : null;
            $tempsen2 = isset($tempObj->tempSenor2) ? $tempObj->tempSenor2 : null;
            $tempsen3 = isset($tempObj->tempSenor3) ? $tempObj->tempSenor3 : null;
            $tempsen4 = isset($tempObj->tempSenor4) ? $tempObj->tempSenor4 : null;
            /* Temperature Sensor 1 readings*/
            $temp1_range1_start = isset($tempsen1['min_temp_start_1']) ? $tempsen1['min_temp_start_1'] : NULL;
            $temp1_range1_end = isset($tempsen1['max_temp_end_1']) ? $tempsen1['max_temp_end_1'] : NULL;
            $temp1_range1_color = isset($tempsen1['colorpallet1']) ? $tempsen1['colorpallet1'] : NULL;
            $temp1_range2_start = isset($tempsen1['min_temp_start_2']) ? $tempsen1['min_temp_start_2'] : NULL;
            $temp1_range2_end = isset($tempsen1['max_temp_end_2']) ? $tempsen1['max_temp_end_2'] : NULL;
            $temp1_range2_color = isset($tempsen1['colorpallet2']) ? $tempsen1['colorpallet2'] : NULL;
            $temp1_range3_start = isset($tempsen1['min_temp_start_3']) ? $tempsen1['min_temp_start_3'] : NULL;
            $temp1_range3_end = isset($tempsen1['max_temp_end_3']) ? $tempsen1['max_temp_end_3'] : NULL;
            $temp1_range3_color = isset($tempsen1['colorpallet3']) ? $tempsen1['colorpallet3'] : NULL;
            $temp1_range4_start = isset($tempsen1['min_temp_start_4']) ? $tempsen1['min_temp_start_4'] : NULL;
            $temp1_range4_end = isset($tempsen1['max_temp_end_4']) ? $tempsen1['max_temp_end_4'] : NULL;
            $temp1_range4_color = isset($tempsen1['colorpallet4']) ? $tempsen1['colorpallet4'] : NULL;
            /* Temperature Sensor 2 readings*/
            $temp2_range1_start = isset($tempsen2['min_temp_start_1']) ? $tempsen2['min_temp_start_1'] : NULL;
            $temp2_range1_end = isset($tempsen2['max_temp_end_1']) ? $tempsen2['max_temp_end_1'] : NULL;
            $temp2_range1_color = isset($tempsen2['colorpallet1']) ? $tempsen2['colorpallet1'] : NULL;
            $temp2_range2_start = isset($tempsen2['min_temp_start_2']) ? $tempsen2['min_temp_start_2'] : NULL;
            $temp2_range2_end = isset($tempsen2['max_temp_end_2']) ? $tempsen2['max_temp_end_2'] : NULL;
            $temp2_range2_color = isset($tempsen2['colorpallet2']) ? $tempsen2['colorpallet2'] : NULL;
            $temp2_range3_start = isset($tempsen1['min_temp_start_3']) ? $tempsen1['min_temp_start_3'] : NULL;
            $temp2_range3_end = isset($tempsen1['max_temp_end_3']) ? $tempsen1['max_temp_end_3'] : NULL;
            $temp2_range3_color = isset($tempsen1['colorpallet3']) ? $tempsen1['colorpallet3'] : NULL;
            $temp2_range4_start = isset($tempsen1['min_temp_start_4']) ? $tempsen1['min_temp_start_4'] : NULL;
            $temp2_range4_end = isset($tempsen1['max_temp_end_4']) ? $tempsen1['max_temp_end_4'] : NULL;
            $temp2_range4_color = isset($tempsen1['colorpallet4']) ? $tempsen1['colorpallet4'] : NULL;
            /* Temperature Sensor 3 readings*/
            $temp3_range1_start = isset($tempsen3['min_temp_start_1']) ? $tempsen3['min_temp_start_1'] : NULL;
            $temp3_range1_end = isset($tempsen3['max_temp_end_1']) ? $tempsen3['max_temp_end_1'] : NULL;
            $temp3_range1_color = isset($tempsen3['colorpallet1']) ? $tempsen3['colorpallet1'] : "";
            $temp3_range2_start = isset($tempsen3['min_temp_start_2']) ? $tempsen3['min_temp_start_2'] : NULL;
            $temp3_range2_end = isset($tempsen3['max_temp_end_2']) ? $tempsen3['max_temp_end_2'] : NULL;
            $temp3_range2_color = isset($tempsen3['colorpallet2']) ? $tempsen3['colorpallet2'] : NULL;
            $temp3_range3_start = isset($tempsen3['min_temp_start_3']) ? $tempsen3['min_temp_start_3'] : NULL;
            $temp3_range3_end = isset($tempsen3['max_temp_end_3']) ? $tempsen3['max_temp_end_3'] : NULL;
            $temp3_range3_color = isset($tempsen3['colorpallet3']) ? $tempsen3['colorpallet3'] : NULL;
            $temp3_range4_start = isset($tempsen3['min_temp_start_4']) ? $tempsen3['min_temp_start_4'] : NULL;
            $temp3_range4_end = isset($tempsen3['max_temp_end_4']) ? $tempsen3['max_temp_end_4'] : NULL;
            $temp3_range4_color = isset($tempsen3['colorpallet4']) ? $tempsen3['colorpallet4'] : NULL;
            /*Temperature Sensor 4 readings*/
            $temp4_range1_start = isset($tempsen4['min_temp_start_1']) ? $tempsen4['min_temp_start_1'] : NULL;
            $temp4_range1_end = isset($tempsen4['max_temp_end_1']) ? $tempsen4['max_temp_end_1'] : NULL;
            $temp4_range1_color = isset($tempsen4['colorpallet1']) ? $tempsen4['colorpallet1'] : NULL;
            $temp4_range2_start = isset($tempsen4['min_temp_start_2']) ? $tempsen4['min_temp_start_2'] : NULL;
            $temp4_range2_end = isset($tempsen4['max_temp_end_2']) ? $tempsen4['max_temp_end_2'] : NULL;
            $temp4_range2_color = isset($tempsen4['colorpallet2']) ? $tempsen4['colorpallet2'] : NULL;
            $temp4_range3_start = isset($tempsen4['min_temp_start_3']) ? $tempsen4['min_temp_start_3'] : NULL;
            $temp4_range3_end = isset($tempsen4['max_temp_end_3']) ? $tempsen4['max_temp_end_3'] : NULL;
            $temp4_range3_color = isset($tempsen4['colorpallet3']) ? $tempsen4['colorpallet3'] : NULL;
            $temp4_range4_start = isset($tempsen4['min_temp_start_4']) ? $tempsen4['min_temp_start_4'] : NULL;
            $temp4_range4_end = isset($tempsen4['max_temp_end_4']) ? $tempsen4['max_temp_end_4'] : NULL;
            $temp4_range4_color = isset($tempsen4['colorpallet4']) ? $tempsen4['colorpallet4'] : NULL;
            $Query = "INSERT INTO `tempRangeColourMapping` (`vehicleid`, `temp1_range1_start`, `temp1_range1_end`, `temp1_range1_color`, `temp1_range2_start`, `temp1_range2_end`, `temp1_range2_color`, `temp1_range3_start`, `temp1_range3_end`, `temp1_range3_color`, `temp1_range4_start`, `temp1_range4_end`, `temp1_range4_color`, `temp2_range1_start`, `temp2_range1_end`, `temp2_range1_color`, `temp2_range2_start`, `temp2_range2_end`, `temp2_range2_color`, `temp2_range3_start`, `temp2_range3_end`, `temp2_range3_color`, `temp2_range4_start`, `temp2_range4_end`, `temp2_range4_color`, `temp3_range1_start`, `temp3_range1_end`, `temp3_range1_color`, `temp3_range2_start`, `temp3_range2_end`, `temp3_range2_color`, `temp3_range3_start`, `temp3_range3_end`, `temp3_range3_color`, `temp3_range4_start`, `temp3_range4_end`, `temp3_range4_color`, `temp4_range1_start`, `temp4_range1_end`, `temp4_range1_color`, `temp4_range2_start`, `temp4_range2_end`, `temp4_range2_color`, `temp4_range3_start`, `temp4_range3_end`, `temp4_range3_color`, `temp4_range4_start`, `temp4_range4_end`, `temp4_range4_color`, `customerno`, `created_by`, `created_on`, `updated_by`, `updated_on`, `isdeleted`) VALUES(%d, %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s',%d, %d, '%s',%d,'%s',%d)";
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Float($temp1_range1_start), Sanitise::Float($temp1_range1_end), Sanitise::String($temp1_range1_color), Sanitise::Float($temp1_range2_start), Sanitise::Float($temp1_range2_end), Sanitise::String($temp1_range2_color), Sanitise::Float($temp1_range3_start), Sanitise::Float($temp1_range3_end), Sanitise::String($temp1_range3_color), Sanitise::Float($temp1_range4_start), Sanitise::Float($temp1_range4_end), Sanitise::String($temp1_range4_color), Sanitise::Float($temp2_range1_start), Sanitise::Float($temp2_range1_end), Sanitise::String($temp2_range1_color), Sanitise::Float($temp2_range2_start), Sanitise::Float($temp2_range2_end), Sanitise::String($temp2_range2_color), Sanitise::Float($temp2_range3_start), Sanitise::Float($temp2_range3_end), Sanitise::String($temp2_range3_color), Sanitise::Float($temp2_range4_start), Sanitise::Float($temp2_range4_end), Sanitise::String($temp2_range4_color), Sanitise::Float($temp3_range1_start), Sanitise::Float($temp3_range1_end), Sanitise::String($temp3_range1_color), Sanitise::Float($temp3_range2_start), Sanitise::Float($temp3_range2_end), Sanitise::String($temp3_range2_color), Sanitise::Float($temp3_range3_start), Sanitise::Float($temp3_range3_end), Sanitise::String($temp3_range3_color), Sanitise::Float($temp3_range4_start), Sanitise::Float($temp3_range4_end), Sanitise::String($temp3_range4_color), Sanitise::Float($temp4_range1_start), Sanitise::Float($temp4_range1_end), Sanitise::String($temp4_range1_color), Sanitise::Float($temp4_range2_start), Sanitise::Float($temp4_range2_end), Sanitise::String($temp4_range2_color), Sanitise::Float($temp4_range3_start), Sanitise::Float($temp4_range3_end), Sanitise::String($temp4_range3_color), Sanitise::Float($temp4_range4_start), Sanitise::Float($temp4_range4_end), Sanitise::String($temp4_range4_color), 636, Sanitise::Long($userid), Sanitise::Long($today), Sanitise::Long($userid), Sanitise::Long($today), '0');
            $this->_databaseManager->executeQuery($SQL);
        }
    }

    public function add_warehouse($vehicleno, $kind, $userid, $groupid, $overspeed_limit, $average, $fuelcapacity, $min_temp1 = 0, $max_temp1 = 0, $min_temp2 = 0, $max_temp2 = 0, $batch = NULL, $work_key = NULL, $stime = Null, $sdate = Null, $dummybatch = Null, $sel_master = Null) {
        $today = date("Y-m-d H:i:s");
        $Query = "INSERT INTO warehosue (vehicleno,kind,customerno,userid,groupid,overspeed_limit,average,fuelcapacity,temp1_min,temp1_max,temp2_min,temp2_max,timestamp) VALUES ('%s','%s',%d,%d,%d,%d,%d,%f,%f,%f,%f,%f,'%s')";
        $SQL = sprintf($Query, Sanitise::String($vehicleno), Sanitise::String($kind), $this->_Customerno, Sanitise::Long($userid), Sanitise::Long($groupid), Sanitise::Long($overspeed_limit), Sanitise::Float($average), Sanitise::Long($fuelcapacity), Sanitise::Float($min_temp1), Sanitise::Float($max_temp1), Sanitise::Float($min_temp2), Sanitise::Float($max_temp2), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($SQL);
        $vehicleid = $this->_databaseManager->get_insertedId();
        //events  alerts
        $SQL = sprintf("INSERT INTO eventalerts (vehicleid,overspeed, tamper, powercut, temp,temp2, ac, customerno) VALUES (%d,0,0,0,0,0,0,%d)", $vehicleid, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        // ignition alerts
        $SQL = sprintf("INSERT INTO ignitionalert (vehicleid,last_status, count, status, customerno) VALUES (%d,0,0,0,%d)", $vehicleid, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function addFuel($vehicleid, $fuelstorrage, $average, $userid, $sdate, $stime, $fueltank) {
        $date = $sdate . ' ' . $stime;
        $date1 = date("Y-m-d H:i:s", strtotime($date));
        $today = date("Y-m-d H:i:s");
        $Query = "INSERT INTO fuelstorrage(fuel,vehicleid,customerno,userid,addedon,timestamp)VALUES(%f,%d,%d,%d,'%s','%s')";
        $SQL = sprintf($Query, Sanitise::Float($fuelstorrage), Sanitise::Long($vehicleid), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($date1), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($SQL);
        $Que = "UPDATE vehicle SET fuel_balance = fuel_balance + %f, fuelcapacity=%d WHERE vehicleid = %d";
        $sql = sprintf($Que, $fuelstorrage, Sanitise::Long($fueltank), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($sql);
        if ($average) {
            $Query = "Update vehicle SET average=%d WHERE vehicleid = %d";
            $SQL = sprintf($Query, Sanitise::Float($average), Sanitise::Long($vehicleid));
            $this->_databaseManager->executeQuery($SQL);
        }
    }

    public function addBuzzer($unitno, $customerno, $userid) {
        $loggedInUserId = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;
        $todate = date("Y-m-d H:i:s");
        $Que = "UPDATE unit SET  setcom = 1, command='BUZZ=30',updatedBy=%d,updatedOn='%s' WHERE unitno=%d AND customerno=%d";
        $sql = sprintf($Que, $loggedInUser, $todate, $unitno, $customerno);
        $this->_databaseManager->executeQuery($sql);
        $responce = "Ok";
        $getdata = $this->getvehicledetail($unitno, $customerno);
        if (isset($getdata)) {
            $uid = $getdata['uid'];
            $devicelat = $getdata['devicelat'];
            $devicelong = $getdata['devicelong'];
            $vehicleid = $getdata['vehicleid'];
            $today = date("Y-m-d H:i:s");
            $sql = "INSERT INTO buzzerlog (uid, vehicleid, devicelat,devicelong,customerno,createdby ,createdon) "
                . "VALUES ('" . $uid . "','" . $vehicleid . "','" . $devicelat . "','" . $devicelong . "','" . $customerno . "','" . $userid . "','" . $today . "')";
            $this->_databaseManager->executeQuery($sql);
        }
        return $responce;
    }

    public function muteVehicleTemperature($vehicle) {
        if (isset($vehicle->temp)) {
            $temp = 'temp' . $vehicle->temp . '_mute';
            if ($vehicle->condition == 'Mute') {
                $Que = "UPDATE vehicle SET  $temp = 1 WHERE vehicleid=%d AND customerno=%d";
                // Not used as it is done in Listener
                // $this->temp_mute(1,$vehicle->vehicleid,$this->_Customerno,$vehicle->temp);
            } elseif ($vehicle->condition == 'Unmute') {
                $Que = "UPDATE vehicle SET  $temp = '0' WHERE vehicleid=%d AND customerno=%d";
                // Not used as it is done in Listener
                // $this->temp_mute(2,$vehicle->vehicleid,$this->_Customerno,$vehicle->temp);
            }
            $sql = sprintf($Que, $vehicle->vehicleid, $this->_Customerno);
            $this->_databaseManager->executeQuery($sql);
            $responce = "Ok";
        } else {
            if ($vehicle->condition == 'Mute') {
                $Que = "UPDATE vehicle SET  temp1_mute = 1, temp2_mute = 1, temp3_mute = 1, temp4_mute = 1 WHERE vehicleid=%d AND customerno=%d";
            } elseif ($vehicle->condition == 'Unmute') {
                $Que = "UPDATE vehicle SET  temp1_mute = 0, temp2_mute = 0, temp3_mute = 0, temp4_mute = 0 WHERE vehicleid=%d AND customerno=%d";
            }
            $sql = sprintf($Que, $vehicle->vehicleid, $this->_Customerno);
            $this->_databaseManager->executeQuery($sql);
            $responce = "Ok";
        }
        return $responce;
    }

    public function temp_mute($status, $vehicleid, $customerno, $temp_type) {
        $vehicledetails = $this->get_vehicle_details($vehicleid);
        $uid = $vehicledetails->uid;
        $today = date("Y-m-d H:i:s");
        if ($status == 1) {
            $Query = "INSERT INTO temp_mute(vehicleid,uid,customerno,temp_type,mute_starttime,timestamp) VALUES('" . $vehicleid . "','" . $uid . "'," . $this->_Customerno . "," . $temp_type . ",'" . $today . "','" . $today . "')";
            $SQL = sprintf($Query);
            $this->_databaseManager->executeQuery($SQL);
        } else {
            $Query = "select muteid,vehicleid,uid,customerno from temp_mute where vehicleid=" . $vehicleid . " AND customerno=" . $this->_Customerno . " order by  muteid desc limit 0,1";
            $SQL = sprintf($Query);
            $this->_databaseManager->executeQuery($SQL);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()) {
                    $muteidold = $row['muteid'];
                    $vehicleidold = $row['vehicleid'];
                    $uidold = $row['uid'];
                    $customernoold = $row['customerno'];
                }
                $query = "UPDATE  temp_mute SET  mute_endtime ='" . $today . "' WHERE muteid = " . $muteidold . " AND vehicleid=" . $vehicleidold . " AND customerno=" . $customernoold;
                $SQL1 = sprintf($query);
                $this->_databaseManager->executeQuery($SQL1);
            }
        }
    }

    public function getvehicledetail($unitno, $customerno) {
        $data = array();
        $query = "SELECT u.uid,u.vehicleid,d.deviceid,d.lastupdated,d.devicelat, d.devicelong FROM unit as u INNER JOIN devices as d ON d.uid = u.uid WHERE u.customerno='" . $customerno . "' AND u.unitno ='" . $unitno . "'";
        $sql = sprintf($query);
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $data = array(
                    "devicelat" => $row['devicelat'],
                    "devicelong" => $row['devicelong'],
                    "uid" => $row['uid'],
                    "vehicleid" => $row['vehicleid'],
                    "deviceid" => $row['deviceid']
                );
            }
            return $data;
        }
        return NULL;
    }

    public function unfreezedvehicle($unitno, $customerno, $userid, $alertSent = null) {
        $loggedInUserId = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;
        $todate = date("Y-m-d H:i:s");
        $getdata = $this->getvehicledetail($unitno, $customerno);
        if (isset($getdata)) {
            $uid_freeze = $getdata['uid'];
            $devicelat_freeze = $getdata['devicelat'];
            $devicelong_freeze = $getdata['devicelong'];
            $today = date("Y-m-d H:i:s");
            if ($customerno != 135) {
                $Que = "UPDATE unit set is_freeze=0,updatedBy=%d,updatedOn='%s' where uid = " . $uid_freeze;
                $sql = sprintf($Que, $loggedInUserId, $todate);
                $this->_databaseManager->executeQuery($sql);
                $sql = "update freezelog set isdeleted=1,updatedon='" . $today . "', updatedby='" . $userid . "' where uid='" . $uid_freeze . "' AND isdeleted=0";
                $test = sprintf($sql);
                $this->_databaseManager->executeQuery($test);
                $Alertsql = "update freezelog set isAlertSent=1,updatedon='" . $today . "', updatedby='" . $userid . "' where uid='" . $uid_freeze . "' AND isdeleted=0";
                $test_new = sprintf($Alertsql);
                $this->_databaseManager->executeQuery($test_new);
            } // Customer 135 wants only manual deletion of freezlog.
            else {
                $Alertsql = "update freezelog set isAlertSent=1,updatedon='" . $today . "', updatedby='" . $userid . "' where uid='" . $uid_freeze . "' AND isdeleted=0";
                $test_new = sprintf($Alertsql);
                $this->_databaseManager->executeQuery($test_new);
            }
        }
        $responce = "Ok";
        return $responce;
    }

    public function freezedvehicle($unitno, $customerno, $userid) {
        $loggedInUserId = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;
        $todate = date("Y-m-d H:i:s");
        $getdata = $this->getvehicledetail($unitno, $customerno);
        if (isset($getdata)) {
            $uid_freeze = $getdata['uid'];
            $devicelat_freeze = $getdata['devicelat'];
            $devicelong_freeze = $getdata['devicelong'];
            $vehicleid_freeze = $getdata['vehicleid'];
            $odometer = $this->getvehicledata_by_vehicleid($vehicleid_freeze);
            $today = date("Y-m-d H:i:s");
            $Que = "UPDATE unit set is_freeze=1,updatedBy=%d,updatedOn='%s' where uid = " . $uid_freeze;
            $sql = sprintf($Que, $loggedInUserId, $todate);
            $this->_databaseManager->executeQuery($sql);
            $sql = "INSERT INTO  freezelog (uid,vehicleid,devicelat,devicelong,odometer,customerno,createdby,createdon,updatedby,updatedon)VALUES ('" . $uid_freeze . "','" . $vehicleid_freeze . "','" . $devicelat_freeze . "','" . $devicelong_freeze . "','" . $odometer . "','" . $customerno . "','" . $userid . "','" . $today . "','" . $userid . "','" . $today . "')";
            $SQL = sprintf($sql);
            $this->_databaseManager->executeQuery($SQL);
        }
        $responce = "Ok";
        return $responce;
    }

    public function immobilise_vehicle($unitno, $customerno, $command, $userid) {
        $loggedInUserId = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;
        $todate = date("Y-m-d H:i:s");
        if ($command == 'STARTV') {
            $flag = 0;
        } else {
            $flag = 1;
        }
        $Que = "UPDATE unit SET  setcom = 1, command='%s', mobiliser_flag=%d,updatedBy=%d,updatedOn='%s'  WHERE unitno=%d AND customerno=%d";
        $sql = sprintf($Que, $command, $flag, $loggedInUser, $todate, $unitno, $customerno);
        $this->_databaseManager->executeQuery($sql);
        $getdata = $this->getvehicledetail($unitno, $customerno);
        if (isset($getdata)) {
            $uid = $getdata['uid'];
            $devicelat = $getdata['devicelat'];
            $devicelong = $getdata['devicelong'];
            $vehicleid = $getdata['vehicleid'];
            $today = date("Y-m-d H:i:s");
            $sql = "INSERT INTO immobiliserlog (uid, vehicleid, devicelat,devicelong,commandname,mobiliser_flag,customerno,createdby ,createdon)"
                . "VALUES ('" . $uid . "','" . $vehicleid . "','" . $devicelat . "','" . $devicelong . "','" . $command . "','" . $flag . "','" . $customerno . "','" . $userid . "','" . $today . "')";
            $this->_databaseManager->executeQuery($sql);
        }
        $responce = "Ok";
        return $responce;
    }

    public function addDriver($vehicleid, $userid, $driverid, $olddriverid) {
        $query = "SELECT vehicleid from vehicle where driverid = %d";
        $SQL = sprintf($query, $driverid);
        $this->_databaseManager->executeQuery($SQL);
        $data = $this->_databaseManager->get_nextRow();
        $vid = $data['vehicleid'];
        $sql = "update vehicle SET driverid = %d WHERE vehicleid = %d AND customerno=%d";
        $query = sprintf($sql, $driverid, $vehicleid, $this->_Customerno);
        $this->_databaseManager->executeQuery($query);
        $Query = "Update driver SET customerno=%d, vehicleid=%d, userid=%d WHERE driverid = %d ";
        $Sql = sprintf($Query, $this->_Customerno, $vehicleid, $userid, $driverid);
        $this->_databaseManager->executeQuery($Sql);
        $sql = "SELECT * from driver where drivername ='Not Allocated' and customerno =%d and vehicleid = 0 and isdeleted=0";
        $query = sprintf($sql, $this->_Customerno);
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $did = $row['driverid'];
            }
        }
        $sql = "Update vehicle SET driverid  = %d where vehicleid = %d";
        $query = sprintf($sql, $did, $vid);
        $this->_databaseManager->executeQuery($query);
        $sql = "Update driver SET vehicleid  = %d where driverid = %d";
        $query = sprintf($sql, $vid, $did);
        $this->_databaseManager->executeQuery($query);
        $sql1 = "update driver SET vehicleid = '0' WHERE driverid=%d";
        $query1 = sprintf($sql1, $olddriverid);
        $this->_databaseManager->executeQuery($query1);
        /*$varOldVehicleId is Not 0*/
        $todaysDate = DATE(speedConstants::DEFAULT_TIMESTAMP);
        if (isset($vid) && $vid != 0) {
            $Query = "INSERT INTO driverVehicleHistoricMapping(driverId, vehicleId, customerNo, createdBy, createdOn)VALUES(%d,%d,%d,%d,'%s')";
            $SQL = sprintf($Query, Sanitise::Long($driverid), Sanitise::Long($vid), $this->_Customerno, Sanitise::Long($userid), Sanitise::DATETIME($todaysDate));
            $this->_databaseManager->executeQuery($SQL);
        }
        $responce = "ok";
        return $responce;
    }

    public function addNewDriver($vehicleid, $userid, $dname, $dlic, $dphone, $olddriverid) {
        $addQuery = "INSERT INTO driver(drivername, driverlicno, driverphone, customerno, userid, isdeleted)VALUES('%s', '%s', %d, %d, %d, 0)";
        $SQL = sprintf($addQuery, $dname, $dlic, $dphone, $this->_Customerno, $userid);
        $this->_databaseManager->executeQuery($SQL);
        $driverid = $this->_databaseManager->get_insertedId();
        $sql = "update vehicle SET driverid = %d WHERE vehicleid = %d AND customerno=%d";
        $query = sprintf($sql, $driverid, $vehicleid, $this->_Customerno);
        $this->_databaseManager->executeQuery($query);
        $Query = "Update driver SET customerno=%d, vehicleid=%d, userid=%d WHERE driverid = %d ";
        $Sql = sprintf($Query, $this->_Customerno, $vehicleid, $userid, $driverid);
        $this->_databaseManager->executeQuery($Sql);
        $sql1 = "update driver SET vehicleid = '0' WHERE driverid=%d";
        $query1 = sprintf($sql1, $olddriverid);
        $this->_databaseManager->executeQuery($query1);
        $responce = "ok";
        return $responce;
    }

    public function add_geo($vehicleid, $checkpoints, $fences, $userid) {
        $Query = "SELECT * FROM `checkpointmanage` where vehicleid=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $Query = "UPDATE checkpointmanage SET isdeleted=1,userid=%d WHERE vehicleid =%d AND customerno =%d";
            $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);
        }
// chyeckpoints
        $Query = "INSERT INTO checkpointmanage (vehicleid,checkpointid,customerno,userid) VALUES ('%d','%d',%d,%d)";
        foreach ($checkpoints as $chkid) {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($chkid), $this->_Customerno, Sanitise::Long($userid));
            $this->_databaseManager->executeQuery($SQL);
        }
        $Query = "SELECT * FROM `fenceman` where vehicleid=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $Query = "UPDATE fenceman SET isdeleted=1,userid=%d WHERE vehicleid =%d AND customerno =%d";
            $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);
        }
// fences
        $Query = "INSERT INTO fenceman (vehicleid,fenceid,customerno,userid) VALUES (%d,%d,%d,%d)";
        foreach ($fences as $fence) {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::long($fence), $this->_Customerno, Sanitise::Long($userid));
            $this->_databaseManager->executeQuery($SQL);
        }
        $vehicle = 'ok';
        return $vehicle;
    }

    public function edit_geo($vehicleid, $checkpoints, $fences, $userid) {
        $Query = "UPDATE checkpointmanage SET isdeleted=1,userid=%d WHERE vehicleid =%d AND customerno =%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
// checkpoints
        $Query = "INSERT INTO checkpointmanage (vehicleid,checkpointid,customerno,userid) VALUES ('%d','%d',%d,%d)";
        foreach ($checkpoints as $chkid) {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($chkid), $this->_Customerno, Sanitise::Long($userid));
            $this->_databaseManager->executeQuery($SQL);
        }
        $Query = "UPDATE fenceman SET isdeleted=1,userid=%d WHERE vehicleid =%d AND customerno =%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
// fences
        $Query = "INSERT INTO fenceman (vehicleid,fenceid,customerno,userid) VALUES (%d,%d,%d,%d)";
        foreach ($fences as $fence) {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::long($fence), $this->_Customerno, Sanitise::Long($userid));
            $this->_databaseManager->executeQuery($SQL);
        }
        $today = date("Y-m-d H:i:s");
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL1);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function addvehicle($form, $userid) {
        if ($_SESSION['use_hierarchy'] == '0') {
            //$form['branchid'] = 0;
        }
        $taxDate = $permitDate = $fitdate = "";
        $PDate = date('Y-m-d', strtotime($form['PDate']));
        $taxDate = isset($form['taxDate']) ? date('Y-m-d', strtotime($form['taxDate'])) : "";
        $permitDate = isset($form['permitDate']) ? date('Y-m-d', strtotime($form['permitDate'])) : "";
        $fitdate = isset($form['fitDate']) ? date('Y-m-d', strtotime($form['fitDate'])) : "";
        $today = date("Y-m-d H:i:s");
        $invoice_date = date('Y-m-d', strtotime($form['invoice_date']));
        if (isset($form['vehicle_added_id']) && $form['vehicle_added_id'] != '') {
            $Query = "UPDATE vehicle set vehicleno='%s',kind='%s',userid=%d,groupid=%d,overspeed_limit=%d,modelid=%d,manufacturing_month=%d,manufacturing_year=%d,purchase_date='%s',tax_date ='%s',permit_date='%s',fitness_date ='%s', start_meter_reading=%d,fueltype='%s', fulecapacity=%d, timestamp='%s' WHERE `vehicleid`=%d AND customerno=%d";
            $SQL = sprintf($Query, Sanitise::String($form['vehicle_no']), Sanitise::String($form['kind']), Sanitise::Long($userid), Sanitise::Long($form['branchid']), Sanitise::Long($form['overspeed']), Sanitise::Long($form['model']), Sanitise::Long($form['monthofman']), Sanitise::Long($form['yearofman']), Sanitise::Date($PDate), Sanitise::Date($taxDate), Sanitise::Date($permitDate), Sanitise::Date($fitdate), Sanitise::Long($form['start_meter']), Sanitise::String($form['fueltype']), Sanitise::Long($form['ftcap']), Sanitise::DateTime($today), Sanitise::Long($form['vehicle_added_id']), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);
            $vehicleid = $form['vehicle_added_id'];
        } else {
            $Query = "INSERT INTO vehicle (vehicleno,kind,customerno,userid,groupid,overspeed_limit,modelid,manufacturing_month,"
                . " manufacturing_year,purchase_date,start_meter_reading,fueltype,fuelcapacity,tax_date,permit_date,fitness_date,status_id,timestamp,rto_location,current_location,authorized_signatory,hypothecation,serial_number,"
                . " expiry_date,owner_name) "
                . " VALUES ('%s','%s','%s',%d,%d,%d,%d,%d,%d,'%s',%d,'%s',%d,'%s','%s','%s','5','%s','%s','%s','%s','%s','%s','%s','%s') ";
            $SQL = sprintf($Query, Sanitise::String($form['vehicle_no']), Sanitise::String($form['kind']), $this->_Customerno, Sanitise::Long($userid), Sanitise::Long($form['branchid']), Sanitise::Long($form['overspeed']), Sanitise::Long($form['model']), Sanitise::Long($form['monthofman']), Sanitise::Long($form['yearofman']), Sanitise::Date($PDate), Sanitise::Long($form['start_meter']), Sanitise::String($form['fueltype']), Sanitise::Long($form['ftcap']), Sanitise::String($taxDate), Sanitise::String($permitDate), Sanitise::String($fitdate), Sanitise::DateTime($today), Sanitise::String($form['rto_location']), Sanitise::String($form['current_location']), Sanitise::String($form['auth_signatory']), Sanitise::String($form['hypothecation']), Sanitise::String($form['serial_number']), Sanitise::Date($form['expiry_date']), Sanitise::String($form['owner_name']));
            $this->_databaseManager->executeQuery($SQL);
            $vehicleid = $this->_databaseManager->get_insertedId();
//events  alerts
            $SQL1 = sprintf("INSERT INTO eventalerts (vehicleid,overspeed, tamper, powercut, temp, temp2, ac, customerno) VALUES (%d,0,0,0,0,0,0,%d)", $vehicleid, $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL1);
// ignition alerts
            $SQL2 = sprintf("INSERT INTO ignitionalert (vehicleid,last_status, count, status, customerno) VALUES (%d,0,0,0,%d)", $vehicleid, $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL2);
// Insert Description
            if ($form['engineno'] !== '') {
                $Query = "INSERT INTO description (vehicleid,customerno,engineno,chasisno,vehicle_purpose,vehicle_type,dealerid,invoiceno,invoicedate,invoiceamt,seatcapacity) VALUES (%d,%d,'%s','%s',%d,%d,%d,'%s','%s',%d,'%s')";
                $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno, Sanitise::String($form['engineno']), Sanitise::String($form['chasisno']), Sanitise::Long($form['veh_purpose']), Sanitise::Long($form['veh_type']), Sanitise::Long($form['dealerid']), Sanitise::String($form['invoiceno']), Sanitise::Date($invoice_date), Sanitise::Long($form['invoiceamt']), Sanitise::String($form['seatcapacity']));
                $this->_databaseManager->executeQuery($SQL);
            }
// Insert Insurance
            if ($form['insurance_value'] != '') {
                $Start_date = date('Y-m-d', strtotime($form['StartDate']));
                $End_date = date('Y-m-d', strtotime($form['EndDate']));
                $Query = "INSERT INTO `insurance` (value,premium,start_date,end_date,amount,notes,companyid,claim_place,vehicleid,idv,ncb,customerno,policy_no,ins_dealerid) VALUES (%d,%d,'%s','%s',%d,'%s',%d,'%s',%d,%d,%d,%d,'%s',%d)";
                $SQL = sprintf($Query, Sanitise::Long($form['insurance_value']), Sanitise::Long($form['premium_value']), Sanitise::Date($Start_date), Sanitise::Date($End_date), Sanitise::Long($form['ins_amount']), Sanitise::String($form['ins_notes']), Sanitise::Long($form['insurance_company']), Sanitise::String($form['near_place']), Sanitise::Long($vehicleid), Sanitise::Long($form['ide']), Sanitise::Long($form['enb']), $this->_Customerno, Sanitise::String($form['polno']), Sanitise::Long($form['insdealerid']));
                $this->_databaseManager->executeQuery($SQL);
            }
// INSERT Loan
            if ($form['margin_amt'] != '') {
                $Start_date = date('Y-m-d', strtotime($form['StartDateloan']));
                $End_date = date('Y-m-d', strtotime($form['EndDateloan']));
                if ($form['emidate'] != '') {
                    $Emi_date = date('Y-m-d', strtotime($form['emidate']));
                } else {
                    $Emi_date = '';
                }
                $Query = "INSERT INTO loan(vehicleid,marginamt, loanamt, financier, emiamt, tennure, start_date, end_date,loan_accountno,emidate) VALUES(%d,%d,%d,'%s',%d,%d,'%s','%s','%s','%s')";
                $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($form['margin_amt']), Sanitise::Long($form['loan_amt']), Sanitise::String($form['financier']), Sanitise::Long($form['emi_amt']), Sanitise::Long($form['loan_tenure']), Sanitise::DateTime($Start_date), Sanitise::DateTime($End_date), Sanitise::String($form['loan_accno']), Sanitise::DateTime($Emi_date));
                $this->_databaseManager->executeQuery($SQL);
            }
// Insert Capitalization
            if ($form['cap_EDate'] != '') {
                $End_date_cap = date('Y-m-d', strtotime($form['cap_EDate']));
                $Query = "INSERT INTO `capitalization` (address,cost,date,vehicleid,userid,customerno) VALUES ('%s','%s','%s',%d,%d,%d)";
                $SQL = sprintf($Query, Sanitise::String($form['cap_address']), Sanitise::String($form['cap_cost']), Sanitise::Date($End_date_cap), Sanitise::Long($vehicleid), Sanitise::Long($_SESSION['userid']), $this->_Customerno);
                $this->_databaseManager->executeQuery($SQL);
            }
// Insert tyre type
            if ($form['right_front'] != '') {
                $insdate = '';
                if ($form['rf_insdate'] != '') {
                    $insdate = date("Y-m-d", strtotime($form['rf_insdate']));
                }
                $Query = "INSERT INTO `maintenance_maptyre` (vehicleid,customerno,tyreid,serialno,installedon,createdby,createdon,updatedby,updatedon) VALUES (%d,%d,%d,'%s','%s',%d,'%s',%d,'%s')";
                $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno, 1, $form['right_front'], $insdate, Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today), Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($SQL);
            }
            if ($form['left_front'] != '') {
                $insdate = '';
                if ($form['lf_insdate'] != '') {
                    $insdate = date("Y-m-d", strtotime($form['lf_insdate']));
                }
                $Query = "INSERT INTO `maintenance_maptyre` (vehicleid,customerno,tyreid,serialno,installedon,createdby,createdon,updatedby,updatedon) VALUES (%d,%d,%d,'%s','%s',%d,'%s',%d,'%s')";
                $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno, 2, $form['left_front'], $insdate, Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today), Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($SQL);
            }
            if ($form['right_back_out'] != '') {
                $insdate = '';
                if ($form['rbout_insdate'] != '') {
                    $insdate = date("Y-m-d", strtotime($form['rbout_insdate']));
                }
                $Query = "INSERT INTO `maintenance_maptyre` (vehicleid,customerno,tyreid,serialno,installedon,createdby,createdon,updatedby,updatedon) VALUES (%d,%d,%d,'%s','%s',%d,'%s',%d,'%s')";
                $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno, 3, $form['right_back_out'], $insdate, Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today), Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($SQL);
            }
            if ($form['left_back_out'] != '') {
                $insdate = '';
                if ($form['lbout_insdate'] != '') {
                    $insdate = date("Y-m-d", strtotime($form['lbout_insdate']));
                }
                $Query = "INSERT INTO `maintenance_maptyre` (vehicleid,customerno,tyreid,serialno,installedon,createdby,createdon,updatedby,updatedon) VALUES (%d,%d,%d,'%s','%s',%d,'%s',%d,'%s')";
                $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno, 4, $form['left_back_out'], $insdate, Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today), Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($SQL);
            }
            if ($form['stepney'] != '') {
                $insdate = '';
                if ($form['st_insdate'] != '') {
                    $insdate = date("Y-m-d", strtotime($form['st_insdate']));
                }
                $Query = "INSERT INTO `maintenance_maptyre` (vehicleid,customerno,tyreid,serialno,installedon,createdby,createdon,updatedby,updatedon) VALUES (%d,%d,%d,'%s','%s',%d,'%s',%d,'%s')";
                $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno, 5, $form['stepney'], $insdate, Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today), Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($SQL);
            }
            if ($form['right_back_in'] != '') {
                $insdate = '';
                if ($form['rbin_insdate'] != '') {
                    $insdate = date("Y-m-d", strtotime($form['rbin_insdate']));
                }
                $Query = "INSERT INTO `maintenance_maptyre` (vehicleid,customerno,tyreid,serialno,installedon,createdby,createdon,updatedby,updatedon) VALUES (%d,%d,%d,'%s','%s',%d,'%s',%d,'%s')";
                $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno, 6, $form['right_back_in'], $insdate, Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today), Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($SQL);
            }
            if ($form['left_back_in'] != '') {
                $insdate = '';
                if ($form['lbin_insdate'] != '') {
                    $insdate = date("Y-m-d", strtotime($form['lbin_insdate']));
                }
                $Query = "INSERT INTO `maintenance_maptyre` (vehicleid,customerno,tyreid,serialno,installedon,createdby,createdon,updatedby,updatedon) VALUES (%d,%d,%d,'%s','%s',%d,'%s',%d,'%s')";
                $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno, 7, $form['left_back_in'], $insdate, Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today), Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($SQL);
            }
//insert battery srno
            if ($form['battsrno'] != '') {
                $insdate = '';
                if ($form['battsrno_insdate'] != '') {
                    $insdate = date("Y-m-d", strtotime($form['battsrno_insdate']));
                }
                $Query = "INSERT INTO `maintenance_mapbattery` (vehicleid,customerno,batt_serialno,installedon,createdby,createdon,updatedby,updatedon) VALUES (%d,%d,'%s','%s',%d,'%s',%d,'%s')";
                $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno, $form['battsrno'], $insdate, Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today), Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($SQL);
            }
// Insert Geotags
            $checkpoints = array();
            $fences = array();
            foreach ($form as $single_post_name => $single_post_value) {
                if (substr($single_post_name, 0, 14) == "to_checkpoint_") {
                    $checkpoints[] = substr($single_post_name, 14, 25);
                }
            }
            foreach ($form as $single_post_name => $single_post_value) {
                if (substr($single_post_name, 0, 9) == "to_fence_") {
                    $fences[] = substr($single_post_name, 9, 25);
                }
            }
            if (!empty($checkpoints)) {
                $Query = "INSERT INTO checkpointmanage (vehicleid,checkpointid,customerno,userid) VALUES ('%d','%d',%d,%d)";
                foreach ($checkpoints as $chkid) {
                    $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($chkid), $this->_Customerno, Sanitise::Long($_SESSION['userid']));
                    $this->_databaseManager->executeQuery($SQL);
                }
            }
            if (!empty($fences)) {
                $Query = "INSERT INTO fenceman (vehicleid,fenceid,customerno,userid) VALUES (%d,%d,%d,%d)";
                foreach ($fences as $fence) {
                    $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::long($fence), $this->_Customerno, Sanitise::Long($_SESSION['userid']));
                    $this->_databaseManager->executeQuery($SQL);
                }
            }
        }
        if ($vehicleid == '') {
            $vehicle = 'notok';
        } else {
            $vehicle = $vehicleid;
            $_SESSION['vehicle_id'] = $vehicleid;
        }
        return $vehicle;
    }

    public function editvehicle($form, $userid) {
        if ($_SESSION['use_hierarchy'] == '0') {
            //$form['branchid'] = 0;
        }
        //$inputs = array('PDate','RDate','edit_vehicle_no','kind', );
        /* $form = array();
        foreach($form_rough as $key=>$value){
        $form[$key] =  $value;
         */
        $PDate = date('Y-m-d', strtotime($form['PDate']));
        $taxDate = isset($form['taxDate']) ? date('Y-m-d', strtotime($form['taxDate'])) : "";
        $permitDate = isset($form['permitDate']) ? date('Y-m-d', strtotime($form['permitDate'])) : "";
        $fitDate = isset($form['fitDate']) ? date('Y-m-d', strtotime($form['fitDate'])) : "";
        $RDate = isset($form['RDate']) ? date('Y-m-d', strtotime($form['RDate'])) : "";
        $today = date("Y-m-d H:i:s");
        $Query = "UPDATE vehicle set vehicleno='%s',kind='%s',userid=%d,groupid=%d,overspeed_limit=%d,modelid=%d,manufacturing_month=%d,manufacturing_year=%d,purchase_date='%s',registration_date='%s',start_meter_reading=%d,fueltype='%s',fuelcapacity=%d, status_id='5',timestamp='%s',rto_location='%s',current_location='%s',authorized_signatory='%s',hypothecation='%s',serial_number='%s',expiry_date='%s',tax_date='%s',permit_date='%s',fitness_date='%s',owner_name='%s' WHERE `vehicleid`=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::String($form['edit_vehicle_no']), Sanitise::String($form['kind']), Sanitise::Long($userid), Sanitise::Long($form['branchid']), Sanitise::Long($form['overspeed']), Sanitise::Long($form['model']), Sanitise::Long($form['monthofman']), Sanitise::Long($form['yearofman']), Sanitise::Date($PDate), Sanitise::Date($RDate), Sanitise::Long($form['start_meter']), Sanitise::String($form['fueltype']), Sanitise::Long($form['ftcap']), Sanitise::DateTime($today), Sanitise::String($form['rto_location']), Sanitise::String($form['current_location']), Sanitise::String($form['auth_signatory']), Sanitise::String($form['hypothecation']), Sanitise::String($form['serial_number']), Sanitise::Date($form['expiry_date']), Sanitise::Date($taxDate), Sanitise::Date($permitDate), Sanitise::Date($fitDate), Sanitise::String($form['owner_name']), Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        // Edit Description
        if ($form['edit_engineno'] != '') {
            $invoice_date = date('Y-m-d', strtotime($form['invoice_date']));
            $Query = "SELECT * FROM `description` where vehicleid=%d";
            $vehiclesQuery = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']));
            $this->_databaseManager->executeQuery($vehiclesQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                $Query = "UPDATE description set engineno='%s',chasisno='%s',vehicle_purpose=%d,vehicle_type=%d,dealerid=%d,invoiceno='%s',invoicedate='%s', invoiceamt='%s', seatcapacity='%s' WHERE vehicleid=%d";
                $SQL = sprintf($Query, Sanitise::String($form['edit_engineno']), Sanitise::String($form['chasisno']), Sanitise::Long($form['veh_purpose']), Sanitise::Long($form['veh_type']), Sanitise::Long($form['dealerid']), Sanitise::String($form['invoiceno']), Sanitise::Date($invoice_date), Sanitise::String($form['invoiceamt']), Sanitise::String($form['seatcapacity']), Sanitise::Long($form['edit_vehicle_id']));
                $this->_databaseManager->executeQuery($SQL);
            } else {
                $Query = "INSERT INTO description (vehicleid,engineno,chasisno,vehicle_purpose,vehicle_type,dealerid,invoiceno,invoicedate,invoiceamt,seatcapacity) VALUES (%d,'%s','%s',%d,%d,%d,'%s','%s',%d, '%s')";
                $SQL = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), Sanitise::String($form['edit_engineno']), Sanitise::String($form['chasisno']), Sanitise::Long($form['veh_purpose']), Sanitise::Long($form['veh_type']), Sanitise::Long($form['dealerid']), Sanitise::String($form['invoiceno']), Sanitise::Date($invoice_date), Sanitise::Long($form['invoiceamt']), Sanitise::String($form['seatcapacity']));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        // Edit Insurance
        if ($form['insurance_value'] != '') {
            $Start_date = date('Y-m-d', strtotime($form['StartDate']));
            $End_date = date('Y-m-d', strtotime($form['EndDate']));
            $Query = "SELECT * FROM `insurance` where vehicleid=%d";
            $vehiclesQuery = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']));
            $this->_databaseManager->executeQuery($vehiclesQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                $Query = "UPDATE `insurance` set value = %d,premium = %d,start_date = '%s',end_date = '%s',notes = '%s',companyid = %d,claim_place = '%s', ncb = %d , policy_no = '%s', ins_dealerid = %d WHERE vehicleid = %d";
                $SQL = sprintf($Query, Sanitise::Long($form['insurance_value']), Sanitise::Long($form['premium_value']), Sanitise::Date($Start_date), Sanitise::Date($End_date), Sanitise::String($form['ins_notes']), Sanitise::Long($form['edit_insurance_company']), Sanitise::String($form['near_place']), Sanitise::Long($form['enb']), Sanitise::String($form['polno']), Sanitise::Long($form['insdealerid']), Sanitise::Long($form['edit_vehicle_id']));
                $this->_databaseManager->executeQuery($SQL);
            } else {
                $Query = "INSERT INTO `insurance` (value,premium,start_date,end_date,amount,notes,companyid,claim_place,vehicleid, idv, ncb,policy_no,ins_dealerid) VALUES (%d,%d,'%s','%s',%d,'%s',%d,'%s',%d,%d,%d,'%s',%d)";
                $SQL = sprintf($Query, Sanitise::Long($form['insurance_value']), Sanitise::Long($form['premium_value']), Sanitise::Date($Start_date), Sanitise::Date($End_date), Sanitise::Long($form['ins_amount']), Sanitise::String($form['ins_notes']), Sanitise::Long($form['edit_insurance_company']), Sanitise::String($form['near_place']), Sanitise::Long($form['edit_vehicle_id']), Sanitise::Long($form['ide']), Sanitise::Long($form['enb']), Sanitise::String($form['polno']), Sanitise::Long($form['insdealerid']));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        //Edit AMC
        if ($form['AgrStartDate'] != '') {
            $today = date('Y-m-d H:i:s');
            $AgrStartDate = date('Y-m-d', strtotime($form['AgrStartDate']));
            $AgrEndDate = date('Y-m-d', strtotime($form['AgrEndDate']));
            $Query = "SELECT * FROM `amc` where vehicleid=%d";
            $vehiclesQuery = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']));
            if ($this->_databaseManager->get_rowCount() > 0) {
                $Query = "UPDATE `amc` set agree_start_date = '%s',agree_end_date = '%s',total_insured_km=%d ,insured_month =%d, startkm=%d, endkm=%d,  paidamt='%s' WHERE vehicleid = %d";
                $SQL = sprintf($Query, Sanitise::Date($AgrStartDate), Sanitise::Date($AgrEndDate), Sanitise::Long($form['totalinsuredkm']), Sanitise::Long($form['totalinsuredmonth']), Sanitise::Long($form['amcstartkm']), Sanitise::Long($form['amcendkm']), Sanitise::Long($form['amcpaidamt']), Sanitise::Long($form['edit_vehicle_id']));
                $this->_databaseManager->executeQuery($SQL);
            } else {
                $Query = "INSERT INTO `amc`(vehicleid,customerno,agree_start_date,agree_end_date,total_insured_km,insured_month,startkm,endkm,paidamt,entrytime) VALUES "
                    . " (%d,%d,'%s','%s',%d,%d,%d,%d,'%s','%s') ";
                $SQL = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno, Sanitise::Date($AgrStartDate), Sanitise::Date($AgrEndDate), Sanitise::Long($form['totalinsuredkm']), Sanitise::Long($form['totalinsuredmonth']), Sanitise::Long($form['amcstartkm']), Sanitise::Long($form['amcendkm']), Sanitise::Long($form['amcpaidamt']), Sanitise::String($today));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        // EDIT Loan
        if ($form['margin_amt'] != '') {
            $Start_date = date('Y-m-d', strtotime($form['StartDateloan']));
            $End_date = date('Y-m-d', strtotime($form['EndDateloan']));
            if ($form['emidate'] == '') {
                $Emi_date = '';
            } else {
                $Emi_date = date('Y-m-d', strtotime($form['emidate']));
            }
            $Query = "SELECT * FROM loan WHERE vehicleid=%d";
            $SQLQuery = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']));
            $this->_databaseManager->executeQuery($SQLQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                $Query = "UPDATE loan SET marginamt=%d, loanamt=%d, financier='%s', emiamt=%d, tennure=%d, start_date='%s', end_date='%s',loan_accountno = '%s',emidate = '%s' WHERE vehicleid = %d ";
                $SQL = sprintf($Query, Sanitise::Long($form['margin_amt']), Sanitise::Long($form['loan_amt']), Sanitise::String($form['financier']), Sanitise::Long($form['emi_amt']), Sanitise::Long($form['loan_tenure']), Sanitise::DateTime($Start_date), Sanitise::DateTime($End_date), Sanitise::String($form['loan_accno']), Sanitise::String($Emi_date), Sanitise::Long($form['edit_vehicle_id']));
                $this->_databaseManager->executeQuery($SQL);
            } else {
                $Query = "INSERT INTO loan(vehicleid, marginamt, loanamt, financier, emiamt, tennure, start_date, end_date,loan_accountno,emidate) VALUES(%d,%d,%d,'%s',%d,%d,'%s','%s','%s','%s')";
                $SQL = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), Sanitise::Long($form['margin_amt']), Sanitise::Long($form['loan_amt']), Sanitise::String($form['financier']), Sanitise::Long($form['emi_amt']), Sanitise::Long($form['loan_tenure']), Sanitise::DateTime($Start_date), Sanitise::DateTime($End_date), Sanitise::String($form['loan_accno']), Sanitise::String($Emi_date));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        if ($form["notes"] != "") {
            $Query = "UPDATE vehicle SET notes='%s' WHERE vehicleid =%d AND customerno =%d";
            $SQL = sprintf($Query, $form['notes'], Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);
        }
        // Edit Capitalization
        if ($form['cap_EDate'] != '') {
            $End_date = date('Y-m-d', strtotime($form['cap_EDate']));
            $Query = "SELECT * FROM `capitalization` where vehicleid=%d AND customerno=%d";
            $vehiclesQuery = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno);
            $this->_databaseManager->executeQuery($vehiclesQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                $Query = "UPDATE `capitalization` set address='%s',cost='%s',date='%s',userid=%d WHERE  customerno=%d AND vehicleid=%d ";
                $SQL = sprintf($Query, Sanitise::String($form['cap_address']), Sanitise::String($form['edit_cap_cost']), Sanitise::Date($End_date), Sanitise::Long($_SESSION['userid']), $this->_Customerno, Sanitise::Long($form['edit_vehicle_id']));
                $this->_databaseManager->executeQuery($SQL);
            } else {
                $Query = "INSERT INTO `capitalization` (address,cost,date,vehicleid,userid,customerno) VALUES ('%s','%s','%s',%d,%d,%d)";
                $SQL = sprintf($Query, Sanitise::String($form['cap_address']), Sanitise::String($form['edit_cap_cost']), Sanitise::Date($End_date), Sanitise::Long($form['edit_vehicle_id']), $_SESSION['userid'], $this->_Customerno);
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        ////Delete ALL
        // Edit Geo Tags
        $checkpoints = array();
        $fences = array();
        foreach ($_POST as $single_post_name => $single_post_value) {
            if (substr($single_post_name, 0, 14) == "to_checkpoint_") {
                $checkpoints[] = substr($single_post_name, 14, 25);
            }
        }
        foreach ($_POST as $single_post_name => $single_post_value) {
            if (substr($single_post_name, 0, 9) == "to_fence_") {
                $fences[] = substr($single_post_name, 9, 25);
            }
        }
        // Checkpoints
        if (!empty($checkpoints)) {
            $Query = "UPDATE checkpointmanage SET isdeleted=1,userid=%d, updatedBy=%d,updatedOn='%s' WHERE vehicleid =%d AND customerno =%d";
            $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($_SESSION['userid']), $today, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);
            // checkpoints
            $Query = "INSERT INTO checkpointmanage (vehicleid,checkpointid,customerno,userid,createdOn,createdBy) VALUES ('%d','%d',%d,%d,'%s',%d)";
            foreach ($checkpoints as $chkid) {
                $SQL = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), Sanitise::Long($chkid), $this->_Customerno, Sanitise::Long($userid), Sanitise::Date($today));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        // fences
        if (!empty($fences)) {
            $Query = "UPDATE fenceman SET isdeleted=1,userid=%d,updatedBy=%d,updatedOn='%s' WHERE vehicleid =%d AND customerno =%d";
            $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($_SESSION['userid']), $today, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);
            // fences
            $Query = "INSERT INTO fenceman (vehicleid,fenceid,customerno,userid,createdBy,createdOn) VALUES (%d,%d,%d,%d,%d,'%s',%d,'%s')";
            foreach ($fences as $fence) {
                $SQL = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), Sanitise::long($fence), $this->_Customerno, Sanitise::Long($userid), Sanitise::Long($_SESSION['userid']), $today);
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        $vehicle = 'ok';
        return $vehicle;
    }

    public function editvehicle_approval($form, $userid) {
        if ($_SESSION['use_hierarchy'] == '0') {
            //$form['branchid'] = 0;
        }
        $PDate = date('Y-m-d', strtotime($form['PDate']));
        $RDate = date('Y-m-d', strtotime($form['RDate']));
        $today = date("Y-m-d H:i:s");
        $Query = "UPDATE vehicle set vehicleno='%s',kind='%s',userid=%d,groupid=%d,overspeed_limit=%d,modelid=%d,manufacturing_month=%d,manufacturing_year=%d,purchase_date='%s',registration_date='%s',start_meter_reading=%d,fueltype='%s',fuelcapacity=%d, status_id='1',timestamp='%s',rto_location='%s',serial_number='%s',expiry_date='%s',owner_name='%s' WHERE `vehicleid`=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::String($form['edit_vehicle_no']), Sanitise::String($form['kind']), Sanitise::Long($userid), Sanitise::Long($form['branchid']), Sanitise::Long($form['overspeed']), Sanitise::Long($form['model']), Sanitise::Long($form['monthofman']), Sanitise::Long($form['yearofman']), Sanitise::Date($PDate), Sanitise::Date($RDate), Sanitise::Long($form['start_meter']), Sanitise::String($form['fueltype']), Sanitise::Long($form['ftcap']), Sanitise::DateTime($today), Sanitise::String($form['rto_location']), Sanitise::String($form['serial_number']), Sanitise::Date($form['expiry_date']), Sanitise::String($form['owner_name']), Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        // Edit Description
        if ($form['edit_engineno'] != '') {
            $invoice_date = date('Y-m-d', strtotime($form['invoice_date']));
            $Query = "SELECT * FROM `description` where vehicleid=%d";
            $vehiclesQuery = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']));
            $this->_databaseManager->executeQuery($vehiclesQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                $Query = "UPDATE description set engineno='%s',chasisno='%s',vehicle_purpose=%d,vehicle_type=%d,dealerid=%d,invoiceno='%s',invoicedate='%s', invoiceamt='%s', seatcapacity='%s' WHERE vehicleid=%d";
                $SQL = sprintf($Query, Sanitise::String($form['edit_engineno']), Sanitise::String($form['chasisno']), Sanitise::Long($form['veh_purpose']), Sanitise::Long($form['veh_type']), Sanitise::Long($form['dealerid']), Sanitise::String($form['invoiceno']), Sanitise::Date($invoice_date), Sanitise::String($form['invoiceamt']), Sanitise::String($form['seatcapacity']), Sanitise::Long($form['edit_vehicle_id']));
                $this->_databaseManager->executeQuery($SQL);
            } else {
                $Query = "INSERT INTO description (vehicleid,engineno,chasisno,vehicle_purpose,vehicle_type,dealerid,invoiceno,invoicedate,invoiceamt,seatcapacity) VALUES (%d,'%s','%s',%d,%d,%d,'%s','%s',%d, '%s')";
                $SQL = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), Sanitise::String($form['edit_engineno']), Sanitise::String($form['chasisno']), Sanitise::Long($form['veh_purpose']), Sanitise::Long($form['veh_type']), Sanitise::Long($form['dealerid']), Sanitise::String($form['invoiceno']), Sanitise::Date($invoice_date), Sanitise::Long($form['invoiceamt']), Sanitise::String($form['seatcapacity']));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        // Edit Insurance
        if ($form['insurance_value'] != '') {
            $Start_date = date('Y-m-d', strtotime($form['StartDate']));
            $End_date = date('Y-m-d', strtotime($form['EndDate']));
            $Query = "SELECT * FROM `insurance` where vehicleid=%d";
            $vehiclesQuery = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']));
            $this->_databaseManager->executeQuery($vehiclesQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                $Query = "UPDATE `insurance` set value=%d,premium=%d,start_date='%s',end_date='%s',notes='%s',companyid=%d,claim_place='%s', ncb=%d,policy_no = '%s',ins_dealerid = %d WHERE vehicleid=%d";
                $SQL = sprintf($Query, Sanitise::Long($form['insurance_value']), Sanitise::Long($form['premium_value']), Sanitise::Date($Start_date), Sanitise::Date($End_date), Sanitise::String($form['ins_notes']), Sanitise::Long($form['edit_insurance_company']), Sanitise::String($form['near_place']), Sanitise::Long($form['enb']), Sanitise::String($form['polno']), Sanitise::Long($form['insdealerid']), Sanitise::Long($form['edit_vehicle_id']));
                $this->_databaseManager->executeQuery($SQL);
            } else {
                $Query = "INSERT INTO `insurance` (value,premium,start_date,end_date,amount,notes,companyid,claim_place,vehicleid, idv, ncb,policy_no,ins_dealerid) VALUES (%d,%d,'%s','%s',%d,'%s',%d,'%s',%d,%d,%d,'%s',%d)";
                $SQL = sprintf($Query, Sanitise::Long($form['insurance_value']), Sanitise::Long($form['premium_value']), Sanitise::Date($Start_date), Sanitise::Date($End_date), Sanitise::Long($form['ins_amount']), Sanitise::String($form['ins_notes']), Sanitise::Long($form['edit_insurance_company']), Sanitise::String($form['near_place']), Sanitise::Long($form['edit_vehicle_id']), Sanitise::Long($form['ide']), Sanitise::Long($form['enb']), Sanitise::String($form['polno']), Sanitise::Long($form['insdealerid']));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        // EDIT Loan
        if ($form['margin_amt'] != '') {
            $Start_date = date('Y-m-d', strtotime($form['StartDateloan']));
            $End_date = date('Y-m-d', strtotime($form['EndDateloan']));
            if ($form['emidate'] == '') {
                $Emi_date = '';
            } else {
                $Emi_date = date('Y-m-d', strtotime($form['emidate']));
            }
            $Query = "SELECT * FROM loan WHERE vehicleid=%d";
            $SQLQuery = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']));
            $this->_databaseManager->executeQuery($SQLQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                $Query = "UPDATE loan SET marginamt=%d, loanamt=%d, financier='%s', emiamt=%d, tennure=%d, start_date='%s', end_date='%s',loan_accountno = '%s',emidate='%s' WHERE vehicleid = %d ";
                $SQL = sprintf($Query, Sanitise::Long($form['margin_amt']), Sanitise::Long($form['loan_amt']), Sanitise::String($form['financier']), Sanitise::Long($form['emi_amt']), Sanitise::Long($form['loan_tenure']), Sanitise::DateTime($Start_date), Sanitise::DateTime($End_date), Sanitise::String($form['loan_accno']), Sanitise::String($Emi_date), Sanitise::Long($form['edit_vehicle_id']));
                $this->_databaseManager->executeQuery($SQL);
            } else {
                $Query = "INSERT INTO loan(vehicleid, marginamt, loanamt, financier, emiamt, tennure, start_date, end_date,loan_accountno,emidate) VALUES(%d,%d,%d,'%s',%d,%d,'%s','%s','%s','%s')";
                $SQL = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), Sanitise::Long($form['margin_amt']), Sanitise::Long($form['loan_amt']), Sanitise::String($form['financier']), Sanitise::Long($form['emi_amt']), Sanitise::Long($form['loan_tenure']), Sanitise::DateTime($Start_date), Sanitise::DateTime($End_date), Sanitise::String($form['loan_accno']), Sanitise::String($Emi_date));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        /*
        // edit  tyre type srno
        if ($form['right_front'] != '' && $_SESSION['customerno'] == 118) {
        $Query = "SELECT * FROM `maintenance_maptyre` where vehicleid=%d AND customerno=%d AND tyreid=1";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
        $Query = "UPDATE `maintenance_maptyre` set serialno='%s' WHERE  customerno=%d AND vehicleid=%d AND tyreid=1 ";
        $SQL = sprintf($Query, Sanitise::String($form['right_front']), $this->_Customerno, Sanitise::Long($form['edit_vehicle_id']));
        $this->_databaseManager->executeQuery($SQL);
        } else {
        $Query = "INSERT INTO `maintenance_maptyre` (vehicleid,customerno,tyreid,serialno) VALUES (%d,%d,%d,'%s')";
        $SQL = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno, 1, trim($form['right_front']));
        $this->_databaseManager->executeQuery($SQL);
        }
        }
        if ($form['left_front'] != '' && $_SESSION['customerno'] == 118) {
        $Query = "SELECT * FROM `maintenance_maptyre` where vehicleid=%d AND customerno=%d AND tyreid=2";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
        $Query = "UPDATE `maintenance_maptyre` set serialno='%s' WHERE  customerno=%d AND vehicleid=%d AND tyreid=2";
        $SQL = sprintf($Query, Sanitise::String($form['left_front']), $this->_Customerno, Sanitise::Long($form['edit_vehicle_id']));
        $this->_databaseManager->executeQuery($SQL);
        } else {
        $Query = "INSERT INTO `maintenance_maptyre` (vehicleid,customerno,tyreid,serialno) VALUES (%d,%d,%d,'%s')";
        $SQL = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno, 2, trim($form['left_front']));
        $this->_databaseManager->executeQuery($SQL);
        }
        }
        if ($form['right_back_out'] != '' && $_SESSION['customerno'] == 118) {
        $Query = "SELECT * FROM `maintenance_maptyre` where vehicleid=%d AND customerno=%d AND tyreid=3";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
        $Query = "UPDATE `maintenance_maptyre` set serialno='%s' WHERE  customerno=%d AND vehicleid=%d AND tyreid=3 ";
        $SQL = sprintf($Query, Sanitise::String($form['right_back_out']), $this->_Customerno, Sanitise::Long($form['edit_vehicle_id']));
        $this->_databaseManager->executeQuery($SQL);
        } else {
        $Query = "INSERT INTO `maintenance_maptyre` (vehicleid,customerno,tyreid,serialno) VALUES (%d,%d,%d,'%s')";
        $SQL = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno, 3, trim($form['right_back_out']));
        $this->_databaseManager->executeQuery($SQL);
        }
        }
        if ($form['left_back_out'] != '' && $_SESSION['customerno'] == 118) {
        $Query = "SELECT * FROM `maintenance_maptyre` where vehicleid=%d AND customerno=%d AND tyreid=4";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
        $Query = "UPDATE `maintenance_maptyre` set serialno='%s' WHERE  customerno=%d AND vehicleid=%d AND tyreid=4";
        $SQL = sprintf($Query, Sanitise::String($form['left_back_out']), $this->_Customerno, Sanitise::Long($form['edit_vehicle_id']));
        $this->_databaseManager->executeQuery($SQL);
        } else {
        $Query = "INSERT INTO `maintenance_maptyre` (vehicleid,customerno,tyreid,serialno) VALUES (%d,%d,%d,'%s')";
        $SQL = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno, 4, trim($form['left_back_out']));
        $this->_databaseManager->executeQuery($SQL);
        }
        }
        if ($form['stepney'] != '' && $_SESSION['customerno'] == 118) {
        $Query = "SELECT * FROM `maintenance_maptyre` where vehicleid=%d AND customerno=%d AND tyreid=5";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
        $Query = "UPDATE `maintenance_maptyre` set serialno='%s' WHERE  customerno=%d AND vehicleid=%d AND tyreid=5 ";
        $SQL = sprintf($Query, Sanitise::String($form['stepney']), $this->_Customerno, Sanitise::Long($form['edit_vehicle_id']));
        $this->_databaseManager->executeQuery($SQL);
        } else {
        $Query = "INSERT INTO `maintenance_maptyre` (vehicleid,customerno,tyreid,serialno) VALUES (%d,%d,%d,'%s')";
        $SQL = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno, 5, trim($form['stepney']));
        $this->_databaseManager->executeQuery($SQL);
        }
        }
        if ($form['right_back_in'] != '' && $_SESSION['customerno'] == 118) {
        $Query = "SELECT * FROM `maintenance_maptyre` where vehicleid=%d AND customerno=%d AND tyreid=6";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
        $Query = "UPDATE `maintenance_maptyre` set serialno='%s' WHERE  customerno=%d AND vehicleid=%d AND tyreid=6 ";
        $SQL = sprintf($Query, Sanitise::String($form['right_back_in']), $this->_Customerno, Sanitise::Long($form['edit_vehicle_id']));
        $this->_databaseManager->executeQuery($SQL);
        } else {
        $Query = "INSERT INTO `maintenance_maptyre` (vehicleid,customerno,tyreid,serialno) VALUES (%d,%d,%d,'%s')";
        $SQL = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno, 6, trim($form['right_back_in']));
        $this->_databaseManager->executeQuery($SQL);
        }
        }
        if ($form['left_back_in'] != '' && $_SESSION['customerno'] == 118) {
        $Query = "SELECT * FROM `maintenance_maptyre` where vehicleid=%d AND customerno=%d AND tyreid=7";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
        $Query = "UPDATE `maintenance_maptyre` set serialno='%s' WHERE  customerno=%d AND vehicleid=%d AND tyreid=7 ";
        $SQL = sprintf($Query, Sanitise::String($form['left_back_in']), $this->_Customerno, Sanitise::Long($form['edit_vehicle_id']));
        $this->_databaseManager->executeQuery($SQL);
        } else {
        $Query = "INSERT INTO `maintenance_maptyre` (vehicleid,customerno,tyreid,serialno) VALUES (%d,%d,%d,'%s')";
        $SQL = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno, 7, trim($form['left_back_in']));
        $this->_databaseManager->executeQuery($SQL);
        }
        }
        // edit battery srno
        if ($form['battsrno'] != '' && $_SESSION['customerno'] == 118) {
        $Query = "SELECT * FROM `maintenance_mapbattery` where vehicleid=%d AND customerno=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
        $Query = "UPDATE `maintenance_mapbattery` set batt_serialno='%s' WHERE  customerno=%d AND vehicleid=%d ";
        $SQL = sprintf($Query, Sanitise::String($form['battsrno']), $this->_Customerno, Sanitise::Long($form['edit_vehicle_id']));
        $this->_databaseManager->executeQuery($SQL);
        } else {
        $Query = "INSERT INTO `maintenance_mapbattery` (vehicleid,customerno,batt_serialno) VALUES (%d,%d,'%s')";
        $SQL = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno, $form['battsrno']);
        $this->_databaseManager->executeQuery($SQL);
        }
        }
         *
         */
        // Edit Capitalization
        if ($form['cap_EDate'] != '') {
            $End_date = date('Y-m-d', strtotime($form['cap_EDate']));
            $Query = "SELECT * FROM `capitalization` where vehicleid=%d AND customerno=%d";
            $vehiclesQuery = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno);
            $this->_databaseManager->executeQuery($vehiclesQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                $Query = "UPDATE `capitalization` set address='%s',cost='%s',date='%s',userid=%d WHERE  customerno=%d AND vehicleid=%d ";
                $SQL = sprintf($Query, Sanitise::String($form['cap_address']), Sanitise::String($form['edit_cap_cost']), Sanitise::Date($End_date), Sanitise::Long($_SESSION['userid']), $this->_Customerno, Sanitise::Long($form['edit_vehicle_id']));
                $this->_databaseManager->executeQuery($SQL);
            } else {
                $Query = "INSERT INTO `capitalization` (address,cost,date,vehicleid,userid,customerno) VALUES ('%s','%s','%s',%d,%d,%d)";
                $SQL = sprintf($Query, Sanitise::String($form['cap_address']), Sanitise::String($form['edit_cap_cost']), Sanitise::Date($End_date), Sanitise::Long($form['edit_vehicle_id']), $_SESSION['userid'], $this->_Customerno);
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        // Edit Geo Tags
        $checkpoints = array();
        $fences = array();
        foreach ($_POST as $single_post_name => $single_post_value) {
            if (substr($single_post_name, 0, 14) == "to_checkpoint_") {
                $checkpoints[] = substr($single_post_name, 14, 25);
            }
        }
        foreach ($_POST as $single_post_name => $single_post_value) {
            if (substr($single_post_name, 0, 9) == "to_fence_") {
                $fences[] = substr($single_post_name, 9, 25);
            }
        }
        // Checkpoints
        if (!empty($checkpoints)) {
            $Query = "UPDATE checkpointmanage SET isdeleted=1,userid=%d WHERE vehicleid =%d AND customerno =%d";
            $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);
            // checkpoints
            $Query = "INSERT INTO checkpointmanage (vehicleid,checkpointid,customerno,userid) VALUES ('%d','%d',%d,%d)";
            foreach ($checkpoints as $chkid) {
                $SQL = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), Sanitise::Long($chkid), $this->_Customerno, Sanitise::Long($userid));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        // fences
        if (!empty($fences)) {
            $Query = "UPDATE fenceman SET isdeleted=1,userid=%d WHERE vehicleid =%d AND customerno =%d";
            $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($form['edit_vehicle_id']), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);
            // fences
            $Query = "INSERT INTO fenceman (vehicleid,fenceid,customerno,userid) VALUES (%d,%d,%d,%d)";
            foreach ($fences as $fence) {
                $SQL = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), Sanitise::long($fence), $this->_Customerno, Sanitise::Long($userid));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        $vehicle = 'ok';
        return $vehicle;
    }

    public function approved($vehicleid, $notes, $userid, $veh_notes) {
        $today = date("Y-m-d H:i:s");
        $Query1 = "UPDATE vehicle set status_id='2', approval_date = '%s', userid=%d, notes='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($userid), $veh_notes, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL1);
        $Query = "INSERT INTO notes (notes,vehicleid,status,userid) VALUES ('%s',%d,'2',%d)";
        $SQL = sprintf($Query, Sanitise::String($notes), Sanitise::Long($vehicleid), Sanitise::Long($userid));
        $this->_databaseManager->executeQuery($SQL);
        //$vehicleid = $this->_databaseManager->get_insertedId();
        $vehicle = 'ok';
        return $vehicle;
    }

    public function reject($vehicleid, $notes, $userid, $veh_notes) {
        $Query1 = "UPDATE vehicle set status_id='3',notes='%s', userid=%d WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, $veh_notes, Sanitise::Long($userid), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL1);
        $Query = "INSERT INTO notes (notes,vehicleid,status,userid) VALUES ('%s',%d,'3',%d)";
        $SQL = sprintf($Query, Sanitise::String($notes), Sanitise::Long($vehicleid), Sanitise::Long($userid));
        $this->_databaseManager->executeQuery($SQL);
//$vehicleid = $this->_databaseManager->get_insertedId();
        $vehicle = 'ok';
        return $vehicle;
    }

    public function get_general($vehicleid) {
//TODO: Select only necessary details as left join overrides the value for same column.
        /* Eg: It overrides value of userid as it is present in both the tables.
         * Hence, declared the userid explicitly */
        $Query = "SELECT vehicle.*, model.*, vehicle.userid as userid  FROM `vehicle`
        Left Outer JOIN `model` on model.model_id = vehicle.modelid
        where vehicleid=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->userid = $row['userid'];
                $vehicle->kind = $row['kind'];
                $vehicle->makeid = $row['make_id'];
                $vehicle->modelid = $row['modelid'];
                $vehicle->manufacturing_month = $row['manufacturing_month'];
                $vehicle->manufacturing_year = $row['manufacturing_year'];
                $vehicle->purchase_date = $row['purchase_date'];
                $vehicle->branchid = $row['groupid'];
                $vehicle->start_meter_reading = $row['start_meter_reading'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->fueltype = $row['fueltype'];
                $vehicle->fuelcapacity = $row['fuelcapacity'];
                $vehicle->registration_date = $row['registration_date'];
                $vehicle->rto_location = $row['rto_location'];
                $vehicle->current_location = $row['current_location'];
                $vehicle->authorized_signatory = $row['authorized_signatory'];
                $vehicle->hypothecation = $row['hypothecation'];
                $vehicle->serial_number = $row['serial_number'];
                $vehicle->expiry_date = $row['expiry_date'];
                $vehicle->owner_name = $row['owner_name'];
                $vehicle->taxDate = $row['tax_date'];
                $vehicle->permitDate = $row['permit_date'];
                $vehicle->fitnessDate = $row['fitness_date'];
            }
            return $vehicle;
        }
        return null;
    }

    public function get_generalPDF($vehicleid) {
        $Query = "select vehicle.vehicleid, description.dealerid,  vehicle.vehicleno, group.cityid, kind, make.name as makename, model.name as modelname, manufacturing_year, purchase_date, vehicle.groupid as grpid, groupname, group.code as gcode, group.cityid as cid, city.name as cname, city.address as caddress, start_meter_reading, fueltype, overspeed_limit, engineno, chasisno, vehicle_purpose, vehicle_type, dealer.dealerid, invoiceno, invoicedate, dealer.name as dname, dealer.code as dcode, vehicle.manufacturing_month
        from vehicle
        left outer join model on model.model_id = vehicle.modelid
        left outer join make on make.id = model.make_id
        left outer join `group` on group.groupid = vehicle.groupid
        left outer join city on city.cityid = group.cityid
        left outer join description on description.vehicleid = vehicle.vehicleid
        left outer join dealer on dealer.dealerid = description.dealerid
        where vehicle.customerno=%d and vehicle.vehicleid=%d group by vehicle.vehicleid";
        $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->kind = $row['kind'];
                $vehicle->make = $row['makename'];
                $vehicle->model = $row['modelname'];
                $vehicle->manufacturing_month = $row['manufacturing_month'];
                $vehicle->manufacturing_year = $row['manufacturing_year'];
                $vehicle->purchase_date = $row['purchase_date'];
                $vehicle->branchid = $row['grpid'];
                $vehicle->start_meter_reading = $row['start_meter_reading'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->fueltype = $row['fueltype'];
                $vehicle->groupname = $row['groupname'];
                $vehicle->gcode = $row['gcode'];
                $vehicle->cid = $row['cid'];
                $vehicle->cname = $row['cname'];
                $vehicle->caddress = $row['caddress'];
                $vehicle->engineno = $row['engineno'];
                $vehicle->chasisno = $row['chasisno'];
                $vehicle->vehicle_purpose = $row['vehicle_purpose'];
                $vehicle->vehicle_type = $row['vehicle_type'];
                $vehicle->dealerid = $row['dealerid'];
                $vehicle->invoiceno = $row['invoiceno'];
                $vehicle->invoicedate = $row['invoicedate'];
                $vehicle->dname = $row['dname'];
                $vehicle->dcode = $row['dcode'];
            }
            return $vehicle;
        }
        return null;
    }

    public function get_general_formail($vehicleid) {
        $Query = "SELECT vehicle.vehicleno, vehicle.kind, vehicle.manufacturing_year, vehicle.purchase_date,vehicle.fitness_date,vehicle.permit_date,vehicle.tax_date, vehicle.start_meter_reading, vehicle.overspeed_limit,
        vehicle.fueltype, vehicle.status_id,  make.name as makename, model.name as modelname, group.groupname, maintenance_status.name  FROM `vehicle`
        Left Outer JOIN `model` on model.model_id = vehicle.modelid
        LEFT OUTER JOIN `maintenance_status` ON `vehicle`.status_id = `maintenance_status`.id
        LEFT OUTER JOIN `make` ON `model`.make_id = `make`.id
        LEFT OUTER JOIN `group` ON `vehicle`.groupid = `group`.groupid
        where vehicleid=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->kind = $row['kind'];
                $vehicle->make = $row['makename'];
                $vehicle->model = $row['modelname'];
                $vehicle->manufacturing_year = $row['manufacturing_year'];
                $vehicle->purchase_date = $row['purchase_date'];
                $vehicle->branch = $row['groupname'];
                $vehicle->start_meter_reading = $row['start_meter_reading'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->fueltype = $row['fueltype'];
                $vehicle->status_id = $row['status_id'];
                $vehicle->status_name = $row['name'];
                $vehicle->tax_date = $row['tax_date'];
                $vehicle->permit_date = $row['permit_date'];
                $vehicle->fitness_date = $row['fitness_date'];
            }
            return $vehicle;
        }
        return null;
    }

    public function sendEmailTolog($data) {
//$Query = "INSERT INTO temptable(mailfrom, mailto, transid) VALUES ('%s','%s','%s');";
        //$SQL = sprintf($Query, $from, $to, $vehicleno);
        //$this->_databaseManager->executeQuery($SQL);
        $mpath = '';
        if (defined('Mpath')) {
            $mpath = Mpath;
        }
//$un = isset($_SESSION['username']) ? $_SESSION['username'] : $username;
        $customerno = $_SESSION['customerno'];
        if (isset($customerno)) {
            $date = new DateTime();
            $timestamp = $date->format('Y-m-d H:i:s');
            $file = $mpath . '../../customer/' . $customerno . '/log_email.html';
            $current = "#Time: " . $timestamp . "\r\n #Email: <br>" . $data . "<br>";
            $current .= "\r\n-----------------------------------------------------------------------------------------------------------------\r\n<br>";
            $current .= "<br>";
            $filename = $mpath . '../../customer/' . $customerno . '/';
            if (!file_exists($filename)) {
                mkdir($mpath . "../../customer/" . $customerno, 0777);
                if (file_exists($file)) {
                    $fh = fopen($file, 'a');
                    fwrite($fh, $current . "\r\n");
                } else {
                    $fh = fopen($file, 'w');
                    fwrite($fh, $current . "\r\n");
                }
            } else {
                if (file_exists($file)) {
                    $fh = fopen($file, 'a');
                    fwrite($fh, $current . "\r\n");
                } else {
                    $fh = fopen($file, 'w');
                    fwrite($fh, $current . "\r\n");
                }
            }
            fclose($fh);
            return true;
        } else {
            return false;
        }
    }

    public function get_transaction_formail($vehicleid) {
        $Query = "SELECT vehicle.vehicleno, vehicle.kind, vehicle.manufacturing_year, vehicle.purchase_date, vehicle.start_meter_reading, vehicle.overspeed_limit,
        vehicle.fueltype, vehicle.status_id,  make.name as makename, model.name as modelname, group.groupname, maintenance_status.name  FROM `vehicle`
        Left Outer JOIN `model` on model.model_id = vehicle.modelid
        LEFT OUTER JOIN `maintenance_status` ON `vehicle`.status_id = `maintenance_status`.id
        LEFT OUTER JOIN `make` ON `model`.make_id = `make`.id
        LEFT OUTER JOIN `group` ON `vehicle`.groupid = `group`.groupid
        where vehicleid=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->kind = $row['kind'];
                $vehicle->make = $row['makename'];
                $vehicle->model = $row['modelname'];
                $vehicle->manufacturing_year = $row['manufacturing_year'];
                $vehicle->purchase_date = $row['purchase_date'];
                $vehicle->branch = $row['groupname'];
                $vehicle->start_meter_reading = $row['start_meter_reading'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->fueltype = $row['fueltype'];
                $vehicle->status_id = $row['status_id'];
                $vehicle->status_name = $row['name'];
            }
            return $vehicle;
        }
        return null;
    }

    public function get_description($vehicleid) {
        $Query = "SELECT * FROM `description`
        where vehicleid=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->engineno = isset($row['engineno']) ? $row['engineno'] : "";
                $vehicle->chasisno = isset($row['chasisno']) ? $row['chasisno'] : "";
                $vehicle->vehicle_purpose = isset($row['vehicle_purpose']) ? $row['vehicle_purpose'] : "";
                $vehicle->vehicle_type = isset($row['vehicle_type']) ? $row['vehicle_type'] : "";
                $vehicle->dealerid = isset($row['dealerid']) ? $row['dealerid'] : "";
                $vehicle->invoiceno = isset($row['invoiceno']) ? $row['invoiceno'] : "";
                $vehicle->invoicedate = isset($row['invoicedate']) ? $row['invoicedate'] : "";
                $vehicle->invoiceamt = isset($row['invoiceamt']) ? $row['invoiceamt'] : "";
                $vehicle->seatcapacity = isset($row['seatcapacity']) ? $row['seatcapacity'] : "";
            }
            return $vehicle;
        }
        return null;
    }

    public function get_insurance($vehicleid) {
        $Query = "SELECT * FROM `insurance` where vehicleid=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->value = $row['value'];
                $vehicle->premium = $row['premium'];
                $vehicle->start_date = date('d-m-Y', strtotime($row['start_date']));
                $vehicle->end_date = date('d-m-Y', strtotime($row['end_date']));
                $vehicle->amount = $row['amount'];
                $vehicle->notes = $row['notes'];
                $vehicle->companyid = $row['companyid'];
                $vehicle->claim_place = $row['claim_place'];
                $vehicle->idv = $row['idv'];
                $vehicle->ncb = $row['ncb'];
                $vehicle->polno = $row['policy_no'];
                $vehicle->ins_dealerid = $row['ins_dealerid'];
            }
            return $vehicle;
        }
        return null;
    }

    public function get_loan($vehicleid) {
        $Query = "SELECT * FROM `loan` where vehicleid=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->loanid = $row['loanid'];
                $vehicle->margin_amt = $row['marginamt'];
                $vehicle->loan_amt = $row['loanamt'];
                $vehicle->financier = $row['financier'];
                $vehicle->emi_amt = $row['emiamt'];
                $vehicle->loan_tenure = $row['tennure'];
                $vehicle->startdate = $row['start_date'];
                $vehicle->enddate = $row['end_date'];
                $vehicle->vehicleid = $row['vehicleid'];
                if ($row['emidate'] == '0000-00-00' || $row['emidate'] == '1970-01-01') {
                    $vehicle->emidate = '';
                } else {
                    $vehicle->emidate = date('d-m-Y', strtotime($row['emidate']));
                }
                $vehicle->loan_accno = $row['loan_accountno'];
            }
            return $vehicle;
        }
        return null;
    }

    public function get_amc($vehicleid) {
        $Query = "SELECT * FROM `amc` where vehicleid=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new stdClass();
                $vehicle->amcid = $row['amcid'];
                $vehicle->agree_start_date = $row['agree_start_date'];
                $vehicle->agree_end_date = $row['agree_end_date'];
                $vehicle->total_insured_km = $row['total_insured_km'];
                $vehicle->insured_month = $row['insured_month'];
                $vehicle->startkm = $row['startkm'];
                $vehicle->endkm = $row['endkm'];
                $vehicle->paidamt = $row['paidamt'];
                $vehicle->entrytime = $row['entrytime'];
            }
            return $vehicle;
        }
        return null;
    }

    public function get_insurancedata($form) {
        $Query = "SELECT * FROM `insurance` where vehicleid=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($form['insurance_vehicle_id']));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $insurance_detail = array();
                $insurance_detail['value'] = $row['value'];
                $insurance_detail['premium'] = $row['premium'];
                $insurance_detail['start_date'] = date('d-m-Y', strtotime($row['start_date']));
                $insurance_detail['end_date'] = date('d-m-Y', strtotime($row['end_date']));
                $insurance_detail['amount'] = $row['amount'];
                $insurance_detail['notes'] = $row['notes'];
                $insurance_detail['companyid'] = $row['companyid'];
                $insurance_detail['claim_place'] = $row['claim_place'];
            }
            return json_encode($insurance_detail);
        }
        return null;
    }

    public function get_capitalization($vehicleid) {
        $Query = "SELECT * FROM `capitalization` INNER JOIN vehicle ON vehicle.vehicleid = capitalization.vehicleid where capitalization.vehicleid=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->address = isset($row['address']) ? $row['address'] : "";
                $vehicle->cost = isset($row['cost']) ? $row['cost'] : "";
                $vehicle->additional_amount = isset($row['additional_amount']) ? $row['additional_amount'] : "";
                $vehicle->date = isset($row['date']) ? date('d-m-Y', strtotime($row['date'])) : "";
                $get_array_maintanance = $this->maintenance_percent($vehicleid);
                $vehicle->maintenance_percent = isset($get_array_maintanance['maintenance_percent']) ? $get_array_maintanance['maintenance_percent'] : "";
            }
            return $vehicle;
        }
        return null;
    }

    public function adddescription($form) {
        $invoice_date = date('Y-m-d', strtotime($form['invoice_date']));
        $Query = "SELECT * FROM `description` where vehicleid=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $Query = "UPDATE description set engineno='%s',chasisno='%s',vehicle_purpose=%d,vehicle_type=%d,dealerid=%d,invoiceno='%s',invoicedate='%s' WHERE vehicleid=%d";
            $SQL = sprintf($Query, Sanitise::String($form['engineno']), Sanitise::String($form['chasisno']), Sanitise::Long($form['veh_purpose']), Sanitise::Long($form['veh_type']), Sanitise::Long($form['dealerid']), Sanitise::String($form['invoiceno']), Sanitise::Date($invoice_date), Sanitise::Long($form['vehicleid']));
            $this->_databaseManager->executeQuery($SQL);
            $vehicle = 'ok';
        } else {
            $Query = "INSERT INTO description (vehicleid,engineno,chasisno,vehicle_purpose,vehicle_type,dealerid,invoiceno,invoicedate) VALUES (%d,'%s','%s',%d,%d,%d,'%s','%s')";
            $SQL = sprintf($Query, Sanitise::Long($form['vehicleid']), Sanitise::String($form['engineno']), Sanitise::String($form['chasisno']), Sanitise::Long($form['veh_purpose']), Sanitise::Long($form['veh_type']), Sanitise::Long($form['dealerid']), Sanitise::String($form['invoiceno']), Sanitise::Date($invoice_date));
            $this->_databaseManager->executeQuery($SQL);
            $descriptionid = $this->_databaseManager->get_insertedId();
            if ($descriptionid == '') {
                $vehicle = 'notok';
            } else {
                $vehicle = 'ok';
            }
        }
        return $vehicle;
    }

    public function editdescription($form) {
        $invoice_date = date('Y-m-d', strtotime($form['invoice_date']));
        $Query = "SELECT * FROM `description` where vehicleid=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $Query = "UPDATE description set engineno='%s',chasisno='%s',vehicle_purpose=%d,vehicle_type=%d,dealerid=%d,invoiceno='%s',invoicedate='%s' WHERE vehicleid=%d";
            $SQL = sprintf($Query, Sanitise::String($form['edit_engineno']), Sanitise::String($form['chasisno']), Sanitise::Long($form['veh_purpose']), Sanitise::Long($form['veh_type']), Sanitise::Long($form['dealerid']), Sanitise::String($form['invoiceno']), Sanitise::Date($invoice_date), Sanitise::Long($form['edit_vehicle_id']));
            $this->_databaseManager->executeQuery($SQL);
        } else {
            $Query = "INSERT INTO description (vehicleid,engineno,chasisno,vehicle_purpose,vehicle_type,dealerid,invoiceno,invoicedate) VALUES (%d,'%s','%s',%d,%d,%d,'%s','%s')";
            $SQL = sprintf($Query, Sanitise::Long($form['edit_vehicle_id']), Sanitise::String($form['edit_engineno']), Sanitise::String($form['chasisno']), Sanitise::Long($form['veh_purpose']), Sanitise::Long($form['veh_type']), Sanitise::Long($form['dealerid']), Sanitise::String($form['invoiceno']), Sanitise::Date($invoice_date));
            $this->_databaseManager->executeQuery($SQL);
        }
        $today = date("Y-m-d H:i:s");
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($form['edit_vehicle_id']));
        $this->_databaseManager->executeQuery($SQL1);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function edit_tax_by_id($form) {
        $from_date = date('Y-m-d', strtotime($form['edit_from_date']));
        $to_date = date('Y-m-d', strtotime($form['edit_to_date']));
        $Query = "UPDATE tax set from_date='%s',to_date='%s',amount=%d,type=%d,reg_no='%s',userid=%d WHERE id=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::String($from_date), Sanitise::String($to_date), Sanitise::Long($form['edit_amount']), Sanitise::Long($form['edit_tax_type']), Sanitise::String($form['edit_registrationno']), $_SESSION["userid"], Sanitise::Long($form['edit_save_taxid']), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $today = date("Y-m-d H:i:s");
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($form['edit_tax_vehicle_id']));
        $this->_databaseManager->executeQuery($SQL1);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function delete_tax($taxid, $vehicleid) {
        $Query = "UPDATE tax set isdeleted='1' WHERE id=%d";
        $SQL = sprintf($Query, Sanitise::Long($taxid));
        $this->_databaseManager->executeQuery($SQL);
        $today = date("Y-m-d H:i:s");
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL1);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function delete_maintenance($maintenanceid, $vehicleid) {
        $today = date("Y-m-d H:i:s");
        $Query = "UPDATE maintenance set isdeleted='1',userid=%d,timestamp='%s' WHERE id=%d";
        $SQL = sprintf($Query, $_SESSION["userid"], Sanitise::DateTime($today), Sanitise::Long($maintenanceid));
        $this->_databaseManager->executeQuery($SQL);
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL1);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function delete_maintenance_transaction($maintenanceid) {
        $today = date("Y-m-d H:i:s");
        $Query = "UPDATE maintenance set isdeleted='1',userid=%d,timestamp='%s' WHERE id=%d";
        $SQL = sprintf($Query, $_SESSION["userid"], Sanitise::DateTime($today), Sanitise::Long($maintenanceid));
        $this->_databaseManager->executeQuery($SQL);
        $status = 'ok';
        return $status;
    }

    public function delete_acc($accid) {
        $Query = "UPDATE accident set isdeleted='1' WHERE id=%d";
        $SQL = sprintf($Query, Sanitise::Long($accid));
        $this->_databaseManager->executeQuery($SQL);
//$today = date("Y-m-d H:i:s");
        // $Query1 = "UPDATE accident set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        // $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($vehicleid));
        //$this->_databaseManager->executeQuery($SQL1);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function addtax($form) {
        $from_date = date('Y-m-d', strtotime($form['from_date']));
        $to_date = date('Y-m-d', strtotime($form['to_date']));
        $Query = "INSERT INTO `tax` (from_date,to_date,amount,vehicleid,type,reg_no, customerno, userid, emailalert) VALUES ('%s','%s','%s',%d,%d,'%s',%d,%d,%d)";
        $SQL = sprintf($Query, Sanitise::Date($from_date), Sanitise::Date($to_date), Sanitise::String($form['tax_amount']), Sanitise::Long($form['vehicleid']), Sanitise::Long($form['tax_type']), Sanitise::String($form['registrationno']), $this->_Customerno, $_SESSION["userid"], Sanitise::Long($form['alert_by_email']));
        $this->_databaseManager->executeQuery($SQL);
        $taxid = $this->_databaseManager->get_insertedId();
        $today = date("Y-m-d H:i:s");
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($form['vehicleid']));
        $this->_databaseManager->executeQuery($SQL1);
        if ($taxid == '') {
            $vehicle = 'notok';
        } else {
            $vehicle = 'ok';
        }
        return $vehicle;
    }

    public function add_insurance($form) {
        $Start_date = date('Y-m-d', strtotime($form['StartDate']));
        $End_date = date('Y-m-d', strtotime($form['EndDate']));
        $Query = "SELECT * FROM `insurance` where vehicleid=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($form['vehicleid']));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $Query = "UPDATE `insurance` set value=%d,premium=%d,start_date='%s',end_date='%s',amount=%d,notes='%s',companyid=%d,claim_place='%s' WHERE vehicleid=%d";
            $SQL = sprintf($Query, Sanitise::Long($form['insurance_value']), Sanitise::Long($form['premium_value']), Sanitise::Date($Start_date), Sanitise::Date($End_date), Sanitise::Long($form['ins_amount']), Sanitise::String($form['ins_notes']), Sanitise::Long($form['insurance_company']), Sanitise::String($form['near_place']), Sanitise::Long($form['vehicleid']));
            $this->_databaseManager->executeQuery($SQL);
            $today = date("Y-m-d H:i:s");
            $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
            $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($form['vehicleid']));
            $this->_databaseManager->executeQuery($SQL1);
            $vehicle = 'ok';
        } else {
            $Query = "INSERT INTO `insurance` (value,premium,start_date,end_date,amount,notes,companyid,claim_place,vehicleid) VALUES (%d,%d,'%s','%s',%d,'%s',%d,'%s',%d)";
            $SQL = sprintf($Query, Sanitise::Long($form['insurance_value']), Sanitise::Long($form['premium_value']), Sanitise::Date($Start_date), Sanitise::Date($End_date), Sanitise::Long($form['ins_amount']), Sanitise::String($form['ins_notes']), Sanitise::Long($form['insurance_company']), Sanitise::String($form['near_place']), Sanitise::Long($form['vehicleid']));
            $this->_databaseManager->executeQuery($SQL);
            $insuranceid = $this->_databaseManager->get_insertedId();
            if ($insuranceid == '') {
                $vehicle = 'notok';
            } else {
                $vehicle = 'ok';
            }
        }
        return $vehicle;
    }

    public function edit_insurance($form) {
        $Start_date = date('Y-m-d', strtotime($form['StartDate']));
        $End_date = date('Y-m-d', strtotime($form['EndDate']));
        $Query = "SELECT * FROM `insurance` where vehicleid=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($form['vehicleid']));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $Query = "UPDATE `insurance` set value=%d,premium=%d,start_date='%s',end_date='%s',amount=%d,notes='%s',companyid=%d,claim_place='%s' WHERE vehicleid=%d";
            $SQL = sprintf($Query, Sanitise::Long($form['insurance_value']), Sanitise::Long($form['premium_value']), Sanitise::Date($Start_date), Sanitise::Date($End_date), Sanitise::Long($form['ins_amount']), Sanitise::String($form['ins_notes']), Sanitise::Long($form['edit_insurance_company']), Sanitise::String($form['near_place']), Sanitise::Long($form['vehicleid']));
            $this->_databaseManager->executeQuery($SQL);
            $today = date("Y-m-d H:i:s");
            $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
            $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($form['vehicleid']));
            $this->_databaseManager->executeQuery($SQL1);
            $vehicle = 'ok';
        } else {
            $Query = "INSERT INTO `insurance` (value,premium,start_date,end_date,amount,notes,companyid,claim_place,vehicleid) VALUES (%d,%d,'%s','%s',%d,'%s',%d,'%s',%d)";
            $SQL = sprintf($Query, Sanitise::Long($form['insurance_value']), Sanitise::Long($form['premium_value']), Sanitise::Date($Start_date), Sanitise::Date($End_date), Sanitise::Long($form['ins_amount']), Sanitise::String($form['ins_notes']), Sanitise::Long($form['edit_insurance_company']), Sanitise::String($form['near_place']), Sanitise::Long($form['vehicleid']));
            $this->_databaseManager->executeQuery($SQL);
            $insuranceid = $this->_databaseManager->get_insertedId();
            $today = date("Y-m-d H:i:s");
            $Query1 = "UPDATE vehicle set status_id='5', timestamp='%s' WHERE `vehicleid`=%d";
            $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($form['vehicleid']));
            $this->_databaseManager->executeQuery($SQL1);
            if ($insuranceid == '') {
                $vehicle = 'notok';
            } else {
                $vehicle = 'ok';
            }
        }
        return $vehicle;
    }

    public function add_capitalization($form) {
        $End_date = date('Y-m-d', strtotime($form['cap_EDate']));
        $Query = "SELECT * FROM `capitalization` where vehicleid=%d AND customerno=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($form['vehicleid']), $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $Query = "UPDATE `capitalization` set address='%s',cost='%s',date='%s',userid=%d WHERE vehicleid=%d AND customerno=%d";
            $SQL = sprintf($Query, Sanitise::String($form['cap_address']), Sanitise::String($form['cap_cost']), Sanitise::Date($End_date), Sanitise::Long($_SESSION['userid']), Sanitise::Long($form['vehicleid']), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);
            $vehicle = 'ok';
        } else {
            $Query = "INSERT INTO `capitalization` (address,cost,date,vehicleid,userid,customerno) VALUES ('%s','%s','%s',%d,%d,%d)";
            $SQL = sprintf($Query, Sanitise::String($form['cap_address']), Sanitise::String($form['cap_cost']), Sanitise::Date($End_date), Sanitise::Long($form['vehicleid']), Sanitise::Long($_SESSION['userid']), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);
            $capid = $this->_databaseManager->get_insertedId();
            if ($capid == '') {
                $vehicle = 'notok';
            } else {
                $vehicle = 'ok';
            }
        }
        return $vehicle;
    }

    public function edit_capitalization($form) {
        $today = date("Y-m-d H:i:s");
        $End_date = date('Y-m-d', strtotime($form['cap_EDate']));
        $Query = "SELECT * FROM `capitalization` where vehicleid=%d AND customerno=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($form['vehicleid']), $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $Query = "UPDATE `capitalization` set address='%s',cost='%s',date='%s',userid=%d WHERE vehicleid=%d AND customerno=%d";
            $SQL = sprintf($Query, Sanitise::String($form['cap_address']), Sanitise::String($form['edit_cap_cost']), Sanitise::Date($End_date), Sanitise::Long($_SESSION['userid']), Sanitise::Long($form['vehicleid']), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);
            $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s',additional_amount=%d WHERE `vehicleid`=%d";
            $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($form['additional_main_amount']), Sanitise::Long($form['vehicleid']));
            $this->_databaseManager->executeQuery($SQL1);
            $vehicle = 'ok';
        } else {
            $Query = "INSERT INTO `capitalization` (address,cost,date,vehicleid,userid,customerno) VALUES ('%s','%s','%s',%d,%d,%d)";
            $SQL = sprintf($Query, Sanitise::String($form['cap_address']), Sanitise::String($form['edit_cap_cost']), Sanitise::Date($End_date), Sanitise::Long($form['vehicleid']), Sanitise::Long($_SESSION['userid']), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);
            $capid = $this->_databaseManager->get_insertedId();
            $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s',,additional_amount=%d WHERE `vehicleid`=%d";
            $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($form['additional_main_amount']), Sanitise::Long($form['vehicleid']));
            $this->_databaseManager->executeQuery($SQL1);
            if ($capid == '') {
                $vehicle = 'notok';
            } else {
                $vehicle = 'ok';
            }
        }
        return $vehicle;
    }

    public function add_battery_approval($form) {
        $today = date("Y-m-d H:i:s");
        $Query = "SELECT * FROM `maintenance` where vehicleid=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($form['battery_vehicleid']));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $Query = "UPDATE `maintenance` SET meter_reading=%d,dealer_id=%d, notes=%d WHERE vehicleid = %d";
            $SQL = sprintf($Query, Sanitise::Long($form['meter_reading']), Sanitise::Long($form['dealerid']), Sanitise::String($form['note_batt']), Sanitise::Long($form['battery_vehicleid']));
            $this->_databaseManager->executeQuery($SQL);
            $Query1 = "INSERT INTO `maintenance_history` (category,meter_reading,dealer_id,notes,vehicleid,timestamp) VALUES ('0',%d,%d,'%s',%d,'%s')";
            $SQL1 = sprintf($Query1, Sanitise::Long($form['meter_reading']), Sanitise::Long($form['dealerid']), Sanitise::String($form['note_batt']), Sanitise::Long($form['battery_vehicleid']), Sanitise::DateTime($today));
            $this->_databaseManager->executeQuery($SQL1);
            $mainid = $this->_databaseManager->get_insertedId();
        } else {
            $Query = "INSERT INTO `maintenance` (meter_reading,dealer_id,vehicleid,notes,statusid) VALUES (%d,%d,%d,'%s','5')";
            $SQL = sprintf($Query, Sanitise::Long($form['meter_reading']), Sanitise::Long($form['dealerid']), Sanitise::Long($form['battery_vehicleid']), Sanitise::String($form['note_batt']));
            $this->_databaseManager->executeQuery($SQL);
            $Query1 = "INSERT INTO `maintenance_history` (category,meter_reading,dealer_id,notes,vehicleid,timestamp) VALUES ('0',%d,%d,'%s',%d,'%s')";
            $SQL1 = sprintf($Query1, Sanitise::Long($form['meter_reading']), Sanitise::Long($form['dealerid']), Sanitise::String($form['note_batt']), Sanitise::Long($form['battery_vehicleid']), Sanitise::DateTime($today));
            $this->_databaseManager->executeQuery($SQL1);
            $mainid = $this->_databaseManager->get_insertedId();
        }
        if ($mainid == '') {
            $vehicle = 'notok';
        } else {
            $vehicle = 'ok';
        }
        return $vehicle;
    }

    public function add_accident_approval_vehicle($form) {
        $today = date("Y-m-d H:i:s");
        $acc_Date = date('Y-m-d', strtotime($form['acc_Date']));
        $acc_datetime = $acc_Date . ' ' . $form['STime'];
        if ($form['thirdparty'] == 'yes') {
            $thirdparty = '1';
        } else {
            $thirdparty = '0';
        }
        $val_fromdate = date('Y-m-d', strtotime($form['val_from_Date']));
        $val_todate = date('Y-m-d', strtotime($form['val_to_Date']));
        $mahindra_amount = $form['actual_amount'] - $form['sett_amount'];
        $vehicleid = $form['accident_vehicleid'];
        $roleid = $this->get_approvarid_for_transaction(4, $vehicleid, null, null, null);
        $Query = "INSERT INTO `accident` (accident_datetime,accident_location,thirdparty_insured,description,drivername,licence_validity_from,licence_validity_to,licence_type,workshop_location,loss_amount,sett_amount,actual_amount,mahindra_amount,send_report_to,statusid,roleid,vehicleid,userid,customerno,timestamp,submission_date,approval_date,ofasnumber) VALUES ('%s','%s',%d,'%s','%s','%s','%s','%s','%s',%d,%d,%d,%d,'%s','6',%d,%d,%d,%d,'%s','%s','%s','%s')";
        $SQL = sprintf($Query, Sanitise::String($acc_datetime), Sanitise::String($form['acc_location']), Sanitise::Long($thirdparty), Sanitise::String($form['acc_desc']), Sanitise::String($form['driver_name']), Sanitise::String($val_fromdate), Sanitise::String($val_todate), Sanitise::String($form['licence_type']), Sanitise::String($form['add_workshop']), Sanitise::Long($form['loss_amount']), Sanitise::Long($form['sett_amount']), Sanitise::Long($form['actual_amount']), Sanitise::Long($mahindra_amount), Sanitise::String($form['send_report']), $roleid, Sanitise::Long($form['accident_vehicleid']), $_SESSION['userid'], $this->_Customerno, Sanitise::DateTime($today), Sanitise::DateTime($form['acc_submission_date']), Sanitise::DateTime($form['acc_approval_date']), Sanitise::String($form['acc_ofasnumber']));
        $this->_databaseManager->executeQuery($SQL);
        $accidentid = $this->_databaseManager->get_insertedId();
        $Query1 = "INSERT INTO `accident_history` (accidentid,accident_datetime,accident_location,thirdparty_insured,description,drivername,licence_validity_from,licence_validity_to,licence_type,workshop_location,loss_amount,sett_amount,actual_amount,mahindra_amount,send_report_to,statusid,roleid,vehicleid,userid,customerno,timestamp,submission_date,approval_date,ofasnumber) VALUES (%d,'%s','%s',%d,'%s','%s','%s','%s','%s','%s',%d,%d,%d,%d,'%s','6','1',%d,%d,%d,'%s','%s','%s','%s')";
        $SQL1 = sprintf($Query1, Sanitise::Long($accidentid), Sanitise::String($acc_datetime), Sanitise::String($form['acc_location']), Sanitise::Long($thirdparty), Sanitise::String($form['acc_desc']), Sanitise::String($form['driver_name']), Sanitise::String($val_fromdate), Sanitise::String($val_todate), Sanitise::String($form['licence_type']), Sanitise::String($form['add_workshop']), Sanitise::Long($form['loss_amount']), Sanitise::Long($form['sett_amount']), Sanitise::Long($form['actual_amount']), Sanitise::Long($mahindra_amount), Sanitise::String($form['send_report']), Sanitise::Long($form['accident_vehicleid']), $_SESSION['userid'], $this->_Customerno, Sanitise::DateTime($today), Sanitise::DateTime($form['acc_submission_date']), Sanitise::DateTime($form['acc_approval_date']), Sanitise::String($form['acc_ofasnumber']));
        $this->_databaseManager->executeQuery($SQL1);
        $mainid = $this->_databaseManager->get_insertedId();
        $uploaddir = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $form['accident_vehicleid'] . "/";
        $oldi1_1 = $uploaddir . 'file1_acc.pdf';
        $oldi1_2 = $uploaddir . 'file1_acc.png';
        $oldi1_3 = $uploaddir . 'file1_acc.jpg';
        $oldi1_4 = $uploaddir . 'file1_acc.jpeg';
        $oldi2_1 = $uploaddir . 'file2_acc.pdf';
        $oldi2_2 = $uploaddir . 'file2_acc.png';
        $oldi2_3 = $uploaddir . 'file2_acc.jpg';
        $oldi2_4 = $uploaddir . 'file2_acc.jpeg';
        $oldi3_1 = $uploaddir . 'file3_acc.pdf';
        $oldi3_2 = $uploaddir . 'file3_acc.png';
        $oldi3_3 = $uploaddir . 'file3_acc.jpg';
        $oldi3_4 = $uploaddir . 'file3_acc.jpeg';
        $oldi4_1 = $uploaddir . 'file4_acc.pdf';
        $oldi4_2 = $uploaddir . 'file4_acc.png';
        $oldi4_3 = $uploaddir . 'file4_acc.jpg';
        $oldi4_4 = $uploaddir . 'file4_acc.jpeg';
        $oldi5_1 = $uploaddir . 'file5_acc.pdf';
        $oldi5_2 = $uploaddir . 'file5_acc.png';
        $oldi5_3 = $uploaddir . 'file5_acc.jpg';
        $oldi5_4 = $uploaddir . 'file5_acc.jpeg';
        $newi1_1 = $uploaddir . $mainid . '_1_acc.pdf';
        $newi1_2 = $uploaddir . $mainid . '_1_acc.png';
        $newi1_3 = $uploaddir . $mainid . '_1_acc.jpg';
        $newi1_4 = $uploaddir . $mainid . '_1_acc.jpeg';
        $newi2_1 = $uploaddir . $mainid . '_2_acc.pdf';
        $newi2_2 = $uploaddir . $mainid . '_2_acc.png';
        $newi2_3 = $uploaddir . $mainid . '_2_acc.jpg';
        $newi2_4 = $uploaddir . $mainid . '_2_acc.jpeg';
        $newi3_1 = $uploaddir . $mainid . '_3_acc.pdf';
        $newi3_2 = $uploaddir . $mainid . '_3_acc.png';
        $newi3_3 = $uploaddir . $mainid . '_3_acc.jpg';
        $newi3_4 = $uploaddir . $mainid . '_3_acc.jpeg';
        $newi4_1 = $uploaddir . $mainid . '_4_acc.pdf';
        $newi4_2 = $uploaddir . $mainid . '_4_acc.png';
        $newi4_3 = $uploaddir . $mainid . '_4_acc.jpg';
        $newi4_4 = $uploaddir . $mainid . '_4_acc.jpeg';
        $newi5_1 = $uploaddir . $mainid . '_5_acc.pdf';
        $newi5_2 = $uploaddir . $mainid . '_5_acc.png';
        $newi5_3 = $uploaddir . $mainid . '_5_acc.jpg';
        $newi5_4 = $uploaddir . $mainid . '_5_acc.jpeg';
        if (file_exists($oldi1_1)) {
            rename($oldi1_1, $newi1_1);
        }
        if (file_exists($oldi1_2)) {
            rename($oldi1_2, $newi1_2);
        }
        if (file_exists($oldi1_3)) {
            rename($oldi1_3, $newi1_3);
        }
        if (file_exists($oldi1_4)) {
            rename($oldi1_4, $newi1_4);
        }
        if (file_exists($oldi2_1)) {
            rename($oldi2_1, $newi2_1);
        }
        if (file_exists($oldi2_2)) {
            rename($oldi2_2, $newi2_2);
        }
        if (file_exists($oldi2_3)) {
            rename($oldi2_3, $newi2_3);
        }
        if (file_exists($oldi2_4)) {
            rename($oldi2_4, $newi2_4);
        }
        if (file_exists($oldi3_1)) {
            rename($oldi3_1, $newi3_1);
        }
        if (file_exists($oldi3_2)) {
            rename($oldi3_2, $newi3_2);
        }
        if (file_exists($oldi3_3)) {
            rename($oldi3_3, $newi3_3);
        }
        if (file_exists($oldi3_4)) {
            rename($oldi3_4, $newi3_4);
        }
        if (file_exists($oldi4_1)) {
            rename($oldi4_1, $newi4_1);
        }
        if (file_exists($oldi4_2)) {
            rename($oldi4_2, $newi4_2);
        }
        if (file_exists($oldi4_3)) {
            rename($oldi4_3, $newi4_3);
        }
        if (file_exists($oldi4_4)) {
            rename($oldi4_4, $newi4_4);
        }
        if (file_exists($oldi5_1)) {
            rename($oldi5_1, $newi5_1);
        }
        if (file_exists($oldi5_2)) {
            rename($oldi5_2, $newi5_2);
        }
        if (file_exists($oldi5_3)) {
            rename($oldi5_3, $newi5_3);
        }
        if (file_exists($oldi5_4)) {
            rename($oldi5_4, $newi5_4);
        }
        if ($mainid == '') {
            $vehicle = 'notok';
        } else {
            $vehicle = 'ok';
        }
        return $vehicle;
    }

    public function add_accident_approval($form) {
        $today = date("Y-m-d H:i:s");
        $acc_Date = date('Y-m-d', strtotime($form['acc_Date']));
        $acc_datetime = $acc_Date . ' ' . $form['STime'];
        if ($form['thirdparty'] == 'yes') {
            $thirdparty = '1';
        } else {
            $thirdparty = '0';
        }
        $val_fromdate = date('Y-m-d', strtotime($form['val_from_Date']));
        $val_todate = date('Y-m-d', strtotime($form['val_to_Date']));
        $mahindra_amount = $form['actual_amount'] - $form['sett_amount'];
        $Query = "INSERT INTO `accident` (accident_datetime,accident_location,thirdparty_insured,description,drivername,licence_validity_from,licence_validity_to,licence_type,workshop_location,loss_amount,sett_amount,actual_amount,mahindra_amount,send_report_to,statusid,roleid,vehicleid,userid,customerno,timestamp,submission_date) VALUES ('%s','%s',%d,'%s','%s','%s','%s','%s','%s',%d,%d,%d,%d,'%s','10','1',%d,%d,%d,'%s','%s')";
        $SQL = sprintf($Query, Sanitise::String($acc_datetime), Sanitise::String($form['acc_location']), Sanitise::Long($thirdparty), Sanitise::String($form['acc_desc']), Sanitise::String($form['driver_name']), Sanitise::String($val_fromdate), Sanitise::String($val_todate), Sanitise::String($form['licence_type']), Sanitise::String($form['add_workshop']), Sanitise::Long($form['loss_amount']), Sanitise::Long($form['sett_amount']), Sanitise::Long($form['actual_amount']), Sanitise::Long($mahindra_amount), Sanitise::String($form['send_report']), Sanitise::Long($form['accident_vehicleid']), $_SESSION['userid'], $this->_Customerno, Sanitise::DateTime($today), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($SQL);
        $accidentid = $this->_databaseManager->get_insertedId();
        $Query1 = "INSERT INTO `accident_history` (accidentid,accident_datetime,accident_location,thirdparty_insured,description,drivername,licence_validity_from,licence_validity_to,licence_type,workshop_location,loss_amount,sett_amount,actual_amount,mahindra_amount,send_report_to,statusid,roleid,vehicleid,userid,customerno,timestamp,submission_date) VALUES (%d,'%s','%s',%d,'%s','%s','%s','%s','%s','%s',%d,%d,%d,%d,'%s','10','1',%d,%d,%d,'%s','%s')";
        $SQL1 = sprintf($Query1, Sanitise::Long($accidentid), Sanitise::String($acc_datetime), Sanitise::String($form['acc_location']), Sanitise::Long($thirdparty), Sanitise::String($form['acc_desc']), Sanitise::String($form['driver_name']), Sanitise::String($val_fromdate), Sanitise::String($val_todate), Sanitise::String($form['licence_type']), Sanitise::String($form['add_workshop']), Sanitise::Long($form['loss_amount']), Sanitise::Long($form['sett_amount']), Sanitise::Long($form['actual_amount']), Sanitise::Long($mahindra_amount), Sanitise::String($form['send_report']), Sanitise::Long($form['accident_vehicleid']), $_SESSION['userid'], $this->_Customerno, Sanitise::DateTime($today), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($SQL1);
        $mainid = $this->_databaseManager->get_insertedId();
        $uploaddir = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $form['accident_vehicleid'] . "/";
        $oldi1_1 = $uploaddir . 'file1_acc.pdf';
        $oldi1_2 = $uploaddir . 'file1_acc.png';
        $oldi1_3 = $uploaddir . 'file1_acc.jpg';
        $oldi1_4 = $uploaddir . 'file1_acc.jpeg';
        $oldi2_1 = $uploaddir . 'file2_acc.pdf';
        $oldi2_2 = $uploaddir . 'file2_acc.png';
        $oldi2_3 = $uploaddir . 'file2_acc.jpg';
        $oldi2_4 = $uploaddir . 'file2_acc.jpeg';
        $oldi3_1 = $uploaddir . 'file3_acc.pdf';
        $oldi3_2 = $uploaddir . 'file3_acc.png';
        $oldi3_3 = $uploaddir . 'file3_acc.jpg';
        $oldi3_4 = $uploaddir . 'file3_acc.jpeg';
        $oldi4_1 = $uploaddir . 'file4_acc.pdf';
        $oldi4_2 = $uploaddir . 'file4_acc.png';
        $oldi4_3 = $uploaddir . 'file4_acc.jpg';
        $oldi4_4 = $uploaddir . 'file4_acc.jpeg';
        $oldi5_1 = $uploaddir . 'file5_acc.pdf';
        $oldi5_2 = $uploaddir . 'file5_acc.png';
        $oldi5_3 = $uploaddir . 'file5_acc.jpg';
        $oldi5_4 = $uploaddir . 'file5_acc.jpeg';
        $newi1_1 = $uploaddir . $mainid . '_1_acc.pdf';
        $newi1_2 = $uploaddir . $mainid . '_1_acc.png';
        $newi1_3 = $uploaddir . $mainid . '_1_acc.jpg';
        $newi1_4 = $uploaddir . $mainid . '_1_acc.jpeg';
        $newi2_1 = $uploaddir . $mainid . '_2_acc.pdf';
        $newi2_2 = $uploaddir . $mainid . '_2_acc.png';
        $newi2_3 = $uploaddir . $mainid . '_2_acc.jpg';
        $newi2_4 = $uploaddir . $mainid . '_2_acc.jpeg';
        $newi3_1 = $uploaddir . $mainid . '_3_acc.pdf';
        $newi3_2 = $uploaddir . $mainid . '_3_acc.png';
        $newi3_3 = $uploaddir . $mainid . '_3_acc.jpg';
        $newi3_4 = $uploaddir . $mainid . '_3_acc.jpeg';
        $newi4_1 = $uploaddir . $mainid . '_4_acc.pdf';
        $newi4_2 = $uploaddir . $mainid . '_4_acc.png';
        $newi4_3 = $uploaddir . $mainid . '_4_acc.jpg';
        $newi4_4 = $uploaddir . $mainid . '_4_acc.jpeg';
        $newi5_1 = $uploaddir . $mainid . '_5_acc.pdf';
        $newi5_2 = $uploaddir . $mainid . '_5_acc.png';
        $newi5_3 = $uploaddir . $mainid . '_5_acc.jpg';
        $newi5_4 = $uploaddir . $mainid . '_5_acc.jpeg';
        if (file_exists($oldi1_1)) {
            rename($oldi1_1, $newi1_1);
        }
        if (file_exists($oldi1_2)) {
            rename($oldi1_2, $newi1_2);
        }
        if (file_exists($oldi1_3)) {
            rename($oldi1_3, $newi1_3);
        }
        if (file_exists($oldi1_4)) {
            rename($oldi1_4, $newi1_4);
        }
        if (file_exists($oldi2_1)) {
            rename($oldi2_1, $newi2_1);
        }
        if (file_exists($oldi2_2)) {
            rename($oldi2_2, $newi2_2);
        }
        if (file_exists($oldi2_3)) {
            rename($oldi2_3, $newi2_3);
        }
        if (file_exists($oldi2_4)) {
            rename($oldi2_4, $newi2_4);
        }
        if (file_exists($oldi3_1)) {
            rename($oldi3_1, $newi3_1);
        }
        if (file_exists($oldi3_2)) {
            rename($oldi3_2, $newi3_2);
        }
        if (file_exists($oldi3_3)) {
            rename($oldi3_3, $newi3_3);
        }
        if (file_exists($oldi3_4)) {
            rename($oldi3_4, $newi3_4);
        }
        if (file_exists($oldi4_1)) {
            rename($oldi4_1, $newi4_1);
        }
        if (file_exists($oldi4_2)) {
            rename($oldi4_2, $newi4_2);
        }
        if (file_exists($oldi4_3)) {
            rename($oldi4_3, $newi4_3);
        }
        if (file_exists($oldi4_4)) {
            rename($oldi4_4, $newi4_4);
        }
        if (file_exists($oldi5_1)) {
            rename($oldi5_1, $newi5_1);
        }
        if (file_exists($oldi5_2)) {
            rename($oldi5_2, $newi5_2);
        }
        if (file_exists($oldi5_3)) {
            rename($oldi5_3, $newi5_3);
        }
        if (file_exists($oldi5_4)) {
            rename($oldi5_4, $newi5_4);
        }
        if ($mainid == '') {
            $vehicle = 'notok';
        } else {
            $vehicle = $mainid;
        }
        return $vehicle;
    }

    public function add_accident($form) {
        $today = date("Y-m-d H:i:s");
        $acc_Date = date('Y-m-d', strtotime($form['acc_Date']));
        $acc_datetime = $acc_Date . ' ' . $form['STime'];
        if ($form['thirdparty'] == 'yes') {
            $thirdparty = '1';
        } else {
            $thirdparty = '0';
        }
        $val_fromdate = date('Y-m-d', strtotime($form['val_from_Date']));
        $val_todate = date('Y-m-d', strtotime($form['val_to_Date']));
        $mahindra_amount = $form['actual_amount'] - $form['sett_amount'];
        $Query = "INSERT INTO `accident` (accident_datetime,accident_location,thirdparty_insured,description,drivername,licence_validity_from,licence_validity_to,licence_type,workshop_location,loss_amount,sett_amount,actual_amount,mahindra_amount,send_report_to,statusid,roleid,vehicleid,userid,customerno,timestamp, submission_date) VALUES ('%s','%s',%d,'%s','%s','%s','%s','%s','%s',%d,%d,%d,%d,'%s','1','1',%d,%d,%d,'%s','%s')";
        $SQL = sprintf($Query, Sanitise::String($acc_datetime), Sanitise::String($form['acc_location']), Sanitise::Long($thirdparty), Sanitise::String($form['acc_desc']), Sanitise::String($form['driver_name']), Sanitise::String($val_fromdate), Sanitise::String($val_todate), Sanitise::String($form['licence_type']), Sanitise::String($form['add_workshop']), Sanitise::Long($form['loss_amount']), Sanitise::Long($form['sett_amount']), Sanitise::Long($form['actual_amount']), Sanitise::Long($mahindra_amount), Sanitise::String($form['send_report']), Sanitise::Long($form['accident_vehicleid']), $_SESSION['userid'], $this->_Customerno, Sanitise::DateTime($today), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($SQL);
        $accidentid = $this->_databaseManager->get_insertedId();
        $Query1 = "INSERT INTO `accident_history` (accidentid,accident_datetime,accident_location,thirdparty_insured,description,drivername,licence_validity_from,licence_validity_to,licence_type,workshop_location,loss_amount,sett_amount,actual_amount,mahindra_amount,send_report_to,statusid,roleid,vehicleid,userid,customerno,timestamp, submission_date) VALUES (%d,'%s','%s',%d,'%s','%s','%s','%s','%s','%s',%d,%d,%d,%d,'%s','1','1',%d,%d,%d,'%s','%s')";
        $SQL1 = sprintf($Query1, Sanitise::Long($accidentid), Sanitise::String($acc_datetime), Sanitise::String($form['acc_location']), Sanitise::Long($thirdparty), Sanitise::String($form['acc_desc']), Sanitise::String($form['driver_name']), Sanitise::String($val_fromdate), Sanitise::String($val_todate), Sanitise::String($form['licence_type']), Sanitise::String($form['add_workshop']), Sanitise::Long($form['loss_amount']), Sanitise::Long($form['sett_amount']), Sanitise::Long($form['actual_amount']), Sanitise::Long($mahindra_amount), Sanitise::String($form['send_report']), Sanitise::Long($form['accident_vehicleid']), $_SESSION['userid'], $this->_Customerno, Sanitise::DateTime($today), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($SQL1);
        $mainid = $this->_databaseManager->get_insertedId();
        $uploaddir = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $form['accident_vehicleid'] . "/";
        $oldi1_1 = $uploaddir . 'file1_acc.pdf';
        $oldi1_2 = $uploaddir . 'file1_acc.png';
        $oldi1_3 = $uploaddir . 'file1_acc.jpg';
        $oldi1_4 = $uploaddir . 'file1_acc.jpeg';
        $oldi2_1 = $uploaddir . 'file2_acc.pdf';
        $oldi2_2 = $uploaddir . 'file2_acc.png';
        $oldi2_3 = $uploaddir . 'file2_acc.jpg';
        $oldi2_4 = $uploaddir . 'file2_acc.jpeg';
        $oldi3_1 = $uploaddir . 'file3_acc.pdf';
        $oldi3_2 = $uploaddir . 'file3_acc.png';
        $oldi3_3 = $uploaddir . 'file3_acc.jpg';
        $oldi3_4 = $uploaddir . 'file3_acc.jpeg';
        $oldi4_1 = $uploaddir . 'file4_acc.pdf';
        $oldi4_2 = $uploaddir . 'file4_acc.png';
        $oldi4_3 = $uploaddir . 'file4_acc.jpg';
        $oldi4_4 = $uploaddir . 'file4_acc.jpeg';
        $oldi5_1 = $uploaddir . 'file5_acc.pdf';
        $oldi5_2 = $uploaddir . 'file5_acc.png';
        $oldi5_3 = $uploaddir . 'file5_acc.jpg';
        $oldi5_4 = $uploaddir . 'file5_acc.jpeg';
        $newi1_1 = $uploaddir . $mainid . '_1_acc.pdf';
        $newi1_2 = $uploaddir . $mainid . '_1_acc.png';
        $newi1_3 = $uploaddir . $mainid . '_1_acc.jpg';
        $newi1_4 = $uploaddir . $mainid . '_1_acc.jpeg';
        $newi2_1 = $uploaddir . $mainid . '_2_acc.pdf';
        $newi2_2 = $uploaddir . $mainid . '_2_acc.png';
        $newi2_3 = $uploaddir . $mainid . '_2_acc.jpg';
        $newi2_4 = $uploaddir . $mainid . '_2_acc.jpeg';
        $newi3_1 = $uploaddir . $mainid . '_3_acc.pdf';
        $newi3_2 = $uploaddir . $mainid . '_3_acc.png';
        $newi3_3 = $uploaddir . $mainid . '_3_acc.jpg';
        $newi3_4 = $uploaddir . $mainid . '_3_acc.jpeg';
        $newi4_1 = $uploaddir . $mainid . '_4_acc.pdf';
        $newi4_2 = $uploaddir . $mainid . '_4_acc.png';
        $newi4_3 = $uploaddir . $mainid . '_4_acc.jpg';
        $newi4_4 = $uploaddir . $mainid . '_4_acc.jpeg';
        $newi5_1 = $uploaddir . $mainid . '_5_acc.pdf';
        $newi5_2 = $uploaddir . $mainid . '_5_acc.png';
        $newi5_3 = $uploaddir . $mainid . '_5_acc.jpg';
        $newi5_4 = $uploaddir . $mainid . '_5_acc.jpeg';
        if (file_exists($oldi1_1)) {
            rename($oldi1_1, $newi1_1);
        }
        if (file_exists($oldi1_2)) {
            rename($oldi1_2, $newi1_2);
        }
        if (file_exists($oldi1_3)) {
            rename($oldi1_3, $newi1_3);
        }
        if (file_exists($oldi1_4)) {
            rename($oldi1_4, $newi1_4);
        }
        if (file_exists($oldi2_1)) {
            rename($oldi2_1, $newi2_1);
        }
        if (file_exists($oldi2_2)) {
            rename($oldi2_2, $newi2_2);
        }
        if (file_exists($oldi2_3)) {
            rename($oldi2_3, $newi2_3);
        }
        if (file_exists($oldi2_4)) {
            rename($oldi2_4, $newi2_4);
        }
        if (file_exists($oldi3_1)) {
            rename($oldi3_1, $newi3_1);
        }
        if (file_exists($oldi3_2)) {
            rename($oldi3_2, $newi3_2);
        }
        if (file_exists($oldi3_3)) {
            rename($oldi3_3, $newi3_3);
        }
        if (file_exists($oldi3_4)) {
            rename($oldi3_4, $newi3_4);
        }
        if (file_exists($oldi4_1)) {
            rename($oldi4_1, $newi4_1);
        }
        if (file_exists($oldi4_2)) {
            rename($oldi4_2, $newi4_2);
        }
        if (file_exists($oldi4_3)) {
            rename($oldi4_3, $newi4_3);
        }
        if (file_exists($oldi4_4)) {
            rename($oldi4_4, $newi4_4);
        }
        if (file_exists($oldi5_1)) {
            rename($oldi5_1, $newi5_1);
        }
        if (file_exists($oldi5_2)) {
            rename($oldi5_2, $newi5_2);
        }
        if (file_exists($oldi5_3)) {
            rename($oldi5_3, $newi5_3);
        }
        if (file_exists($oldi5_4)) {
            rename($oldi5_4, $newi5_4);
        }
        if ($mainid == '') {
            $vehicle = 'notok';
        } else {
            $vehicle = 'ok';
        }
        return $vehicle;
    }

    public function edit_accident($form) {
        $today = date("Y-m-d H:i:s");
        $acc_Date = date('Y-m-d', strtotime($form['acc_Date']));
        $acc_datetime = $acc_Date . ' ' . $form['STime'];
        if ($form['thirdparty'] == 'yes') {
            $thirdparty = '1';
        } else {
            $thirdparty = '0';
        }
        $val_fromdate = date('Y-m-d', strtotime($form['val_from_Date']));
        $val_todate = date('Y-m-d', strtotime($form['val_to_Date']));
        $mahindra_amount = $form['actual_amount'] - $form['sett_amount'];
        $Query = "update  `accident`
        set accident_datetime='%s'
        ,accident_location='%s'
        ,thirdparty_insured='%d'
        ,description='%s'
        ,drivername='%s'
        ,licence_validity_from='%s'
        ,licence_validity_to='%s',
        licence_type='%s',
        workshop_location='%s',
        loss_amount='%d',
        sett_amount='%d',
        actual_amount='%d',
        mahindra_amount='%s',
        send_report_to='%s',
        statusid=6,
        submission_date='%s',
        approval_date = '%s',
        ofasnumber = '%s',
        userid='%d' where id='%d' ";
        $SQL = sprintf($Query, Sanitise::String($acc_datetime), Sanitise::String($form['acc_location']), Sanitise::Long($thirdparty), Sanitise::String($form['acc_desc']), Sanitise::String($form['driver_name']), Sanitise::String($val_fromdate), Sanitise::String($val_todate), Sanitise::String($form['licence_type']), Sanitise::String($form['add_workshop']), Sanitise::Long($form['loss_amount']), Sanitise::Long($form['sett_amount']), Sanitise::Long($form['actual_amount']), Sanitise::Long($form['mahindra_amount']), Sanitise::String($form['send_report']), Sanitise::DateTime($form['acc_submission_date']), Sanitise::DateTime($form['acc_approval_date']), Sanitise::String($form['acc_ofasnumber']), $_SESSION['userid'], $form['editacc_maintainanceid']);
        $this->_databaseManager->executeQuery($SQL);
        $mainid = $accidentid = $form['editacc_maintainanceid'];
        $Query1 = "INSERT INTO `accident_history` (accidentid,accident_datetime,accident_location,thirdparty_insured,description,drivername,licence_validity_from,licence_validity_to,licence_type,workshop_location,loss_amount,sett_amount,actual_amount,mahindra_amount,send_report_to,statusid,roleid,vehicleid,userid,customerno,timestamp) VALUES (%d,'%s','%s',%d,'%s','%s','%s','%s','%s','%s',%d,%d,%d,%d,'%s','6','1',%d,%d,%d,'%s')";
        $SQL1 = sprintf($Query1, Sanitise::Long($accidentid), Sanitise::String($acc_datetime), Sanitise::String($form['acc_location']), Sanitise::Long($thirdparty), Sanitise::String($form['acc_desc']), Sanitise::String($form['driver_name']), Sanitise::String($val_fromdate), Sanitise::String($val_todate), Sanitise::String($form['licence_type']), Sanitise::String($form['add_workshop']), Sanitise::Long($form['loss_amount']), Sanitise::Long($form['sett_amount']), Sanitise::Long($form['actual_amount']), Sanitise::Long($mahindra_amount), Sanitise::String($form['send_report']), Sanitise::Long($form['edit_vehicleid_history']), $_SESSION['userid'], $this->_Customerno, Sanitise::String($today));
        $this->_databaseManager->executeQuery($SQL1);
        $uploaddir = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $form['edit_vehicleid_history'] . "/";
        $oldi1_1 = $uploaddir . 'efile1_acc.pdf';
        $oldi1_2 = $uploaddir . 'efile1_acc.png';
        $oldi1_3 = $uploaddir . 'efile1_acc.jpg';
        $oldi1_4 = $uploaddir . 'efile1_acc.jpeg';
        $oldi2_1 = $uploaddir . 'efile2_acc.pdf';
        $oldi2_2 = $uploaddir . 'efile2_acc.png';
        $oldi2_3 = $uploaddir . 'efile2_acc.jpg';
        $oldi2_4 = $uploaddir . 'efile2_acc.jpeg';
        $oldi3_1 = $uploaddir . 'efile3_acc.pdf';
        $oldi3_2 = $uploaddir . 'efile3_acc.png';
        $oldi3_3 = $uploaddir . 'efile3_acc.jpg';
        $oldi3_4 = $uploaddir . 'efile3_acc.jpeg';
        $oldi4_1 = $uploaddir . 'efile4_acc.pdf';
        $oldi4_2 = $uploaddir . 'efile4_acc.png';
        $oldi4_3 = $uploaddir . 'efile4_acc.jpg';
        $oldi4_4 = $uploaddir . 'efile4_acc.jpeg';
        $oldi5_1 = $uploaddir . 'efile5_acc.pdf';
        $oldi5_2 = $uploaddir . 'efile5_acc.png';
        $oldi5_3 = $uploaddir . 'efile5_acc.jpg';
        $oldi5_4 = $uploaddir . 'efile5_acc.jpeg';
        $newi1_1 = $uploaddir . $mainid . '_1_acc.pdf';
        $newi1_2 = $uploaddir . $mainid . '_1_acc.png';
        $newi1_3 = $uploaddir . $mainid . '_1_acc.jpg';
        $newi1_4 = $uploaddir . $mainid . '_1_acc.jpeg';
        $newi2_1 = $uploaddir . $mainid . '_2_acc.pdf';
        $newi2_2 = $uploaddir . $mainid . '_2_acc.png';
        $newi2_3 = $uploaddir . $mainid . '_2_acc.jpg';
        $newi2_4 = $uploaddir . $mainid . '_2_acc.jpeg';
        $newi3_1 = $uploaddir . $mainid . '_3_acc.pdf';
        $newi3_2 = $uploaddir . $mainid . '_3_acc.png';
        $newi3_3 = $uploaddir . $mainid . '_3_acc.jpg';
        $newi3_4 = $uploaddir . $mainid . '_3_acc.jpeg';
        $newi4_1 = $uploaddir . $mainid . '_4_acc.pdf';
        $newi4_2 = $uploaddir . $mainid . '_4_acc.png';
        $newi4_3 = $uploaddir . $mainid . '_4_acc.jpg';
        $newi4_4 = $uploaddir . $mainid . '_4_acc.jpeg';
        $newi5_1 = $uploaddir . $mainid . '_5_acc.pdf';
        $newi5_2 = $uploaddir . $mainid . '_5_acc.png';
        $newi5_3 = $uploaddir . $mainid . '_5_acc.jpg';
        $newi5_4 = $uploaddir . $mainid . '_5_acc.jpeg';
        if (file_exists($oldi1_1)) {
            rename($oldi1_1, $newi1_1);
        }
        if (file_exists($oldi1_2)) {
            rename($oldi1_2, $newi1_2);
        }
        if (file_exists($oldi1_3)) {
            rename($oldi1_3, $newi1_3);
        }
        if (file_exists($oldi1_4)) {
            rename($oldi1_4, $newi1_4);
        }
        if (file_exists($oldi2_1)) {
            rename($oldi2_1, $newi2_1);
        }
        if (file_exists($oldi2_2)) {
            rename($oldi2_2, $newi2_2);
        }
        if (file_exists($oldi2_3)) {
            rename($oldi2_3, $newi2_3);
        }
        if (file_exists($oldi2_4)) {
            rename($oldi2_4, $newi2_4);
        }
        if (file_exists($oldi3_1)) {
            rename($oldi3_1, $newi3_1);
        }
        if (file_exists($oldi3_2)) {
            rename($oldi3_2, $newi3_2);
        }
        if (file_exists($oldi3_3)) {
            rename($oldi3_3, $newi3_3);
        }
        if (file_exists($oldi3_4)) {
            rename($oldi3_4, $newi3_4);
        }
        if (file_exists($oldi4_1)) {
            rename($oldi4_1, $newi4_1);
        }
        if (file_exists($oldi4_2)) {
            rename($oldi4_2, $newi4_2);
        }
        if (file_exists($oldi4_3)) {
            rename($oldi4_3, $newi4_3);
        }
        if (file_exists($oldi4_4)) {
            rename($oldi4_4, $newi4_4);
        }
        if (file_exists($oldi5_1)) {
            rename($oldi5_1, $newi5_1);
        }
        if (file_exists($oldi5_2)) {
            rename($oldi5_2, $newi5_2);
        }
        if (file_exists($oldi5_3)) {
            rename($oldi5_3, $newi5_3);
        }
        if (file_exists($oldi5_4)) {
            rename($oldi5_4, $newi5_4);
        }
        if ($mainid == '') {
            $vehicle = 'notok';
        } else {
            $vehicle = 'ok';
        }
        return $vehicle;
    }

    public function edit_accident_ofas($form) {
        $today = date("Y-m-d H:i:s");
        $Query = "update  `accident`
        set timestamp='%s',
        ofasnumber=%d,
        statusid=14,
        userid='%d' where id='%d' ";
        $SQL = sprintf($Query, Sanitise::DateTime($today), Sanitise::String($form['acc_ofasnumber']), $_SESSION['userid'], $form['editacc_maintainanceid']);
        $this->_databaseManager->executeQuery($SQL);
        $mainid = $accidentid = $form['editacc_maintainanceid'];
        $Query1 = "update  `accident_history`
        set timestamp='%s',
        ofasnumber=%d,
        statusid=14,
        userid='%d' where accidentid='%d' ";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::String($form['acc_ofasnumber']), $_SESSION['userid'], $form['editacc_maintainanceid']);
        $this->_databaseManager->executeQuery($SQL1);
        if ($mainid == '') {
            $vehicle = 'notok';
        } else {
            $vehicle = 'ok';
        }
        return $vehicle;
    }

    public function get_accident_transaction($statusid = null) {
        $vm = new VehicleManager($_SESSION['customerno']);
        $groups = $vm->getUserGroups($_SESSION['customerno'], $_SESSION['userid']);
        $maintanances = Array();
        $masterRoleId = 1;
        switch ($_SESSION['customerno']) {
            case 63:
                $masterRoleId = 28;
                break;
            case 64:
                $masterRoleId = 33;
                break;
            case 118:
                $masterRoleId = 18;
                $accountRoleId = 42;
                $leaderRoleId = 19;
                break;
            case 167:
                $masterRoleId = 24;
                break;
            default:
                $masterRoleId = 1;
                break;
        }
        $Query = "SELECT vehicle.vehicleid,accident.statusid,accident.submission_date, user.realname,accident.timestamp,group.groupname,accident.id,accident.roleid,vehicle.vehicleno,maintenance_status.name, accident.actual_amount from accident
        inner join vehicle on accident.vehicleid=vehicle.vehicleid
        LEFT OUTER JOIN `maintenance_status` ON `accident`.statusid = `maintenance_status`.id
        LEFT OUTER JOIN `user` ON `user`.userid = `accident`.userid
        LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid";
//$Query .= " WHERE accident.customerno =%d ";
        if ($_SESSION['roleid'] == '1' || $_SESSION['roleid'] == '8') {
            $Query .= " WHERE  accident.customerno =%d";
        } elseif ($_SESSION['roleid'] == '2') {
            $Query .= " LEFT OUTER JOIN city on city.cityid = group.cityid
            LEFT OUTER JOIN district on district.districtid = city.districtid
            LEFT OUTER JOIN state on state.stateid = district.stateid";
            $Query .= "  WHERE accident.customerno =%d AND state.stateid = " . $_SESSION['heirarchy_id'] . " ";
        } elseif ($_SESSION['roleid'] == '3') {
            $Query .= " LEFT OUTER JOIN city on city.cityid = group.cityid
            LEFT OUTER JOIN district on district.districtid = city.districtid ";
            $Query .= " WHERE  accident.customerno =%d AND district.districtid = " . $_SESSION['heirarchy_id'] . " ";
        } elseif ($_SESSION['roleid'] == '4') {
            $Query .= " LEFT OUTER JOIN city on city.cityid = group.cityid";
            $Query .= " WHERE  accident.customerno =%d AND city.cityid = " . $_SESSION['heirarchy_id'] . " ";
        } else {
            $Query .= " WHERE accident.isdeleted=0 AND accident.customerno =%d";
        }
        if ($statusid != null) {
            $Query .= " AND statusid IN (7,10,11) ";
        }
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
        } else {
            if ($groups[0] != 0) {
                $groupid_ids = implode(',', $groups);
                $Query .= " AND vehicle.groupid in (" . $groupid_ids . ") ";
            }
        }
        if ($_SESSION['groupid'] != 0) {
            $maintananceQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $maintananceQuery = sprintf($Query, $this->_Customerno);
        }
        $maintananceQuery .= " ORDER BY accident.timestamp DESC";
//$maintananceQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($maintananceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $maintanance = new VOMaintanance();
                $maintanance->id = $row['id'];
                if ($row["roleid"] == 1) {
                    $maintanance->role = $_SESSION['master'];
                } elseif ($row["roleid"] == 2) {
                    $maintanance->role = $_SESSION['statehead'];
                } elseif ($row["roleid"] == 3) {
                    $maintanance->role = $_SESSION['districthead'];
                } elseif ($row["roleid"] == 4) {
                    $maintanance->role = $_SESSION['cityhead'];
                } elseif ($row["roleid"] == 5) {
                    $maintanance->role = $_SESSION['administrator'];
                } else {
                    $maintanance->role = "N/A";
                }
                $maintanance->vehicleno = $row['vehicleno'];
                $maintanance->vehicleid = $row['vehicleid'];
                $maintanance->statusname = $row['name'];
                $maintanance->sender = $row['realname'];
                $maintanance->trans = "AC00" . $row["id"];
                $maintanance->group = $row['groupname'];
                $maintanance->invoice_amount = $row['actual_amount'];
                $maintanance->statusid = $row['statusid'];
                $maintanance->category = "Accident";
                $maintanance->timestamp = date("d-m-Y", strtotime($row["timestamp"]));
                $maintanance->submit_date = date("d-m-Y", strtotime($row["submission_date"]));
                $maintanance->chk_box = '';
                if (isset($row["roleid"])) {
                    $val_chk = $maintanance->id . "-" . $maintanance->statusid;
                    if ($_SESSION['roleid'] == isset($accountRoleId) ? $accountRoleId : "" || $_SESSION['roleid'] == isset($masterRoleId) ? $masterRoleId : "") {
//$maintanance->chk_box = $val_chk;
                        if ($row['statusid'] == 13) {
                            $maintanance->chk_box = "<input type='checkbox' class='call-checkbox' value='" . $val_chk . "'/>";
                        }
                    }
                }
                $maintanance->edit = '';
                $edit = "<a href='transaction.php?id=5&tid=$maintanance->id' ><i class='icon-pencil'></i></a>";
//                if ($_SESSION['roleid'] == $row["roleid"] || $_SESSION['roleid'] == $masterRoleId) {
                //                    $maintanance->edit = $edit;
                //                    } else
                //
                if ($_SESSION['roleid'] == isset($leaderRoleId) ? $leaderRoleId : "" || $_SESSION['roleid'] == isset($masterRoleId) ? $masterRoleId : "") {
                    if ($row['statusid'] == 13) {
                        $delete_trans = "| <a a onClick=\"javascript: return confirm('Please confirm deletion');\"href='route_ajax.php?delaccid=$maintanance->id' ><i class='icon-trash'></i>";
                        $edit = "<a href='transaction.php?id=5&tid=$maintanance->id' ><i class='icon-pencil'></i></a>  " . $delete_trans;
                        $maintanance->edit = $edit;
                    } else {
                        $maintanance->edit = $edit;
                    }
                }
                //$maintid =$maintanance->id;
                //$cancelled = "| <a href='javascript:void(0)' alt='cancelled' title='cancelled' onclick='transaction_cancelled(".$maintid.");return false;'><i class='icon-remove'></i></a> |";
                //$maintanance->edit = $cancelled;
                // $maintanance->edit = $maintanance->edit." ".$cancelled;
                $maintanance->dname = "N/A";
                $maintanance->invoice_amount = "N/A";
                $maintanance->invno = "N/A";
                $maintanance->quote_amount = "N/A";
                $maintanance->invoice_date = "N/A";
                $maintanance->vehicle_out_date = "N/A";
                $maintanance->meter_reading = "N/A";
                $maintanance->approval_chkdata = $maintanance->id . "-" . $maintanance->statusid;
                $maintanances[] = $maintanance;
            }
            return $maintanances;
        }
        return null;
    }

    public function get_filtered_accidents($transid, $vehicleid, $statusid) {
        $maintanances = Array();
        $groups = $this->getUserGroups($_SESSION['customerno'], $_SESSION['userid']);
        $masterRoleId = 1;
        switch ($_SESSION['customerno']) {
            case 63:
                $masterRoleId = 28;
                break;
            case 64:
                $masterRoleId = 33;
                break;
            case 118:
                $masterRoleId = 18;
                $accountRoleId = 42;
                $leaderRoleId = 19;
                $coExecutiveId = 23;
                break;
            case 167:
                $masterRoleId = 24;
                break;
            default:
                $masterRoleId = 1;
                break;
        }
        $transaction = str_split($transid, 4);
        $trans_substr = substr($transid, 4);
        $Queryadd = "";
        if ($transaction[0] != "AC00" && $transaction[0] != "") {
            $trans_substr = substr($transid, 4);
        }
        if ($trans_substr != "") {
            $Queryadd = sprintf(" AND accident.id='%s' ", $trans_substr);
        }
        $vehicleQuery = "";
        if ($vehicleid != "") {
            $vehicleQuery = sprintf(" AND accident.vehicleid=%d ", $vehicleid);
        }
        $statusQuery = "";
        if ($statusid != "" && $statusid != "1") {
            $statusQuery = sprintf(" AND accident.statusid=%d ", $statusid);
        }
// to check if filter is for approval
        if ($statusid != "" && $statusid == "1") {
            $statusQuery = sprintf(" AND accident.statusid IN (7,10,11)");
        }
        $Query = "SELECT vehicle.vehicleid,accident.statusid,accident.submission_date, user.realname,accident.timestamp,group.groupname,accident.id,accident.roleid,vehicle.vehicleno,maintenance_status.name from accident
    inner join vehicle on accident.vehicleid=vehicle.vehicleid
    LEFT OUTER JOIN `maintenance_status` ON `accident`.statusid = `maintenance_status`.id
    LEFT OUTER JOIN `user` ON `user`.userid = `accident`.userid
    LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid";
        $Query .= " WHERE accident.customerno =%d ";
        $Query .= $Queryadd;
        $Query .= $vehicleQuery;
        $Query .= $statusQuery;
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
        } else {
            if ($groups[0] != 0) {
                $groupid_ids = implode(',', $groups);
                $Query .= " AND vehicle.groupid in (" . $groupid_ids . ") ";
            }
        }
        if ($_SESSION['groupid'] != 0) {
            $maintananceQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $maintananceQuery = sprintf($Query, $this->_Customerno);
        }
        $maintananceQuery .= " ORDER BY accident.timestamp DESC";
//$maintananceQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($maintananceQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($transaction[0] == "AC00" || $transaction[0] == "") {
                    $maintanance = new VOMaintanance();
                    $maintanance->id = $row['id'];
                    if ($row["roleid"] == 1) {
                        $maintanance->role = $_SESSION['master'];
                    } elseif ($row["roleid"] == 2) {
                        $maintanance->role = $_SESSION['statehead'];
                    } elseif ($row["roleid"] == 3) {
                        $maintanance->role = $_SESSION['districthead'];
                    } elseif ($row["roleid"] == 4) {
                        $maintanance->role = $_SESSION['cityhead'];
                    } elseif ($row["roleid"] == 5) {
                        $maintanance->role = $_SESSION['administrator'];
                    }
                    $maintanance->vehicleno = $row['vehicleno'];
                    $maintanance->vehicleid = $row['vehicleid'];
                    $maintanance->statusname = $row['name'];
                    $maintanance->sender = $row['realname'];
                    $maintanance->trans = "AC00" . $row["id"];
                    $maintanance->group = $row['groupname'];
                    $maintanance->statusid = $row['statusid'];
                    $maintanance->category = "Accident";
                    $maintanance->timestamp = date("d-m-Y", strtotime($row["timestamp"]));
                    $maintanance->submit_date = date("d-m-Y", strtotime($row["submission_date"]));
                    $maintanance->chk_box = '';
                    if (isset($row["roleid"])) {
                        $val_chk = $maintanance->id . "-" . $maintanance->statusid;
                        if ($_SESSION['roleid'] == $accountRoleId || $_SESSION['roleid'] == $masterRoleId) {
                            if ($row['statusid'] == 13) {
                                $maintanance->chk_box = "<input type='checkbox' class='call-checkbox' value='" . $val_chk . "'/>";
                            }
                        }
                    }
                    $maintanance->edit = "";
                    $edit = "<a href='approvals.php?id=5&tid=$maintanance->id'><i class='icon-pencil'></i></a>";
                    if ($_SESSION['roleid'] == $row["roleid"] || $_SESSION['roleid'] == $masterRoleId) {
                        $maintanance->edit = $edit;
                    } elseif ($_SESSION['roleid'] == $leaderRoleId || $_SESSION['roleid'] == $masterRoleId) {
                        if ($row['statusid'] == 13) {
                            $delete_trans = "| <a a onClick=\"javascript: return confirm('Please confirm deletion');\"href='route_ajax.php?delaccid=$maintanance->id' ><i class='icon-trash'></i>";
                            $edit = "<a href='transaction.php?id=5&tid=$maintanance->id' ><i class='icon-pencil'></i></a> | " . $delete_trans;
                            $maintanance->edit = $edit;
                        }
                    }
                    //$maintid =$maintanance->id;
                    //$cancelled = " | <a href='javascript:void(0)' alt='cancelled' title='cancelled' onclick='transaction_cancelled(".$maintid.");return false;'><i class='icon-remove'></i></a> |";
                    // $maintanance->edit = $maintanance->edit." ".$cancelled;
                    $maintanance->dname = "N/A";
                    $maintanance->invoice_amount = "N/A";
                    $maintanance->quote_amount = "N/A";
                    $maintanance->invno = "N/A";
                    $maintanance->invnoice_date = "N/A";
                    $maintanance->vehicle_out_date = "N/A";
                    $maintanance->meter_reading = "N/A";
                    $maintanance->approval_chkdata = $maintanance->id . "-" . $maintanance->statusid;
                    $maintanances[] = $maintanance;
                }
            }
            return $maintanances;
        }
        return null;
    }

    public function add_accessory($form) {
        $today = date("Y-m-d H:i:s");
        $invoice_date = date('Y-m-d', strtotime($form['accessory_invoice_date']));
        $vehicle_in_date = date('Y-m-d', strtotime($form['accessory_vehicle_in_date']));
        $vehicle_out_date = date('Y-m-d', strtotime($form['accessory_vehicle_out_date']));
        $submission_date = date('Y-m-d', strtotime($form['accessory_submission_date']));
        $approval_date = date('Y-m-d', strtotime($form['accessory_approval_date']));
        $payment_approval_date = date('Y-m-d', strtotime($form['accessory_payment_approval_date']));
        $payment_submission_date = date('Y-m-d', strtotime($form['accessory_payment_submission_date']));
        $roleid = 1;
        $Query = "INSERT INTO `maintenance` (maintenance_date,meter_reading,vehicle_in_date,vehicle_out_date,dealer_id,invoice_date, invoice_no, invoice_amount, tax, vehicleid, timestamp, userid, customerno, roleid, notes, amount_quote, category, statusid, isdeleted, submission_date, approval_date, ofasno, payment_approval_date, payment_submission_date) VALUES ('%s',%d,'%s','%s',%d,'%s','%s','%s',%d,%d,'%s',%d,%d,%d,'%s',%d,5,6,0,'%s','%s','%s','%s','%s')";
        $SQL = sprintf($Query, Sanitise::DateTime($vehicle_in_date), Sanitise::Long($form['accessory_meter_reading']), Sanitise::DateTime($vehicle_in_date), Sanitise::DateTime($vehicle_out_date), Sanitise::Long($form['accessory_dealerid']), Sanitise::DateTime($invoice_date), Sanitise::String($form['accessory_invoice_no']), Sanitise::String($form['accessory_amount_invoice']), Sanitise::String($form['accessory_invoice_tax']), Sanitise::Long($form['new_accessory_vehicleid']), Sanitise::DateTime($today), $_SESSION['userid'], $this->_Customerno, $roleid, Sanitise::String($form['note_accessory']), Sanitise::Long($form['accessory_amount_quote']), Sanitise::Date($submission_date), Sanitise::Date($approval_date), Sanitise::Long($form['accessory_ofasnumber']), Sanitise::Date($payment_approval_date), Sanitise::Date($payment_submission_date));
        $this->_databaseManager->executeQuery($SQL);
        $mainid = $this->_databaseManager->get_insertedId();
        $Query = "INSERT INTO `maintenance_history` (maintananceid, maintenance_date,meter_reading,vehicle_in_date,vehicle_out_date,dealer_id,invoice_date, invoice_no, invoice_amount, tax, vehicleid, timestamp, userid, customerno, roleid, notes, amount_quote, category, statusid, submission_date, approval_date, ofasno, payment_approval_date, payment_submission_date) VALUES (%d,'%s',%d,'%s','%s',%d,'%s','%s','%s',%d,%d,'%s',%d,%d,%d,'%s',%d,0,6,'%s','%s','%s','%s','%s')";
        $SQL = sprintf($Query, $mainid, Sanitise::DateTime($vehicle_in_date), Sanitise::Long($form['accessory_meter_reading']), Sanitise::DateTime($vehicle_in_date), Sanitise::DateTime($vehicle_out_date), Sanitise::Long($form['accessory_dealerid']), Sanitise::DateTime($invoice_date), Sanitise::String($form['accessory_invoice_no']), Sanitise::String($form['accessory_amount_invoice']), Sanitise::String($form['accessory_invoice_tax']), Sanitise::Long($form['new_accessory_vehicleid']), Sanitise::DateTime($today), $_SESSION['userid'], $this->_Customerno, $roleid, Sanitise::String($form['note_accessory']), Sanitise::Long($form['accessory_amount_quote']), Sanitise::Date($submission_date), Sanitise::Date($approval_date), Sanitise::Long($form['accessory_ofasnumber']), Sanitise::Date($payment_approval_date), Sanitise::Date($payment_submission_date));
        $this->_databaseManager->executeQuery($SQL);
        $uploaddir = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $form['new_accessory_vehicleid'] . "/";
        $oldi1 = $uploaddir . '_invoice.pdf';
        $oldi2 = $uploaddir . '_invoice.png';
        $oldi3 = $uploaddir . '_invoice.jpg';
        $oldi4 = $uploaddir . '_invoice.jpeg';
        $newi1 = $uploaddir . $mainid . '_5_invoice.pdf';
        $newi2 = $uploaddir . $mainid . '_5_invoice.png';
        $newi3 = $uploaddir . $mainid . '_5_invoice.jpg';
        $newi4 = $uploaddir . $mainid . '_5_invoice.jpeg';
        if (file_exists($oldi1)) {
            rename($oldi1, $newi1);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_5_invoice.pdf', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set invoice_file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_5_invoice.pdf', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldi2)) {
            rename($oldi2, $newi2);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_5_invoice.png', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set invoice_file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_5_invoice.png', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldi3)) {
            rename($oldi3, $newi3);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_5_invoice.jpg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set invoice_file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_5_invoice.jpg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldi4)) {
            rename($oldi4, $newi4);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_5_invoice.jpeg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set invoice_file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_5_invoice.jpeg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        $oldq1 = $uploaddir . '_quote.pdf';
        $oldq2 = $uploaddir . '_quote.png';
        $oldq3 = $uploaddir . '_quote.jpg';
        $oldq4 = $uploaddir . '_quote.jpeg';
        $newq1 = $uploaddir . $mainid . '_5_quote.pdf';
        $newq2 = $uploaddir . $mainid . '_5_quote.png';
        $newq3 = $uploaddir . $mainid . '_5_quote.jpg';
        $newq4 = $uploaddir . $mainid . '_5_quote.jpeg';
        if (file_exists($oldq1)) {
            rename($oldq1, $newq1);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_5_quote.pdf', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_5_quote.pdf', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldq2)) {
            rename($oldq2, $newq2);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_5_quote.png', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_5_quote.png', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldq3)) {
            rename($oldq3, $newq3);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_5_quote.jpg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_5_quote.jpg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldq4)) {
            rename($oldq4, $newq4);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_5_quote.jpeg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_5_quote.jpeg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($form['new_accessory_vehicleid']));
        $this->_databaseManager->executeQuery($SQL1);
// echo $form['accessory_list'];die();
        $accessory_list = explode(",", $form['accessory_list']);
        //print_r($accessory_list);
        foreach ($accessory_list as $this_acc) {
            $this_acc_details = explode("-", $this_acc);
            $tot = $this_acc_details[1] / $this_acc_details[2];
            $Query = "INSERT INTO `accessory_map` (timestamp,customerno,userid,isdeleted,cost,amount,quantity,accessoryid,maintenanceid) VALUES ('%s',%d,%d,'0',%d,%d,%d,%d,%d);";
            $SQL = sprintf($Query, Sanitise::DateTime($today), $this->_Customerno, $_SESSION['userid'], $this_acc_details[1], $tot, $this_acc_details[2], $this_acc_details[0], $mainid);
            $this->_databaseManager->executeQuery($SQL);
        }
        if ($mainid == '') {
            $vehicle = 'notok';
        } else {
            $vehicle = 'ok';
        }
        return $vehicle;
    }

    public function add_battery($form) {
        $today = date("Y-m-d H:i:s");
        $invoice_date = date('Y-m-d', strtotime($form['batt_invoice_date']));
        $vehicle_in_date = date('Y-m-d', strtotime($form['batt_vehicle_in_date']));
        $vehicle_out_date = date('Y-m-d', strtotime($form['batt_vehicle_out_date']));
        $submission_date = date('Y-m-d', strtotime($form['batt_submission_date']));
        $approval_date = date('Y-m-d', strtotime($form['batt_approval_date']));
        $payment_approval_date = date('Y-m-d', strtotime($form['batt_payment_approval_date']));
        $payment_submission_date = date('Y-m-d', strtotime($form['batt_payment_submission_date']));
        $roleid = $this->get_approvarid_for_transaction(0, Sanitise::Long($form['new_batt_vehicleid']), Sanitise::Long($form['batt_amount_quote']), Sanitise::Long($form['batt_meter_reading']), 0);
        $Query = "INSERT INTO `maintenance` (maintenance_date,meter_reading,vehicle_in_date,vehicle_out_date,dealer_id,invoice_date, invoice_no, invoice_amount, vehicleid, timestamp, userid, customerno, roleid, notes, amount_quote, category, statusid, isdeleted, submission_date, approval_date, ofasno, payment_approval_date, payment_submission_date) VALUES ('%s',%d,'%s','%s',%d,'%s','%s',%d,%d,'%s',%d,%d,%d,'%s',%d,0,6,0,'%s','%s','%s','%s','%s')";
        $SQL = sprintf($Query, Sanitise::DateTime($vehicle_in_date), Sanitise::Long($form['batt_meter_reading']), Sanitise::DateTime($vehicle_in_date), Sanitise::DateTime($vehicle_out_date), Sanitise::Long($form['batt_dealerid']), Sanitise::DateTime($invoice_date), Sanitise::String($form['batt_invoice_no']), Sanitise::String($form['batt_amount_invoice']), Sanitise::Long($form['new_batt_vehicleid']), Sanitise::DateTime($today), $_SESSION['userid'], $this->_Customerno, $roleid, Sanitise::String($form['note_batt']), Sanitise::Long($form['batt_amount_quote']), Sanitise::Date($submission_date), Sanitise::Date($approval_date), Sanitise::Long($form['batt_ofasnumber']), Sanitise::Date($payment_approval_date), Sanitise::Date($payment_submission_date));
        $this->_databaseManager->executeQuery($SQL);
        $mainid = $this->_databaseManager->get_insertedId();
        $Query = "INSERT INTO `maintenance_history` (maintananceid, maintenance_date,meter_reading,vehicle_in_date,vehicle_out_date,dealer_id,invoice_date, invoice_no, invoice_amount, vehicleid, timestamp, userid, customerno, roleid, notes, amount_quote, category, statusid, submission_date, approval_date, ofasno, payment_approval_date, payment_submission_date) VALUES (%d,'%s',%d,'%s','%s',%d,'%s','%s',%d,%d,'%s',%d,%d,%d,'%s',%d,0,6,'%s','%s','%s','%s','%s')";
        $SQL = sprintf($Query, $mainid, Sanitise::DateTime($vehicle_in_date), Sanitise::Long($form['batt_meter_reading']), Sanitise::DateTime($vehicle_in_date), Sanitise::DateTime($vehicle_out_date), Sanitise::Long($form['batt_dealerid']), Sanitise::DateTime($invoice_date), Sanitise::String($form['batt_invoice_no']), Sanitise::String($form['batt_amount_invoice']), Sanitise::Long($form['new_batt_vehicleid']), Sanitise::DateTime($today), $_SESSION['userid'], $this->_Customerno, $roleid, Sanitise::String($form['note_batt']), Sanitise::Long($form['batt_amount_quote']), Sanitise::Date($submission_date), Sanitise::Date($approval_date), Sanitise::Long($form['batt_ofasnumber']), Sanitise::Date($payment_approval_date), Sanitise::Date($payment_submission_date));
        $this->_databaseManager->executeQuery($SQL);
        $uploaddir = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $form['new_batt_vehicleid'] . "/";
        $oldi1 = $uploaddir . '_invoice.pdf';
        $oldi2 = $uploaddir . '_invoice.png';
        $oldi3 = $uploaddir . '_invoice.jpg';
        $oldi4 = $uploaddir . '_invoice.jpeg';
        $newi1 = $uploaddir . $mainid . '_0_invoice.pdf';
        $newi2 = $uploaddir . $mainid . '_0_invoice.png';
        $newi3 = $uploaddir . $mainid . '_0_invoice.jpg';
        $newi4 = $uploaddir . $mainid . '_0_invoice.jpeg';
        if (file_exists($oldi1)) {
            rename($oldi1, $newi1);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_0_invoice.pdf', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set invoice_file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_0_invoice.pdf', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldi2)) {
            rename($oldi2, $newi2);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_0_invoice.png', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set invoice_file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_0_invoice.png', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldi3)) {
            rename($oldi3, $newi3);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_0_invoice.jpg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set invoice_file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_0_invoice.jpg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldi4)) {
            rename($oldi4, $newi4);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_0_invoice.jpeg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set invoice_file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_0_invoice.jpeg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        $oldq1 = $uploaddir . '_quote.pdf';
        $oldq2 = $uploaddir . '_quote.png';
        $oldq3 = $uploaddir . '_quote.jpg';
        $oldq4 = $uploaddir . '_quote.jpeg';
        $newq1 = $uploaddir . $mainid . '_0_quote.pdf';
        $newq2 = $uploaddir . $mainid . '_0_quote.png';
        $newq3 = $uploaddir . $mainid . '_0_quote.jpg';
        $newq4 = $uploaddir . $mainid . '_0_quote.jpeg';
        if (file_exists($oldq1)) {
            rename($oldq1, $newq1);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_0_quote.pdf', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_0_quote.pdf', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldq2)) {
            rename($oldq2, $newq2);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_0_quote.png', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_0_quote.png', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldq3)) {
            rename($oldq3, $newq3);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_0_quote.jpg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_0_quote.jpg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldq4)) {
            rename($oldq4, $newq4);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_0_quote.jpeg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_0_quote.jpeg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($form['new_batt_vehicleid']));
        $this->_databaseManager->executeQuery($SQL1);
        if ($mainid == '') {
            $vehicle = 'notok';
        } else {
            $vehicle = 'ok';
        }
        return $vehicle;
    }

    public function edit_battery_by_id($form, $statusid = null, $roleid) {
        $today = date("Y-m-d H:i:s");
        $main = $this->getlatest_maintenance_details($form['maintenanceid']); //fetch data so to insert in maintenance_history
        $invoice_date = date('Y-m-d', strtotime($form['batt_invoice_date']));
        $replacement_date = date('Y-m-d', strtotime($form['batt_vehicle_in_date']));
        $vehicle_in_date = date('Y-m-d', strtotime($form['batt_vehicle_in_date']));
        $vehicle_out_date = date('Y-m-d', strtotime($form['batt_vehicle_out_date']));
        if ($statusid == '10' || $statusid == '11' || $form['categoryid'] == '5') {
            $is_sentfinalpayment = 0;
            if ($statusid == '10') {
                $is_sentfinalpayment = '1';
            }
            $behalfid = 0;
            if ($main->userid != $_SESSION['userid']) {
                $behalfid = $_SESSION['userid'];
                $userid = $main->userid;
            } else {
                $behalfid = 0;
                $userid = $_SESSION['userid'];
            }
            $inv_amt = isset($form['batt_amount_invoice']) ? $form['batt_amount_invoice'] : $main->amount_quote;
            //die("---userid--".$userid."--->".$behalfid);
            $Query = "UPDATE `maintenance` set roleid='%d',`is_sentfinalpayment`='%d',timestamp='%s',payment_submission_date='%s',invoice_file_name='%s',userid=%d, behalfid=%d,invoice_amount='%s', invoice_date='%s', invoice_no='%s', maintenance_date='%s', vehicle_in_date='%s',vehicle_out_date='%s',statusid='%d', category='%d',ofasno='%s' WHERE id=%d AND customerno=%d";
            $SQL = sprintf($Query, Sanitise::Long($roleid), Sanitise::Long($is_sentfinalpayment), Sanitise::DateTime($today), Sanitise::DateTime($today), Sanitise::String($form['file_invoice_name']), $userid, $behalfid, Sanitise::String($inv_amt), Sanitise::Date($invoice_date), Sanitise::String($form['batt_invoice_no']), Sanitise::Date($replacement_date), Sanitise::Date($vehicle_in_date), Sanitise::Date($vehicle_out_date), Sanitise::Long($statusid), Sanitise::Long($form['categoryid']), Sanitise::String($form['ofasnumber']), Sanitise::Long($form['maintenanceid']), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);
            $Query1 = "INSERT INTO maintenance_history(behalfid,maintananceid,maintenance_date,meter_reading,dealer_id,vehicleid,timestamp,userid,customerno,roleid,notes,approval_notes,amount_quote,file_name,tyre_type,battery_srno,parts_list,task_select_array,category,statusid,submission_date,approval_date,payment_submission_date,invoice_file_name,invoice_amount,invoice_date,invoice_no,vehicle_in_date,vehicle_out_date,ofasno)
            VALUES(%d,%d,'%s', %d, %d, %d, '%s', %d, %d, %d, '%s', '%s', %d, '%s', '%s', '%s', '%s','%s',%d, %d,'%s','%s', '%s','%s','%s', '%s', '%s', '%s','%s','%s')";
            $SQL1 = sprintf($Query1, $main->behalfid, $form['maintenanceid'], Sanitise::Date($replacement_date), $main->meter_reading, $main->dealer_id, $main->vehicleid, Sanitise::DateTime($today), $main->userid, $main->customerno, $main->roleid, $main->notes, $main->approval_notes, $main->amount_quote, $main->file_name, $main->tyre_type, $main->battery_srno, $main->parts_list, $main->task_select_array, Sanitise::Long($form['categoryid']), $statusid, $main->submission_date, $main->approval_date, Sanitise::DateTime($today), Sanitise::String($form['file_invoice_name']), Sanitise::String($inv_amt), Sanitise::Date($invoice_date), Sanitise::String($form['batt_invoice_no']), Sanitise::Date($vehicle_in_date), Sanitise::Date($vehicle_out_date), Sanitise::String($form['ofasnumber']));
            //echo "<br>history". $SQL1;
            $this->_databaseManager->executeQuery($SQL1);
            $last_hist_insertid = $this->_databaseManager->get_insertedId();
            /*
        $Query = "UPDATE `maintenance_history` set timestamp='%s',payment_submission_date='%s',invoice_file_name='%s',userid=%d,invoice_amount=%d, invoice_date='%s', invoice_no='%s', maintenance_date='%s', vehicle_in_date='%s',vehicle_out_date='%s',statusid='%d', category='%d',ofasno='%s' WHERE maintananceid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::DateTime($today), Sanitise::DateTime($today), Sanitise::String($form['file_invoice_name']), $_SESSION['userid'], Sanitise::Long($form['batt_amount_invoice']), Sanitise::Date($invoice_date), Sanitise::String($form['batt_invoice_no']), Sanitise::Date($replacement_date), Sanitise::Date($vehicle_in_date), Sanitise::Date($vehicle_out_date), Sanitise::Long($statusid), Sanitise::Long($form['categoryid']), Sanitise::String($form['ofasnumber']), Sanitise::Long($form['maintenanceid']), $this->_Customerno);
        $_SESSION["sql"] = $SQL;
        $this->_databaseManager->executeQuery($SQL);
         *
         */
        } elseif ($statusid == '14' && $_SESSION['customerno'] == '118') {
            $behalfid = 0;
            if ($main->userid != $_SESSION['userid']) {
                $behalfid = $_SESSION['userid'];
                $userid = $main->userid;
            } else {
                $behalfid = 0;
                $userid = $_SESSION['userid'];
            }
            $Query = "UPDATE `maintenance` set timestamp='%s',invoice_file_name='%s',userid=%d,behalfid=%d ,invoice_amount='%s', invoice_date='%s', invoice_no='%s',statusid='%d', category='%d',ofasno='%s' WHERE id=%d AND customerno=%d";
            $SQL = sprintf($Query, Sanitise::DateTime($today), Sanitise::String($form['file_invoice_name']), $userid, $behalfid, Sanitise::String($form['batt_amount_invoice']), Sanitise::Date($invoice_date), Sanitise::String($form['batt_invoice_no']), Sanitise::Long($statusid), Sanitise::Long($form['categoryid']), Sanitise::String($form['ofasnumber']), Sanitise::Long($form['maintenanceid']), $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);
            $Query1 = "INSERT INTO maintenance_history(behalfid,maintananceid,maintenance_date,meter_reading,dealer_id,vehicleid,timestamp,userid,customerno,roleid,notes,approval_notes,amount_quote,file_name,tyre_type,battery_srno,parts_list,task_select_array,category,statusid,submission_date,approval_date,payment_submission_date,invoice_file_name,invoice_amount,invoice_date,invoice_no,vehicle_in_date,vehicle_out_date,ofasno)
            VALUES(%d,%d,'%s', %d, %d, %d, '%s', %d, %d, %d, '%s', '%s', %d, '%s', '%s', '%s', '%s','%s',%d, %d,'%s','%s', '%s','%s','%s', '%s', '%s', '%s','%s','%s')";
            $SQL1 = sprintf($Query1, $main->behalfid, $form['maintenanceid'], Sanitise::Date($replacement_date), $main->meter_reading, $main->dealer_id, $main->vehicleid, Sanitise::DateTime($today), $main->userid, $main->customerno, $main->roleid, $main->notes, $main->approval_notes, $main->amount_quote, $main->file_name, $main->tyre_type, $main->battery_srno, $main->parts_list, $main->task_select_array, Sanitise::Long($form['categoryid']), $statusid, $main->submission_date, $main->approval_date, Sanitise::DateTime($today), Sanitise::String($form['file_invoice_name']), Sanitise::String($form['batt_amount_invoice']), Sanitise::Date($invoice_date), Sanitise::String($form['batt_invoice_no']), Sanitise::Date($vehicle_in_date), Sanitise::Date($vehicle_out_date), Sanitise::String($form['ofasnumber']));
            $this->_databaseManager->executeQuery($SQL1);
            $last_hist_insertid = $this->_databaseManager->get_insertedId();
        } else {
            $behalfid = 0;
            if ($main->userid != $_SESSION['userid']) {
                $behalfid = $_SESSION['userid'];
                $userid = $main->userid;
            } else {
                $behalfid = 0;
                $userid = $_SESSION['userid'];
            }
            $Query = "UPDATE `maintenance` set behalfid=%d, timestamp='%s',ofasno='%s', statusid=%d WHERE id=%d";
            $SQL = sprintf($Query, $behalfid, Sanitise::DateTime($today), Sanitise::String($form['ofasnumber']), Sanitise::Long($statusid), Sanitise::Long($form['maintenanceid']));
            $this->_databaseManager->executeQuery($SQL);
            $Query1 = "INSERT INTO maintenance_history(behalfid,maintananceid,maintenance_date,meter_reading,dealer_id,vehicleid,timestamp,userid,customerno,roleid,notes,approval_notes,amount_quote,file_name,tyre_type,battery_srno,parts_list,task_select_array,category,statusid,submission_date,approval_date,payment_submission_date,invoice_file_name,invoice_amount,invoice_date,invoice_no,vehicle_in_date,vehicle_out_date,ofasno,payment_approval_date,payment_approval_note)
            VALUES(%d,%d,'%s', %d, %d, %d, '%s', %d, %d, %d, '%s', '%s', %d, '%s', '%s', '%s', '%s','%s',%d, %d,'%s','%s', '%s','%s','%s', '%s', '%s', '%s','%s','%s','%s','%s')";
            $SQL1 = sprintf($Query1, $main->behalfid, $form['maintenanceid'], $main->maintenance_date, $main->meter_reading, $main->dealer_id, $main->vehicleid, Sanitise::DateTime($today), $main->userid, $main->customerno, $main->roleid, $main->notes, $main->approval_notes, $main->amount_quote, $main->file_name, $main->tyre_type, $main->battery_srno, $main->parts_list, $main->task_select_array, $main->category, $statusid, $main->submission_date, $main->approval_date, $main->payment_submission_date, $main->invoice_file_name, $main->invoice_amount, $main->invoice_date, $main->invoice_no, $main->vehicle_in_date, $main->vehicle_out_date, Sanitise::String($form['ofasnumber']), $main->payment_approval_date, $main->payment_approval_note);
            $this->_databaseManager->executeQuery($SQL1);
            /*
        $Query = "UPDATE `maintenance_history` set timestamp='%s',ofasno='%s', statusid=%d WHERE maintananceid=%d";
        $SQL = sprintf($Query, Sanitise::DateTime($today), Sanitise::String($form['ofasnumber']), Sanitise::Long($statusid), Sanitise::Long($form['maintenanceid']));
        $_SESSION["sql"] = $SQL;
        $this->_databaseManager->executeQuery($SQL);
         *
         */
        }
        if ($statusid == '10' && $form['categoryid'] == '0') {
            // echo "10, cat-0"; die();
            // battery srno
            if (isset($last_hist_insertid) && $main->battery_srno != '') {
                $query1 = "UPDATE maintenance_history SET battery_srno='%s' WHERE hist_id=%d AND customerno=%d";
                $bquery1 = sprintf($query1
                    , Sanitise::String($main->battery_srno)
                    , $last_hist_insertid
                    , $this->_Customerno);
                $this->_databaseManager->executeQuery($bquery1);
            }
            if ($form['new_battsrno'] != '') {
                $query2 = "UPDATE maintenance SET battery_srno='%s' WHERE id=%d AND customerno=%d";
                $bquery2 = sprintf($query2
                    , Sanitise::String($form['new_battsrno'])
                    , Sanitise::Long($form['maintenanceid'])
                    , $this->_Customerno);
//echo $bquery1; die();
                $this->_databaseManager->executeQuery($bquery2);
            }
        }
        if ($statusid == '10' && $form['categoryid'] == '1') {
            //echo "10, cat-0"; die();
            //tyre srno
            if (isset($form['newtyre_list']) && $form['newtyre_list'] != '') {
                $query2 = "UPDATE maintenance SET tyre_type='%s' WHERE id=%d AND customerno=%d";
                $bquery2 = sprintf($query2
                    , Sanitise::String($form['newtyre_list'])
                    , Sanitise::Long($form['maintenanceid'])
                    , $this->_Customerno);
                $this->_databaseManager->executeQuery($bquery2);
            }
            if (isset($form['oldtyre_list']) && $form['oldtyre_list'] != '') {
                $query1 = "UPDATE maintenance_history SET tyre_type='%s' WHERE hist_id=%d AND customerno=%d";
                $bquery1 = sprintf($query1
                    , Sanitise::String($form['oldtyre_list'])
                    , $last_hist_insertid
                    , $this->_Customerno);
                $this->_databaseManager->executeQuery($bquery1);
            }
        }
        $vehicle = 'ok';
        return $vehicle;
    }

    public function edit_battery_history_by_id($form) {
        $today = date("Y-m-d H:i:s");
        $invoice_date = date('Y-m-d', strtotime($form['batt_invoice_date']));
        $replacement_date = date('Y-m-d', strtotime($form['batt_vehicle_in_date']));
        $vehicle_in_date = date('Y-m-d', strtotime($form['batt_vehicle_in_date']));
        $vehicle_out_date = date('Y-m-d', strtotime($form['batt_vehicle_out_date']));
        $submission_date = date('Y-m-d', strtotime($form['batt_submission_date']));
        $approval_date = date('Y-m-d', strtotime($form['batt_approval_date']));
        $payment_submission_date = date('Y-m-d', strtotime($form['batt_payment_submission_date']));
        $payment_approval_date = date('Y-m-d', strtotime($form['batt_payment_approval_date']));
        $Query = "UPDATE `maintenance` set maintenance_date='%s',meter_reading=%d,vehicle_in_date='%s',vehicle_out_date='%s', dealer_id=%d, invoice_date='%s', invoice_no='%s',invoice_amount='%s',vehicleid=%d,timestamp='%s',userid=%d,customerno=%d,roleid=1,notes='%s',amount_quote='%s',category=0,statusid=6,isdeleted=0,submission_date='%s',approval_date='%s',ofasno='%s',payment_approval_date='%s',payment_submission_date='%s' WHERE id=%d";
        $SQL = sprintf($Query, Sanitise::Date($vehicle_in_date), Sanitise::Long($form['batt_meter_reading']), Sanitise::Date($vehicle_in_date), Sanitise::Date($vehicle_out_date), Sanitise::Long($form['batt_dealerid']), Sanitise::Date($invoice_date), Sanitise::String($form['batt_invoice_no']), Sanitise::String($form['batt_amount_invoice']), Sanitise::Long($form['edit_batt_vehicleid']), Sanitise::DateTime($today), $_SESSION['userid'], $_SESSION['customerno'], Sanitise::String($form['note_batt']), Sanitise::Long($form['batt_amount_quote']), Sanitise::Date($submission_date), Sanitise::Date($approval_date), Sanitise::String($form['batt_ofasnumber']), Sanitise::Date($payment_approval_date), Sanitise::Date($payment_submission_date), Sanitise::Long($form['maintenanceid']));
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE `maintenance_history` set maintenance_date='%s',meter_reading=%d,vehicle_in_date='%s',vehicle_out_date='%s', dealer_id=%d, invoice_date='%s', invoice_no='%s',invoice_amount='%s',vehicleid=%d,timestamp='%s',userid=%d,customerno=%d,roleid=1,notes='%s',amount_quote='%s',category=0,statusid=6,submission_date='%s',approval_date='%s',ofasno='%s',payment_approval_date='%s',payment_submission_date='%s' WHERE maintananceid=%d";
        $SQL = sprintf($Query, Sanitise::Date($vehicle_in_date), Sanitise::Long($form['batt_meter_reading']), Sanitise::Date($vehicle_in_date), Sanitise::Date($vehicle_out_date), Sanitise::Long($form['batt_dealerid']), Sanitise::Date($invoice_date), Sanitise::String($form['batt_invoice_no']), Sanitise::String($form['batt_amount_invoice']), Sanitise::Long($form['edit_batt_vehicleid']), Sanitise::DateTime($today), $_SESSION['userid'], $_SESSION['customerno'], Sanitise::String($form['note_batt']), Sanitise::Long($form['batt_amount_quote']), Sanitise::Date($submission_date), Sanitise::Date($approval_date), Sanitise::String($form['batt_ofasnumber']), Sanitise::Date($payment_approval_date), Sanitise::Date($payment_submission_date), Sanitise::Long($form['maintenanceid']));
        $this->_databaseManager->executeQuery($SQL);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function edit_accessory_history_by_id($form) {
        $today = date("Y-m-d H:i:s");
        $invoice_date = date('Y-m-d', strtotime($form['accessory_invoice_date']));
        $replacement_date = date('Y-m-d', strtotime($form['accessory_vehicle_in_date']));
        $vehicle_in_date = date('Y-m-d', strtotime($form['accessory_vehicle_in_date']));
        $vehicle_out_date = date('Y-m-d', strtotime($form['accessory_vehicle_out_date']));
        $submission_date = date('Y-m-d', strtotime($form['accessory_submission_date']));
        $approval_date = date('Y-m-d', strtotime($form['accessory_approval_date']));
        $payment_submission_date = date('Y-m-d', strtotime($form['accessory_payment_submission_date']));
        $payment_approval_date = date('Y-m-d', strtotime($form['accessory_payment_approval_date']));
        $Query = "UPDATE `maintenance` set maintenance_date='%s',meter_reading=%d,vehicle_in_date='%s',vehicle_out_date='%s', dealer_id=%d, invoice_date='%s', invoice_no='%s',invoice_amount=%d,vehicleid=%d,timestamp='%s',userid=%d,roleid=1,notes='%s',amount_quote='%s',category=5,statusid=6,isdeleted=0,submission_date='%s',approval_date='%s',ofasno='%s',payment_approval_date='%s',payment_submission_date='%s' WHERE id=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Date($vehicle_in_date), Sanitise::Long($form['accessory_meter_reading']), Sanitise::Date($vehicle_in_date), Sanitise::Date($vehicle_out_date), Sanitise::Long($form['accessory_dealerid']), Sanitise::Date($invoice_date), Sanitise::String($form['accessory_invoice_no']), Sanitise::Long($form['accessory_amount_invoice']), Sanitise::Long($form['edit_accessory_vehicleid']), Sanitise::DateTime($today), $_SESSION['userid'], Sanitise::String($form['note_accessory']), Sanitise::Long($form['accessory_amount_quote']), Sanitise::Date($submission_date), Sanitise::Date($approval_date), Sanitise::String($form['accessory_ofasnumber']), Sanitise::Date($payment_approval_date), Sanitise::Date($payment_submission_date), Sanitise::Long($form['maintenanceid']), $_SESSION['customerno']);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE `maintenance_history` set maintenance_date='%s',meter_reading=%d,vehicle_in_date='%s',vehicle_out_date='%s', dealer_id=%d, invoice_date='%s', invoice_no='%s',invoice_amount=%d,vehicleid=%d,timestamp='%s',userid=%d,roleid=1,notes='%s',amount_quote='%s',category=5,statusid=6,submission_date='%s',approval_date='%s',ofasno='%s',payment_approval_date='%s',payment_submission_date='%s' WHERE maintananceid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Date($vehicle_in_date), Sanitise::Long($form['accessory_meter_reading']), Sanitise::Date($vehicle_in_date), Sanitise::Date($vehicle_out_date), Sanitise::Long($form['accessory_dealerid']), Sanitise::Date($invoice_date), Sanitise::String($form['accessory_invoice_no']), Sanitise::Long($form['accessory_amount_invoice']), Sanitise::Long($form['edit_accessory_vehicleid']), Sanitise::DateTime($today), $_SESSION['userid'], Sanitise::String($form['note_accessory']), Sanitise::Long($form['accessory_amount_quote']), Sanitise::Date($submission_date), Sanitise::Date($approval_date), Sanitise::String($form['accessory_ofasnumber']), Sanitise::Date($payment_approval_date), Sanitise::Date($payment_submission_date), Sanitise::Long($form['maintenanceid']), $_SESSION['customerno']);
        $this->_databaseManager->executeQuery($SQL);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function edit_tyre_by_id($form) {
        $today = date("Y-m-d H:i:s");
        $invoice_date = date('Y-m-d', strtotime($form['tyre_invoice_date']));
        $replacement_date = date('Y-m-d', strtotime($form['tyre_vehicle_in_date']));
        $vehicle_in_date = date('Y-m-d', strtotime($form['tyre_vehicle_in_date']));
        $vehicle_out_date = date('Y-m-d', strtotime($form['tyre_vehicle_out_date']));
        $submission_date = date('Y-m-d', strtotime($form['tyre_submission_date']));
        $approval_date = date('Y-m-d', strtotime($form['tyre_approval_date']));
        $payment_submission_date = date('Y-m-d', strtotime($form['tyre_payment_submission_date']));
        $payment_approval_date = date('Y-m-d', strtotime($form['tyre_payment_approval_date']));
        $Query = "UPDATE `maintenance` set maintenance_date='%s',meter_reading=%d,vehicle_in_date='%s',vehicle_out_date='%s', dealer_id=%d, invoice_date='%s', invoice_no='%s',invoice_amount=%d,vehicleid=%d,timestamp='%s',userid=%d,roleid=1,notes='%s',amount_quote='%s',category=1,statusid=6,isdeleted=0,submission_date='%s',approval_date='%s',ofasno='%s',payment_approval_date='%s',payment_submission_date='%s' WHERE id=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Date($vehicle_in_date), Sanitise::Long($form['tyre_meter_reading']), Sanitise::Date($vehicle_in_date), Sanitise::Date($vehicle_out_date), Sanitise::Long($form['tyre_dealerid']), Sanitise::Date($invoice_date), Sanitise::String($form['tyre_invoice_no']), Sanitise::Long($form['tyre_amount_invoice']), Sanitise::Long($form['edit_tyre_vehicleid']), Sanitise::DateTime($today), $_SESSION['userid'], Sanitise::String($form['note_tyre']), Sanitise::Long($form['tyre_amount_quote']), Sanitise::Date($submission_date), Sanitise::Date($approval_date), Sanitise::String($form['tyre_ofasnumber']), Sanitise::Date($payment_approval_date), Sanitise::Date($payment_submission_date), Sanitise::Long($form['maintenanceid']), $_SESSION['customerno']);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE `maintenance_history` set maintenance_date='%s',meter_reading=%d,vehicle_in_date='%s',vehicle_out_date='%s', dealer_id=%d, invoice_date='%s', invoice_no='%s',invoice_amount=%d,vehicleid=%d,timestamp='%s',userid=%d,roleid=1,notes='%s',amount_quote='%s',category=1,statusid=6,submission_date='%s',approval_date='%s',ofasno='%s',payment_approval_date='%s',payment_submission_date='%s' WHERE maintananceid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Date($vehicle_in_date), Sanitise::Long($form['tyre_meter_reading']), Sanitise::Date($vehicle_in_date), Sanitise::Date($vehicle_out_date), Sanitise::Long($form['tyre_dealerid']), Sanitise::Date($invoice_date), Sanitise::String($form['tyre_invoice_no']), Sanitise::Long($form['tyre_amount_invoice']), Sanitise::Long($form['edit_tyre_vehicleid']), Sanitise::DateTime($today), $_SESSION['userid'], Sanitise::String($form['note_tyre']), Sanitise::Long($form['tyre_amount_quote']), Sanitise::Date($submission_date), Sanitise::Date($approval_date), Sanitise::String($form['tyre_ofasnumber']), Sanitise::Date($payment_approval_date), Sanitise::Date($payment_submission_date), Sanitise::Long($form['maintenanceid']), $_SESSION['customerno']);
        $this->_databaseManager->executeQuery($SQL);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function update_notes($custno, $editvehicleid, $notes) {
        $Query = "UPDATE `vehicle` set notes='%s' WHERE customerno = %d AND vehicleid=%d";
        $SQL = sprintf($Query, $notes, $custno, $editvehicleid);
        $this->_databaseManager->executeQuery($SQL);
        $vehicle1 = 'ok';
        return $vehicle1;
    }

    public function edit_repair_by_id($form) {
        $today = date("Y-m-d H:i:s");
        $invoice_date = date('Y-m-d', strtotime($form['repair_invoice_date']));
        $replacement_date = date('Y-m-d', strtotime($form['repair_vehicle_in_date']));
        $vehicle_in_date = date('Y-m-d', strtotime($form['repair_vehicle_in_date']));
        $vehicle_out_date = date('Y-m-d', strtotime($form['repair_vehicle_out_date']));
        $submission_date = date('Y-m-d', strtotime($form['repair_submission_date']));
        $approval_date = date('Y-m-d', strtotime($form['repair_approval_date']));
        $payment_submission_date = date('Y-m-d', strtotime($form['repair_payment_submission_date']));
        $payment_approval_date = date('Y-m-d', strtotime($form['repair_payment_approval_date']));
        $Query = "UPDATE `maintenance` set maintenance_date='%s',meter_reading=%d,vehicle_in_date='%s',vehicle_out_date='%s', dealer_id=%d, invoice_date='%s', invoice_no='%s',invoice_amount=%d,vehicleid=%d,timestamp='%s',userid=%d,roleid=1,notes='%s',amount_quote='%s',statusid=6,isdeleted=0,submission_date='%s',approval_date='%s',ofasno='%s',payment_approval_date='%s',payment_submission_date='%s' WHERE id=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Date($vehicle_in_date), Sanitise::Long($form['repair_meter_reading']), Sanitise::Date($vehicle_in_date), Sanitise::Date($vehicle_out_date), Sanitise::Long($form['repair_dealerid']), Sanitise::Date($invoice_date), Sanitise::String($form['repair_invoice_no']), Sanitise::Long($form['repair_amount_invoice']), Sanitise::Long($form['edit_repair_vehicleid']), Sanitise::DateTime($today), $_SESSION['userid'], Sanitise::String($form['note_repair']), Sanitise::Long($form['repair_amount_quote']), Sanitise::Date($submission_date), Sanitise::Date($approval_date), Sanitise::String($form['repair_ofasnumber']), Sanitise::Date($payment_approval_date), Sanitise::Date($payment_submission_date), Sanitise::Long($form['maintenanceid']), $_SESSION['customerno']);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE `maintenance_history` set maintenance_date='%s',meter_reading=%d,vehicle_in_date='%s',vehicle_out_date='%s', dealer_id=%d, invoice_date='%s', invoice_no='%s',invoice_amount=%d,vehicleid=%d,timestamp='%s',userid=%d,roleid=1,notes='%s',amount_quote='%s',statusid=6,submission_date='%s',approval_date='%s',ofasno='%s',payment_approval_date='%s',payment_submission_date='%s' WHERE maintananceid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Date($vehicle_in_date), Sanitise::Long($form['repair_meter_reading']), Sanitise::Date($vehicle_in_date), Sanitise::Date($vehicle_out_date), Sanitise::Long($form['repair_dealerid']), Sanitise::Date($invoice_date), Sanitise::String($form['repair_invoice_no']), Sanitise::Long($form['repair_amount_invoice']), Sanitise::Long($form['edit_repair_vehicleid']), Sanitise::DateTime($today), $_SESSION['userid'], Sanitise::String($form['note_repair']), Sanitise::Long($form['repair_amount_quote']), Sanitise::Date($submission_date), Sanitise::Date($approval_date), Sanitise::String($form['repair_ofasnumber']), Sanitise::Date($payment_approval_date), Sanitise::Date($payment_submission_date), Sanitise::Long($form['maintenanceid']), $_SESSION['customerno']);
        $this->_databaseManager->executeQuery($SQL);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function getparts($parts) {
        $partsnew = Array();
        if (isset($parts)) {
            foreach ($parts as $thispartid) {
                $Query = "SELECT part_name FROM `parts` where id=%d";
                $vehiclesQuery = sprintf($Query, Sanitise::Long($thispartid));
                $this->_databaseManager->executeQuery($vehiclesQuery);
                if ($this->_databaseManager->get_rowCount() > 0) {
                    while ($row = $this->_databaseManager->get_nextRow()) {
                        $partsnew[] = $row['part_name'];
                    }
                }
            }
            return $partsnew;
        }
        return "";
    }

    public function getparts_main($parts) {
        $partsnew = Array();
        $Query = "SELECT partid,part_name FROM `maintenance_parts` INNER JOIN parts on parts.id = maintenance_parts.partid where mid=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($parts));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $partsnew[] = $row['part_name'];
            }
            return $partsnew;
        }
        return "";
    }

    public function gettasks($parts) {
        $partsnew = Array();
        if (isset($parts)) {
            foreach ($parts as $thispartid) {
                $Query = "SELECT task_name FROM `task` where id=%d";
                $vehiclesQuery = sprintf($Query, Sanitise::Long($thispartid));
                $this->_databaseManager->executeQuery($vehiclesQuery);
                if ($this->_databaseManager->get_rowCount() > 0) {
                    while ($row = $this->_databaseManager->get_nextRow()) {
                        $partsnew[] = $row['task_name'];
                    }
                }
            }
            return $partsnew;
        }
        return "";
    }

    public function gettasks_main($parts) {
        $partsnew = Array();
        $Query = "SELECT partid,task_name FROM `maintenance_tasks` INNER JOIN task on task.id = maintenance_tasks.partid where mid=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($parts));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $partsnew[] = $row['task_name'];
            }
            return $partsnew;
        }
        return "";
    }

    public function getbattery_id($maintenanceid) {
        $Query = "SELECT *,maintenance.customerno,maintenance.invoice_amount,maintenance.submission_date as main_submission_date,maintenance.approval_date as main_approval_date,maintenance.timestamp as main_timestamp,maintenance.notes as mainnotes, maintenance.id AS transid, maintenance_status.name as statusname, description.invoiceno as invno,description.invoiceamt as invamt, maintenance.payment_approval_note as approve_note, maintenance.approval_notes,mp.repairtype,mt.tyrerepairid,maintenance.vehicleid as vehid from maintenance
        INNER JOIN vehicle ON maintenance.vehicleid=vehicle.vehicleid
        LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
        INNER JOIN role ON maintenance.roleid= role.id
        INNER JOIN maintenance_status ON maintenance_status.id = maintenance.statusid
        LEFT OUTER JOIN dealer ON maintenance.dealer_id= dealer.dealerid
        LEFT OUTER JOIN description ON maintenance.vehicleid = description.vehicleid
        LEFT OUTER JOIN maintenance_tyre_repair_mapping as mt ON maintenance.id=mt.maintenanceid
        LEFT OUTER JOIN maintenance_tyre_repair_type as mp ON mt.tyrerepairid = mp.tyrerepairid
        WHERE maintenance.id=%d AND maintenance.isdeleted=0";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($maintenanceid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $vehicle = '';
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row['category'] == '0') {
                    $cat = "Battery";
                }
                if ($row['category'] == 1) {
                    $cat = "Tyre";
                }
                if ($row['category'] == 2) {
                    $cat = "Repair";
                }
                if ($row['category'] == 3) {
                    $cat = "Service";
                }
                if ($row['category'] == 5) {
                    $cat = "Accessory";
                }
                $battery_detail = array();
                $battery_detail['categoryid'] = $row['category'];
                $battery_detail['payment_approval_note'] = $row['payment_approval_note'];
                $battery_detail['meter_reading'] = $row['meter_reading'];
                if ($row['vehicle_in_date'] == "0000-00-00") {
                    $battery_detail['vehicle_in_date'] = date('d-m-Y');
                } else {
                    $battery_detail['vehicle_in_date'] = date('d-m-Y', strtotime($row['vehicle_in_date']));
                }
                if ($row['vehicle_out_date'] == "0000-00-00") {
                    $battery_detail['vehicle_out_date'] = date('d-m-Y');
                } else {
                    $battery_detail['vehicle_out_date'] = date('d-m-Y', strtotime($row['vehicle_out_date']));
                }
                $battery_detail['submission_date'] = date("d-m-Y", strtotime($row['main_submission_date']));
                $battery_detail['approval_date'] = date("d-m-Y", strtotime($row['main_approval_date']));
                $battery_detail['amount_quote'] = $row['amount_quote'];
                $battery_detail['groupname'] = $row['groupname'];
                if ($row['category'] == '0') {
                    $battery_detail['transid'] = "B00" . $row['transid'];
                } elseif ($row['category'] == '1') {
                    $battery_detail['transid'] = "T00" . $row['transid'];
                } elseif ($row['category'] == '2') {
                    $battery_detail['transid'] = "R00" . $row['transid'];
                } elseif ($row['category'] == '3') {
                    $battery_detail['transid'] = "S00" . $row['transid'];
                } elseif ($row['category'] == '5') {
                    $battery_detail['transid'] = "A00" . $row['transid'];
                }
                $battery_detail['mainid'] = $row['transid'];
                $battery_detail['odometer'] = $row['odometer'];
                $battery_detail['dealername'] = $row['name'];
                $battery_detail['dealerid'] = $row['dealerid'];
                $battery_detail['statusname'] = $row['statusname'];
                $battery_detail['category'] = $cat;
                $battery_detail['quote_file_name'] = $row['file_name'];
                $battery_detail['invoice_file_name'] = $row['invoice_file_name'];
                if ($row['invoice_date'] == "0000-00-00") {
                    $battery_detail['invoice_date'] = date('d-m-Y');
                } else {
                    $battery_detail['invoice_date'] = date('d-m-Y', strtotime($row['invoice_date']));
                }
                $battery_detail['tax'] = $row['tax'];
                $battery_detail['batt_invoice_no'] = $row['invoice_no'];
                $battery_detail['invoice_amount'] = $row['invoice_amount'];
                $battery_detail['vehicleid'] = $row['vehid'];
                $battery_detail['vehicleno'] = $row['vehicleno'];
                $battery_detail['approval_notes'] = $row['approval_notes'];
                $battery_detail['ofasnumber'] = $row['ofasno'];
                $battery_detail['payment_approval_date'] = date('d-m-Y', strtotime($row['payment_approval_date']));
                $battery_detail['payment_submission_date'] = date('d-m-Y', strtotime($row['payment_submission_date']));
                $battery_detail['timestamp'] = date('d-m-Y', strtotime($row['main_timestamp']));
                $battery_detail['customerno'] = $row['customerno'];
                $battery_detail['dealer_id'] = $row['dealer_id'];
                $battery_detail['category_type'] = $row['category'];
                $battery_detail['statusid'] = $row['statusid'];
                $battery_detail['notes'] = $row['mainnotes'];
                $battery_detail['approve_note'] = $row['approve_note'];
                $battery_detail['srno'] = $row['battery_srno'];
                $parts = explode(",", $row['parts_list']);
//$partsnew = $this->getparts($parts);
                $partsnew = $this->getparts_main($maintenanceid);
                @$battery_detail['partsnew'] = implode(", ", $partsnew);
                $tasks = explode(",", $row['task_select_array']);
//$tasksnew = $this->gettasks($tasks);
                $tasksnew = $this->gettasks_main($maintenanceid);
                @$battery_detail['tasksnew'] = implode(", ", $tasksnew);
                if ($row['category'] == 1) {
                    $repair_type = isset($row['repairtype']) ? $row['repairtype'] : '';
                    $tyrerepairid = isset($row['tyrerepairid']) ? $row['tyrerepairid'] : 0;
                    $battery_detail['repairtype'] = $repair_type;
                    $battery_detail['tyrerepairid'] = $tyrerepairid;
                    $battery_detail['tyre_type'] = $row['tyre_type'];
                    /*
                if ($row['tyre_type']) {
                $tyre_type = explode(",", $row['tyre_type']);
                if (isset($tyre_type)) {
                $tyre_detail = array();
                foreach ($tyre_type as $thistype) {
                if ($thistype == '1') {
                $tyre_detail[] = "Right Front";
                }
                if ($thistype == '2') {
                $tyre_detail[] = "Right Back";
                }
                if ($thistype == '3') {
                $tyre_detail[] = "Left Front";
                }
                if ($thistype == '4') {
                $tyre_detail[] = "Left Back";
                }
                if ($thistype == '5') {
                $tyre_detail[] = "Stepney";
                }
                }
                }
                }
                if (isset($tyre_detail)) {
                $battery_detail['tyre_type'] = implode(", ", $tyre_detail);
                }
                else {
                $battery_detail['tyre_type'] = "";
                }
                }
                // trigon transit tyre srno
                if ($row['category'] == '1' && $_SESSION['customerno'] == 118) {
                $battery_detail['tyre_srno'] = "";
                $tofind = strpos($row['tyre_type'], '-');
                if ($tofind === false) {// for old tyre replacement without Srno.
                $battery_detail['tyre_srno'] .="<tr>";
                $battery_detail['tyre_srno'] .="<td>Tyre Type</td><td>";
                $tyre_type = explode(",", $row['tyre_type']);
                if (isset($tyre_type)) {
                $tyre_detail = array();
                foreach ($tyre_type as $thistype) {
                if ($thistype == '1') {
                $tyre_detail[] = "Right Front";
                }
                if ($thistype == '2') {
                $tyre_detail[] = "Right Back";
                }
                if ($thistype == '3') {
                $tyre_detail[] = "Left Front";
                }
                if ($thistype == '4') {
                $tyre_detail[] = "Left Back";
                }
                if ($thistype == '5') {
                $tyre_detail[] = "Stepney";
                }
                }
                }
                if (isset($tyre_detail)) {
                $battery_detail['tyre_srno'] .= implode(',', $tyre_detail);
                }
                else {
                $battery_detail['tyre_srno'] .="";
                }
                $battery_detail['tyre_srno'] .="</td>";
                $battery_detail['tyre_srno'] .="</tr>";
                }
                else {
                $battery_detail['tyre_srno'] .="<table class='table table-bordered table-striped'>";
                $battery_detail['tyre_srno'] .="<th colspan='2'>New Tyre Serial No. Details</th>";
                $tyre_srno = explode(",", $row['tyre_type']);
                //print_r($tyre_srno);echo "<br>";die();
                foreach ($tyre_srno as $tyre_srnos) {
                $battery_detail['tyre_srno'] .="<tr>";
                $battery_detail['tyre_srno'] .="<td width='50%'>" . $tyre_srnos . "</td>";
                $battery_detail['tyre_srno'] .="</tr>";
                }
                $battery_detail['tyre_srno'] .="</table>";
                }
                 *
                 */
                }
                if ($row['category'] == '5') {
                    $battery_detail["get_accessories"] = "<table class='table table-bordered table-striped'>";
                    $battery_detail["get_accessories"] .= "<th colspan='4'>Accessory Details</th>";
                    $battery_detail["get_accessories"] .= "<tr>";
                    $battery_detail["get_accessories"] .= "<td width='15%'><b>Sr. No</b></td>";
                    $battery_detail["get_accessories"] .= "<td width='35%'><b>Accessory</b></td>";
                    $battery_detail["get_accessories"] .= "<td width='25%'><b>Cost</b></td>";
                    $battery_detail["get_accessories"] .= "<td width='25%'><b>Max. Perm. Amount</b></td>";
                    $battery_detail["get_accessories"] .= "</tr>";
                    $accessories = $this->getaccessories_forapproval($row['transid']);
                    if (isset($accessories)) {
                        foreach ($accessories as $thisacc) {
                            $battery_detail["get_accessories"] .= "<tr>";
                            $battery_detail["get_accessories"] .= "<td>" . $thisacc->count;
                            $battery_detail["get_accessories"] .= "</td>";
                            $battery_detail["get_accessories"] .= "<td>";
                            $battery_detail["get_accessories"] .= $thisacc->name;
                            $battery_detail["get_accessories"] .= "</td>";
                            $battery_detail["get_accessories"] .= "<td>";
                            $battery_detail["get_accessories"] .= $thisacc->cost;
                            $battery_detail["get_accessories"] .= "</td>";
                            $battery_detail["get_accessories"] .= "<td>";
                            $battery_detail["get_accessories"] .= $thisacc->max_amount;
                            $battery_detail["get_accessories"] .= "</td>";
                            $battery_detail["get_accessories"] .= "</tr>";
                        }
                    }
                    $battery_detail["get_accessories"] .= "</table>";
                }
            }
//return json_encode($battery_detail);
            return $battery_detail;
        }
    }

    public function getaccessories_forapproval($mainid) {
        $accs = Array();
        $count = 0;
        $Query = "SELECT * FROM `accessory_map` INNER JOIN `accessory` ON accessory_map.accessoryid = accessory.id
        where accessory_map.maintenanceid = %d AND  accessory_map.isdeleted=0";
        $GroupsQuery = sprintf($Query, $mainid);
        $this->_databaseManager->executeQuery($GroupsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $count++;
                $acc = new VOAccessory();
                $acc->count = $count;
                $acc->id = $row['accessoryid'];
                $acc->name = $row['name'];
                $acc->cost = $row['cost'];
                $acc->max_amount = $row['max_amount'];
                $accs[] = $acc;
            }
            return $accs;
        }
        return null;
    }

    public function getacc_id($maintenanceid) {
        $Query = "SELECT accident.submission_date, accident.approval_notes,accident.ofasnumber,`group`.groupname, vehicle.odometer, accident.drivername,
        accident.approval_date,accident.thirdparty_insured,vehicle.vehicleno,accident.statusid,accident.vehicleid,accident.accident_datetime,accident.accident_location,accident.description,accident.licence_validity_to,accident.licence_validity_from,accident.licence_type,
        accident.workshop_location,accident.send_report_to,accident.loss_amount,accident.sett_amount,accident.actual_amount,accident.mahindra_amount
        FROM `accident`
        INNER JOIN vehicle ON vehicle.vehicleid = accident.vehicleid
        LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
        WHERE accident.id=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($maintenanceid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $vehicle = '';
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $acc_detail = array();
                $acc_detail['transid'] = "AC00" . $maintenanceid;
                $acc_detail['groupname'] = $row['groupname'];
                $acc_detail['ofasnumber'] = $row['ofasnumber'];
                $acc_detail['approval_notes'] = $row['approval_notes'];
                $acc_detail['drivername'] = $row['drivername'];
                $acc_detail['odometer'] = $row['odometer'];
                $acc_detail['approval_date'] = date('d-m-Y', strtotime($row['approval_date']));
                $acc_detail['submission_date'] = date('d-m-Y', strtotime($row['submission_date']));
                $acc_detail['acc_Date'] = date('d-m-Y', strtotime($row['accident_datetime']));
                $acc_detail['STime'] = date('H:i', strtotime($row['accident_datetime']));
                $acc_detail['acc_location'] = $row['accident_location'];
                $acc_detail['acc_desc'] = $row['description'];
                $acc_detail['vehicleno'] = $row['vehicleno'];
                if ($row['thirdparty_insured'] == '1') {
                    $acc_detail['tpi'] = "Yes";
                } else {
                    $acc_detail['tpi'] = "No";
                }
                $acc_detail['val_to_Date'] = date('d-m-Y', strtotime($row['licence_validity_to']));
                $acc_detail['val_from_Date'] = date('d-m-Y', strtotime($row['licence_validity_from']));
                $acc_detail['statusid'] = $row['statusid'];
                $acc_detail['licence_type'] = $row['licence_type'];
                $acc_detail['add_workshop'] = $row['workshop_location'];
                $acc_detail['send_report'] = $row['send_report_to'];
                $acc_detail['loss_amount'] = $row['loss_amount'];
                $acc_detail['sett_amount'] = $row['sett_amount'];
                $acc_detail['actual_amount'] = $row['actual_amount'];
                $acc_detail['mahindra_amount'] = $row['mahindra_amount'];
                $uploaddir = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $row['vehicleid'] . "/";
                if (file_exists($uploaddir)) {
                    $dh = opendir($uploaddir);
                    $strcount = count($maintenanceid) + 1;
                    while (false !== ($filename = readdir($dh))) {
                        if (substr($filename, 0, $strcount) == $maintenanceid) {
                            $files_obj = array();
                            $files_obj['name'] = $filename;
                            $files_obj['url'] = $uploaddir . $filename;
                            $files[] = $files_obj;
                        }
                    }
                    $acc_detail['files'] = $files;
                }
            }
//return json_encode($acc_detail);
            return $acc_detail;
        }
    }

    public function add_tyre($form) {
        $today = date("Y-m-d H:i:s");
        $invoice_date = date('Y-m-d', strtotime($form['tyre_invoice_date']));
        $vehicle_in_date = date('Y-m-d', strtotime($form['tyre_vehicle_in_date']));
        $vehicle_out_date = date('Y-m-d', strtotime($form['tyre_vehicle_out_date']));
        $submission_date = date('Y-m-d', strtotime($form['tyre_submission_date']));
        $approval_date = date('Y-m-d', strtotime($form['tyre_approval_date']));
        $payment_approval_date = date('Y-m-d', strtotime($form['tyre_payment_approval_date']));
        $payment_submission_date = date('Y-m-d', strtotime($form['tyre_payment_submission_date']));
        $roleid = $this->get_approvarid_for_transaction(1, Sanitise::Long($form['new_tyre_vehicleid']), Sanitise::Long($form['tyre_amount_quote']), Sanitise::Long($form['tyre_meter_reading']), Sanitise::String($form['tyre_list']));
        $Query = "INSERT INTO `maintenance` (maintenance_date,meter_reading,vehicle_in_date,vehicle_out_date,dealer_id,invoice_date, invoice_no, invoice_amount, vehicleid, timestamp, userid, customerno, roleid, notes, amount_quote, category, statusid, isdeleted, submission_date, approval_date, ofasno, payment_approval_date, payment_submission_date,tyre_type) VALUES ('%s',%d,'%s','%s',%d,'%s','%s',%d,%d,'%s',%d,%d,%d,'%s',%d,1,6,0,'%s','%s','%s','%s','%s','%s')";
        $SQL = sprintf($Query, Sanitise::DateTime($vehicle_in_date), Sanitise::Long($form['tyre_meter_reading']), Sanitise::DateTime($vehicle_in_date), Sanitise::DateTime($vehicle_out_date), Sanitise::Long($form['tyre_dealerid']), Sanitise::DateTime($invoice_date), Sanitise::String($form['tyre_invoice_no']), Sanitise::String($form['tyre_amount_invoice']), Sanitise::Long($form['new_tyre_vehicleid']), Sanitise::DateTime($today), $_SESSION['userid'], $this->_Customerno, $roleid, Sanitise::String($form['note_tyre']), Sanitise::Long($form['tyre_amount_quote']), Sanitise::Date($submission_date), Sanitise::Date($approval_date), Sanitise::Long($form['tyre_ofasnumber']), Sanitise::Date($payment_approval_date), Sanitise::Date($payment_submission_date), Sanitise::String($form['tyre_list']));
        $this->_databaseManager->executeQuery($SQL);
        $mainid = $this->_databaseManager->get_insertedId();
        $Query = "INSERT INTO `maintenance_history` (maintananceid, maintenance_date,meter_reading,vehicle_in_date,vehicle_out_date,dealer_id,invoice_date, invoice_no, invoice_amount, vehicleid, timestamp, userid, customerno, roleid, notes, amount_quote, category, statusid, submission_date, approval_date, ofasno, payment_approval_date, payment_submission_date,tyre_type) VALUES (%d,'%s',%d,'%s','%s',%d,'%s','%s',%d,%d,'%s',%d,%d,%d,'%s',%d,1,6,'%s','%s','%s','%s','%s','%s')";
        $SQL = sprintf($Query, $mainid, Sanitise::DateTime($vehicle_in_date), Sanitise::Long($form['tyre_meter_reading']), Sanitise::DateTime($vehicle_in_date), Sanitise::DateTime($vehicle_out_date), Sanitise::Long($form['tyre_dealerid']), Sanitise::DateTime($invoice_date), Sanitise::String($form['tyre_invoice_no']), Sanitise::String($form['tyre_amount_invoice']), Sanitise::Long($form['new_tyre_vehicleid']), Sanitise::DateTime($today), $_SESSION['userid'], $this->_Customerno, $roleid, Sanitise::String($form['note_tyre']), Sanitise::Long($form['tyre_amount_quote']), Sanitise::Date($submission_date), Sanitise::Date($approval_date), Sanitise::Long($form['tyre_ofasnumber']), Sanitise::Date($payment_approval_date), Sanitise::Date($payment_submission_date), Sanitise::String($form['tyre_list']));
        $this->_databaseManager->executeQuery($SQL);
        $uploaddir = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $form['new_tyre_vehicleid'] . "/";
        $oldi1 = $uploaddir . '_invoice.pdf';
        $oldi2 = $uploaddir . '_invoice.png';
        $oldi3 = $uploaddir . '_invoice.jpg';
        $oldi4 = $uploaddir . '_invoice.jpeg';
        $newi1 = $uploaddir . $mainid . '_1_invoice.pdf';
        $newi2 = $uploaddir . $mainid . '_1_invoice.png';
        $newi3 = $uploaddir . $mainid . '_1_invoice.jpg';
        $newi4 = $uploaddir . $mainid . '_1_invoice.jpeg';
        if (file_exists($oldi1)) {
            rename($oldi1, $newi1);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_1_invoice.pdf', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set invoice_file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_1_invoice.pdf', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldi2)) {
            rename($oldi2, $newi2);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_1_invoice.png', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set invoice_file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_1_invoice.png', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldi3)) {
            rename($oldi3, $newi3);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_1_invoice.jpg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set invoice_file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_1_invoice.jpg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldi4)) {
            rename($oldi4, $newi4);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_1_invoice.jpeg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set invoice_file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_1_invoice.jpeg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        $oldq1 = $uploaddir . '_quote.pdf';
        $oldq2 = $uploaddir . '_quote.png';
        $oldq3 = $uploaddir . '_quote.jpg';
        $oldq4 = $uploaddir . '_quote.jpeg';
        $newq1 = $uploaddir . $mainid . '_1_quote.pdf';
        $newq2 = $uploaddir . $mainid . '_1_quote.png';
        $newq3 = $uploaddir . $mainid . '_1_quote.jpg';
        $newq4 = $uploaddir . $mainid . '_1_quote.jpeg';
        if (file_exists($oldq1)) {
            rename($oldq1, $newq1);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_1_quote.pdf', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_1_quote.pdf', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldq2)) {
            rename($oldq2, $newq2);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_1_quote.png', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_1_quote.png', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldq3)) {
            rename($oldq3, $newq3);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_1_quote.jpg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_1_quote.jpg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldq4)) {
            rename($oldq4, $newq4);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_1_quote.jpeg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_1_quote.jpeg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($form['new_tyre_vehicleid']));
        $this->_databaseManager->executeQuery($SQL1);
        if ($mainid == '') {
            $vehicle = 'notok';
        } else {
            $vehicle = 'ok';
        }
        return $vehicle;
    }

    public function add_fuel_transaction($form) {
        $today = date("Y-m-d H:i:s");
        $sdate = date('Y-m-d', strtotime($form['SDate1']));
        $stime = date('H:i:s', strtotime($form['STime1']));
        $sdate1 = $sdate . ' ' . $stime;
        if ($_SESSION['customerno'] == 118) {
            $Query = "INSERT INTO fuelstorrage(notes,ofasnumber,fuel,vehicleid, amount,additional_amount, rate, refno, openingkm, endingkm, average, dealerid, customerno, userid, addedon, timestamp) VALUES ('%s','%s','%f', %d, '%f','%f','%f', '%s', %d, %d,%d, %d, %d, %d, '%s', '%s')";
            $SQL = sprintf($Query, Sanitise::String($form['notes']), Sanitise::String(isset($form['ofasnumber']) ? $form['ofasnumber'] : ''), Sanitise::Float($form['fuel']), Sanitise::Long($form['vehicle_id']), Sanitise::Float($form['amount']), Sanitise::Float($form['additional_amount']), Sanitise::Float($form['rate']), Sanitise::String($form['refno']), Sanitise::String($form['openingkm']), Sanitise::String($form['endingkm']), Sanitise::String($form['avg']), Sanitise::Long($form['dealerid']), $this->_Customerno, $_SESSION['userid'], Sanitise::DateTime($sdate1), Sanitise::DateTime($today));
            $this->_databaseManager->executeQuery($SQL);
        } else {
            $refilldate = date('Y-m-d', strtotime($form['refilldate']));
            $Query = "INSERT INTO fuelstorrage(notes,ofasnumber,fuel, vehicleid, amount,additional_amount, rate, refno, openingkm, endingkm, average, dealerid, customerno, userid, addedon, timestamp,perdaykm,refilldate) VALUES ('%s','%s','%f', %d, '%f',%f, '%f', '%s', %d, %d,%d, %d, %d, %d, '%s', '%s', %d, '%s')";
            $SQL = sprintf($Query, Sanitise::String($form['notes']), Sanitise::String(isset($form['ofasnumber']) ? $form['ofasnumber'] : ''), Sanitise::Float($form['fuel']), Sanitise::Long($form['vehicle_id']), Sanitise::Float($form['amount']), Sanitise::Float($form['additional_amount']), Sanitise::Float($form['rate']), Sanitise::String($form['refno']), Sanitise::String($form['openingkm']), Sanitise::String($form['endingkm']), Sanitise::String($form['avg']), Sanitise::Long($form['dealerid']), $this->_Customerno, $_SESSION['userid'], Sanitise::DateTime($sdate1), Sanitise::DateTime($today), Sanitise::Float($form['perday']), $refilldate);
            $this->_databaseManager->executeQuery($SQL);
        }
        $id = $this->_databaseManager->get_insertedId();
        $responce = $id;
        return $responce;
    }

    public function edit_fuel_transaction($form) {
        $today = date("Y-m-d H:i:s");
        $sdate = date('Y-m-d', strtotime($form['SDate1']));
        $stime = date('H:i:s', strtotime($form['STime1']));
        $sdate1 = $sdate . ' ' . $stime;
        $Query = "Update fuelstorrage set notes='%s', ofasnumber='%s', fuel='%.2f', amount='%.2f',additional_amount='%.2f', rate='%.2f', refno='%s', openingkm=%d, endingkm=%d, average='%.2f', dealerid=%d, customerno=%d, userid=%d, addedon='%s', timestamp='%s' ";
        $Query .= " where fuelid=%d ";
        $SQL = sprintf($Query, Sanitise::String($form['notes']), Sanitise::String(isset($form['ofasnumber']) ? $form['ofasnumber'] : ''), Sanitise::Float($form['fuel']), Sanitise::Float($form['amount']), Sanitise::Float($form['addamount']), Sanitise::Float($form['rate']), Sanitise::String($form['refno']), Sanitise::String($form['openingkm']), Sanitise::String($form['endingkm']), Sanitise::Float($form['avg']), Sanitise::Long($form['dealerid']), $this->_Customerno, $_SESSION['userid'], Sanitise::DateTime($sdate1), Sanitise::DateTime($today), Sanitise::Long($form['fuelid']));
        $this->_databaseManager->executeQuery($SQL);
        return 'ok';
    }

    public function getEnddingKm($vehicleid) {
        $Query = "SELECT endingkm FROM fuelstorrage where vehicleid=%d order by fuelid DESC limit 1";
        $SQL = sprintf($Query, $vehicleid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
//echo $row['endingkm'];
                if ($row['endingkm'] == '') {
                    return 0;
                } else {
                    return $row['endingkm'];
                }
            }
        } else {
            return 0;
        }
    }

    public function delete_fuel_transaction($delid) {
        $Query = "Update fuelstorrage set isdeleted=1 where fuelid=%d";
        $SQL = sprintf($Query, Sanitise::Long($delid));
        $this->_databaseManager->executeQuery($SQL);
        return 'ok';
    }

    public function add_repair($form) {
        $today = date("Y-m-d H:i:s");
        $invoice_date = date('Y-m-d', strtotime($form['repair_invoice_date']));
        $vehicle_in_date = date('Y-m-d', strtotime($form['repair_vehicle_in_date']));
        $vehicle_out_date = date('Y-m-d', strtotime($form['repair_vehicle_out_date']));
        $inv = $form['repair_amount_invoice'] + $form['p_tax'];
        $submission_date = date('Y-m-d', strtotime($form['repair_submission_date']));
        $approval_date = date('Y-m-d', strtotime($form['repair_approval_date']));
        $payment_approval_date = date('Y-m-d', strtotime($form['repair_payment_approval_date']));
        $payment_submission_date = date('Y-m-d', strtotime($form['repair_payment_submission_date']));
        $roleid = $this->get_approvarid_for_transaction(Sanitise::String($form['category_type']), Sanitise::Long($form['new_repair_vehicleid']), Sanitise::Long($form['repair_amount_quote']), Sanitise::Long($form['repair_meter_reading']), 0);
        $Query = "INSERT INTO `maintenance` (maintenance_date,meter_reading,vehicle_in_date,vehicle_out_date,dealer_id,invoice_date, invoice_no,  invoice_amount, tax, vehicleid, timestamp, userid, customerno, roleid, notes, amount_quote, category, statusid, isdeleted, submission_date, approval_date, ofasno, payment_approval_date, payment_submission_date,parts_list, task_select_array) VALUES ('%s',%d,'%s','%s',%d,'%s','%s','%s',%d,%d,'%s',%d,%d,%d,'%s',%d,%d,6,0,'%s','%s','%s','%s','%s','%s','%s')";
        $SQL = sprintf($Query, Sanitise::DateTime($vehicle_in_date), Sanitise::Long($form['repair_meter_reading']), Sanitise::DateTime($vehicle_in_date), Sanitise::DateTime($vehicle_out_date), Sanitise::Long($form['repair_dealerid']), Sanitise::DateTime($invoice_date), Sanitise::String($form['repair_invoice_no']), Sanitise::String($form['repair_amount_invoice']), Sanitise::String($form['repair_invoice_tax']), Sanitise::Long($form['new_repair_vehicleid']), Sanitise::DateTime($today), $_SESSION['userid'], $this->_Customerno, $roleid, Sanitise::String($form['note_repair']), Sanitise::Long($form['repair_amount_quote']), Sanitise::String($form['category_type']), Sanitise::Date($submission_date), Sanitise::Date($approval_date), Sanitise::Long($form['repair_ofasnumber']), Sanitise::Date($payment_approval_date), Sanitise::Date($payment_submission_date), Sanitise::String($form['parts_list']), Sanitise::String($form['task_select_array']));
        $this->_databaseManager->executeQuery($SQL);
        $mainid = $this->_databaseManager->get_insertedId();
        $Query = "INSERT INTO `maintenance_history` (maintananceid, maintenance_date,meter_reading,vehicle_in_date,vehicle_out_date,dealer_id,invoice_date, invoice_no, invoice_amount, tax, vehicleid, timestamp, userid, customerno, roleid, notes, amount_quote, category, statusid, submission_date, approval_date, ofasno, payment_approval_date, payment_submission_date,parts_list, task_select_array) VALUES (%d,'%s',%d,'%s','%s',%d,'%s','%s','%s',%d,%d,'%s',%d,%d,%d,'%s',%d,%d,6,'%s','%s','%s','%s','%s','%s','%s')";
        $SQL = sprintf($Query, $mainid, Sanitise::DateTime($vehicle_in_date), Sanitise::Long($form['repair_meter_reading']), Sanitise::DateTime($vehicle_in_date), Sanitise::DateTime($vehicle_out_date), Sanitise::Long($form['repair_dealerid']), Sanitise::DateTime($invoice_date), Sanitise::String($form['repair_invoice_no']), Sanitise::String($form['repair_amount_invoice']), Sanitise::String($form['repair_invoice_tax']), Sanitise::Long($form['new_repair_vehicleid']), Sanitise::DateTime($today), $_SESSION['userid'], $this->_Customerno, $roleid, Sanitise::String($form['note_repair']), Sanitise::Long($form['repair_amount_quote']), Sanitise::String($form['category_type']), Sanitise::Date($submission_date), Sanitise::Date($approval_date), Sanitise::Long($form['repair_ofasnumber']), Sanitise::Date($payment_approval_date), Sanitise::Date($payment_submission_date), Sanitise::String($form['parts_list']), Sanitise::String($form['task_select_array']));
        $this->_databaseManager->executeQuery($SQL);
        $parts = "";
        $accessory_list = explode(",", $form['parts_select_array1']);
        //print_r($accessory_list);
        foreach ($accessory_list as $this_acc) {
            $this_acc_details = explode("-", $this_acc);
            $tot = $this_acc_details[1] / $this_acc_details[2];
            $Query = "INSERT INTO `maintenance_parts` (mid,partid,qty,amount,total,flag) VALUES (%d,%d,%d,%d,%d,1)";
            $SQL = sprintf($Query, Sanitise::Long($mainid), $this_acc_details[0], $this_acc_details[2], $tot, $this_acc_details[1]);
            $this->_databaseManager->executeQuery($SQL);
            $parts .= $this_acc_details[0] . ",";
        }
        $tasks = "";
        $accessory_list1 = explode(",", $form['tasks_select_array1']);
        foreach ($accessory_list1 as $this_acc) {
            $this_acc_details = explode("-", $this_acc);
            $tot = $this_acc_details[1] / $this_acc_details[2];
            $Query = "INSERT INTO `maintenance_tasks` (mid,partid,qty,amount,total,flag) VALUES (%d,%d,%d,%d,%d,2)";
            $SQL = sprintf($Query, Sanitise::Long($mainid), $this_acc_details[0], $this_acc_details[2], $tot, $this_acc_details[1]);
            $this->_databaseManager->executeQuery($SQL);
            $tasks .= $this_acc_details[0] . ",";
        }
        $uploaddir = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $form['new_repair_vehicleid'] . "/";
        $oldi1 = $uploaddir . '_invoice.pdf';
        $oldi2 = $uploaddir . '_invoice.png';
        $oldi3 = $uploaddir . '_invoice.jpg';
        $oldi4 = $uploaddir . '_invoice.jpeg';
        $newi1 = $uploaddir . $mainid . '_' . $form['category_type'] . '_invoice.pdf';
        $newi2 = $uploaddir . $mainid . '_' . $form['category_type'] . '_invoice.png';
        $newi3 = $uploaddir . $mainid . '_' . $form['category_type'] . '_invoice.jpg';
        $newi4 = $uploaddir . $mainid . '_' . $form['category_type'] . '_invoice.jpeg';
        if (file_exists($oldi1)) {
            rename($oldi1, $newi1);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_invoice.pdf', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set invoice_file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_invoice.pdf', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldi2)) {
            rename($oldi2, $newi2);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_invoice.png', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set invoice_file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_invoice.png', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldi3)) {
            rename($oldi3, $newi3);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_invoice.jpg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set invoice_file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_invoice.jpg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldi4)) {
            rename($oldi4, $newi4);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_invoice.jpeg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set invoice_file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_invoice.jpeg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        $oldq1 = $uploaddir . '_quote.pdf';
        $oldq2 = $uploaddir . '_quote.png';
        $oldq3 = $uploaddir . '_quote.jpg';
        $oldq4 = $uploaddir . '_quote.jpeg';
        $newq1 = $uploaddir . $mainid . '_' . $form['category_type'] . '_quote.pdf';
        $newq2 = $uploaddir . $mainid . '_' . $form['category_type'] . '_quote.png';
        $newq3 = $uploaddir . $mainid . '_' . $form['category_type'] . '_quote.jpg';
        $newq4 = $uploaddir . $mainid . '_' . $form['category_type'] . '_quote.jpeg';
        if (file_exists($oldq1)) {
            rename($oldq1, $newq1);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_quote.pdf', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_quote.pdf', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldq2)) {
            rename($oldq2, $newq2);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_quote.png', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_quote.png', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldq3)) {
            rename($oldq3, $newq3);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_quote.jpg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_quote.jpg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldq4)) {
            rename($oldq4, $newq4);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_quote.jpeg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
            $Query2 = "UPDATE maintenance_history set file_name='%s' WHERE `maintananceid`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_quote.jpeg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($form['new_tyre_vehicleid']));
        $this->_databaseManager->executeQuery($SQL1);
        if ($mainid == '') {
            $vehicle = 'notok';
        } else {
            $vehicle = 'ok';
        }
        return $vehicle;
        $today = date("Y-m-d H:i:s");
        $invoice_date = date('Y-m-d', strtotime($form['repair_invoice_date']));
        $replacement_date = date('Y-m-d', strtotime($form['repair_replacement_date']));
        $vehicle_in_date = date('Y-m-d', strtotime($form['repair_vehicle_in_date']));
        $vehicle_out_date = date('Y-m-d', strtotime($form['repair_vehicle_out_date']));
        $Query = "INSERT INTO `payment` (type,amount,chequeno, ifsc_code, bank_name) VALUES (%d,'%s','%s','%s','%s')";
        $SQL = sprintf($Query, Sanitise::Long($form['repair_payment_mode']), Sanitise::Long($form['repair_amount']), Sanitise::String($form['repair_cheque']), Sanitise::String($form['repair_ifsc']), Sanitise::String($form['repair_bank']));
        $this->_databaseManager->executeQuery($SQL);
        $paymentid = $this->_databaseManager->get_insertedId();
        $roleid = $this->get_approvarid_for_transaction($form['category_type'], Sanitise::Long($form['new_repair_vehicleid']), Sanitise::Long($form['repair_amount_quote']), Sanitise::Long($form['repair_meter_reading']), 0);
        $Query = "INSERT INTO `maintenance` (submission_date, roleid, userid,customerno,invoice_amount, invoice_date, invoice_no, maintenance_date, meter_reading,vehicle_in_date,vehicle_out_date,dealer_id,vehicleid,notes,amount_quote,statusid, category, payment_id, parts_list, task_select_array) VALUES ('%s',%d,%d,%d,%d,'%s','%s','%s',%d,'%s','%s',%d,%d,'%s',%d,'1',%d,%d,'%s','%s')";
        $SQL = sprintf($Query, Sanitise::DateTime($today), $roleid, $_SESSION['userid'], $this->_Customerno, Sanitise::Long($form['repair_amount_invoice']), Sanitise::Date($invoice_date), Sanitise::Long($form['repair_invoice_no']), Sanitise::Date($replacement_date), Sanitise::Long($form['repair_meter_reading']), Sanitise::Date($vehicle_in_date), Sanitise::Date($vehicle_out_date), Sanitise::Long($form['repair_dealerid']), Sanitise::Long($form['new_repair_vehicleid']), Sanitise::String($form['repair_note']), Sanitise::Long($form['repair_amount_quote']), Sanitise::String($form['category_type']), Sanitise::Long($paymentid), Sanitise::String($form['parts_list']), Sanitise::String($form['task_select_array']));
        $this->_databaseManager->executeQuery($SQL);
        $mainid = $this->_databaseManager->get_insertedId();
        $Query = "INSERT INTO `maintenance_history` (maintananceid, submission_date,roleid,userid,customerno,invoice_amount, invoice_date, invoice_no, maintenance_date, meter_reading,vehicle_in_date,vehicle_out_date,dealer_id,vehicleid,notes,amount_quote,statusid, category, payment_id, parts_list, task_select_array) VALUES (%d,'%s',%d,%d,%d,%d,'%s',%d,'%s',%d,'%s','%s',%d,%d,'%s',%d,'1',%d,%d,'%s','%s')";
        $SQL = sprintf($Query, $mainid, Sanitise::DateTime($today), $roleid, $_SESSION['userid'], $this->_Customerno, Sanitise::Long($form['batt_amount_invoice']), Sanitise::Date($invoice_date), Sanitise::Long($form['batt_invoice_no']), Sanitise::Date($replacement_date), Sanitise::Long($form['batt_meter_reading']), Sanitise::Date($vehicle_in_date), Sanitise::Date($vehicle_out_date), Sanitise::Long($form['batt_dealerid']), Sanitise::Long($form['new_batt_vehicleid']), Sanitise::String($form['batt_note']), Sanitise::Long($form['batt_amount_quote']), Sanitise::String($form['category_type']), Sanitise::Long($paymentid), Sanitise::String($form['parts_list']), Sanitise::String($form['task_select_array']));
        $this->_databaseManager->executeQuery($SQL);
        $uploaddir = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $form['new_repair_vehicleid'] . "/";
        $oldi1 = $uploaddir . '_invoice.pdf';
        $oldi2 = $uploaddir . '_invoice.png';
        $oldi3 = $uploaddir . '_invoice.jpg';
        $oldi4 = $uploaddir . '_invoice.jpeg';
        $newi1 = $uploaddir . $mainid . '_' . $form['category_type'] . '_invoice.pdf';
        $newi2 = $uploaddir . $mainid . '_' . $form['category_type'] . '_invoice.png';
        $newi3 = $uploaddir . $mainid . '_' . $form['category_type'] . '_invoice.jpg';
        $newi4 = $uploaddir . $mainid . '_' . $form['category_type'] . '_invoice.jpeg';
        if (file_exists($oldi1)) {
            rename($oldi1, $newi1);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_invoice.pdf', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldi2)) {
            rename($oldi2, $newi2);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_invoice.png', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldi3)) {
            rename($oldi3, $newi3);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_invoice.jpg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldi4)) {
            rename($oldi4, $newi4);
            $Query2 = "UPDATE maintenance set invoice_file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_invoice.jpeg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        $oldq1 = $uploaddir . '_quote.pdf';
        $oldq2 = $uploaddir . '_quote.png';
        $oldq3 = $uploaddir . '_quote.jpg';
        $oldq4 = $uploaddir . '_quote.jpeg';
        $newq1 = $uploaddir . $mainid . '_' . $form['category_type'] . '_quote.pdf';
        $newq2 = $uploaddir . $mainid . '_' . $form['category_type'] . '_quote.png';
        $newq3 = $uploaddir . $mainid . '_' . $form['category_type'] . '_quote.jpg';
        $newq4 = $uploaddir . $mainid . '_' . $form['category_type'] . '_quote.jpeg';
        if (file_exists($oldq1)) {
            rename($oldq1, $newq1);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_quote.pdf', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldq2)) {
            rename($oldq2, $newq2);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_quote.png', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldq3)) {
            rename($oldq3, $newq3);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_quote.jpg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        if (file_exists($oldq4)) {
            rename($oldq4, $newq4);
            $Query2 = "UPDATE maintenance set file_name='%s' WHERE `id`=%d";
            $SQL2 = sprintf($Query2, $mainid . '_' . $form['category_type'] . '_quote.jpeg', $mainid);
            $this->_databaseManager->executeQuery($SQL2);
        }
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($form['new_repair_vehicleid']));
        $this->_databaseManager->executeQuery($SQL1);
        if ($mainid == '') {
            $vehicle = 'notok';
        } else {
            $vehicle = 'ok';
        }
        return $vehicle;
    }

    public function send_approval($vehicleid) {
        $today = date("Y-m-d H:i:s");
        $Query = "UPDATE `vehicle` SET status_id='1',submission_date='%s' WHERE vehicleid = %d";
        $SQL = sprintf($Query, Sanitise::DateTime($today), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);
//        $vehiclesQuery = sprintf($Query,Sanitise::Long($vehicleid));
        //        $this->_databaseManager->executeQuery($vehiclesQuery);
        //        if ($this->_databaseManager->get_rowCount() > 0)
        //        {
        //        $Query = "UPDATE `vehicle` SET statusid='1' WHERE vehicleid = %d";
        //        $SQL = sprintf($Query,Sanitise::Long($vehicleid));
        //        $this->_databaseManager->executeQuery($SQL);
        //        }
        //        else{
        //        $Query = "INSERT INTO `vehicle` (vehicleid,statusid) VALUES (%d,'1')";
        //        $SQL = sprintf($Query,Sanitise::Long($vehicleid));
        //        $this->_databaseManager->executeQuery($SQL);
        //
        //        $mainid = $this->_databaseManager->get_insertedId();
        //        }
        $vehicle = 'ok';
        return $vehicle;
    }

    public function get_vehicle_creator($vehicleid) {
        $vehicles = array();
        $Query = "SELECT vehicle.userid, user.email, user.realname, user.username from vehicle
        INNER JOIN user on user.userid = vehicle.userid
        WHERE vehicle.customerno = %d AND vehicle.vehicleid = %d AND user.email <> '' AND user.isdeleted=0";
        $SQL = sprintf($Query, $this->_Customerno, $vehicleid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->userid = $row['userid'];
                $vehicle->email = $row['email'];
                $vehicle->realname = $row['realname'];
                $vehicle->username = $row['username'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
    }

    public function get_transaction_creator($vehicleid) {
        $vehicles = array();
        $Query = "select maintenance.userid, user.realname, user.username, user.email from maintenance
        inner join user on user.userid = maintenance.userid
        where maintenance.id = %d AND user.email <> '' AND maintenance.isdeleted=0";
        $SQL = sprintf($Query, $vehicleid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->userid = $row['userid'];
                $vehicle->email = $row['email'];
                $vehicle->realname = $row['realname'];
                $vehicle->username = $row['username'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
    }

    public function get_accident_creator($mainid) {
        $vehicles = array();
        $Query = "select maintenance.userid, user.realname, user.username, user.email from maintenance
        inner join user on user.userid = maintenance.userid
        where maintenance.vehicleid = %d AND user.email <> '' AND maintenance.isdeleted=0";
        $SQL = sprintf($Query, $mianid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->userid = $row['userid'];
                $vehicle->email = $row['email'];
                $vehicle->realname = $row['realname'];
                $vehicle->username = $row['username'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
    }

    public function get_vehicle_approver($customerno) {
//        $vehicles = array();
        //        $Query = "SELECT vehicle.groupid, group.cityid, city.districtid, district.stateid, state.nationid, user.userid, user.email, user.realname, user.username
        //        FROM vehicle
        //        LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
        //        LEFT OUTER JOIN `city` ON `city`.cityid = group.cityid
        //        LEFT OUTER JOIN `district` ON `district`.districtid = city.districtid
        //        LEFT OUTER JOIN `state` ON `state`.stateid = district.stateid
        //        LEFT OUTER JOIN `user` ON `user`.heirarchy_id = state.nationid
        //        WHERE vehicle.customerno=%d  AND vehicle.vehicleid =%d AND user.roleid =1 AND user.email <> '' AND user.isdeleted=0";
        //        $SQL = sprintf($Query, $this->_Customerno, $vehicleid);
        //        $this->_databaseManager->executeQuery($SQL);
        //        if ($this->_databaseManager->get_rowCount() > 0) {
        //            while ($row = $this->_databaseManager->get_nextRow()) {
        //                $vehicle = new VOVehicle();
        //                $vehicle->userid = $row['userid'];
        //                $vehicle->email = $row['email'];
        //                $vehicle->realname = $row['realname'];
        //                $vehicle->username = $row['username'];
        //                $vehicles[] = $vehicle;
        //            }
        //            return $vehicles;
        //        }
        $vehicles = array();
        $roles = $this->setMaintenanceRoles($customerno);
        $Query = "SELECT user.userid, user.email, user.realname, user.username
        FROM user
        WHERE user.customerno=%d AND user.roleid =%d AND user.email <> '' AND user.isdeleted=0";
        $SQL = sprintf($Query, $this->_Customerno, $roles->stateRoleId);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->userid = $row['userid'];
                $vehicle->email = $row['email'];
                $vehicle->realname = $row['realname'];
                $vehicle->username = $row['username'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
    }

    public function get_transaction_approver($mainid, $roleid) {
        $arrUserDetails = array();
        $Query = "";
        $Query .= "select email, user.userid, user.phone,realname, username
        from maintenance
        inner join user on user.roleid = maintenance.roleid
        where maintenance.id = %d
        AND user.roleid = %d
        AND user.email <> ''
        AND user.customerno = %d
        AND user.isdeleted = 0
        AND maintenance.isdeleted = 0";
        $SQL = sprintf($Query, $mainid, $roleid, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $userDetail = new stdClass();
                $userDetail->userid = $row['userid'];
                $userDetail->email = $row['email'];
                $userDetail->realname = $row['realname'];
                $userDetail->username = $row['username'];
                $userDetail->phone = $row['phone'];
                $arrUserDetails[] = $userDetail;
            }
        }
        return $arrUserDetails;
        /*
    $vehicles = array();
    //echo $roleid;
    $Query .= "select u.email,u.userid, u.realname, u.username from maintenance
    inner join user on user.userid = maintenance.userid";
    if ($roleid == '1') {
    $Query .=" inner join `group` on group.groupid = user.groupid
    inner join `city` on city.cityid = group.cityid
    inner join `district` on district.districtid = city.districtid
    inner join `state` on state.stateid = district.stateid
    inner join `user` as u on u.heirarchy_id = state.nationid
    ";
    }
    if ($roleid == '2') {
    $Query .= "
    inner join `group` on group.groupid = user.groupid
    inner join `city` on city.cityid = group.cityid
    inner join `district` on district.districtid = city.districtid
    inner join `state` on state.stateid = district.stateid
    inner join `user` as u on u.heirarchy_id = state.stateid
    ";
    }
    if ($roleid == '3') {
    $Query .= "
    inner join `group` on group.groupid = user.groupid
    inner join `city` on city.cityid = group.cityid
    inner join `district` on district.districtid = city.districtid
    inner join `user` as u on u.heirarchy_id = district.districtid ";
    }
    if ($roleid == '4') {
    $Query .= " inner join `group` on group.groupid = user.groupid
    inner join `city` on city.cityid = group.cityid
    inner join `user` as u on u.heirarchy_id = city.cityid ";
    }
    if ($roleid == '5') {
    $Query .= " inner join `user` as u on u.roleid = $roleid ";
    }
    if ($roleid == '10') {
    $Query .= " inner join `user` as u on u.roleid = $roleid ";
    }
    $Query.=" where maintenance.id=%d AND u.roleid =%d AND user.email <> '' and u.customerno=%d AND u.isdeleted=0 AND maintenance.isdeleted=0";
    $SQL = sprintf($Query, $mainid, $roleid, $this->_Customerno);
    $this->_databaseManager->executeQuery($SQL);
    if ($this->_databaseManager->get_rowCount() > 0) {
    while ($row = $this->_databaseManager->get_nextRow()) {
    $vehicle = new VOVehicle();
    $vehicle->userid = $row['userid'];
    $vehicle->email = $row['email'];
    $vehicle->realname = $row['realname'];
    $vehicle->username = $row['username'];
    $vehicles[] = $vehicle;
    }
    return $vehicles;
    }
     */
    }

    public function get_accident_approver($mainid, $roleid) {
        $vehicles = array();
        $Query .= "select user.email from accident
        inner join user on user.userid = accident.userid";
        /*
        if ($roleid == '1') {
        $Query .=" inner join `group` on group.groupid = user.groupid
        inner join `city` on city.cityid = group.cityid
        inner join `district` on district.districtid = city.districtid
        inner join `state` on state.stateid = district.districtid
        inner join `user` as u on u.heirarchy_id = state.nationid ";
        }
        if ($roleid == '2') {
        $Query .= "
        inner join `group` on group.groupid = user.groupid
        inner join `city` on city.cityid = group.cityid
        inner join `district` on district.districtid = city.districtid
        inner join `state` on state.stateid = district.districtid
        inner join `user` as u on u.heirarchy_id = state.stateid
        ";
        }
        if ($roleid == '3') {
        $Query .= "
        inner join `group` on group.groupid = user.groupid
        inner join `city` on city.cityid = group.cityid
        inner join `district` on district.districtid = city.districtid
        inner join `user` as u on u.heirarchy_id = district.districtid ";
        }
        if ($roleid == '4') {
        $Query .= " inner join `group` on group.groupid = user.groupid
        inner join `city` on city.cityid = group.cityid
        inner join `user` as u on u.heirarchy_id = city.districtid ";
        }
         */
        $Query .= " where accident.id=%d AND user.roleid =%d AND user.email <> '' ";
        $SQL = sprintf($Query, $mainid, $roleid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->userid = $row['userid'];
                $vehicle->email = $row['email'];
                $vehicle->realname = $row['realname'];
                $vehicle->username = $row['username'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
    }

    public function send_incomplete_approval($vehicleid) {
        $Query = "UPDATE `vehicle` SET status_id='5' WHERE vehicleid = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function get_filename($vehicleid) {
        $Query = "SELECT puc_filename,registration_filename,insurance_filename,other_upload1,other_upload2,other_upload3,other_upload4,other_upload5,other_upload6 FROM `vehicle` WHERE `vehicleid`=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::String($vehicleid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->other1 = $row['other_upload1'];
                $vehicle->other2 = $row['other_upload2'];
                $vehicle->other3 = $row['other_upload3'];
                $vehicle->other4 = $row['other_upload4'];
                $vehicle->puc_filename = $row['puc_filename'];
                $vehicle->reg_filename = $row['registration_filename'];
                $vehicle->ins_filename = $row['insurance_filename'];
                $vehicle->other5 = $row['other_upload5'];
                $vehicle->other6 = $row['other_upload6'];
            }
            return $vehicle;
        }
        return null;
    }

    public function filename_upload($vehicleid, $filename) {
        $Query = "UPDATE vehicle set other_upload1='%s' WHERE `vehicleid`=%d";
        $SQL = sprintf($Query, Sanitise::String($filename), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);
        $today = date("Y-m-d H:i:s");
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL1);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function filename_upload1($vehicleid, $filename) {
        $Query = "UPDATE vehicle set other_upload2='%s' WHERE `vehicleid`=%d";
        $SQL = sprintf($Query, Sanitise::String($filename), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);
        $today = date("Y-m-d H:i:s");
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL1);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function filename_upload2($vehicleid, $filename) {
        $Query = "UPDATE vehicle set other_upload3='%s' WHERE `vehicleid`=%d";
        $SQL = sprintf($Query, Sanitise::String($filename), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);
        $today = date("Y-m-d H:i:s");
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL1);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function filename_upload4($vehicleid, $filename) {
        $Query = "UPDATE vehicle set other_upload4='%s' WHERE `vehicleid`=%d";
        $SQL = sprintf($Query, Sanitise::String($filename), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);
        $today = date("Y-m-d H:i:s");
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL1);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function filename_upload5($vehicleid, $filename) {
        $Query = "UPDATE vehicle set other_upload5='%s' WHERE `vehicleid`=%d";
        $SQL = sprintf($Query, Sanitise::String($filename), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);
        $today = date("Y-m-d H:i:s");
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL1);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function filename_upload6($vehicleid, $filename) {
        $Query = "UPDATE vehicle set other_upload6='%s' WHERE `vehicleid`=%d";
        $SQL = sprintf($Query, Sanitise::String($filename), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);
        $today = date("Y-m-d H:i:s");
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL1);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function filename_puc($vehicleid, $filename) {
        $othername = "PUC_" . date("Y-m-d");
        $filearr = explode(".", $filename);
        $ext = $filearr[1];
        $othername = trim($othername . "." . $ext);
        $Query = "UPDATE vehicle set puc_filename='%s' WHERE `vehicleid`=%d";
        $SQL = sprintf($Query, Sanitise::String($othername), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);
        $today = date("Y-m-d H:i:s");
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL1);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function filename_speed_gov($vehicleid, $filename) {
        $othername = "speedgov_" . date("Y-m-d");
        $filearr = explode(".", $filename);
        $ext = $filearr[1];
        $othername = trim($othername . "." . $ext);
        $Query = "UPDATE vehicle set speed_gov_filename='%s' WHERE `vehicleid`=%d";
        $SQL = sprintf($Query, Sanitise::String($othername), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);
        $today = date("Y-m-d H:i:s");
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL1);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function filename_fireext($vehicleid, $filename) {
        $othername = "fireext_" . date("Y-m-d");
        $filearr = explode(".", $filename);
        $ext = $filearr[1];
        $othername = trim($othername . "." . $ext);
        $Query = "UPDATE vehicle set fire_extinguisher_filename='%s' WHERE `vehicleid`=%d";
        $SQL = sprintf($Query, Sanitise::String($othername), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);
        $today = date("Y-m-d H:i:s");
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL1);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function filename_reg($vehicleid, $filename) {
        $othername = "registration_" . date("Y-m-d");
        $filearr = explode(".", $filename);
        $ext = $filearr[1];
        $othername = trim($othername . "." . $ext);
        $Query = "UPDATE vehicle set registration_filename='%s' WHERE `vehicleid`=%d";
        $SQL = sprintf($Query, Sanitise::String($othername), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);
        $today = date("Y-m-d H:i:s");
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL1);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function filename_ins($vehicleid, $filename) {
        $othername = "insurance_" . date("Y-m-d");
        $filearr = explode(".", $filename);
        $ext = $filearr[1];
        $othername = trim($othername . "." . $ext);
        $Query = "UPDATE vehicle set insurance_filename='%s' WHERE `vehicleid`=%d";
        $SQL = sprintf($Query, Sanitise::String($othername), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);
        $today = date("Y-m-d H:i:s");
        $Query1 = "UPDATE vehicle set status_id='5',timestamp='%s' WHERE `vehicleid`=%d";
        $SQL1 = sprintf($Query1, Sanitise::DateTime($today), Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL1);
        $vehicle = 'ok';
        return $vehicle;
    }

    public function getinsurance_companys() {
        $vehicles = Array();
        $Query = "SELECT * FROM `insurance_company` ORDER BY name ASC";
        $vehiclesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->id = $row['id'];
                $vehicle->name = $row['name'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getinsurance_companyname($id) {
        $Query = "SELECT name FROM `insurance_company` where id=" . $id . " ORDER BY name ASC";
        $vehiclesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $inscomp = $row['name'];
            }
            return $inscomp;
        }
        return null;
    }

    public function gettax($vehicleid, $veh_read) {
        $Query = "SELECT *, user.realname FROM `tax` LEFT OUTER JOIN user on user.userid = tax.userid WHERE tax.vehicleid=%d AND tax.isdeleted='0' AND tax.customerno = %d ORDER BY tax.timestamp DESC";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $vehicle = '';
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row['type'] == '1') {
                    $type = "Road Tax";
                } elseif ($row['type'] == '2') {
                    $type = "Registration Tax";
                }
                $vehicle .= "<tr>";
                $vehicle .= "<td>$type</td>";
                $vehicle .= "<td>" . $row['from_date'] . "</td>";
                $vehicle .= "<td>" . $row['to_date'] . "</td>";
                $vehicle .= "<td>" . $row['amount'] . "</td>";
                $vehicle .= "<td>" . $row['realname'] . "</td>";
                $vehicle .= "<td>" . date(speedConstants::DEFAULT_DATETIME, strtotime($row['timestamp'])) . "</td>";
                if ($veh_read == 1) {
                    $vehicle .= "<td colspan='2'><a onclick='get_tax(" . $row['id'] . ")' ><img src='../../images/view.png'></img></a></td>";
                } else {
                    $vehicle .= "<td><a onclick='get_tax(" . $row['id'] . ")' ><i class='icon-pencil'></i></a></td>";
                    $vehicle .= "<td><a onclick='delete_tax(" . $row['id'] . ")' ><i class='icon-trash'></i></td>";
                }
                $vehicle .= "</tr>";
            }
        } else {
            $vehicle = "<tr><td colspan=100%>No Tax Added</td></tr>";
        }
        return $vehicle;
    }

    public function getnotes($vehicleid) {
        $Query = "SELECT *,maintenance_status.name as statusname from notes INNER JOIN user on user.userid = notes.userid
        INNER JOIN maintenance_status on maintenance_status.id = notes.status
        WHERE notes.vehicleid=%d ORDER BY notes.timestamp DESC";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $vehicle = '';
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle .= "<tr>";
                $vehicle .= "<td>" . $row['notes'] . "</td>";
                $vehicle .= "<td>" . $row['statusname'] . "</td>";
                $vehicle .= "<td>" . $row['realname'] . "</td>";
                $vehicle .= "<td>" . date(speedConstants::DEFAULT_DATETIME, strtotime($row['timestamp'])) . "</td>";
                $vehicle .= "</tr>";
            }
        } else {
            $vehicle = "<tr><td colspan=100%>No Notes Added</td></tr>";
        }
        return $vehicle;
    }

    public function gettax_view($vehicleid) {
        $Query = "SELECT * FROM `tax` WHERE vehicleid=%d AND customerno=%d AND isdeleted='0'";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $vehicle = '';
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row['type'] == '1') {
                    $type = "Road Tax";
                } elseif ($row['type'] == '2') {
                    $type = "Registration Tax";
                }
                $vehicle .= "<tr>";
                $vehicle .= "<td>" . $row['from_date'] . "</td>";
                $vehicle .= "<td>" . $row['to_date'] . "</td>";
                $vehicle .= "<td>" . $row['amount'] . "</td>";
                $vehicle .= "<td>$type</td>";
                $vehicle .= "</tr>";
            }
        } else {
            $vehicle = "<tr><td colspan=100%>No Tax Added</td></tr>";
        }
        return $vehicle;
    }

    public function gettax_id($taxid) {
        $Query = "SELECT * FROM `tax` WHERE id=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($taxid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $vehicle = '';
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $tax_detail = array();
                $tax_detail['from_date'] = date('d-m-Y', strtotime($row['from_date']));
                $tax_detail['to_date'] = date('d-m-Y', strtotime($row['to_date']));
                $tax_detail['type'] = $row['type'];
                $tax_detail['amount'] = $row['amount'];
                $tax_detail['reg_no'] = $row['reg_no'];
//                if($row['type'] == '1'){
                //                    $type = "Road Tax";
                //                }
                //                else if($row['type'] == '2'){
                //                    $type = "Registration Tax";
                //                }
                //                $vehicle .= "<tr>";
                //                $vehicle .= "<td>".$row['from_date']."</td>";
                //                $vehicle .= "<td>".$row['to_date']."</td>";
                //                $vehicle .= "<td>".$row['amount']."</td>";
                //                $vehicle .= "<td>$type</td>";
                //                $vehicle .= "<td><a onclick='get_tax(".$row['id'].")' ><i class='icon-pencil'></i></a></td>";
                //                $vehicle .= "<td><a onclick='delete_tax(".$row['id'].")' ><i class='icon-trash'></i></td>";
                //                $vehicle .= "</tr>";
            }
            return json_encode($tax_detail);
        }
    }

    public function get_battery_hist($form) {
        $Query = "SELECT maintenance.statusid, maintenance.id,maintenance.timestamp, user.realname, maintenance.maintenance_date as md, maintenance.invoice_amount, maintenance_status.name as statusname FROM `maintenance`
        INNER JOIN maintenance_status ON maintenance.statusid = maintenance_status.id
        INNER JOIN user ON user.userid = maintenance.userid
        WHERE maintenance.vehicleid=%d AND maintenance.category=0 AND maintenance.isdeleted=0";
        $vehiclesQuery = sprintf($Query, Sanitise::String($form['batt_vehicle_id']));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $vehicle = '';
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle .= "<tr>";
                $vehicle .= "<td>B00" . $row['id'] . "</td>";
                if (date("d-m-Y", strtotime($row['md'])) == "01-01-1970") {
                    $vehicle .= "<td>Incomplete</td>";
                } else {
                    $vehicle .= "<td>" . date("d-m-Y", strtotime($row['md'])) . "</td>";
                }
                $vehicle .= "<td>" . $row['realname'] . "</td>";
                $vehicle .= "<td>" . date(speedConstants::DEFAULT_DATETIME, strtotime($row['timestamp'])) . "</td>";
                $vehicle .= "<td>" . $row['statusname'] . "</td>";
                if ($row["statusid"] != '14') {
//$vehicle .= "<td><a onclick='get_battery(" . $row['id'] . ")' ><i class='icon-pencil'></i></a></td>";
                    $vehicle .= "<td><a onclick='delete_battery(" . $row['id'] . ")' ><i class='icon-trash'></i></td>";
                } else {
                    $vehicle .= "<td></td>";
                    $vehicle .= "<td></td>";
                }
                $vehicle .= "</tr>";
            }
        } else {
            $vehicle = "<tr><td colspan=100%>No Battery History Added</td></tr>";
        }
        return $vehicle;
    }

    public function get_battery_hist_view($form) {
        $Query = "SELECT maintenance.id,maintenance.timestamp, user.realname, maintenance.maintenance_date as md, maintenance.invoice_amount, maintenance_status.name as statusname FROM `maintenance`
        INNER JOIN maintenance_status ON maintenance.statusid = maintenance_status.id
        INNER JOIN user ON user.userid = maintenance.userid
        WHERE maintenance.vehicleid=%d AND maintenance.category=0 AND maintenance.isdeleted=0";
        $vehiclesQuery = sprintf($Query, Sanitise::String($form['batt_vehicle_id_view']));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $vehicle = '';
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle .= "<tr>";
                $vehicle .= "<td>B00" . $row['id'] . "</td>";
                if (date("d-m-Y", strtotime($row['md'])) == "01-01-1970") {
                    $vehicle .= "<td>Incomplete</td>";
                } else {
                    $vehicle .= "<td>" . date("d-m-Y", strtotime($row['md'])) . "</td>";
                }
                $vehicle .= "<td>" . $row['realname'] . "</td>";
                $vehicle .= "<td>" . date(speedConstants::DEFAULT_DATETIME, strtotime($row['timestamp'])) . "</td>";
                $vehicle .= "<td>" . $row['statusname'] . "</td>";
                $vehicle .= "<td><a onclick='get_battery(" . $row['id'] . ")' ><i class='icon-eye-open'></i></a></td>";
                $vehicle .= "</tr>";
            }
        } else {
            $vehicle = "<tr><td colspan=100%>No Battery History Added</td></tr>";
        }
        return $vehicle;
    }

    public function get_tyre_hist($form) {
        $Query = "SELECT maintenance.statusid, maintenance.id,maintenance.timestamp, user.realname, maintenance.maintenance_date as md, maintenance.invoice_amount, maintenance_status.name as statusname FROM `maintenance`
        INNER JOIN maintenance_status ON maintenance.statusid = maintenance_status.id
        INNER JOIN user ON user.userid = maintenance.userid
        WHERE maintenance.vehicleid=%d AND maintenance.category=1 AND maintenance.isdeleted=0";
        $vehiclesQuery = sprintf($Query, Sanitise::String($form['tyre_vehicle_id']));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $vehicle = '';
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle .= "<tr>";
                $vehicle .= "<td>T00" . $row['id'] . "</td>";
                if (date("d-m-Y", strtotime($row['md'])) == "01-01-1970") {
                    $vehicle .= "<td>Incomplete</td>";
                } else {
                    $vehicle .= "<td>" . date("d-m-Y", strtotime($row['md'])) . "</td>";
                }
                $vehicle .= "<td>" . $row['realname'] . "</td>";
                $vehicle .= "<td>" . date(speedConstants::DEFAULT_DATETIME, strtotime($row['timestamp'])) . "</td>";
                $vehicle .= "<td>" . $row['statusname'] . "</td>";
                if ($row["statusid"] != '14') {
//$vehicle .= "<td><a onclick='get_tyre(" . $row['id'] . ")' ><i class='icon-pencil'></i></a></td>";
                    $vehicle .= "<td><a onclick='delete_tyre(" . $row['id'] . ")' ><i class='icon-trash'></i></td>";
                } else {
                    $vehicle .= "<td></td>";
                    $vehicle .= "<td></td>";
                }
                $vehicle .= "</tr>";
            }
        } else {
            $vehicle = "<tr><td colspan=100%>No Tyre History Added</td></tr>";
        }
        return $vehicle;
    }

    public function get_tyre_hist_view($form) {
        $Query = "SELECT maintenance.id,maintenance.timestamp, user.realname, maintenance.maintenance_date as md,maintenance.invoice_amount, maintenance_status.name as statusname FROM `maintenance`
        INNER JOIN maintenance_status ON maintenance.statusid = maintenance_status.id
        INNER JOIN user ON user.userid = maintenance.userid
        WHERE maintenance.vehicleid=%d AND maintenance.category=1 AND maintenance.isdeleted=0";
        $vehiclesQuery = sprintf($Query, Sanitise::String($form['tyre_vehicle_id_view']));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $vehicle = '';
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle .= "<tr>";
                $vehicle .= "<td>T00" . $row['id'] . "</td>";
                if (date("d-m-Y", strtotime($row['md'])) == "01-01-1970") {
                    $vehicle .= "<td>Incomplete</td>";
                } else {
                    $vehicle .= "<td>" . date("d-m-Y", strtotime($row['md'])) . "</td>";
                }
                $vehicle .= "<td>" . $row['realname'] . "</td>";
                $vehicle .= "<td>" . date(speedConstants::DEFAULT_DATETIME, strtotime($row['timestamp'])) . "</td>";
                $vehicle .= "<td>" . $row['statusname'] . "</td>";
                $vehicle .= "<td><a onclick='get_tyre(" . $row['id'] . ")' ><i class='icon-eye-open'></i></a></td>";
                $vehicle .= "</tr>";
            }
        } else {
            $vehicle = "<tr><td colspan=100%>No Tyre History Added</td></tr>";
        }
        return $vehicle;
    }

    public function get_repair_hist($form) {
        $Query = "SELECT maintenance.statusid, maintenance.category, maintenance.id,maintenance.timestamp, user.realname, maintenance.maintenance_date as md, maintenance.invoice_amount, maintenance_status.name as statusname FROM `maintenance`
        INNER JOIN maintenance_status ON maintenance.statusid = maintenance_status.id
        INNER JOIN user ON user.userid = maintenance.userid
        WHERE maintenance.vehicleid=%d AND maintenance.category IN (-1,2,3) AND maintenance.isdeleted=0";
        $vehiclesQuery = sprintf($Query, Sanitise::String($form['repair_vehicle_id']));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $vehicle = '';
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle .= "<tr>";
                if ($row['category'] == '2') {
                    $vehicle .= "<td>R00" . $row['id'] . "</td>";
                } else {
                    $vehicle .= "<td>S00" . $row['id'] . "</td>";
                }
                if (date("d-m-Y", strtotime($row['md'])) == "01-01-1970") {
                    $vehicle .= "<td><Incomplete/td>";
                } else {
                    $vehicle .= "<td>" . date("d-m-Y", strtotime($row['md'])) . "</td>";
                }
                $vehicle .= "<td>" . $row['realname'] . "</td>";
                $vehicle .= "<td>" . date(speedConstants::DEFAULT_DATETIME, strtotime($row['timestamp'])) . "</td>";
                $vehicle .= "<td>" . $row['statusname'] . "</td>";
                if ($row["statusid"] != '14') {
// $vehicle .= "<td><a onclick='get_repair(" . $row['id'] . ")' ><i class='icon-pencil'></i></a></td>";
                    $vehicle .= "<td><a onclick='delete_repair(" . $row['id'] . ")' ><i class='icon-trash'></i></td>";
                } else {
                    $vehicle .= "<td></td>";
                    $vehicle .= "<td></td>";
                }
                $vehicle .= "</tr>";
            }
        } else {
            $vehicle = "<tr><td colspan=100%>No Repair History Added</td></tr>";
        }
        return $vehicle;
    }

    public function get_accessory_hist($form) {
        $Query = "SELECT maintenance.statusid, maintenance.category, maintenance.id,maintenance.timestamp, user.realname, maintenance.maintenance_date as md, maintenance.invoice_amount, maintenance_status.name as statusname FROM `maintenance`
        INNER JOIN maintenance_status ON maintenance.statusid = maintenance_status.id
        INNER JOIN user ON user.userid = maintenance.userid
        WHERE maintenance.vehicleid=%d AND maintenance.category IN (5) AND maintenance.isdeleted=0";
        $vehiclesQuery = sprintf($Query, Sanitise::String($form['accessory_vehicle_id']));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $vehicle = '';
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle .= "<tr>";
                $vehicle .= "<td>A00" . $row['id'] . "</td>";
                if (date("d-m-Y", strtotime($row['md'])) == "01-01-1970") {
                    $vehicle .= "<td><Incomplete/td>";
                } else {
                    $vehicle .= "<td>" . date("d-m-Y", strtotime($row['md'])) . "</td>";
                }
                $vehicle .= "<td>" . $row['realname'] . "</td>";
                $vehicle .= "<td>" . date(speedConstants::DEFAULT_DATETIME, strtotime($row['timestamp'])) . "</td>";
                $vehicle .= "<td>" . $row['statusname'] . "</td>";
                if ($row["statusid"] != '14') {
//$vehicle .= "<td><a onclick='get_accessory(" . $row['id'] . ")' ><i class='icon-pencil'></i></a></td>";
                    $vehicle .= "<td><a onclick='delete_accessory(" . $row['id'] . ")' ><i class='icon-trash'></i></td>";
                } else {
                    $vehicle .= "<td></td>";
                    $vehicle .= "<td></td>";
                }
                $vehicle .= "</tr>";
            }
        } else {
            $vehicle = "<tr><td colspan=100%>No Accessory History Added</td></tr>";
        }
        return $vehicle;
    }

    public function get_accident_hist($form) {
        $Query = "SELECT accident.statusid, accident.id,accident.timestamp, user.realname, accident.accident_datetime as md, accident.loss_amount, maintenance_status.name as statusname FROM `accident`
        INNER JOIN maintenance_status ON accident.statusid = maintenance_status.id
        INNER JOIN user ON user.userid = accident.userid
        WHERE accident.vehicleid=%d AND accident.isdeleted=0";
        $vehiclesQuery = sprintf($Query, Sanitise::String($form['accident_hist_vehicle_id']));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $vehicle = '';
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle .= "<tr>";
                $vehicle .= "<td>AC00" . $row['id'] . "</td>";
                if (date("d-m-Y", strtotime($row['md'])) == "01-01-1970") {
                    $vehicle .= "<td>Incomplete</td>";
                } else {
                    $vehicle .= "<td>" . date("d-m-Y", strtotime($row['md'])) . "</td>";
                }
                $vehicle .= "<td>" . $row['realname'] . "</td>";
                $vehicle .= "<td>" . date(speedConstants::DEFAULT_DATETIME, strtotime($row['timestamp'])) . "</td>";
                $vehicle .= "<td>" . $row['statusname'] . "</td>";
                if ($row["statusid"] != '1') {
//$vehicle .= "<td><a onclick='get_accident(" . $row['id'] . ")' ><i class='icon-pencil'></i></a></td>";
                    $vehicle .= "<td><a onclick='delete_accident(" . $row['id'] . ")' ><i class='icon-trash'></i></td>";
                } else {
                    $vehicle .= "<td></td>";
                    $vehicle .= "<td></td>";
                }
                $vehicle .= "</tr>";
            }
        } else {
            $vehicle = "<tr><td colspan=100%>No Accident Claim History Added</td></tr>";
        }
        return $vehicle;
    }

    public function get_repair_hist_view($form) {
        $Query = "SELECT maintenance.category, maintenance.id,maintenance.timestamp, user.realname, maintenance.maintenance_date as md,maintenance.invoice_amount, maintenance_status.name as statusname FROM `maintenance`
        INNER JOIN maintenance_status ON maintenance.statusid = maintenance_status.id
        INNER JOIN user ON user.userid = maintenance.userid
        WHERE maintenance.vehicleid=%d AND maintenance.category IN (-1,2,3) AND maintenance.isdeleted=0";
        $vehiclesQuery = sprintf($Query, Sanitise::String($form['repair_vehicle_id_view']));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $vehicle = '';
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle .= "<tr>";
                if ($row['category'] == '2') {
                    $vehicle .= "<td>R00" . $row['id'] . "</td>";
                } else {
                    $vehicle .= "<td>S00" . $row['id'] . "</td>";
                }
                if (date("d-m-Y", strtotime($row['md'])) == "01-01-1970") {
                    $vehicle .= "<td>Incomplete</td>";
                } else {
                    $vehicle .= "<td>" . date("d-m-Y", strtotime($row['md'])) . "</td>";
                }
                $vehicle .= "<td>" . $row['realname'] . "</td>";
                $vehicle .= "<td>" . date(speedConstants::DEFAULT_DATETIME, strtotime($row['timestamp'])) . "</td>";
                $vehicle .= "<td>" . $row['statusname'] . "</td>";
                $vehicle .= "<td><a onclick='get_repair(" . $row['id'] . ")' ><i class='icon-eye-open'></i></a></td>";
                $vehicle .= "</tr>";
            }
        } else {
            $vehicle = "<tr><td colspan=100%>No Repair History Added</td></tr>";
        }
        return $vehicle;
    }

    public function get_accident_hist_view($form) {
        $Query = "SELECT accident.statusid, accident.id,accident.timestamp, user.realname, accident.accident_datetime as md, accident.loss_amount, maintenance_status.name as statusname FROM `accident`
        INNER JOIN maintenance_status ON accident.statusid = maintenance_status.id
        INNER JOIN user ON user.userid = accident.userid
        WHERE accident.vehicleid=%d AND accident.isdeleted=0";
        $vehiclesQuery = sprintf($Query, Sanitise::String($form['accident_vehicle_id_view']));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        $vehicle = '';
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle .= "<tr>";
                $vehicle .= "<td>AC00" . $row['id'] . "</td>";
                if (date("d-m-Y", strtotime($row['md'])) == "01-01-1970") {
                    $vehicle .= "<td>Incomplete</td>";
                } else {
                    $vehicle .= "<td>" . date("d-m-Y", strtotime($row['md'])) . "</td>";
                }
                $vehicle .= "<td>" . $row['realname'] . "</td>";
                $vehicle .= "<td>" . date(speedConstants::DEFAULT_DATETIME, strtotime($row['timestamp'])) . "</td>";
                $vehicle .= "<td>" . $row['statusname'] . "</td>";
                if ($row["statusid"] != '1') {
                    $vehicle .= "<td><a onclick='get_accident(" . $row['id'] . ")' ><i class='icon-eye-open'></i></a></td>";
                } else {
                    $vehicle .= "<td></td>";
                    $vehicle .= "<td></td>";
                }
                $vehicle .= "</tr>";
            }
        } else {
            $vehicle = "<tr><td colspan=100%>No Accident Claim History Added</td></tr>";
        }
        return $vehicle;
    }

    public function getvehiclesforrtd() {
        $vehicles = Array();
        if (!isset($_SESSION['ecodeid'])) {
            $Query = "SELECT vehicle.vehicleno, vehicle.vehicleid, vehicle.curspeed, vehicle.overspeed_limit, vehicle.stoppage_transit_time, vehicle.lastupdated,vehicle.chkpoint_status,checkpoint.cname, devices.registeredon, devices.ignition, vehicle.stoppage_flag FROM vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN unit ON devices.uid = unit.uid
            LEFT JOIN checkpoint ON checkpoint.checkpointid=vehicle.checkpointId
            WHERE vehicle.customerno =%d AND unit.trans_statusid NOT IN (10,22) ";
            if (isset($_SESSION) && $_SESSION['groupid'] != 0) {
                $Query .= " AND vehicle.groupid =%d";
            }
            $Query .= " AND vehicle.isdeleted=0 ORDER BY devices.lastupdated DESC";
            if (isset($_SESSION) && $_SESSION['groupid'] != 0) {
                $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
            } else {
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            }
        } else {
            $Query = "SELECT ecodeman.vehicleid, vehicle.vehicleno, vehicle.curspeed, vehicle.overspeed_limit, vehicle.stoppage_transit_time, vehicle.lastupdated,vehicle.chkpoint_status,checkpoint.cname, devices.registeredon,
            devices.ignition, vehicle.stoppage_flag, vehicle.groupid FROM ecodeman
            INNER JOIN vehicle ON vehicle.vehicleid = ecodeman.vehicleid
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN unit ON devices.uid = unit.uid
            LEFT JOIN checkpoint ON checkpoint.checkpointid=vehicle.checkpointId
            WHERE ecodeman.customerno =%d AND ecodeman.ecodeid=%d AND unit.trans_statusid NOT IN (10,22) ";
            $Query .= " AND ecodeman.isdeleted=0 ORDER BY devices.lastupdated DESC";
            $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['e_id']);
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->stoppage_transit_time = $row['stoppage_transit_time'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                }
                $vehicle->ignition = $row['ignition'];
                $vehicle->stoppage_flag = $row["stoppage_flag"];
                $vehicle->chkpoint_status = $row["chkpoint_status"];
                $vehicle->cname = $row["cname"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getvehiclesforrtd_byId($srhstring) {
        $vehicles = Array();
        if (!isset($_SESSION['ecodeid'])) {
            $Query = "SELECT vehicle.vehicleno, vehicle.vehicleid, vehicle.curspeed, vehicle.overspeed_limit, vehicle.stoppage_transit_time, vehicle.lastupdated, devices.registeredon, devices.ignition, vehicle.stoppage_flag FROM vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN unit ON devices.uid = unit.uid
            WHERE vehicle.customerno =%d AND vehicle.vehicleno LIKE '%s' AND unit.trans_statusid NOT IN (10,22) ";
            if (isset($_SESSION) && $_SESSION['groupid'] != 0) {
                $Query .= " AND vehicle.groupid =%d";
            }
            $Query .= " AND vehicle.isdeleted=0 ORDER BY devices.lastupdated DESC";
            if (isset($_SESSION) && $_SESSION['groupid'] != 0) {
                $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring), $_SESSION['groupid']);
            } else {
                $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring));
            }
        } else {
            $Query = "SELECT ecodeman.vehicleid, vehicle.vehicleno, vehicle.curspeed, vehicle.overspeed_limit, vehicle.stoppage_transit_time, vehicle.lastupdated, devices.registeredon,
            devices.ignition, vehicle.stoppage_flag, vehicle.groupid FROM ecodeman
            INNER JOIN vehicle ON vehicle.vehicleid = ecodeman.vehicleid
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN unit ON devices.uid = unit.uid
            WHERE ecodeman.customerno =%d AND vehicle.vehicleno LIKE '%s' AND ecodeman.ecodeid=%d AND unit.trans_statusid NOT IN (10,22) ";
            $Query .= " AND ecodeman.isdeleted=0 ORDER BY devices.lastupdated DESC";
            $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring), $_SESSION['e_id']);
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->stoppage_transit_time = $row['stoppage_transit_time'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                }
                $vehicle->ignition = $row['ignition'];
                $vehicle->stoppage_flag = $row["stoppage_flag"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getvehiclesforrtdwithpagination($state = null, $arrRouteDetails = array()) {
        $vehicles = Array();
        $timeIn60Minutes = time() - 60 * 60;
        $ServerIST_less1 = date('Y-m-d H:i:s', $timeIn60Minutes);
        $ServerIST_less1;
        $today = date("Y-m-d");
        /*
        Changes Made By : Pratik Raut
        Date : 17-09-2019
        Change : Added Parameter in Function
        Reason : requirement to change Delux API changes
         */
        if (!empty($arrRouteDetails)) {
            $_SESSION['customerno'] = $arrRouteDetails['customerno'];
            $_SESSION['userid'] = $arrRouteDetails['userid'];
            $_SESSION['groupid'] = $arrRouteDetails['groupid'];
        }
        /*
        Changes Ends Here
         */
        $groups = $this->getUserGroups($_SESSION['customerno'], $_SESSION['userid']);
        $list = "g.sequence as sequenceno
        , vehicle.ignition_wirecut
        , vehicle.vehicleid
        , vehicle.curspeed
        , vehicle.customerno
        , vehicle.overspeed_limit
        , vehicle.stoppage_transit_time
        , driver.drivername
        , vehicle.temp1_min
        , vehicle.temp1_max
        , vehicle.temp2_min
        , vehicle.temp2_max
        , vehicle.temp3_min
        , vehicle.temp3_max
        , vehicle.temp4_min
        , vehicle.temp4_max
        , devices.devicelat
        , devices.devicelong
        , vehicle.groupid
        , g.groupname
        , vehicle.extbatt
        , devices.ignition
        , devices.status
        , unit.is_buzzer
        , unit.is_freeze
        , vehicle.stoppage_flag
        , devices.directionchange
        , customer.use_geolocation
        , unit.acsensor
        , vehicle.vehicleno
        , unit.unitno
        , devices.tamper
        , devices.powercut
        , devices.inbatt
        , unit.analog1
        , unit.analog2
        , unit.analog3
        , unit.analog4
        , unit.get_conversion
        , unit.digitalioupdated
        , unit.digitalio
        , unit.is_door_opp
        , devices.gsmstrength
        , devices.registeredon
        , driver.driverid
        , driver.driverphone
        , vehicle.kind
        , vehicle.average
        , vehicle.fuelcapacity
        , vehicle.fuel_balance
        , unit.extra_digital
        , customer.use_extradigital
        , unit.extra_digitalioupdated
        , unit.tempsen1
        , unit.tempsen2
        , unit.tempsen3
        , unit.tempsen4
        , unit.humidity
        , unit.is_mobiliser
        , unit.mobiliser_flag
        , unit.command
        , devices.deviceid
        , devices.lastupdated
        , unit.is_ac_opp
        , unit.msgkey
        , ignitionalert.status as igstatus
        , ignitionalert.ignchgtime
        , g1.gensetno as genset1
        , g2.gensetno as genset2
        , t1.transmitterno as transmitter1
        , t2.transmitterno as transmitter2
        , unit.setcom
        , vehicle.temp1_mute
        , vehicle.temp2_mute
        , vehicle.temp3_mute
        , vehicle.temp4_mute
        , unit.extra2_digitalioupdated
        , unit.door_digitalioupdated
        , checkpoint.cname
        , vehicle.chkpoint_status
        , devices.gpsfixed
        , vehicle.checkpoint_timestamp
        , vehicle.routeDirection
        , vehicle.checkpointId
        , unit.door_digitalio
        , unit.isDoorExt
        , first_odometer
        , last_odometer
        , max_odometer
        , vehicle_common_status_master.status
        , vehicle_common_status_master.color_code
        , unit.isGensetExt
        ";
        if (isset($_SESSION['ecodeid'])) {
            $Query = "SELECT $list,v_t.vehicleType
            FROM ecodeman
            INNER JOIN vehicle ON vehicle.vehicleid = ecodeman.vehicleid
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN elixiacode ON elixiacode.id = ecodeman.ecodeid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = ecodeman.customerno AND ecodeman.customerno = $this->_Customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = ecodeman.vehicleid
            LEFT JOIN vehicle_common_status_master ON vehicle_common_status_master.id = vehicle.vehicle_status
            LEFT JOIN `group` g on g.groupid = vehicle.groupid and g.isdeleted = 0 and g.customerno = $this->_Customerno
            LEFT JOIN dailyreport ON dailyreport.vehicleid = vehicle.vehicleid and dailyreport.customerno=$this->_Customerno and daily_date='$today'
            LEFT JOIN checkpoint ON checkpoint.checkpointid = vehicle.checkpointId and checkpoint.customerno = $this->_Customerno
            LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid and g1.customerno = $this->_Customerno
            LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid and g2.customerno = $this->_Customerno
            LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid and t1.customerno = $this->_Customerno
            LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid and t2.customerno = $this->_Customerno
            LEFT JOIN vehicle_type AS v_t ON v_t.vehicleTypeId = vehicle.vehicleType
            WHERE (ecodeman.customerno =%d) AND ecodeman.ecodeid=%d AND unit.trans_statusid NOT IN (10,22) AND ecodeman.isdeleted=0
            ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, ecodeman.customerno, vehicle.sequenceno ASC, devices.lastupdated DESC ";
            $vehiclesQuery = sprintf($Query, Sanitise::Long($_SESSION['customerno']), Sanitise::Long($_SESSION['e_id']));
        } elseif (isset($_SESSION['roleid']) && $_SESSION['roleid'] == '43') {
            $Query = "SELECT $list,v_t.vehicleType
            FROM vehicle
            /*INNER JOIN tripdetails ON tripdetails.vehicleid = vehicle.vehicleid AND tripdetails.is_tripend = 0*/
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno AND vehicle.customerno = $this->_Customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
            LEFT JOIN vehicle_common_status_master ON vehicle_common_status_master.id = vehicle.vehicle_status
            LEFT JOIN `group` g on g.groupid = vehicle.groupid and g.isdeleted = 0 and g.customerno = $this->_Customerno
            LEFT JOIN dailyreport ON dailyreport.vehicleid = vehicle.vehicleid and dailyreport.customerno=$this->_Customerno and daily_date='$today'
            LEFT JOIN checkpoint ON checkpoint.checkpointid = vehicle.checkpointId and checkpoint.customerno = $this->_Customerno
            LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid and g1.customerno = $this->_Customerno
            LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid and g2.customerno = $this->_Customerno
            LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid and t1.customerno = $this->_Customerno
            LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid and t2.customerno = $this->_Customerno
            LEFT JOIN vehicle_type AS v_t ON v_t.vehicleTypeId = vehicle.vehicleType";
            if ($_SESSION['groupid'] == 0) {
                if ($groups[0] != 0) {
                    $groupid_ids = implode(',', $groups);
                    $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid IN (" . $groupid_ids . ") ";
                } else {
                    $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . "";
                }
            } else {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid = %d";
            }
            if ($_SESSION['customerno'] == 1) {
                $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
            }
            $Query .= " WHERE (vehicle.customerno =%d) AND unit.trans_statusid NOT IN (10,22) ";
            $Query .= " AND vehicle.isdeleted=0 and vehmap.isdeleted = 0 and vehicle.kind <> 'Warehouse' "
                . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.customerno, vehicle.sequenceno ASC, devices.lastupdated DESC ";
            if ($_SESSION['groupid'] == 0) {
                $vehiclesQuery = sprintf($Query, Sanitise::Long($this->_Customerno));
            } else {
                $vehiclesQuery = sprintf($Query, $_SESSION['groupid'], Sanitise::Long($this->_Customerno));
            }
            //echo $vehiclesQuery;
        } elseif (isset($_SESSION['role_modal']) && strtolower($_SESSION['role_modal']) == 'consignee') {
            $Query = "SELECT $list,v_t.vehicleType
            FROM vehicle
            INNER JOIN tripdetails ON tripdetails.vehicleid = vehicle.vehicleid AND tripdetails.is_tripend = 0 AND tripdetails.tripstatusid NOT IN(9,10) AND tripdetails.consigneeid= " . $_SESSION['consignee_id'] . " AND tripdetails.isdeleted=0 AND tripdetails.tripstatusid=4
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno AND vehicle.customerno = $this->_Customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
            LEFT JOIN vehicle_common_status_master ON vehicle_common_status_master.id = vehicle.vehicle_status
            LEFT JOIN `group` g on g.groupid = vehicle.groupid and g.isdeleted = 0 and g.customerno = $this->_Customerno
            LEFT JOIN dailyreport ON dailyreport.vehicleid = vehicle.vehicleid and dailyreport.customerno=$this->_Customerno and daily_date='$today'
            LEFT JOIN checkpoint ON checkpoint.checkpointid = vehicle.checkpointId and checkpoint.customerno = $this->_Customerno
            LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid and g1.customerno = $this->_Customerno
            LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid and g2.customerno = $this->_Customerno
            LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid and t1.customerno = $this->_Customerno
            LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid and t2.customerno = $this->_Customerno
            LEFT JOIN vehicle_type AS v_t ON v_t.vehicleTypeId = vehicle.vehicleType";
            /*  if ($_SESSION['groupid'] == 0) {
            if ($groups[0] != 0) {
            $groupid_ids = implode(',', $groups);
            //$Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid IN (" . $groupid_ids . ") ";
            } else {
            //$Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . "";
            }
            } else {
            //$Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid = %d";
             */
            if ($_SESSION['customerno'] == 1) {
                $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
            }
            $Query .= " WHERE (vehicle.customerno =%d) AND unit.trans_statusid NOT IN (10,22) ";
            $Query .= " AND vehicle.isdeleted=0 /*and vehmap.isdeleted = 0 */ and vehicle.kind <> 'Warehouse'  GROUP BY vehicle.vehicleid"
                . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.customerno, vehicle.sequenceno ASC, devices.lastupdated DESC ";
            if ($_SESSION['groupid'] == 0) {
                $vehiclesQuery = sprintf($Query, Sanitise::Long($this->_Customerno));
            } else {
                $vehiclesQuery = sprintf($Query, $_SESSION['groupid'], Sanitise::Long($this->_Customerno));
            }
        } else {
            $Query = "SELECT $list,v_t.vehicleType
            FROM vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON vehicle.uid = unit.uid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno AND vehicle.customerno = $this->_Customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
            LEFT JOIN vehicle_common_status_master ON vehicle_common_status_master.id = vehicle.vehicle_status
            LEFT JOIN `group` g on g.groupid = vehicle.groupid and g.isdeleted = 0 and g.customerno = $this->_Customerno
            LEFT JOIN dailyreport ON dailyreport.vehicleid = vehicle.vehicleid and dailyreport.customerno=$this->_Customerno and daily_date='$today'
            LEFT JOIN checkpoint ON checkpoint.checkpointid = vehicle.checkpointId and checkpoint.customerno = $this->_Customerno
            LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid and g1.customerno = $this->_Customerno
            LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid and g2.customerno = $this->_Customerno
            LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid and t1.customerno = $this->_Customerno
            LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid and t2.customerno = $this->_Customerno
            LEFT JOIN vehicle_type AS v_t ON v_t.vehicleTypeId = vehicle.vehicleType
            WHERE (vehicle.customerno =%d) AND unit.trans_statusid NOT IN (10,22)";
            if ($_SESSION['groupid'] == 0) {
                if ($groups[0] != 0) {
                    $groupid_ids = implode(',', $groups);
                    $Query .= " AND vehicle.groupid  IN (" . $groupid_ids . ") ";
                }
            } else {
                $Query .= " AND vehicle.groupid =%d";
            }
            if ($_SESSION['customerno'] == 1) {
                $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
            }
            /*
             * Changes Made By : Pratik Raut
             * Date : 27-09-2019
             * change : Added sequence of group in query earlier it was vehicle sequence
             */
            //$Query .= " AND vehicle.isdeleted=0 and vehicle.kind <> 'Warehouse'
            //          ORDER BY CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.customerno, vehicle.sequenceno ASC, devices.lastupdated DESC";
            $Query .= " AND vehicle.isdeleted=0 and vehicle.kind <> 'Warehouse'
                      ORDER BY CASE WHEN (g.sequence is null) THEN 0 ELSE g.sequence END DESC,vehicle.sequenceno,vehicle.customerno,devices.lastupdated DESC";
            /*
            changes ends here
             */
            if ($_SESSION['groupid'] == 0) {
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            } else {
                $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
            }
        }
        //echo $vehiclesQuery;die;
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->sequenceno = $row['sequenceno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->customerno = $row['customerno'];
                $vehicle->tamper = $row['tamper'];
                $vehicle->powercut = $row['powercut'];
                $vehicle->inbatt = $row['inbatt'];
                $vehicle->analog1 = $row['analog1'];
                $vehicle->analog2 = $row['analog2'];
                $vehicle->analog3 = $row['analog3'];
                $vehicle->analog4 = $row['analog4'];
                $vehicle->get_conversion = $row['get_conversion'];
                $vehicle->digitalio = $row['digitalio'];
                $vehicle->digitalioupdated = $row['digitalioupdated'];
                $vehicle->gsmstrength = $row['gsmstrength'];
                $vehicle->acsensor = $row['acsensor'];
                $vehicle->tempsen1 = $row['tempsen1'];
                $vehicle->tempsen2 = $row['tempsen2'];
                $vehicle->tempsen3 = $row['tempsen3'];
                $vehicle->tempsen4 = $row['tempsen4'];
                $vehicle->humidity = $row['humidity'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->type = $row['kind'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->stoppage_transit_time = $row['stoppage_transit_time'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->devicelat = $row['devicelat'];
                $vehicle->devicelong = $row['devicelong'];
                $vehicle->temp1_mute = $row['temp1_mute'];
                $vehicle->temp2_mute = $row['temp2_mute'];
                $vehicle->temp3_mute = $row['temp3_mute'];
                $vehicle->temp4_mute = $row['temp4_mute'];
                $vehicle->temp1_min = $row['temp1_min'];
                $vehicle->temp1_max = $row['temp1_max'];
                $vehicle->temp2_min = $row['temp2_min'];
                $vehicle->temp2_max = $row['temp2_max'];
                $vehicle->temp3_min = $row['temp3_min'];
                $vehicle->temp3_max = $row['temp3_max'];
                $vehicle->temp4_min = $row['temp4_min'];
                $vehicle->temp4_max = $row['temp4_max'];
                $vehicle->msgkey = $row['msgkey'];
                $vehicle->groupid = $row['groupid'];
                if ($row['groupid'] != 0) {
                    $vehicle->groupname = $row['groupname'];
                } else {
                    $vehicle->groupname = 'Ungrouped';
                }
                $vehicle->average = $row['average'];
                $vehicle->is_mobiliser = $row['is_mobiliser'];
                $vehicle->is_freeze = $row['is_freeze'];
                $vehicle->fuelcapacity = $row['fuelcapacity'];
                $vehicle->fuel_balance = $row['fuel_balance'];
                $vehicle->paginate = $vehiclesQuery;
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                }
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->ignition = $row['ignition'];
                $vehicle->status = $row['status'];
                $vehicle->isacopp = $row["is_ac_opp"];
                $vehicle->is_door_opp = $row["is_door_opp"];
                $vehicle->command = $row["command"];
                $vehicle->mobiliser_flag = $row["mobiliser_flag"];
                $vehicle->stoppage_flag = $row["stoppage_flag"];
                $vehicle->directionchange = $row["directionchange"];
                $vehicle->igstatus = $row["igstatus"];
                $vehicle->ignchgtime = $row["ignchgtime"];
                $vehicle->use_geolocation = $row["use_geolocation"];
                $vehicle->is_buzzer = $row["is_buzzer"];
                $vehicle->extra_digitalioupdated = $row["extra_digitalioupdated"];
                $vehicle->extra2_digitalioupdated = $row["extra2_digitalioupdated"];
                $vehicle->door_digitalioupdated = $row["door_digitalioupdated"];
                $vehicle->genset1 = $row['genset1'];
                $vehicle->genset2 = $row['genset2'];
                $vehicle->transmitter1 = $row['transmitter1'];
                $vehicle->transmitter2 = $row['transmitter2'];
                $vehicle->setcom = $row['setcom'];
                $vehicle->cname = $row['cname'];
                $vehicle->chkpoint_status = $row['chkpoint_status'];
                $vehicle->checkpoint_timestamp = $row['checkpoint_timestamp'];
                $vehicle->ignition_wirecut = $row['ignition_wirecut'];
                $vehicle->gpsfixed = $row['gpsfixed'];
                $vehicle->routeDirection = $row['routeDirection'];
                $vehicle->checkpointId = $row['checkpointId'];
                $vehicle->door_digitalio = $row['door_digitalio'];
                $vehicle->isDoorExt = $row['isDoorExt'];
                $vehicle->vehicleType = $row['vehicleType'];
                $vehicle->first_odometer = $row['first_odometer'];
                $vehicle->last_odometer = $row['last_odometer'];
                $vehicle->max_odometer = $row['max_odometer'];
                $vehicle->avg_temp_sense1 = 0;
                $vehicle->avg_temp_sense2 = 0;
                $vehicle->avg_temp_sense3 = 0;
                $vehicle->avg_temp_sense4 = 0;
                $vehicle->vehicle_status = $row['status'];
                $vehicle->vehicle_status_color_code = $row['color_code'];
                $vehicle->extra_digital = $row["extra_digital"];
                if (isset($row['isGensetExt']) && $row['isGensetExt'] == 1) {
                    if (isset($row['analog1']) && $row['analog1'] > 0) {
                        $vehicle->extra_digital = 1;
                    } else {
                        $vehicle->extra_digital = 0;
                    }
                }
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getvehiclesforrtdwithpagination_fassos() {
        $vehicles = Array();
        $timeIn60Minutes = time() - 60 * 60;
        $ServerIST_less1 = date('Y-m-d H:i:s', $timeIn60Minutes);
        $ServerIST_less1;
//echo $_SESSION['groupid'];
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
            $Query = "SELECT vehicle.sequenceno,vehicle.vehicleid, vehicle.curspeed, vehicle.overspeed_limit, vehicle.stoppage_transit_time, driver.drivername, vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max, devices.devicelat, devices.devicelong, vehicle.groupid, vehicle.extbatt, devices.ignition, devices.status, unit.is_buzzer,vehicle.stoppage_flag, devices.directionchange, customer.use_geolocation,unit.acsensor, vehicle.vehicleno, unit.unitno,devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,unit.analog3, unit.analog4, unit.digitalioupdated,unit.digitalio, unit.is_door_opp, devices.gsmstrength, devices.registeredon, driver.driverid, driver.driverphone, vehicle.kind, vehicle.average,vehicle.fuelcapacity, vehicle.fuel_balance,unit.extra_digital,customer.use_extradigital,unit.extra_digitalioupdated,unit.tempsen1,unit.tempsen2,unit.tempsen3,unit.tempsen4,unit.is_mobiliser,unit.mobiliser_flag,unit.command,devices.deviceid,devices.lastupdated, unit.is_ac_opp, unit.msgkey,ignitionalert.status as igstatus,ignitionalert.ignchgtime
            FROM vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid";
            if ($_SESSION['roleid'] == '2') {
                $Query .= " LEFT OUTER JOIN `group` ON group.groupid = vehicle.groupid
                INNER JOIN city on city.cityid = group.cityid
                INNER JOIN district on district.districtid = city.districtid
                INNER JOIN state on state.stateid = district.stateid
                WHERE vehicle.customerno =%d AND state.stateid=%d AND group.isdeleted = 0 AND unit.trans_statusid NOT IN (10,22)";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND vehicle.isdeleted=0 and vehicle.kind <> 'Warehouse' "
                    . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.sequenceno ASC, devices.lastupdated DESC ";
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '3') {
                $Query .= " LEFT OUTER JOIN `group` ON group.groupid = vehicle.groupid
                INNER JOIN city on city.cityid = group.cityid
                INNER JOIN district on district.districtid = city.districtid
                WHERE vehicle.customerno =%d AND district.districtid=%d AND group.isdeleted = 0 AND unit.trans_statusid NOT IN (10,22)";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND vehicle.isdeleted=0 and vehicle.kind <> 'Warehouse' "
                    . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.sequenceno ASC, devices.lastupdated DESC ";
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '4') {
                $Query .= " LEFT OUTER JOIN `group` ON group.groupid = vehicle.groupid
                INNER JOIN city on city.cityid = group.cityid
                WHERE vehicle.customerno =%d AND city.cityid=%d AND group.isdeleted = 0 AND unit.trans_statusid NOT IN (10,22)";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND vehicle.isdeleted=0 and vehicle.kind <> 'Warehouse'"
                    . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.sequenceno ASC, devices.lastupdated DESC ";
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '8') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                WHERE vehicle.customerno =%d AND group.isdeleted = 0 AND unit.trans_statusid NOT IN (10,22)";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND vehicle.isdeleted=0 and vehicle.kind <> 'Warehouse' "
                    . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.sequenceno ASC, devices.lastupdated DESC ";
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno);
                }
            } else {
                $Query .= " WHERE vehicle.customerno =%d AND unit.trans_statusid NOT IN (10,22)";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND vehicle.isdeleted=0 and vehicle.kind <> 'Warehouse' "
                    . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.sequenceno ASC, devices.lastupdated DESC ";
//$Query.= "";
                //$MainQuery = $Query.''.$Querylimit;
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                    $paginationQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                    $paginationQuery = sprintf($Query, $this->_Customerno);
                }
            }
        } elseif (isset($_SESSION['ecodeid'])) {
            $Query = "SELECT vehicle.sequenceno,ecodeman.vehicleid, vehicle.curspeed, vehicle.overspeed_limit, vehicle.stoppage_transit_time, driver.drivername, devices.devicelat, devices.devicelong, vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max,vehicle.groupid, vehicle.extbatt, devices.ignition, devices.status, vehicle.stoppage_flag, devices.directionchange, customer.use_geolocation,unit.acsensor,vehicle.vehicleno, unit.unitno, devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,unit.analog3, unit.analog4, unit.digitalio, unit.digitalioupdated,unit.is_buzzer,devices.gsmstrength, devices.registeredon, driver.driverid, driver.driverphone, vehicle.kind, vehicle.average, vehicle.fuelcapacity, vehicle.fuel_balance, unit.extra_digital,customer.use_extradigital,unit.extra_digitalioupdated, unit.tempsen1,unit.tempsen2,unit.tempsen3,unit.tempsen4,unit.is_mobiliser,unit.mobiliser_flag,unit.is_door_opp, unit.command,ecodeman.ecodeid, elixiacode.startdate,elixiacode.expirydate, elixiacode.ecode,ecodeman.customerno,vehicle.groupid,
            devices.deviceid,devices.lastupdated, unit.is_ac_opp, unit.msgkey,ignitionalert.status as igstatus,ignitionalert.ignchgtime
            FROM ecodeman
            INNER JOIN vehicle ON vehicle.vehicleid = ecodeman.vehicleid
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN elixiacode ON elixiacode.id = ecodeman.ecodeid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = ecodeman.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = ecodeman.vehicleid
            WHERE ecodeman.customerno =%d
            AND ecodeman.ecodeid=%d
            AND unit.trans_statusid NOT IN (10,22)
            AND ecodeman.isdeleted=0
            ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,
            vehicle.sequenceno ASC, devices.lastupdated DESC ";
            $vehiclesQuery = sprintf($Query, Sanitise::Long($_SESSION['customerno']), Sanitise::Long($_SESSION['e_id']));
        } elseif (isset($_SESSION['roleid']) && $this->VehicleUsermappingExists($_SESSION['userid']) == true) {
            $Query = "SELECT vehicle.sequenceno,vehicle.vehicleid, vehicle.curspeed, vehicle.overspeed_limit, vehicle.stoppage_transit_time, driver.drivername, vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max,devices.devicelat, devices.devicelong, vehicle.groupid, vehicle.extbatt, devices.ignition, devices.status, unit.is_buzzer,vehicle.stoppage_flag, devices.directionchange, customer.use_geolocation,unit.acsensor, vehicle.vehicleno, unit.unitno,devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,unit.analog3, unit.analog4, unit.digitalioupdated,unit.digitalio, unit.is_door_opp, devices.gsmstrength, devices.registeredon, driver.driverid, driver.driverphone, vehicle.kind, vehicle.average, vehicle.fuelcapacity, vehicle.fuel_balance,unit.extra_digital,customer.use_extradigital,unit.extra_digitalioupdated,unit.tempsen1,unit.tempsen2,unit.tempsen3,unit.tempsen4,unit.is_mobiliser,unit.mobiliser_flag,unit.command,devices.deviceid,devices.lastupdated, unit.is_ac_opp, unit.msgkey,ignitionalert.status as igstatus,ignitionalert.ignchgtime
            FROM vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid ";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid = %d";
            } else {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . "";
            }
            $Query .= " WHERE vehicle.customerno =%d
            AND unit.trans_statusid NOT IN (10,22)
            AND vehicle.isdeleted=0
            and vehmap.isdeleted = 0
            AND vehicle.kind <> 'Warehouse'
            ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.sequenceno ASC, devices.lastupdated DESC ";
            if ($_SESSION['customerno'] == 1) {
                $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
            }
            if ($_SESSION['groupid'] != 0) {
                $vehiclesQuery = sprintf($Query, $_SESSION['groupid'], $this->_Customerno);
            } else {
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            }
//die();
        } else {
            $Query = "SELECT vehicle.sequenceno,vehicle.vehicleid, vehicle.curspeed, vehicle.overspeed_limit, vehicle.stoppage_transit_time, driver.drivername, vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max,devices.devicelat, devices.devicelong, vehicle.groupid, vehicle.extbatt, devices.ignition, devices.status, unit.is_buzzer,vehicle.stoppage_flag, devices.directionchange, customer.use_geolocation,unit.acsensor, vehicle.vehicleno, unit.unitno,devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,unit.analog3, unit.analog4, unit.digitalioupdated,unit.digitalio, unit.is_door_opp, devices.gsmstrength, devices.registeredon, driver.driverid, driver.driverphone, vehicle.kind, vehicle.average, vehicle.fuelcapacity, vehicle.fuel_balance,unit.extra_digital,customer.use_extradigital,unit.extra_digitalioupdated,unit.tempsen1,unit.tempsen2,unit.tempsen3,unit.tempsen4,unit.is_mobiliser,unit.mobiliser_flag,unit.command,devices.deviceid,devices.lastupdated, unit.is_ac_opp, unit.msgkey,ignitionalert.status as igstatus,ignitionalert.ignchgtime
            FROM vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
            WHERE vehicle.customerno =%d
            AND unit.trans_statusid NOT IN (10,22) ";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " AND vehicle.groupid =%d";
            }
            if ($_SESSION['customerno'] == 1) {
                $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
            }
            $Query .= " AND vehicle.isdeleted=0 and vehicle.kind = 'Warehouse' "
                . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.sequenceno ASC, devices.lastupdated DESC ";
            if ($_SESSION['groupid'] != 0) {
                $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
            } else {
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            }
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->sequenceno = $row['sequenceno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->customerno = $this->_Customerno;
                $vehicle->tamper = $row['tamper'];
                $vehicle->powercut = $row['powercut'];
                $vehicle->inbatt = $row['inbatt'];
                $vehicle->analog1 = $row['analog1'];
                $vehicle->analog2 = $row['analog2'];
                $vehicle->analog3 = $row['analog3'];
                $vehicle->analog4 = $row['analog4'];
                $vehicle->digitalio = $row['digitalio'];
                $vehicle->digitalioupdated = $row['digitalioupdated'];
                $vehicle->gsmstrength = $row['gsmstrength'];
                $vehicle->acsensor = $row['acsensor'];
                $vehicle->tempsen1 = $row['tempsen1'];
                $vehicle->tempsen2 = $row['tempsen2'];
                $vehicle->tempsen3 = $row['tempsen3'];
                $vehicle->tempsen4 = $row['tempsen4'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->type = $row['kind'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->stoppage_transit_time = $row['stoppage_transit_time'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->devicelat = $row['devicelat'];
                $vehicle->devicelong = $row['devicelong'];
                $vehicle->temp1_min = $row['temp1_min'];
                $vehicle->temp1_max = $row['temp1_max'];
                $vehicle->temp2_min = $row['temp2_min'];
                $vehicle->temp2_max = $row['temp2_max'];
                $vehicle->msgkey = $row['msgkey'];
                $vehicle->groupid = $row['groupid'];
                $vehicle->average = $row['average'];
                $vehicle->is_mobiliser = $row['is_mobiliser'];
                $vehicle->fuelcapacity = $row['fuelcapacity'];
                $vehicle->fuel_balance = $row['fuel_balance'];
                $vehicle->paginate = $vehiclesQuery;
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                }
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->ignition = $row['ignition'];
                $vehicle->status = $row['status'];
                $vehicle->isacopp = $row["is_ac_opp"];
                $vehicle->is_door_opp = $row["is_door_opp"];
                $vehicle->command = $row["command"];
                $vehicle->mobiliser_flag = $row["mobiliser_flag"];
                $vehicle->stoppage_flag = $row["stoppage_flag"];
                $vehicle->directionchange = $row["directionchange"];
                $vehicle->igstatus = $row["igstatus"];
                $vehicle->ignchgtime = $row["ignchgtime"];
                $vehicle->use_geolocation = $row["use_geolocation"];
                $vehicle->is_buzzer = $row["is_buzzer"];
                $vehicle->extra_digital = $row["extra_digital"];
                $vehicle->extra_digitalioupdated = $row["extra_digitalioupdated"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getwarehouseforrtd() {
        $vehicles = Array();
        $timeIn60Minutes = time() - 60 * 60;
        $ServerIST_less1 = date('Y-m-d H:i:s', $timeIn60Minutes);
        $ServerIST_less1;
        $today = date('Y-m-d');
        $groups = $this->getUserGroups($_SESSION['customerno'], $_SESSION['userid']);
        $list = "vehicle.sequenceno
        ,vehicle.vehicleid
        , vehicle.curspeed
        , vehicle.customerno
        , vehicle.overspeed_limit
        , vehicle.stoppage_transit_time
        , driver.drivername
        , vehicle.temp1_min
        , vehicle.temp1_max
        , vehicle.temp1_allowance
        , vehicle.temp2_min
        , vehicle.temp2_max
        , vehicle.temp2_allowance
        , vehicle.temp3_min
        , vehicle.temp3_max
        , vehicle.temp3_allowance
        , vehicle.temp4_min
        , vehicle.temp4_max
        , vehicle.temp4_allowance
        ,devices.devicelat
        , devices.devicelong
        , vehicle.groupid
        , vehicle.extbatt
        , devices.ignition
        , devices.status
        , unit.is_buzzer
        ,unit.is_freeze
        ,vehicle.stoppage_flag
        , devices.directionchange
        , customer.use_geolocation
        ,unit.acsensor
        , vehicle.vehicleno
        , unit.unitno
        ,devices.tamper
        , devices.powercut
        , devices.inbatt
        , unit.analog1
        , unit.analog2
        ,unit.analog3
        , unit.analog4
        ,unit.get_conversion
        , unit.digitalioupdated
        ,unit.digitalio
        , unit.is_door_opp
        , devices.gsmstrength
        , devices.registeredon
        , driver.driverid
        , driver.driverphone
        , vehicle.kind
        , vehicle.average
        ,vehicle.fuelcapacity
        , vehicle.fuel_balance
        ,unit.extra_digital
        ,customer.use_extradigital
        ,unit.extra_digitalioupdated
        ,unit.tempsen1
        ,unit.tempsen2
        ,unit.tempsen3
        ,unit.tempsen4
        ,unit.humidity
        ,unit.is_mobiliser
        ,unit.mobiliser_flag
        ,unit.command
        ,unit.n1
        ,unit.n2
        ,unit.n3
        ,unit.n4
        ,unit.isDoorExt
        ,devices.deviceid
        ,devices.lastupdated
        , unit.is_ac_opp
        , unit.msgkey
        ,ignitionalert.status as igstatus
        ,ignitionalert.ignchgtime
        ,vehicle.temp1_mute
        ,vehicle.temp2_mute
        ,vehicle.temp3_mute
        ,vehicle.temp4_mute
        ,unit.extra2_digitalioupdated
        ,unit.door_digitalioupdated
        , trcm.vehicleid AS trcmvehicleid
        , trcm.temp1_range1_start
        , trcm.temp1_range1_end
        , trcm.temp1_range1_color
        , trcm.temp1_range2_start
        , trcm.temp1_range2_end
        , trcm.temp1_range2_color
        , trcm.temp1_range3_start
        , trcm.temp1_range3_end
        , trcm.temp1_range3_color
        , trcm.temp1_range4_start
        , trcm.temp1_range4_end
        , trcm.temp1_range4_color
        , trcm.temp2_range1_start
        , trcm.temp2_range1_end
        , trcm.temp2_range1_color
        , trcm.temp2_range2_start
        , trcm.temp2_range2_end
        , trcm.temp2_range2_color
        , trcm.temp2_range3_start
        , trcm.temp2_range3_end
        , trcm.temp2_range3_color
        , trcm.temp2_range4_start
        , trcm.temp2_range4_end
        , trcm.temp2_range4_color
        , trcm.temp3_range1_start
        , trcm.temp3_range1_end
        , trcm.temp3_range1_color
        , trcm.temp3_range2_start
        , trcm.temp3_range2_end
        , trcm.temp3_range2_color
        , trcm.temp3_range3_start
        , trcm.temp3_range3_end
        , trcm.temp3_range3_color
        , trcm.temp3_range4_start
        , trcm.temp3_range4_end
        , trcm.temp3_range4_color
        , trcm.temp4_range1_start
        , trcm.temp4_range1_end
        , trcm.temp4_range1_color
        , trcm.temp4_range2_start
        , trcm.temp4_range2_end
        , trcm.temp4_range2_color
        , trcm.temp4_range3_start
        , trcm.temp4_range3_end
        , trcm.temp4_range3_color
        , trcm.temp4_range4_start
        , trcm.temp4_range4_end
        , trcm.temp4_range4_color
        ,dailyreport.avg_temp_sens1
        ,dailyreport.avg_temp_sens2
        ,dailyreport.avg_temp_sens3
        ,dailyreport.avg_temp_sens4
        ";
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
            $Query = "SELECT $list
            FROM vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
            LEFT JOIN vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child = $this->_Customerno
            LEFT OUTER JOIN tempRangeColourMapping as trcm on trcm.vehicleid  = vehicle.vehicleid AND trcm.isdeleted = 0
            LEFT JOIN dailyreport ON dailyreport.vehicleid = vehicle.vehicleid and dailyreport.customerno=$this->_Customerno and dailyreport.daily_date='$today'";
            if ($_SESSION['roleid'] == '2') {
                $Query .= " LEFT OUTER JOIN `group` ON group.groupid = vehicle.groupid
                INNER JOIN city on city.cityid = group.cityid
                INNER JOIN district on district.districtid = city.districtid
                INNER JOIN state on state.stateid = district.stateid
                WHERE   (vehicle.customerno = %d || vehicle.customerno = vr.parent)
                AND     state.stateid = %d
                AND     group.isdeleted = 0 AND unit.trans_statusid NOT IN (10,22)";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid = %d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND vehicle.isdeleted=0 and vehicle.kind = 'Warehouse' "
                    . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.customerno,vehicle.sequenceno ASC, devices.lastupdated DESC ";
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '3') {
                $Query .= " LEFT OUTER JOIN `group` ON group.groupid = vehicle.groupid
                INNER JOIN city on city.cityid = group.cityid
                INNER JOIN district on district.districtid = city.districtid
                WHERE (vehicle.customerno =%d || vehicle.customerno = vr.parent) AND district.districtid=%d AND group.isdeleted = 0 AND unit.trans_statusid NOT IN (10,22) ";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND vehicle.isdeleted=0 and vehicle.kind = 'Warehouse' "
                    . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.customerno,vehicle.sequenceno ASC, devices.lastupdated DESC ";
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '4') {
                $Query .= " LEFT OUTER JOIN `group` ON group.groupid = vehicle.groupid
                INNER JOIN city on city.cityid = group.cityid
                WHERE (vehicle.customerno =%d || vehicle.customerno = vr.parent) AND city.cityid=%d AND group.isdeleted = 0 AND unit.trans_statusid NOT IN (10,22) ";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND vehicle.isdeleted=0 and vehicle.kind = 'Warehouse' "
                    . "ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.customerno,vehicle.sequenceno ASC, devices.lastupdated DESC ";
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '8') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                WHERE (vehicle.customerno =%d || vehicle.customerno = vr.parent) AND group.isdeleted = 0 AND unit.trans_statusid NOT IN (10,22) ";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND vehicle.isdeleted=0 and vehicle.kind = 'Warehouse' "
                    . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.customerno,vehicle.sequenceno ASC, devices.lastupdated DESC ";
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno);
                }
            } else {
                $Query .= " WHERE (vehicle.customerno =%d || vehicle.customerno = vr.parent) AND unit.trans_statusid NOT IN (10,22) ";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND vehicle.isdeleted=0 and vehicle.kind = 'Warehouse' "
                    . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.customerno,vehicle.sequenceno ASC, devices.lastupdated DESC ";
                //$Query.= "";
                //$MainQuery = $Query.''.$Querylimit;
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                    $paginationQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                    $paginationQuery = sprintf($Query, $this->_Customerno);
                }
            }
        } elseif (isset($_SESSION['ecodeid'])) {
            $Query = "SELECT vehicle.sequenceno,ecodeman.vehicleid, vehicle.curspeed, vehicle.overspeed_limit, vehicle.stoppage_transit_time, driver.drivername, devices.devicelat, devices.devicelong, vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max,
            , vehicle.temp3_min, vehicle.temp3_max, vehicle.temp4_min, vehicle.temp4_max, vehicle.groupid, vehicle.extbatt, devices.ignition, devices.status, vehicle.stoppage_flag, devices.directionchange, customer.use_geolocation,unit.acsensor,
            vehicle.vehicleno, unit.unitno, devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,unit.analog3, unit.analog4, unit.digitalio, unit.digitalioupdated,unit.is_buzzer,unit.is_freeze,
            devices.gsmstrength, devices.registeredon, driver.driverid, driver.driverphone, vehicle.kind, vehicle.average, vehicle.fuelcapacity, vehicle.fuel_balance, unit.extra_digital,customer.use_extradigital,unit.extra_digitalioupdated, unit.tempsen1,
            unit.tempsen2,unit.tempsen3,unit.tempsen4,unit.humidity,unit.is_mobiliser,unit.mobiliser_flag,unit.is_door_opp, unit.command,ecodeman.ecodeid, elixiacode.startdate,elixiacode.expirydate, elixiacode.ecode,ecodeman.customerno,vehicle.groupid,
            devices.deviceid,devices.lastupdated, unit.is_ac_opp, unit.msgkey,ignitionalert.status as igstatus,ignitionalert.ignchgtime,unit.n1,unit.n2,unit.n3,unit.n4,
            vehicle.temp1_mute,vehicle.temp2_mute,vehicle.temp3_mute,vehicle.temp4_mute,unit.extra2_digitalioupdated,unit.door_digitalioupdated
            FROM ecodeman
            INNER JOIN vehicle ON vehicle.vehicleid = ecodeman.vehicleid
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN elixiacode ON elixiacode.id = ecodeman.ecodeid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = ecodeman.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = ecodeman.vehicleid
            LEFT JOIN vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child = $this->_Customerno
            LEFT OUTER JOIN tempRangeColourMapping as trcm on trcm.vehicleid  = vehicle.vehicleid AND trcm.isdeleted = 0
             LEFT JOIN dailyreport ON dailyreport.vehicleid = vehicle.vehicleid and dailyreport.customerno=$this->_Customerno and dailyreport.daily_date='$today'
            WHERE   (ecodeman.customerno = %d || ecodeman.customerno = vr.parent)
            AND     ecodeman.ecodeid = %d
            AND     unit.trans_statusid NOT IN (10,22)
            AND     ecodeman.isdeleted = 0
            ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.sequenceno ASC, devices.lastupdated DESC ";
            $vehiclesQuery = sprintf($Query, Sanitise::Long($_SESSION['customerno']), Sanitise::Long($_SESSION['e_id']));
        } elseif (isset($_SESSION['roleid']) && $this->VehicleUsermappingExists($_SESSION['userid']) == true) {
            $Query = "SELECT $list
            FROM vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
            LEFT JOIN vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child = $this->_Customerno
            LEFT OUTER JOIN tempRangeColourMapping as trcm on trcm.vehicleid  = vehicle.vehicleid AND trcm.isdeleted = 0
             LEFT JOIN dailyreport ON dailyreport.vehicleid = vehicle.vehicleid and dailyreport.customerno=$this->_Customerno and dailyreport.daily_date='$today'";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid = %d";
            } else {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . "";
            }
            if ($_SESSION['customerno'] == 1) {
                $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
            }
            $Query .= " WHERE   (vehicle.customerno = %d || vehicle.customerno = vr.parent)
                        AND     unit.trans_statusid NOT IN (10,22)
                        AND     vehicle.isdeleted = 0
                        AND     vehmap.isdeleted = 0
                        AND     vehicle.kind = 'Warehouse'
                        ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.customerno,vehicle.sequenceno ASC, devices.lastupdated DESC ";
            if ($_SESSION['groupid'] != 0) {
                $vehiclesQuery = sprintf($Query, $_SESSION['groupid'], $this->_Customerno);
            } else {
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            }
        } else {
            $Query = "SELECT $list
            FROM vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
            LEFT JOIN vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child = $this->_Customerno
            LEFT OUTER JOIN tempRangeColourMapping as trcm on trcm.vehicleid  = vehicle.vehicleid AND trcm.isdeleted = 0
            LEFT JOIN dailyreport ON dailyreport.vehicleid = vehicle.vehicleid and dailyreport.customerno=$this->_Customerno and dailyreport.daily_date='$today'
            WHERE (vehicle.customerno =%d || vehicle.customerno = vr.parent) AND unit.trans_statusid NOT IN (10,22) ";
            if ($_SESSION['groupid'] == 0) {
                if ($groups[0] != 0) {
                    $groupid_ids = implode(',', $groups);
                    $Query .= " AND vehicle.groupid  IN (" . $groupid_ids . ") ";
                }
            } else {
                $Query .= " AND vehicle.groupid =%d";
            }
            if ($_SESSION['customerno'] == 1) {
                $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
            }
            $Query .= " AND vehicle.isdeleted = 0
                        AND vehicle.kind = 'Warehouse'
                        ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.customerno,vehicle.sequenceno ASC, devices.lastupdated DESC ";
            if ($_SESSION['groupid'] != 0) {
                $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
            } else {
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            }
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->sequenceno = $row['sequenceno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->tamper = $row['tamper'];
                $vehicle->customerno = $row['customerno'];
                $vehicle->powercut = $row['powercut'];
                $vehicle->inbatt = $row['inbatt'];
                $vehicle->analog1 = $row['analog1'];
                $vehicle->analog2 = $row['analog2'];
                $vehicle->analog3 = $row['analog3'];
                $vehicle->analog4 = $row['analog4'];
                $vehicle->get_conversion = $row['get_conversion'];
                $vehicle->digitalio = $row['digitalio'];
                $vehicle->digitalioupdated = $row['digitalioupdated'];
                $vehicle->gsmstrength = $row['gsmstrength'];
                $vehicle->acsensor = $row['acsensor'];
                $vehicle->tempsen1 = $row['tempsen1'];
                $vehicle->tempsen2 = $row['tempsen2'];
                $vehicle->tempsen3 = $row['tempsen3'];
                $vehicle->tempsen4 = $row['tempsen4'];
                $vehicle->humidity = $row['humidity'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->type = $row['kind'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->stoppage_transit_time = $row['stoppage_transit_time'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->devicelat = $row['devicelat'];
                $vehicle->devicelong = $row['devicelong'];
                $vehicle->temp1_mute = $row['temp1_mute'];
                $vehicle->temp2_mute = $row['temp2_mute'];
                $vehicle->temp3_mute = $row['temp3_mute'];
                $vehicle->temp4_mute = $row['temp4_mute'];
                $vehicle->temp1_min = $row['temp1_min'];
                $vehicle->temp1_max = $row['temp1_max'];
                $vehicle->temp1_allowance = $row['temp1_allowance'];
                $vehicle->temp2_min = $row['temp2_min'];
                $vehicle->temp2_max = $row['temp2_max'];
                $vehicle->temp2_allowance = $row['temp2_allowance'];
                $vehicle->temp3_min = $row['temp3_min'];
                $vehicle->temp3_max = $row['temp3_max'];
                $vehicle->temp3_allowance = $row['temp3_allowance'];
                $vehicle->temp4_min = $row['temp4_min'];
                $vehicle->temp4_max = $row['temp4_max'];
                $vehicle->temp4_allowance = $row['temp4_allowance'];
                $vehicle->n1 = $row['n1'];
                $vehicle->n2 = $row['n2'];
                $vehicle->n3 = $row['n3'];
                $vehicle->n4 = $row['n4'];
                $vehicle->msgkey = $row['msgkey'];
                $vehicle->groupid = $row['groupid'];
                $vehicle->average = $row['average'];
                $vehicle->is_mobiliser = $row['is_mobiliser'];
                $vehicle->is_freeze = $row['is_freeze'];
                $vehicle->fuelcapacity = $row['fuelcapacity'];
                $vehicle->fuel_balance = $row['fuel_balance'];
                $vehicle->paginate = $vehiclesQuery;
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                }
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->isDoorExt = $row['isDoorExt'];
                $vehicle->ignition = $row['ignition'];
                $vehicle->status = $row['status'];
                $vehicle->isacopp = $row["is_ac_opp"];
                $vehicle->is_door_opp = $row["is_door_opp"];
                $vehicle->command = $row["command"];
                $vehicle->mobiliser_flag = $row["mobiliser_flag"];
                $vehicle->stoppage_flag = $row["stoppage_flag"];
                $vehicle->directionchange = $row["directionchange"];
                $vehicle->igstatus = $row["igstatus"];
                $vehicle->ignchgtime = $row["ignchgtime"];
                $vehicle->use_geolocation = $row["use_geolocation"];
                $vehicle->is_buzzer = $row["is_buzzer"];
                $vehicle->extra_digital = $row["extra_digital"];
                $vehicle->extra_digitalioupdated = $row["extra_digitalioupdated"];
                $vehicle->extra2_digitalioupdated = $row["extra2_digitalioupdated"];
                $vehicle->door_digitalioupdated = $row["door_digitalioupdated"];
                if (isset($row["trcmvehicleid"])) {
                    $vehicle->trcmvehicleid = $row["trcmvehicleid"];
                    $vehicle->temp1_range1_start = $row["temp1_range1_start"];
                    $vehicle->temp1_range1_end = $row["temp1_range1_end"];
                    $vehicle->temp1_range1_color = $row["temp1_range1_color"];
                    $vehicle->temp1_range2_start = $row["temp1_range2_start"];
                    $vehicle->temp1_range2_end = $row["temp1_range2_end"];
                    $vehicle->temp1_range2_color = $row["temp1_range2_color"];
                    $vehicle->temp1_range3_start = $row["temp1_range3_start"];
                    $vehicle->temp1_range3_end = $row["temp1_range3_end"];
                    $vehicle->temp1_range3_color = $row["temp1_range3_color"];
                    $vehicle->temp1_range4_start = $row["temp1_range4_start"];
                    $vehicle->temp1_range4_end = $row["temp1_range4_end"];
                    $vehicle->temp1_range4_color = $row["temp1_range4_color"];
                    $vehicle->temp2_range1_start = $row["temp2_range1_start"];
                    $vehicle->temp2_range1_end = $row["temp2_range1_end"];
                    $vehicle->temp2_range1_color = $row["temp2_range1_color"];
                    $vehicle->temp2_range2_start = $row["temp2_range2_start"];
                    $vehicle->temp2_range2_end = $row["temp2_range2_end"];
                    $vehicle->temp2_range2_color = $row["temp2_range2_color"];
                    $vehicle->temp2_range3_start = $row["temp2_range3_start"];
                    $vehicle->temp2_range3_end = $row["temp2_range3_end"];
                    $vehicle->temp2_range3_color = $row["temp2_range3_color"];
                    $vehicle->temp2_range4_start = $row["temp2_range4_start"];
                    $vehicle->temp2_range4_end = $row["temp2_range4_end"];
                    $vehicle->temp2_range4_color = $row["temp2_range4_color"];
                    $vehicle->temp3_range1_start = $row["temp3_range1_start"];
                    $vehicle->temp3_range1_end = $row["temp3_range1_end"];
                    $vehicle->temp3_range1_color = $row["temp3_range1_color"];
                    $vehicle->temp3_range2_start = $row["temp3_range2_start"];
                    $vehicle->temp3_range2_end = $row["temp3_range2_end"];
                    $vehicle->temp3_range2_color = $row["temp3_range2_color"];
                    $vehicle->temp3_range3_start = $row["temp3_range3_start"];
                    $vehicle->temp3_range3_end = $row["temp3_range3_end"];
                    $vehicle->temp3_range3_color = $row["temp3_range3_color"];
                    $vehicle->temp3_range4_start = $row["temp3_range4_start"];
                    $vehicle->temp3_range4_end = $row["temp3_range4_end"];
                    $vehicle->temp3_range4_color = $row["temp3_range4_color"];
                    $vehicle->temp4_range1_start = $row["temp4_range1_start"];
                    $vehicle->temp4_range1_end = $row["temp4_range1_end"];
                    $vehicle->temp4_range1_color = $row["temp4_range1_color"];
                    $vehicle->temp4_range2_start = $row["temp4_range2_start"];
                    $vehicle->temp4_range2_end = $row["temp4_range2_end"];
                    $vehicle->temp4_range2_color = $row["temp4_range2_color"];
                    $vehicle->temp4_range3_start = $row["temp4_range3_start"];
                    $vehicle->temp4_range3_end = $row["temp4_range3_end"];
                    $vehicle->temp4_range3_color = $row["temp4_range3_color"];
                    $vehicle->temp4_range4_start = $row["temp4_range4_start"];
                    $vehicle->temp4_range4_end = $row["temp4_range4_end"];
                    $vehicle->temp4_range4_color = $row["temp4_range4_color"];
                }
                $vehicle->avg_temp_sens1 = isset($row["avg_temp_sens1"]) ? $row["avg_temp_sens1"] : 0;
                $vehicle->avg_temp_sens2 = isset($row["avg_temp_sens1"]) ? $row["avg_temp_sens1"] : 0;
                $vehicle->avg_temp_sens3 = isset($row["avg_temp_sens1"]) ? $row["avg_temp_sens1"] : 0;
                $vehicle->avg_temp_sens4 = isset($row["avg_temp_sens1"]) ? $row["avg_temp_sens1"] : 0;
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getwarehouseforrtd_fassos() {
        $vehicles = Array();
        $timeIn60Minutes = time() - 60 * 60;
        $ServerIST_less1 = date('Y-m-d H:i:s', $timeIn60Minutes);
        $ServerIST_less1;
        $groups = $this->getUserGroups($_SESSION['customerno'], $_SESSION['userid']);
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
            $Query = "SELECT vehicle.sequenceno,vehicle.vehicleid, vehicle.curspeed, vehicle.overspeed_limit, vehicle.stoppage_transit_time, driver.drivername, vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max,vehicle.temp3_min, vehicle.temp3_max,vehicle.temp4_min, vehicle.temp4_max,
            devices.devicelat, devices.devicelong, vehicle.groupid, vehicle.extbatt, devices.ignition, devices.status, unit.is_buzzer,
            vehicle.stoppage_flag, devices.directionchange, customer.use_geolocation,unit.acsensor, vehicle.vehicleno, unit.unitno,unit.get_conversion
            devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,unit.analog3, unit.analog4, unit.digitalioupdated,
            unit.digitalio, unit.is_door_opp, devices.gsmstrength, devices.registeredon, driver.driverid, driver.driverphone, vehicle.kind, vehicle.average,vehicle.fuelcapacity, vehicle.fuel_balance,unit.extra_digital,customer.use_extradigital,unit.extra_digitalioupdated, unit.n1,unit.n2,unit.n3,unit.n4,
            unit.tempsen1,unit.tempsen2,unit.tempsen3,unit.tempsen4,unit.is_mobiliser,unit.mobiliser_flag,unit.command,devices.deviceid,devices.lastupdated, unit.is_ac_opp, unit.msgkey,ignitionalert.status as igstatus,ignitionalert.ignchgtime,unit.n1,unit.n2,unit.n3,unit.n4,
            ,vehicle.temp1_mute,vehicle.temp2_mute,vehicle.temp3_mute,vehicle.temp4_mute
            FROM vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid";
            if ($_SESSION['roleid'] == '2') {
                $Query .= " LEFT OUTER JOIN `group` ON group.groupid = vehicle.groupid
                INNER JOIN city on city.cityid = group.cityid
                INNER JOIN district on district.districtid = city.districtid
                INNER JOIN state on state.stateid = district.stateid
                WHERE vehicle.customerno =%d AND state.stateid=%d AND group.isdeleted = 0 AND unit.trans_statusid NOT IN (10,22)";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND vehicle.isdeleted=0 and vehicle.kind = 'Warehouse' "
                    . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.sequenceno ASC, devices.lastupdated DESC  ";
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '3') {
                $Query .= " LEFT OUTER JOIN `group` ON group.groupid = vehicle.groupid
                INNER JOIN city on city.cityid = group.cityid
                INNER JOIN district on district.districtid = city.districtid
                WHERE vehicle.customerno =%d AND district.districtid=%d AND group.isdeleted = 0 AND unit.trans_statusid NOT IN (10,22)";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND vehicle.isdeleted=0 and vehicle.kind = 'Warehouse' "
                    . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.sequenceno ASC, devices.lastupdated DESC ";
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '4') {
                $Query .= " LEFT OUTER JOIN `group` ON group.groupid = vehicle.groupid
                INNER JOIN city on city.cityid = group.cityid
                WHERE vehicle.customerno =%d AND city.cityid=%d AND group.isdeleted = 0 AND unit.trans_statusid NOT IN (10,22)";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND vehicle.isdeleted=0 and vehicle.kind = 'Warehouse' "
                    . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.sequenceno ASC, devices.lastupdated DESC ";
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '8') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid
                WHERE vehicle.customerno =%d AND group.isdeleted = 0 AND unit.trans_statusid NOT IN (10,22)";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND vehicle.isdeleted=0 and vehicle.kind = 'Warehouse' "
                    . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.sequenceno ASC, devices.lastupdated DESC";
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno);
                }
            } else {
                $Query .= " WHERE vehicle.customerno =%d AND unit.trans_statusid NOT IN (10,22)";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND vehicle.groupid =%d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND vehicle.isdeleted=0 and vehicle.kind = 'Warehouse' "
                    . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.sequenceno ASC, devices.lastupdated DESC";
//$Query.= "";
                //$MainQuery = $Query.''.$Querylimit;
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                    $paginationQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                    $paginationQuery = sprintf($Query, $this->_Customerno);
                }
            }
        } elseif (isset($_SESSION['ecodeid'])) {
            $Query = "SELECT vehicle.sequenceno,ecodeman.vehicleid, vehicle.curspeed, vehicle.overspeed_limit, vehicle.stoppage_transit_time, driver.drivername, devices.devicelat, devices.devicelong, vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max,vehicle.temp3_min, vehicle.temp3_max,vehicle.temp4_min, vehicle.temp4_max,
            vehicle.groupid, vehicle.extbatt, devices.ignition, devices.status, vehicle.stoppage_flag, devices.directionchange, customer.use_geolocation,unit.acsensor,
            vehicle.vehicleno, unit.unitno, devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,unit.analog3, unit.analog4, unit.digitalio, unit.digitalioupdated,unit.is_buzzer,
            devices.gsmstrength, devices.registeredon, driver.driverid, driver.driverphone, vehicle.kind, vehicle.average, vehicle.fuelcapacity, vehicle.fuel_balance, unit.extra_digital,customer.use_extradigital,unit.extra_digitalioupdated, unit.tempsen1, unit.n1,unit.n2,unit.n3,unit.n4,
            unit.tempsen2,unit.tempsen3,unit.tempsen4,unit.is_mobiliser,unit.mobiliser_flag,unit.is_door_opp, unit.command,ecodeman.ecodeid, elixiacode.startdate,elixiacode.expirydate, elixiacode.ecode,ecodeman.customerno,vehicle.groupid,
            devices.deviceid,devices.lastupdated, unit.is_ac_opp, unit.msgkey,ignitionalert.status as igstatus,ignitionalert.ignchgtime,unit.n1,unit.n2,unit.n3,unit.n4,
            ,vehicle.temp1_mute,vehicle.temp2_mute,vehicle.temp3_mute,vehicle.temp4_mute
            FROM ecodeman
            INNER JOIN vehicle ON vehicle.vehicleid = ecodeman.vehicleid
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN elixiacode ON elixiacode.id = ecodeman.ecodeid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = ecodeman.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = ecodeman.vehicleid
            WHERE ecodeman.customerno =%d AND ecodeman.ecodeid=%d AND unit.trans_statusid NOT IN (10,22) AND ecodeman.isdeleted=0 ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.sequenceno ASC, devices.lastupdated DESC";
            $vehiclesQuery = sprintf($Query, Sanitise::Long($_SESSION['customerno']), Sanitise::Long($_SESSION['e_id']));
        } elseif (isset($_SESSION['roleid']) && $this->VehicleUsermappingExists($_SESSION['userid']) == true) {
            $Query = "SELECT vehicle.sequenceno,vehicle.vehicleid, vehicle.curspeed, vehicle.overspeed_limit, vehicle.stoppage_transit_time, driver.drivername, vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max,vehicle.temp3_min, vehicle.temp3_max,vehicle.temp4_min, vehicle.temp4_max,devices.devicelat, devices.devicelong, vehicle.groupid, vehicle.extbatt, devices.ignition, devices.status, unit.is_buzzer,vehicle.stoppage_flag, devices.directionchange, customer.use_geolocation,unit.acsensor, vehicle.vehicleno, unit.unitno,devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,unit.analog3, unit.analog4, unit.digitalioupdated,unit.digitalio, unit.is_door_opp, devices.gsmstrength, devices.registeredon, driver.driverid, driver.driverphone, vehicle.kind, vehicle.average, vehicle.fuelcapacity, vehicle.fuel_balance,unit.extra_digital,customer.use_extradigital,unit.extra_digitalioupdated,unit.tempsen1,unit.tempsen2,unit.tempsen3,unit.tempsen4,unit.is_mobiliser,unit.mobiliser_flag,unit.command,devices.deviceid,devices.lastupdated, unit.is_ac_opp, unit.msgkey,ignitionalert.status as igstatus,ignitionalert.ignchgtime,unit.n1,unit.n2,unit.n3,unit.n4
            ,vehicle.temp1_mute,vehicle.temp2_mute,vehicle.temp3_mute,vehicle.temp4_mute
            FROM vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid ";
            if ($_SESSION['groupid'] != 0) {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid = %d";
            } else {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . "";
            }
            $Query .= " WHERE vehicle.customerno =%d
            AND unit.trans_statusid NOT IN (10,22)
            AND vehicle.isdeleted=0
            and vehmap.isdeleted = 0
            AND vehicle.kind = 'Warehouse'
            ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.sequenceno ASC, devices.lastupdated DESC ";
            if ($_SESSION['customerno'] == 1) {
                $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
            }
            if ($_SESSION['groupid'] != 0) {
                $vehiclesQuery = sprintf($Query, $_SESSION['groupid'], $this->_Customerno);
            } else {
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            }
        } else {
            $Query = "SELECT vehicle.sequenceno,vehicle.vehicleid, vehicle.curspeed, vehicle.overspeed_limit, vehicle.stoppage_transit_time, driver.drivername, vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max,vehicle.temp3_min, vehicle.temp3_max,vehicle.temp4_min, vehicle.temp4_max,
            devices.devicelat, devices.devicelong, vehicle.groupid, vehicle.extbatt, devices.ignition, devices.status, unit.is_buzzer,
            vehicle.stoppage_flag, devices.directionchange, customer.use_geolocation,unit.acsensor, vehicle.vehicleno, unit.unitno,
            devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,unit.analog3, unit.analog4, unit.digitalioupdated,
            unit.digitalio, unit.is_door_opp, devices.gsmstrength, devices.registeredon, driver.driverid, driver.driverphone, vehicle.kind, vehicle.average, vehicle.fuelcapacity, vehicle.fuel_balance,unit.extra_digital,customer.use_extradigital,unit.extra_digitalioupdated,unit.n1,unit.n2,unit.n3,unit.n4,
            unit.tempsen1,unit.tempsen2,unit.tempsen3,unit.tempsen4,unit.is_mobiliser,unit.mobiliser_flag,unit.command,devices.deviceid,devices.lastupdated, unit.is_ac_opp, unit.msgkey,ignitionalert.status as igstatus,ignitionalert.ignchgtime,unit.n1,unit.n2,unit.n3,unit.n4
            ,vehicle.temp1_mute,vehicle.temp2_mute,vehicle.temp3_mute,vehicle.temp4_mute
            FROM vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
            WHERE vehicle.customerno =%d AND unit.trans_statusid NOT IN (10,22) ";
            if ($_SESSION['groupid'] == 0) {
                if ($groups[0] != 0) {
                    $groupid_ids = implode(',', $groups);
                    $Query .= " AND vehicle.groupid  IN (" . $groupid_ids . ") ";
                }
            } else {
                $Query .= " AND vehicle.groupid =%d";
            }
            if ($_SESSION['customerno'] == 1) {
                $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
            }
            $Query .= " AND vehicle.isdeleted=0 and vehicle.kind = 'Warehouse' ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.sequenceno ASC, devices.lastupdated DESC";
            if ($_SESSION['groupid'] != 0) {
                $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
            } else {
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            }
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->sequenceno = $row['sequenceno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->tamper = $row['tamper'];
                $vehicle->customerno = $this->_Customerno;
                $vehicle->powercut = $row['powercut'];
                $vehicle->inbatt = $row['inbatt'];
                $vehicle->analog1 = $row['analog1'];
                $vehicle->analog2 = $row['analog2'];
                $vehicle->analog3 = $row['analog3'];
                $vehicle->analog4 = $row['analog4'];
                $vehicle->get_conversion = $row['get_conversion'];
                $vehicle->digitalio = $row['digitalio'];
                $vehicle->digitalioupdated = $row['digitalioupdated'];
                $vehicle->gsmstrength = $row['gsmstrength'];
                $vehicle->acsensor = $row['acsensor'];
                $vehicle->tempsen1 = $row['tempsen1'];
                $vehicle->tempsen2 = $row['tempsen2'];
                $vehicle->tempsen3 = $row['tempsen3'];
                $vehicle->tempsen4 = $row['tempsen4'];
                $vehicle->n1 = $row['n1'];
                $vehicle->n2 = $row['n2'];
                $vehicle->n3 = $row['n3'];
                $vehicle->n4 = $row['n4'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->type = $row['kind'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->stoppage_transit_time = $row['stoppage_transit_time'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->devicelat = $row['devicelat'];
                $vehicle->devicelong = $row['devicelong'];
                $vehicle->temp1_mute = $row['temp1_mute'];
                $vehicle->temp2_mute = $row['temp2_mute'];
                $vehicle->temp3_mute = $row['temp3_mute'];
                $vehicle->temp4_mute = $row['temp4_mute'];
                $vehicle->temp1_min = $row['temp1_min'];
                $vehicle->temp1_max = $row['temp1_max'];
                $vehicle->temp2_min = $row['temp2_min'];
                $vehicle->temp2_max = $row['temp2_max'];
                $vehicle->temp3_min = $row['temp3_min'];
                $vehicle->temp3_max = $row['temp3_max'];
                $vehicle->temp4_min = $row['temp4_min'];
                $vehicle->temp4_max = $row['temp4_max'];
                $vehicle->msgkey = $row['msgkey'];
                $vehicle->groupid = $row['groupid'];
                $vehicle->average = $row['average'];
                $vehicle->is_mobiliser = $row['is_mobiliser'];
                $vehicle->fuelcapacity = $row['fuelcapacity'];
                $vehicle->fuel_balance = $row['fuel_balance'];
                $vehicle->paginate = $vehiclesQuery;
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                }
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->ignition = $row['ignition'];
                $vehicle->status = $row['status'];
                $vehicle->isacopp = $row["is_ac_opp"];
                $vehicle->is_door_opp = $row["is_door_opp"];
                $vehicle->command = $row["command"];
                $vehicle->mobiliser_flag = $row["mobiliser_flag"];
                $vehicle->stoppage_flag = $row["stoppage_flag"];
                $vehicle->directionchange = $row["directionchange"];
                $vehicle->igstatus = $row["igstatus"];
                $vehicle->ignchgtime = $row["ignchgtime"];
                $vehicle->use_geolocation = $row["use_geolocation"];
                $vehicle->is_buzzer = $row["is_buzzer"];
                $vehicle->extra_digital = $row["extra_digital"];
                $vehicle->extra_digitalioupdated = $row["extra_digitalioupdated"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getwarehouseforrtdwithpagination() {
        $vehicles = Array();
        $timeIn60Minutes = time() - 60 * 60;
        $ServerIST_less1 = date('Y-m-d H:i:s', $timeIn60Minutes);
        $ServerIST_less1;
        $groups = $this->getUserGroups($_SESSION['customerno'], $_SESSION['userid']);
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
            $Query = "SELECT warehouse.vehicleid, warehouse.curspeed, warehouse.overspeed_limit, warehouse.stoppage_transit_time, driver.drivername, driver.driverid, driver.driverphone,devices.devicelat, devices.devicelong, warehouse.groupid, warehouse.extbatt, devices.ignition, devices.status, unit.is_buzzer,warehouse.stoppage_flag, devices.directionchange, customer.use_geolocation,unit.acsensor, warehouse.vehicleno, unit.unitno,devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,unit.analog3, unit.analog4, unit.digitalioupdated,unit.digitalio, unit.is_door_opp, devices.gsmstrength, devices.registeredon, warehouse.kind, warehouse.average,warehouse.fuelcapacity, warehouse.fuel_balance,unit.extra_digital,customer.use_extradigital,unit.extra_digitalioupdated,unit.tempsen1,unit.tempsen2,unit.is_mobiliser,unit.mobiliser_flag,unit.command,devices.deviceid,devices.lastupdated, unit.is_ac_opp, unit.msgkey,ignitionalert.status as igstatus,ignitionalert.ignchgtime
            FROM warehouse
            INNER JOIN devices ON devices.uid = warehouse.uid
            LEFT OUTER JOIN driver ON driver.driverid = warehouse.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = warehouse.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = warehouse.vehicleid";
            if ($_SESSION['roleid'] == '2') {
                $Query .= " LEFT OUTER JOIN `group` ON group.groupid = warehouse.groupid
                INNER JOIN city on city.cityid = group.cityid
                INNER JOIN district on district.districtid = city.districtid
                INNER JOIN state on state.stateid = district.stateid
                WHERE warehouse.customerno =%d AND state.stateid=%d AND group.isdeleted = 0 AND unit.trans_statusid NOT IN (10,22)";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND warehouse.groupid =%d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND warehouse.isdeleted=0 ORDER BY devices.lastupdated DESC";
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '3') {
                $Query .= " LEFT OUTER JOIN `group` ON group.groupid = warehouse.groupid
                INNER JOIN city on city.cityid = group.cityid
                INNER JOIN district on district.districtid = city.districtid
                WHERE warehouse.customerno =%d AND district.districtid=%d AND group.isdeleted = 0 AND unit.trans_statusid NOT IN (10,22)";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND warehouse.groupid =%d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND warehouse.isdeleted=0 ORDER BY devices.lastupdated DESC";
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '4') {
                $Query .= " LEFT OUTER JOIN `group` ON group.groupid = warehouse.groupid
                INNER JOIN city on city.cityid = group.cityid
                WHERE warehouse.customerno =%d AND city.cityid=%d AND group.isdeleted = 0 AND unit.trans_statusid NOT IN (10,22)";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND warehouse.groupid =%d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND warehouse.isdeleted=0 ORDER BY devices.lastupdated DESC";
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id'], $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['heirarchy_id']);
                }
            } elseif ($_SESSION['roleid'] == '8') {
                $Query .= " LEFT OUTER JOIN `group` ON `group`.groupid = warehouse.groupid
                WHERE warehouse.customerno =%d AND group.isdeleted = 0 AND unit.trans_statusid NOT IN (10,22)";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND warehouse.groupid =%d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND warehouse.isdeleted=0 ORDER BY devices.lastupdated DESC";
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno);
                }
            } else {
                $Query .= " WHERE warehouse.customerno =%d AND unit.trans_statusid NOT IN (10,22)";
                if ($_SESSION['groupid'] != 0) {
                    $Query .= " AND warehouse.groupid =%d";
                }
                if ($_SESSION['customerno'] == 1) {
                    $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
                }
                $Query .= " AND warehouse.isdeleted=0 ORDER BY devices.lastupdated DESC";
//$Query.= "";
                //$MainQuery = $Query.''.$Querylimit;
                if ($_SESSION['groupid'] != 0) {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                    $paginationQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                } else {
                    $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
                    $paginationQuery = sprintf($Query, $this->_Customerno);
                }
            }
        } elseif (isset($_SESSION['ecodeid'])) {
            $Query = "SELECT ecodeman.vehicleid, warehouse.curspeed, warehouse.overspeed_limit, warehouse.stoppage_transit_time,  devices.devicelat, devices.devicelong,  driver.drivername, driver.driverid, driver.driverphone,warehouse.groupid, warehouse.extbatt, devices.ignition, devices.status, warehouse.stoppage_flag, devices.directionchange, customer.use_geolocation,unit.acsensor,warehouse.vehicleno, unit.unitno, devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,unit.analog3, unit.analog4, unit.digitalio, unit.digitalioupdated,unit.is_buzzer,devices.gsmstrength, devices.registeredon, warehouse.kind, warehouse.average, warehouse.fuelcapacity, warehouse.fuel_balance, unit.extra_digital,customer.use_extradigital,unit.extra_digitalioupdated, unit.tempsen1,unit.tempsen2,unit.is_mobiliser,unit.mobiliser_flag,unit.is_door_opp, unit.command,ecodeman.ecodeid, elixiacode.startdate,elixiacode.expirydate, elixiacode.ecode,ecodeman.customerno,vehicle.groupid,devices.deviceid,devices.lastupdated, unit.is_ac_opp, unit.msgkey,ignitionalert.status as igstatus,ignitionalert.ignchgtime
            FROM ecodeman
            INNER JOIN warehouse ON warehouse.vehicleid = ecodeman.vehicleid
            INNER JOIN devices ON devices.uid = warehouse.uid
            LEFT OUTER JOIN driver ON driver.driverid = warehouse.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN elixiacode ON elixiacode.id = ecodeman.ecodeid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = ecodeman.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = ecodeman.vehicleid
            WHERE ecodeman.customerno =%d AND ecodeman.ecodeid=%d AND unit.trans_statusid NOT IN (10,22) AND ecodeman.isdeleted=0 ORDER BY devices.lastupdated DESC";
            $vehiclesQuery = sprintf($Query, Sanitise::Long($_SESSION['customerno']), Sanitise::Long($_SESSION['e_id']));
        } else {
            $Query = "SELECT warehouse.vehicleid, warehouse.curspeed, warehouse.overspeed_limit, warehouse.stoppage_transit_time,  driver.drivername, driver.driverid, driver.driverphone,devices.devicelat, devices.devicelong, warehouse.groupid, warehouse.extbatt, devices.ignition, devices.status, unit.is_buzzer,warehouse.stoppage_flag, devices.directionchange, customer.use_geolocation,unit.acsensor, warehouse.vehicleno, unit.unitno,devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,unit.analog3, unit.analog4, unit.digitalioupdated,unit.digitalio, unit.is_door_opp, devices.gsmstrength, devices.registeredon, warehouse.kind, warehouse.average, warehouse.fuelcapacity, warehouse.fuel_balance,unit.extra_digital,customer.use_extradigital,unit.extra_digitalioupdated,unit.tempsen1,unit.tempsen2,unit.is_mobiliser,unit.mobiliser_flag,unit.command,devices.deviceid,devices.lastupdated, unit.is_ac_opp, unit.msgkey,ignitionalert.status as igstatus,ignitionalert.ignchgtime
            FROM warehouse
            INNER JOIN devices ON devices.uid = warehouse.uid
            LEFT OUTER JOIN driver ON driver.driverid = warehouse.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = warehouse.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = warehouse.vehicleid
            WHERE warehouse.customerno =%d AND unit.trans_statusid NOT IN (10,22) ";
            if ($_SESSION['groupid'] == 0) {
                if ($groups[0] != 0) {
                    $groupid_ids = implode(',', $groups);
                    $Query .= " AND warehouse.groupid  IN (" . $groupid_ids . ") ";
                }
            } else {
                $Query .= " AND warehouse.groupid =%d";
            }
            if ($_SESSION['customerno'] == 1) {
                $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
            }
            $Query .= " AND warehouse.isdeleted=0 ORDER BY devices.lastupdated DESC";
            if ($_SESSION['groupid'] != 0) {
                $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
            } else {
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            }
        }
// echo $vehiclesQuery;
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->tamper = $row['tamper'];
                $vehicle->powercut = $row['powercut'];
                $vehicle->inbatt = $row['inbatt'];
                $vehicle->analog1 = $row['analog1'];
                $vehicle->analog2 = $row['analog2'];
                $vehicle->analog3 = $row['analog3'];
                $vehicle->analog4 = $row['analog4'];
                $vehicle->digitalio = $row['digitalio'];
                $vehicle->digitalioupdated = $row['digitalioupdated'];
                $vehicle->gsmstrength = $row['gsmstrength'];
                $vehicle->acsensor = $row['acsensor'];
                $vehicle->tempsen1 = $row['tempsen1'];
                $vehicle->tempsen2 = $row['tempsen2'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->type = $row['kind'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->stoppage_transit_time = $row['stoppage_transit_time'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->devicelat = $row['devicelat'];
                $vehicle->devicelong = $row['devicelong'];
                $vehicle->msgkey = $row['msgkey'];
                $vehicle->groupid = $row['groupid'];
                $vehicle->average = $row['average'];
                $vehicle->is_mobiliser = $row['is_mobiliser'];
                $vehicle->fuelcapacity = $row['fuelcapacity'];
                $vehicle->fuel_balance = $row['fuel_balance'];
                $vehicle->paginate = $vehiclesQuery;
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                }
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->ignition = $row['ignition'];
                $vehicle->status = $row['status'];
                $vehicle->isacopp = $row["is_ac_opp"];
                $vehicle->is_door_opp = $row["is_door_opp"];
                $vehicle->command = $row["command"];
                $vehicle->mobiliser_flag = $row["mobiliser_flag"];
                $vehicle->stoppage_flag = $row["stoppage_flag"];
                $vehicle->directionchange = $row["directionchange"];
                $vehicle->igstatus = $row["igstatus"];
                $vehicle->ignchgtime = $row["ignchgtime"];
                $vehicle->use_geolocation = $row["use_geolocation"];
                $vehicle->is_buzzer = $row["is_buzzer"];
                $vehicle->extra_digital = $row["extra_digital"];
                $vehicle->extra_digitalioupdated = $row["extra_digitalioupdated"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getvehiclesforridlestatus($idle) {
        $vehicles = Array();
        $lastupdate = date('Y-m-d H:i:s', time() - 3600);
        $hour1 = date('Y-m-d H:i:s', time() - (1 * 3600));
        $hour3 = date('Y-m-d H:i:s', time() - (3 * 3600));
        $hour5 = date('Y-m-d H:i:s', time() - (5 * 3600));
        $hour24 = date('Y-m-d H:i:s', time() - (24 * 3600));
        $Query = "SELECT vehicle.vehicleno FROM vehicle
        INNER JOIN devices ON devices.uid = vehicle.uid
        INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno
        WHERE vehicle.customerno =%d";
        if ($idle == '1') {
            $Query .= " AND vehicle.stoppage_transit_time <='$hour1' AND vehicle.stoppage_transit_time >= '$hour3' ";
        }
        if ($idle == '3') {
            $Query .= " AND vehicle.stoppage_transit_time <='$hour3' AND vehicle.stoppage_transit_time >= '$hour5' ";
        }
        if ($idle == '5') {
            $Query .= " AND vehicle.stoppage_transit_time <='$hour5' AND vehicle.stoppage_transit_time >= '$hour24' ";
        }
        if ($idle == '24') {
            $Query .= " AND vehicle.stoppage_transit_time <='$hour24' ";
        }
        $Query .= " AND vehicle.isdeleted=0 AND devices.lastupdated >'%s' ";
        $vehiclesQuery = sprintf($Query, $this->_Customerno, $lastupdate);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function gethours($StartTime) {
        $EndTime = date('Y-m-d H:i:s');
//                echo $EndTime.'_'.$StartTime.'<br>';
        $idleduration = strtotime($EndTime) - strtotime($StartTime);
        $years = floor($idleduration / (365 * 60 * 60 * 24));
        $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        if ($days > 0) {
            $hours = ($day * 24) + $hours;
        }
        return $hours;
    }

    public function getManager() {
        $Query = "Select rel_manager,manager_name,manager_email,manager_mobile from " . DB_PARENT . ".customer inner join " . DB_PARENT . ".relationship_manager on relationship_manager.rid = customer.rel_manager  where customerno=%d AND rel_manager <> ''";
        $SQL = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $manager = new VOVehicle();
                $manager->mid = $row['rel_manager'];
                $manager->name = $row['manager_name'];
                $manager->email = $row['manager_email'];
                $manager->mobile = $row['manager_mobile'];
            }
            return $manager;
        } else {
            return null;
        }
    }

    public function get_filter_vehiclesforrtd($sel_status, $sel_stoppage) {
        $vehicleid = '';
        $vehicleid_query = '';
        $group_query = '';
        $vehicles = Array();
        $status = explode(',', $sel_status);
        $groupid = $_SESSION['groupid'];
        $list = "vehicle.sequenceno
        ,vehicle.vehicleid
        , vehicle.curspeed
        , vehicle.overspeed_limit
        , vehicle.stoppage_transit_time
        , driver.drivername
        , devices.devicelat
        , devices.devicelong
        , vehicle.groupid
        , vehicle.extbatt
        , devices.ignition
        , devices.status
        ,vehicle.stoppage_flag
        , devices.directionchange
        , customer.use_geolocation
        ,unit.acsensor
        , vehicle.vehicleno
        , unit.unitno
        , devices.tamper
        , devices.powercut
        , devices.inbatt
        , unit.analog1
        , unit.analog2
        ,unit.analog3
        , unit.analog4
        ,unit.get_conversion
        , unit.digitalioupdated
        ,unit.is_door_opp
        , unit.digitalio
        , devices.gsmstrength
        , devices.registeredon
        , driver.driverid
        , driver.driverphone
        , vehicle.kind
        , vehicle.average
        , vehicle.fuelcapacity
        , vehicle.fuel_balance
        ,unit.is_buzzer
        , unit.is_freeze
        ,unit.is_freeze
        ,unit.tempsen1
        ,vehicle.temp1_min
        , vehicle.temp1_max
        , vehicle.temp2_min
        , vehicle.temp2_max
        , unit.tempsen2
        , unit.tempsen3
        , unit.tempsen4
        , unit.is_mobiliser
        ,unit.mobiliser_flag
        ,unit.command
        ,devices.deviceid
        ,devices.lastupdated
        , unit.is_ac_opp
        , unit.msgkey
        ,ignitionalert.status as igstatus
        ,ignitionalert.ignchgtime
        ,g1.gensetno as genset1
        ,g2.gensetno as genset2
        ,t1.transmitterno as transmitter1
        ,t2.transmitterno as transmitter2
        ,unit.setcom
        ,vehicle.temp1_mute
        ,vehicle.temp2_mute
        ,vehicle.temp3_mute
        ,vehicle.temp4_mute
        ,unit.extra2_digitalioupdated
        ,unit.door_digitalioupdated
        ,vehicle.routeDirection
        ,vehicle.ignition_wirecut";
        if ($groupid != 0) {
            $group_query = sprintf(" AND vehicle.groupid=%d ", $groupid);
        }
//echo $_SESSION['ecodeid'];
        if (isset($_SESSION['ecodeid'])) {
            $Query = sprintf("SELECT vehicle.sequenceno,ecodeman.vehicleid, vehicle.curspeed, vehicle.overspeed_limit, vehicle.stoppage_transit_time, driver.drivername, devices.devicelat, devices.devicelong,vehicle.groupid, vehicle.extbatt, devices.ignition, devices.status, vehicle.stoppage_flag, devices.directionchange, customer.use_geolocation,unit.acsensor,vehicle.vehicleno, unit.unitno, devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,unit.analog3, unit.analog4, unit.digitalio, unit.digitalioupdated,unit.is_buzzer, unit.is_freeze,devices.gsmstrength, devices.registeredon, driver.driverid, driver.driverphone, vehicle.kind, vehicle.average, vehicle.fuelcapacity, vehicle.fuel_balance, unit.tempsen1,vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max,unit.is_door_opp, unit.tempsen2,unit.is_mobiliser,unit.mobiliser_flag,unit.command,ecodeman.ecodeid, elixiacode.startdate,elixiacode.expirydate, elixiacode.ecode,ecodeman.customerno,vehicle.groupid,devices.deviceid,devices.lastupdated, unit.is_ac_opp, unit.msgkey,ignitionalert.status as igstatus,ignitionalert.ignchgtime,g1.gensetno as genset1,g2.gensetno as genset2,t1.transmitterno as transmitter1,t2.transmitterno as transmitter2,unit.setcom,vehicle.temp1_mute,vehicle.temp2_mute,vehicle.temp3_mute,vehicle.temp4_mute,unit.extra2_digitalioupdated,unit.door_digitalioupdated
                FROM ecodeman
                INNER JOIN vehicle ON vehicle.vehicleid = ecodeman.vehicleid
                INNER JOIN devices ON devices.uid = vehicle.uid
                INNER JOIN driver ON driver.driverid = vehicle.driverid
                INNER JOIN unit ON devices.uid = unit.uid
                INNER JOIN elixiacode ON elixiacode.id = ecodeman.ecodeid
                INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = ecodeman.customerno
                INNER JOIN ignitionalert ON ignitionalert.vehicleid = ecodeman.vehicleid
                LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid
                LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid
                LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid
                LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid
                WHERE ecodeman.customerno =%d AND ecodeman.ecodeid=%d AND unit.trans_statusid NOT IN (10,22)", $_SESSION['customerno'], $_SESSION['e_id']);
//$and_query = "AND ";
            $status_query = array();
            $date = date('Y-m-d H:i:s', time() - 3600);
            if ($vehicleid != "") {
                $vehicleid_query = sprintf(" and vehicle.vehicleno='%s'", $vehicleid);
            }
            if (in_array(5, $status)) {
                $status_query[] = sprintf(" devices.lastupdated < '%s'", $date);
            }
            if (in_array(4, $status)) {
                $status_query[] = sprintf(" (devices.ignition = 0 AND vehicle.curspeed = 0 AND devices.lastupdated > '%s' and devices.lastupdated > '%s' )", $date, $date);
            }
            if (in_array(3, $status)) {
                $status_query[] = sprintf(" ( devices.ignition = 1 AND vehicle.stoppage_flag = 0 AND devices.lastupdated > '%s' and devices.lastupdated > '%s' ) ", $date, $date);
            }
            if (in_array(2, $status)) {
                $status_query[] = sprintf(" (devices.ignition = 1  AND vehicle.stoppage_flag = 1 and devices.lastupdated > '%s' )", $date);
            }
            if (in_array(1, $status)) {
                $tempq = "";
                $c = "(case WHEN (unit.tempsen1=1) THEN unit.analog1 WHEN (unit.tempsen1=2) then unit.analog2 WHEN (unit.tempsen1=3) then unit.analog3 WHEN (unit.tempsen1=4) then unit.analog4 END)";
                if ($_SESSION['temp_sensors'] == 1) {
//die("=========11111========");
                    $tempq = "( ($c-1150)/4.45 < vehicle.temp1_min || ($c-1150)/4.45 > vehicle.temp1_max) and devices.ignition!=0 or ";
                } elseif ($_SESSION['temp_sensors'] == 2) {
//die("========2222222========");
                    $c2 = "(case WHEN (unit.tempsen2=1) THEN unit.analog1 WHEN (unit.tempsen2=2) then unit.analog2 WHEN (unit.tempsen2=3) then unit.analog3 WHEN (unit.tempsen1=4) then unit.analog4 END)";
                    $tempq1 = "( ($c-1150)/4.45 < vehicle.temp1_min || ($c-1150)/4.45 > vehicle.temp1_max) and devices.ignition!=0 or ";
                    $tempq2 = "( ($c2-1150)/4.45 < vehicle.temp2_min || ($c2-1150)/4.45 > vehicle.temp2_max) and devices.ignition!=0 or ";
                    $tempq = $tempq1 . $tempq2;
                }
                $status_query[] = sprintf("$tempq (vehicle.curspeed > vehicle.overspeed_limit and devices.lastupdated > '%s' )", $date);
            }
            $Query .= $vehicleid_query;
            if (!empty($status_query)) {
                $test = implode(' OR ', $status_query);
                $Query .= " and ($test)";
            }
            $Query .= $group_query;
            $Query .= sprintf(" AND ecodeman.isdeleted=0 ORDER BY devices.lastupdated DESC");
        } elseif (isset($_SESSION['roleid']) && $_SESSION['roleid'] == 43) {
            // elseif (isset($_SESSION['roleid']) && $this->VehicleUsermappingExists($_SESSION['userid']) == true) {
            $sql = "SELECT  $list
            FROM vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
            LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid
            LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid
            LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid
            LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid
            LEFT JOIN vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child = $this->_Customerno";
            if ($_SESSION['groupid'] != 0) {
                $sql .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid = %d";
            } else {
                $sql .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . "";
            }
            $sql .= " WHERE (vehicle.customerno =%d || vehicle.customerno = vr.parent) AND unit.trans_statusid NOT IN (10,22)";
            if ($_SESSION['groupid'] != 0) {
                $Query = sprintf($sql, $_SESSION['groupid'], $this->_Customerno);
            } else {
                $Query = sprintf($sql, $this->_Customerno);
            }
//$and_query = "AND ";
            $status_query = array();
            $date = date('Y-m-d H:i:s', time() - 3600);
            if ($vehicleid != "") {
                $vehicleid_query = sprintf(" and vehicle.vehicleno='%s'", $vehicleid);
            }
            if (in_array(5, $status)) {
                $status_query[] = sprintf(" devices.lastupdated < '%s'", $date);
            }
            if (in_array(4, $status)) {
                $status_query[] = sprintf(" (devices.ignition = 0 AND vehicle.curspeed = 0 AND devices.lastupdated > '%s' and devices.lastupdated > '%s' )", $date, $date);
            }
            if (in_array(3, $status)) {
                $status_query[] = sprintf(" ( devices.ignition = 1 AND vehicle.stoppage_flag = 0 AND devices.lastupdated > '%s' and devices.lastupdated > '%s' ) ", $date, $date);
            }
            if (in_array(2, $status)) {
                $status_query[] = sprintf(" (devices.ignition = 1  AND vehicle.stoppage_flag = 1 and devices.lastupdated > '%s' )", $date);
            }
            if (in_array(1, $status)) {
                $status_query[] = sprintf("(vehicle.curspeed > vehicle.overspeed_limit and devices.lastupdated > '%s' )", $date);
            }
            if (in_array(1, $status)) {
                $tempq = "";
                $c = "(case WHEN (unit.tempsen1=1) THEN unit.analog1 WHEN (unit.tempsen1=2) then unit.analog2 WHEN (unit.tempsen1=3) then unit.analog3 WHEN (unit.tempsen1=4) then unit.analog4 END)";
                if ($_SESSION['temp_sensors'] == 1) {
//die("=========11111========");
                    $tempq = "( ($c-1150)/4.45 < vehicle.temp1_min || ($c-1150)/4.45 > vehicle.temp1_max) and devices.ignition!=0 or ";
                } elseif ($_SESSION['temp_sensors'] == 2) {
//die("========2222222========");
                    $c2 = "(case WHEN (unit.tempsen2=1) THEN unit.analog1 WHEN (unit.tempsen2=2) then unit.analog2 WHEN (unit.tempsen2=3) then unit.analog3 WHEN (unit.tempsen1=4) then unit.analog4 END)";
                    $tempq1 = "( ($c-1150)/4.45 < vehicle.temp1_min || ($c-1150)/4.45 > vehicle.temp1_max) and devices.ignition!=0 or ";
                    $tempq2 = "( ($c2-1150)/4.45 < vehicle.temp2_min || ($c2-1150)/4.45 > vehicle.temp2_max) and devices.ignition!=0 or ";
                    $tempq = $tempq1 . $tempq2;
                }
                $status_query[] = sprintf("$tempq (vehicle.curspeed > vehicle.overspeed_limit and devices.lastupdated > '%s' )", $date);
            }
            $Query .= $vehicleid_query;
            if (!empty($status_query)) {
                $test = implode(' OR ', $status_query);
                $Query .= " and ($test)";
            }
            $Query .= $group_query;
            $Query .= sprintf(" AND vehicle.isdeleted=0 and vehmap.isdeleted = 0 and vehicle.kind<>'Warehouse' ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.customerno,vehicle.sequenceno ASC, devices.lastupdated DESC");
        } else {
            $Query = sprintf("SELECT $list
                FROM vehicle
                INNER JOIN devices ON devices.uid = vehicle.uid
                INNER JOIN driver ON driver.driverid = vehicle.driverid
                INNER JOIN unit ON devices.uid = unit.uid
                INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno
                INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid
                LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid
                LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid
                LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid
                LEFT JOIN vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child = $this->_Customerno
                WHERE (vehicle.customerno =%d || vehicle.customerno = vr.parent) AND unit.trans_statusid NOT IN (10,22)", $this->_Customerno);
//$and_query = "AND ";
            $status_query = array();
            $date = date('Y-m-d H:i:s', time() - 3600);
            if ($vehicleid != "") {
                $vehicleid_query = sprintf(" and vehicle.vehicleno='%s'", $vehicleid);
            }
            if (in_array(5, $status)) {
                $status_query[] = sprintf(" devices.lastupdated < '%s'", $date);
            }
            if (in_array(4, $status)) {
                $status_query[] = sprintf(" (devices.ignition = 0 AND vehicle.curspeed = 0 AND devices.lastupdated > '%s' and devices.lastupdated > '%s' )", $date, $date);
            }
            if (in_array(3, $status)) {
                $status_query[] = sprintf(" ( devices.ignition = 1 AND vehicle.stoppage_flag = 0 AND devices.lastupdated > '%s' and devices.lastupdated > '%s' ) ", $date, $date);
            }
            if (in_array(2, $status)) {
                $status_query[] = sprintf(" (devices.ignition = 1  AND vehicle.stoppage_flag = 1 and devices.lastupdated > '%s' )", $date);
            }
            if (in_array(1, $status)) {
                $status_query[] = sprintf("(vehicle.curspeed > vehicle.overspeed_limit and devices.lastupdated > '%s' )", $date);
            }
            if (in_array(1, $status)) {
                $tempq = "";
                $c = "(case WHEN (unit.tempsen1=1) THEN unit.analog1 WHEN (unit.tempsen1=2) then unit.analog2 WHEN (unit.tempsen1=3) then unit.analog3 WHEN (unit.tempsen1=4) then unit.analog4 END)";
                if ($_SESSION['temp_sensors'] == 1) {
//die("=========11111========");
                    $tempq = "( ($c-1150)/4.45 < vehicle.temp1_min || ($c-1150)/4.45 > vehicle.temp1_max) and devices.ignition!=0 or ";
                } elseif ($_SESSION['temp_sensors'] == 2) {
//die("========2222222========");
                    $c2 = "(case WHEN (unit.tempsen2=1) THEN unit.analog1 WHEN (unit.tempsen2=2) then unit.analog2 WHEN (unit.tempsen2=3) then unit.analog3 WHEN (unit.tempsen1=4) then unit.analog4 END)";
                    $tempq1 = "( ($c-1150)/4.45 < vehicle.temp1_min || ($c-1150)/4.45 > vehicle.temp1_max) and devices.ignition!=0 or ";
                    $tempq2 = "( ($c2-1150)/4.45 < vehicle.temp2_min || ($c2-1150)/4.45 > vehicle.temp2_max) and devices.ignition!=0 or ";
                    $tempq = $tempq1 . $tempq2;
                }
                $status_query[] = sprintf("$tempq (vehicle.curspeed > vehicle.overspeed_limit and devices.lastupdated > '%s' )", $date);
            }
            $Query .= $vehicleid_query;
            if (!empty($status_query)) {
                $test = implode(' OR ', $status_query);
                $Query .= " and ($test)";
            }
            $Query .= $group_query;
            $Query .= sprintf(" AND vehicle.isdeleted=0 and vehicle.kind<>'Warehouse' ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC,vehicle.customerno,vehicle.sequenceno ASC, devices.lastupdated DESC");
        }
//echo $Query;
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->sequenceno = $row['sequenceno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->customerno = $this->_Customerno;
                $vehicle->tamper = $row['tamper'];
                $vehicle->powercut = $row['powercut'];
                $vehicle->inbatt = $row['inbatt'];
                $vehicle->analog1 = $row['analog1'];
                $vehicle->analog2 = $row['analog2'];
                $vehicle->analog3 = $row['analog3'];
                $vehicle->analog4 = $row['analog4'];
                $vehicle->get_conversion = $row['get_conversion'];
                $vehicle->digitalio = $row['digitalio'];
                $vehicle->digitalioupdated = $row['digitalioupdated'];
                $vehicle->gsmstrength = $row['gsmstrength'];
                $vehicle->acsensor = $row['acsensor'];
                $vehicle->tempsen1 = $row['tempsen1'];
                $vehicle->tempsen2 = $row['tempsen2'];
                $vehicle->tempsen3 = $row['tempsen3'];
                $vehicle->tempsen4 = $row['tempsen4'];
                $vehicle->is_door_opp = $row['is_door_opp'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->type = $row['kind'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->stoppage_transit_time = $row['stoppage_transit_time'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->devicelat = $row['devicelat'];
                $vehicle->devicelong = $row['devicelong'];
                $vehicle->msgkey = $row['msgkey'];
                $vehicle->groupid = $row['groupid'];
                $vehicle->is_mobiliser = $row['is_mobiliser'];
                $vehicle->is_freeze = $row['is_freeze'];
                $vehicle->average = $row['average'];
                $vehicle->fuelcapacity = $row['fuelcapacity'];
                $vehicle->fuel_balance = $row['fuel_balance'];
                $vehicle->paginate = $Query;
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                }
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->ignition = $row['ignition'];
                $vehicle->status = $row['status'];
                $vehicle->isacopp = $row["is_ac_opp"];
                $vehicle->command = $row["command"];
                $vehicle->mobiliser_flag = $row["mobiliser_flag"];
                $vehicle->stoppage_flag = $row["stoppage_flag"];
                $vehicle->directionchange = $row["directionchange"];
                $vehicle->igstatus = $row["igstatus"];
                $vehicle->ignchgtime = $row["ignchgtime"];
                $vehicle->use_geolocation = $row["use_geolocation"];
                $vehicle->is_buzzer = $row["is_buzzer"];
                $vehicle->temp1_mute = $row['temp1_mute'];
                $vehicle->temp2_mute = $row['temp2_mute'];
                $vehicle->temp3_mute = $row['temp3_mute'];
                $vehicle->temp4_mute = $row['temp4_mute'];
                $vehicle->extra2_digitalioupdated = $row["extra2_digitalioupdated"];
                $vehicle->door_digitalioupdated = $row["door_digitalioupdated"];
                $vehicle->genset1 = $row['genset1'];
                $vehicle->genset2 = $row['genset2'];
                $vehicle->transmitter1 = $row['transmitter1'];
                $vehicle->transmitter2 = $row['transmitter2'];
                $vehicle->ignition_wirecut = $row['ignition_wirecut'];
                $date = new Date();
                $ServerIST_less1 = $date->add_hours(date("Y-m-d H:i:s"), -1);
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getrel_manager($cid) {
        $sql = "select rel.manager_name from " . DB_PARENT . ".customer left join " . DB_PARENT . ".relationship_manager rel on rel.rid = customer.rel_manager where customer.customerno =" . $cid;
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $manager_name = $row["manager_name"];
            }
        } else {
            $manager_name = "-";
        }
        return $manager_name;
    }

    public function getvehiclesforrtd_all() {
        $vehicles = Array();
        $Query = "SELECT *,unit.acsensor, vehicle.vehicleno, unit.unitno, unit.customerno,devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,
        unit.digitalio,unit.get_conversion,devices.gsmstrength, devices.lastupdated, devices.registeredon, devices.swv,unit.tempsen1,unit.tempsen2,devices.deviceid,devices.lastupdated,
        unit.is_ac_opp, simcard.simcardno
        FROM vehicle
        INNER JOIN devices ON devices.uid = vehicle.uid
        INNER JOIN driver ON driver.driverid = vehicle.driverid
        INNER JOIN unit ON devices.uid = unit.uid
        LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid
        WHERE  vehicle.isdeleted=0 AND unit.trans_statusid NOT IN (10,22) AND unit.customerno NOT IN (-1) ORDER BY unit.customerno,devices.lastupdated DESC";
        $vehiclesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->customerno = $row['customerno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->tamper = $row['tamper'];
                $vehicle->powercut = $row['powercut'];
                $vehicle->inbatt = $row['inbatt'];
                $vehicle->analog1 = $row['analog1'];
                $vehicle->analog2 = $row['analog2'];
                $vehicle->digitalio = $row['digitalio'];
                $vehicle->get_conversion = $row['get_conversion'];
                $vehicle->gsmstrength = $row['gsmstrength'];
                $vehicle->acsensor = $row['acsensor'];
                $vehicle->tempsen1 = $row['tempsen1'];
                $vehicle->tempsen2 = $row['tempsen2'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->type = $row['kind'];
                $vehicle->phone = $row['simcardno'];
                $vehicle->swv = $row['swv'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->devicelat = $row['devicelat'];
                $vehicle->devicelong = $row['devicelong'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                }
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->ignition = $row['ignition'];
                $vehicle->status = $row['status'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getvehiclesforrtd_all_by_customer() {
        $vehicles = Array();
        $Query = "SELECT *,unit.acsensor, vehicle.vehicleno, unit.unitno, unit.customerno,devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,
        unit.digitalio, devices.gsmstrength, devices.lastupdated, devices.registeredon, devices.swv,unit.tempsen1,unit.tempsen2,devices.deviceid,devices.lastupdated,
        unit.is_ac_opp, simcard.simcardno
        FROM vehicle
        INNER JOIN devices ON devices.uid = vehicle.uid
        INNER JOIN driver ON driver.driverid = vehicle.driverid
        INNER JOIN unit ON devices.uid = unit.uid
        LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid
        WHERE  vehicle.isdeleted=0 AND unit.trans_statusid NOT IN (10,22) AND unit.customerno=$this->_Customerno ORDER BY unit.customerno,devices.lastupdated DESC";
        $vehiclesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->customerno = $row['customerno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->tamper = $row['tamper'];
                $vehicle->powercut = $row['powercut'];
                $vehicle->inbatt = $row['inbatt'];
                $vehicle->analog1 = $row['analog1'];
                $vehicle->analog2 = $row['analog2'];
                $vehicle->digitalio = $row['digitalio'];
                $vehicle->gsmstrength = $row['gsmstrength'];
                $vehicle->acsensor = $row['acsensor'];
                $vehicle->tempsen1 = $row['tempsen1'];
                $vehicle->tempsen2 = $row['tempsen2'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->type = $row['kind'];
                $vehicle->phone = $row['simcardno'];
                $vehicle->swv = $row['swv'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->devicelat = $row['devicelat'];
                $vehicle->devicelong = $row['devicelong'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                }
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->ignition = $row['ignition'];
                $vehicle->status = $row['status'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getvehiclesforrtd_all_inactive($from_inactive_date, $to_inactive_date, $crmmanager, $issuetype, $icustomer) {
        if (($from_inactive_date == "1970-01-01 00:00:00" || $from_inactive_date == "") && ($to_inactive_date == "1970-01-01 23:50:50" || $to_inactive_date == "")) {
            $date = date('Y-m-d H:i:s', time() - 3600);
            $daterange = "devices.lastupdated<'$date'";
        } else {
            $daterange = "devices.lastupdated BETWEEN '$from_inactive_date' AND '$to_inactive_date'";
        }
        if ($crmmanager != 0) {
            $wheremanager = "customer.rel_manager=" . $crmmanager . " AND";
        } else {
            $wheremanager = "";
        }
///issue type filter
        if ($issuetype != 0) {
            $whereissue = "unit.issue_type=" . $issuetype . " AND";
        } else {
            $whereissue = "";
        }
//////customer filter
        if ($icustomer != 0) {
            $wherecustomer = "unit.customerno=" . $icustomer . " AND ";
        } else {
            $wherecustomer = "";
        }
        $vehicles = Array();
//   $date = date('Y-m-d H:i:s', time() - 3600);
        $Query = "SELECT *,unit.acsensor,customer.rel_manager,vehicle.vehicleno, unit.unitno, unit.customerno,devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,unit.digitalio, devices.gsmstrength, devices.lastupdated, devices.registeredon, devices.swv,unit.tempsen1,unit.tempsen2,devices.deviceid,devices.lastupdated, unit.is_ac_opp, simcard.simcardno
        FROM vehicle
        INNER JOIN devices ON devices.uid = vehicle.uid
        INNER JOIN driver ON driver.driverid = vehicle.driverid
        INNER JOIN unit ON devices.uid = unit.uid
        INNER JOIN simcard ON simcard.id = devices.simcardid
        INNER JOIN " . DB_PARENT . ".customer ON unit.customerno = customer.customerno
        WHERE  " . $wherecustomer . "   " . $whereissue . " " . $wheremanager . "  vehicle.isdeleted=0 AND unit.trans_statusid NOT IN (10,22) AND unit.customerno NOT IN (-1,1) AND " . $daterange . " ORDER BY unit.customerno,devices.lastupdated DESC";
//$vehiclesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                if ($row['customerno'] == '97') {
                    $date1 = new DateTime();
                    $date1->setTimezone(new DateTimeZone('Africa/Cairo'));
                    $cairo_dt = $date1->format('Y-m-d H:i:s');
                    $cairo_diff = date("Y-m-d H:i:s", strtotime("-60 minutes", strtotime($cairo_dt)));
                } else {
                    date_default_timezone_set('Asia/Kolkata');
                }if ($row['customerno'] == '97' && $row['lastupdated'] > $cairo_diff) {
                    continue;
                } else {
                    $vehicle->customerno = $row['customerno'];
                    $vehicle->deviceid = $row['deviceid'];
                    $vehicle->tamper = $row['tamper'];
                    $vehicle->powercut = $row['powercut'];
                    $vehicle->inbatt = $row['inbatt'];
                    $vehicle->analog1 = $row['analog1'];
                    $vehicle->analog2 = $row['analog2'];
                    $vehicle->digitalio = $row['digitalio'];
                    $vehicle->gsmstrength = $row['gsmstrength'];
                    $vehicle->acsensor = $row['acsensor'];
                    $vehicle->tempsen1 = $row['tempsen1'];
                    $vehicle->tempsen2 = $row['tempsen2'];
                    $vehicle->vehicleid = $row['vehicleid'];
                    $vehicle->type = $row['kind'];
                    $vehicle->phone = $row['simcardno'];
                    $vehicle->swv = $row['swv'];
                    $vehicle->vehicleno = $row['vehicleno'];
                    $vehicle->curspeed = $row['curspeed'];
                    $vehicle->overspeed_limit = $row['overspeed_limit'];
                    $vehicle->unitno = $row['unitno'];
                    $vehicle->drivername = $row['drivername'];
                    $vehicle->driverphone = $row['driverphone'];
                    $vehicle->devicelat = $row['devicelat'];
                    $vehicle->devicelong = $row['devicelong'];
                    if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                        $vehicle->lastupdated = $row['lastupdated'];
                    } else {
                        $vehicle->lastupdated = $row['registeredon'];
                    }
                    $vehicle->extbatt = $row['extbatt'];
                    $vehicle->ignition = $row['ignition'];
                    $vehicle->status = $row['status'];
                    $vehicle->remark = $row['remark'];
                    $vehicle->alterremark = $row['alterremark'];
                    $vehicle->issuetype = $row['issue_type'];
                    $vehicles[] = $vehicle;
                }
            }
            return $vehicles;
        }
        return null;
    }

    public function team_getvehiclesforrtd_all_inactive($crmmanager, $issuetype, $icustomer) {
        $date = date('Y-m-d H:i:s', time() - 3600);
        $daterange = "devices.lastupdated<'$date'";
        if ($crmmanager != 0) {
            $wheremanager = "customer.rel_manager=" . $crmmanager . " AND";
        } else {
            $wheremanager = "";
        }
///issue type filter
        if ($issuetype != 0) {
            $whereissue = "unit.issue_type=" . $issuetype . " AND";
        } else {
            $whereissue = "";
        }
//////customer filter
        if ($icustomer != 0) {
            $wherecustomer = "unit.customerno=" . $icustomer . " AND ";
        } else {
            $wherecustomer = "";
        }
        $vehicles = Array();
//   $date = date('Y-m-d H:i:s', time() - 3600);
        $Query = "SELECT *,unit.acsensor,customer.rel_manager,vehicle.vehicleno, unit.unitno, unit.customerno, unit.get_conversion,devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,unit.digitalio, devices.gsmstrength, devices.registeredon, devices.swv,unit.tempsen1,unit.tempsen2,devices.deviceid,devices.lastupdated, unit.is_ac_opp, simcard.simcardno
        FROM vehicle
        INNER JOIN devices ON devices.uid = vehicle.uid
        INNER JOIN unit ON devices.uid = unit.uid
        INNER JOIN simcard ON simcard.id = devices.simcardid
        INNER JOIN " . DB_PARENT . ".customer ON unit.customerno = customer.customerno
        WHERE  " . $wherecustomer . "   " . $whereissue . " " . $wheremanager . "  vehicle.isdeleted=0 AND unit.trans_statusid NOT IN (10,22) AND unit.customerno NOT IN (-1,1) AND " . $daterange . " ORDER BY unit.customerno,devices.lastupdated DESC";
//$vehiclesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                if ($row['customerno'] == '97') {
                    $date1 = new DateTime();
                    $date1->setTimezone(new DateTimeZone('Africa/Cairo'));
                    $cairo_dt = $date1->format('Y-m-d H:i:s');
                    $cairo_diff = date("Y-m-d H:i:s", strtotime("-60 minutes", strtotime($cairo_dt)));
                } else {
                    date_default_timezone_set('Asia/Kolkata');
                }if ($row['customerno'] == '97' && $row['lastupdated'] > $cairo_diff) {
                    continue;
                } else {
                    $vehicle->customerno = $row['customerno'];
                    $vehicle->deviceid = $row['deviceid'];
                    $vehicle->tamper = $row['tamper'];
                    $vehicle->powercut = $row['powercut'];
                    $vehicle->inbatt = $row['inbatt'];
                    $vehicle->analog1 = $row['analog1'];
                    $vehicle->analog2 = $row['analog2'];
                    $vehicle->get_conversion = $row['get_conversion'];
                    $vehicle->digitalio = $row['digitalio'];
                    $vehicle->gsmstrength = $row['gsmstrength'];
                    $vehicle->acsensor = $row['acsensor'];
                    $vehicle->tempsen1 = $row['tempsen1'];
                    $vehicle->tempsen2 = $row['tempsen2'];
                    $vehicle->vehicleid = $row['vehicleid'];
                    $vehicle->type = $row['kind'];
                    $vehicle->phone = $row['simcardno'];
                    $vehicle->swv = $row['swv'];
                    $vehicle->vehicleno = $row['vehicleno'];
                    $vehicle->curspeed = $row['curspeed'];
                    $vehicle->overspeed_limit = $row['overspeed_limit'];
                    $vehicle->unitno = $row['unitno'];
                    $vehicle->devicelat = $row['devicelat'];
                    $vehicle->devicelong = $row['devicelong'];
                    if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                        $vehicle->lastupdated = $row['lastupdated'];
                    } else {
                        $vehicle->lastupdated = $row['registeredon'];
                    }
                    $vehicle->extbatt = $row['extbatt'];
                    $vehicle->ignition = $row['ignition'];
                    $vehicle->status = $row['status'];
                    $vehicle->remark = $row['remark'];
                    $vehicle->alterremark = $row['alterremark'];
                    $vehicle->issuetype = $row['issue_type'];
                    $vehicles[] = $vehicle;
                }
            }
            return $vehicles;
        }
        return null;
    }

    public function team_getvehiclesforrtd_all_inactive_days($crmmanager, $issuetype, $icustomer, $count_days) {
        $date_new = date("Y-m-d");
        $date = date('Y-m-d H:i:s', strtotime($date_new . ' - ' . $count_days . ' days'));
        $daterange = "devices.lastupdated<'$date'";
        if ($crmmanager != 0) {
            $wheremanager = "customer.rel_manager=" . $crmmanager . " AND";
        } else {
            $wheremanager = "";
        }
///issue type filter
        if ($issuetype != 0) {
            $whereissue = "unit.issue_type=" . $issuetype . " AND";
        } else {
            $whereissue = "";
        }
//////customer filter
        if ($icustomer != 0) {
            $wherecustomer = "devices.customerno=" . $icustomer . " AND ";
        } else {
            $wherecustomer = "";
        }
        $vehicles = Array();
        $Query = "SELECT *
        FROM devices
        INNER JOIN unit ON unit.uid = devices.uid
        INNER JOIN " . DB_PARENT . ".customer ON devices.customerno = customer.customerno
        WHERE  " . $wherecustomer . "   " . $whereissue . " " . $wheremanager . " unit.trans_statusid IN (5,6) AND customer.renewal NOT IN (-1,-2) AND " . $daterange . " ORDER BY devices.lastupdated DESC";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->deviceid = $row['deviceid'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function gettemp_wirecuts($crmmanager, $issuetype, $icustomer) {
        if ($crmmanager != 0) {
            $wheremanager = "customer.rel_manager=" . $crmmanager . " AND";
        } else {
            $wheremanager = "";
        }
///issue type filter
        if ($issuetype != 0) {
            $whereissue = "unit.issue_type=" . $issuetype . " AND";
        } else {
            $whereissue = "";
        }
//////customer filter
        if ($icustomer != 0) {
            $wherecustomer = "unit.customerno=" . $icustomer . " AND ";
        } else {
            $wherecustomer = "";
        }
        $vehicles = Array();
        $Query = "SELECT unit.n1, unit.n2, unit.n3, unit.n4, vehicle.vehicleid, customer.temp_sensors,customer.rel_manager,vehicle.vehicleno, unit.unitno, unit.customerno, unit.analog1, unit.analog2,unit.analog3, unit.analog4, devices.lastupdated,unit.tempsen1,unit.tempsen2,unit.tempsen3,unit.tempsen4,devices.deviceid, simcard.simcardno
        FROM vehicle
        INNER JOIN devices ON devices.uid = vehicle.uid
        INNER JOIN unit ON devices.uid = unit.uid
        INNER JOIN simcard ON simcard.id = devices.simcardid
        INNER JOIN " . DB_PARENT . ".customer ON unit.customerno = customer.customerno
        WHERE  " . $wherecustomer . "   " . $whereissue . " " . $wheremanager . "  vehicle.isdeleted=0 AND unit.trans_statusid NOT IN (10,22) AND unit.customerno NOT IN (-1,1) ORDER BY unit.customerno,devices.lastupdated DESC";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                if ($row['customerno'] == '97') {
                    $date1 = new DateTime();
                    $date1->setTimezone(new DateTimeZone('Africa/Cairo'));
                    $cairo_dt = $date1->format('Y-m-d H:i:s');
                    $cairo_diff = date("Y-m-d H:i:s", strtotime("-60 minutes", strtotime($cairo_dt)));
                } else {
                    date_default_timezone_set('Asia/Kolkata');
                }if ($row['customerno'] == '97' && $row['lastupdated'] > $cairo_diff) {
                    continue;
                } else {
                    if ($row["temp_sensors"] > 0) {
                        $vehicle->customerno = $row['customerno'];
                        $vehicle->deviceid = $row['deviceid'];
                        $vehicle->analog1 = $row['analog1'];
                        $vehicle->analog2 = $row['analog2'];
                        $vehicle->analog3 = $row['analog3'];
                        $vehicle->analog4 = $row['analog4'];
                        $vehicle->tempsen1 = $row['tempsen1'];
                        $vehicle->tempsen2 = $row['tempsen2'];
                        $vehicle->tempsen3 = $row['tempsen3'];
                        $vehicle->tempsen4 = $row['tempsen4'];
                        $vehicle->vehicleid = $row['vehicleid'];
                        $vehicle->vehicleno = $row['vehicleno'];
                        $vehicle->unitno = $row['unitno'];
                        $vehicle->devicelat = $row['devicelat'];
                        $vehicle->devicelong = $row['devicelong'];
                        if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                            $vehicle->lastupdated = $row['lastupdated'];
                        } else {
                            $vehicle->lastupdated = $row['registeredon'];
                        }
                        $vehicle->status = $row['status'];
                        $vehicle->n1 = $row['n1'];
                        $vehicle->n2 = $row['n2'];
                        $vehicle->n3 = $row['n3'];
                        $vehicle->n4 = $row['n4'];
                        $vehicle->remark = $row['remark'];
                        $vehicle->alterremark = $row['alterremark'];
                        $vehicle->issuetype = $row['issue_type'];
                        if ($row["temp_sensors"] >= 1) {
                            $s1 = "analog" . $vehicle->tempsen1;
                            if ($vehicle->tempsen1 != 0 && ($vehicle->$s1 == 0 || $vehicle->$s1 == 1150)) {
                                if ($row["customerno"] == 177) {
                                    if ($vehicle->n1 != 0) {
                                        $vehicle->tempsel = "Temperature Probe 1";
                                        $vehicles[] = $vehicle;
                                    }
                                } else {
                                    $vehicle->tempsel = "Temperature Probe 1";
                                    $vehicles[] = $vehicle;
                                }
                            }
                        }
                        if ($row["temp_sensors"] >= 2) {
                            $s2 = "analog" . $vehicle->tempsen2;
                            if ($vehicle->tempsen2 != 0 && ($vehicle->$s2 == 0 || $vehicle->$s2 == 1150)) {
                                if ($row["customerno"] == 177) {
                                    if ($vehicle->n2 != 0) {
                                        $vehicle->tempsel = "Temperature Probe 2";
                                        $vehicles[] = $vehicle;
                                    }
                                } else {
                                    $vehicle->tempsel = "Temperature Probe 2";
                                    $vehicles[] = $vehicle;
                                }
                            }
                        }
                        if ($row["temp_sensors"] >= 3) {
                            $s3 = "analog" . $vehicle->tempsen3;
                            if ($vehicle->tempsen3 != 0 && ($vehicle->$s3 == 0 || $vehicle->$s3 == 1150)) {
                                if ($row["customerno"] == 177) {
                                    if ($vehicle->n3 != 0) {
                                        $vehicle->tempsel = "Temperature Probe 3";
                                        $vehicles[] = $vehicle;
                                    }
                                } else {
                                    $vehicle->tempsel = "Temperature Probe 3";
                                    $vehicles[] = $vehicle;
                                }
                            }
                        }
                        if ($row["temp_sensors"] >= 4) {
                            $s4 = "analog" . $vehicle->tempsen4;
                            if ($vehicle->tempsen4 != 0 && ($vehicle->$s4 == 0 || $vehicle->$s4 == 1150)) {
                                if ($row["customerno"] == 177) {
                                    if ($vehicle->n4 != 0) {
                                        $vehicle->tempsel = "Temperature Probe 4";
                                        $vehicles[] = $vehicle;
                                    }
                                } else {
                                    $vehicle->tempsel = "Temperature Probe 4";
                                    $vehicles[] = $vehicle;
                                }
                            }
                        }
                    }
                }
            }
            return $vehicles;
        }
        return null;
    }

//pending invoices
    public function getvehiclesforrtd_all_pending() {
        $vehicles = Array();
        $date = date('Y-m-d H:i:s', time() - 3600);
        $Query = "SELECT *,vehicle.vehicleno, unit.unitno, unit.uid, unit.customerno,devices.lastupdated, devices.registeredon, devices.installdate, devices.expirydate,
        devices.deviceid,devices.lastupdated, devices.invoiceno, simcard.simcardno
        FROM vehicle
        INNER JOIN devices ON devices.uid = vehicle.uid
        INNER JOIN unit ON devices.uid = unit.uid
        LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid
        WHERE  vehicle.isdeleted=0 AND unit.customerno NOT IN (-1,1,2) AND unit.trans_statusid NOT IN(22,23,24,10) AND devices.expirydate !='1970-01-01' AND  devices.expirydate!='0000-00-00' AND devices.invoiceno = '' ORDER BY unit.customerno,devices.lastupdated DESC";
// $vehiclesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->customerno = $row['customerno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->phone = $row['simcardno'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->install = date("d-m-Y", strtotime($row['installdate']));
                $vehicle->expiry = date("d-m-Y", strtotime($row['expirydate']));
                $vehicle->unitno = $row['unitno'];
                $vehicle->uid = $row['uid'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                }
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getNameForTemp($nid) {
        $Query = "Select * from nomens where nid=%d and customerno=%d";
        $SQL = sprintf($Query, $nid, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row['name'] == 'Make Line') {
                    $row['name'] = 'Vigi Cooler';
                    return $row['name'];
                } else {
                    return $row['name'];
                }
            }
        }
    }

//find pending invoices according to search
    public function getsearch_pendinginv($expdate, $customerno) {
        $vehicles = Array();
        $exp_date = $expdate;
        $cust_no = $customerno;
//$date = date('Y-m-d H:i:s', time() - 3600);
        $Query = "SELECT *,vehicle.vehicleno, unit.unitno, unit.uid, unit.customerno,devices.lastupdated, devices.registeredon, devices.installdate, devices.expirydate,
        devices.deviceid,devices.lastupdated, devices.invoiceno, simcard.simcardno
        FROM vehicle
        INNER JOIN devices ON devices.uid = vehicle.uid
        INNER JOIN unit ON devices.uid = unit.uid
        LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid
        WHERE  vehicle.isdeleted=0 AND unit.customerno NOT IN (-1,1) AND unit.trans_statusid NOT IN(22,23,24,10) AND devices.expirydate !='1970-01-01' AND  devices.expirydate!='0000-00-00' AND devices.invoiceno = ''";
        if ($cust_no != "" && $cust_no != '0') {
            $Query .= sprintf(" AND unit.customerno='$cust_no'");
        }
        if ($exp_date != "1970-01-01") {
            $Query .= sprintf(" AND devices.expirydate='$exp_date'");
        }
//echo $Query;
        // $vehiclesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->customerno = $row['customerno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->phone = $row['simcardno'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->install = date("d-m-Y", strtotime($row['installdate']));
                $vehicle->expiry = date("d-m-Y", strtotime($row['expirydate']));
                $vehicle->unitno = $row['unitno'];
                $vehicle->uid = $row['uid'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                }
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

//pending renewals
    public function getvehiclesforrtd_all_pending1() {
        $vehicles = Array();
        $date = date('Y-m-d H:i:s', time() - 3600);
        $today = date('Y-m-d');
        $Query = "SELECT *,vehicle.vehicleno, unit.unitno, unit.uid, unit.customerno,unit.get_conversion,devices.lastupdated, devices.registeredon, devices.installdate, devices.expirydate,
        devices.deviceid,devices.lastupdated, unit.is_ac_opp, simcard.simcardno
        FROM vehicle
        INNER JOIN devices ON devices.uid = vehicle.uid
        INNER JOIN driver ON driver.driverid = vehicle.driverid
        INNER JOIN unit ON devices.uid = unit.uid
        LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid
        WHERE  vehicle.isdeleted= 0 AND devices.expirydate <'" . $today . "' AND unit.customerno NOT IN (-1,1) AND devices.expirydate !='1970-01-01' AND devices.expirydate!='0000-00-00' AND  unit.trans_statusid NOT IN(23,24,10)  ORDER BY devices.expirydate,unit.customerno,devices.lastupdated DESC";
// $vehiclesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->customerno = $row['customerno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->phone = $row['simcardno'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->install = date("d-m-Y", strtotime($row['installdate']));
                $vehicle->expiry = date("d-m-Y", strtotime($row['expirydate']));
                $vehicle->unitno = $row['unitno'];
                $vehicle->uid = $row['uid'];
                $vehicle->get_conversion = $row['get_conversion'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                }
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

//find pending renewal according to search
    public function getsearch_renewal($expdate, $customerno, $insdate) {
        $vehicles = Array();
        $exp_date = $expdate;
        $cust_no = $customerno;
        $ins_date = $insdate;
        $date = date('Y-m-d H:i:s', time() - 3600);
        $today = date('Y-m-d');
        $Query = "SELECT *,vehicle.vehicleno, unit.unitno, unit.uid, unit.customerno,devices.lastupdated, devices.registeredon, devices.installdate, devices.expirydate,
        devices.deviceid,devices.lastupdated, unit.is_ac_opp, simcard.simcardno
        FROM vehicle
        INNER JOIN devices ON devices.uid = vehicle.uid
        INNER JOIN unit ON devices.uid = unit.uid
        LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid
        WHERE  vehicle.isdeleted= 0 AND devices.expirydate <'" . $today . "' AND unit.customerno NOT IN (-1,1) AND devices.expirydate !='1970-01-01' AND devices.expirydate!='0000-00-00' AND  unit.trans_statusid NOT IN(22,23,24,10)";
// $vehiclesQuery = sprintf($Query, $this->_Customerno);
        if ($cust_no != "" && $cust_no != '0') {
            $Query .= sprintf(" AND unit.customerno='$cust_no'");
        }
        if ($exp_date != "1970-01-01") {
            $Query .= sprintf(" AND devices.expirydate='$exp_date'");
        }
        if ($ins_date != "1970-01-01") {
            $Query .= sprintf(" AND devices.installdate='$ins_date'");
        }
//echo $Query;
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->customerno = $row['customerno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->phone = $row['simcardno'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->install = date("d-m-Y", strtotime($row['installdate']));
                $vehicle->expiry = date("d-m-Y", strtotime($row['expirydate']));
                $vehicle->unitno = $row['unitno'];
                $vehicle->uid = $row['uid'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                }
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getvehiclesforrtd_all_inactive_bycustomer($customerno, $arrGroupIds = null, $isWarehouse = null) {
        $warehouseCondition = " AND vehicle.kind <> 'Warehouse'";
        if (isset($isWarehouse)) {
            $warehouseCondition = " AND vehicle.kind = 'Warehouse'";
        }
        $vehicles = Array();
        $date = date('Y-m-d H:i:s', time() - 3600);
        $Query = "SELECT *,unit.acsensor, vehicle.vehicleno, unit.unitno, unit.customerno,devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,
        unit.digitalio, devices.gsmstrength, devices.lastupdated, devices.registeredon, devices.swv,unit.tempsen1,unit.tempsen2,devices.deviceid,devices.lastupdated,
        unit.is_ac_opp, simcard.simcardno, `group`.groupname
        FROM vehicle
        INNER JOIN devices ON devices.uid = vehicle.uid
        LEFT OUTER JOIN `group` ON vehicle.groupid = `group`.groupid
        INNER JOIN driver ON driver.driverid = vehicle.driverid
        INNER JOIN unit ON devices.uid = unit.uid
        INNER JOIN " . DB_PARENT . ".customer ON vehicle.customerno = customer.customerno
        LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid
        WHERE  vehicle.isdeleted=0 AND unit.trans_statusid NOT IN (10,22) " . $warehouseCondition . " AND unit.customerno =%d";
        if ($arrGroupIds[0] == 0) {
            $vehiclesQuery = sprintf($Query, $customerno);
        } else {
            $group_in = implode(',', $arrGroupIds);
            $Query .= " AND vehicle.groupid in (%s) ";
            $vehiclesQuery = sprintf($Query, $customerno, $group_in);
        }
        $vehiclesQuery .= " ORDER BY `group`.groupname ASC, vehicle.lastupdated DESC";
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->customerno = $row['customerno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->tamper = $row['tamper'];
                $vehicle->powercut = $row['powercut'];
                $vehicle->inbatt = $row['inbatt'];
                $vehicle->analog1 = $row['analog1'];
                $vehicle->analog2 = $row['analog2'];
                $vehicle->digitalio = $row['digitalio'];
                $vehicle->isacopp = $row['is_ac_opp'];
                $vehicle->digitalioupdated = $row['digitalioupdated'];
                $vehicle->gsmstrength = $row['gsmstrength'];
                $vehicle->acsensor = $row['acsensor'];
                $vehicle->tempsen1 = $row['tempsen1'];
                $vehicle->tempsen2 = $row['tempsen2'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->groupid = $row['groupid'];
                $vehicle->type = $row['kind'];
                $vehicle->phone = $row['simcardno'];
                $vehicle->swv = $row['swv'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->uid = $row['uid'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->devicelat = $row['devicelat'];
                $vehicle->devicelong = $row['devicelong'];
                $vehicle->gprsregister = $row['gprsregister'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                }
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->ignition = $row['ignition'];
                $vehicle->status = $row['status'];
                $vehicle->remark = $row['remark'];
                $vehicle->alterremark = $row['alterremark'];
                $vehicle->groupname = $row['groupname'];
                $vehicle->customercompany = $row['customercompany'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getvehicles_Temp_bycustomer($customerno) {
        $vehicles = Array();
        $Query = "SELECT *,unit.acsensor,unit.get_conversion,vehicle.vehicleno, unit.unitno, unit.customerno,devices.tamper, devices.powercut, devices.inbatt, unit.analog1, unit.analog2,
        unit.analog3, unit.analog4, unit.tempsen1, unit.tempsen2,unit.digitalio, devices.gsmstrength, devices.lastupdated, devices.registeredon, devices.swv,
        unit.tempsen1,unit.tempsen2,devices.deviceid,devices.lastupdated, unit.is_ac_opp, simcard.simcardno, customer.use_humidity
        FROM vehicle
        INNER JOIN devices ON devices.uid = vehicle.uid
        INNER JOIN driver ON driver.driverid = vehicle.driverid
        INNER JOIN unit ON devices.uid = unit.uid
        LEFT JOIN `group` ON vehicle.groupid = group.groupid
        INNER JOIN " . DB_PARENT . ".customer ON vehicle.customerno = customer.customerno
        LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid
        WHERE  vehicle.isdeleted=0 AND unit.trans_statusid NOT IN (10,22) AND unit.customerno =%d ORDER BY unit.customerno,vehicle.lastupdated DESC";
        $vehiclesQuery = sprintf($Query, $customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->customerno = $row['customerno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->tamper = $row['tamper'];
                $vehicle->powercut = $row['powercut'];
                $vehicle->inbatt = $row['inbatt'];
                $vehicle->tempsen1 = $row['tempsen1'];
                $vehicle->tempsen2 = $row['tempsen2'];
                $vehicle->analog1 = $row['analog1'];
                $vehicle->analog2 = $row['analog2'];
                $vehicle->analog3 = $row['analog3'];
                $vehicle->analog4 = $row['analog4'];
                $vehicle->get_conversion = $row['get_conversion'];
                $vehicle->digitalio = $row['digitalio'];
                $vehicle->isacopp = $row['is_ac_opp'];
                $vehicle->digitalioupdated = $row['digitalioupdated'];
                $vehicle->gsmstrength = $row['gsmstrength'];
                $vehicle->acsensor = $row['acsensor'];
                $vehicle->tempsen1 = $row['tempsen1'];
                $vehicle->tempsen2 = $row['tempsen2'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->groupid = $row['groupid'];
                $vehicle->groupname = $row['groupname'];
                $vehicle->type = $row['kind'];
                $vehicle->phone = $row['simcardno'];
                $vehicle->swv = $row['swv'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->uid = $row['uid'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->devicelat = $row['devicelat'];
                $vehicle->devicelong = $row['devicelong'];
                $vehicle->gprsregister = $row['gprsregister'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                }
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->ignition = $row['ignition'];
                $vehicle->status = $row['status'];
                $vehicle->remark = $row['remark'];
                $vehicle->alterremark = $row['alterremark'];
                $vehicle->customercompany = $row['customercompany'];
                $vehicle->use_humidity = $row['use_humidity'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getGensetName($customerno) {
        $SQL = "SELECT customname FROM customfield WHERE custom_id = 1 and customerno=%d and usecustom=1";
        $QUERY = sprintf($SQL, $customerno);
        $this->_databaseManager->executeQuery($QUERY);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($row['customname'] != "") {
                    return $customname = $row['customname'];
                } else {
                    return $customname = 'Digital';
                }
            }
        } else {
            return $customname = 'Digital';
        }
    }

    public function update_file_names_for_transaction($transactionid, $file_name_array, $statusid = null) {
        if ($transactionid != 0 && $transactionid != "") {
            if (isset($statusid) && $statusid == '10') {
                //upload invoice file
                $Query = "SELECT invoice_file_name  FROM maintenance WHERE maintenance.customerno = %d AND id = %d AND maintenance.isdeleted=0 ";
                $vehiclesQuery = sprintf($Query, $this->_Customerno, $transactionid);
                $this->_databaseManager->executeQuery($vehiclesQuery);
                if ($this->_databaseManager->get_rowCount() > 0) {
                    while ($row = $this->_databaseManager->get_nextRow()) {
                        if ($row['invoice_file_name'] != "") {
                            $file_name_array[] = $row['invoice_file_name'];
                        }
                    }
                }
                if (count($file_name_array) > 0) {
                    $Query1 = "UPDATE maintenance set invoice_file_name='" . implode(',', $file_name_array) . "' WHERE maintenance.customerno = %d AND id = %d ";
                    $vehiclesQuery = sprintf($Query1, $this->_Customerno, $transactionid);
                    $this->_databaseManager->executeQuery($vehiclesQuery);
                } else {
                    return 0;
                }
            } else {
// upload quotation file
                $Query = "SELECT file_name  FROM maintenance WHERE maintenance.customerno = %d AND id = %d AND maintenance.isdeleted=0 ";
                $vehiclesQuery = sprintf($Query, $this->_Customerno, $transactionid);
                $this->_databaseManager->executeQuery($vehiclesQuery);
                if ($this->_databaseManager->get_rowCount() > 0) {
                    while ($row = $this->_databaseManager->get_nextRow()) {
                        if ($row['file_name'] != "") {
                            $file_name_array[] = $row['file_name'];
                        }
                    }
                }
                if (count($file_name_array) > 0) {
                    $Query1 = "UPDATE maintenance set file_name='" . implode(',', $file_name_array) . "' WHERE maintenance.customerno = %d AND id = %d ";
                    $vehiclesQuery = sprintf($Query1, $this->_Customerno, $transactionid);
                    $this->_databaseManager->executeQuery($vehiclesQuery);
                } else {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }

    public function make_transaction($form, $userid) {
        $today = date('Y-m-d H:i:s');
        $todaytime = date('Y-m-d h:i:s');
        if (isset($form['totalinv']) && isset($form['tax'])) {
            $inv = $form['totalinv'] + $form['tax'];
        }
        $category_id = isset($form['category_id']) ? $form['category_id'] : 0;
        $statusid = isset($form['status']) ? $form['status'] : 7;
        if ($_SESSION['use_hierarchy'] == '1') {
            $roleid = $this->get_approvarid_for_transaction($category_id
                , isset($form['vehicle_id']) ? $form['vehicle_id'] : 0
                , isset($form['amount_quote']) ? $form['amount_quote'] : 0
                , isset($form['meter_reading']) ? $form['meter_reading'] : 0
                , isset($form['tyre_list']) ? $form['tyre_list'] : 0
                , $statusid
            );
        } else {
            $roleid = 5;
        }
        $form_meterreading = isset($form['meter_reading']) ? $form['meter_reading'] : 0;
        $form_dealerid = isset($form['dealerid']) ? $form['dealerid'] : 0;
        $form_vehicleid = isset($form['vehicle_id']) ? $form['vehicle_id'] : 0;
        $form_note_batt = isset($form['note_batt']) ? $form['note_batt'] : '';
        $form_newtyre_list = isset($form['newtyre_list']) ? $form['newtyre_list'] : '';
        if ($statusid == 10) {
            $inv_fname = isset($form['fname']) ? $form['fname'] : '';
            $sent_finalpay = 1;
            $form_fname = '';
        } else {
            $form_fname = isset($form['fname']) ? $form['fname'] : '';
            $sent_finalpay = 0;
            $inv_fname = '';
        }
        $behalfid = isset($form['behalfid']) ? $form['behalfid'] : 0;
        $form_amount_quote = isset($form['amount_quote']) ? $form['amount_quote'] : '0';
        $form_tax = isset($form['tax']) ? $form['tax'] : 0;
        $tyre_repair = isset($form['tyre_repair']) ? $form['tyre_repair'] : 0;
        $form_old_battno = isset($form['old_battno']) ? $form['old_battno'] : '';
        $form_new_battno = isset($form['new_battno']) ? $form['new_battno'] : '';
        $vehindate = isset($form['vehindate']) && $form['vehindate'] != '' ? date("Y-m-d", strtotime($form['vehindate'])) : '';
        $vehoutdate = isset($form['vehoutdate']) && $form['vehoutdate'] != '' ? date("Y-m-d", strtotime($form['vehoutdate'])) : '';
        $invoiceno = isset($form['invoiceno']) ? GetSafeValueString($form['invoiceno'], "string") : '';
        $repair_invamt = isset($inv) ? $inv : '';
        $invoiceamt = isset($form['invoiceamt']) && $form['invoiceamt'] != '' ? GetSafeValueString($form['invoiceamt'], "string") : $repair_invamt;
        $invoicedate = isset($form['invoicedate']) && $form['invoicedate'] != '' ? date("Y-m-d", strtotime($form['invoicedate'])) : '';
        if ($roleid > 0) {
// battery transaction
            if ($category_id == 0) {
//print_r($form);
                // battery transaction
                $Query = "INSERT INTO `maintenance` ("
                    . "timestamp,submission_date,meter_reading,dealer_id"
                    . ",vehicleid,notes,statusid,category"
                    . ",file_name,amount_quote,customerno"
                    . ",userid,behalfid,roleid,invoice_amount,tax"
                    . ",vehicle_in_date,vehicle_out_date,invoice_date,invoice_no"
                    . ",is_sentfinalpayment,battery_srno) "
                    . "VALUES ("
                    . "'%s','%s',%d,%d"
                    . ",%d,'%s','%d','%d'"
                    . ",'%s','%s','%d'"
                    . ",'%d','%d','%d','%s','%s'"
                    . ",'%s','%s','%s','%s'"
                    . ",%d,'%s')";
                $SQL = sprintf($Query
                    , Sanitise::DateTime($today)
                    , Sanitise::DateTime($today)
                    , Sanitise::Long($form_meterreading)
                    , Sanitise::Long($form_dealerid)
                    , Sanitise::Long($form_vehicleid)
                    , Sanitise::String($form_note_batt)
                    , $statusid
                    , $category_id
                    , Sanitise::String($form_fname)
                    , Sanitise::String($form_amount_quote)
                    , $this->_Customerno
                    , $userid
                    , $behalfid
                    , $roleid
                    , Sanitise::String($invoiceamt)
                    , Sanitise::String($form_tax)
                    , Sanitise::String($vehindate)
                    , Sanitise::String($vehoutdate)
                    , Sanitise::String($invoicedate)
                    , Sanitise::String($invoiceno)
                    , $sent_finalpay
                    , Sanitise::String($form_new_battno)
                );
                $this->_databaseManager->executeQuery($SQL);
                $mainid = $this->_databaseManager->get_insertedId();
                $Query = "INSERT INTO `maintenance_history` "
                    . "(timestamp,submission_date,meter_reading,dealer_id"
                    . ",vehicleid,notes,statusid,category"
                    . ",file_name,amount_quote,customerno"
                    . ",userid,behalfid,roleid,invoice_amount,tax,maintananceid"
                    . ",vehicle_in_date,vehicle_out_date,invoice_date,invoice_no,is_sentfinalpayment,battery_srno) "
                    . "VALUES "
                    . "('%s','%s',%d,%d"
                    . ",%d,'%s',%d,%d"
                    . ",'%s','%s','%d'"
                    . ",'%d','%d','%d','%s','%s',%d"
                    . ",'%s','%s','%s','%s',%d,'%s')";
                $SQL = sprintf($Query
                    , Sanitise::DateTime($today)
                    , Sanitise::DateTime($today)
                    , Sanitise::Long($form_meterreading)
                    , Sanitise::Long($form_dealerid)
                    , Sanitise::Long($form_vehicleid)
                    , Sanitise::String($form_note_batt)
                    , $statusid
                    , $category_id
                    , Sanitise::String($form_fname)
                    , Sanitise::String($form_amount_quote)
                    , $this->_Customerno
                    , $userid
                    , $behalfid
                    , $roleid
                    , Sanitise::String($invoiceamt)
                    , Sanitise::String($form_tax)
                    , $mainid
                    , Sanitise::String($vehindate)
                    , Sanitise::String($vehoutdate)
                    , Sanitise::String($invoicedate)
                    , Sanitise::String($invoiceno)
                    , $sent_finalpay
                    , Sanitise::String($form_old_battno)
                );
                $this->_databaseManager->executeQuery($SQL);
//if exists update else insert in maintenance_mapbattery-table
                $bquery2 = "select * from maintenance_mapbattery where vehicleid = " . $form['vehicle_id'] . " AND customerno=" . $this->_Customerno . " AND isdeleted=0";
                $bsql2 = sprintf($bquery2);
                $this->_databaseManager->executeQuery($bsql2);
                if ($this->_databaseManager->get_rowCount() > 0) {
                    $bquery3 = "update maintenance_mapbattery set batt_serialno='" . $form_new_battno . "', installedon='" . $today . "', updatedby=" . $userid . ", updatedon='" . $todaytime . "' where vehicleid = " . $form['vehicle_id'] . " AND customerno =" . $this->_Customerno;
                    $BSQL3 = sprintf($bquery3);
                    $this->_databaseManager->executeQuery($BSQL3);
                } else {
                    $bquery3 = " INSERT INTO maintenance_mapbattery (vehicleid, customerno,batt_serialno,installedon,createdby,createdon,isdeleted) "
                        . " VALUES (%d,%d,'%s','%s',%d,'%s','%d')";
                    $bsql3 = sprintf($bquery3, Sanitise::String($form['vehicle_id']), Sanitise::String($this->_Customerno), Sanitise::String($form_new_battno), Sanitise::String($today), Sanitise::String($userid), Sanitise::String($todaytime), 0
                    );
                    $this->_databaseManager->executeQuery($bsql3);
                }
            }
// battery transaction ends
            if ($category_id == 1 && $tyre_repair != 0) {
// tyre transaction
                if ($tyre_repair != 1) {
                    $form_newtyre_list = $form['oldtyre_list'];
                    //$form['newtyre_list'] = $form['oldtyre_list'];
                }
                $Query = "INSERT INTO `maintenance` ("
                    . "timestamp, submission_date,meter_reading,dealer_id"
                    . ",vehicleid,notes,statusid,category"
                    . ",file_name,amount_quote,customerno"
                    . ",userid,behalfid,roleid,tyre_type"
                    . ",invoice_amount,tax,vehicle_in_date,vehicle_out_date"
                    . ",invoice_date,invoice_no,is_sentfinalpayment"
                    . ") "
                    . "VALUES ("
                    . "'%s','%s',%d,%d"
                    . ",%d,'%s','%d','1'"
                    . ",'%s','%s','%d'"
                    . ",'%d','%d','%d','%s'"
                    . ",'%s',%s,'%s','%s'"
                    . ",'%s','%s',%d"
                    . ")";
                $SQL = sprintf($Query
                    , Sanitise::DateTime($today), Sanitise::DateTime($today), Sanitise::Long($form_meterreading), Sanitise::Long($form_dealerid)
                    , Sanitise::Long($form['vehicle_id']), Sanitise::String($form['note_batt']), $statusid
                    , Sanitise::String($form_fname), Sanitise::String($form['amount_quote']), $this->_Customerno
                    , $userid, $behalfid, $roleid, Sanitise::String($form_newtyre_list)
                    , Sanitise::String($invoiceamt), Sanitise::String($form_tax), Sanitise::String($vehindate), Sanitise::String($vehoutdate)
                    , Sanitise::String($invoicedate), Sanitise::String($invoiceno), $sent_finalpay
                );
                $this->_databaseManager->executeQuery($SQL);
                $mainid = $this->_databaseManager->get_insertedId();
                $Query = "INSERT INTO `maintenance_history` ("
                    . "timestamp, submission_date, meter_reading,dealer_id"
                    . ",vehicleid,notes,statusid,category"
                    . ",file_name,amount_quote,customerno"
                    . ",userid,behalfid,roleid,maintananceid,tyre_type"
                    . ",invoice_amount,tax,vehicle_in_date,vehicle_out_date"
                    . ",invoice_date,invoice_no,is_sentfinalpayment) "
                    . "VALUES ("
                    . "'%s','%s',%d,%d"
                    . ",%d,'%s','%d','1'"
                    . ",'%s','%s','%d'"
                    . ",'%d','%d','%d','%d','%s'"
                    . ",'%s','%s','%s','%s'"
                    . ",'%s','%s',%d"
                    . ")";
                $SQL = sprintf($Query
                    , Sanitise::DateTime($today), Sanitise::DateTime($today), Sanitise::Long($form_meterreading), Sanitise::Long($form_dealerid)
                    , Sanitise::Long($form['vehicle_id']), Sanitise::String($form['note_batt']), $statusid
                    , Sanitise::String($form_fname), Sanitise::String($form['amount_quote']), $this->_Customerno
                    , $userid, $behalfid, $roleid, $mainid, Sanitise::String($form['oldtyre_list'])
                    , Sanitise::String($invoiceamt), Sanitise::String($form_tax), Sanitise::String($vehindate), Sanitise::String($vehoutdate)
                    , Sanitise::String($invoicedate), Sanitise::String($invoiceno), $sent_finalpay);
                $this->_databaseManager->executeQuery($SQL);
// adding tyre repair type
                $Query3 = "INSERT INTO `maintenance_tyre_repair_mapping` (tyrerepairid,maintenanceid,customerno,createdby,createdon,updatedby,updatedon) VALUES (%d,%d,%d,%d,'%s',%d,'%s')";
                $SQL3 = sprintf($Query3, $tyre_repair, $mainid, $this->_Customerno, $userid, Sanitise::DateTime($today), $userid, Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($SQL3);
                if ($tyre_repair == 1) {
                    $tyrelistnew = "";
                    $newtyrelist_srno = "";
                    $stringtyre = "";
                    $newtyrelist_srno = $form['newtyre_tyreid_srno'];
                    $tyrelistnew = explode(",", $newtyrelist_srno);
                    if (count($tyrelistnew) > 0) {
                        for ($i = 0; count($tyrelistnew) > $i; $i++) {
                            $stringtyre = explode("$", $tyrelistnew[$i]);
                            $customerno = $this->_Customerno;
                            $Query4 = "select * from maintenance_maptyre where vehicleid = " . $form['vehicle_id'] . " AND tyreid=" . $stringtyre[0] . " AND isdeleted=0";
                            $SQL4 = sprintf($Query4);
                            $this->_databaseManager->executeQuery($SQL4);
                            if ($this->_databaseManager->get_rowCount() > 0) {
                                $Query5 = "update maintenance_maptyre set serialno='" . $stringtyre[1] . "', installedon='" . $today . "', updatedby=" . $userid . ", updatedon='" . $todaytime . "' where vehicleid = " . $form['vehicle_id'] . " AND tyreid =" . $stringtyre[0];
                                $SQL5 = sprintf($Query5);
                                $this->_databaseManager->executeQuery($SQL5);
                            } else {
                                $Query6 = " INSERT INTO maintenance_maptyre (vehicleid, customerno, tyreid, serialno,installedon,createdby,createdon,isdeleted) "
                                    . " VALUES (%d,%d,%d,'%s','%s',%d,'%s','%d')";
                                $SQL6 = sprintf($Query6, Sanitise::String($form['vehicle_id']), Sanitise::String($customerno), Sanitise::String($stringtyre[0]), Sanitise::String($stringtyre[1]), Sanitise::String($today), Sanitise::String($userid), Sanitise::String($todaytime), 0
                                );
                                $this->_databaseManager->executeQuery($SQL6);
                            }
                        }
                    }
                }
            }
//tyre transaction ends
            if ($category_id == 2 || $category_id == 3) {
                // repair/service transaction
                $Query = "INSERT INTO `maintenance` (timestamp, submission_date,meter_reading,dealer_id,vehicleid,notes,statusid,category"
                    . ",file_name,amount_quote,customerno,userid,behalfid,roleid,invoice_amount,tax"
                    . ",vehicle_in_date,vehicle_out_date,invoice_date,invoice_no,is_sentfinalpayment) "
                    . "VALUES ('%s','%s',%d,%d,%d,'%s','%d','%d'"
                    . ",'%s','%s','%d','%d','%d','%d', '%s','%s'"
                    . ",'%s','%s','%s','%s',%d)";
                $SQL = sprintf($Query, Sanitise::DateTime($today), Sanitise::DateTime($today), Sanitise::Long($form_meterreading), Sanitise::Long($form_dealerid), Sanitise::Long($form['vehicle_id']), Sanitise::String($form['note_batt']), $statusid, $category_id
                    , Sanitise::String($form_fname), Sanitise::String($form['amount_quote']), $this->_Customerno, $userid, $behalfid, $roleid, Sanitise::String($invoiceamt), Sanitise::String($form['tax'])
                    , Sanitise::String($vehindate), Sanitise::String($vehoutdate), Sanitise::String($invoicedate), Sanitise::String($invoiceno), $sent_finalpay);
                $this->_databaseManager->executeQuery($SQL);
                $mainid = $this->_databaseManager->get_insertedId();
                $Query = "INSERT INTO `maintenance_history` "
                    . "(timestamp, submission_date,meter_reading"
                    . ",dealer_id,vehicleid,notes,statusid"
                    . ",category,file_name,amount_quote"
                    . ",customerno,userid,behalfid,roleid,maintananceid"
                    . ",invoice_amount,tax"
                    . ",vehicle_in_date,vehicle_out_date,invoice_date,invoice_no,is_sentfinalpayment)"
                    . " VALUES "
                    . "('%s','%s',%d"
                    . ",%d,%d,'%s','%d'"
                    . ",'%d','%s','%s'"
                    . ",'%d','%d','%d','%d','%d'"
                    . ",'%s','%s'"
                    . ",'%s','%s','%s','%s',%d)";
                $SQL = sprintf($Query
                    , Sanitise::DateTime($today), Sanitise::DateTime($today), Sanitise::Long($form_meterreading)
                    , Sanitise::Long($form_dealerid), Sanitise::Long($form['vehicle_id']), Sanitise::String($form['note_batt'])
                    , $statusid, $category_id, Sanitise::String($form_fname), Sanitise::String($form['amount_quote'])
                    , $this->_Customerno, $userid, $behalfid, $roleid, $mainid
                    , Sanitise::String($invoiceamt), Sanitise::String($form['tax'])
                    , Sanitise::String($vehindate), Sanitise::String($vehoutdate), Sanitise::String($invoicedate), Sanitise::String($invoiceno), $sent_finalpay);
                $this->_databaseManager->executeQuery($SQL);
                // adding task or parts
                $parts = "";
                $accessory_list = explode(",", $_GET['parts_select_array1']);
                if (!empty($_GET['parts_select_array1'])) {
                    $total_amt = 0;
                    $tax = 0;
                    $disc = 0;
                    $tot = 0;
                    foreach ($accessory_list as $this_acc) {
                        $this_acc_details = explode("-", $this_acc);
                        $tot = $this_acc_details[1] * $this_acc_details[2];
                        $disc = $this_acc_details[3] * $this_acc_details[2];
                        $total_amt = $this_acc_details[4];
//$total_amt1 = ($tot - $disc);
                        // $total_amt = $total_amt1 + $tax;
                        $Query = "INSERT INTO `maintenance_parts` (mid,partid,qty,amount,tax_amount,discount,total,flag) VALUES (%d,%d,'%s','%s','%s','%s','%s',1)";
                        $SQL = sprintf($Query, Sanitise::Long($mainid), $this_acc_details[0], Sanitise::String($this_acc_details[2]), Sanitise::String($this_acc_details[1]), Sanitise::String($this_acc_details[4]), Sanitise::String($this_acc_details[3]), Sanitise::String($total_amt));
                        $this->_databaseManager->executeQuery($SQL);
                        $parts .= $this_acc_details[0] . ",";
                    }
                }
                $tasks = "";
                $tot = 0;
                $total_amt = 0;
                $tax = 0;
                $disc = 0;
                $accessory_list1 = explode(",", $_GET['tasks_select_array1']);
                if (!empty($_GET['tasks_select_array1'])) {
                    foreach ($accessory_list1 as $this_acc) {
                        $this_acc_details = explode("-", $this_acc);
                        $tot = $this_acc_details[1] * $this_acc_details[2];
                        $disc = $this_acc_details[3] * $this_acc_details[2];
                        $total_amt = $this_acc_details[4];
                        $Query = "INSERT INTO `maintenance_tasks` (mid,partid,qty,amount,tax_amount,discount,total,flag) VALUES (%d,%d,%s,'%s',%s,'%s','%s',2)";
                        $SQL = sprintf($Query, Sanitise::Long($mainid), $this_acc_details[0], Sanitise::String($this_acc_details[2]), Sanitise::String($this_acc_details[1]), Sanitise::String($this_acc_details[4]), Sanitise::String($this_acc_details[3]), Sanitise::String($total_amt));
                        $this->_databaseManager->executeQuery($SQL);
                        $tasks .= $this_acc_details[0] . ",";
                    }
                }
            }
// repair/service transaction end
            if ($category_id == 5) {
// accessory transaction
                $Query = "INSERT INTO `maintenance` (timestamp, submission_date,meter_reading,dealer_id,notes,vehicleid,statusid,category"
                    . ",file_name,amount_quote,customerno,userid,behalfid,roleid,invoice_amount,tax"
                    . ",vehicle_in_date,vehicle_out_date,invoice_date,invoice_no,is_sentfinalpayment) "
                    . "VALUES ('%s','%s',%d,%d,'%s',%d,%d,%d "
                    . ",'%s','%s','%d','%d','%d','%d','%s','%s'"
                    . ",'%s','%s','%s','%s',%d)";
                $SQL = sprintf($Query, Sanitise::DateTime($today), Sanitise::DateTime($today), Sanitise::Long($form_meterreading), Sanitise::Long($form_dealerid), Sanitise::String($form['note_batt']), Sanitise::Long($form['vehicle_id']), $statusid, $category_id
                    , Sanitise::String($form_fname), Sanitise::String($form_amount_quote), $this->_Customerno, $userid, $behalfid, $roleid, Sanitise::String($invoiceamt), Sanitise::String($form_tax)
                    , Sanitise::String($vehindate), Sanitise::String($vehoutdate), Sanitise::String($invoicedate), Sanitise::String($invoiceno), $sent_finalpay);
                $this->_databaseManager->executeQuery($SQL);
                $mainid = $this->_databaseManager->get_insertedId();
                $Query = "INSERT INTO `maintenance_history` (timestamp, submission_date,meter_reading,dealer_id,notes,vehicleid,statusid,category"
                    . ",file_name,amount_quote,customerno,userid,behalfid,roleid,maintananceid,invoice_amount,tax"
                    . ",vehicle_in_date,vehicle_out_date,invoice_date,invoice_no,is_sentfinalpayment) "
                    . "VALUES ('%s','%s',%d,%d,'%s',%d,%d,%d"
                    . ",'%s','%s','%d','%d','%d','%d','%d','%s','%s'"
                    . ",'%s','%s','%s','%s',%d)";
                $SQL = sprintf($Query, Sanitise::DateTime($today), Sanitise::DateTime($today), Sanitise::Long($form_meterreading), Sanitise::Long($form_dealerid), Sanitise::String($form['note_batt']), Sanitise::Long($form['vehicle_id']), $statusid, $category_id
                    , Sanitise::String($form_fname), Sanitise::String($form_amount_quote), $this->_Customerno, $userid, $behalfid, $roleid, $mainid, Sanitise::String($invoiceamt), Sanitise::String($form_tax)
                    , Sanitise::String($vehindate), Sanitise::String($vehoutdate), Sanitise::String($invoicedate), Sanitise::String($invoiceno), $sent_finalpay);
                $this->_databaseManager->executeQuery($SQL);
                // accessory list
                $accessory_list = explode(",", $_GET['accessory_list']);
                foreach ($accessory_list as $this_acc) {
                    $this_acc_details = explode("-", $this_acc);
                    $tot = $this_acc_details[1] / $this_acc_details[2];
                    $Query = "INSERT INTO `accessory_map` (timestamp,customerno,userid,isdeleted,cost,amount,quantity,accessoryid,maintenanceid) VALUES ('%s',%d,%d,'0',%d,%d,%d,%d,%d)";
                    $SQL = sprintf($Query, Sanitise::DateTime($today), $this->_Customerno, $userid, $this_acc_details[1], $tot, $this_acc_details[2], $this_acc_details[0], $mainid);
                    $this->_databaseManager->executeQuery($SQL);
                }
            }
// accessory transaction ends
            return $mainid;
        } else {
            return 0;
        }
    }

    public function maintenance_percent($vehicleid) {
// type 0 for battery
        $roleid = 0;
        $invoice_total = 0;
        $cost_of_capitalisation = 0;
        $last_maintanance = "";
        $expected_growth = "";
        $quotation_amount = 0;
// invoice total
        $Query = "SELECT sum(invoice_amount)as invoice_amount_total
        FROM maintenance WHERE maintenance.customerno = %d AND vehicleid=" . $vehicleid . "
        and statusid IN (2,4) AND maintenance.isdeleted=0  group by vehicleid ";
        $vehiclesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $invoice_total = $row['invoice_amount_total'];
            }
        }
// cost of capitalisation
        $Query = "SELECT cost as cost_of_capitalisation FROM capitalization WHERE capitalization.customerno = %d AND vehicleid=" . $vehicleid . " ";
        $vehiclesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $cost_of_capitalisation = $row['cost_of_capitalisation'];
            }
        }
        if ($cost_of_capitalisation != '' && $cost_of_capitalisation != 0) {
            $maintenance_percent = (($invoice_total / $cost_of_capitalisation) * 100);
            $expected_growth = (($invoice_total + $quotation_amount) / $cost_of_capitalisation) * 100;
        } else {
            $maintenance_percent = 0;
        }
        $ret_arr = array();
        $ret_arr['maintenance_percent'] = $maintenance_percent;
        $ret_arr['expected_growth'] = $expected_growth;
        return $ret_arr;
    }

    public function get_approvarid_for_transaction($type, $vehicleid, $quotation_amount, $current_reading, $tyre_list, $statusid = null) {
        $masterRoleId = 1;
        $stateRoleId = 2;
        $zoneRoleId = 3;
        $regionRoleId = 4;
        switch ($_SESSION['customerno']) {
            case 63:
                $masterRoleId = 28;
                $zoneRoleId = 30;
                $regionRoleId = 31;
                break;
            case 64:
                $masterRoleId = 33;
                $zoneRoleId = 35;
                $regionRoleId = 36;
                $cityRoleId = $masterRoleId;
                break;
            case 118:
                $masterRoleId = 18; // leader
                $stateRoleId = 19; //Sr Ops Manager
                $zoneRoleId = 20;
                $regionRoleId = 21;
                $accountRoleId = 42;
                $cityRoleId = 23;
                break;
            case 167:
                $masterRoleId = 24;
                $zoneRoleId = 26;
                $regionRoleId = 27;
                break;
            default:
                $masterRoleId = 1;
                $zoneRoleId = 3;
                $regionRoleId = 4;
                break;
        }
        $roleid = 0;
        $invoice_total = 0;
        $cost_of_capitalisation = 0;
        $last_maintanance = "";
        if ($statusid != 7 || $statusid == null) {
// invoice total
            $Query = "SELECT sum(invoice_amount) as invoice_amount_total
        FROM maintenance WHERE maintenance.customerno = %d AND vehicleid=" . $vehicleid . "
        and statusid IN (2,4) AND maintenance.isdeleted=0 group by vehicleid ";
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
            $this->_databaseManager->executeQuery($vehiclesQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()) {
                    $invoice_total = $row['invoice_amount_total'];
                }
            }
// cost of capitalisation
            $Query = "SELECT cost as cost_of_capitalisation FROM capitalization WHERE capitalization.customerno = %d AND vehicleid=" . $vehicleid . " ";
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
            $this->_databaseManager->executeQuery($vehiclesQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()) {
                    $cost_of_capitalisation = $row['cost_of_capitalisation'];
                }
            }
// avoid devision by zero
            if ($type == 5 || $type == 4) {
                //$roleid = $masterRoleId;
                if ($quotation_amount > 2500) {
                    $roleid = $masterRoleId;
                } elseif ($quotation_amount <= 2500) {
                    $roleid = $stateRoleId;
                }
            } elseif ($cost_of_capitalisation > 0 && (($invoice_total + $quotation_amount) / $cost_of_capitalisation) * 100 > 45) {
// condition for cost% greater > 45 %
                $roleid = $masterRoleId;
            } else {
// condition for battery
                if ($type == 0) {
                    $Query = "SELECT  DATEDIFF(CURDATE(),maintenance.maintenance_date) as last_maintanance
                FROM maintenance WHERE maintenance.customerno = %d AND vehicleid=" . $vehicleid . " and category=0
                and maintenance.isdeleted=0 AND statusid IN (6,8,10,13,14)
                order by maintenance_date desc LIMIT 1 ";
                    $vehiclesQuery = sprintf($Query, $this->_Customerno);
                    $this->_databaseManager->executeQuery($vehiclesQuery);
                    if ($this->_databaseManager->get_rowCount() > 0) {
                        while ($row = $this->_databaseManager->get_nextRow()) {
                            $last_maintanance = $row['last_maintanance'];
                        }
                    } else {
                        $Query = "SELECT  DATEDIFF(CURDATE(),vehicle.purchase_date) as last_maintanance
                    FROM vehicle WHERE vehicle.customerno = %d AND vehicleid=" . $vehicleid . "  LIMIT 1 ";
                        $vehiclesQuery = sprintf($Query, $this->_Customerno);
                        $this->_databaseManager->executeQuery($vehiclesQuery);
                        while ($row = $this->_databaseManager->get_nextRow()) {
                            $last_maintanance = $row['last_maintanance'];
                        }
                    }
// date diff in days $last_maintanance
                    $today = date("Y-m-d");
                    $months15_today = date("Y-m-d", strtotime("-15 months"));
                    $months18_today = date("Y-m-d", strtotime("-18 months"));
                    $days_between_15 = $this->howDays($months15_today, $today);
                    $days_between_18 = $this->howDays($months18_today, $today);
                    if ($_SESSION['customerno'] == 118) {
                        if ($quotation_amount > 2500) {
                            $roleid = $masterRoleId;
                        } elseif ($quotation_amount <= 2500) {
                            $roleid = $stateRoleId;
                        } elseif ($last_maintanance < $days_between_15) {
// fifteen months
                            $roleid = $masterRoleId;
                        } else {
                            $roleid = $stateRoleId;
                        }
                    } else {
                        if ($last_maintanance < $days_between_15) {
// fifteen months
                            $roleid = $masterRoleId;
                        } elseif ($last_maintanance > $days_between_15 && $last_maintanance < $days_between_18) {
// 18 months validation
                            $roleid = $zoneRoleId;
                        } elseif ($last_maintanance > $days_between_18) {
// 18 months validation
                            $roleid = $regionRoleId;
                        }
                    }
// calculation of date in months
                }
// codition for battery ends
                if ($type == 1) {
// Tyre Replacement Starts
                    // Calculation for Previous Meter Reading
                    $prev_meter_reading = 0;
                    $tyre_list_array = explode(",", $tyre_list);
                    $maintenance_tyre_success = false;
                    $Query = "SELECT tyre_type,meter_reading FROM maintenance WHERE maintenance.customerno = %d AND vehicleid=" . $vehicleid . "
                AND statusid IN (6,8,10,13,14) AND maintenance.isdeleted=0 AND category = 1 ORDER BY maintenance_date DESC";
                    $vehiclesQuery = sprintf($Query, $this->_Customerno);
                    $this->_databaseManager->executeQuery($vehiclesQuery);
                    if ($this->_databaseManager->get_rowCount() > 0) {
                        while ($row = $this->_databaseManager->get_nextRow()) {
                            $prev_tyre_list = $row['tyre_type'];
                            $prev_tyre_list_array = explode(",", $prev_tyre_list);
                            $common_elements = array_intersect($tyre_list_array, $prev_tyre_list_array);
                            if (count($common_elements) != 0) {
                                $maintenance_tyre_success = true;
                                $prev_meter_reading = $row["meter_reading"];
                            }
                        }
                    }
                    if ($maintenance_tyre_success == false) {
                        $Query = "SELECT  start_meter_reading
                    FROM vehicle WHERE vehicle.customerno = %d AND vehicleid=" . $vehicleid . "  LIMIT 1 ";
                        $vehiclesQuery = sprintf($Query, $this->_Customerno);
                        $this->_databaseManager->executeQuery($vehiclesQuery);
                        while ($row = $this->_databaseManager->get_nextRow()) {
                            $prev_meter_reading = $row['start_meter_reading'];
                        }
                    }
                    if ($_SESSION['customerno'] == 118) {
                        if ($quotation_amount > 2500) {
                            $roleid = $masterRoleId;
                        } elseif ($quotation_amount <= 2500) {
                            $roleid = $stateRoleId;
                        } elseif (($current_reading - $prev_meter_reading) < 40000) {
                            $roleid = $masterRoleId;
                        } else {
                            $roleid = $stateRoleId;
                        }
                    } else {
                        if (($current_reading - $prev_meter_reading) < 40000) {
                            $roleid = $masterRoleId;
                        } elseif (($current_reading - $prev_meter_reading) < 50000 && ($current_reading - $prev_meter_reading) > 40000) {
                            $roleid = $zoneRoleId;
                        } else {
                            $roleid = $regionRoleId;
                        }
                    }
// Tyre Replacement Ends
                }
                if ($type == 2) {
// repair condition Starts
                    if ($_SESSION['customerno'] == 118) {
                        if ($quotation_amount > 2500) {
                            $roleid = $masterRoleId;
                        } elseif ($quotation_amount <= 2500) {
                            $roleid = $stateRoleId;
                        }
                    } else {
                        if ($quotation_amount > 15000) {
                            $roleid = $masterRoleId;
                        } elseif ($quotation_amount > 10000 && $quotation_amount < 15000) {
                            $roleid = $zoneRoleId;
                        } else {
                            $roleid = $regionRoleId;
                        }
                    }
// repair condition Ends
                }
// repair condition ends
                // condition of tyre
                if ($type == 3) {
// Service Condition Starts
                    // Calculation for Previous Meter Reading
                    $prev_meter_reading = 0;
                    $maintenance_meter_success = false;
                    $Query = "SELECT meter_reading FROM maintenance WHERE maintenance.customerno = %d AND vehicleid=" . $vehicleid . "
                AND statusid IN (6,8,10,13,14) AND maintenance.isdeleted=0 AND category = 3 ORDER BY maintenance_date DESC";
                    $vehiclesQuery = sprintf($Query, $this->_Customerno);
                    $this->_databaseManager->executeQuery($vehiclesQuery);
                    if ($this->_databaseManager->get_rowCount() > 0) {
                        while ($row = $this->_databaseManager->get_nextRow()) {
                            $prev_meter_reading = $row["meter_reading"];
                            $maintenance_meter_success = true;
                        }
                    }
                    if ($maintenance_meter_success == false) {
                        $Query = "SELECT  start_meter_reading
                    FROM vehicle WHERE vehicle.customerno = %d AND vehicleid=" . $vehicleid . "  LIMIT 1 ";
                        $vehiclesQuery = sprintf($Query, $this->_Customerno);
                        $this->_databaseManager->executeQuery($vehiclesQuery);
                        while ($row = $this->_databaseManager->get_nextRow()) {
                            $prev_meter_reading = $row['start_meter_reading'];
                        }
                    }
// Calculation for Last Maintenance Date
                    $Query = "SELECT  DATEDIFF(CURDATE(),maintenance.maintenance_date) as last_maintanance
                FROM maintenance WHERE maintenance.customerno = %d AND vehicleid=" . $vehicleid . " and category=3
                and maintenance.isdeleted=0 AND statusid IN (6,8,10,13,14)
                order by timestamp desc LIMIT 1 ";
                    $vehiclesQuery = sprintf($Query, $this->_Customerno);
                    $this->_databaseManager->executeQuery($vehiclesQuery);
                    if ($this->_databaseManager->get_rowCount() > 0) {
                        while ($row = $this->_databaseManager->get_nextRow()) {
                            $last_maintanance = $row['last_maintanance'];
                        }
                    } else {
                        $Query = "SELECT  DATEDIFF(CURDATE(),vehicle.purchase_date) as last_maintanance
                    FROM vehicle WHERE vehicle.customerno = %d AND vehicleid=" . $vehicleid . "  LIMIT 1 ";
                        $vehiclesQuery = sprintf($Query, $this->_Customerno);
                        $this->_databaseManager->executeQuery($vehiclesQuery);
                        while ($row = $this->_databaseManager->get_nextRow()) {
                            $last_maintanance = $row['last_maintanance'];
                        }
                    }
                    $today = date("Y-m-d");
                    $months3_today = date("Y-m-d", strtotime("-3 months"));
                    $months2_today = date("Y-m-d", strtotime("-2 months"));
                    $days_between_3 = $this->howDays($months3_today, $today);
                    $days_between_2 = $this->howDays($months2_today, $today);
// Actual Condition
                    if ($_SESSION['customerno'] == 118) {
                        if ($last_maintanance > $days_between_3 || $current_reading - $prev_meter_reading > 10000) {
                            $roleid = $stateRoleId;
                        } elseif (($last_maintanance < $days_between_3 && $last_maintanance > $days_between_2) || ($current_reading - $prev_meter_reading < 10000 && $current_reading - $prev_meter_reading > 8000)) {
                            $roleid = $stateRoleId;
                        } else {
                            $roleid = $masterRoleId;
                        }
                    } else {
                        if ($last_maintanance > $days_between_3 || $current_reading - $prev_meter_reading > 10000) {
                            $roleid = $regionRoleId;
                        } elseif (($last_maintanance < $days_between_3 && $last_maintanance > $days_between_2) || ($current_reading - $prev_meter_reading < 10000 && $current_reading - $prev_meter_reading > 8000)) {
                            $roleid = $zoneRoleId;
                        } else {
                            $roleid = $masterRoleId;
                        }
                    }
// Service Condition Ends
                }
            }
        } else {
            $roleid = $cityRoleId;
        }
        return $roleid;
    }

    public function howDays($from, $to) {
        $first_date = strtotime($from);
        $second_date = strtotime($to);
        $offset = $second_date - $first_date;
        return floor($offset / 60 / 60 / 24);
    }

    public function getvehiclewithfences() {
        $vehicles = Array();
        $Query = "SELECT vehicle.vehicleid,vehicleno,fenceid FROM vehicle
        LEFT OUTER JOIN fence ON vehicle.vehicleid = fence.vehicleid
        WHERE vehicle.customerno = %d AND vehicle.isdeleted=0";
        $vehiclesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->fenceid = $row['fenceid'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function delvehicle($vehicleid, $userid) {
        $Query = "UPDATE vehicle SET isdeleted=1,uid=0, userid=%d WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE driver SET vehicleid=0, userid=%d WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE fenceman SET isdeleted=1, userid=%d, vehicleid=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE unit SET vehicleid=0, userid=%d WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE checkpointmanage SET isdeleted=1, userid=%d, vehicleid=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE acalerts SET vehicleid=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE articlemanage SET vehicleid=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE checkpoint SET vehicleid=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
//$this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE ecodeman SET isdeleted=1, userid=%d, vehicleid=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE eventalerts SET vehicleid=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE ignitionalert SET vehicleid=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE batch SET isdeleted=1 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function delwarehouse($vehicleid, $userid) {
        $Query = "UPDATE warehouse SET isdeleted=1,uid=0, userid=%d WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE unit SET vehicleid=0, userid=%d WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE acalerts SET vehicleid=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE articlemanage SET vehicleid=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE ecodeman SET isdeleted=1, userid=%d, vehicleid=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE eventalerts SET vehicleid=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE ignitionalert SET vehicleid=0 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function modvehicle($vehicleno, $vehicleid, $kind, $chkids, $fences, $userid, $groupid, $overspeed_limit, $average, $fuelcapacity, $min_temp1 = 0, $max_temp1 = 0, $min_temp2 = 0, $max_temp2 = 0, $min_temp3 = 0, $max_temp3 = 0, $min_temp4 = 0, $max_temp4 = 0, $batch = NULL, $work_key = NULL, $stime = Null, $sdate = Null, $dummybatch = Null, $sel_master = Null, $hum_min = 0, $hum_max = 0, $n1 = 0, $n2 = 0, $n3 = 0, $n4 = 0, $temp1_allowance = 0, $temp2_allowance = 0, $temp3_allowance = 0, $temp4_allowance = 0, $tempObj = Null) {
        $loggedInUser = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;
        $today = date("Y-m-d H:i:s");
        $Query = "UPDATE vehicle SET vehicleno='%s',kind='%s',userid=%d,groupid=%d,overspeed_limit=%d,average=%f,fuelcapacity=%d,temp1_min=%f,temp1_max = %f,temp1_allowance = %f,temp2_min = %f,temp2_max = %f,temp2_allowance = %f,temp3_min = %f,temp3_max = %f,temp3_allowance = %f,temp4_min = %f,temp4_max = %f,temp4_allowance = %f,hum_min = %f,hum_max = %f,timestamp='%s',updatedBy=%d,updatedOn='%s', isStaticTemp1=%d, isStaticTemp2=%d, isStaticTemp3=%d, isStaticTemp4=%d  WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, $vehicleno, $kind, Sanitise::Long($userid), Sanitise::Long($groupid), Sanitise::Long($overspeed_limit), Sanitise::Float($average), Sanitise::Long($fuelcapacity), Sanitise::Float($min_temp1), Sanitise::Float($max_temp1), Sanitise::Float($temp1_allowance), Sanitise::Float($min_temp2), Sanitise::Float($max_temp2), Sanitise::Float($temp2_allowance), Sanitise::Float($min_temp3), Sanitise::Float($max_temp3), Sanitise::Float($temp3_allowance), Sanitise::Float($min_temp4), Sanitise::Float($max_temp4), Sanitise::Float($temp4_allowance), Sanitise::Float($hum_min), Sanitise::Long($hum_max), Sanitise::DateTime($today), $loggedInUser, $today, Sanitise::Long($tempObj->staticTemp1), Sanitise::Long($tempObj->staticTemp2), Sanitise::Long($tempObj->staticTemp3), Sanitise::Long($tempObj->staticTemp4), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "SELECT uid FROM vehicle WHERE vehicleid='$vehicleid'";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $uid = $row['uid'];
            }
        }
        $Query = "UPDATE unit SET n1 ='%d',n2= '%d',n3='%d',n4='%d',updatedBy=%d,updatedOn='%s' WHERE uid='%d'";
        $SQL = sprintf($Query, Sanitise::Long($n1), Sanitise::Long($n2), Sanitise::Long($n3), Sanitise::Long($n4), $loggedInUser, $today, Sanitise::Long($uid));
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE checkpointmanage SET isdeleted=1,userid=%d,updatedBy=%d,updatedOn='%s' WHERE vehicleid =%d AND customerno =%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), $loggedInUser, $today, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "INSERT INTO checkpointmanage (vehicleid,checkpointid,customerno, userid,createdBy,createdOn) VALUES (%d,%d,%d,%d,%d,'%s')";
        foreach ($chkids as $chkid) {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($chkid), $this->_Customerno, Sanitise::Long($userid), $loggedInUser, $today);
            $this->_databaseManager->executeQuery($SQL);
        }
        $Query = "UPDATE fenceman SET isdeleted=1,userid=%d,updatedBy=%d,updatedOn='%s' WHERE vehicleid =%d AND customerno =%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), $loggedInUser, $today, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "INSERT INTO fenceman (vehicleid,fenceid,customerno,userid,createdBy,createdOn) VALUES (%d,%d,%d,%d,%d,'%s')";
        foreach ($fences as $fence) {
            $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($fence), $this->_Customerno, Sanitise::Long($userid), $loggedInUser, $today);
            $this->_databaseManager->executeQuery($SQL);
        }
        if (isset($tempObj)) {
            $trcmid = '';
            //check if temprangecolormapping entry is done for the vehicle MT 18/03/2019
            $Query = "SELECT trcmid FROM tempRangeColourMapping WHERE vehicleid = %d AND isdeleted =0";
            $SQL = sprintf($Query, Sanitise::Long($vehicleid));
            $this->_databaseManager->executeQuery($SQL);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()) {
                    $trcmid = $row['trcmid'];
                }
            }
            $isdeleted = 0;
            $tempsen1 = array();
            $tempsen2 = array();
            $tempsen3 = array();
            $tempsen4 = array();
            $tempsen1 = isset($tempObj->tempSenor1) ? $tempObj->tempSenor1 : null;
            $tempsen2 = isset($tempObj->tempSenor2) ? $tempObj->tempSenor2 : null;
            $tempsen3 = isset($tempObj->tempSenor3) ? $tempObj->tempSenor3 : null;
            $tempsen4 = isset($tempObj->tempSenor4) ? $tempObj->tempSenor4 : null;
            /* Temperature Sensor 1 readings*/
            $temp1_range1_start = isset($tempsen1['min_temp_start_1']) ? $tempsen1['min_temp_start_1'] : NULL;
            $temp1_range1_end = isset($tempsen1['max_temp_end_1']) ? $tempsen1['max_temp_end_1'] : NULL;
            $temp1_range1_color = isset($tempsen1['colorpallet1']) ? $tempsen1['colorpallet1'] : NULL;
            $temp1_range2_start = isset($tempsen1['min_temp_start_2']) ? $tempsen1['min_temp_start_2'] : NULL;
            $temp1_range2_end = isset($tempsen1['max_temp_end_2']) ? $tempsen1['max_temp_end_2'] : NULL;
            $temp1_range2_color = isset($tempsen1['colorpallet2']) ? $tempsen1['colorpallet2'] : NULL;
            $temp1_range3_start = isset($tempsen1['min_temp_start_3']) ? $tempsen1['min_temp_start_3'] : NULL;
            $temp1_range3_end = isset($tempsen1['max_temp_end_3']) ? $tempsen1['max_temp_end_3'] : NULL;
            $temp1_range3_color = isset($tempsen1['colorpallet3']) ? $tempsen1['colorpallet3'] : NULL;
            $temp1_range4_start = isset($tempsen1['min_temp_start_4']) ? $tempsen1['min_temp_start_4'] : NULL;
            $temp1_range4_end = isset($tempsen1['max_temp_end_4']) ? $tempsen1['max_temp_end_4'] : NULL;
            $temp1_range4_color = isset($tempsen1['colorpallet4']) ? $tempsen1['colorpallet4'] : NULL;
            /* Temperature Sensor 2 readings*/
            $temp2_range1_start = isset($tempsen2['min_temp_start_1']) ? $tempsen2['min_temp_start_1'] : NULL;
            $temp2_range1_end = isset($tempsen2['max_temp_end_1']) ? $tempsen2['max_temp_end_1'] : NULL;
            $temp2_range1_color = isset($tempsen2['colorpallet1']) ? $tempsen2['colorpallet1'] : NULL;
            $temp2_range2_start = isset($tempsen2['min_temp_start_2']) ? $tempsen2['min_temp_start_2'] : NULL;
            $temp2_range2_end = isset($tempsen2['max_temp_end_2']) ? $tempsen2['max_temp_end_2'] : NULL;
            $temp2_range2_color = isset($tempsen2['colorpallet2']) ? $tempsen2['colorpallet2'] : NULL;
            $temp2_range3_start = isset($tempsen2['min_temp_start_3']) ? $tempsen2['min_temp_start_3'] : NULL;
            $temp2_range3_end = isset($tempsen2['max_temp_end_3']) ? $tempsen2['max_temp_end_3'] : NULL;
            $temp2_range3_color = isset($tempsen2['colorpallet3']) ? $tempsen1['colorpallet3'] : NULL;
            $temp2_range4_start = isset($tempsen2['min_temp_start_4']) ? $tempsen2['min_temp_start_4'] : NULL;
            $temp2_range4_end = isset($tempsen2['max_temp_end_4']) ? $tempsen2['max_temp_end_4'] : NULL;
            $temp2_range4_color = isset($tempsen2['colorpallet4']) ? $tempsen2['colorpallet4'] : NULL;
            /* Temperature Sensor 3 readings*/
            $temp3_range1_start = isset($tempsen3['min_temp_start_1']) ? $tempsen3['min_temp_start_1'] : NULL;
            $temp3_range1_end = isset($tempsen3['max_temp_end_1']) ? $tempsen3['max_temp_end_1'] : NULL;
            $temp3_range1_color = isset($tempsen3['colorpallet1']) ? $tempsen3['colorpallet1'] : "";
            $temp3_range2_start = isset($tempsen3['min_temp_start_2']) ? $tempsen3['min_temp_start_2'] : NULL;
            $temp3_range2_end = isset($tempsen3['max_temp_end_2']) ? $tempsen3['max_temp_end_2'] : NULL;
            $temp3_range2_color = isset($tempsen3['colorpallet2']) ? $tempsen3['colorpallet2'] : NULL;
            $temp3_range3_start = isset($tempsen3['min_temp_start_3']) ? $tempsen3['min_temp_start_3'] : NULL;
            $temp3_range3_end = isset($tempsen3['max_temp_end_3']) ? $tempsen3['max_temp_end_3'] : NULL;
            $temp3_range3_color = isset($tempsen3['colorpallet3']) ? $tempsen3['colorpallet3'] : NULL;
            $temp3_range4_start = isset($tempsen3['min_temp_start_4']) ? $tempsen3['min_temp_start_4'] : NULL;
            $temp3_range4_end = isset($tempsen3['max_temp_end_4']) ? $tempsen3['max_temp_end_4'] : NULL;
            $temp3_range4_color = isset($tempsen3['colorpallet4']) ? $tempsen3['colorpallet4'] : NULL;
            /*Temperature Sensor 4 readings*/
            $temp4_range1_start = isset($tempsen4['min_temp_start_1']) ? $tempsen4['min_temp_start_1'] : NULL;
            $temp4_range1_end = isset($tempsen4['max_temp_end_1']) ? $tempsen4['max_temp_end_1'] : NULL;
            $temp4_range1_color = isset($tempsen4['colorpallet1']) ? $tempsen4['colorpallet1'] : NULL;
            $temp4_range2_start = isset($tempsen4['min_temp_start_2']) ? $tempsen4['min_temp_start_2'] : NULL;
            $temp4_range2_end = isset($tempsen4['max_temp_end_2']) ? $tempsen4['max_temp_end_2'] : NULL;
            $temp4_range2_color = isset($tempsen4['colorpallet2']) ? $tempsen4['colorpallet2'] : NULL;
            $temp4_range3_start = isset($tempsen4['min_temp_start_3']) ? $tempsen4['min_temp_start_3'] : NULL;
            $temp4_range3_end = isset($tempsen4['max_temp_end_3']) ? $tempsen4['max_temp_end_3'] : NULL;
            $temp4_range3_color = isset($tempsen4['colorpallet3']) ? $tempsen4['colorpallet3'] : NULL;
            $temp4_range4_start = isset($tempsen4['min_temp_start_4']) ? $tempsen4['min_temp_start_4'] : NULL;
            $temp4_range4_end = isset($tempsen4['max_temp_end_4']) ? $tempsen4['max_temp_end_4'] : NULL;
            $temp4_range4_color = isset($tempsen4['colorpallet4']) ? $tempsen4['colorpallet4'] : NULL;
            if ($trcmid != "") {
                $Query = "UPDATE `tempRangeColourMapping` SET `temp1_range1_start`= %f , `temp1_range1_end` = %f, `temp1_range1_color` = '%s', `temp1_range2_start` = %f, `temp1_range2_end` = %f, `temp1_range2_color` = '%s', `temp1_range3_start` = %f, `temp1_range3_end` =%f, `temp1_range3_color`='%s', `temp1_range4_start` =%f, `temp1_range4_end` =%f, `temp1_range4_color` ='%s', `temp2_range1_start` =%f, `temp2_range1_end` =%f, `temp2_range1_color` ='%s', `temp2_range2_start` =%f, `temp2_range2_end`=%f, `temp2_range2_color`='%s', `temp2_range3_start` =%f, `temp2_range3_end` =%f, `temp2_range3_color`='%s', `temp2_range4_start` =%f, `temp2_range4_end` =%f, `temp2_range4_color` ='%s', `temp3_range1_start` =%f, `temp3_range1_end` =%f, `temp3_range1_color` ='%s', `temp3_range2_start` =%f, `temp3_range2_end` =%f, `temp3_range2_color` ='%s', `temp3_range3_start` =%f, `temp3_range3_end` =%f, `temp3_range3_color`='%s', `temp3_range4_start`=%f, `temp3_range4_end` =%f, `temp3_range4_color`='%s', `temp4_range1_start`=%f, `temp4_range1_end`=%f, `temp4_range1_color`='%s', `temp4_range2_start`=%f, `temp4_range2_end`=%f, `temp4_range2_color`='%s', `temp4_range3_start` =%f, `temp4_range3_end`=%f, `temp4_range3_color`='%s', `temp4_range4_start`=%f, `temp4_range4_end`=%f, `temp4_range4_color`='%s', `customerno`=%d, `created_by`=%d, `created_on`='%s', `updated_by`=%d, `updated_on`='%s', `isdeleted`=%d WHERE vehicleId = %d";
                $SQL = sprintf($Query, Sanitise::Float($temp1_range1_start), Sanitise::Float($temp1_range1_end), Sanitise::String($temp1_range1_color), Sanitise::Float($temp1_range2_start), Sanitise::Float($temp1_range2_end), Sanitise::String($temp1_range2_color), Sanitise::Float($temp1_range3_start), Sanitise::Float($temp1_range3_end), Sanitise::String($temp1_range3_color), Sanitise::Float($temp1_range4_start), Sanitise::Float($temp1_range4_end), Sanitise::String($temp1_range4_color), Sanitise::Float($temp2_range1_start), Sanitise::Float($temp2_range1_end), Sanitise::String($temp2_range1_color), Sanitise::Float($temp2_range2_start), Sanitise::Float($temp2_range2_end), Sanitise::String($temp2_range2_color), Sanitise::Float($temp2_range3_start), Sanitise::Float($temp2_range3_end), Sanitise::String($temp2_range3_color), Sanitise::Float($temp2_range4_start), Sanitise::Float($temp2_range4_end), Sanitise::String($temp2_range4_color), Sanitise::Float($temp3_range1_start), Sanitise::Float($temp3_range1_end), Sanitise::String($temp3_range1_color), Sanitise::Float($temp3_range2_start), Sanitise::Float($temp3_range2_end), Sanitise::String($temp3_range2_color), Sanitise::Float($temp3_range3_start), Sanitise::Float($temp3_range3_end), Sanitise::String($temp3_range3_color), Sanitise::Float($temp3_range4_start), Sanitise::Float($temp3_range4_end), Sanitise::String($temp3_range4_color), Sanitise::Float($temp4_range1_start), Sanitise::Float($temp4_range1_end), Sanitise::String($temp4_range1_color), Sanitise::Float($temp4_range2_start), Sanitise::Float($temp4_range2_end), Sanitise::String($temp4_range2_color), Sanitise::Float($temp4_range3_start), Sanitise::Float($temp4_range3_end), Sanitise::String($temp4_range3_color), Sanitise::Float($temp4_range4_start), Sanitise::Float($temp4_range4_end), Sanitise::String($temp4_range4_color), Sanitise::Long($this->_Customerno), Sanitise::Long($userid), Sanitise::String($today), Sanitise::Long($userid), Sanitise::String($today), Sanitise::Long($isdeleted), Sanitise::Long($vehicleid));
            } else {
                $Query = "INSERT INTO `tempRangeColourMapping` (`vehicleid`, `temp1_range1_start`, `temp1_range1_end`, `temp1_range1_color`, `temp1_range2_start`, `temp1_range2_end`, `temp1_range2_color`, `temp1_range3_start`, `temp1_range3_end`, `temp1_range3_color`, `temp1_range4_start`, `temp1_range4_end`, `temp1_range4_color`, `temp2_range1_start`, `temp2_range1_end`, `temp2_range1_color`, `temp2_range2_start`, `temp2_range2_end`, `temp2_range2_color`, `temp2_range3_start`, `temp2_range3_end`, `temp2_range3_color`, `temp2_range4_start`, `temp2_range4_end`, `temp2_range4_color`, `temp3_range1_start`, `temp3_range1_end`, `temp3_range1_color`, `temp3_range2_start`, `temp3_range2_end`, `temp3_range2_color`, `temp3_range3_start`, `temp3_range3_end`, `temp3_range3_color`, `temp3_range4_start`, `temp3_range4_end`, `temp3_range4_color`, `temp4_range1_start`, `temp4_range1_end`, `temp4_range1_color`, `temp4_range2_start`, `temp4_range2_end`, `temp4_range2_color`, `temp4_range3_start`, `temp4_range3_end`, `temp4_range3_color`, `temp4_range4_start`, `temp4_range4_end`, `temp4_range4_color`, `customerno`, `created_by`, `created_on`, `updated_by`, `updated_on`, `isdeleted`) VALUES(%d, %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s', %f, %f, '%s',%d, %d, '%s',%d,'%s',%d)";
                $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Float($temp1_range1_start), Sanitise::Float($temp1_range1_end), Sanitise::String($temp1_range1_color), Sanitise::Float($temp1_range2_start), Sanitise::Float($temp1_range2_end), Sanitise::String($temp1_range2_color), Sanitise::Float($temp1_range3_start), Sanitise::Float($temp1_range3_end), Sanitise::String($temp1_range3_color), Sanitise::Float($temp1_range4_start), Sanitise::Float($temp1_range4_end), Sanitise::String($temp1_range4_color), Sanitise::Float($temp2_range1_start), Sanitise::Float($temp2_range1_end), Sanitise::String($temp2_range1_color), Sanitise::Float($temp2_range2_start), Sanitise::Float($temp2_range2_end), Sanitise::String($temp2_range2_color), Sanitise::Float($temp2_range3_start), Sanitise::Float($temp2_range3_end), Sanitise::String($temp2_range3_color), Sanitise::Float($temp2_range4_start), Sanitise::Float($temp2_range4_end), Sanitise::String($temp2_range4_color), Sanitise::Float($temp3_range1_start), Sanitise::Float($temp3_range1_end), Sanitise::String($temp3_range1_color), Sanitise::Float($temp3_range2_start), Sanitise::Float($temp3_range2_end), Sanitise::String($temp3_range2_color), Sanitise::Float($temp3_range3_start), Sanitise::Float($temp3_range3_end), Sanitise::String($temp3_range3_color), Sanitise::Float($temp3_range4_start), Sanitise::Float($temp3_range4_end), Sanitise::String($temp3_range4_color), Sanitise::Float($temp4_range1_start), Sanitise::Float($temp4_range1_end), Sanitise::String($temp4_range1_color), Sanitise::Float($temp4_range2_start), Sanitise::Float($temp4_range2_end), Sanitise::String($temp4_range2_color), Sanitise::Float($temp4_range3_start), Sanitise::Float($temp4_range3_end), Sanitise::String($temp4_range3_color), Sanitise::Float($temp4_range4_start), Sanitise::Float($temp4_range4_end), Sanitise::String($temp4_range4_color), Sanitise::Long($this->_Customerno), Sanitise::Long($userid), Sanitise::String($today), Sanitise::Long($userid), Sanitise::String($today), '0');
            }
            $this->_databaseManager->executeQuery($SQL);
        }
        if ($_SESSION['customerno'] == 15 || $_SESSION['customerno'] == 21 || $_SESSION['customerno'] == 48) {
            $today = date('Y-m-d H:i:s');
            if ($sdate != '' || $stime != '') {
                $starttime = $sdate . ' ' . $stime;
                $starttime = date('Y-m-d H:i:s', strtotime($starttime));
            } else {
                $starttime = '';
            }
            $Que = "SELECT * FROM batch WHERE vehicleid = %d AND customerno= %d";
            $SQL = sprintf($Que, $vehicleid, $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);
            if ($this->_databaseManager->get_rowCount() > 0) {
                if (empty($batch) && empty($work_key)) {
                    $Query = "UPDATE batch SET batchno='%s', workkey='%s', starttime='%s', dummybatch='%s', pmid=%d, updatedon='%s',updatedBy=%d, isdeleted=1 WHERE customerno=%d AND vehicleid=%d";
                    $SQL = sprintf($Query, $batch, $work_key, $starttime, $dummybatch, $sel_master, Sanitise::DateTime($today), $loggedInUser, $this->_Customerno, $vehicleid);
                    $this->_databaseManager->executeQuery($SQL);
                } else {
                    $Query = "UPDATE batch SET batchno='%s', workkey='%s', starttime='%s', dummybatch='%s', pmid=%d, updatedon='%s',updatedBy=%d, isdeleted=0 WHERE customerno=%d AND vehicleid=%d";
                    $SQL = sprintf($Query, $batch, $work_key, $starttime, $dummybatch, $sel_master, Sanitise::DateTime($today), $loggedInUser, $this->_Customerno, $vehicleid);
                    $this->_databaseManager->executeQuery($SQL);
                }
            } else {
                $SQL = sprintf("INSERT INTO batch(vehicleid, customerno, batchno, workkey, starttime, dummybatch, pmid, addedon, isdeleted)VALUES(%d,'%s','%s','%s','%s','%s',%d,'%s',0 ,%d)", $vehicleid, $this->_Customerno, $batch, $work_key, $starttime, $dummybatch, $sel_master, $today, $loggedInUser);
                $this->_databaseManager->executeQuery($SQL);
            }
        }
    }

    public function updateGenset($vehicle) {
        $today = date("Y-m-d H:i:s");
        $Query = "UPDATE vehicle
        SET genset1 = %d
        , genset2 = %d
        , transmitter1 = %d
        , transmitter2 = %d
        , userid = %d
        WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query
            , Sanitise::Long($vehicle->gensetid1)
            , Sanitise::Long($vehicle->gensetid2)
            , Sanitise::Long($vehicle->transmitterid1)
            , Sanitise::Long($vehicle->transmitterid2)
            , Sanitise::Long($vehicle->userid)
            , Sanitise::Long($vehicle->vehicleid)
            , $this->_Customerno
        );
        $this->_databaseManager->executeQuery($SQL);
        if ($vehicle->transmitter1 != '') {
            $command1 = "WID=$vehicle->transmitter1";
        }
        if ($vehicle->transmitter2 != '') {
            $command2 = "WID1=$vehicle->transmitter2";
        }
        if (isset($command1) && $command1 != '') {
            $commandparam = $command1;
        }
        if (isset($command2) && $command2 != '') {
            $commandparam = $command2;
        }
        if (isset($command1) && isset($command2) && $command1 != '' && $command2 != '') {
            $commandparam = $command1 . ";" . $command2;
        }
        if (isset($commandparam) && $commandparam != '') {
            $updateQuery = "UPDATE unit SET setcom = 1, command='$commandparam'
            where uid = %d AND customerno = %d";
            $SQL = sprintf($updateQuery
                , Sanitise::Long($vehicle->uid)
                , $this->_Customerno
            );
            $this->_databaseManager->executeQuery($SQL);
        }
    }

    public function insertGensetLog($vehicle) {
        $today = date("Y-m-d H:i:s");
        $Query = "INSERT INTO genset_mapping_history(
        vehicleid
        ,gensetid1
        ,genset1
        ,gensetid2
        ,genset2
        ,transmitterid1
        ,transmitter1
        ,transmitterid2
        ,transmitter2
        ,customerno
        ,created_on
        ,updated_on
        ,created_by
        ,updated_by
        ,isdeleted)
        Values(%d,%d,'%s',%d,'%s',%d,'%s',%d,'%s',%d,'%s','%s',%d,%d,0)";
        $SQL = sprintf($Query
            , Sanitise::Long($vehicle->vehicleid)
            , Sanitise::Long($vehicle->gensetid1)
            , Sanitise::string($vehicle->genset1)
            , Sanitise::Long($vehicle->gensetid2)
            , Sanitise::string($vehicle->genset2)
            , Sanitise::Long($vehicle->transmitterid1)
            , Sanitise::string($vehicle->transmitter1)
            , Sanitise::Long($vehicle->transmitterid2)
            , Sanitise::string($vehicle->transmitter2)
            , $this->_Customerno
            , $today
            , $today
            , Sanitise::Long($vehicle->userid)
            , Sanitise::Long($vehicle->userid)
        );
        $this->_databaseManager->executeQuery($SQL);
    }

    public function modwarehouse($vehicleno, $vehicleid, $kind, $userid, $groupid, $overspeed_limit, $average, $fuelcapacity, $min_temp1 = 0, $max_temp1 = 0, $min_temp2 = 0, $max_temp2 = 0, $batch = NULL, $work_key = NULL, $stime = Null, $sdate = Null, $dummybatch = Null, $sel_master = Null) {
        $today = date("Y-m-d H:i:s");
        $Query = "UPDATE warehosue SET vehicleno='%s',kind='%s',userid=%d,groupid=%d,overspeed_limit=%d,average=%f,fuelcapacity=%d,temp1_min=%f,temp1_max = %f,temp2_min = %f,temp2_max = %f,timestamp='%s' WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, $vehicleno, $kind, Sanitise::Long($userid), Sanitise::Long($groupid), Sanitise::Long($overspeed_limit), Sanitise::Float($average), Sanitise::Long($fuelcapacity), Sanitise::Float($min_temp1), Sanitise::Float($max_temp1), Sanitise::Float($min_temp2), Sanitise::Float($max_temp2), Sanitise::DateTime($today), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function editvehicledata($vehicleno, $vehicleid) {
        $today = date("Y-m-d H:i:s");
        $Query = "UPDATE vehicle SET vehicleno='%s',timestamp='%s' WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, $vehicleno, Sanitise::DateTime($today), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function editdriverdata($drivername, $driverno, $vehicleid) {
        $Query = "UPDATE driver SET drivername='%s',driverphone='%s' WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, $drivername, $driverno, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function mapunit($vehicleid, $unitid, $userid) {
        $Query = 'UPDATE unit SET vehicleid=%d,userid=%d WHERE uid=%d AND customerno=%d';
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($userid), Sanitise::Long($unitid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = 'UPDATE vehicle SET uid=%d,userid=%d WHERE vehicleid=%d AND customerno=%d';
        $SQL = sprintf($Query, Sanitise::Long($unitid), Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function demapunit($vehicleid, $userid) {
        $Query = 'Update vehicle Set uid=0, userid=%d WHERE vehicleid = %d AND customerno = %d';
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = 'Update unit Set vehicleid= 0, userid=%d WHERE vehicleid = %d AND customerno = %d';
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getvehicleswithchks() {
        $vehicles = Array();
        $Query = "SELECT vehicle.vehicleid,vehicleno,checkpointid FROM vehicle
        LEFT OUTER JOIN checkpointmanage ON vehicle.vehicleid = checkpointmanage.vehicleid
        WHERE vehicle.customerno = %d AND vehicle.isdeleted=0 GROUP BY vehicle.vehicleid";
        $vehiclesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->checkpointid = $row['checkpointid'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getvehicleswithchks_ById($srhstring) {
        $vehicles = Array();
        $Query = "SELECT vehicle.vehicleid,vehicleno,checkpointid FROM vehicle
        LEFT OUTER JOIN checkpointmanage ON vehicle.vehicleid = checkpointmanage.vehicleid
        WHERE vehicle.customerno = %d AND vehicle.vehicleno LIKE '%s' AND vehicle.isdeleted=0 GROUP BY vehicle.vehicleid";
        $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->checkpointid = $row['checkpointid'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function get_all_vehiclesbyid($vehicleid) {
        $Query = 'SELECT * FROM vehicle
        inner join unit on unit.uid=vehicle.uid
        inner join devices on devices.uid=vehicle.uid
        LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid
        WHERE vehicle.vehicleid=%d and vehicle.customerno=%d AND vehicle.isdeleted=0 AND unit.trans_statusid NOT IN (10,22)';
        $vehiclesQuery = sprintf($Query, $vehicleid, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->intbatt = $row['inbatt'];
                $vehicle->gsmstrength = $row['gsmstrength'];
                $vehicle->phone = $row['simcardno'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->vehicleno = $row["vehicleno"];
                return $vehicle;
            }
        }
        return null;
    }

    public function GetFuelGauge($vehicleid) {
        $vehicles = Array();
        $tank = $this->get_Fuel_Tank($vehicleid);
        $fuel = $this->getFueledVehicle_byID($vehicleid);
        $vehicle = new VOVehicle();
        $vehicle->vehicleid = $fuel->vehicleid;
        $vehicle->unitno = $fuel->unitno;
        $vehicle->customerno = $fuel->customerno;
        $vehicle->average = $fuel->average;
        $vehicle->fuel_balance = $fuel->fuel_balance;
        $vehicle->fuelcapacity = $tank->fuelcapacity;
        $vehicle->fuel_alert_percentage = $tank->fuel_alert_percentage;
        $vehicles[] = $vehicle;
        return $vehicles;
    }

    public function GetDailyFuelReport_Data($vehicleid, $STdate, $EDdate) {
        $vehicles = Array();
        $Query = "SELECT fuelstorrage.vehicleid,fuelstorrage.fuel,fuelstorrage.addedon, vehicle.vehicleno FROM fuelstorrage
        INNER JOIN vehicle ON vehicle.vehicleid = fuelstorrage.vehicleid
        WHERE fuelstorrage.customerno=%d AND fuelstorrage.vehicleid=%d
        AND fuelstorrage.addedon BETWEEN '%s' AND '%s' order by fuelstorrage.addedon ";
        $SQL = sprintf($Query, $this->_Customerno, $vehicleid, $STdate, $EDdate);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->fuel = $row['fuel'];
                $vehicle->timestamp = $row['addedon'];
                $vehicles[] = $vehicle;
            }
        }
        return $vehicles;
    }

    public function GetDailyFuelReportAll_Data($STdate, $EDdate) {
        $vehicles = Array();
        $Query = "SELECT fuelstorrage.vehicleid,fuelstorrage.fuel,fuelstorrage.addedon, vehicle.vehicleno FROM fuelstorrage
        INNER JOIN vehicle ON vehicle.vehicleid = fuelstorrage.vehicleid
        WHERE fuelstorrage.customerno=%d AND  fuelstorrage.addedon BETWEEN '%s' AND '%s' order by fuelstorrage.addedon";
        $SQL = sprintf($Query, $this->_Customerno, $STdate, $EDdate);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->fuel = $row['fuel'];
                $vehicle->timestamp = $row['addedon'];
                $vehicles[] = $vehicle;
            }
        }
        return $vehicles;
    }

    public function get_Fuel_Tank($vehicleid) {
        $tank = Array();
        $Query = "SELECT vehicle.fuelcapacity FROM vehicle
        WHERE vehicle.customerno=%d AND vehicle.vehicleid=%d";
        $SQL = sprintf($Query, $this->_Customerno, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $fuel = new VOVehicle();
                $fuel->fuelcapacity = $row['fuelcapacity'];
                $tank[] = $fuel;
            }
        }
        return $tank;
    }

    public function get_FuelAlert() {
        $Query = "SELECT fuel_alert_percentage FROM user WHERE userid = %d";
        $SQL = sprintf($Query, $_SESSION['userid']);
        $this->_databaseManager->executeQuery($SQL);
        $row = $this->_databaseManager->get_nextRow();
        if ($row) {
            return $row['fuel_alert_percentage'];
        } else {
            return 0;
        }
    }

    public function getFueledVehicle() {
        $vehicles = Array();
        $Query = "SELECT vehicle.customerno,vehicle.vehicleid,vehicle.fuel_balance,vehicle.average,unit.unitno FROM vehicle
        INNER JOIN unit ON unit.uid = vehicle.uid
        WHERE vehicle.fuel_balance > 0 ";
        $SQL = sprintf($Query);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->customerno = $row['customerno'];
                $vehicle->average = $row['average'];
                $vehicle->fuel_balance = $row['fuel_balance'];
                $vehicles[] = $vehicle;
            }
        }
        return $vehicles;
    }

    public function getFueledVehicle_byID($vehicleid) {
        $vehicles = Array();
        $Query = "SELECT vehicle.customerno,vehicle.vehicleid,vehicle.fuel_balance,vehicle.average,unit.unitno FROM vehicle
        INNER JOIN unit ON unit.uid = vehicle.uid
        WHERE vehicle.fuel_balance > 0 AND vehicle.vehicleid = %d ";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->customerno = $row['customerno'];
                $vehicle->average = $row['average'];
                $vehicle->fuel_balance = $row['fuel_balance'];
                $vehicles[] = $vehicle;
            }
        }
        return $vehicles;
    }

    public function fuel_update($vehicleid, $fuel) {
        $Query = "UPDATE vehicle SET fuel_balance = %d WHERE vehicleid=%d";
        $SQL = sprintf($Query, $fuel, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function fuel_balance($vehicleid) {
        $Query = "SELETC fuel_balance FROM vehicle WHERE vehicleid=%d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($SQL);
        $row = $this->_databaseManager->get_nextRow();
        return $row['fuel_balance'];
    }

    public function setPUCExpiry($customerno, $userid, $vehicleid, $pucexpiry, $pucrem) {
        $timestamp = date('Y-m-d H:i:s');
        $Check = "SELECT vehicleid FROM valert WHERE customerno=%d AND vehicleid=%d";
        $CheckSQL = sprintf($Check, $customerno, $vehicleid);
        $this->_databaseManager->executeQuery($CheckSQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $Update = "UPDATE valert SET puc_expiry='%s',puc_sms_email=%d, timestamp='%s' WHERE customerno=%d AND vehicleid=%d";
            $UpdateSQL = sprintf($Update, Sanitise::DateTime($pucexpiry), $pucrem, Sanitise::DateTime($timestamp), $customerno, $vehicleid);
            if (!$this->_databaseManager->executeQuery($UpdateSQL)) {
                return $status = 'Ok';
            } else {
                return $status = "Not Ok From Update";
            }
        } else {
            $Insert = "INSERT INTO valert(vehicleid,customerno,userid,puc_expiry,puc_sms_email,timestamp)VALUES(%d,%d,%d,'%s',%d,'%s')";
            $InsertSql = sprintf($Insert, $vehicleid, $customerno, $userid, Sanitise::DateTime($pucexpiry), $pucrem, Sanitise::DateTime($timestamp));
            if (!$this->_databaseManager->executeQuery($InsertSql)) {
                return $status = 'Ok';
            } else {
                return $status = 'Not Ok From Insert ';
            }
        }
    }

    public function setREGExpiry($customerno, $userid, $vehicleid, $regexpiry, $regrem) {
        $timestamp = date('Y-m-d H:i:s');
        $Check = "SELECT vehicleid FROM valert WHERE customerno=%d AND vehicleid=%d";
        $CheckSQL = sprintf($Check, $customerno, $vehicleid);
        $this->_databaseManager->executeQuery($CheckSQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $Update = "UPDATE valert SET reg_expiry='%s',reg_sms_email=%d, timestamp='%s' WHERE customerno=%d AND vehicleid=%d";
            $UpdateSQL = sprintf($Update, Sanitise::DateTime($regexpiry), $regrem, Sanitise::DateTime($timestamp), $customerno, $vehicleid);
            if (!$this->_databaseManager->executeQuery($UpdateSQL)) {
                return $status = "Ok";
            } else {
                return $status = "Not Ok From Update";
            }
        } else {
            $Insert = "INSERT INTO valert(vehicleid,customerno,userid,reg_expiry,reg_sms_email,timestamp)VALUES(%d,%d,%d,'%s',%d,'%s')";
            $InsertSql = sprintf($Insert, $vehicleid, $customerno, $userid, Sanitise::DateTime($regexpiry), $regrem, Sanitise::DateTime($timestamp));
            if (!$this->_databaseManager->executeQuery($InsertSql)) {
                return $status = 'Ok';
            } else {
                return $status = 'Not Ok From Insert ';
            }
        }
    }

    public function setSPEEDExpiry($customerno, $userid, $vehicleid, $regexpiry, $regrem) {
        $timestamp = date('Y-m-d H:i:s');
        $Check = "SELECT vehicleid FROM valert WHERE customerno=%d AND vehicleid=%d";
        $CheckSQL = sprintf($Check, $customerno, $vehicleid);
        $this->_databaseManager->executeQuery($CheckSQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $Update = "UPDATE valert SET speed_gov_expiry='%s', speed_gov_sms_email=%d, timestamp='%s' WHERE customerno=%d AND vehicleid=%d";
            $UpdateSQL = sprintf($Update, Sanitise::DateTime($regexpiry), $regrem, Sanitise::DateTime($timestamp), $customerno, $vehicleid);
            if (!$this->_databaseManager->executeQuery($UpdateSQL)) {
                return $status = "Ok";
            } else {
                return $status = "Not Ok From Update";
            }
        } else {
            $Insert = "INSERT INTO valert(vehicleid,customerno,userid,speed_gov_expiry,speed_gov_sms_email,timestamp)VALUES(%d,%d,%d,'%s',%d,'%s')";
            $InsertSql = sprintf($Insert, $vehicleid, $customerno, $userid, Sanitise::DateTime($regexpiry), $regrem, Sanitise::DateTime($timestamp));
            if (!$this->_databaseManager->executeQuery($InsertSql)) {
                return $status = 'Ok';
            } else {
                return $status = 'Not Ok From Insert ';
            }
        }
    }

    public function setFIREEXTExpiry($customerno, $userid, $vehicleid, $fireexpiry, $fireextrem) {
        $timestamp = date('Y-m-d H:i:s');
        $Check = "SELECT vehicleid FROM valert WHERE customerno=%d AND vehicleid=%d";
        $CheckSQL = sprintf($Check, $customerno, $vehicleid);
        $this->_databaseManager->executeQuery($CheckSQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $Update = "UPDATE valert SET fire_expiry='%s',fire_sms_email=%d, timestamp='%s' WHERE customerno=%d AND vehicleid=%d";
            $UpdateSQL = sprintf($Update, Sanitise::DateTime($fireexpiry), $fireextrem, Sanitise::DateTime($timestamp), $customerno, $vehicleid);
            if (!$this->_databaseManager->executeQuery($UpdateSQL)) {
                return $status = "Ok";
            } else {
                return $status = "Not Ok From Update";
            }
        } else {
            $Insert = "INSERT INTO valert(vehicleid,customerno,userid,fire_expiry,fire_sms_email,timestamp)VALUES(%d,%d,%d,'%s',%d,'%s')";
            $InsertSql = sprintf($Insert, $vehicleid, $customerno, $userid, Sanitise::DateTime($fireexpiry), $fireextrem, Sanitise::DateTime($timestamp));
            if (!$this->_databaseManager->executeQuery($InsertSql)) {
                return $status = 'Ok';
            } else {
                return $status = 'Not Ok From Insert ';
            }
        }
    }

    public function setINSExpiry($customerno, $userid, $vehicleid, $insexpiry, $insrem) {
        $timestamp = date('Y-m-d H:i:s');
        $Check = "SELECT vehicleid FROM valert WHERE customerno=%d AND vehicleid=%d";
        $CheckSQL = sprintf($Check, $customerno, $vehicleid);
        $this->_databaseManager->executeQuery($CheckSQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $Update = "UPDATE valert SET insurance_expiry='%s',insurance_sms_email=%d, timestamp='%s' WHERE customerno=%d AND vehicleid=%d";
            $UpdateSQL = sprintf($Update, Sanitise::DateTime($insexpiry), $insrem, Sanitise::DateTime($timestamp), $customerno, $vehicleid);
            if (!$this->_databaseManager->executeQuery($UpdateSQL)) {
                return $status = "Ok";
            } else {
                return $status = "Not Ok From Update";
            }
        } else {
            $Insert = "INSERT INTO valert(vehicleid,customerno,userid,insurance_expiry,insurance_sms_email,timestamp)VALUES(%d,%d,%d,'%s',%d,'%s')";
            $InsertSql = sprintf($Insert, $vehicleid, $customerno, $userid, Sanitise::DateTime($insexpiry), $insrem, Sanitise::DateTime($timestamp));
            if (!$this->_databaseManager->executeQuery($InsertSql)) {
                return $status = 'Ok';
            } else {
                return $status = 'Not Ok From Insert ';
            }
        }
    }

    public function setOTH1Expiry($customerno, $userid, $vehicleid, $oth1expiry, $oth1rem) {
        $timestamp = date('Y-m-d H:i:s');
        $Check = "SELECT vehicleid FROM valert WHERE customerno=%d AND vehicleid=%d";
        $CheckSQL = sprintf($Check, $customerno, $vehicleid);
        $this->_databaseManager->executeQuery($CheckSQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $Update = "UPDATE valert SET other1_expiry='%s',other1_sms_email=%d, timestamp='%s' WHERE customerno=%d AND vehicleid=%d";
            $UpdateSQL = sprintf($Update, Sanitise::DateTime($oth1expiry), $oth1rem, Sanitise::DateTime($timestamp), $customerno, $vehicleid);
            if (!$this->_databaseManager->executeQuery($UpdateSQL)) {
                return $status = "Ok";
            } else {
                return $status = "Not Ok From Update";
            }
        } else {
            $Insert = "INSERT INTO valert(vehicleid,customerno,userid,other1_expiry,other1_sms_email,timestamp)VALUES(%d,%d,%d,'%s',%d,'%s')";
            $InsertSql = sprintf($Insert, $vehicleid, $customerno, $userid, Sanitise::DateTime($oth1expiry), $oth1rem, Sanitise::DateTime($timestamp));
            if (!$this->_databaseManager->executeQuery($InsertSql)) {
                return $status = 'Ok';
            } else {
                return $status = 'Not Ok From Insert ';
            }
        }
    }

    public function setOTH2Expiry($customerno, $userid, $vehicleid, $oth2expiry, $oth2rem) {
        $timestamp = date('Y-m-d H:i:s');
        $Check = "SELECT vehicleid FROM valert WHERE customerno=%d AND vehicleid=%d";
        $CheckSQL = sprintf($Check, $customerno, $vehicleid);
        $this->_databaseManager->executeQuery($CheckSQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $Update = "UPDATE valert SET other2_expiry='%s',other2_sms_email=%d, timestamp='%s' WHERE customerno=%d AND vehicleid=%d";
            $UpdateSQL = sprintf($Update, Sanitise::DateTime($oth2expiry), $oth2rem, Sanitise::DateTime($timestamp), $customerno, $vehicleid);
            if (!$this->_databaseManager->executeQuery($UpdateSQL)) {
                return $status = "Ok";
            } else {
                return $status = "Not Ok From Update";
            }
        } else {
            $Insert = "INSERT INTO valert(vehicleid,customerno,userid,other2_expiry,other2_sms_email,timestamp)VALUES(%d,%d,%d,'%s',%d,'%s')";
            $InsertSql = sprintf($Insert, $vehicleid, $customerno, $userid, Sanitise::DateTime($oth2expiry), $oth2rem, Sanitise::DateTime($timestamp));
            if (!$this->_databaseManager->executeQuery($InsertSql)) {
                return $status = 'Ok';
            } else {
                return $status = 'Not Ok From Insert ';
            }
        }
    }

    public function setOTH3Expiry($customerno, $userid, $vehicleid, $oth3expiry, $oth3rem) {
        $timestamp = date('Y-m-d H:i:s');
        $Check = "SELECT vehicleid FROM valert WHERE customerno=%d AND vehicleid=%d";
        $CheckSQL = sprintf($Check, $customerno, $vehicleid);
        $this->_databaseManager->executeQuery($CheckSQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $Update = "UPDATE valert SET other3_expiry='%s',other3_sms_email=%d, timestamp='%s' WHERE customerno=%d AND vehicleid=%d";
            $UpdateSQL = sprintf($Update, Sanitise::DateTime($oth3expiry), $oth3rem, Sanitise::DateTime($timestamp), $customerno, $vehicleid);
            if (!$this->_databaseManager->executeQuery($UpdateSQL)) {
                return $status = "Ok";
            } else {
                return $status = "Not Ok From Update";
            }
        } else {
            $Insert = "INSERT INTO valert(vehicleid,customerno,userid,other3_expiry,other3_sms_email,timestamp)VALUES(%d,%d,%d,'%s',%d,'%s')";
            $InsertSql = sprintf($Insert, $vehicleid, $customerno, $userid, Sanitise::DateTime($oth3expiry), $oth3rem, Sanitise::DateTime($timestamp));
            if (!$this->_databaseManager->executeQuery($InsertSql)) {
                return $status = 'Ok';
            } else {
                return $status = 'Not Ok From Insert ';
            }
        }
    }

    public function setOTH4Expiry($customerno, $userid, $vehicleid, $oth4expiry, $oth4rem) {
        $timestamp = date('Y-m-d H:i:s');
        $Check = "SELECT vehicleid FROM valert WHERE customerno=%d AND vehicleid=%d";
        $CheckSQL = sprintf($Check, $customerno, $vehicleid);
        $this->_databaseManager->executeQuery($CheckSQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $Update = "UPDATE valert SET other4_expiry='%s',other4_sms_email=%d, timestamp='%s' WHERE customerno=%d AND vehicleid=%d";
            $UpdateSQL = sprintf($Update, Sanitise::DateTime($oth4expiry), $oth4rem, Sanitise::DateTime($timestamp), $customerno, $vehicleid);
            if (!$this->_databaseManager->executeQuery($UpdateSQL)) {
                return $status = "Ok";
            } else {
                return $status = "Not Ok From Update";
            }
        } else {
            $Insert = "INSERT INTO valert(vehicleid,customerno,userid,other4_expiry,other4_sms_email,timestamp)VALUES(%d,%d,%d,'%s',%d,'%s')";
            $InsertSql = sprintf($Insert, $vehicleid, $customerno, $userid, Sanitise::DateTime($oth4expiry), $oth4rem, Sanitise::DateTime($timestamp));
            if (!$this->_databaseManager->executeQuery($InsertSql)) {
                return $status = 'Ok';
            } else {
                return $status = 'Not Ok From Insert ';
            }
        }
    }

    public function getVehicleAlert($vehicleid) {
        $Query = "select * from valert left join vehicle on vehicle.vehicleid = valert.vehicleid where valert.customerno=%d  AND valert.vehicleid=%d limit 1";
        $SQL = sprintf($Query, $this->_Customerno, $vehicleid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            if (isset($row)) {
                return $row;
            }
        }
    }

    public function getAlertVehiclesByCustomer() {
        $valert = Array();
        $Query = "SELECT *,vehicle.groupid, vehicle.vehicleno,gr.groupname FROM valert
        INNER JOIN vehicle On valert.vehicleid = vehicle.vehicleid
        LEFT OUTER JOIN `group` as gr ON vehicle.groupid = gr.groupid
        WHERE valert.customerno=%d";
        $SQL = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->customerno = $row['customerno'];
                $vehicle->puc_expiry = $row['puc_expiry'];
                $vehicle->puc_sms_email = $row['puc_sms_email'];
                $vehicle->puc_filename = $row['puc_filename'];
                $vehicle->reg_expiry = $row['reg_expiry'];
                $vehicle->reg_sms_email = $row['reg_sms_email'];
                $vehicle->registration_filename = $row['registration_filename'];
                $vehicle->insurance_expiry = $row['insurance_expiry'];
                $vehicle->insurance_sms_email = $row['insurance_sms_email'];
                $vehicle->insurance_filename = $row['insurance_filename'];
                $vehicle->other1_expiry = $row['other1_expiry'];
                $vehicle->other1_sms_email = $row['other1_sms_email'];
                $vehicle->other1 = $row['other_upload1'];
                $vehicle->other2_expiry = $row['other2_expiry'];
                $vehicle->other2_sms_email = $row['other2_sms_email'];
                $vehicle->other2 = $row['other_upload2'];
                $vehicle->other3_expiry = $row['other3_expiry'];
                $vehicle->other3_sms_email = $row['other3_sms_email'];
                $vehicle->other3 = $row['other_upload3'];
                $vehicle->other4_expiry = $row['other4_expiry'];
                $vehicle->other4_sms_email = $row['other4_sms_email'];
                $vehicle->other4 = $row['other_upload4'];
                $vehicle->other5_expiry = $row['other5_expiry'];
                $vehicle->other5_sms_email = $row['other5_sms_email'];
                $vehicle->other5 = $row['other_upload5'];
                $vehicle->other6_expiry = $row['other6_expiry'];
                $vehicle->other6_sms_email = $row['other6_sms_email'];
                $vehicle->other6 = $row['other_upload6'];
                $vehicle->groupid = $row['groupid'];
                $vehicle->groupname = $row['groupname'];
                $valert[] = $vehicle;
            }
        }
        return $valert;
    }

    public function getTaxAlert($vehicleid) {
        $Query = "select * from tax where customerno=%d and vehicleid=%d and isdeleted=0";
        $SQL = sprintf($Query, $this->_Customerno, $vehicleid);
        $valert = Array();
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->tax_expiry = $row['to_date'];
                $vehicle->tax_sms_email = $row['emailalert'];
                $valert[] = $vehicle;
            }
        }
        return $valert;
    }

    public function get_all_vehicles_for_batch($customerno) {
        $vehicles = array();
        $Query = "SELECT batch.vehicleid, batch.batchno, batch.workkey, batch.starttime, batch.pmid, batch.dummybatch, batch.last_fid, vehicle.vehicleno, vehicle.uid, devices.devicelat, devices.devicelong, devices.directionchange,
        unit.unitno, vehicle.lastupdated, vehicle.curspeed, vehicle.odometer FROM batch
        INNER JOIN vehicle ON vehicle.vehicleid = batch.vehicleid
        INNER JOIN devices ON vehicle.uid = devices.uid
        INNER JOIN unit ON vehicle.uid = unit.uid
        WHERE batch.customerno=%d AND vehicle.customerno=%d AND batch.isdeleted=0 AND vehicle.isdeleted=0";
        $SQL = sprintf($Query, $customerno, $customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->cgeolat = $row['devicelat'];
                $vehicle->cgeolong = $row['devicelong'];
                $vehicle->direction = $row['directionchange'];
                $vehicle->batchno = $row['batchno'];
                $vehicle->dummybatchno = $row['dummybatch'];
                $vehicle->workkey = $row['workkey'];
                $vehicle->starttime = $row['starttime'];
                $vehicle->pmid = $row['pmid'];
                $vehicle->imei = $row['unitno'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->odometer = $row['odometer'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                }
                $vehicle->last_fid = $row['last_fid'];
                $vehicles[] = $vehicle;
            }
        }
        return $vehicles;
    }

    public function update_batch_fid($vehicleid, $fid) {
        $SQL = "update batch set last_fid=$fid where vehicleid=$vehicleid";
        $this->_databaseManager->executeQuery($SQL);
    }

    public function get_static_sqlite($pmid) {
        $Query = "select * from probity_master where pmid=%d";
        $SQL = sprintf($Query, $pmid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->workkey = $row['workkey'];
                $vehicle->workname = $row['workkey_name'];
                $vehicle->master = $row['work_master'];
                return $vehicle;
            }
        }
        return NULL;
    }

    public function get_messagekey_for_vehicle($vehicleid) {
        $Query = "SELECT unit.msgkey from vehicle
        inner join unit on vehicle.uid = unit.uid
        where vehicle.vehicleid = %d";
        $SQL = sprintf($Query, $vehicleid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row['msgkey'];
            }
        }
        return NULL;
    }

    public function reset_batchno($vehicleid) {
        $Query = "update batch SET batchno = '',last_fid=null where vehicleid = %d";
        $SQL = sprintf($Query, $vehicleid);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function reset_dummybatch($vehicleid) {
        $Query = "update batch SET dummybatch = '' , batchno = '',  starttime = '', pmid=0, workkey='', last_fid=null where vehicleid = %d";
        $SQL = sprintf($Query, $vehicleid);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function get_groupname_by_id($groupid) {
        $Query = "SELECT groupname FROM `group` where customerno=%d AND groupid=%d AND isdeleted=0";
        $groupQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($groupid));
        $this->_databaseManager->executeQuery($groupQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row['groupname'];
            }
        }
        return NULL;
    }

    public function get_grouped_vehicles_by_userid($customerno, $userid, $groupid) {
//echo "$customerno, $userid, $groupid<br/>";
        $vehicles = Array();
        if ($groupid == 0) {
            $Query = 'SELECT b.vehicleid FROM user as a
            left join vehicle as b on a.customerno=b.customerno WHERE a.customerno=%d and a.userid=%d AND  a.isdeleted=0 and  b.isdeleted=0 and a.role!= "elixir"';
            $vehiclesQuery = sprintf($Query, $customerno, $userid);
        } else {
            $Query = 'SELECT a.groupid, b.vehicleid FROM groupman as a
            left join vehicle as b on a.groupid=b.groupid and a.customerno=b.customerno
            left join user as c on a.userid=c.userid and a.customerno=c.customerno
            where a.customerno=%d and a.userid=%d and a.isdeleted=0 and b.isdeleted=0 and c.isdeleted=0';
            $vehiclesQuery = sprintf($Query, $customerno, $userid);
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
//echo "========================".$this->_databaseManager->get_rowCount().'<br/>';
            $vehicles = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicles[] = $row['vehicleid'];
            }
            return $vehicles;
        }
        return null;
    }

    public function get_group_vehicle_by_userid($customerno, $userid, $groupid) {
        $vehicles = Array();
        if ($groupid == 0) {
            $Query = '  SELECT   v.vehicleid
                                ,v.`temp1_min`
                                ,v.`temp1_max`
                                ,v.`temp2_min`
                                ,v.`temp2_max`
                                ,v.`temp3_min`
                                ,v.`temp3_max`
                                ,v.`temp4_min`
                                ,v.`temp4_max`
                                ,t.`temp1_min_sms`
                                ,t.`temp1_max_sms`
                                ,t.`temp2_min_sms`
                                ,t.`temp2_max_sms`
                                ,t.`temp3_min_sms`
                                ,t.`temp3_max_sms`
                                ,t.`temp4_min_sms`
                                ,t.`temp4_max_sms`
                        FROM    user as u
                        INNER JOIN vehicle as v on u.customerno = v.customerno
                        LEFT JOIN advancetempalertrange as t on t.customerno = v.customerno
                        WHERE   u.customerno = %d
                        AND     u.userid = %d
                        AND     u.isdeleted = 0
                        AND     v.isdeleted = 0
                        AND     u.role != "elixir"
                        LIMIT   1';
            $vehiclesQuery = sprintf($Query, $customerno, $userid);
        } else {
            $Query = '  SELECT   v.vehicleid
                                ,v.`temp1_min`
                                ,v.`temp1_max`
                                ,v.`temp2_min`
                                ,v.`temp2_max`
                                ,v.`temp3_min`
                                ,v.`temp3_max`
                                ,v.`temp4_min`
                                ,v.`temp4_max`
                                ,t.`temp1_min_sms`
                                ,t.`temp1_max_sms`
                                ,t.`temp2_min_sms`
                                ,t.`temp2_max_sms`
                                ,t.`temp3_min_sms`
                                ,t.`temp3_max_sms`
                                ,t.`temp4_min_sms`
                                ,t.`temp4_max_sms`
                        FROM    groupman as g
                        INNER JOIN `vehicle` as v on g.groupid = v.groupid AND g.customerno = v.customerno
                        INNER JOIN `user` as u on g.userid = u.userid AND g.customerno = u.customerno
                        LEFT OUTER JOIN advancetempalertrange as t on t.customerno = v.vehicleid
                        WHERE   g.customerno = %d
                        AND     g.userid = %d
                        AND     g.isdeleted = 0
                        AND     v.isdeleted = 0
                        AND     u.isdeleted = 0
                        LIMIT   1';
            $vehiclesQuery = sprintf($Query, $customerno, $userid);
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $vehicles = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new stdClass();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->temp1_min = isset($row['temp1_min']) ? $row['temp1_min'] : 0;
                $vehicle->temp1_max = isset($row['temp1_max']) ? $row['temp1_max'] : 0;
                $vehicle->temp2_min = $row['temp2_min'];
                $vehicle->temp2_max = $row['temp2_max'];
                $vehicle->temp3_min = $row['temp3_min'];
                $vehicle->temp3_max = $row['temp3_max'];
                $vehicle->temp4_min = $row['temp4_min'];
                $vehicle->temp4_max = $row['temp4_max'];
                $vehicle->temp1_min_sms = $row['temp1_min_sms'];
                $vehicle->temp1_max_sms = $row['temp1_max_sms'];
                $vehicle->temp2_min_sms = $row['temp2_min_sms'];
                $vehicle->temp2_max_sms = $row['temp2_max_sms'];
                $vehicle->temp3_min_sms = $row['temp3_min_sms'];
                $vehicle->temp3_max_sms = $row['temp3_max_sms'];
                $vehicle->temp4_min_sms = $row['temp4_min_sms'];
                $vehicle->temp4_max_sms = $row['temp4_max_sms'];
            }
            return $vehicle;
        }
        return null;
    }

    public function get_grouped_vehicles_by_groupid($customerno, $groupid) {
        $vehicles = Array();
        $group = '';
        if ($groupid != '0') {
            $group = ' AND     v.groupid IN (%s) ';
        }
        $Query = '  SELECT  v.vehicleid
                    FROM    vehicle v
                    INNER JOIN unit AS u ON u.uid = v.uid
                    INNER JOIN devices AS d ON d.uid = u.uid
                    WHERE   v.customerno = %d
                    AND     u.trans_statusid NOT IN (10,22)
                    ' . $group . '
                    AND     v.isdeleted = 0
                    ORDER BY v.sequenceno = 0,v.sequenceno ASC,v.groupid ASC';
        if ($groupid != '0') {
            $vehiclesQuery = sprintf($Query, $customerno, $groupid);
        } else {
            $vehiclesQuery = sprintf($Query, $customerno);
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $vehicles = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicles[] = $row['vehicleid'];
            }
            return $vehicles;
        }
        return null;
    }

    public function vehicle_alert_insert($query) {
        $this->_databaseManager->executeQuery($query);
    }

    /* ak added, for download/report.php */
    public function get_vehicle_details_by_id($vehid) {
        $Query = "SELECT `group`.groupname, vehicle.vehicleid, vehicle.driverid, vehicle.vehicleno,vehicle.uid, devices.deviceid
        FROM vehicle
        INNER JOIN devices ON devices.uid = vehicle.uid
        left join `group` on `group`.groupid=vehicle.groupid
        WHERE vehicle.customerno =%d AND vehicle.isdeleted=0 AND vehicle.vehicleid=%d";
        $vehiclesQuery = sprintf($Query, $this->_Customerno, $vehid);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->groupname = $row['groupname'];
                return $vehicle;
            }
        }
        return null;
    }

    public function get_tyretype() {
        $vehicles = array();
        $Query = "SELECT * FROM maintenance_tyretype";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->tyreid = $row['tyreid'];
                $vehicle->type = $row['type'];
                $vehicles[] = $vehicle;
            }
        }
        return $vehicles;
    }

    public function getTyretype_byvehicle($vehid) {
        $vehicles = array();
        $Query = "SELECT tyre_type FROM maintenance as mm WHERE mm.vehicleid=%d AND mm.customerno=%d AND mm.category = 1 ORDER BY id DESC LIMIT 1,1"
        ;
        $vehiclesQuery = sprintf($Query, $vehid, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicles = $row['tyre_type'];
            }
        }
        return $vehicles;
    }

    public function getTyretype_fortransaction($vehid) {
        $vehicles = array();
        $Query = "SELECT tyre_type FROM maintenance as mm WHERE mm.vehicleid=%d AND mm.customerno=%d AND mm.category = 1 ORDER BY id DESC LIMIT 1"
        ;
        $vehiclesQuery = sprintf($Query, $vehid, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicles = $row['tyre_type'];
            }
        }
        return $vehicles;
    }

    public function getbatteryno_byvehicle($vehid) {
        $vehicles = array();
        $Query = "SELECT * FROM maintenance_mapbattery
        WHERE vehicleid=%d AND customerno=%d";
        $vehiclesQuery = sprintf($Query, $vehid, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $vehicle = new stdClass();
            $vehicle->srno = $row['batt_serialno'];
            $vehicle->ins = $row['installedon'];
            $vehicle->batt_mapid = $row['batt_mapid'];
            $vehicles[] = $vehicle;
        }
        return $vehicles;
    }

    public function getbatteryno_byvehicle_history($vehid, $tid) {
        $oldbatno = "";
        $Query = "SELECT mh.battery_srno FROM maintenance_history as mh WHERE mh.vehicleid=%d AND mh.customerno=%d AND mh.maintananceid=%d AND mh.category = 0 ORDER BY hist_id DESC LIMIT 1";
        $vehiclesQuery = sprintf($Query, $vehid, $this->_Customerno, $tid);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $oldbatno = $row['battery_srno'];
            }
        }
        return $oldbatno;
    }

    public function getTimezoneDiffInMin($customerno) {
        $QUERY = "SELECT timezone FROM " . DB_PARENT . ".customer where customerno=%d";
        $SQL = sprintf($QUERY, $customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $timezone = $row['timezone'];
                if ($timezone != 0) {
                    $Query = "SELECT * from " . DB_PARENT . ".timezone where tid=%d";
                    $Sql = sprintf($Query, $timezone);
                    $this->_databaseManager->executeQuery($Sql);
                    $row1 = $this->_databaseManager->get_nextRow();
                    $min = $row1['timediff'];
                    return $min;
                } else {
                    return $timezone;
                }
            }
        }
        return 0;
    }

// to find key for multidimension array
    public function searchForId($id, $array, $array_col) {
        foreach ($array as $key => $val) {
            if ($val[$array_col] === $id) {
                return $key;
            }
        }
        return null;
    }

    /**/
    /*    public function getlastodometer($vehicleid)
    {
    $Query = "SELECT vehiclehistory.odometer,vehiclehistory.vehicleid
    FROM vehiclehistory WHERE vehiclehistory.customerno =%d AND vehiclehistory.vehicleid=%d
    ORDER BY vehiclehistory.vehiclehistoryid DESC LIMIT 0,1";
    $vehiclesQuery = sprintf($Query,$this->_Customerno,Sanitise::Long($vehicleid));
    $this->_databaseManager->executeQuery($vehiclesQuery);
    if ($this->_databaseManager->get_rowCount() > 0)
    {
    while ($row = $this->_databaseManager->get_nextRow())
    {
    $odometer = $row['odometer'];
    return $odometer;
    }
    }
    return null;
    }
    public function getfirstodometer($vehicleid)
    {
    $Query = "SELECT vehiclehistory.odometer,vehiclehistory.vehicleid
    FROM vehiclehistory WHERE vehiclehistory.customerno =%d AND vehiclehistory.vehicleid=%d
    ORDER BY vehiclehistory.vehiclehistoryid ASC LIMIT 0,1";
    $vehiclesQuery = sprintf($Query,$this->_Customerno,Sanitise::Long($vehicleid));
    $this->_databaseManager->executeQuery($vehiclesQuery);
    if ($this->_databaseManager->get_rowCount() > 0)
    {
    while ($row = $this->_databaseManager->get_nextRow())
    {
    $odometer = $row['odometer'];
    return $odometer;
    }
    }
    return null;
    }
    public function getlastodometerwithdate($vehicleid,$date)
    {
    $Query = "SELECT vehiclehistory.odometer,vehiclehistory.vehicleid,
    devicehistory.msgid FROM vehiclehistory
    INNER JOIN devicehistory ON devicehistory.id = vehiclehistory.dhid
    WHERE vehiclehistory.customerno =%d AND vehiclehistory.vehicleid=%s
    AND DATE(vehiclehistory.lastupdated) BETWEEN '%s' and '%s'
    ORDER BY vehiclehistory.lastupdated DESC LIMIT 0,1";
    $vehiclesQuery = sprintf($Query,$this->_Customerno,$vehicleid,$date,$date);
    $this->_databaseManager->executeQuery($vehiclesQuery);
    if ($this->_databaseManager->get_rowCount() > 0)
    {
    while ($row = $this->_databaseManager->get_nextRow())
    {
    $odometer = $row['odometer'];
    return $odometer;
    }
    }
    return null;
    }
    public function getfirstodometerwithdate($vehicleid,$date)
    {
    $Query = "SELECT vehiclehistory.odometer,vehiclehistory.vehicleid,
    devicehistory.msgid FROM vehiclehistory
    INNER JOIN devicehistory ON devicehistory.id = vehiclehistory.dhid
    WHERE vehiclehistory.customerno =%d AND vehiclehistory.vehicleid=%d
    AND DATE(vehiclehistory.lastupdated) BETWEEN '%s' and '%s'
    ORDER BY vehiclehistory.lastupdated ASC LIMIT 0,1";
    $vehiclesQuery = sprintf($Query,$this->_Customerno,$vehicleid,$date,$date);
    $this->_databaseManager->executeQuery($vehiclesQuery);
    if ($this->_databaseManager->get_rowCount() > 0)
    {
    while ($row = $this->_databaseManager->get_nextRow())
    {
    $odometer = $row['odometer'];
    return $odometer;
    }
    }
    return null;
    }
     *
     */
    public function getupload($vehicleid) {
        $op1 = '';
        $op2 = '';
        $op3 = '';
        $Query = "SELECT other_upload1,other_upload2,other_upload3,other_upload4 FROM vehicle
        WHERE vehicleid=%d AND customerno=%d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $op1 = $row['other_upload1'];
            $op2 = $row['other_upload2'];
            $op3 = $row['other_upload3'];
            $op4 = $row['other_upload4'];
        }
        $vehicle = '';
        $puc_file = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $vehicleid . "/puc.pdf";
        $reg_file = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $vehicleid . "/registration.pdf";
        $ins_file = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $vehicleid . "/insurance.pdf";
        $other1_file = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $vehicleid . "/" . $op1 . ".pdf";
        $other2_file = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $vehicleid . "/" . $op2 . ".pdf";
        $other3_file = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $vehicleid . "/" . $op3 . ".pdf";
        $other4_file = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $vehicleid . "/" . $op4 . ".pdf";
        if ($op1 != '' && file_exists($other1_file)) {
            $vehicle .= "<tr> <td>" . $op1 . "</td> </tr>";
        }
        if ($op2 != '' && file_exists($other2_file)) {
            $vehicle .= "<tr> <td>" . $op2 . "</td> </tr>";
        }
        if ($op3 != '' && file_exists($other3_file)) {
            $vehicle .= "<tr> <td>" . $op3 . "</td> </tr>";
        }
        if ($op4 != '' && file_exists($other4_file)) {
            $vehicle .= "<tr> <td>" . $op4 . "</td> </tr>";
        }
        if (file_exists($puc_file)) {
            $vehicle .= "<tr> <td>PUC</td> </tr>";
        }
        if (file_exists($reg_file)) {
            $vehicle .= "<tr> <td>Registration</td> </tr>";
        }
        if (file_exists($ins_file)) {
            $vehicle .= "<tr> <td>Insurance</td> </tr>";
        }
        if (!file_exists($other1_file) && !file_exists($other2_file) && !file_exists($other3_file) && !file_exists($other4_file) && !file_exists($puc_file) && !file_exists($reg_file) && !file_exists($ins_file)) {
            $vehicle .= "<tr> <td>No Files Uploaded</td> </tr>";
        }
        return $vehicle;
    }

    public function getlatest_maintenance_details($main_id) {
        $main = array();
        $QUERY = "SELECT * FROM maintenance WHERE id=%d AND customerno=%d and isdeleted=0";
        $SQL = sprintf($QUERY, $main_id, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $data = new VOVehicle();
            $data->maintenance_date = $row['maintenance_date'];
            $data->meter_reading = $row['meter_reading'];
            $data->vehicle_in_date = $row['vehicle_in_date'];
            $data->vehicle_out_date = $row['vehicle_out_date'];
            $data->dealer_id = $row['dealer_id'];
            $data->invoice_date = $row['invoice_date'];
            $data->invoice_no = $row['invoice_no'];
            $data->invoice_amount = $row['invoice_amount'];
            $data->tax = $row['tax'];
            $data->payment_id = $row['payment_id'];
            $data->vehicleid = $row['vehicleid'];
            $data->userid = $row['userid'];
            $data->customerno = $row['customerno'];
            $data->roleid = $row['roleid'];
            $data->notes = $row['notes'];
            $data->approval_notes = $row['approval_notes'];
            $data->amount_quote = $row['amount_quote'];
            $data->file_name = $row['file_name'];
            $data->invoice_file_name = $row['invoice_file_name'];
            $data->tyre_type = $row['tyre_type'];
            $data->battery_srno = $row['battery_srno'];
            $data->parts_list = $row['parts_list'];
            $data->task_select_array = $row['task_select_array'];
            $data->category = $row['category'];
            $data->submission_date = $row['submission_date'];
            $data->approval_date = $row['approval_date'];
            $data->ofasno = $row['ofasno'];
            $data->payment_approval_date = $row['payment_approval_date'];
            $data->payment_submission_date = $row['payment_submission_date'];
            $data->payment_approval_note = $row['payment_approval_note'];
            $data->behalfid = $row['behalfid'];
            return $data;
        }
    }

    public function vehiclesequence_update($seq_array, $defaultseqarray) {
        if (!in_array("", $seq_array)) {
            $i = 0;
            foreach ($seq_array as $key => $value) {
                $i++;
                $sql = "call update_sequenceno($value,$i)";
//$sql = "update vehicle set sequenceno= '" . $i . "' where vehicleid='" . $value . "'";
                $test = sprintf($sql);
                $this->_databaseManager->executeQuery($test);
            }
        }
        if (!in_array("", $defaultseqarray)) {
            foreach ($defaultseqarray as $key => $value) {
                $i = 0;
                $sql = "call update_sequenceno($value,$i)";
//$sql = "update vehicle set sequenceno= 0 where vehicleid='" . $value . "'";
                $test2 = sprintf($sql);
                $this->_databaseManager->executeQuery($test2);
            }
        }
        return 'ok';
    }

    public function getlastupdatefordevicesreason() {
        $vehicles = array();
        $date = date('Y-m-d H:i:s', time() - 3600);
        $daterange = "devices.lastupdated<'$date'";
        $Query = "SELECT vehicle.groupid, unit.unitno, devices.deviceid,vehicle.vehicleid,
        devices.uid, vehicle.vehicleno, devices.lastupdated, devices.customerno, devices.ignition, devices.powercut, devices.tamper, devices.gsmstrength, devices.gprsregister, customer.customercompany,vendor.id from devices
        INNER JOIN unit ON unit.uid = devices.uid
        INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
        INNER JOIN " . DB_PARENT . ".customer ON devices.customerno = customer.customerno
        INNER JOIN simcard ON simcard.id = devices.simcardid
        INNER JOIN vendor ON vendor.id = simcard.vendorid
        WHERE unit.trans_statusid NOT IN (10,22) AND unit.customerno NOT IN (-1,1) AND vehicle.isdeleted = 0 AND $daterange ";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                if ($row['customerno'] == '97') {
                    $date1 = new DateTime();
                    $date1->setTimezone(new DateTimeZone('Africa/Cairo'));
                    $cairo_dt = $date1->format('Y-m-d H:i:s');
                    $cairo_diff = date("Y-m-d H:i:s", strtotime("-60 minutes", strtotime($cairo_dt)));
                } else {
                    date_default_timezone_set('Asia/Kolkata');
                }if ($row['customerno'] == '97' && $row['lastupdated'] > $cairo_diff) {
                    continue;
                } else {
                    $vehicle->deviceid = $row['deviceid'];
                    $vehicle->vehicleid = $row['vehicleid'];
                    $vehicle->uid = $row['uid'];
                    $vehicle->unitno = $row['unitno'];
                    $vehicle->vehicleno = $row['vehicleno'];
                    $vehicle->groupid = $row['groupid'];
                    $vehicle->lastupdated = $row['lastupdated'];
                    $vehicle->customerno = $row['customerno'];
                    $vehicle->ignition = $row['ignition'];
                    $vehicle->powercut = $row['powercut'];
                    $vehicle->nodata_alert = $row['nodata_alert'];
                    $vehicle->tamper = $row['tamper'];
                    $vehicle->gsmstrength = $row['gsmstrength'];
                    $vehicle->gprsregister = $row['gprsregister'];
                    $vehicle->customercompany = $row['customercompany'];
                    $vehicle->vendorid = $row['id'];
                    $vehicles[] = $vehicle;
                }
            }
            return $vehicles;
        }
    }

    public function getTyreRepair() {
        $vehicles = array();
        $Query = "SELECT * FROM maintenance_tyre_repair_type";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->tyrerepairid = $row['tyrerepairid'];
                $vehicle->repairtype = $row['repairtype'];
                $vehicles[] = $vehicle;
            }
        }
        return $vehicles;
    }

    public function getTyredata($vehicleid, $tid) {
        $tyretype = "";
        $Query = "SELECT tyre_type FROM maintenance where id=" . $tid;
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $tyretype = $row['tyre_type'];
            }
        }
        return $tyretype;
    }

    public function getTyredataold($vehicleid, $tid) {
        $tyretype = "";
        $Query = "SELECT tyre_type FROM maintenance_history where maintananceid=" . $tid;
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $tyretype = $row['tyre_type'];
            }
        }
        return $tyretype;
    }

    public function gettyreTypedata_for_xls($vehicleid) {
        $data = array();
        $this->_databaseManager->next_result();
        $Query = "SELECT * FROM maintenance_maptyre mm
        INNER JOIN maintenance_tyretype as mt ON mm.tyreid = mt.tyreid WHERE mm.customerno = " . $this->_Customerno . " AND mm.vehicleid =" . $vehicleid;
        $records = $this->_databaseManager->query($Query, __FILE__, __LINE__);
        $row_count = $this->_databaseManager->num_rows($records);
        $arrResult = array();
        if ($row_count > 0) {
            while ($row = $this->_databaseManager->fetch_array($records)) {
                $data[] = array(
                    'tyremapid' => $row['tyremapid'],
                    'vehicleid' => $row['vehicleid'],
                    'tyreid' => $row['tyreid'],
                    'type' => $row['type'],
                    'serialno' => $row['serialno']
                );
            }
        }
        $records->close();
        $this->_databaseManager->next_result();
        return $data;
    }

    public function gettyreTypedata($vehicleid) {
        $vehicles = array();
//        $Query = "SELECT * FROM maintenance_maptyre_history mm
        //        INNER JOIN maintenance_tyretype as mt ON mm.tyreid = mt.tyreid WHERE mm.customerno = %d AND mm.vehicleid = %d";
        $Query = "SELECT * FROM maintenance_maptyre mm
        INNER JOIN maintenance_tyretype as mt ON mm.tyreid = mt.tyreid WHERE mm.customerno = %d AND mm.vehicleid = %d";
        $vehQuery = sprintf($Query, $this->_Customerno, $vehicleid);
        $this->_databaseManager->executeQuery($vehQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                //$vehicle = new stdClass();
                $vehicle['tyremapid'] = $row['tyremapid'];
                $vehicle['vehicleid'] = $row['vehicleid'];
                $vehicle['customerno'] = $row['customerno'];
                $vehicle['tyreid'] = $row['tyreid'];
                $vehicle['type'] = $row['type'];
                $vehicle['serialno'] = $row['serialno'];
                if ($row['installedon'] == '1970-01-01' || $row['installedon'] == '0000-00-00') {
                    $vehicle['installedon'] = '';
                } else {
                    $vehicle['installedon'] = date("d-m-Y", strtotime($row['installedon']));
                }
                $vehicle['createdby'] = $row['createdby'];
                $vehicle['createdon'] = $row['createdon'];
                $vehicle['updatedby'] = $row['updatedby'];
                $vehicle['updatedon'] = $row['updatedon'];
                $vehicles[] = $vehicle;
            }
        }
        return $vehicles;
    }

    public function insertprobity_entry($vehicleid, $vehicleno, $sdate, $stime, $sel_master, $customerno) {
        $today = date("Y-m-d H:i:s");
// batch No & Work Key
        if ($sdate != '' || $stime != '') {
            $starttime = $sdate . ' ' . $stime;
            $starttime = date('Y-m-d H:i:s', strtotime($starttime));
        } else {
            $starttime = '';
        }
//batchno,workey,dummybatch
        $SQL = sprintf("INSERT INTO probity(vehicleid, customerno, starttime,pmid, addedon, isdeleted)VALUES(%d,'%s','%s',%d,'%s',0 )", $vehicleid, $this->_Customerno, $starttime, $sel_master, $today);
        $this->_databaseManager->executeQuery($SQL);
        return true;
    }

    public function insertprobityform_entry($data) {
        $today = date("Y-m-d H:i:s");
// batch No & Work Key
        if ($data['startdate'] != '' || $data['starttime'] != '') {
            $starttime = $data['startdate'] . ' ' . $data['starttime'];
            $starttime = date('Y-m-d H:i:s', strtotime($starttime));
        } else {
            $starttime = '';
        }
//batchno,workey,dummybatch
        $SQL = sprintf("INSERT INTO probityformdata(vehicleno,unitno, customerno,batchno,workkey, starttime,pmid, addedon,isdeleted) VALUES('%s','%s','%s','%s','%s','%s','%s','%s',0 )", $data['vehicleno'], $data['unitno'], $this->_Customerno, $data['batch'], $data['workey'], $starttime, $data['selmaster'], $today);
        $this->_databaseManager->executeQuery($SQL);
        return true;
    }

    public function insertprobitytestingdata($record) {
        $SQL = sprintf("INSERT INTO probitydata_testing
            (`vehicleno`,
            `latitude`,
            `longitude`,
            `workkey`,
            `unitno`,
            `direction`,
            `distance`,
            `velocity`,`date_time`,`batchno`,`manufacturer`,`created_date`,`customerno`)
            VALUES ('" . $record->vehicleno . "',"
            . "'" . $record->cgeolat . "',"
            . "'" . $record->cgeolong . "',"
            . "'" . $record->workkey . "',"
            . "'" . $record->unitno . "',"
            . "'" . $record->direction . "',"
            . "'" . $record->distance . "',"
            . "'" . $record->curspeed . "',"
            . "'" . $record->datetime . "',"
            . "'" . $record->batchno . "',"
            . "'" . $record->manufacturer . "',"
            . "'" . $record->createddate . "',"
            . "$this->_Customerno)
            ");
        $this->_databaseManager->executeQuery($SQL);
        return true;
    }

    public function getvehiclesbyuserid($userid, $isWarehouse = null) {
        $vehicles = Array();
        $vehiclekind = '';
        if ($isWarehouse == null) {
            $vehiclekind = ' and vehicle.kind<> "Warehouse"';
        } else {
            $vehiclekind = ' and vehicle.kind="Warehouse"';
        }
        $Query = "select vehicle.vehicleid,vehicle.vehicleno
        from vehicle
        INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid
        where vehmap.userid=%s
        AND vehicle.customerno=%s
        AND vehicle.isdeleted=0
        AND vehmap.isdeleted=0 $vehiclekind";
        $groupQuery = sprintf($Query, Sanitise::Long($userid), $this->_Customerno);
        $this->_databaseManager->executeQuery($groupQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return NULL;
    }

    public function setMaintenanceRoles($customerno) {
        $elixirRoleId = 1;
        $masterRoleId = 1;
        $stateRoleId = 2;
        $zoneRoleId = 3;
        $regionRoleId = 4;
        $cityRoleId = 8;
        $groupRoleId = 0;
        $accountRoleId = 0;
        switch ($_SESSION['customerno']) {
            case 63:
                $masterRoleId = 28;
                $zoneRoleId = 30;
                $regionRoleId = 31;
                break;
            case 64:
                $masterRoleId = 33;
                $zoneRoleId = 35;
                $regionRoleId = 36;
                break;
            case 118:
                $masterRoleId = 18;
                $stateRoleId = 19;
                $zoneRoleId = 20;
                $regionRoleId = 21;
                $cityRoleId = 23;
                $groupRoleId = 22;
                $accountRoleId = 42;
                break;
            case 167:
                $masterRoleId = 24;
                $zoneRoleId = 26;
                $regionRoleId = 27;
                break;
            case 258:
                $masterRoleId = 44;
                $stateRoleId = 45;
                $groupRoleId = 46;
                break;
            default:
                $masterRoleId = 1;
                $zoneRoleId = 3;
                $regionRoleId = 4;
                break;
        }
        $objRole = new stdClass();
        $objRole->customerno = $customerno;
        $objRole->elixirRoleId = $elixirRoleId;
        $objRole->masterRoleId = $masterRoleId;
        $objRole->stateRoleId = $stateRoleId;
        $objRole->zoneRoleId = $zoneRoleId;
        $objRole->regionRoleId = $regionRoleId;
        $objRole->cityRoleId = $cityRoleId;
        $objRole->groupRoleId = $groupRoleId;
        $objRole->accountRoleId = $accountRoleId;
        return $objRole;
    }

    public function getUserGroups($customerno, $userid) {
        $groups = array();
        $um = new UserManager();
        $grouplist = $um->get_groups_fromuser($customerno, $userid);
        if (isset($grouplist)) {
            foreach ($grouplist as $group) {
                $groups[] = $group->groupid;
            }
        } else {
            $groups[] = 0;
        }
        return $groups;
    }

    public function gettripdetails($vehicleid) {
        $tripdata = array();
        $Query = "select td.entrytime as startdate,v.vehicleno,v.vehicleid,td.triplogno,td.tripstatusid,td.billingparty,td.routename,td.budgetedkms,td.budgetedhrs,td.mintemp,td.maxtemp,td.drivername,
                td.drivermobile1,td.drivermobile2,td.tripid,td.statusdate,ts.tripstatus,consr.consignorname,con.consigneename
                    from tripdetails as td
                    inner join vehicle as v on v.vehicleid = td.vehicleid
                    left join tripstatus as ts on td.tripstatusid = ts.tripstatusid
                    left join tripconsignee as con on con.consid = td.consigneeid
                    left join tripconsignor as consr on consr.consrid = td.consignorid
                    where td.is_tripend=0 AND td.vehicleid =%d AND td.customerno=%d AND td.is_tripend=0 AND  td.isdeleted=0 order by td.tripid desc limit 0,1";
        $QueryRes = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($QueryRes);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $startdate = $this->getTripStartTime($row['tripid']);
                $tripdata[] = array(
                    'vehicleno' => $row['vehicleno'],
                    'triplogno' => $row['triplogno'],
                    'tripstatusid' => $row['tripstatusid'],
                    'billingparty' => $row['billingparty'],
                    'routename' => $row['routename'],
                    'budgetedkms' => $row['budgetedkms'],
                    'budgetedhrs' => $row['budgetedhrs'],
                    'mintemp' => $row['mintemp'],
                    'maxtemp' => $row['maxtemp'],
                    'drivername' => $row['drivername'],
                    'drivermobile1' => $row['drivermobile1'],
                    'drivermobile2' => $row['drivermobile2'],
                    'tripid' => $row['tripid'],
                    'statusdate' => $row['statusdate'],
                    'startdate' => $startdate,
                    'tripstatus' => $row['tripstatus'],
                    'consignorname' => $row['consignorname'],
                    'consigneename' => $row['consigneename']
                );
            }
            return $tripdata;
        }
        return null;
    }

    public function getTripStartTime($tripid) {
        $Query = "SELECT a.statusdate FROM  tripdetail_history as a WHERE a.customerno=$this->_Customerno AND a.tripid = $tripid AND tripstatusid=3 AND a.isdeleted=0 order by  triphisid desc limit 1";
        $QueryRes = sprintf($Query);
        $this->_databaseManager->executeQuery($QueryRes);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row['statusdate'];
            }
        }
        return null;
    }

    public function getgroupnamebyvehicleid($vehicleid) {
        $Query = "SELECT `group`.groupname FROM `vehicle` INNER JOIN `group` ON `group`.groupid = vehicle.groupid
        where vehicle.vehicleid = '$vehicleid'";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row['groupname'];
            }
        }
        return "Ungrouped";
    }

    public function getvehicledata_by_vehicleid($vehicleid) {
        $odometer = '';
        $Query = "SELECT odometer FROM `vehicle`  where vehicleid = " . $vehicleid;
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $odometer = $row['odometer'];
            }
        }
        return $odometer;
    }

    public function get_all_vehicles_by_group_fuel($groups) {
        $todayDate = date('Y-m-d');
        $vehicles = Array();
        $vehiclekind = '';
        $list = "vehicle.vehicleid
        ,vehicle.customerno
        ,dailyreport.max_odometer
        ,vehicle.vehicleno
        ,vehicle.kind
        ,vehicle.overspeed_limit
        ,unit.uid
        ,unit.unitno
        ,devices.deviceid
        ,vehicle.groupid
        ,vehicle.fuel_balance
        ,vehicle.old_fuel_balance
        ,vehicle.fuel_min_volt
        ,vehicle.fuel_max_volt
        ,vehicle.fuelMaxVoltCapacity
        ,vehicle.fuelcapacity
        ,unit.fuelsensor
        ,vehicle.lastupdated
        ,vehicle.curspeed
        ,vehicle.odometer
        ,fa.old_odometer
        ,`group`.groupname
        ,fa.vehicleid as fuelvehicleid
        ,fa.countcheck
        ,fa.old_fuel_balance as fuelreading
        ,fa.deltaSum
        ,fa.userid as fueluserid
        ,vehicle.average
        ,vehicle.old_fuel_odometer
        ";
        //
        $Query = "SELECT " . $list . " FROM vehicle
            INNER JOIN unit on unit.vehicleid=vehicle.vehicleid
            INNER JOIN dailyreport on dailyreport.vehicleid=vehicle.vehicleid AND daily_date='%s'
            INNER JOIN devices on devices.uid=vehicle.uid
            LEFT JOIN `group` ON `group`.groupid = vehicle.groupid
            LEFT JOIN fuelcron_alertlog fa ON fa.vehicleid = vehicle.vehicleid
            WHERE (vehicle.customerno=%d ) AND unit.fuelsensor<>0 AND vehicle.isdeleted=0 AND  unit.trans_statusid NOT IN (10,22)";
        if (isset($groups) && $groups[0] == 0) {
            $vehiclesQuery = sprintf($Query, $todayDate, $this->_Customerno);
        } else {
            $group_in = implode(',', $groups);
            $Query .= " AND vehicle.groupid in (%s)";
            $vehiclesQuery = sprintf($Query, $todayDate, $this->_Customerno, $group_in);
        }
        $vehiclesQuery = $vehiclesQuery;
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->customerno = $row['customerno'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->type = $row['kind'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->groupid = $row['groupid'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated_store = $row['lastupdated'];
                    $vehicle->lastupdated = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated_store = $row['lastupdated'];
                    $vehicle->lastupdated = $row['lastupdated'];
                }
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->odometer = $row['odometer'];
                $vehicle->max_odometer = $row['max_odometer'];
                $vehicle->groupname = $row['groupname'];
                $vehicle->fuel_balance = $row['fuel_balance'];
                $vehicle->old_fuel_balance = $row['old_fuel_balance'];
                $vehicle->fuel_min_volt = $row['fuel_min_volt'];
                $vehicle->fuel_max_volt = $row['fuel_max_volt'];
                $vehicle->fuelcapacity = $row['fuelcapacity'];
                $vehicle->fuelMaxVoltCapacity = $row['fuelMaxVoltCapacity'];
                $vehicle->fuelsensor = $row['fuelsensor'];
                $vehicle->fuelvehicleid = $row['fuelvehicleid'];
                $vehicle->countcheck = $row['countcheck'];
                $vehicle->fuelreading = $row['fuelreading'];
                $vehicle->deltaSum = $row['deltaSum'];
                $vehicle->old_odometer = $row['old_odometer'];
                $vehicle->average = $row['average'];
                $vehicle->old_fuel_odometer = $row['old_fuel_odometer'];
                $vehicle->fueluserid = $row['fueluserid'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getFuelBalance($objInputDetails) {
        $objResult = new stdClass();
        $Query = "select fuel_balance, old_fuel_balance from vehicle where vehicleid=" . $objInputDetails->vehicleid . " AND customerno = " . $this->_Customerno . " LIMIT 1;";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $objResult->fuel_balance = $row['fuel_balance'];
                $objResult->old_fuel_balance = $row['old_fuel_balance'];
            }
        }
        return $objResult;
    }

    public function getFuel_alertExists($data) {
        $today = date("Y-m-d H:i:s");
        $fuelcapacity = $data['fuelcapacity'];
        $fuelbalance = $data['fuelbalance'];
        $alerttype = $data['alerttype'];
        $fuel_alert_percentage = $data['fuel_alert_percentage'];
        $thresholdltr = $data['thresholdltr'];
        $vehicleid = $data['vehicleid'];
        $userid = $data['userid'];
        $customerno = $data['customerno'];
        $countcheck = 0;
        $old_fuel_balance = 0;
        $threshold_conflict_status = 0;
        $Query = "select alerttype,countcheck,old_fuel_balance,threshold_conflict_status from fuelcron_alertlog  where vehicleid=" . $vehicleid . " AND userid=" . $userid . " AND alerttype=" . $alerttype . " AND customerno = " . $customerno . " order by fid desc limit 1";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $count = $this->_databaseManager->get_rowCount();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $countcheck = $row['countcheck'];
                $threshold_conflict_status = $row['threshold_conflict_status'];
                $old_fuel_balance = $row['old_fuel_balance'];
                $alerttype = $row['alerttype'];
            }
        } else {
            $count = 0;
        }
        $result = array(
            'countcheck' => $countcheck,
            'threshold_conflict_status' => $threshold_conflict_status,
            'recordcount' => $count,
            'old_fuel_balance' => $old_fuel_balance,
            'alerttype' => $alerttype
        );
        return $result;
    }

    public function update_fuelalertcron($data, $countcheck, $thresholdstatus) {
        $today = date("Y-m-d H:i:s");
        $fuelcapacity = $data['fuelcapacity'];
        $fuelbalance = $data['fuelbalance'];
        $alerttype = $data['alerttype'];
        $fuel_alert_percentage = $data['fuel_alert_percentage'];
        $thresholdltr = $data['thresholdltr'];
        $vehicleid = $data['vehicleid'];
        $userid = $data['userid'];
        $customerno = $data['customerno'];
        if ($alerttype == 1) {
            $query1 = "UPDATE fuelcron_alertlog SET  threshold_conflict_status =" . $thresholdstatus . ", lastupdated='" . $today . "', countcheck = " . $countcheck . " WHERE alerttype='1' AND userid=" . $userid . " AND customerno=" . $customerno;
            $bquery1 = sprintf($query1);
            $this->_databaseManager->executeQuery($bquery1);
        } elseif ($alerttype == 2 || $alerttype == 3) {
            $query1 = "UPDATE fuelcron_alertlog SET old_fuel_balance=" . $fuelbalance . ",  threshold_conflict_status =" . $thresholdstatus . ", lastupdated='" . $today . "', countcheck = " . $countcheck . " WHERE alerttype=" . $alerttype . " AND userid=" . $userid . " AND customerno=" . $customerno;
            $bquery1 = sprintf($query1);
            $this->_databaseManager->executeQuery($bquery1);
        }
    }

    public function update_fuelalertcron1($data, $countcheck, $thresholdstatus, $fuelbalance, $old_odometer) {
        $today = date("Y-m-d H:i:s");
        $fuelcapacity = $data['fuelcapacity'];
        //$fuelbalance = $data['fuelbalance'];
        $alerttype = $data['alerttype'];
        $fuel_alert_percentage = $data['fuel_alert_percentage'];
        $thresholdltr = $data['thresholdltr'];
        $vehicleid = $data['vehicleid'];
        $userid = $data['userid'];
        $customerno = $data['customerno'];
        $query1 = "UPDATE fuelcron_alertlog SET old_fuel_balance=" . $fuelbalance . ", old_fuel_balance=" . $old_odometer . ", threshold_conflict_status =" . $thresholdstatus . ", lastupdated='" . $today . "', countcheck = " . $countcheck . " WHERE alerttype=" . $alerttype . " AND userid=" . $userid . " AND customerno=" . $customerno;
        $bquery1 = sprintf($query1);
        $this->_databaseManager->executeQuery($bquery1);
    }

    public function update_fuelalertcron2($data, $countcheck, $thresholdstatus, $fuelbalance) {
        $today = date("Y-m-d H:i:s");
        $fuelcapacity = $data['fuelcapacity'];
        $alerttype = $data['alerttype'];
        $fuel_alert_percentage = $data['fuel_alert_percentage'];
        $thresholdltr = $data['thresholdltr'];
        $vehicleid = $data['vehicleid'];
        $userid = $data['userid'];
        $customerno = $data['customerno'];
        $query1 = "UPDATE fuelcron_alertlog SET old_fuel_balance=" . $fuelbalance . ",  threshold_conflict_status =" . $thresholdstatus . ", lastupdated='" . $today . "', countcheck = " . $countcheck . " WHERE alerttype=" . $alerttype . " AND userid=" . $userid . " AND customerno=" . $customerno;
        $bquery1 = sprintf($query1);
        $this->_databaseManager->executeQuery($bquery1);
    }

    public function insert_fuelalertcron($data, $countcheck) {
        $today = date("Y-m-d H:i:s");
        $fuelcapacity = $data['fuelcapacity'];
        $fuelbalance = $data['fuelbalance'];
        $alerttype = $data['alerttype'];
        $fuel_alert_percentage = $data['fuel_alert_percentage'];
        $thresholdltr = $data['thresholdltr'];
        $vehicleid = $data['vehicleid'];
        $userid = $data['userid'];
        $customerno = $data['customerno'];
        $odometer = $data['odometer'];
        if ($alerttype == 1 || $alerttype == 2 || $alerttype == 3) {
            $Query = "INSERT INTO `fuelcron_alertlog`(userid,vehicleid,customerno,countcheck,alerttype,old_fuel_balance,old_odometer,lastupdated) VALUES (%d,%d,%d,%d,%d,'%s','%s','%s')";
            $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), Sanitise::Long($customerno), Sanitise::Long($countcheck), Sanitise::Long($alerttype), Sanitise::String($fuelbalance), Sanitise::String($odometer), Sanitise::String($today));
            $this->_databaseManager->executeQuery($SQL);
        }
    }

    public function chkstatustrip($vehicleid, $conchkid, $status) {
        $Query = "SELECT * FROM vehicle WHERE vehicleid=" . $vehicleid . " AND checkpointId=" . $conchkid . " AND chkpoint_status=" . $status . " AND customerno=" . $this->_Customerno;
        $QueryRes = sprintf($Query);
        $this->_databaseManager->executeQuery($QueryRes);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $status = 1;
        } else {
            $status = 0;
        }
        return $status;
    }

    public function rtdVehicleHistory($vehicleid) {
        $vehicles = array();
        $Query = "  SELECT  oldunit.unitno AS oldunitno
                            ,newunit.unitno AS newunitno
                            ,th.createdon
                            ,team.name
                            ,th.remark
                            ,th.transtypeid
                            ,th.bucketstatusid
                    FROM    `trans_history_new` th
                    LEFT OUTER JOIN " . DB_PARENT . ".team ON team.teamid=th.teamid
                    LEFT OUTER JOIN unit oldunit ON oldunit.uid=th.oldunitid
                    LEFT OUTER JOIN unit newunit ON newunit.uid=th.newunitid
                    where   th.oldvehicleid = " . $vehicleid . "
                    OR      th.newvehicleid = " . $vehicleid . "
                    ORDER BY th.createdon DESC";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle['oldunitid'] = isset($row['oldunitno']) ? $row['oldunitno'] : '0';
                $vehicle['newunitid'] = isset($row['newunitno']) ? $row['newunitno'] : '0';
                $vehicle['createdon'] = date('d-m-Y', strtotime($row['createdon']));
                $vehicle['name'] = $row['name'];
                $vehicle['remark'] = $row['remark'];
                if ($row['transtypeid'] == 1) {
                    $vehicle['task'] = 'Device Installation';
                } elseif ($row['transtypeid'] == 2) {
                    $vehicle['task'] = 'Device and simcard removal';
                } elseif ($row['transtypeid'] == 3) {
                    $vehicle['task'] = 'Simcard replacement';
                } elseif ($row['transtypeid'] == 4) {
                    $vehicle['task'] = 'Device replacement';
                } elseif ($row['transtypeid'] == 5) {
                    $vehicle['task'] = 'Device and simcard replacement';
                } elseif ($row['transtypeid'] == 6) {
                    $vehicle['task'] = 'Device reinstallation';
                } elseif ($row['transtypeid'] == 7) {
                    $vehicle['task'] = 'Device repair';
                }
                if ($row['bucketstatusid'] == 1) {
                    $vehicle['status'] = 'Successful';
                } elseif ($row['bucketstatusid'] == 2) {
                    $vehicle['status'] = 'Unsuccessful';
                } elseif ($row['bucketstatusid'] == 3) {
                    $vehicle['status'] = 'Reschedule';
                } elseif ($row['bucketstatusid'] == 4) {
                    $vehicle['status'] = 'Cancel';
                } elseif ($row['bucketstatusid'] == 5) {
                    $vehicle['status'] = 'Incomplete';
                }
                $vehicles[] = $vehicle;
            }
        }
        return $vehicles;
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

    public function getInactiveDevices($startdate, $enddate) {
        $startdate = $startdate . ' 00:00:00';
        $enddate = $enddate . ' 23:59:00';
        $format = '%Y-%m-%d %H:%i:%s';
        $Query = sprintf("SELECT v.vehicleno ,v.vehicleid,min(di.start_time) as start_time ,max(di.end_time) as end_time,
                            TIMESTAMPDIFF(MINUTE,min(di.start_time),max(di.end_time)) AS time_difference
                            FROM `device_inactive` di
                            INNER JOIN vehicle v ON v.vehicleid = di.vehicleid
                            WHERE di.start_time <= STR_TO_DATE('%s','%s')
                            AND   di.start_time >= STR_TO_DATE('%s','%s')
                            AND   di.customerno = %d
                            GROUP BY v.vehicleno
                            ORDER BY di.id DESC", Sanitise::DateTime($enddate)
            , Sanitise::String($format)
            , Sanitise::DateTime($startdate)
            , Sanitise::String($format)
            , Sanitise::Long($_SESSION['customerno']));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $devices = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device['vehicleno'] = $row['vehicleno'];
                $device['vehicleid'] = $row['vehicleid'];
                $device['start_time'] = $row['start_time'];
                $device['end_time'] = $row['end_time'];
                $device['time_difference'] = gmdate('i:s', $row['time_difference']);
                $devices[] = $device;
            }
            return $devices;
        } else {
            return NULL;
        }
    }

    public function geotrackersUpdateDeviceData($objVehDetail) {
        $objOutput = new stdClass();
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sp_params = "'" . $objVehDetail->vehNo . "'"
        . ",'" . $objVehDetail->deviceLat . "'"
        . ",'" . $objVehDetail->deviceLng . "'"
        . ",'" . $objVehDetail->altitude . "'"
        . ",'" . $objVehDetail->direction . "'"
        . ",'" . $objVehDetail->ignition . "'"
        . ",'" . $objVehDetail->odometer . "'"
        . ",'" . $objVehDetail->speed . "'"
        . ",'" . $objVehDetail->analog1 . "'"
        . ",'" . $objVehDetail->digitalio . "'"
        . ",'" . $objVehDetail->stoppageTransitTime . "'"
        . ",'" . $this->_Customerno . "'"
        . ",'" . $objVehDetail->lastUpdated . "'"
        ;
        $queryCallSP = "CALL " . speedConstants::SP_GEOTRACKER_UPDATE_DEVICE_DETAILS . "($sp_params)";
        $outputResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        /*
        $outputParamsQuery = "SELECT @isUpdated AS isUpdated, @unitNo as unitNo";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
         */
        $this->_databaseManager->ClosePDOConn($pdo);
        if (isset($outputResult) && count($outputResult) > 0) {
            $objOutput->uid = $outputResult['uid'];
            $objOutput->unitNo = $outputResult['unitno'];
            $objOutput->vehicleId = $outputResult['vehicleid'];
            $objOutput->deviceId = $outputResult['deviceid'];
            $objOutput->driverId = $outputResult['driverid'];
            $objOutput->isUpdated = $outputResult['isUpdated'];
        }
        return $objOutput;
    }

    public function apiUpdateDeviceData($objVehDetail) {
        $objOutput = new stdClass();
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sp_params = "'" . $objVehDetail->vehicleNo . "'"
        . ",'" . $objVehDetail->unitNo . "'"
        . ",'" . $objVehDetail->latitude . "'"
        . ",'" . $objVehDetail->longitude . "'"
        . ",'" . $objVehDetail->altitude . "'"
        . ",'" . $objVehDetail->direction . "'"
        . ",'" . $objVehDetail->vehicleBattery . "'"
        . ",'" . $objVehDetail->ignition . "'"
        . ",'" . $objVehDetail->gsmStrength . "'"
        . ",'" . $objVehDetail->odometer . "'"
        . ",'" . $objVehDetail->speed . "'"
        . ",'" . $objVehDetail->temperature1 . "'"
        . ",'" . $objVehDetail->temperature2 . "'"
        . ",'" . $objVehDetail->temperature3 . "'"
        . ",'" . $objVehDetail->temperature4 . "'"
        . ",'" . $objVehDetail->digitalIO . "'"
        . ",'" . $objVehDetail->driverName . "'"
        . ",'" . $this->_Customerno . "'"
        . ",'" . $objVehDetail->lastUpdated . "'";
        $queryCallSP = "CALL " . speedConstants::SP_API_UPDATE_DEVICE_DETAILS . "($sp_params)";
        $outputResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        /*
        $outputParamsQuery = "SELECT @isUpdated AS isUpdated, @unitNo as unitNo";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
         */
        $this->_databaseManager->ClosePDOConn($pdo);
        if (isset($outputResult) && !empty($outputResult) && count($outputResult) > 0) {
            $objOutput->uid = $outputResult['uid'];
            $objOutput->unitNo = $outputResult['unitno'];
            $objOutput->vehicleId = $outputResult['vehicleid'];
            $objOutput->deviceId = $outputResult['deviceid'];
            $objOutput->driverId = $outputResult['driverid'];
            $objOutput->isUpdated = $outputResult['isUpdated'];
        }
        return $objOutput;
    }

    public function insertFuelAlert($objData) {
        $today = date("Y-m-d H:i:s");
        $Query = "INSERT INTO `fuelcron_alertlog`(
                vehicleid,
                countcheck,
                alerttype,
                old_fuel_balance,
                deltaSum,
                old_odometer,
                customerno,
                userid,
                lastupdated
                )
        VALUES (%d,%d,%d,'%s','%s',%d,%d,%d,'%s')";
        $SQL = sprintf($Query, Sanitise::Long($objData->vehicleid), Sanitise::Long($objData->countcheck), Sanitise::Long($objData->isAlert), Sanitise::String($objData->fuelreading), Sanitise::String($objData->deltaSum), Sanitise::String($objData->old_odometer), Sanitise::Long($objData->customerno), Sanitise::Long($objData->userid), Sanitise::String($today));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function updateFuelAlert($objData) {
        $today = date("Y-m-d H:i:s");
        $isExists = 0;
        $sql = "SELECT * from fuelcron_alertlog
                WHERE vehicleid=" . $objData->fuelvehicleid . "
                AND userid=" . $objData->userid . "
                AND customerno=" . $objData->customerno . " AND isdeleted = 0";
        $sqlQuery = sprintf($sql);
        $this->_databaseManager->executeQuery($sqlQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $isExists = 1;
        }
        if ($isExists) {
            $query = "UPDATE fuelcron_alertlog SET
            countcheck = " . $objData->countcheck . ",
            alerttype = " . $objData->isAlert . ",
            old_fuel_balance=" . $objData->fuelreading . ",
            deltaSum=" . $objData->deltaSum . ",
            old_odometer=" . $objData->old_odometer . ",
            customerno = " . $objData->customerno . ",
            userid = " . $objData->userid . ",
            lastupdated='" . $today . "'
            WHERE vehicleid=" . $objData->fuelvehicleid . " AND userid=" . $objData->userid . " AND customerno=" . $objData->customerno;
            $sqlquery = sprintf($query);
            $this->_databaseManager->executeQuery($sqlquery);
        } else {
            $this->insertFuelAlert($objData);
        }
    }

    public function updateFuelOldBalance($objData) {
        $today = date("Y-m-d H:i:s");
        $query = "UPDATE vehicle SET
        old_fuel_balance=" . $objData->old_fuel_balance . ",
        old_fuel_odometer=" . $objData->old_fuel_odometer . ",
        customerno = " . $objData->customerno . ",
        userid = " . $objData->userid . ",
        lastupdated='" . $today . "'
        WHERE vehicleid=" . $objData->vehicleid . " AND customerno=" . $objData->customerno;
        $sqlquery = sprintf($query);
        $this->_databaseManager->executeQuery($sqlquery);
    }

    public function getVehicleNumber($vehicleNo) {
        $query = "SELECT vehicleno FROM vehicle where customerno = '%d' AND vehicleno like '%s'";
        // $sqlQuery = sprintf($query, $this->_Customerno, '%'.$vehicleNo.'%');
        $sqlQuery = sprintf($query, $this->_Customerno, '%' . $vehicleNo . '%');
        $this->_databaseManager->executeQuery($sqlQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $data = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicleNo = $row["vehicleno"];
            }
            return $vehicleNo;
        }
    }

    public function gpsProviderUpdateDeviceData($objVehDetail) {
        $objVehDetail->analog2 = isset($objVehDetail->analog2) ? $objVehDetail->analog2 : 0;
        $objVehDetail->stoppageOdometer = isset($objVehDetail->stoppage_odometer) ? $objVehDetail->stoppage_odometer : 0;
        $objVehDetail->stoppageFlag = isset($objVehDetail->stoppage_flag) ? $objVehDetail->stoppage_flag : 0;
        $objVehDetail->customerNo = isset($objVehDetail->customerNo) ? $objVehDetail->customerNo : $this->_Customerno;
        $objOutput = new stdClass();
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sp_params = "'" . $objVehDetail->vehNo . "'"
        . ",'" . $objVehDetail->deviceLat . "'"
        . ",'" . $objVehDetail->deviceLng . "'"
        . ",'" . $objVehDetail->altitude . "'"
        . ",'" . $objVehDetail->direction . "'"
        . ",'" . $objVehDetail->ignition . "'"
        . ",'" . $objVehDetail->odometer . "'"
        . ",'" . $objVehDetail->speed . "'"
        . ",'" . $objVehDetail->analog1 . "'"
        . ",'" . $objVehDetail->analog2 . "'"
        . ",'" . $objVehDetail->digitalio . "'"
        . ",'" . $objVehDetail->stoppageTransitTime . "'"
        . ",'" . $objVehDetail->stoppageOdometer . "'"
        . ",'" . $objVehDetail->stoppageFlag . "'"
        // . ",'" . $this->_Customerno . "'"
         . ",'" . $objVehDetail->customerNo . "'"
        . ",'" . $objVehDetail->lastUpdated . "'"
        ;
        //print_r($sp_params); die;
        $queryCallSP = "CALL " . speedConstants::SP_GPSPROVIDER_UPDATE_DEVICE_DETAILS . "($sp_params)";
        $outputResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        /*
        $outputParamsQuery = "SELECT @isUpdated AS isUpdated, @unitNo as unitNo";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
         */
        $this->_databaseManager->ClosePDOConn($pdo);
        if (is_array($outputResult) && isset($outputResult) && count($outputResult) > 0) {
            $objOutput->uid = $outputResult['uid'];
            $objOutput->unitNo = $outputResult['unitno'];
            $objOutput->vehicleId = $outputResult['vehicleid'];
            $objOutput->deviceId = $outputResult['deviceid'];
            $objOutput->driverId = $outputResult['driverid'];
            $objOutput->isUpdated = $outputResult['isUpdated'];
        }
        return $objOutput;
    }

    public function getVehiclesByUser($userid, $term) {
        $customerno = $_SESSION['customerno'];
        $term = '%' . $term . '%';
        if ($_SESSION['groupid'] == '0') {
            $Query = '  SELECT  b.vehicleid
                                ,b.vehicleno
                                ,t.temp1_min_email
                                ,t.temp1_max_email
                                ,t.temp2_min_email
                                ,t.temp2_max_email
                                ,t.temp3_min_email
                                ,t.temp3_max_email
                                ,t.temp4_min_email
                                ,t.temp4_max_email
                                ,t.temp1_min_sms
                                ,t.temp1_max_sms
                                ,t.temp2_min_sms
                                ,t.temp2_max_sms
                                ,t.temp3_min_sms
                                ,t.temp3_max_sms
                                ,t.temp4_min_sms
                                ,t.temp4_max_sms
                        FROM    user as a
                        LEFT JOIN vehicle as b on a.customerno = b.customerno
                        LEFT OUTER JOIN advancetempalertrange as t ON t.vehicleid = b.vehicleid AND a.userid = t.userid AND t.isdeleted = 0
                        WHERE   a.customerno = %d and a.userid = %d
                        AND     b.vehicleno LIKE "%s"
                        AND     a.isdeleted = 0
                        AND     b.isdeleted = 0';
            $vehiclesQuery = sprintf($Query, $customerno, $userid, $term);
        } else {
            $Query = '  SELECT  b.vehicleid
                                ,b.vehicleno
                                ,t.temp1_min_email
                                ,t.temp1_max_email
                                ,t.temp2_min_email
                                ,t.temp2_max_email
                                ,t.temp3_min_email
                                ,t.temp3_max_email
                                ,t.temp4_min_email
                                ,t.temp4_max_email
                                ,t.temp1_min_sms
                                ,t.temp1_max_sms
                                ,t.temp2_min_sms
                                ,t.temp2_max_sms
                                ,t.temp3_min_sms
                                ,t.temp3_max_sms
                                ,t.temp4_min_sms
                                ,t.temp4_max_sms
                        FROM    groupman as a
                        LEFT JOIN vehicle as b on a.groupid = b.groupid and a.customerno = b.customerno
                        LEFT JOIN user as c on a.userid = c.userid and a.customerno = c.customerno
                        LEFT JOIN advancetempalertrange as t ON t.vehicleid = b.vehicleid AND t.userid = a.userid AND t.isdeleted = 0
                        WHERE   a.customerno = %d
                        AND     b.vehicleno LIKE "%s"
                        AND     a.userid = %d
                        AND     a.isdeleted = 0
                        AND     b.isdeleted = 0
                        AND     c.isdeleted = 0';
            $vehiclesQuery = sprintf($Query, $customerno, $term, $userid);
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $vehicles = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle['vid'] = $row['vehicleid'];
                $vehicle['value'] = $row['vehicleno'];
                $vehicle['temp1_min_email'] = isset($row['temp1_min_email']) ? $row['temp1_min_email'] : NULL;
                $vehicle['temp1_max_email'] = isset($row['temp1_max_email']) ? $row['temp1_max_email'] : NULL;
                $vehicle['temp2_min_email'] = isset($row['temp2_min_email']) ? $row['temp2_min_email'] : NULL;
                $vehicle['temp2_max_email'] = isset($row['temp2_max_email']) ? $row['temp2_max_email'] : NULL;
                $vehicle['temp3_min_email'] = isset($row['temp3_min_email']) ? $row['temp3_min_email'] : NULL;
                $vehicle['temp3_max_email'] = isset($row['temp3_max_email']) ? $row['temp3_max_email'] : NULL;
                $vehicle['temp4_min_email'] = isset($row['temp4_min_email']) ? $row['temp4_min_email'] : NULL;
                $vehicle['temp4_max_email'] = isset($row['temp4_max_email']) ? $row['temp4_max_email'] : NULL;
                $vehicle['temp1_min_sms'] = isset($row['temp1_min_sms']) ? $row['temp1_min_sms'] : NULL;
                $vehicle['temp1_max_sms'] = isset($row['temp1_max_sms']) ? $row['temp1_max_sms'] : NULL;
                $vehicle['temp2_min_sms'] = isset($row['temp2_min_sms']) ? $row['temp2_min_sms'] : NULL;
                $vehicle['temp2_max_sms'] = isset($row['temp2_max_sms']) ? $row['temp2_max_sms'] : NULL;
                $vehicle['temp3_min_sms'] = isset($row['temp3_min_sms']) ? $row['temp3_min_sms'] : NULL;
                $vehicle['temp3_max_sms'] = isset($row['temp3_max_sms']) ? $row['temp3_max_sms'] : NULL;
                $vehicle['temp4_min_sms'] = isset($row['temp4_min_sms']) ? $row['temp4_min_sms'] : NULL;
                $vehicle['temp4_max_sms'] = isset($row['temp4_max_sms']) ? $row['temp4_max_sms'] : NULL;
                array_push($vehicles, $vehicle);
            }
            return json_encode($vehicles);
        }
    }

    public function getAdvanceTempRangeVeh($uid) {
        $query = "  SELECT  t.vehicleid
                            ,v.vehicleno
                    FROM    `advancetempalertrange` t
                    INNER JOIN `vehicle` v ON v.vehicleid = t.vehicleid
                    WHERE   t.userid = '%d'
                    AND     t.isdeleted = 0";
        $sqlQuery = sprintf($query, $uid);
        $this->_databaseManager->executeQuery($sqlQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $vehicles = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle['vehicleid'] = $row["vehicleid"];
                $vehicle['vehicleno'] = $row["vehicleno"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return NULL;
    }

    public function getDashboardVehicleData($objTrip) {
        $arrVehicleData = null;
        $objOutput = new stdClass();
        $pdo = $this->_databaseManager->CreatePDOConn();
        try {
            $sp_params = "'" . $objTrip->vehicleType . "'"
            . ",'" . $objTrip->customerno . "'"
            . ",'" . $objTrip->currentDate . "'";
            $queryCallSP = "CALL " . speedConstants::SP_GET_DASHBOARD_VEHICLES . "($sp_params)";
            $arrVehicleData = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, 0, "Trip", __FUNCTION__);
        }
        return $arrVehicleData;
    }

    public function getDashboardVehicleETADetails($objTrip) {
        $result = null;
        $sql_trip = "";
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            $sp_params =
            "'" . $objTrip->customerno . "'"
            . ",'" . $objTrip->currentDate . "'"
            . ",'" . $objTrip->vehicleType . "'"
            ;
            $queryCallSP = "CALL " . speedConstants::SP_GET_DASHBOARD_VEHICLEDETAILS_FOR_ETA . "($sp_params)";
            $result = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, 0, "Trip", __FUNCTION__);
        }
        return $result;
    }

    public function updateTripYardLog($objTrip) {
        $result = null;
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            $sp_params =
            "'" . $objTrip->vehicleid . "'"
            . ",'" . $objTrip->checkpointid . "'"
            . ",'" . $objTrip->eta . "'"
            . ",'" . $objTrip->ata . "'"
            . ",'" . $objTrip->customerno . "'"
            . ",'" . $objTrip->userid . "'"
            . ",'" . $objTrip->todaydate . "'";
            $queryCallSP = "CALL " . speedConstants::SP_UPDATE_TRIP_LOG . "($sp_params)";
            $result = $pdo->query($queryCallSP);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, 0, "Trip", __FUNCTION__);
        }
        return $result;
    }

    public function getInactiveDevicesPerVehicle($vehicleid, $startdate, $enddate) {
        /* $startdate = $startdate . ' 00:00:00';
        $format = '%Y-%m-%d %H:%i:%s'; */
        $Query = sprintf("SELECT v.vehicleno ,v.vehicleid,di.start_time,di.end_time
                            FROM `device_inactive` di
                            INNER JOIN vehicle v ON v.vehicleid = di.vehicleid
                            WHERE di.start_time <= '%s'
                            AND   di.start_time >='%s'
                            AND di.customerno = %d
                            AND v.vehicleid= %d
                            ORDER BY di.id ASC", Sanitise::DateTime($enddate)
            , Sanitise::DateTime($startdate)
            , Sanitise::Long($_SESSION['customerno'])
            , Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $devices = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device['vehicleno'] = $row['vehicleno'];
                $device['vehicleid'] = $row['vehicleid'];
                $device['start_time'] = ($row['start_time'] != '0000-00-00 00:00:00') ? $row['start_time'] : '';
                $device['end_time'] = ($row['end_time'] != '0000-00-00 00:00:00') ? $row['end_time'] : '';
                if ($row['start_time'] != '0000-00-00 00:00:00' && $row['end_time'] != '0000-00-00 00:00:00') {
                    $startTime = strtotime($row['start_time']);
                    $endTime = strtotime($row['end_time']);
                    $date1 = date_create($row['start_time']);
                    $date2 = date_create($row['end_time']);
                    $diff = date_diff($date1, $date2);
                    $device['time_diff'] = $diff->format("%H:%I:%S");
                } else {
                    $device['time_diff'] = "--";
                }
                $devices[] = $device;
            }
            return $devices;
        } else {
            return NULL;
        }
    }

    public function getVehicleDataFromUnitNo($objVehDetail) {
        $objOutput = new stdClass();
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sp_params = "'" . $objVehDetail->vehicleNo . "'"
        . ",'" . $objVehDetail->unitNo . "'"
        . ",'" . $this->_Customerno . "'"
        ;
        $queryCallSP = "CALL " . speedConstants::SP_GET_VEHICLE_DATA_FROM_UNIT_NO . "($sp_params)";
        $outputResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        if (isset($outputResult) && count($outputResult) > 0) {
            $objOutput->uid = $outputResult['uid'];
            $objOutput->unitNo = $outputResult['unitno'];
            $objOutput->vehicleId = $outputResult['vehicleid'];
            $objOutput->deviceId = $outputResult['deviceid'];
            $objOutput->driverId = $outputResult['driverid'];
        }
        return $objOutput;
    }

    public function get_driver_by_driverid($driverid) {
        $Query = "  SELECT  driver.drivername
                    FROM    driver
                    WHERE   driver.driverid = %d";
        $vehiclesQuery = sprintf($Query, Sanitise::Long($driverid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $drivername = $row['drivername'];
            }
            return $drivername;
        }
        return null;
    }

    public function get_sorted_vehicles($vehicleidList) {
        $Query = "  SELECT  v.vehicleid
                    FROM    vehicle v
                    INNER JOIN unit AS u ON u.uid = v.uid
                    INNER JOIN devices AS d ON d.uid = u.uid
                    WHERE   v.vehicleid  IN (%s)
                    AND     u.trans_statusid NOT IN (10,22)
                    ORDER BY v.sequenceno = 0,v.sequenceno ASC,v.groupid ASC";
        $vehiclesQuery = sprintf($Query, Sanitise::String($vehicleidList));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicleid[] = $row['vehicleid'];
            }
            return $vehicleid;
        }
        return null;
    }

    public function getNomensList($custno, $nid) {
        $arrResult = array();
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            //Prepare parameters
            $sp_params =
                "" . $custno . ""
                . "," . $nid . "";
            $queryCallSP = "CALL " . speedConstants::SP_GET_NOMENS . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
            $this->_databaseManager->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            //Log
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, null, __FUNCTION__);
        }
        return $arrResult;
    }

    public function addNomens($nomensArray) {
        $arrResult = array();
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            //Prepare parameters
            $sp_params =
            "'" . $nomensArray->nomens . "'"
            . "," . $nomensArray->customerno . ""
            . ",'" . $nomensArray->created_on . "'"
            . "," . $nomensArray->created_by . ""
            . ",'" . $nomensArray->created_on . "'"
            . "," . $nomensArray->created_by . ""
            ;
            $queryCallSP = "CALL " . speedConstants::SP_INSERT_NOMENS . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
            $this->_databaseManager->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            //Log
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, null, __FUNCTION__);
        }
        return $arrResult[0];
    }

    public function editNomens($nomensArray) {
        $arrResult = array();
        $status = "false";
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            //Prepare parameters
            $sp_params = "" . $nomensArray->nid . ""
            . ",'" . $nomensArray->nomens . "'"
            . "," . $nomensArray->customerno . ""
            . "," . $nomensArray->isdeleted . ""
            . ",'" . $nomensArray->updated_on . "'"
            . "," . $nomensArray->updated_by . ""
            ;
            $queryCallSP = "CALL " . speedConstants::SP_UPDATE_NOMENS . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
            $status = "true";
            $this->_databaseManager->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, null, __FUNCTION__);
        }
        return $status;
    }

    public function deleteNomens($nomensArray) {
        $arrResult = array();
        $status = "false";
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            //Prepare parameters
            $sp_params = "" . $nomensArray->nid . ""
            . ",'" . $nomensArray->nomens . "'"
            . "," . $nomensArray->customerno . ""
            . "," . $nomensArray->isdeleted . ""
            . ",'" . $nomensArray->updated_on . "'"
            . "," . $nomensArray->updated_by . ""
            ;
            $queryCallSP = "CALL " . speedConstants::SP_UPDATE_NOMENS . "($sp_params)";
            $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
            $status = "true";
            $this->_databaseManager->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, null, __FUNCTION__);
        }
        return $status;
    }

    public function getNomensName($deviceid, $customerno, $temp_sensors) {
        $arrResult = array();
        $Query = "    SELECT  u.n1,u.n2,u.n3,u.n4
                        FROM    unit u
                        INNER JOIN devices AS d ON d.uid = u.uid
                        WHERE   d.deviceid  ='" . $deviceid . "' AND u.customerno = " . $customerno . "";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                if ($temp_sensors == '1') {
                    if ($row['n1'] != 0) {
                        $nomensName['1-0'] = getName_ByType($row['n1']);
                    } else {
                        $nomensName['1-0'] = 'Temperature 1';
                    }
                } elseif ($temp_sensors == '2') {
                    if ($row['n1'] != 0) {
                        $nomensName['1-0'] = getName_ByType($row['n1']);
                    } else {
                        $nomensName['1-0'] = 'Temperature 1';
                    }
                    if ($row['n2'] != 0) {
                        $nomensName['2-0'] = getName_ByType($row['n2']);
                    } else {
                        $nomensName['2-0'] = 'Temperature 2';
                    }
                } elseif ($temp_sensors == '3') {
                    if ($row['n1'] != 0) {
                        $nomensName['1-0'] = getName_ByType($row['n1']);
                    } else {
                        $nomensName['1-0'] = 'Temperature 1';
                    }
                    if ($row['n2'] != 0) {
                        $nomensName['2-0'] = getName_ByType($row['n2']);
                    } else {
                        $nomensName['2-0'] = 'Temperature 2';
                    }
                    if ($row['n3'] != 0) {
                        $nomensName['3-0'] = getName_ByType($row['n3']);
                    } else {
                        $nomensName['3-0'] = 'Temperature 3';
                    }
                } else {
                    if ($row['n1'] != 0) {
                        $nomensName['1-0'] = getName_ByType($row['n1']);
                    } else {
                        $nomensName['1-0'] = 'Temperature 1';
                    }
                    if ($row['n2'] != 0) {
                        $nomensName['2-0'] = getName_ByType($row['n2']);
                    } else {
                        $nomensName['2-0'] = 'Temperature 2';
                    }
                    if ($row['n3'] != 0) {
                        $nomensName['3-0'] = getName_ByType($row['n3']);
                    } else {
                        $nomensName['3-0'] = 'Temperature 3';
                    }
                    if ($row['n4'] != 0) {
                        $nomensName['4-0'] = getName_ByType($row['n4']);
                    } else {
                        $nomensName['4-0'] = 'Temperature 4';
                    }
                }
            }
            return $nomensName;
        } else {
            return null;
        }
    }

    public function getDuplicateUnitsByVehicleNo($objVehDetail) {
        // print_r($objVehDetail);
        $outputResult = array();
        $objVehDetail->vehNo = isset($objVehDetail->vehNo) ? $objVehDetail->vehNo : '';
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            $sp_params = "'" . $objVehDetail->vehNo . "'"
            ;
            $queryCallSP = "CALL " . speedConstants::SP_GET_DUPLICATE_UNITS_BY_VEHICLE_NO . "($sp_params)";
            $outputResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
            // print_r($outputResult); die;
            $this->_databaseManager->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, null, __FUNCTION__);
        }
        // print("<Pre>"); print_r($outputResult); die;
        return $outputResult;
    }

    public function checkNomensIfExist($nomensArray) {
        $arrResult = array();
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            //Prepare parameters
            $sp_params =
            "'" . $nomensArray->nomens . "'"
            . "," . $nomensArray->customerno . ""
            ;
            $queryCallSP = "CALL " . speedConstants::SP_CHECK_IF_EXIST_NOMENS . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
            $this->_databaseManager->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            //Log
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, null, __FUNCTION__);
        }
        return $arrResult[0];
    }

    public function getSmsStoreLog($startdate, $enddate, $customerno) {
        // print_r($objVehDetail);
        $startdate = date('Y-m-d', strtotime($startdate));
        $enddate = date('Y-m-d', strtotime($enddate));
        /*$startdate = "2017-06-21";
        $enddate = "2018-06-21";
         */
        $outputResult = array();
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            $sp_params = "'" . $startdate . "','"
                . $enddate . "','"
                . $customerno . "'";
            $queryCallSP = "CALL " . speedConstants::SP_GET_SMS_STORE_LOG . "($sp_params)";
            $outputResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
            // print_r($outputResult); die;
            $this->_databaseManager->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, null, __FUNCTION__);
        }
        return $outputResult;
    }

    public function getVehicleHistoryLogs($obj) {
        $uiList = array();
        $uiList = ["vehicleno", "type", "overspeed_limit", "average", "fuelcapacity", "groupname", "isdeleted"];
        $arrResult = array();
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $_SESSION['customerno'] . "'" .
        ",'" . $obj->vehicleId . "'" .
        ",'" . date("Y-m-d", strtotime($obj->start_date)) . "'" .
        ",'" . date("Y-m-d", strtotime($obj->end_date)) . "'" .
        ",'" . $obj->total_records . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_VEHICLE_LOGS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $finalArray = array();
        foreach ($arrResult as $k => &$row) {
            $data['vehicleid'] = $row['vehicleid'];
            $data['isdeleted'] = $row['isdeleted'];
            if ($data['isdeleted'] == 1) {
                $data['isdeleted'] = 'Deleted';
            } else {
                $data['isdeleted'] = '';
            }
            $data['type'] = $row['kind'];
            $data['vehicleno'] = $row['vehicleno'];
            $data['overspeed_limit'] = $row['overspeed_limit'];
            $data['average'] = $row['average'];
            $data['fuelcapacity'] = $row['fuelcapacity'];
            $data['groupname'] = $row['groupname'];
            $data['temp1_min'] = $row['temp1_min'];
            $data['temp1_max'] = $row['temp1_max'];
            $data['temp1_allowance '] = $row['temp1_allowance'];
            $data['temp2_min'] = $row['temp2_min'];
            $data['temp2_max'] = $row['temp2_max'];
            $data['temp2_allowance'] = $row['temp2_allowance'];
            $data['temp3_min'] = $row['temp3_min'];
            $data['temp3_max'] = $row['temp3_max'];
            $data['temp3_allowance'] = $row['temp3_allowance'];
            $data['temp4_min'] = $row['temp4_min'];
            $data['temp4_max'] = $row['temp4_max'];
            $data['temp4_allowance'] = $row['temp4_allowance'];
            $data['hum_min'] = $row['hum_min'];
            $data['hum_max'] = $row['hum_max'];
            $data['insertedOn'] = date("d-m-Y H:i:s", strtotime($row['insertedOn']));
            $data['insertedBy'] = $row['realname'];
            $finalArray[] = $data;
        }
        $finalArray = array_reverse($finalArray);
        foreach ($finalArray as $k => &$record) {
            foreach ($record as $column => &$value) {
                if (in_array($column, $uiList)) {
                    $record[$column . "ui"] = $value;
                    if (isset($finalArray[$k - 1][$column])) {
                        if (isset($finalArray[$k - 1][$column]) && $finalArray[$k - 1][$column] != $value) {
                            $record[$column . "ui"] = "<font color='green'>" . $value . "</font>";
                            $finalArray[$k - 1][$column . "ui"] = "<font color='red'>" . $finalArray[$k - 1][$column] . "</font>";
                        }
                    }
                }
            }
            $previousRecord = $record;
        }
        $finalArray = array_reverse($finalArray);
        return $finalArray;
    }

    public function getChk_And_FenceLogs($obj) {
        $arrResult = array();
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $_SESSION['customerno'] . "'" .
        ",'" . $obj->vehicleId . "'" .
        ",'" . date("Y-m-d", strtotime($obj->start_date)) . "'" .
        ",'" . date("Y-m-d", strtotime($obj->end_date)) . "'" .
        ",'" . $obj->total_records . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_CHECKPOINT_LOGS . "(" . $sp_params . ")";
        $arrResult1 = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $queryCallSP = "CALL " . speedConstants::SP_GET_FENCE_LOGS . "(" . $sp_params . ")";
        $arrResult2 = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $finalArray = array();
        foreach ($arrResult1 as $row) {
            $data['vehicleno'] = $row['vehicleno'];
            $data['chk_name'] = $row['cname'];
            $data['insertedOn'] = date("d-m-Y H:i:s", strtotime($row['insertedOn']));
            $data['insertedBy'] = $row['realname'];
            $data['isdeleted'] = $row['isdeleted'];
            $finalArray['checkpoint'][] = $data;
        }
        foreach ($arrResult2 as $row) {
            $data['vehicleno'] = $row['vehicleno'];
            $data['fencename'] = $row['fencename'];
            $data['insertedOn'] = date("d-m-Y H:i:s", strtotime($row['insertedOn']));
            $data['insertedBy'] = $row['realname'];
            $data['isdeleted'] = $row['isdeleted'];
            $finalArray['fence'][] = $data;
        }
        return $finalArray;
    }

    public function isUserVehicleMappingExists($userId, $vehicleId) {
        $status = false;
        $Query = 'SELECT vu.userid FROM vehicleusermapping as vu WHERE vu.userid=%d AND vu.vehicleid=%d AND vu.customerno=%d AND vu.isdeleted=0';
        $vehiclesQuery = sprintf($Query, $userId, $vehicleId, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $status = true;
        }
        return $status;
    }

    public function getUnitHistoryLogs($obj) {
        $arrResult = array();
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConn();
        $sp_params = "'" . $_SESSION['customerno'] . "'" .
        ",'" . $obj->unitId . "'" .
        ",'" . date("Y-m-d", strtotime($obj->start_date)) . "'" .
        ",'" . date("Y-m-d", strtotime($obj->end_date)) . "'" .
        ",'" . $obj->total_records . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_UNIT_LOGS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $finalArray = array();
        foreach ($arrResult as $row) {
            $data['unitno'] = $row['unitno'];
            $data['vehicleno'] = $row['vehicleno'];
            $data['insertedOn'] = date("d-m-Y H:i:s", strtotime($row['updatedOn']));
            $data['insertedBy'] = $row['realname'];
            $finalArray[] = $data;
        }
        return $finalArray;
    }

    public function getVehiclesById($vehicleId) {
        $Query = "SELECT vehicle.vehicleno,vehicle.vehicleid,vehicle.curspeed, vehicle.overspeed_limit,vehicle.stoppage_transit_time,vehicle.lastupdated,devices.registeredon,devices.ignition,vehicle.stoppage_flag,d.runningtime,d.first_odometer,d.last_odometer,d.max_odometer,devices.devicelat,devices.devicelong
            FROM
                vehicle
                    INNER JOIN
                devices ON devices.uid = vehicle.uid
                    INNER JOIN
                unit ON devices.uid = unit.uid
                    INNER JOIN
                dailyreport d ON d.vehicleid = vehicle.vehicleid
            WHERE
                vehicle.customerno = %d
                    AND vehicle.vehicleid = %d
                    AND unit.trans_statusid NOT IN (10 , 22)
                    AND vehicle.isdeleted = 0
            ORDER BY devices.lastupdated DESC LIMIT 1";
        $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::Long($vehicleId));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new stdClass();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->stoppage_transit_time = $row['stoppage_transit_time'];
                $vehicle->lat = $row['devicelat'];
                $vehicle->long = $row['devicelong'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                }
                $vehicle->lastupdated_date = date("Y-m-d", strtotime($vehicle->lastupdated));
                $vehicle->ignition = $row['ignition'];
                $vehicle->stoppage_flag = $row["stoppage_flag"];
                $vehicle->runningtime = $row['runningtime'];
                $last_odometer = $row['last_odometer'];
                $vehicle->total_distance = $last_odometer - $row['first_odometer'];
                if ($vehicle->total_distance < 0) {
                    $last_odometer = $row['max_odometer'] + $last_odometer;
                    $vehicle->total_distance = abs($last_odometer - $row['first_odometer']);
                }
            }
            return $vehicle;
        }
        return null;
    }

    public function getVehicleByCust($customerNo) {
        $Query = "SELECT v.vehicleid FROM vehicle v
                  INNER JOIN unit u on u.uid = v.uid AND u.trans_statusid NOT IN (10,22)
                  WHERE v.customerno=%d";
        $SQL = sprintf($Query, $customerNo);
        $this->_databaseManager->executeQuery($SQL);
        while ($row = $this->_databaseManager->get_nextRow()) {
            $vehicle = new stdClass();
            $vehicle->vehicleid = $row['vehicleid'];
            $vehicles[] = $vehicle;
        }
        return $vehicles;
    }

    public function getTravelSettingList() {
        $customerno = $_SESSION['customerno'];
        $arrResult = array();
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            $sp_params =
                "" . $customerno . ""
                . ",0";
            $queryCallSP = "CALL " . speedConstants::SP_GET_TRAVELSETTINGLIST . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
            $this->_databaseManager->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            //Log
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, null, __FUNCTION__);
        }
        return $arrResult;
    }

    public function addTravelSetting($travelSettingArray) {
        $arrResult = array();
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            //Prepare parameters
            $sp_params = "'" . $travelSettingArray->starttime . "'"
            . ",'" . $travelSettingArray->endtime . "'"
            . ",'" . $travelSettingArray->threshold . "'"
            . "," . $travelSettingArray->customerno . ""
            . ",'" . $travelSettingArray->created_on . "'"
            . "," . $travelSettingArray->created_by . "";
            $queryCallSP = "CALL " . speedConstants::SP_INSERT_TRAVEL_SETTINGS . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
            $this->_databaseManager->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            //Log
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, null, __FUNCTION__);
        }
        return $arrResult[0];
    }

    public function editTravelSetting($travelSettingArray) {
        $arrResult = array();
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            //Prepare parameters
            $sp_params = "'" . $travelSettingArray->travelsettingid . "'"
            . ",'" . $travelSettingArray->starttime . "'"
            . ",'" . $travelSettingArray->endtime . "'"
            . ",'" . $travelSettingArray->threshold . "'"
            . "," . $travelSettingArray->customerno . ""
            . ",'" . $travelSettingArray->updated_on . "'"
            . "," . $travelSettingArray->updated_by . ""
            . "," . $travelSettingArray->isdeleted . "";
            $queryCallSP = "CALL " . speedConstants::SP_EDIT_TRAVEL_SETTINGS . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetchall(PDO::FETCH_ASSOC);
            $this->_databaseManager->ClosePDOConn($pdo);
        } catch (Exception $ex) {
            //Log
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, null, __FUNCTION__);
        }
        return $arrResult[0];
    }

    public function getFrozenVehicles($customerNo) {
        $Query = "  SELECT v.vehicleid,v.vehicleno
                    FROM vehicle v
                    INNER JOIN freezelog f on f.vehicleid = v.vehicleid and f.isdeleted=0
                    WHERE v.customerno=%d and v.isdeleted = 0";
        $SQL = sprintf($Query, $customerNo);
        $this->_databaseManager->executeQuery($SQL);
        while ($row = $this->_databaseManager->get_nextRow()) {
            $vehicle = new stdClass();
            $vehicle->vehicleid = $row['vehicleid'];
            $vehicle->vehicleno = $row['vehicleno'];
            $vehicles[] = $vehicle;
        }
        return $vehicles;
    }

    public function getVehicleStatusData() {
        $Query = "select id,`status`,color_code from vehicle_common_status_master where customerno IN(0," . $_SESSION['customerno'] . ") AND isDeleted=0";
        $this->_databaseManager->executeQuery($Query);
        while ($row = $this->_databaseManager->get_nextRow()) {
            $vehicle = new stdClass();
            $vehicle->vehicleStatusId = $row['id'];
            $vehicle->vehicleStatus = $row['status'];
            $vehicle->color_code = $row['color_code'];
            $vehicles[] = $vehicle;
        }
        return $vehicles;
    }

    public function updateVehicleCommonStatus($vehicleId, $vehicleStatusId) {
        $Query = "UPDATE vehicle SET vehicle_status = " . $vehicleStatusId . " WHERE vehicleid =" . $vehicleId . " AND customerno = " . $this->_Customerno;
        $this->_databaseManager->executeQuery($Query);
    }

    public function getVehiclesForStoppageReport($groups, $isWarehouse = null, $fetchVehiclesForUserOnly = 0, $userId = 0) {
        $today = date("Y-m-d H:i:s");
        $vehicles = Array();
        $vehiclekind = '';
        $list = "vehicle.vehicleid
            ,vehicle.customerno
            ,vehicle.vehicleno
            ,unit.uid
            ,unit.unitno
            ,devices.deviceid
            ,vehicle.lastupdated
            ,DATEDIFF('" . $today . "',vehicle.lastupdated) as inactive_days
            ,devices.ignition
            ,vehicle.curspeed
            ,`group`.groupname
            ,vehicle.stoppage_flag
            ,vehicle.stoppage_transit_time
            ,ia.ignontime";
        if ($isWarehouse == null) {
            $vehiclekind = ' and vehicle.kind<> "Warehouse"';
        } else {
            $vehiclekind = ' and vehicle.kind="Warehouse"';
        }
        if ($fetchVehiclesForUserOnly == 1) {
            $Query = 'SELECT ' . $list . ' FROM vehicle
                INNER JOIN unit on unit.vehicleid=vehicle.vehicleid
                INNER JOIN devices on devices.uid=vehicle.uid
                INNER JOIN ignitionalert as ia on  ia.vehicleid = vehicle.vehicleid
                LEFT JOIN `group` ON `group`.groupid = vehicle.groupid
                LEFT JOIN simcard ON simcard.id = devices.simcardid';
            $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $userId . "";
            $Query .= ' WHERE (vehicle.customerno=%d )
                AND vehicle.isdeleted=0 ' . $vehiclekind . ' and vehmap.isdeleted = 0 and unit.trans_statusid NOT IN (10,22) ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.sequenceno ASC,vehicle.lastupdated ASC';
            $vehiclesQuery = sprintf($Query, $this->_Customerno);
        } elseif (isset($_SESSION['roleid']) && $_SESSION['roleid'] == 43) {
            // elseif (isset($_SESSION['roleid']) && $this->VehicleUsermappingExists($_SESSION['userid']) == true) {
            $Query = 'SELECT ' . $list . ' FROM vehicle
            INNER JOIN unit on unit.vehicleid=vehicle.vehicleid
            INNER JOIN devices on devices.uid=vehicle.uid
            INNER JOIN ignitionalert as ia on  ia.vehicleid = vehicle.vehicleid
            LEFT JOIN `group` ON `group`.groupid = vehicle.groupid
            LEFT JOIN simcard ON simcard.id = devices.simcardid';
            if ($groups[0] != 0) {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid in (%s)";
            } else {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . "";
            }
            $Query .= ' WHERE (vehicle.customerno=%d )
            AND vehicle.isdeleted=0 ' . $vehiclekind . ' and vehmap.isdeleted = 0 and unit.trans_statusid NOT IN (10,22) ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.sequenceno ASC,vehicle.lastupdated ASC';
            if ($groups[0] == 0) {
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            } else {
                $group_in = implode(',', $groups);
                $vehiclesQuery = sprintf($Query, $group_in, $this->_Customerno);
            }
        } else {
            $Query = 'SELECT ' . $list . ' FROM vehicle
            INNER JOIN unit on unit.vehicleid=vehicle.vehicleid
            INNER JOIN devices on devices.uid=vehicle.uid
            INNER JOIN ignitionalert as ia on  ia.vehicleid = vehicle.vehicleid
            LEFT JOIN `group` ON `group`.groupid = vehicle.groupid
            LEFT JOIN simcard ON simcard.id = devices.simcardid
            WHERE (vehicle.customerno=%d )
            AND vehicle.isdeleted=0 ' . $vehiclekind . ' and unit.trans_statusid NOT IN (10,22)';
            if (!array_key_exists('0', $groups) || $groups[0] == 0) {
                $Query .= " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.sequenceno ASC,vehicle.lastupdated ASC";
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            } else {
                $group_in = implode(',', $groups);
                $Query .= " AND vehicle.groupid in (%s) ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.sequenceno ASC,vehicle.lastupdated ASC";
                $vehiclesQuery = sprintf($Query, $this->_Customerno, $group_in);
            }
        }
        $vehiclesQuery = $vehiclesQuery;
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->customerno = $row['customerno'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->uid = $row['uid'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->deviceid = $row['deviceid'];
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated_store = $row['lastupdated'];
                    $vehicle->lastupdated = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated_store = $row['lastupdated'];
                    $vehicle->lastupdated = $row['lastupdated'];
                }
                $vehicle->inactive_days = $row['inactive_days'];
                $vehicle->ignition = $row['ignition'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->groupname = $row['groupname'];
                $vehicle->stoppage_flag = $row['stoppage_flag'];
                $vehicle->stoppage_transit_time = $row['stoppage_transit_time'];
                $vehicle->ignontime = $row['ignontime'];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function insertNotes($vehicleId = '', $customerno = '', $notes = '', $userid = '') {
        if (!empty($vehicleId) && !empty($customerno) && !empty($notes)) {
            $date = date("Y-m-d H:i:s");
            $arrData = array();
            $sql = "INSERT INTO `vehiclenotes`(`noteId`, `vehicleId`, `note`, `customerNo`, `createdBy`, `createdOn`, `updatedBy`, `updatedOn`, `isDeleted`)
            VALUES (null, '" . $vehicleId . "', '" . $notes . "', '" . $customerno . "', '" . $userid . "', '" . $userid . "', '" . $userid . "', '" . $date . "', '0')";
            $pdo = $this->_databaseManager->CreatePDOConn();
            $response = $pdo->query($sql);
            if ($response) {
                $arrResult = $this->getRecentNotes($customerno, $vehicleId);
                if (!empty($arrResult)) {
                    echo json_encode($arrResult);
                } else {
                    return array();
                }
            }
        } else {
            return array();
        }
    }

    public function getRecentNotes($customer = '', $vehicleId = '') {
        if (!empty($customer) && !empty($vehicleId)) {
            $pdo = $this->_databaseManager->CreatePDOConn();
            $sql = "SELECT * from vehiclenotes where vehicleId = " . $vehicleId . " and customerNo = '" . $customer . "' and isDeleted = '0' ORDER BY noteId DESC";
            $result = $pdo->query($sql);
            $arrResponse = $arrData = array();
            require '../../lib/bo/UserManager.php';
            $um = new UserManager();
            while ($row = $result->fetchObject()) {
                $arrData['noteId'] = $row->noteId;
                $arrData['vehicleId'] = $row->vehicleId;
                $arrData['customerno'] = $row->customerNo;
                $arrData['createdBy'] = $um->GetUserRole($row->createdBy)->username;
                //$arrData['createdBy'] = $row->createdBy;
                $arrData['updatedOn'] = $row->updatedOn;
                $arrData['note'] = $row->note;
                $arrResponse[] = $arrData;
            }
            if (!empty($arrResponse)) {
                return $arrResponse;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    public function getAllVehicleForCustomer($customerNo) {
        $todaysDate = date('Y-m-d H:i:s');
        $Query = "  SELECT  vehicle.customerno
                            , vehicle.vehicleno
                            , CASE  WHEN devices.lastupdated = '0000-00-00 00:00:00' THEN ''
                                    ELSE devices.lastupdated END AS lastupdated
                            , CASE  WHEN (devices.lastupdated < DATE_SUB('%s', INTERVAL 1 HOUR) OR devices.lastupdated = '0000-00-00 00:00:00' OR devices.lastupdated IS NULL) THEN 'INACTIVE'
                                    ELSE 'ACTIVE' END AS `status`
                    FROM    vehicle
                    INNER JOIN devices ON devices.uid = vehicle.uid
                    INNER JOIN unit ON vehicle.uid = unit.uid
                    INNER JOIN customer ON customer.customerno = vehicle.customerno
                    WHERE   (vehicle.customerno = %d)
                    AND     unit.trans_statusid NOT IN (10,22)
                    AND     vehicle.isdeleted = 0
                    ORDER BY devices.lastupdated DESC";
        $SQL = sprintf($Query, $todaysDate, $customerNo);
        $this->_databaseManager->executeQuery($SQL);
        $vehicles = array();
        while ($row = $this->_databaseManager->get_nextRow()) {
            $vehicle = new stdClass();
            $vehicle->customerno = $row['customerno'];
            $vehicle->lastupdated = $row['lastupdated'];
            $vehicle->vehicleno = $row['vehicleno'];
            $vehicle->status = $row['status'];
            $vehicles[] = $vehicle;
        }
        return $vehicles;
    }

    public function getVehicleCurrentStatus($vehcileId) {
        $futureRoute = array();
        $Query = "select
            devices.devicelat,
            devices.devicelong,
            vehicle.checkpointId,
            vehicle.chkpoint_status,
            vehicle.lastupdated,
            vehicle.routeDirection
            from
            devices
            inner join
            vehicle
            on
            vehicle.uid = devices.uid
            where
            vehicle.vehicleid =" . $vehcileId;
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $obj = new stdClass();
                $obj->devicelat = $row['devicelat'];
                $obj->devicelong = $row['devicelong'];
                $obj->checkpointId = $row['checkpointId'];
                $obj->chkpoint_status = $row['chkpoint_status'];
                $obj->lastupdated = $row['lastupdated'];
                $obj->vehcileId = $vehcileId;
                $futureRoute[] = $obj;
            }
            return $futureRoute;
        }
        return NULL;
    }

    public function get_all_vehicles_of_groups($groupid) {
        $vehicles = Array();
        $Query = '  SELECT  vehicleid
                            ,vehicleno
                            ,groupid
                            ,devicelat
                            ,devicelong
                            ,deviceid
                    FROM    vehicle
                    Inner join devices on devices.uid = vehicle.uid
                    WHERE   vehicle.customerno = %d
                    AND     isdeleted = 0
                    AND     vehicle.groupid = %d';
        $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($groupid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->groupid = $row["groupid"];
                $vehicle->cgeolat = $row["devicelat"];
                $vehicle->cgeolong = $row["devicelong"];
                $vehicle->deviceid = $row["deviceid"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function getVehicleForVehicleDashboard($vehicleId, $state = null, $arrRouteDetails = array()) {
        $vehicles = Array();
        $timeIn60Minutes = time() - 60 * 60;
        $ServerIST_less1 = date('Y-m-d H:i:s', $timeIn60Minutes);
        $ServerIST_less1;
        $today = date("Y-m-d");
        /*
        Changes Made By : Pratik Raut
        Date : 17-09-2019
        Change : Added Parameter in Function
        Reason : requirement to change Delux API changes
         */
        if (!empty($arrRouteDetails)) {
            $_SESSION['customerno'] = $arrRouteDetails['customerno'];
            $_SESSION['userid'] = $arrRouteDetails['userid'];
            $_SESSION['groupid'] = $arrRouteDetails['groupid'];
        }
        /*
        Changes Ends Here
         */
        $groups = $this->getUserGroups($_SESSION['customerno'], $_SESSION['userid']);
        $list = "g.sequence as sequenceno
        , vehicle.ignition_wirecut
        , vehicle.vehicleid
        , vehicle.curspeed
        , vehicle.customerno
        , vehicle.overspeed_limit
        , vehicle.stoppage_transit_time
        , driver.drivername
        , vehicle.temp1_min
        , vehicle.temp1_max
        , vehicle.temp2_min
        , vehicle.temp2_max
        , vehicle.temp3_min
        , vehicle.temp3_max
        , vehicle.temp4_min
        , vehicle.temp4_max
        , devices.devicelat
        , devices.devicelong
        , vehicle.groupid
        , g.groupname
        , vehicle.extbatt
        , devices.ignition
        , devices.status
        , unit.is_buzzer
        , unit.is_freeze
        , vehicle.stoppage_flag
        , devices.directionchange
        , customer.use_geolocation
        , unit.acsensor
        , vehicle.vehicleno
        , unit.unitno
        , devices.tamper
        , devices.powercut
        , devices.inbatt
        , unit.analog1
        , unit.analog2
        , unit.analog3
        , unit.analog4
        , unit.get_conversion
        , unit.digitalioupdated
        , unit.digitalio
        , unit.is_door_opp
        , devices.gsmstrength
        , devices.registeredon
        , driver.driverid
        , driver.driverphone
        , vehicle.kind
        , vehicle.average
        , vehicle.fuelcapacity
        , vehicle.fuel_balance
        , unit.extra_digital
        , customer.use_extradigital
        , unit.extra_digitalioupdated
        , unit.tempsen1
        , unit.tempsen2
        , unit.tempsen3
        , unit.tempsen4
        , unit.humidity
        , unit.is_mobiliser
        , unit.mobiliser_flag
        , unit.command
        , devices.deviceid
        , devices.lastupdated
        , unit.is_ac_opp
        , unit.msgkey
        , ignitionalert.status as igstatus
        , ignitionalert.ignchgtime
        , g1.gensetno as genset1
        , g2.gensetno as genset2
        , t1.transmitterno as transmitter1
        , t2.transmitterno as transmitter2
        , unit.setcom
        , vehicle.temp1_mute
        , vehicle.temp2_mute
        , vehicle.temp3_mute
        , vehicle.temp4_mute
        , unit.extra2_digitalioupdated
        , unit.door_digitalioupdated
        , checkpoint.cname
        , vehicle.chkpoint_status
        , devices.gpsfixed
        , vehicle.checkpoint_timestamp
        , vehicle.routeDirection
        , vehicle.checkpointId
        , unit.door_digitalio
        , unit.isDoorExt
        , first_odometer
        , last_odometer
        , max_odometer
        , vehicle_common_status_master.status
        , vehicle_common_status_master.color_code
        , unit.isGensetExt
        ";
        if (isset($_SESSION['ecodeid'])) {
            $Query = "SELECT $list,v_t.vehicleType
            FROM ecodeman
            INNER JOIN vehicle ON vehicle.vehicleid = ecodeman.vehicleid
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN elixiacode ON elixiacode.id = ecodeman.ecodeid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = ecodeman.customerno AND ecodeman.customerno = $this->_Customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = ecodeman.vehicleid
            LEFT JOIN vehicle_common_status_master ON vehicle_common_status_master.id = vehicle.vehicle_status
            LEFT JOIN `group` g on g.groupid = vehicle.groupid and g.isdeleted = 0 and g.customerno = $this->_Customerno
            LEFT JOIN dailyreport ON dailyreport.vehicleid = vehicle.vehicleid and dailyreport.customerno=$this->_Customerno and daily_date='$today'
            LEFT JOIN checkpoint ON checkpoint.checkpointid = vehicle.checkpointId and checkpoint.customerno = $this->_Customerno
            LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid and g1.customerno = $this->_Customerno
            LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid and g2.customerno = $this->_Customerno
            LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid and t1.customerno = $this->_Customerno
            LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid and t2.customerno = $this->_Customerno
            LEFT JOIN vehicle_type AS v_t ON v_t.vehicleTypeId = vehicle.vehicleType
            WHERE (ecodeman.customerno =%d) AND ecodeman.ecodeid=%d AND unit.trans_statusid NOT IN (10,22) AND ecodeman.isdeleted=0
            ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, ecodeman.customerno, vehicle.sequenceno ASC, devices.lastupdated DESC ";
            $vehiclesQuery = sprintf($Query, Sanitise::Long($_SESSION['customerno']), Sanitise::Long($_SESSION['e_id']));
        } elseif (isset($_SESSION['roleid']) && $_SESSION['roleid'] == '43') {
            $Query = "SELECT $list,v_t.vehicleType
            FROM vehicle
            /*INNER JOIN tripdetails ON tripdetails.vehicleid = vehicle.vehicleid AND tripdetails.is_tripend = 0*/
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno AND vehicle.customerno = $this->_Customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
            LEFT JOIN vehicle_common_status_master ON vehicle_common_status_master.id = vehicle.vehicle_status
            LEFT JOIN `group` g on g.groupid = vehicle.groupid and g.isdeleted = 0 and g.customerno = $this->_Customerno
            LEFT JOIN dailyreport ON dailyreport.vehicleid = vehicle.vehicleid and dailyreport.customerno=$this->_Customerno and daily_date='$today'
            LEFT JOIN checkpoint ON checkpoint.checkpointid = vehicle.checkpointId and checkpoint.customerno = $this->_Customerno
            LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid and g1.customerno = $this->_Customerno
            LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid and g2.customerno = $this->_Customerno
            LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid and t1.customerno = $this->_Customerno
            LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid and t2.customerno = $this->_Customerno
            LEFT JOIN vehicle_type AS v_t ON v_t.vehicleTypeId = vehicle.vehicleType";
            if ($_SESSION['groupid'] == 0) {
                if ($groups[0] != 0) {
                    $groupid_ids = implode(',', $groups);
                    $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid IN (" . $groupid_ids . ") ";
                } else {
                    $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . "";
                }
            } else {
                $Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid = %d";
            }
            if ($_SESSION['customerno'] == 1) {
                $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
            }
            $Query .= " WHERE (vehicle.customerno =%d) AND unit.trans_statusid NOT IN (10,22) ";
            $Query .= " AND vehicle.isdeleted=0 and vehmap.isdeleted = 0 and vehicle.kind <> 'Warehouse' "
                . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.customerno, vehicle.sequenceno ASC, devices.lastupdated DESC ";
            if ($_SESSION['groupid'] == 0) {
                $vehiclesQuery = sprintf($Query, Sanitise::Long($this->_Customerno));
            } else {
                $vehiclesQuery = sprintf($Query, $_SESSION['groupid'], Sanitise::Long($this->_Customerno));
            }
            //echo $vehiclesQuery;
        } elseif (isset($_SESSION['role_modal']) && strtolower($_SESSION['role_modal']) == 'consignee') {
            $Query = "SELECT $list,v_t.vehicleType
            FROM vehicle
            INNER JOIN tripdetails ON tripdetails.vehicleid = vehicle.vehicleid AND tripdetails.is_tripend = 0 AND tripdetails.tripstatusid NOT IN(9,10) AND tripdetails.consigneeid= " . $_SESSION['consignee_id'] . " AND tripdetails.isdeleted=0 AND tripdetails.tripstatusid=4
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno AND vehicle.customerno = $this->_Customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
            LEFT JOIN vehicle_common_status_master ON vehicle_common_status_master.id = vehicle.vehicle_status
            LEFT JOIN `group` g on g.groupid = vehicle.groupid and g.isdeleted = 0 and g.customerno = $this->_Customerno
            LEFT JOIN dailyreport ON dailyreport.vehicleid = vehicle.vehicleid and dailyreport.customerno=$this->_Customerno and daily_date='$today'
            LEFT JOIN checkpoint ON checkpoint.checkpointid = vehicle.checkpointId and checkpoint.customerno = $this->_Customerno
            LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid and g1.customerno = $this->_Customerno
            LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid and g2.customerno = $this->_Customerno
            LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid and t1.customerno = $this->_Customerno
            LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid and t2.customerno = $this->_Customerno
            LEFT JOIN vehicle_type AS v_t ON v_t.vehicleTypeId = vehicle.vehicleType";
            /*  if ($_SESSION['groupid'] == 0) {
            if ($groups[0] != 0) {
            $groupid_ids = implode(',', $groups);
            //$Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid IN (" . $groupid_ids . ") ";
            } else {
            //$Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . "";
            }
            } else {
            //$Query .= " INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = " . $_SESSION['userid'] . " and vehmap.groupid = %d";
             */
            if ($_SESSION['customerno'] == 1) {
                $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
            }
            $Query .= " WHERE (vehicle.customerno =%d) AND unit.trans_statusid NOT IN (10,22) ";
            $Query .= " AND vehicle.isdeleted=0 /*and vehmap.isdeleted = 0 */ and vehicle.kind <> 'Warehouse'  GROUP BY vehicle.vehicleid"
                . " ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.customerno, vehicle.sequenceno ASC, devices.lastupdated DESC ";
            if ($_SESSION['groupid'] == 0) {
                $vehiclesQuery = sprintf($Query, Sanitise::Long($this->_Customerno));
            } else {
                $vehiclesQuery = sprintf($Query, $_SESSION['groupid'], Sanitise::Long($this->_Customerno));
            }
        } else {
            $Query = "SELECT $list,v_t.vehicleType
            FROM vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON vehicle.uid = unit.uid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno AND vehicle.customerno = $this->_Customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
            LEFT JOIN vehicle_common_status_master ON vehicle_common_status_master.id = vehicle.vehicle_status
            LEFT JOIN `group` g on g.groupid = vehicle.groupid and g.isdeleted = 0 and g.customerno = $this->_Customerno
            LEFT JOIN dailyreport ON dailyreport.vehicleid = vehicle.vehicleid and dailyreport.customerno=$this->_Customerno and daily_date='$today'
            LEFT JOIN checkpoint ON checkpoint.checkpointid = vehicle.checkpointId and checkpoint.customerno = $this->_Customerno
            LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid and g1.customerno = $this->_Customerno
            LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid and g2.customerno = $this->_Customerno
            LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid and t1.customerno = $this->_Customerno
            LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid and t2.customerno = $this->_Customerno
            LEFT JOIN vehicle_type AS v_t ON v_t.vehicleTypeId = vehicle.vehicleType
            WHERE (vehicle.customerno =%d) AND unit.trans_statusid NOT IN (10,22)";
            if ($_SESSION['groupid'] == 0) {
                if ($groups[0] != 0) {
                    $groupid_ids = implode(',', $groups);
                    $Query .= " AND vehicle.groupid  IN (" . $groupid_ids . ") ";
                }
            } else {
                $Query .= " AND vehicle.groupid =%d";
            }
            if ($vehicleId) {
                $Query .= " AND vehicle.vehicleid = '$vehicleId' ";
            }
            if ($_SESSION['customerno'] == 1) {
                $Query .= " AND devices.lastupdated > '$ServerIST_less1' ";
            }
            /*
             * Changes Made By : Pratik Raut
             * Date : 27-09-2019
             * change : Added sequence of group in query earlier it was vehicle sequence
             */
            //$Query .= " AND vehicle.isdeleted=0 and vehicle.kind <> 'Warehouse'
            //          ORDER BY CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.customerno, vehicle.sequenceno ASC, devices.lastupdated DESC";
            $Query .= " AND vehicle.isdeleted=0 and vehicle.kind <> 'Warehouse'
                      ORDER BY CASE WHEN (g.sequence is null) THEN 0 ELSE g.sequence END DESC,vehicle.sequenceno,vehicle.customerno,devices.lastupdated DESC";
            /*
            changes ends here
             */
            if ($_SESSION['groupid'] == 0) {
                $vehiclesQuery = sprintf($Query, $this->_Customerno);
            } else {
                $vehiclesQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
            }
        }
        //echo $vehiclesQuery;die;
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->sequenceno = $row['sequenceno'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->customerno = $row['customerno'];
                $vehicle->tamper = $row['tamper'];
                $vehicle->powercut = $row['powercut'];
                $vehicle->inbatt = $row['inbatt'];
                $vehicle->analog1 = $row['analog1'];
                $vehicle->analog2 = $row['analog2'];
                $vehicle->analog3 = $row['analog3'];
                $vehicle->analog4 = $row['analog4'];
                $vehicle->get_conversion = $row['get_conversion'];
                $vehicle->digitalio = $row['digitalio'];
                $vehicle->digitalioupdated = $row['digitalioupdated'];
                $vehicle->gsmstrength = $row['gsmstrength'];
                $vehicle->acsensor = $row['acsensor'];
                $vehicle->tempsen1 = $row['tempsen1'];
                $vehicle->tempsen2 = $row['tempsen2'];
                $vehicle->tempsen3 = $row['tempsen3'];
                $vehicle->tempsen4 = $row['tempsen4'];
                $vehicle->humidity = $row['humidity'];
                $vehicle->vehicleid = $row['vehicleid'];
                $vehicle->type = $row['kind'];
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->overspeed_limit = $row['overspeed_limit'];
                $vehicle->stoppage_transit_time = $row['stoppage_transit_time'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->devicelat = $row['devicelat'];
                $vehicle->devicelong = $row['devicelong'];
                $vehicle->temp1_mute = $row['temp1_mute'];
                $vehicle->temp2_mute = $row['temp2_mute'];
                $vehicle->temp3_mute = $row['temp3_mute'];
                $vehicle->temp4_mute = $row['temp4_mute'];
                $vehicle->temp1_min = $row['temp1_min'];
                $vehicle->temp1_max = $row['temp1_max'];
                $vehicle->temp2_min = $row['temp2_min'];
                $vehicle->temp2_max = $row['temp2_max'];
                $vehicle->temp3_min = $row['temp3_min'];
                $vehicle->temp3_max = $row['temp3_max'];
                $vehicle->temp4_min = $row['temp4_min'];
                $vehicle->temp4_max = $row['temp4_max'];
                $vehicle->msgkey = $row['msgkey'];
                $vehicle->groupid = $row['groupid'];
                if ($row['groupid'] != 0) {
                    $vehicle->groupname = $row['groupname'];
                } else {
                    $vehicle->groupname = 'Ungrouped';
                }
                $vehicle->average = $row['average'];
                $vehicle->is_mobiliser = $row['is_mobiliser'];
                $vehicle->is_freeze = $row['is_freeze'];
                $vehicle->fuelcapacity = $row['fuelcapacity'];
                $vehicle->fuel_balance = $row['fuel_balance'];
                $vehicle->paginate = $vehiclesQuery;
                if ($row['lastupdated'] != '0000-00-00 00:00:00') {
                    $vehicle->lastupdated = $row['lastupdated'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                } else {
                    $vehicle->lastupdated = $row['registeredon'];
                    $vehicle->lastupdated_store = $row['lastupdated'];
                }
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->ignition = $row['ignition'];
                $vehicle->status = $row['status'];
                $vehicle->isacopp = $row["is_ac_opp"];
                $vehicle->is_door_opp = $row["is_door_opp"];
                $vehicle->command = $row["command"];
                $vehicle->mobiliser_flag = $row["mobiliser_flag"];
                $vehicle->stoppage_flag = $row["stoppage_flag"];
                $vehicle->directionchange = $row["directionchange"];
                $vehicle->igstatus = $row["igstatus"];
                $vehicle->ignchgtime = $row["ignchgtime"];
                $vehicle->use_geolocation = $row["use_geolocation"];
                $vehicle->is_buzzer = $row["is_buzzer"];
                $vehicle->extra_digitalioupdated = $row["extra_digitalioupdated"];
                $vehicle->extra2_digitalioupdated = $row["extra2_digitalioupdated"];
                $vehicle->door_digitalioupdated = $row["door_digitalioupdated"];
                $vehicle->genset1 = $row['genset1'];
                $vehicle->genset2 = $row['genset2'];
                $vehicle->transmitter1 = $row['transmitter1'];
                $vehicle->transmitter2 = $row['transmitter2'];
                $vehicle->setcom = $row['setcom'];
                $vehicle->cname = $row['cname'];
                $vehicle->chkpoint_status = $row['chkpoint_status'];
                $vehicle->checkpoint_timestamp = $row['checkpoint_timestamp'];
                $vehicle->ignition_wirecut = $row['ignition_wirecut'];
                $vehicle->gpsfixed = $row['gpsfixed'];
                $vehicle->routeDirection = $row['routeDirection'];
                $vehicle->checkpointId = $row['checkpointId'];
                $vehicle->door_digitalio = $row['door_digitalio'];
                $vehicle->isDoorExt = $row['isDoorExt'];
                $vehicle->vehicleType = $row['vehicleType'];
                $vehicle->first_odometer = $row['first_odometer'];
                $vehicle->last_odometer = $row['last_odometer'];
                $vehicle->max_odometer = $row['max_odometer'];
                $vehicle->avg_temp_sense1 = 0;
                $vehicle->avg_temp_sense2 = 0;
                $vehicle->avg_temp_sense3 = 0;
                $vehicle->avg_temp_sense4 = 0;
                $vehicle->vehicle_status = $row['status'];
                $vehicle->vehicle_status_color_code = $row['color_code'];
                $vehicle->extra_digital = $row["extra_digital"];
                if (isset($row['isGensetExt']) && $row['isGensetExt'] == 1) {
                    if (isset($row['analog1']) && $row['analog1'] > 0) {
                        $vehicle->extra_digital = 1;
                    } else {
                        $vehicle->extra_digital = 0;
                    }
                }
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }
}
?>