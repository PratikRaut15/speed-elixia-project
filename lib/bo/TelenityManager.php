<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../../";
}
include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
class TelenityManager {
    private $_databaseManager = null;
    private $_customerManager = null;

    public function __construct() {
        // Constructor
        if ($this->_databaseManager == null) {
            $this->_databaseManager = new DatabaseManager();
        }
    }

    public function insertApiToken($obj) {
        $logId = 0;
        $today = date('Y-m-d H:i:s');

        //Check If Request Token Is Alredy Present
        $sqlQuery = "SELECT * FROM requestApiToken";
        $this->_databaseManager->executeQuery($sqlQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $logId = $row['requestId'];
            $updateToken = "UPDATE requestApiToken SET accessToken='%s', tokenType='%s', createdOn =  '%s' ";
            $tokenQuery = sprintf($updateToken, $obj->access_token, $obj->token_type, $today);
            $this->_databaseManager->executeQuery($tokenQuery);
        } else {
            $Query = "INSERT INTO requestApiToken (accessToken, tokenType, createdOn)VALUES ('%s','%s','%s')";
            $tokenQuery = sprintf($Query, $obj->access_token, $obj->token_type, $today);
            $this->_databaseManager->executeQuery($tokenQuery);
            $logId = $this->_databaseManager->get_insertedId();
        }

        return $logId;
    }

    public function getApiToken() {
        $arrToken = array();
        $sqlQuery = "SELECT * FROM requestApiToken";
        $this->_databaseManager->executeQuery($sqlQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_resultRow();
            $arrToken = $row;
        }
        return $arrToken;
    }

    public function getPendingForConsent() {
        $arrConsent = array();
        $sqlQuery = "SELECT * 
                    FROM consent 
                    WHERE serviceProvider <> 1 
                        AND isPaused = 0 
                        AND isStopped = 0 
                        AND consentStatus <> 'ALLOWED' 
                        AND isDeleted = 0 ";
        $this->_databaseManager->executeQuery($sqlQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            //$row = $this->_databaseManager->get_resultRow();
            while ($row = $this->_databaseManager->get_resultRow()) {
                $arrConsent[] = $row;
            }
        }
        return $arrConsent;
    }

    public function updateConsent($obj) {
        $noOfRowsAffected = 0;
        $today = date('Y-m-d H:i:s');
        if(isset($obj->status)){
            $sql = "UPDATE consent SET consentStatus='%s', updatedOn =  '%s' WHERE uid = %d ";
            $query = sprintf($sql, $obj->status, $today, $obj->uid );
            $this->_databaseManager->executeQuery($query);
            $noOfRowsAffected = $this->_databaseManager->get_affectedRows();
            if($obj->status == "ALLOWED"){
                //$this->notifyCustomer($obj);
            }
        }
        return $noOfRowsAffected;
    }
    public function notifyCustomer($obj){
        //print_r($obj);
        // $query = "";
        // $pdo = $this->_databaseManager->CreatePDOConn();
        //$query = sprintf($query,$unit->uid,$unit->customerno,$logObj->messageCode,$logObj->messageText,$logObj->variables,$userId,$today,$logObj->valid);
        //$result = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);

        $arrTo = array("software@elixiatech.com","");
        $strCCMailIds = "";
        $strBCCMailIds = "";
        $subject = "Consent received";
        $content = "Dear customer,<br/> Location tracking consent has been received for the device : ".$obj->deviceNo." <br/> Regards, <br/> Team Elixiatech.";
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = 1;
        //echo $content;
        $isMailSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $content, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
        return $isMailSent;
    }
    public function getDevicesForLocation($objRequest) {
        $arrDevices = array();
        $Query = "SELECT
                unit.uid,
                unit.unitno,
                unit.customerno,
                devices.deviceid,
                vehicle.vehicleid,
                vehicle.vehicleno,
                consent.consentStatus,
                consent.deviceNo,
                consent.locationStatus
            from unit
            INNER JOIN consent ON consent.uid = unit.uid
            INNER JOIN devices ON devices.uid = unit.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            where unit.trans_statusid NOT IN (10,22) ";
            if(isset($objRequest->unitNo)){
                $Query .= " AND unit.unitno = ".$objRequest->unitNo;
            }
            $Query .= " AND (consent.consentStatus = 'ALLOWED' OR consent.serviceProvider = 1)
            AND consent.locationStatus IN (".$objRequest->locationStatus.")
            AND vehicle.isdeleted = 0
            AND consent.isStopped = 0 AND consent.isPaused = 0;";
        //echo $Query;
        $devicesQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($devicesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new stdClass();
                $device->uid = $row['uid'];
                $device->unitno = $row['unitno'];
                $device->customerno = $row['customerno'];
                $device->deviceid = $row['deviceid'];
                $device->vehicleid = $row['vehicleid'];
                $device->vehicleno = $row['vehicleno'];
                $device->consentStatus = $row['consentStatus'];
                $device->deviceNo = $row['deviceNo'];
                $arrDevices[] = $device;
            }
            return $arrDevices;
        }
        return $arrDevices;
    }

    public function updateDeviceDetails($objLocation) {
        $noOfRowsAffected = 0;
        $today = date('Y-m-d H:i:s');
        $objLocation->lastupdated = date('Y-m-d H:i:s', strtotime($objLocation->timestamp));
        $sql = "UPDATE devices SET devicelat='%s', devicelong =  '%s', lastupdated = '%s' WHERE uid = %d AND customerno = %d";
        $query = sprintf($sql, $objLocation->latitude, $objLocation->longitude, $objLocation->lastupdated, $objLocation->uid, $objLocation->customerno );
        $this->_databaseManager->executeQuery($query);
        $noOfRowsAffected = $this->_databaseManager->get_affectedRows();
        //updating nearest distance for trip.
        $sql = "SELECT t.tripId , c.cgeolat,c.cgeolong,t.nearestLat,t.nearestLng
                FROM trips t
                INNER JOIN checkpoint c ON c.checkpointid = t.destId
                WHERE t.uid = ".$objLocation->uid." 
                    AND t.isClosed = 0 
                    AND t.isDeleted= 0 
                    AND t.tripStatus NOT IN (2,3);";
                    //echo $sql;
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                //print_r($row);
                $tripId = $row['tripId'];
                $destLat = $row['cgeolat'];
                $destLng = $row['cgeolong'];
                $nearestLat = $row['nearestLat'];
                $nearestLng = $row['nearestLng'];
                $nearestDist = $this->dist_calculate($nearestLat,$nearestLng,$destLat,$destLng);
                $currentDist = $this->dist_calculate($objLocation->latitude,$objLocation->longitude,$destLat,$destLng);
                if($currentDist<$nearestDist){
                    $this->updateDistance($tripId,$objLocation->latitude,$objLocation->longitude);
                }
            }
        }
        return $noOfRowsAffected;
    }
    public function updateDistance($tripId,$lat,$lng){
        $today = date('Y-m-d H:i:s');
        $query = "UPDATE trips 
                SET 
                    nearestLat = '".$lat."', 
                    nearestLng = '".$lng."',
                    updatedOn = '".$today."',
                    `tripStatus` = 1 
                WHERE `tripId` = ".$tripId." 
                    and `tripStatus`=0;";
        $this->_databaseManager->executeQuery($query);
    }
    public function calcRad($x) {
        return $x*pi()/180;
    }

    public function dist_calculate($devicelat,$devicelong,$cgeolat,$cgeolong)  {
        //Earth's mean radius in km
        $ERadius = 6371; 

        //Difference between devicelatlong and checkpointlatlong
        $diffLat  = $this->calcRad($cgeolat - $devicelat);
        $diffLong = $this->calcRad($cgeolong - $devicelong);

        //Converting between devicelatlong to radians
        $devlat_rad = $this->calcRad($devicelat);
        $devlong_rad = $this->calcRad($cgeolat);

        //Calculation Using Haversine's formula
        //Applying Haversine formula
        $a = sin($diffLat/2) * sin($diffLat/2) + cos($devlat_rad) * cos($devlong_rad) * sin($diffLong/2) * sin($diffLong/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        //Distance
        $diffdist = $ERadius * $c;
        
        return $diffdist;
    }
    public function updateLocationStatus($objLocation){
        $today = date('Y-m-d H:i:s');
        $objLocation->lastupdated = date('Y-m-d H:i:s', strtotime($today));
        $sql = "UPDATE consent SET locationStatus=%d, updatedOn = '%s' WHERE uid = %d AND customerno = %d";
        $query = sprintf($sql, $objLocation->locationStatus, $objLocation->lastupdated, $objLocation->uid, $objLocation->customerno );
        $this->_databaseManager->executeQuery($query);
        $noOfRowsAffected = $this->_databaseManager->get_affectedRows();
        return $noOfRowsAffected;
    }

    public function refreshLocation($unitNo){
        $tokenDetails = $this->getApiToken();
        $arrHeaders = array();
        //$authorization =  "Authorization: Bearer d057711b90f428c0af61700c2e9193f3";
        $arrHeaders[] = "Content-Type: application/json";
        $arrHeaders[] = "Authorization: Bearer " . $tokenDetails['accessToken'];            
        $arrResult = array();
        $arrResult['status']=0;
        $arrResult['result'] = '';
        if (isset($tokenDetails) && !empty($tokenDetails)) {
            $objRequest = new stdClass();
            $objRequest->locationStatus = '-1,0,1';
            $objRequest->unitNo = $unitNo;
            $device = $this->getDevicesForLocation($objRequest);
            //print_r($device);
            if(isset($device)&&(!empty($device))){
                $device = $device[0];
                $url = "https://35.154.136.146/apigw/Location/v2/location_check?address=tel:+" . $device->deviceNo . "&requestedAccuracy=1000";
                $printLog = 0; //optional parameter for curl execution in utilities. 0 to hide log, 1 to show.
                $locationDetails = curlExecution($url, $arrHeaders, $printLog);
                $location = new stdClass();
                $location->vehicleId = $device->vehicleid;                           
                $location->uid = $device->uid;
                $location->unitno = $device->unitno;
                $location->deviceNo = $device->deviceNo;
                $location->customerno = $device->customerno;
                $location->deviceid = $device->deviceid;
                $location->locationStatus = 1;
                $location->vehicleId = $device->vehicleid;
                if ($locationDetails) {
                    //print_r($locationDetails);
                    if (isset($locationDetails->terminalLocationList->terminalLocation[0]->currentLocation)) {
                        $location = $locationDetails->terminalLocationList->terminalLocation[0]->currentLocation;
                        if (isset($location) && !empty($location)) {
                            $location->uid = $device->uid;
                            $location->unitno = $device->unitno;
                            $location->deviceNo = $device->deviceNo;
                            $location->customerno = $device->customerno;
                            $location->deviceid = $device->deviceid;
                            $location->locationStatus = 1;
                            $location->vehicleId = $device->vehicleid;
                            $date = date('Y-m-d', strtotime($location->timestamp));
                            //print_r($location);
                            $this->updateDeviceDetails($location);
                            $this->updateLocationStatus($location);
                            $objSqlite = new SqliteManager($location->customerno, $location->unitno, $date);
                            $objSqlite->InsertTelenityDeviceHistory($location);
                            $arrResult['status']=1;
                            $arrResult['result'] = $location;
                        }
                    } elseif (isset($locationDetails->requestError) && !empty($locationDetails->requestError)) {
                        $location = new stdClass();
                        $location->uid = $device->uid;
                        $location->customerno = $device->customerno;
                        $location->locationStatus = -1;
                        $this->updateLocationStatus($location);
                        $arrResult['status']=0;
                        $arrResult['result'] = $location;
                        $arrResult['error']=$locationDetails->requestError;
                    }
                    $result = $this->updatePingLog($locationDetails,$location);
                    $arrResult['pingsLeft'] = is_numeric($result['pingsLeft'])?$result['pingsLeft']:"N/A";
                }
            }
        }
        return $arrResult;
    }

    public function pauseTracking($unitNo){
        $today = date('Y-m-d H:i:s');
        $unitNo = ltrim($unitNo, '0');
        $query = "UPDATE consent SET isPaused=1 WHERE deviceNo = %d";
        $query = sprintf($query,$unitNo);
        $this->_databaseManager->executeQuery($query);
        $noOfRowsAffected = $this->_databaseManager->get_affectedRows();
        return $noOfRowsAffected;
    }    

    public function enableTracking($unitNo){
        $today = date('Y-m-d H:i:s');
        $unitNo = ltrim($unitNo, '0');
        $query = "UPDATE consent SET isPaused=0 WHERE deviceNo = %d";
        $query = sprintf($query,$unitNo);
        $this->_databaseManager->executeQuery($query);
        $noOfRowsAffected = $this->_databaseManager->get_affectedRows();
        return $noOfRowsAffected;
    }

    public function stopTracking($unitNo){
        $unitNo = ltrim($unitNo, '0');
        $query = "UPDATE consent SET isStopped=1 WHERE deviceNo = %d";
        $query = sprintf($query,$unitNo);
        $this->_databaseManager->executeQuery($query);
        $noOfRowsAffected = $this->_databaseManager->get_affectedRows();
        return $noOfRowsAffected;
    }

    public function getUnitSettings($unitNo){
        $today = date('Y-m-d H:i:s');
        $unitNo = ltrim($unitNo, '0');
        $query = "SELECT consent.deviceNo,consent.isPaused,consent.isStopped,u.vehicleid,consent.consentStatus
                FROM consent 
                INNER JOIN unit u ON u.uid = consent.uid
                WHERE deviceNo = %d;";
        $query = sprintf($query,$unitNo);
        $result = array();
        $result['status'] = 0;
        $result['result'] = 'Unit not found';
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $unit = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $unit = $row;
            }
            $result['status'] = 1;
            $result['result'] = $unit;
        }
        return $result;
    }   

    public function recursiveWalk($input,&$log){
        $text = '';
        $flag = 0;
        foreach($input as $i=>$k){
            if(is_array($k)){
                if($i=='variables'){
                    $log->valid = 0;
                    foreach($k as $vars){
                        $text .= $vars;
                    }
                    $log->variables = $text;
                }else{
                    $this->recursiveWalk($k,$log);
                }
            }elseif(is_object($k)){
                $this->recursiveWalk($k,$log);
            }else{
                if($i==='messageId'){
                    $log->valid = 0;
                    $log->messageCode = $k;
                }elseif($i=='text'){
                    $log->valid = 0;
                    $log->messageText =$k;
                    if($log->messageText=='Out Of Coverage'||$log->messageText=='Phone Switched Off'){
                        $log->isValid = 1;
                    }
                }elseif($i=='latitude'||$i=='longitude'){
                    if($flag==1){
                        $log->$i = $k;
                        $log->messageText = $log->latitude.','.$log->longitude;
                        $log->valid = 1;
                        $log->messageCode = 0;
                        $log->variables = '';
                    }else{
                        $flag = 1;
                        $log->$i = $k;
                    }
                }
                elseif($i == 'code'){
                    $log->valid = 0;
                    $log->messageCode = $k;
                    $log->variables = '';
                }elseif($i == 'message'){
                    $log->messageText = $k;
                }elseif($i=='status'){
                    $log->messageText = $k;
                    $log->valid = 0;
                    $log->messageCode = 0;
                    $log->variables = '';
                }
            }
        }
        if(isset($log->isValid)&&($log->isValid==1)){
            $log->valid = 1;
        }
        return $log;
    }

    public function updatePingLog($response,$unit){
        $today = date('Y-m-d H:i:s');
        $log = new stdClass();
        $logObj = new stdClass();
        $logObj = $this->recursiveWalk($response,$log);
        if(isset($_SESSION)){
            $userId = $_SESSION['userid'];
        }else{
            $userId = 0;
        }
        if(isset($logObj)){
            if(isset($logObj->messageCode)){
                if(($logObj->messageCode === 'POL0001') OR ($logObj->messageCode === 'POL0002')){
                    $notifyObj = new stdClass();
                    $notifyObj->messageCode = $logObj->messageCode;
                    $notifyObj->messageText = $logObj->messageText;
                    $notifyObj->variables = $logObj->variables;
                    $notifyObj->uid = $unit->uid;
                    $notifyObj->address =  $unit->deviceNo;
                    //$this->notifyTelenity($notifyObj);
                }
            }
        }
        $query = "CALL ".constants::SP_UPDATE_TELENITY_LOG."(%d,%d,'%s','%s','%s',%d,'%s',%d,@isExecutedOut,@pingCountOut)";
        $pdo = $this->_databaseManager->CreatePDOConn();
        $query = sprintf($query,$unit->uid,$unit->customerno,$logObj->messageCode,$logObj->messageText,$logObj->variables,$userId,$today,$logObj->valid);
        $result = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);
        $query = "select @isExecutedOut as executedOut,@pingCountOut as pingsLeft";
        $result = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);
        return $result;
    } 

    public function notifyTelenity($obj){
        $arrTo = array("apimanager@telenity.com");
        $strCCMailIds = "support@elixiatech.com,sanketsheth@elixiatech.com,kushal.d@elixiatech.com";
        $strBCCMailIds = "";
        $subject = "Error while tracking location";
        $content = "Hi Telenity Team,\r\nThe following error was raised for \r\nNumber : ".$obj->address."\r\nError code :  ".$obj->messageCode." \r\nError message : ".$obj->messageText.".\r\nVariables : ".$obj->variables." \r\nPlease look into it and get back to us ASAP.\r\nRegards, \r\n Team Elixiatech.";
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = 1;
//        $isMailSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $content, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
//        return $isMailSent;
    }

    public function pullPingLogs($customerNo){
        $pdo = $this->_databaseManager->CreatePDOConn();
        $query = "SELECT l.logId,l.uid,l.messageCode,l.messageText,l.responseVariables,v.vehicleno,
            (CASE WHEN (u.realName IS NULL) THEN 
                'System Update' 
            ELSE u.realName END ) as createdBy,l.createdOn,
            (CASE WHEN l.messageText LIKE('%,%') THEN 
            'Location retrieved' 
            ELSE 'Out of coverage'
            END) as responseStatus
        FROM telenityLogs l 
        INNER JOIN vehicle v ON v.uid = l.uid and v.isdeleted = 0 
        INNER JOIN devices d ON d.uid = l.uid 
        LEFT JOIN user u ON u.userid = l.createdBy
        WHERE l.isValid = 1 AND l.customerNo =".$customerNo;
        $result = $pdo->query($query)->fetchall(PDO::FETCH_ASSOC);
        $i=1;
        if(isset($result)){
            foreach($result as &$record){
                //print_r($record);
                $record['logId'] = 'P00'.$record['logId'];
                $record['createdOn'] = date("d-m-Y H:i:s", strtotime($record['createdOn']));
                $record['srNo'] = $i;
                $i++;
            }
        }
        return $result;
    }

    public function sendConsent($device){
        $objTelenity = new TelenityManager();
        $tokenDetails = $objTelenity->getApiToken();
        $arrHeaders = array();
        //$authorization =  "Authorization: Bearer d057711b90f428c0af61700c2e9193f3";
        $arrHeaders[] = "Content-Type: application/json";
        $arrHeaders[] = "Authorization: Bearer " . $tokenDetails['accessToken'];
        $consent = '';
        if (isset($tokenDetails) && !empty($tokenDetails)) {
            $url = "https://35.154.136.146/apigw/Consent/v2/consent_check?address=tel:+" . $device['deviceNo'];
            $consent = curlExecution($url, $arrHeaders,0);
            if (isset($consent->Consent)) {
                $consent->Consent->uid = $device['uid'];
                $consent->Consent->deviceNo = $device['deviceNo'];
                //print_r($consent->Consent);
                $objTelenity->updateConsent($consent->Consent);
            } 
        }
        return $consent;
    }
}
