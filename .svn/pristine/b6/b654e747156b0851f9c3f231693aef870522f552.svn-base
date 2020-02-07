<?php

include_once '../../lib/system/Validator.php';
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/system/Date.php';

class Datacap {
    
}

class ModelManager extends VersionedManager {

    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function get_make() {
        $vehicles = Array();
        $Query = 'SELECT * FROM make WHERE customerno IN(0,%d) AND isdeleted=0';
        $vehiclesQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new Datacap();
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

    public function get_modelfrommake($makeid) {
        $models = Array();
        $x = 0;
        $Query = "SELECT * FROM model WHERE make_id=%d AND customerno IN(0,%d) AND isdeleted=0";
        $modelQuery = sprintf($Query, $makeid, $this->_Customerno);
        $this->_databaseManager->executeQuery($modelQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $x++;
                $model = new Datacap();
                $model->x = $x;
                $model->model_id = $row['model_id'];
                //$vehicle->devicekey = $row['devicekey'];
                $model->name = $row['name'];
                $model->customerno = $row['customerno'];
                $models[] = $model;
            }
        }
        return $models;
    }

    public function addmodel($modelname, $make_id, $userid) {
        $today = date("Y-m-d H:i:s");
        $Query = "INSERT INTO model (name,make_id,customerno,userid,timestamp) VALUES('%s',%d,%d,%d,'%s')";
        $modelQuery = sprintf($Query, trim($modelname), $make_id, $this->_Customerno, $userid, $today);
        $this->_databaseManager->executeQuery($modelQuery);
    }

    public function delmodel($modelid, $userid) {
        $today = date("Y-m-d H:i:s");
        $Query = "UPDATE model SET isdeleted=1,userid='$userid',timestamp='$today' WHERE model_id=%d AND customerno=%d";
        $modelQuery = sprintf($Query, $modelid, $this->_Customerno);
        $this->_databaseManager->executeQuery($modelQuery);
    }

    public function get_model_name($modelid) {

        $Query = "SELECT * FROM model WHERE model_id=%d AND customerno=%d AND isdeleted=0";
        $modelQuery = sprintf($Query, $modelid, $this->_Customerno);
        $this->_databaseManager->executeQuery($modelQuery);

        $row = $this->_databaseManager->get_nextRow();
        $model = new Datacap();
        $model->model_id = $row['model_id'];
        $model->name = $row['name'];
        $model->customerno = $row['customerno'];

        return $model;
    }

    public function editmodel($modelname, $modelid, $userid) {
        $today = date("Y-m-d H:i:s");
        $Query = "UPDATE model SET name='%s',userid=%d,timestamp='%s' WHERE customerno=%d AND model_id=%d";
        $modelQuery = sprintf($Query, trim($modelname), $userid, $today, $this->_Customerno, $modelid);
        $this->_databaseManager->executeQuery($modelQuery);
    }

}
