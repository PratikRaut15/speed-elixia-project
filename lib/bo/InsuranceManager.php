<?php

include_once '../../lib/system/Validator.php';
include_once '../../lib/system/DatabaseManager.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/system/Date.php';

class Datacap {
    
}

class InsuranceManager extends VersionedManager {

    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function getInsuranceCompany() {
        $inscom = Array();
        $Query = "SELECT * FROM insurance_company WHERE customerno IN(0,%d) AND isdeleted=0";
        $insQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($insQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $insco = new Datacap();
                $insco->id = $row['id'];
                $insco->name = $row['name'];
                $insco->customerno = $row['customerno'];
                $inscom[] = $insco;
            }
        }
        return $inscom;
    }

    public function add_insurance($inscompany, $userid) {
        $today = date("Y-m-d H:i:s");
        $Query = "INSERT INTO insurance_company(name,customerno,userid,timestamp) VALUES('%s',%d,%d,'%s')";
        $insQuery = sprintf($Query, trim($inscompany), $this->_Customerno, $userid, $today);
        $this->_databaseManager->executeQuery($insQuery);
    }

    public function del_insurance($inscompid, $userid) {
        $today = date("Y-m-d H:i:s");
        $Query = "UPDATE insurance_company SET isdeleted=1,userid=%d,timestamp='%s' WHERE id=%d AND customerno=%d";
        $insQuery = sprintf($Query, $userid, $today, $inscompid, $this->_Customerno);
        $this->_databaseManager->executeQuery($insQuery);
    }

    public function get_byinsuranceid($inscompid, $userid) {
        $inscom = Array();
        $Query = "SELECT * FROM insurance_company WHERE customerno=%d AND id=%d AND isdeleted=0";
        $insQuery = sprintf($Query, $this->_Customerno, $inscompid);
        $this->_databaseManager->executeQuery($insQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $insco = new Datacap();
                $insco->id = $row['id'];
                $insco->name = $row['name'];
                $insco->customerno = $row['customerno'];
            }
        }
        return $insco;
    }

    public function edit_insurance($insname, $insid, $userid) {
        $today = date("Y-m-d H:i:s");
        $Query = "UPDATE insurance_company SET name='%s',userid=%d,timestamp='%s' WHERE id=%d AND customerno=%d";
        $insQuery = sprintf($Query, trim($insname), $userid, $today, $insid, $this->_Customerno);
        $this->_databaseManager->executeQuery($insQuery);
    }

}

?>
