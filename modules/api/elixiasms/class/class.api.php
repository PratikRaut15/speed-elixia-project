<?php

require_once "global.config.php";
require_once "database.inc.php";

date_default_timezone_set('Asia/Kolkata');

class api {
    //<editor-fold defaultstate="collapsed" desc="Constructor">
    // construct
    function __construct() {
        $this->db = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, SPEEDDB);
    }

    // </editor-fold>
    //
    //<editor-fold defaultstate="collapsed" desc="API functions">
    function sendSMSForVTSUtilities($objRequest) {

        $arrResult = array();
        $arrResult['status'] = 0;
        $arrResult['message'] = speedConstants::API_INVALID_USERKEY;
        $arrResponse = array();
        $validation = $this->checkUser($objRequest);
        if ($validation['status'] == 1) {
            $this->insertElixiaSms($objRequest, $validation);
            $arrResult['status'] = 1;
            $arrResult['message'] = "Details saved successfully";
        }

        return $arrResult;
    }

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Helper functions">
    function checkUser($objRequest) {
        $retarray = array();
        $retarray['status'] = 0;
        $sql = "SELECT u.userid, u.customerno
                    FROM user u
                    INNER JOIN customer c on c.customerno = u.customerno
                    WHERE u.phone='" . $objRequest->phoneNo . "' AND u.isdeleted=0 AND c.isoffline = 0 LIMIT 1;";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $row = $this->db->fetch_array($record);
        $devices = $this->checkforvalidity($row["customerno"]);
        $initday = 0;
        if (isset($devices)) {
            foreach ($devices as $thisdevice) {
                $days = $this->check_validity_login($thisdevice->expirydate, $thisdevice->today);
                if ($days > 0) {
                    $initday = $days;
                }
            }
            if ($initday > 0) {
                $retarray['status'] = 1;
                $retarray['customerno'] = $row["customerno"];
                $retarray['userid'] = $row["userid"];
            }
        }
        return $retarray;
    }

    function checkforvalidity($customerno, $deviceid = null) {
        $devices = Array();
        $Query = "SELECT deviceid,expirydate, Now() as today FROM `devices` where customerno=%d ";
        if ($deviceid != null) {
            $Query .= " AND deviceid = $deviceid";
        }
        $devicesQuery = sprintf($Query, $customerno);
        $record = $this->db->query($devicesQuery, __FILE__, __LINE__);

        while ($row = $this->db->fetch_array($record)) {
            $device = new stdClass();
            $device->deviceid = $row['deviceid'];
            $device->today = $row["today"];
            $device->expirydate = $row["expirydate"];
            $devices[] = $device;
        }
        return $devices;
    }

    function check_validity_login($expirydate, $currentdate) {
        date_default_timezone_set('Asia/Kolkata');
        $expirytimevalue = '23:59:59';
        $expirydate = date('Y-m-d H:i:s', strtotime("$expirydate $expirytimevalue"));
        $realtime = strtotime($currentdate);
        $expirytime = strtotime($expirydate);
        $diff = $expirytime - $realtime;
        return $diff;
    }

    function insertElixiaSms($objRequest, $validation) {
        $customerno = $validation['customerno'];
        $userid = $validation['userid'];
        $pdo = $this->db->CreatePDOConn();
        $todaysdate = date("Y-m-d H:i:s");
        $sp_params = "'" . $objRequest->phoneNo . "'"
        . ",'" . $objRequest->message . "'"
        . ",'" . $objRequest->timeAdded . "'"
        . ",'" . $userid . "'"
        . ",'" . $customerno . "'"
        . ",'" . $todaysdate . "'"
        . "," . '@insertedId';

        $queryCallSP = "CALL " . speedConstants::SP_ELIXIASMS_INSERT_REQUEST . "($sp_params)";
        $pdo->query($queryCallSP);
        $outputParamsQuery = "SELECT @insertedId AS insertedId";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $this->db->ClosePDOConn($pdo);
    }

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Utility functions">
    function failure($text) {
        return array('Status' => '0', 'Message' => $text);
    }

    function success($message, $result) {
        return array('Status' => '1', 'Message' => $message, 'Result' => $result);
    }
}

?>
