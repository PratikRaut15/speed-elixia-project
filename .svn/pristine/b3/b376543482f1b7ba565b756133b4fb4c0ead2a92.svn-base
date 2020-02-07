<?php

require_once '../../lib/system/DatabaseManager.php';
require_once '../../lib/system/Date.php';
require_once '../../lib/system/Sanitise.php';

class TelAlertsManager {

	private $_databaseManager = null;

	function __construct($customerno, $userid) {
		if ($this->_databaseManager == null) {
			$this->_databaseManager = new DatabaseManager();
		}
		$this->customerno = $customerno;
		$this->userid = $userid;
		$this->todaysdate = date("Y-m-d H:i:s");
	}

	public function insertTelAlert($objTelAlert) {
		$telAlertId = 0;
		try {
			$pdo = $this->_databaseManager->CreatePDOConn();
			/* Prepare SP Parameters */
			$sp_params = "'" . $objTelAlert->cqid . "'"
			. ",'" . $objTelAlert->vehicleid . "'"
			. ",'" . $objTelAlert->userid . "'"
			. ",'" . $objTelAlert->moduleid . "'"
			. ",'" . $objTelAlert->mobileno1 . "'"
			. ",'" . $objTelAlert->mobileno2 . "'"
			. ",'" . $objTelAlert->mobileno3 . "'"
			. ",'" . $objTelAlert->message . "'"
			. ",'" . $this->customerno . "'"
			. ",'" . $this->todaysdate . "'"
				. "," . "@telAlertId";
			$query = $this->_databaseManager->PrepareSP(speedConstants::SP_INSERT_TELALERT, $sp_params);
			$pdo->query($query);
			$outputParamsQuery = "SELECT @telAlertId AS telAlertId";
			$outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
			if (count($outputResult) > 0) {
				$telAlertId = $outputResult["telAlertId"];
			}
		} catch (Exception $ex) {
			$log = new Log();
			$log->createlog($this->customerno, $ex, $this->userid, speedConstants::MODULE_VTS, __FUNCTION__);
		}
		return $telAlertId;
	}

	public function updateTelAlert($objTelAlert) {
		$noOfAffectedRows = 0;
		try {
			$sp_params = "'" . $objTelAlert->telAlertId . "'"
			. "'" . $objTelAlert->cqid . "'"
			. ",'" . $objTelAlert->callSid . "'"
			. ",'" . $objTelAlert->response . "'"
			. ",'" . $objTelAlert->createdDate . "'"
			. ",'" . $objTelAlert->status . "'"
			. ",'" . $objTelAlert->userResponse . "'"
			. ",'" . $objTelAlert->updatedDate . "'"
			. ",'" . $this->customerno . "'"
			. ",'" . $this->todaysdate . "'";
			$query = $this->_databaseManager->PrepareSP(speedConstants::SP_UPDATE_TELALERT, $sp_params);
			$queryResult = $this->_databaseManager->executeQuery($query);
			$noOfRowAffected = $this->_databaseManager->get_affectedRows();
		} catch (Exception $ex) {
			$log = new Log();
			$log->createlog($this->customerno, $ex, $this->userid, speedConstants::MODULE_VTS, __FUNCTION__);
		}
		return $noOfAffectedRows;
	}

	public function getTelAlert($objTelAlert) {
		$arrResult = null;
		$objTelAlert->categoryid = isset($objTelAlert->categoryid) ? $objTelAlert->categoryid : 0;
		try {
			$pdo = $this->_databaseManager->CreatePDOConn();
			//Prepare parameters
			$sp_params = "'" . $objTelAlert->telAlertLogId . "'"
			. "'" . $objTelAlert->cqid . "'"
			. ",'" . $objTelAlert->callSid . "'"
			. ",'" . $objTelAlert->vehicleId . "'"
			. ",'" . $objTelAlert->userId . "'"
			. ",'" . $this->customerno . "'";
			$query = $this->_databaseManager->PrepareSP(speedConstants::SP_GET_TELALERT, $sp_params);
			$arrResult = $pdo->query($query)->fetchall(PDO::FETCH_ASSOC);
			$this->_databaseManager->ClosePDOConn($pdo);
		} catch (Exception $ex) {
			$log = new Log();
			$log->createlog($this->customerno, $ex, $this->userid, speedConstants::MODULE_VTS, __FUNCTION__);
		}
		return $arrResult;
	}

	public function getTelAlertMessage($objTelAlert) {
		$arrResult = null;

		try {
			$pdo = $this->_databaseManager->CreatePDOConn();
			//Prepare parameters
			$sp_params = "'" . $objTelAlert->cqid . "'"
			. ",'" . $objTelAlert->customerno . "'";
			$query = $this->_databaseManager->PrepareSP(speedConstants::SP_GET_COMQUEUE_MESSAGE, $sp_params);
			$arrResult = $pdo->query($query)->fetchall(PDO::FETCH_ASSOC);
			$this->_databaseManager->ClosePDOConn($pdo);
		} catch (Exception $ex) {
			$log = new Log();
			$log->createlog($this->customerno, $ex, $this->userid, speedConstants::MODULE_VTS, __FUNCTION__);
		}
		return $arrResult;
	}

}
