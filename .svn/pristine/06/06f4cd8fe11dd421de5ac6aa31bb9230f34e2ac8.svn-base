<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
if (!defined("RELATIVE_PATH_DOTS")) {
    define("RELATIVE_PATH_DOTS", $RELATIVE_PATH_DOTS);
}
/**
 * Description of SqliteManager
 *
 * @author Mrudang
 */
class SqliteManager {

    private $sqlitePath = null;

    public function __construct($customerno, $unitno, $date) {
        $this->sqlitePath = "sqlite:". RELATIVE_PATH_DOTS ."customer/" . $customerno . "/unitno/" . $unitno . "/sqlite/" . JustDate($date) . ".sqlite";
    }

    private function CreatePDOConn() {
        $sqliteCon = new PDO($this->sqlitePath);
        return $sqliteCon;
    }

    private function ClosePDOConn($sqliteCon) {
        if (isset($sqliteCon)) {
            $sqliteCon = NULL;
        }
    }

    /* Same as CheckDb function in existing Listener */
    public function CheckDb(){
        $db = $this->CreatePDOConn();
        $tables = "CREATE TABLE IF NOT EXISTS data (dataid INTEGER,data TEXT,client TEXT, insertedon DATETIME, PRIMARY KEY(dataid));";
        $tables .= " CREATE TABLE IF NOT EXISTS unithistory (uhid INTEGER,uid INTEGER,unitno TEXT,customerno INTEGER,vehicleid INTEGER,";
        $tables .= "analog1 TEXT,analog2 TEXT,analog3 TEXT,analog4 TEXT,digitalio TEXT,lastupdated datetime,commandkey TEXT,commandkeyval TEXT,PRIMARY KEY (uhid));";
        $tables .= " CREATE TABLE IF NOT EXISTS devicehistory(id INTEGER,deviceid INTEGER,customerno INTEGER,devicelat TEXT,devicelong TEXT,";
        $tables .= "lastupdated datetime,altitude INTEGER,directionchange TEXT,inbatt TEXT,hwv TEXT,swv TEXT,msgid TEXT,";
        $tables .= "uid INTEGER,status TEXT,ignition INTEGER,powercut INTEGER,tamper INTEGER,gpsfixed TEXT,`online/offline` INTEGER,gsmstrength INTEGER,";
        $tables .= "gsmregister INTEGER,gprsregister INTEGER,satv TEXT,PRIMARY KEY (id));";
        $tables .= " CREATE TABLE IF NOT EXISTS vehiclehistory(vehiclehistoryid INTEGER,vehicleid INTEGER,vehicleno TEXT,";
        $tables .= "extbatt TEXT,odometer INTEGER,lastupdated datetime,curspeed INTEGER,customerno INTEGER,driverid INTEGER,uid TEXT,PRIMARY KEY (vehiclehistoryid));";
        $tables .= "CREATE INDEX IF NOT EXISTS `fk_vehiclehistory` ON `vehiclehistory` (`lastupdated`);";
        $tables .= "CREATE INDEX IF NOT EXISTS `fk_unithistory` ON `unithistory` (`lastupdated`);";
        $tables .= "CREATE INDEX IF NOT EXISTS `fk_devicehistory` ON `devicehistory` (`lastupdated`);";
        $db->exec($tables);
        $this->ClosePDOConn($db);
    }

    public function InsertDataInSqlite($rawData, $objDeviceData) {
		$this->CheckDb();
        $db = $this->CreatePDOConn();
        $dataquery = "INSERT INTO data(data,insertedon) VALUES('%s','%s')";
        $query = sprintf($dataquery, $rawData, today());

        $db->exec("BEGIN IMMEDIATE TRANSACTION");
        $db->exec($query);
        $db->exec("COMMIT TRANSACTION");

        if ($objDeviceData->isPacketTimeValid){
            //Device History Data
            $columns = "deviceid,customerno,devicelat,devicelong
                        ,lastupdated,altitude,directionchange,inbatt
                        ,hwv,swv,msgid,uid
                        ,status,ignition,powercut,tamper
                        ,gpsfixed,`online/offline`,gsmstrength,gsmregister
                        ,gprsregister,satv";
            $values = "'" . $objDeviceData->deviceid . "' ,'" . $objDeviceData->customerno . "' ,'" . $objDeviceData->devicelat . "' ,'"  . $objDeviceData->devicelong
                        . "' ,'" . $objDeviceData->timestamp . "' ,'" . $objDeviceData->altitude . "' ,'" . $objDeviceData->directionchange . "' ,'" . $objDeviceData->inbatt
                        . "' ,'" . $objDeviceData->hwv . "' ,'" . $objDeviceData->swv . "' ,'" . $objDeviceData->msgid . "' ,'" . $objDeviceData->unitid
                        . "' ,'" . $objDeviceData->status . "' ,'" . $objDeviceData->ignition . "' ,'" . $objDeviceData->powercut. "' ,'" . $objDeviceData->tamper
                        . "' ,'" . $objDeviceData->gpsfixed . "' ,'" . $objDeviceData->isOffline . "' ,'" . $objDeviceData->gsmstrength. "' ,'" . $objDeviceData->gsmregister
                        . "' ,'" . $objDeviceData->gprsregister . "' ,'" . $objDeviceData->satv . "'"
                        ;
            $deviceQuery = "INSERT INTO devicehistory (" . $columns . ") VALUES (" . $values . ");";

            //Vehicle History Data
            $columns = "vehicleid,vehicleno,extbatt,odometer
                        ,lastupdated,curspeed,customerno
                        ,driverid,uid";
            $values = "'". $objDeviceData->vehicleid . "' ,'" . $objDeviceData->vehicleno . "' ,'" . $objDeviceData->extbatt . "' ,'" . $objDeviceData->odometer
                        . "' ,'" . $objDeviceData->timestamp . "' ,'" . $objDeviceData->speed . "' ,'" . $objDeviceData->customerno
                        . "' ,'" . $objDeviceData->driverid . "' ,'" . $objDeviceData->unitid . "'"
                        ;

            $vehicleQuery = "INSERT INTO vehiclehistory (" . $columns . ") VALUES (" . $values . ");";


            //Unit History Data
            $columns = "uid,unitno,customerno,vehicleid
                        ,analog1,analog2,analog3,analog4
                        ,digitalio,lastupdated
                        ,commandkey,commandkeyval";
            $values = "'" . $objDeviceData->unitid . "' ,'" . $objDeviceData->unitno . "' ,'" . $objDeviceData->customerno . "' ,'" . $objDeviceData->vehicleid
                        . "' ,'" . $objDeviceData->analog1 . "' ,'" . $objDeviceData->analog2 . "' ,'" . $objDeviceData->analog3 . "' ,'" . $objDeviceData->analog4
                        . "' ,'" . $objDeviceData->digitalio . "' ,'" . $objDeviceData->timestamp
                        . "' ,'" . $objDeviceData->commandkey . "' ,'" . $objDeviceData->commandkeyval . "'"
                        ;

            $unitQuery = "INSERT INTO unithistory (" . $columns . ") VALUES (" . $values . ");";

            $db->exec("BEGIN IMMEDIATE TRANSACTION");
            $db->exec($deviceQuery);
            $db->exec($vehicleQuery);
            $db->exec($unitQuery);
            $db->exec("COMMIT TRANSACTION");
        }

        $this->ClosePDOConn($db);
    }

    public function GetRawData() {
        $db = $this->CreatePDOConn();
        $rawDataQuery = "SELECT     dataid, protocol_no, data, client, insertedDate
                         FROM       data
                         ORDER BY   insertedDate ASC";
        $result = $db->query($rawDataQuery);
        $arrRawData = array();
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $objData = new stdClass();
                $objData->dataid = $row["dataid"];
                $objData->protocol_no = $row["protocol_no"];
                $objData->data = $row["data"];
                $objData->client = $row["client"];
                $objData->insertedDate = isset($row["insertedDate"]) ? $row["insertedDate"] : "";
                $arrRawData[] = $objData;
            }
        }
        $this->ClosePDOConn($db);
        return $arrRawData;
    }

    function clean($str) {
        $search = array('&', '"', "'", '<', '>');
        $replace = array('&amp;', '&quot;', '&#39;', '&lt;', '&gt;');
        $str = str_replace($search, $replace, $str);
        return $str;
    }
}
