<?php

require_once '../../lib/system/Validator.php';
require_once '../../lib/system/DatabaseManager.php';
require_once '../../config.inc.php';

define('SP_GET_TOGGLESWITCH_TRIPS', 'get_toggleswitch_trips');

class ToggleSwitchManager {

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

    function get_toggleswitch_trips($custno, $vehid, $startDate, $endDate, $groupid) {
        $readings = array();
        $pdo = null;
        try {
            $pdo = $this->_databaseManager->CreatePDOConn();
            $sp_params = "'" . $vehid . "'"
                    . ",'" . $custno . "'"
                    . ",'" . $startDate . "'"
                    . ",'" . $endDate . "'"
                    . ",'" . $groupid . "'";
            $QUERY = $this->PrepareSP(SP_GET_TOGGLESWITCH_TRIPS, $sp_params);
            $queryOutput = $pdo->query($QUERY);
            if ($queryOutput->rowCount() > 0) {
                $readings = $queryOutput->fetchall(PDO::FETCH_ASSOC);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
            if ($pdo != null) {
                $this->_databaseManager->ClosePDOConn($pdo);
            }
        return $readings;
    }

    function getWeighBridgeDetails($toggleTripId,$customerNo) {
        $weighDetails = array();
        $Query = "SELECT toggleId,transactionId,grossWeight,netWeight,unladenWeight,customerno
            FROM weighBridgeDetails
            where customerno = %d AND toggleId = %d and isdeleted = 0";
        $weighDetailsQuery = sprintf($Query, $customerNo, $toggleTripId);
        $this->_databaseManager->executeQuery($weighDetailsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $weighDetail = new stdClass();
                $weighDetail->toggleTripId = $row['toggleId'];
                $weighDetail->grossWeight = $row['grossWeight'];
                $weighDetail->netWeight = $row['netWeight'];
                $weighDetail->unladenWeight = $row['unladenWeight'];
                $weighDetail->transactionId = $row['transactionId'];
                $weighDetail->customerno = $row['customerno'];
                $weighDetails[] = $weighDetail;
            }

        }
        return $weighDetails;
    }

    function getTransactionIdByCustomerNumber($customerNumber)
    {
        $weighDetails = array();
        $Query = "SELECT weighDetailsId,transactionId,customerNo
            FROM weighBridgeDetails
            where customerno = %d";
        $weighDetailsQuery = sprintf($Query, $customerNumber);
        $this->_databaseManager->executeQuery($weighDetailsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $weighDetail = new stdClass();
                $weighDetail->weighDetailsId = $row['weighDetailsId'];
                $weighDetail->transactionId = $row['transactionId'];
                $weighDetail->customerNo = $row['customerNo'];
                $weighDetails[] = $weighDetail;
            }

        }
        return $weighDetails;
    }

    function updateDataFetchedByApi($query)
    {
        $this->_databaseManager->executeQuery($query);
    }
}

?>
