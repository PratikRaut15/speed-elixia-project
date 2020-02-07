<?php

include_once '../../lib/system/Validator.php';
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/system/Date.php';

class MakeManager extends VersionedManager {

   function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
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

    public function add_make($make_name, $userid) {
        $today = date("Y-m-d H:i:s");
        $Query = "INSERT INTO `make` (name,customerno,userid,timestamp) VALUES ('%s','%d','%d','%s')";
        $SQL = sprintf($Query, trim(Sanitise::String($make_name)), $this->_Customerno, Sanitise::Long($userid), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($SQL);
        return $this->_databaseManager->get_insertedId();
    }

    public function del_make($makeid, $userid) {
        $today = date("Y-m-d H:i:s");
        $Query = "UPDATE `make` SET `isdeleted` = 1, userid=%d, timestamp='%s' WHERE `id` = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::DateTime($today), Sanitise::Long($makeid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function get_makebyid($makeid) {
        $makename = '';
        $Query = "SELECT * FROM make WHERE id=%d AND customerno=%d ";
        $SQL = sprintf($Query, Sanitise::Long($makeid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $makename = $row['name'];
            return $makename;
        } else {
            return $makename;
        }
    }

    public function edit_make($makeid, $makename, $userid) {

        $today = date("Y-m-d H:i:s");
        $Query = "UPDATE `make` SET `name` = '%s', userid=%d, timestamp='%s' WHERE `id` = %d AND customerno = %d";
        $SQL = sprintf($Query, trim(Sanitise::String($makename)), Sanitise::Long($userid), Sanitise::DateTime($today), Sanitise::Long($makeid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

}
