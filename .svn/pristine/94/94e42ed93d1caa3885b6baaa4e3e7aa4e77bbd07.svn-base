<?php

if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
require_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';

class RtddashboardManager extends VersionedManager {

    function __construct($customerNo, $userId) {
        parent::__construct($customerNo);
        $this->customerNo = $customerNo;
        $this->userId = $userId;
        $this->todaysdate = date("Y-m-d H:i:s");
        $this->activeTime = time() - 60 * 60;
        $this->serverActiveTime = date(speedConstants::DEFAULT_TIMESTAMP, $this->activeTime);
    }

    public function getUserGroups() {
        $groups = array();
        $objUserManager = new UserManager();
        $grouplist = $objUserManager->get_groups_fromuser($this->customerNo, $this->userId);
        if (isset($grouplist)) {
            foreach ($grouplist as $group) {
                $groups[] = $group->groupid;
            }
        } else {
            $groups[] = 0;
        }
        return $groups;
    }

    public function getRtdDashboardDeatils($objRequest) {
        $arrRtdData = Array();
        $pdo = $this->_databaseManager->CreatePDOConn();
        //$objRequest->pageSize = 10;
        $sp_params = "'" . $objRequest->pageIndex . "'"
        . ",'" . $objRequest->pageSize . "'"
        . "," . $objRequest->customerNo . ""
        . "," . $objRequest->isWareHouse . ""
        . ",'" . $objRequest->searchString . "'"
        . ",'" . $objRequest->groupsIds . "'"
        . ",'" . $objRequest->userkey . "'"
        . ",'" . $objRequest->isRequiredThirdParty . "'"
        . ",'" . $objRequest->loginType . "'"
        . ",'" . $objRequest->ecode . "'";

        $queryCallSP = "CALL " . speedConstants::SP_GET_RTDDASHBOARD_DETAILS . "($sp_params)";
        $arrRtdData = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        return $arrRtdData;
    }

    public function updateVehicle($objRequest) {
        $noOfRowsAffected = 0;
        $sql = "UPDATE vehicle SET vehicleno = '%s' WHERE vehicleid = %d AND customerno  = %d AND isdeleted = 0 ";
        $sqlQuery = sprintf($sql, $objRequest->vehicleNo, $objRequest->vehicleId, $objRequest->customerNo);
        $this->_databaseManager->executeQuery($sqlQuery);
        $noOfRowsAffected = $this->_databaseManager->get_affectedRows();
        return $noOfRowsAffected;
    }
}
?>
