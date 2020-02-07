<?php

include_once '../../lib/system/Validator.php';
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/system/Date.php';
include_once '../../lib/bo/DeviceManager.php';

class Datacap {
    
}

class BatteryManager extends VersionedManager {

    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function getBatteryData($vehid = null) {
        $data = Array();

        $groupid = isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0 ? $_SESSION['groupid'] : NULL;

        $Query = "SELECT mb.batt_mapid,mb.vehicleid,mb.customerno,mb.batt_serialno,mb.installedon,mb.createdby,mb.createdon,
                  mb.updatedby,mb.updatedon,v.vehicleno,v.vehicleid as vehid FROM maintenance_mapbattery mb
                  INNER JOIN vehicle as v ON mb.vehicleid = v.vehicleid
                  WHERE mb.customerno = %d AND v.isdeleted = 0 ";

        if ($vehid != null) {
            $Query.= "AND mb.vehicleid = %d ";
            $finalQuery = sprintf($Query, $this->_Customerno, $vehid);
        } else if ($groupid != NULL) {
            $Query.= "AND v.groupid = %d ";
            $finalQuery = sprintf($Query, $this->_Customerno, $groupid);
        } else {
            $Query.= " ORDER BY mb.updatedon DESC";
            $finalQuery = sprintf($Query, $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($finalQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $x = 1;
            while ($row = $this->_databaseManager->get_nextRow()) {
                $srno = new Datacap();
                $srno->x = $x++;
                $srno->batt_mapid = $row['batt_mapid'];
                $srno->batt_serialno = $row['batt_serialno'];
                if ($row['installedon'] == '0000-00-00' || $row['installedon'] == '1970-01-01') {
                    $srno->installedon = '';
                } else {
                    $srno->installedon = date('d-m-Y', strtotime($row['installedon']));
                }
                $srno->vehicleno = $row['vehicleno'];
                $srno->vehid = $row['vehicleid'];
                if ($row['updatedon'] == '0000-00-00' || $row['updatedon'] == '1970-01-01') {
                    $srno->updatedon = '';
                } else {
                    $srno->updatedon = date('d-m-Y', strtotime($row['updatedon']));
                }
                $data[] = $srno;
            }
        }
        return $data;
    }

    public function getNoBatteryVehicles($srhstring) {
        $devices = Array();
        $battveh = $this->getBatteryData();
        if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
            $Query = "SELECT vehicleid, vehicleno from vehicle where customerno=%d AND vehicleno LIKE '%s'";
            if ($_SESSION['groupid'] != 0)
                $Query.=" AND vehicle.groupid =%d";

            $Query.=" ORDER BY vehicle.vehicleno";

            if ($_SESSION['groupid'] != 0)
                $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring), $_SESSION['groupid']);
            else
                $devicesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srhstring));
        }

        $this->_databaseManager->executeQuery($devicesQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $found = 0;
                $device = new Datacap();
                if ($row['vehicleid'] > 0) {
                    if (!empty($battveh)) {
                        foreach ($battveh as $battvehs) {
                            if ($battvehs->vehid == $row['vehicleid']) {
                                $found = 1;
                            }
                        }
                    }
                    if ($found == 1) {
                        continue;
                    }
                    $device->vehicleno = $row['vehicleno'];
                    $device->vehicleid = $row['vehicleid'];

                    $devices[] = $device;
                }
            }
            return $devices;
        }
    }

    public function getSrnobyid($batt_mapid) {
        $data = Array();
        $Query = "SELECT mb.batt_mapid,mb.vehicleid,mb.customerno,mb.batt_serialno,mb.installedon,mb.createdby,mb.createdon,
                  mb.updatedby,mb.updatedon,v.vehicleno,v.vehicleid as vehid FROM maintenance_mapbattery mb
                  INNER JOIN vehicle as v ON mb.vehicleid = v.vehicleid
                  WHERE mb.customerno = %d AND mb.batt_mapid = %d";
        $finalQuery = sprintf($Query, $this->_Customerno, $batt_mapid);
        $this->_databaseManager->executeQuery($finalQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $srno = new Datacap();
                $srno->batt_mapid = $row['batt_mapid'];
                $srno->batt_serialno = $row['batt_serialno'];
                if ($row['installedon'] == '0000-00-00' || $row['installedon'] == '1970-01-01') {
                    $srno->installedon = '';
                } else {
                    $srno->installedon = date('d-m-Y', strtotime($row['installedon']));
                }
                $srno->vehicleno = $row['vehicleno'];
                $srno->vehid = $row['vehid'];
                $data = $srno;
            }
        }
        return $data;
    }

    public function Editbatt_srno($form) {
        $today = date("Y-m-d H:i:s");
        $vehid = GetSafeValueString($form['vehid'], "long");
        $batt_mapid = GetSafeValueString($form['batt_mapid'], "long");
        if ($form['ins_date'] != '') {
            $insdate = date("Y-m-d", strtotime(GetSafeValueString($form['ins_date'], "string")));
        } else {
            $insdate = '';
        }
        $Query = "UPDATE maintenance_mapbattery SET batt_serialno = '%s',installedon = '%s',updatedby = %d,updatedon = '%s'
                  WHERE customerno = %d AND batt_mapid = %d";
        $finalQuery = sprintf($Query, Sanitise::String($form['batt_srno']), $insdate, Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today), $this->_Customerno, $batt_mapid);
        $this->_databaseManager->executeQuery($finalQuery);
    }

    public function Addbatt_srno($form) {
        $today = date("Y-m-d H:i:s");
        $vehid = GetSafeValueString($form['vehid'], "long");
        $batt_srno = GetSafeValueString($form['batt_srno'], "string");
        $insdate = '';
        if ($form['ins_date'] != '' || $form['ins_date'] != '00-00-0000') {
            $insdate = date("Y-m-d", strtotime(GetSafeValueString($form['ins_date'], "string")));
        }
        $Query = "INSERT INTO maintenance_mapbattery (vehicleid,customerno,batt_serialno,installedon,createdby,createdon,updatedby,updatedon)
                    VALUES(%d,%d,'%s','%s',%d,'%s',%d,'%s')";
        $finalQuery = sprintf($Query, $vehid, $this->_Customerno, $batt_srno, $insdate, Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today), Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($finalQuery);
    }

    public function SaveSerialno($all_form) {
        $added = 0;
        $skipped = 0;
        foreach ($all_form as $form) {
            if ($form['vehicleno'] != '') {

                $vehicleno = GetSafeValueString(trim($form['vehicleno']), "string");
                $vid = $this->getVehicleid($vehicleno);
                if (!is_null($vid)) {
                    $batt = new Datacap();
                    $batt->vehid = GetSafeValueString($vid, "long");
                    $batt->srno = GetSafeValueString(trim($form['serialno']), "string");
                    $batt->insdate = GetSafeValueString(trim($form['installedon']), "string");
                    if ($this->CheckVehInBattery($vid)) {
                        $update = $this->UpdateSrno($batt);
                        $update != 0 ? $added++ : $skipped++;
                    } else {
                        $insert = $this->InsertSrno($batt);
                        $insert != 0 ? $added++ : $skipped++;
                    }
                } else {
                    $skipped++;
                }
            } else {
                $skipped++;
            }
        }
        $result = array(
            'added' => $added,
            'skipped' => $skipped
        );
        return $result;
    }

    public function getVehicleid($vehicleno) {
        $vehid = null;
        $vehicleno = strtoupper($vehicleno);
        $Query = "SELECT * FROM vehicle WHERE vehicleno = '%s' AND customerno = %d AND isdeleted = 0";
        $finalQuery = sprintf($Query, $vehicleno, $this->_Customerno);
        $this->_databaseManager->executeQuery($finalQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $vehid = $row['vehicleid'];
        }
        return $vehid;
    }

    public function CheckVehInBattery($vid) {
        $found = FALSE;
        $Query = "SELECT * FROM maintenance_mapbattery WHERE vehicleid = %d AND customerno = %d AND isdeleted = 0";
        $finalQuery = sprintf($Query, $vid, $this->_Customerno);
        $this->_databaseManager->executeQuery($finalQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $found = TRUE;
        }
        return $found;
    }

    public function InsertSrno($data) {
        $today = date("Y-m-d H:i:s");
        $insdate = '';
        if ($data->insdate != '' || $data->insdate != '00-00-0000') {
            $insdate = date("Y-m-d", strtotime(GetSafeValueString($data->insdate, "string")));
        }
        $Query = "INSERT INTO maintenance_mapbattery (vehicleid,customerno,batt_serialno,installedon,createdby,createdon,updatedby,updatedon)
                    VALUES(%d,%d,'%s','%s',%d,'%s',%d,'%s')";
        $finalQuery = sprintf($Query, $data->vehid, $this->_Customerno, $data->srno, $insdate, Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today), Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($finalQuery);
        $affected = $this->_databaseManager->get_affectedRows();
        return $affected;
    }

    public function UpdateSrno($data) {
        $today = date("Y-m-d H:i:s");
        if ($data->insdate != '' || $data->insdate != '00-00-0000') {
            $insdate = date("Y-m-d", strtotime(GetSafeValueString($data->insdate, "string")));
        } else {
            $insdate = '';
        }
        $Query = "UPDATE maintenance_mapbattery SET batt_serialno = '%s',installedon = '%s',updatedby = %d,updatedon = '%s'
                  WHERE customerno = %d AND vehicleid = %d AND isdeleted = 0";
        $finalQuery = sprintf($Query, $data->srno, $insdate, Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today), $this->_Customerno, $data->vehid);
        $this->_databaseManager->executeQuery($finalQuery);
        $affected = $this->_databaseManager->get_affectedRows();
        return $affected;
    }

}
