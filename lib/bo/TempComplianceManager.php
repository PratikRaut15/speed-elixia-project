<?php

require_once '../../lib/system/Validator.php';
require_once '../../lib/system/DatabaseManager.php';
require_once '../../config.inc.php';

define('SP_GET_TEMP_COMPLIANCE', 'get_temp_compliance');

class TempComplianceManager {

    private $_databaseManager = null;

 function __construct() {
        // Constructor
        if ($this->_databaseManager == null) {
            $this->_databaseManager = new DatabaseManager();
        }
    }

    public function PrepareSP($sp_name, $sp_params) {
        return "call " . $sp_name . "(" . $sp_params . ");";
    }

    function get_temp_comp_records($custno, $vehid) {
        $readings = array();
        $pdo = null;
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            $sp_params = "'" . $vehid . "'"
                    . ",'" . $custno . "'";
            $QUERY = $this->PrepareSP(SP_GET_TEMP_COMPLIANCE, $sp_params);
            $queryOutput = $pdo->query($QUERY);
            if ($queryOutput->rowCount() > 0) {
                $readings = $queryOutput->fetchall(PDO::FETCH_ASSOC);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        } finally {
            if ($pdo != null) {
                $this->_databaseManager->ClosePDOConn($pdo);
            }
        }
        return $readings;
    }

    function delete_tempCompReport($custno) {
        $delete_query = "update temp_compliance set isdeleted = 1 where customerno = " . $custno;
        $this->_databaseManager->executeQuery($delete_query);
    }

}

?>