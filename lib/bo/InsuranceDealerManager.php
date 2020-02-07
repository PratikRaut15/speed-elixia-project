<?php

include_once '../../lib/system/Validator.php';
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/system/Date.php';

class InsuranceDealerManager extends VersionedManager {

    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function getAllinsDealers() {
        $dealers = Array();
        $Query = "SELECT ins_dealerid
                ,ins_dealername
                ,customerno
                ,createdby
                ,createdon
                ,updatedby
                ,updatedon
                FROM insurance_dealer
                WHERE customerno = %d
                AND isdeleted = 0
                ";
        $SQL = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $insdealer = new stdClass();
                $insdealer->ins_dealerid = $row['ins_dealerid'];
                $insdealer->ins_dealername = $row['ins_dealername'];
                $insdealer->customerno = $row['customerno'];
                $dealers[] = $insdealer;
            }
        }
        return $dealers;
    }

    public function addInsdealer($insdealername) {
        $today = date('Y-m-d H:i:s');
        $Query = "INSERT INTO insurance_dealer(ins_dealername,customerno,createdby,createdon,updatedby,updatedon) VALUES ('%s',%d,%d,'%s',%d,'%s')";
        $SQL = sprintf($Query, Sanitise::String($insdealername), $this->_Customerno, $_SESSION['userid'], $today, $_SESSION['userid'], $today);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function editInsdealer($insdealername, $insdealerid) {
        $today = date('Y-m-d H:i:s');
        $Query = "UPDATE insurance_dealer SET ins_dealername = '%s',updatedby = %d,updatedon = '%s' WHERE ins_dealerid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::String($insdealername), $_SESSION['userid'], $today, $insdealerid, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function deleteInsdealer($insdealerid) {
        $today = date('Y-m-d H:i:s');
        $Query = "UPDATE insurance_dealer SET isdeleted = 1,updatedby = %d,updatedon = '%s' WHERE ins_dealerid=%d AND customerno=%d";
        $SQL = sprintf($Query, $_SESSION['userid'], $today, $insdealerid, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getInsdealerByID($insdealerid) {
        $Query = "SELECT ins_dealerid
                ,ins_dealername
                ,customerno
                ,createdby
                ,createdon
                ,updatedby
                ,updatedon
                FROM insurance_dealer
                WHERE customerno = %d
                AND ins_dealerid = %d AND isdeleted = 0
                ";
        $SQL = sprintf($Query, $this->_Customerno,$insdealerid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
                $insdealer = new stdClass();
                $insdealer->ins_dealerid = $row['ins_dealerid'];
                $insdealer->ins_dealername = $row['ins_dealername'];
                $insdealer->customerno = $row['customerno'];
        }
        return $insdealer;
    }

}
