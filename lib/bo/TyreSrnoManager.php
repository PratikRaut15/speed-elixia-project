<?php

include_once '../../lib/system/Validator.php';
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/system/Date.php';
include_once '../../lib/bo/DeviceManager.php';
include_once '../../lib/bo/VehicleManager.php';

class Datacap {
    
}

class TyreManager extends VersionedManager {

    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function getAllMaintenanceVehicles() {
        $vm = new VehicleManager($_SESSION['customerno']);
        $veh = $vm->GetAllVehicles();

        return $veh;
    }

    public function getTyreDetails() {
        $result = Array();
        $data = $this->getAllMaintenanceVehicles();
        foreach ($data as $datas) {
            $tyre = Array();
            $Query = "SELECT mt.tyremapid,mt.vehicleid,mt.customerno,ma.tyreid,mt.serialno,mt.installedon,mt.updatedon,ma.type,v.vehicleno 
                      FROM maintenance_tyretype ma 
                      LEFT OUTER JOIN maintenance_maptyre mt ON ma.tyreid = mt.tyreid AND mt.vehicleid = %d AND mt.customerno = %d AND mt.isdeleted = 0
                      LEFT OUTER JOIN vehicle as v ON mt.vehicleid = v.vehicleid 
                      ORDER BY ma.tyreid ASC";
            $finalQuery = sprintf($Query, $datas->vehicleid, $this->_Customerno);
            $this->_databaseManager->executeQuery($finalQuery);
            $vehicleid = 0;
            $vehicleno = '';
            $isVehMapped = array();
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()) {
                    $detail = new Datacap();
                    $detail->tyreid = $row['tyreid'];
                    $detail->type = $row['type'];
                    $detail->serialno = $row['serialno'];
                    if ($row['installedon'] == '0000-00-00' || $row['installedon'] == '1970-01-01' || $row['installedon'] == '') {
                        $detail->insdate = '';
                    } else {
                        $detail->insdate = date("d-m-Y", strtotime($row['installedon']));
                    }
                    if ($row['updatedon'] == '0000-00-00 00:00:00' || $row['updatedon'] == '1970-01-01 00:00:00' || $row['updatedon'] == '') {
                        $detail->updatedon = '';
                    } else {
                        $detail->updatedon = date("d-m-Y", strtotime($row['updatedon']));
                    }
                    if (isset($row['vehicleid']) && $row['vehicleid'] > 0) {
                        $isVehMapped[] = 1;
                        $vehicleid = $row['vehicleid'];
                        $vehicleno = $row['vehicleno'];
                    } else {
                        $isVehMapped[] = 0;
                    }
                    $tyre[] = $detail;
                }
            }
            if (isset($vehicleid) && in_array(1, $isVehMapped)) {
                $objResult = new stdClass();
                $objResult->vehicleid = $vehicleid;
                $objResult->vehicleno = $vehicleno;
                $objResult->mappedtyres = $tyre;
                $result[] = $objResult;
            }
        }
        return $result;
    }

    public function getTyreDetailsById($vid) {
        $result = Array();
        $tyre = Array();
        $Query = "SELECT mt.tyremapid,mt.vehicleid,mt.customerno,ma.tyreid,mt.serialno,mt.installedon,mt.updatedon,ma.type,v.vehicleno 
                      FROM maintenance_tyretype ma 
                      LEFT OUTER JOIN maintenance_maptyre mt ON ma.tyreid = mt.tyreid AND mt.vehicleid = %d AND mt.customerno = %d AND mt.isdeleted = 0
                      LEFT OUTER JOIN vehicle as v ON mt.vehicleid = v.vehicleid 
                      ORDER BY ma.tyreid ASC";
        $finalQuery = sprintf($Query, $vid, $this->_Customerno);
        $this->_databaseManager->executeQuery($finalQuery);
        $vehicleid = 0;
        $vehicleno = '';
        $isVehMapped = array();
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $detail = new Datacap();
                $detail->tyreid = $row['tyreid'];
                $detail->type = $row['type'];
                $detail->serialno = $row['serialno'];
                if ($row['installedon'] == '0000-00-00' || $row['installedon'] == '1970-01-01' || $row['installedon'] == '') {
                    $detail->insdate = '';
                } else {
                    $detail->insdate = date("d-m-Y", strtotime($row['installedon']));
                }
                if ($row['updatedon'] == '0000-00-00 00:00:00' || $row['updatedon'] == '1970-01-01 00:00:00' || $row['updatedon'] == '') {
                    $detail->updatedon = '';
                } else {
                    $detail->updatedon = date("d-m-Y", strtotime($row['updatedon']));
                }
                if (isset($row['vehicleid']) && $row['vehicleid'] > 0) {
                    $isVehMapped[] = 1;
                    $vehicleid = $row['vehicleid'];
                    $vehicleno = $row['vehicleno'];
                } else {
                    $isVehMapped[] = 0;
                }
                $tyre[] = $detail;
            }
        }
        if (isset($vehicleid) && in_array(1, $isVehMapped)) {
            $objResult = new stdClass();
            $objResult->vehicleid = $vehicleid;
            $objResult->vehicleno = $vehicleno;
            $objResult->mappedtyres = $tyre;
            $result[] = $objResult;
        }
        return $result;
    }

    public function EditTyreSrnoDetails($form) {
        $vehid = GetSafeValueString($form['veh'], "string");
        if (isset($form['rf'])) {
            $type = 1;
            $srno = GetSafeValueString(trim($form['rf_srno']), "string");
            if ($form['rf_insdate'] == '00-00-0000' || $form['rf_insdate'] == '' || $form['rf_insdate'] == '01-01-1970') {
                $insdate = '';
            } else {
                $insdate = date("Y-m-d", strtotime($form['rf_insdate']));
            }
            if ($this->CheckTyreTypeExists($type, $vehid)) {
                $this->UpdateTyreSrnoDetails($type, $vehid, $srno, $insdate);
            } else {
                $this->InsertTyreSrnoDetails($type, $vehid, $srno, $insdate);
            }
        }
        if (isset($form['lf'])) {
            $type = 2;
            $srno = GetSafeValueString(trim($form['lf_srno']), "string");
            if ($form['lf_insdate'] == '00-00-0000' || $form['lf_insdate'] == '' || $form['lf_insdate'] == '01-01-1970') {
                $insdate = '';
            } else {
                $insdate = date("Y-m-d", strtotime($form['lf_insdate']));
            }
            if ($this->CheckTyreTypeExists($type, $vehid)) {
                $this->UpdateTyreSrnoDetails($type, $vehid, $srno, $insdate);
            } else {
                $this->InsertTyreSrnoDetails($type, $vehid, $srno, $insdate);
            }
        }
        if (isset($form['rb_out'])) {
            $type = 3;
            $srno = GetSafeValueString(trim($form['rb_out_srno']), "string");
            if ($form['rb_out_insdate'] == '00-00-0000' || $form['rb_out_insdate'] == '' || $form['rb_out_insdate'] == '01-01-1970') {
                $insdate = '';
            } else {
                $insdate = date("Y-m-d", strtotime($form['rb_out_insdate']));
            }
            if ($this->CheckTyreTypeExists($type, $vehid)) {
                $this->UpdateTyreSrnoDetails($type, $vehid, $srno, $insdate);
            } else {
                $this->InsertTyreSrnoDetails($type, $vehid, $srno, $insdate);
            }
        }
        if (isset($form['lb_out'])) {
            $type = 4;
            $srno = GetSafeValueString(trim($form['lb_out_srno']), "string");
            if ($form['lb_out_insdate'] == '00-00-0000' || $form['lb_out_insdate'] == '' || $form['lb_out_insdate'] == '01-01-1970') {
                $insdate = '';
            } else {
                $insdate = date("Y-m-d", strtotime($form['lb_out_insdate']));
            }
            if ($this->CheckTyreTypeExists($type, $vehid)) {
                $this->UpdateTyreSrnoDetails($type, $vehid, $srno, $insdate);
            } else {
                $this->InsertTyreSrnoDetails($type, $vehid, $srno, $insdate);
            }
        }
        if (isset($form['st'])) {
            $type = 5;
            $srno = GetSafeValueString(trim($form['st_srno']), "string");
            if ($form['st_insdate'] == '00-00-0000' || $form['st_insdate'] == '' || $form['st_insdate'] == '01-01-1970') {
                $insdate = '';
            } else {
                $insdate = date("Y-m-d", strtotime($form['st_insdate']));
            }
            if ($this->CheckTyreTypeExists($type, $vehid)) {
                $this->UpdateTyreSrnoDetails($type, $vehid, $srno, $insdate);
            } else {
                $this->InsertTyreSrnoDetails($type, $vehid, $srno, $insdate);
            }
        }
        if (isset($form['rb_in'])) {
            $type = 6;
            $srno = GetSafeValueString(trim($form['rb_in_srno']), "string");
            if ($form['rb_in_insdate'] == '00-00-0000' || $form['rb_in_insdate'] == '' || $form['rb_in_insdate'] == '01-01-1970') {
                $insdate = '';
            } else {
                $insdate = date("Y-m-d", strtotime($form['rb_in_insdate']));
            }
            if ($this->CheckTyreTypeExists($type, $vehid)) {
                $this->UpdateTyreSrnoDetails($type, $vehid, $srno, $insdate);
            } else {
                $this->InsertTyreSrnoDetails($type, $vehid, $srno, $insdate);
            }
        }
        if (isset($form['lb_in'])) {
            $type = 7;
            $srno = GetSafeValueString(trim($form['lb_in_srno']), "string");
            if ($form['lb_in_insdate'] == '00-00-0000' || $form['lb_in_insdate'] == '' || $form['lb_in_insdate'] == '01-01-1970') {
                $insdate = '';
            } else {
                $insdate = date("Y-m-d", strtotime($form['lb_in_insdate']));
            }
            if ($this->CheckTyreTypeExists($type, $vehid)) {
                $this->UpdateTyreSrnoDetails($type, $vehid, $srno, $insdate);
            } else {
                $this->InsertTyreSrnoDetails($type, $vehid, $srno, $insdate);
            }
        }
    }

    public function CheckTyreTypeExists($type, $veh) {
        $isExists = FALSE;
        $Query = "SELECT * FROM maintenance_maptyre WHERE vehicleid = %d AND customerno = %d AND tyreid = %d AND isdeleted = 0";
        $finalQuery = sprintf($Query, $veh, $this->_Customerno, $type);
        $this->_databaseManager->executeQuery($finalQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $isExists = TRUE;
        }
        return $isExists;
    }

    public function InsertTyreSrnoDetails($type, $veh, $srno, $insdate) {
        $today = date("Y-m-d H:i:s");
        $Query = "INSERT INTO maintenance_maptyre(vehicleid,customerno,tyreid,serialno,installedon,createdby,createdon,updatedby,updatedon)
                  VALUES(%d,%d,%d,'%s','%s',%d,'%s',%d,'%s')";
        $finalQuery = sprintf($Query, $veh, $this->_Customerno, $type, $srno, $insdate, Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today), Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($finalQuery);
        $affected = $this->_databaseManager->get_affectedRows();
        return $affected;
    }

    public function UpdateTyreSrnoDetails($type, $veh, $srno, $insdate) {
        $today = date("Y-m-d H:i:s");
        if (!empty($srno)){
            $Query = "UPDATE maintenance_maptyre SET serialno = '%s', installedon = '%s' , updatedby = %d , updatedon = '%s'  WHERE vehicleid = %d AND customerno = %d AND tyreid = %d";
            $finalQuery = sprintf($Query, $srno, $insdate, Sanitise::Long($_SESSION['userid']), Sanitise::DateTime($today), $veh, $this->_Customerno, $type);
            $this->_databaseManager->executeQuery($finalQuery);
            $affected = $this->_databaseManager->get_affectedRows();
            return $affected;
        }
    }

    public function SaveSerialno($all_form) {

        $added = 0;
        $skipped = 0;
        foreach ($all_form as $form) {
            if ($form['vehicleno'] != '' && $form['type'] != '' && $form['typeid'] != '' && $form['typeid'] != '#N/A') {

                $vehicleno = GetSafeValueString(trim($form['vehicleno']), "string");
                $vid = $this->getVehicleid($vehicleno);
                if (!is_null($vid)) {
                    $tyre = new Datacap();
                    $tyre->vehid = GetSafeValueString($vid, "string");
                    $tyre->type = GetSafeValueString(trim($form['type']), "string");
                    $tyre->typeid = GetSafeValueString(trim($form['typeid']), "long");
                    $tyre->srno = GetSafeValueString(trim($form['serialno']), "string");

                    if ($form['installedon'] == '' || $form['installedon'] == '00-00-0000') {
                        $tyre->insdate = '';
                    } else {
                        $tyre->insdate = date("Y-m-d", strtotime(GetSafeValueString(trim($form['installedon']), "string")));
                    }

                    if ($this->CheckTyreTypeExists($tyre->typeid, $tyre->vehid)) {
                        $update = $this->UpdateTyreSrnoDetails($tyre->typeid, $tyre->vehid, $tyre->srno, $tyre->insdate);
                        $update != 0 ? $added++ : $skipped++;
                    } else {
                        $insert = $this->InsertTyreSrnoDetails($tyre->typeid, $tyre->vehid, $tyre->srno, $tyre->insdate);
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

}
