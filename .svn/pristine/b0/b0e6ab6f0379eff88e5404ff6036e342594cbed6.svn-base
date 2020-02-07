<?php

/**
 * Description of SMSLogManager
 *
 * @author Mrudang Vora
 */
include_once '../../lib/system/VersionedManager.php';
define("SP_GET_SMSLOGS", "get_SMSLogs");

class SMSLogManager extends VersionedManager {

 function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

 public function getSMSLog($moduleid, $dateparam) {
  $arrResult = array();
  //Prepare parameters
  $sp_params = "'" . $this->_Customerno . "'"
   . ",'" . $moduleid . "'"
   . ",'" . $dateparam . "'";
  $sqlGetSMSLogsSP = "CALL " . SP_GET_SMSLOGS . "($sp_params)";
  $this->_databaseManager->executeQuery($sqlGetSMSLogsSP, __FILE__, __LINE__);
  $arrResult = $this->_databaseManager->get_recordSet();
  return $arrResult;
 }

}
