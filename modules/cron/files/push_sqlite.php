<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
if (!defined('RELATIVE_PATH_DOTS')) {
    define("RELATIVE_PATH_DOTS", $RELATIVE_PATH_DOTS);
}
function GetSqlite($customerno, $file) {
    $path = "sqlite:" . RELATIVE_PATH_DOTS . "customer/$customerno/reports/$file.sqlite";
    $DB = new PDO($path);
    return $DB;
}

function ChkSqlite($customerno, $chkid, $status, $date, $vehicleid, $chktype = NULL) {
    $path = "sqlite:" . RELATIVE_PATH_DOTS . "customer/$customerno/reports/chkreport.sqlite";
    $db = new PDO($path);
    $chktype = isset($chktype) ? $chktype : 0;
    $Query[0] = "CREATE TABLE IF NOT EXISTS V$vehicleid (chkrepid INTEGER,chkid INTEGER,chktype INTEGER,status INTEGER, date DATETIME,PRIMARY KEY(chkrepid))";
    if (IsColumnExistInSqlite($path, 'V' . $vehicleid, 'chktype')) {
        $Query[1] = "INSERT into V$vehicleid (chkid,chktype,status,date) values ($chkid,$chktype,$status,'$date')";
    } else {
        $Query[1] = "INSERT into V$vehicleid (chkid,status,date) values ($chkid,$status,'$date')";
    }
    $db->exec('BEGIN IMMEDIATE TRANSACTION');
    $db->exec($Query[0]);
    $db->exec($Query[1]);
    $db->exec('COMMIT TRANSACTION');
}

function GetCHSqlite($customerno, $file) {
    //$file_db = new PDO('sqlite:../../customer/myDatabase.sqlite');$serverpath
    $path = "sqlite:../../customer/$customerno/history/$file.sqlite";
    if (!file_exists($path)) {
        $filename = '../../customer/' . $customerno . '/';
        if (!file_exists($filename)) {
            mkdir("../../customer/" . $customerno, 0777);
            $historyfolder = '../../customer/' . $customerno . '/history';
            if (!file_exists($historyfolder)) {
                mkdir("../../customer/" . $customerno . "/history", 0777);
                $DB = new PDO($path);
            }
        } else {
            $historyfolder = '../../customer/' . $customerno . '/history';
            if (!file_exists($historyfolder)) {
                mkdir("../../customer/" . $customerno . "/history", 0777);
                $DB = new PDO($path);
            } else {
                $DB = new PDO($path);
            }
        }
    } else {
        $DB = new PDO($path);
    }
    //$DB = new PDO($path);
    return $DB;
}

function GetLoginHistorySqlite($customerno, $file) {
    //$file_db = new PDO('sqlite:../../customer/myDatabase.sqlite');$serverpath
    $path = "sqlite:../../customer/$customerno/history/login/$file.sqlite";
    if (!file_exists($path)) {
        $filename = '../../customer/' . $customerno . '/';
        if (!file_exists($filename)) {
            mkdir("../../customer/" . $customerno, 0777);
            $historyfolder = '../../customer/' . $customerno . '/history';
            if (!file_exists($historyfolder)) {
                mkdir("../../customer/" . $customerno . "/history", 0777);
                $loginhistoryfolder = '../../customer/' . $customerno . '/history/login';
                if (!file_exists($loginhistoryfolder)) {
                    mkdir("../../customer/" . $customerno . "/history/login", 0777);
                    $DB = new PDO($path);
                }
            }
        } else {
            $historyfolder = '../../customer/' . $customerno . '/history';
            if (!file_exists($historyfolder)) {
                mkdir("../../customer/" . $customerno . "/history", 0777);
                $loginhistoryfolder = '../../customer/' . $customerno . '/history/login';
                if (!file_exists($loginhistoryfolder)) {
                    mkdir("../../customer/" . $customerno . "/history/login", 0777);
                    $DB = new PDO($path);
                }
            } else {
                $loginhistoryfolder = '../../customer/' . $customerno . '/history/login';
                if (!file_exists($loginhistoryfolder)) {
                    mkdir("../../customer/" . $customerno . "/history/login", 0777);
                    $DB = new PDO($path);
                } else {
                    $DB = new PDO($path);
                }
            }
        }
    } else {
        $DB = new PDO($path);
    }
    //$DB = new PDO($path);
    return $DB;
}

function clean($str) {
    $search = array('&', '"', "'", '<', '>');
    $replace = array('&amp;', '&quot;', '&#39;', '&lt;', '&gt;');
    $str = str_replace($search, $replace, $str);
    return $str;
}

function CHSqlite($cqhid, $cqid, $customerno, $userid, $type, $enh_checkpointid, $timesent, $dateTime) {
//    $date = new Date();
    //    $today = $date->add_hours(date("Y-m-d H:i:s"), 11.5);
    //date_default_timezone_set("Asia/Calcutta");
    //$dt = strtotime(date("Y-m-d H:i:s", strtotime("-1 days", strtotime($dateTime))));
    $dt = strtotime(date("Y-m-d H:i:s", strtotime($dateTime)));
    $date = date("MY", $dt);
    $db = GetCHSqlite($customerno, $date);
    $Query[0] = "CREATE TABLE IF NOT EXISTS comhistory (id INTEGER,cqhid INTEGER,comqid INTEGER,customerno INTEGER,userid INTEGER,comtype INTEGER,enh_checkpointid INTEGER, timesent DATETIME,PRIMARY KEY(id))";
    $Query[1] = sprintf("INSERT INTO comhistory
                        (cqhid,comqid,customerno,userid,comtype,enh_checkpointid,timesent) VALUES
                        ( '%d','%d','%d','%d','%d','%d','%s')", Sanitise::Long($cqhid), Sanitise::Long($cqid), Sanitise::Long($customerno), Sanitise::Long($userid), Sanitise::Long($type), Sanitise::Long($enh_checkpointid), Sanitise::DateTime($timesent));
//    $db->exec('BEGIN IMMEDIATE TRANSACTION');
    //    $db->exec($Query[0]);
    //    $db->exec($Query[1]);
    //    $db->exec('COMMIT TRANSACTION');
    try {
        $db->exec('BEGIN IMMEDIATE TRANSACTION');
        $db->exec($Query[0]);
        $Success = $db->exec($Query[1]);
        $db->exec('COMMIT TRANSACTION');
        if (!$Success) {
            //print_r($db->errorInfo());
            return $db->errorInfo();
        }
    } catch (PDOException $e) {
        echo ($e);
    }
    //print_r($db->errorInfo());
    //print $db->errorCode();
    //echo '<br>';
    //return $db->errorCode();
    //die(print_r($db->errorInfo(), true));
}

function Loginsqlite($logHistoryId, $page_master_id, $type, $customerno, $created_on, $created_by, $dateTime) {
//    $date = new Date();
    //    $today = $date->add_hours(date("Y-m-d H:i:s"), 11.5);
    //date_default_timezone_set("Asia/Calcutta");
    //$dt = strtotime(date("Y-m-d H:i:s", strtotime("-1 days", strtotime($dateTime))));
    $dt = strtotime(date("Y-m-d H:i:s", strtotime($dateTime)));
    $date = date("MY", $dt);
    $db = GetLoginHistorySqlite($customerno, $date);
    $Query[0] = "CREATE TABLE IF NOT EXISTS loginhistory (id INTEGER,logHistoryId INTEGER,page_master_id INTEGER,type INTEGER,customerno INTEGER,created_on DATETIME,created_by INTEGER,PRIMARY KEY(id))";
    $Query[1] = sprintf("INSERT INTO loginhistory
                        (logHistoryId,page_master_id,type,customerno,created_on, created_by) VALUES
                        ( '%d','%d','%d','%d','%s','%d')", Sanitise::Long($logHistoryId), Sanitise::Long($page_master_id), Sanitise::Long($type), Sanitise::Long($customerno), Sanitise::DateTime($created_on), Sanitise::Long($created_by));
    try {
        $db->exec('BEGIN IMMEDIATE TRANSACTION');
        $db->exec($Query[0]);
        $Success = $db->exec($Query[1]);
        $db->exec('COMMIT TRANSACTION');
        if (!$Success) {
            //print_r($db->errorInfo());
            return $db->errorInfo();
        }
    } catch (PDOException $e) {
        echo ($e);
    }
}

function ComQueueSqlite($customerno, $cqid, $vehicleid, $lat, $long, $type, $timeadded, $status, $message, $processed, $is_shown, $chkid, $fenceid, $dateTime) {
    //    $date = new Date();
    //    $today = $date->add_hours(date("Y-m-d H:i:s"), 11.5);
    //date_default_timezone_set("Asia/Calcutta");
    //$dt = strtotime(date("Y-m-d H:i:s", strtotime("-1 days", strtotime($dateTime))));
    $dt = strtotime(date("Y-m-d H:i:s", strtotime($dateTime)));
    $date = date("MY", $dt);
    $db = GetCHSqlite($customerno, $date);
    $msg = clean($message);
    $Query[0] = "CREATE TABLE IF NOT EXISTS comqueue (id INTEGER,cqid INTEGER,vehicleid INTEGER,lat varchar(50),long varchar(50),type INTEGER,timeadded DATETIME, status INTEGER,message TEXT,processed INTEGER,is_shown INTEGER,chkid INTEGER,fenceid INTEGER,PRIMARY KEY(id))";
    $Query[1] = sprintf("INSERT INTO comqueue
                        (cqid,vehicleid,lat,long,type,timeadded, status, message,processed,is_shown,chkid,fenceid) VALUES
                        ( '%d','%d','%s','%s','%d','%s','%d','%s','%d','%d','%d','%d')", Sanitise::Long($cqid), Sanitise::Long($vehicleid), Sanitise::String($lat), Sanitise::String($long), Sanitise::Long($type), Sanitise::DateTime($timeadded), Sanitise::Long($status), Sanitise::String($msg), Sanitise::Long($processed), Sanitise::Long($is_shown), Sanitise::Long($chkid), Sanitise::Long($fenceid));
    try {
        $db->exec('BEGIN IMMEDIATE TRANSACTION');
        $db->exec($Query[0]);
        $Success = $db->exec($Query[1]);
        $db->exec('COMMIT TRANSACTION');
        if (!$Success) {
            //print_r($db->errorInfo());
            return $db->errorInfo();
        }
    } catch (PDOException $e) {
        echo ($e);
    }
}

function SimdataSqlite($customerno, $id, $type, $phoneno, $message, $requesttime, $client, $lat, $long, $system_msg, $vehicleid, $success, $is_processed, $dateTime) {
//    $date = new Date();
    //    $today = $date->add_hours(date("Y-m-d H:i:s"), 11.5);
    //date_default_timezone_set("Asia/Calcutta");
    //$dt = strtotime(date("Y-m-d H:i:s", strtotime("-1 days",strtotime($dateTime))));
    $dt = strtotime(date("Y-m-d H:i:s", strtotime($dateTime)));
    $date = date("MY", $dt);
    $db = GetCHSqlite($customerno, $date);
    $msg = clean($message);
    $Query[0] = "CREATE TABLE IF NOT EXISTS simdata (id INTEGER,sdid INTEGER,type INTEGER,phoneno varchar(50),message varchar(50),requesttime DATETIME,client varchar(60),lat varchar(50),long varchar(50),system_msg TEXT,vehicleid INTEGER,success INTEGER,is_processed INTEGER,PRIMARY KEY(id))";
    $Query[1] = sprintf("INSERT INTO simdata
                        (sdid,type,phoneno,message,requesttime,client,lat,long,system_msg,vehicleid,success, is_processed) VALUES
                        ( '%d','%d','%s','%s','%s','%s','%s','%s','%s','%d','%d','%d')", Sanitise::Long($id), Sanitise::Long($type), Sanitise::String($phoneno), Sanitise::String($message), Sanitise::DateTime($requesttime), Sanitise::String($client), Sanitise::String($lat), Sanitise::String($long), Sanitise::String($system_msg), Sanitise::Long($vehicleid), Sanitise::Long($success), Sanitise::Long($is_processed));
    try {
        $db->exec('BEGIN IMMEDIATE TRANSACTION');
        $db->exec($Query[0]);
        $Success = $db->exec($Query[1]);
        $db->exec('COMMIT TRANSACTION');
        if (!$Success) {
            //print_r($db->errorInfo());
            return $db->errorInfo();
        }
    } catch (PDOException $e) {
        echo ($e);
    }
}

function vehicleChkSqlite($customerno, $chkid, $status, $date, $vehicleid, $chktype = NULL) {
    $path = "sqlite:" . RELATIVE_PATH_DOTS . "customer/$customerno/reports/vehiclechkreport.sqlite";
    $db = new PDO($path);
    $chktype = isset($chktype) ? $chktype : 0;
    $Query[0] = "CREATE TABLE IF NOT EXISTS C$chkid (chkrepid INTEGER,vehicleid INTEGER,chktype INTEGER,status INTEGER, date DATETIME,PRIMARY KEY(chkrepid))";
    if (IsColumnExistInSqlite($path, 'C' . $chkid, 'chktype')) {
        $Query[1] = "INSERT into C$chkid (vehicleid,chktype,status,date) values ($vehicleid,$chktype,$status,'$date')";
    } else {
        $Query[1] = "INSERT into C$chkid (vehicleid,status,date) values ($vehicleid,$status,'$date')";
    }
    $db->exec('BEGIN IMMEDIATE TRANSACTION');
    $db->exec($Query[0]);
    $db->exec($Query[1]);
    $db->exec('COMMIT TRANSACTION');
}

function createComqueueTable($db) {
    //$db = GetCHSqlite($customerno, $date);
    $Query = "CREATE TABLE IF NOT EXISTS comqueue (id INTEGER,cqid INTEGER,vehicleid INTEGER,lat varchar(50),long varchar(50),type INTEGER,timeadded DATETIME, status INTEGER,message TEXT,processed INTEGER,is_shown INTEGER,chkid INTEGER,fenceid INTEGER,PRIMARY KEY(id))";
    try {
        $db->exec('BEGIN IMMEDIATE TRANSACTION');
        $Success = $db->exec($Query);
        $db->exec('COMMIT TRANSACTION');
        if (!$Success) {
            //print_r($db->errorInfo());
            return $db->errorInfo();
        }
    } catch (PDOException $e) {
        echo ($e);
    }
}

function insertComqueueInSqlite($db, $objData) {
    //print_r($objData);
    $msg = clean($objData->message);
    $Query = sprintf("INSERT INTO comqueue
                        (cqid,vehicleid,lat,long,type,timeadded, status, message,processed,is_shown,chkid,fenceid) VALUES
                        ( '%d','%d','%s','%s','%d','%s','%d','%s','%d','%d','%d','%d')", Sanitise::Long($objData->cqid), Sanitise::Long($objData->vehicleid), Sanitise::String($objData->devlat), Sanitise::String($objData->devlong), Sanitise::Long($objData->type), Sanitise::DateTime($objData->timeadded), Sanitise::Long($objData->status), Sanitise::String($msg), Sanitise::Long($objData->processed), Sanitise::Long($objData->is_shown), Sanitise::Long($objData->chkid), Sanitise::Long($objData->fenceid));
    try {
        $db->exec('BEGIN IMMEDIATE TRANSACTION');
        $Success = $db->exec($Query);
        $db->exec('COMMIT TRANSACTION');
        if (!$Success) {
            //print_r($db->errorInfo());
            return $db->errorInfo();
        }
    } catch (PDOException $e) {
        echo ($e);
    }
}

?>