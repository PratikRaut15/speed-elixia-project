<?php
if (!isset($RELATIVE_PATH_DOTS) || $RELATIVE_PATH_DOTS == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
class ComQueueManager {
    private $_databaseManager = null;
    private $_customerManager = null;
    public function __construct() {
        // Constructor.
        if ($this->_databaseManager == null) {
            $this->_databaseManager = new DatabaseManager();
        }
    }

    public function InsertQ($queue) {
        $today = date("Y-m-d H:i:s");
        $queue->tempSensor = isset($queue->tempSensor) ? $queue->tempSensor : 0;
        $queue->userid = isset($queue->userid) ? $queue->userid : 0;
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sp_params = "'" . $queue->vehicleid . "'"
        . ",'" . $queue->lat . "'"
        . ",'" . $queue->long . "'"
        . ",'" . $queue->type . "'"
        . ",'" . $queue->status . "'"
        . ",'" . $queue->message . "'"
        . ",'" . $queue->tempSensor . "'"
        . ",'" . $queue->userid . "'"
        . ",'" . $queue->customerno . "'"
            . ",'" . $today . "'";
        $queryCallSP = $this->PrepareSP(speedConstants::SP_INSERTQ, $sp_params);
        $pdo->query($queryCallSP);
        $this->_databaseManager->ClosePDOConn($pdo);
    }

    public function InsertQ_ign($queue) {
        $SQL = sprintf("INSERT INTO " . DB_PARENT . ".comqueue
                        (`customerno`,`vehicleid`,`devlat`,`devlong`,`type`,`status`,`message`,`timeadded`) VALUES
                        ( '%d','%d','%f','%f','%d','%d','%s','%s')", Sanitise::Long($queue->customerno), Sanitise::Long($queue->vehicleid), Sanitise::Float($queue->lat), Sanitise::Float($queue->long), Sanitise::Long($queue->type), Sanitise::Long($queue->status), Sanitise::String($queue->message), Sanitise::DateTime($queue->timeadded));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getcomqueuedata() {
        $time = date("Y-m-d H:i:s");
        //$time = date("2019-08-04 17:57:52");
        $queue = array();
        $queueQuery = sprintf(" SELECT  cq.cqid
                                        ,cq.customerno
                                        ,cq.vehicleid
                                        ,cq.devlat
                                        ,cq.devlong
                                        ,cq.type
                                        ,cq.timeadded
                                        ,cq.status
                                        ,cq.message
                                        ,cq.processed
                                        ,cq.is_shown
                                        ,cq.chkid
                                        ,cq.fenceid
                                        ,cq.tempsensor
                                        ,cq.userid
                                        ,c.use_trip
                                        ,c.use_geolocation
                                        ,vehicle.vehicleno
                                        ,vehicle.kind as deviceKind
                                        ,IF(vehicle.kind='Warehouse','Warehouse Name','Vehicle No') AS kind
                                        ,unit.n1
                                        ,unit.n2
                                        ,unit.n3
                                        ,unit.n4
                                        ,unit.uid
                                        ,unit.unitno
                                        ,c.timezone
                                        ,tz.timediff
                                        ,DATE_ADD('" . $time . "', INTERVAL tz.timediff SECOND) as ctTimeZoneTimestamp
                                FROM " . DB_PARENT . ".`comqueue` AS cq
                                INNER JOIN " . DB_PARENT . ".customer AS c ON c.customerno = cq.customerno
                                INNER JOIN timezone tz on tz.tid = c.timezone
                                INNER JOIN vehicle on vehicle.vehicleid = cq.vehicleid and vehicle.isdeleted = 0
                                INNER JOIN unit on vehicle.uid = unit.uid
                                WHERE   cq.`processed` = 0
                                AND     vehicle.isdeleted=0
                                AND     unit.trans_statusid NOT IN (10,22)
                                AND     cq.customerno NOT IN (1)
                                AND     cq.`isQueued` = 0
                                AND     cq.`timeadded` >= DATE_ADD(DATE_ADD('" . $time . "', INTERVAL tz.timediff SECOND), INTERVAL -180 SECOND) order by cq.cqid ASC "); //die();
        $this->_databaseManager->executeQuery($queueQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $singlequeue = new stdClass();
                $singlequeue->cqid = $row['cqid'];
                $singlequeue->customerno = $row['customerno'];
                $singlequeue->vehicleid = $row['vehicleid'];
                $singlequeue->lat = $row['devlat'];
                $singlequeue->long = $row['devlong'];
                $singlequeue->type = $row['type'];
                $singlequeue->timeadded = $row['timeadded'];
                $singlequeue->status = $row['status'];
                $singlequeue->message = $row['message'];
                $singlequeue->processed = $row['processed'];
                $singlequeue->is_shown = $row['is_shown'];
                $singlequeue->chkid = $row['chkid'];
                $singlequeue->fenceid = $row['fenceid'];
                $singlequeue->tempsensor = $row['tempsensor'];
                $singlequeue->userid = $row['userid'];
                $singlequeue->vehicleno = isset($row['vehicleno']) ? $row['vehicleno'] : '';
                $singlequeue->kind = isset($row['kind']) ? $row['kind'] : '';
                $singlequeue->use_geolocation = $row['use_geolocation'];
                $singlequeue->use_trip = $row['use_trip'];
                $singlequeue->n1 = $row['n1'];
                $singlequeue->n2 = $row['n2'];
                $singlequeue->n3 = $row['n3'];
                $singlequeue->n4 = $row['n4'];
                $singlequeue->uid = $row['uid'];
                $singlequeue->unitno = $row['unitno'];
                $singlequeue->deviceKind = $row['deviceKind'];
                $singlequeue->ctTimeZoneTimestamp = $row['ctTimeZoneTimestamp'];
                $queue[] = $singlequeue;
            }
            return $queue;
        }
        return null;
    }

    public function getcomqueuedatafor_vehicle($vehicleid) {
        $data = array();
        $query = "SELECT * from " . DB_PARENT . ".comqueue where vehicleid=" . $vehicleid . " AND timeadded BETWEEN '" . date('Y-m-d') . " 00:00:00' AND '" . date('Y-m-d') . " 23:59:59' ORDER BY timeadded ASC";
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                // for check point
                if ($row['type'] == 2 && $row['status'] == 1) {
                    $temp = array();
                    $temp['timestamp'] = $row['timeadded'];
                    $temp['location'] = $row['message'];
                    $data['check'][] = $temp;
                }
                // for overspeed
                if ($row['type'] == 5 && $row['status'] == 0) {
                    $temp['timestamp'] = $row['timeadded'];
                    $temp['location'] = get_location_detail($row['devlat'], $row['devlong'], $_SESSION['customerno']);
                    $data['oversped'][] = $temp;
                }
            }
        }
        return $data;
    }

    public function get_enh_checkpoints($checkpointid, $vehicleid, $customerno) {
        $checkpoints = array();
//        $Query = "SELECT *,enh_checkpoint.checkpointid,enh_checkpoint.vehicleid FROM `enh_checkpoint`
        //            LEFT OUTER JOIN checkpoint ON checkpoint.checkpointid = enh_checkpoint.checkpointid
        //            LEFT OUTER JOIN vehicle ON vehicle.vehicleid = enh_checkpoint.vehicleid
        //            WHERE checkpoint.isdeleted=0 AND enh_checkpoint.isdeleted=0 GROUP BY enh_checkpoint.checkpointid, enh_checkpoint.vehicleid";
        $Query = "SELECT * FROM `enh_checkpoint` WHERE checkpointid =%d AND vehicleid =%d AND customerno=%d AND isdeleted=0";
        $checkpointsQuery = sprintf($Query, Sanitise::Long($checkpointid), Sanitise::Long($vehicleid), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($checkpointsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new stdClass();
                $checkpoint->enh_checkpointid = $row['enh_checkpoint'];
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->vehicleid = $row['vehicleid'];
                $checkpoint->com_det = $row['com_details'];
                $checkpoint->com_type = $row['com_type'];
                //$checkpoint->com = $row['checkpointid'].'_'.$row['vehicleid'];
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;
        }
        return null;
    }

    public function InsertQChk($queue) {
        $queue->today = isset($queue->today) ? $queue->today : date(speedConstants::DEFAULT_TIMESTAMP);
        //$today = date("Y-m-d H:i:s");
        $SQL = sprintf("INSERT INTO " . DB_PARENT . ".comqueue
                        (`customerno`,`vehicleid`,`devlat`,`devlong`,`type`,`status`,`message`,`chkid`,`timeadded`,`userid`) VALUES
                        ( '%d','%d','%f','%f','%d','%d','%s','%d','%s','%d')", Sanitise::Long($queue->customerno), Sanitise::Long($queue->vehicleid), Sanitise::Float($queue->lat), Sanitise::Float($queue->long), Sanitise::Long($queue->type), Sanitise::Long($queue->status), Sanitise::String($queue->message), Sanitise::Long($queue->chkid), Sanitise::DateTime($queue->today), Sanitise::Long($queue->userid));
        $this->_databaseManager->executeQuery($SQL);
    }

// Following function is used for bulk inert into comqueue table
    public function pushBulkDataInComqueue(string $query) {
        $this->_databaseManager->executeQuery($query);
    }

    public function InsertQFence($queue) {
        $today = date("Y-m-d H:i:s");
        $SQL = sprintf("INSERT INTO " . DB_PARENT . ".comqueue
                        (`customerno`,`vehicleid`,`devlat`,`devlong`,`type`,`status`,`message`,`fenceid`,`timeadded`) VALUES
                        ( '%d','%d','%f','%f','%d','%d','%s','%d','%s')", Sanitise::Long($queue->customerno), Sanitise::Long($queue->vehicleid), Sanitise::Float($queue->lat), Sanitise::Float($queue->long), Sanitise::Long($queue->type), Sanitise::Long($queue->status), Sanitise::String($queue->message), Sanitise::Long($queue->fenceid), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function Insert($type, $email, $phone = Null, $subject, $message, $customerno = null) {
        $today = date("Y-m-d H:i:s");
        $SQL = sprintf("INSERT INTO " . DB_PARENT . ".communicationqueue
                        (`type`,`email`,`phone`,`subject`,`message`,`datecreated`,`customerno`) VALUES
                        ( '%d','%s','%s','%s','%s','%s','%d')", Sanitise::Long($type), Sanitise::String($email), Sanitise::String($phone), Sanitise::String($subject), Sanitise::String($message), Sanitise::DateTime($today), Sanitise::Long($customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function InsertHistory($queue) {
        $today = date("Y-m-d H:i:s");
        $SQL = sprintf("INSERT INTO " . DB_PARENT . ".communicationhistory
                        (`type`,`email`,`phone`,`subject`,`message`,`datesent`,`confirmation`,`sent_error`,`queueid`,`datecreated`,`customerno`) VALUES
                        ( '%d','%s','%s','%s','%s','%s','%d','%d','%d','%s','%d')", Sanitise::Long($queue->type), Sanitise::String($queue->email), Sanitise::String($queue->phone), Sanitise::String($queue->subject), Sanitise::String($queue->message), Sanitise::DateTime($today), Sanitise::Long($queue->confirmation), Sanitise::Long($queue->senderror), Sanitise::Long($queue->queueid), Sanitise::DateTime($queue->datecreated), Sanitise::Long($queue->customerno));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function InsertComHistory($queue) {
        $today = date("Y-m-d H:i:s");
        $SQL = sprintf("INSERT INTO " . DB_PARENT . ".comhistory
                        (`comqid`,`customerno`,`userid`,`comtype`,`enh_checkpointid`,`timesent`) VALUES
                        ( %d,%d,%d,%d,%d,'%s')", Sanitise::Long($queue->cqid), Sanitise::Long($queue->customerno), Sanitise::Long($queue->userid), Sanitise::Long($queue->type), Sanitise::Long($queue->enh_checkpointid), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getqueue() {
        $queue = array();
        $queueQuery = sprintf("SELECT * FROM " . DB_PARENT . ".`communicationqueue`");
        $this->_databaseManager->executeQuery($queueQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $singlequeue = new VOCommunicationQueue();
                $singlequeue->id = $row['id'];
                $singlequeue->type = $row['type'];
                $singlequeue->email = $row['email'];
                $singlequeue->phone = $row['phone'];
                $singlequeue->subject = $row['subject'];
                $singlequeue->message = $row['message'];
                $singlequeue->datecreated = $row['datecreated'];
                $singlequeue->customerno = $row['customerno'];
                $queue[] = $singlequeue;
            }
            return $queue;
        }
        return null;
    }

    public function pullexpirydate($vehicleid) {
        $queueQuery = sprintf("SELECT devices.expirydate, Now() as today FROM `devices` INNER JOIN unit ON unit.uid = devices.uid WHERE unit.vehicleid = %d", Sanitise::Long($vehicleid));
        $this->_databaseManager->executeQuery($queueQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $device = new VODevices();
                $device->today = $row["today"];
                $device->expirydate = $row["expirydate"];
            }
            return $device;
        }
        return null;
    }

    public function getalerthist($date, $type, $vehicleid, $checkpointid, $fenceid, $customerno, $groupid = null) {
        //echo $date.'_'.$type.'_'.$vehicleid.'_'.$checkpointid.'_'.$fenceid.'_'.$customerno;
        if (isset($groupid)) {
            $_SESSION['groupid'] = $groupid;
        }
        $queue = array();
        $newdate = date('Y-m-d', strtotime($date));
        //$dt = strtotime(date("Y-m-d H:i:s"));
        $dateCondition = ' DATE_FORMAT(comqueue.timeadded, "%Y-%m-%d") = "' . $newdate . '" ';
        switch ($type) {
            case '-1':
                {
                    if ($vehicleid != '') {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.vehicleid= $vehicleid AND " . $dateCondition . " ";
                    } else {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND " . $dateCondition . " ";
                    }
                }
                break;
            case '2':
                {
                    if ($vehicleid != '' && $checkpointid != '') {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.vehicleid= " . $vehicleid . " AND comqueue.type= " . $type . " AND comqueue.chkid=" . $checkpointid . " AND " . $dateCondition . " ";
                    } elseif ($vehicleid == '' && $checkpointid != '') {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.type= " . $type . " AND comqueue.chkid=" . $checkpointid . " AND " . $dateCondition . " ";
                    } elseif ($vehicleid != '' && $checkpointid == '') {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.vehicleid= " . $vehicleid . " AND comqueue.type= " . $type . " AND " . $dateCondition . " ";
                    } else {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.type= " . $type . " AND " . $dateCondition . " ";
                    }
                }
                break;
            case '3':
                {
                    if ($vehicleid != '' && $fenceid != '') {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.vehicleid= " . $vehicleid . " AND comqueue.type= " . $type . " AND comqueue.fenceid=" . $fenceid . " AND " . $dateCondition . " ";
                    } elseif ($vehicleid == '' && $fenceid != '') {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.type= " . $type . " AND comqueue.fenceid=" . $fenceid . " AND " . $dateCondition . " ";
                    } elseif ($vehicleid != '' && $fenceid == '') {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.vehicleid= " . $vehicleid . " AND comqueue.type= " . $type . " AND " . $dateCondition . " ";
                    } else {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.type= " . $type . " AND " . $dateCondition . " ";
                    }
                }
                break;
            default:{
                    if ($vehicleid != '') {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.vehicleid= " . $vehicleid . " AND comqueue.type= " . $type . " AND " . $dateCondition . " ";
                    } else {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.type= " . $type . " AND " . $dateCondition . " ";
                    }
                }
                break;
        }
        if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
            $queueQuery .= " AND vehicle.groupid ='" . $_SESSION['groupid'] . "'";
        }
        $queueQuery .= " ORDER BY  comqueue.timeadded ASC ";
        //echo $queueQuery;
        $this->_databaseManager->executeQuery($queueQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $singlequeue = new stdClass();
                $singlequeue->type = $row['type'];
                $singlequeue->timeadded = convertDateToFormat($row["timeadded"], speedConstants::DEFAULT_TIME);
                $singlequeue->message = $row['message'];
                $singlequeue->processed = $row['processed'];
                $singlequeue->comtype = $row['comtype'];
                $singlequeue->email = $row['email'];
                $singlequeue->phone = $row['phone'];
                $queue[] = $singlequeue;
            }
            return $queue;
        }
        return null;
    }

    public function getsimdata($customerno, $dateTime = null) {
        $queue = array();
        $dt = strtotime(date("Y-m-d H:i:s"));
        $date = '%' . date("Y-m-d", $dt) . '%';
        $isDateCondition = '';
        if (isset($dateTime)) {
            $isDateCondition = " AND DATE(`requesttime`) = '" . $dateTime . "'";
        }
        $queueQuery = sprintf("SELECT * FROM `simdata` WHERE `customerno` = %d AND `requesttime` NOT LIKE '%s' " . $isDateCondition . " ", Sanitise::Long($customerno), Sanitise::String($date));
        $this->_databaseManager->executeQuery($queueQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $singlequeue = new stdClass();
                $singlequeue->id = $row['id'];
                $singlequeue->type = $row['type'];
                $singlequeue->phoneno = $row['phoneno'];
                $singlequeue->message = $row['message'];
                $singlequeue->requesttime = $row['requesttime'];
                $singlequeue->client = $row['client'];
                $singlequeue->lat = $row['lat'];
                $singlequeue->long = $row['long'];
                $singlequeue->system_msg = $row['system_msg'];
                $singlequeue->customerno = $row['customerno'];
                $singlequeue->vehicleid = $row['vehicleid'];
                $singlequeue->success = $row['success'];
                $singlequeue->is_processed = $row['is_processed'];
                $queue[] = $singlequeue;
            }
            return $queue;
        }
        return null;
    }

    public function getcomhist($cqid) {
        $queue = array();
        $queueQuery = sprintf("SELECT comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comhistory` INNER JOIN `user` WHERE user.userid = comhistory.userid AND comhistory.comqid = %d", Sanitise::Long($cqid));
        $this->_databaseManager->executeQuery($queueQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $singlequeue = new stdClass();
//                $singlequeue->cqhid = $row['cqhid'];
                //                $singlequeue->comqid = $row['comqid'];
                //                $singlequeue->customerno = $row['customerno'];
                //                $singlequeue->userid = $row['userid'];
                //                $singlequeue->timesent = $row['timesent'];
                $singlequeue->comtype = $row['comtype'];
                $singlequeue->email = $row['email'];
                $singlequeue->phone = $row['phone'];
                $queue[] = $singlequeue;
            }
            return $queue;
        }
        return null;
    }

    public function getuserdetailss($userid) {
        $queueQuery = sprintf("SELECT email,phone FROM `user` WHERE userid = %d", Sanitise::Long($userid));
        $this->_databaseManager->executeQuery($queueQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $singlequeue = new stdClass();
                $singlequeue->email = $row['email'];
                $singlequeue->phone = $row['phone'];
                $queue = $singlequeue;
            }
            return $queue;
        }
        return null;
    }

    public function DeleteSimdata($customerno, $dateTime = null) {
        $dt = strtotime(date("Y-m-d H:i:s"));
        $date = '%' . date("Y-m-d", $dt) . '%';
        $isDateCondition = '';
        if (isset($dateTime)) {
            $isDateCondition = " AND DATE(`requesttime`) = '" . $dateTime . "'";
        }
        $queueQuery = sprintf("DELETE FROM `simdata` WHERE `customerno` = %d AND `requesttime` NOT LIKE '%s' " . $isDateCondition . " ", Sanitise::Long($customerno), Sanitise::String($date));
        $this->_databaseManager->executeQuery($queueQuery);
    }

    public function getnotifs($customerno, $type) {
        $queue = array();
        $queueQuery = sprintf("SELECT * FROM " . DB_PARENT . ".`notifications` WHERE customerno = %d AND isnotified = 0 AND type = %d", Sanitise::Long($customerno), Sanitise::Long($type));
        $this->_databaseManager->executeQuery($queueQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $singlequeue = new VOCommunicationQueue();
                $singlequeue->notif = $row['notification'];
                $queue[] = $singlequeue;
            }
            return $queue;
        }
        return null;
    }

    public function getalerts($customerno) {
        $today = date("Y-m-d H:i:s");
        $time = date("Y-m-d H:i:s", strtotime("-60 seconds"));
        $queue = array();
        $queueQuery = sprintf("SELECT * FROM " . DB_PARENT . ".`comqueue` WHERE customerno = %d AND `is_shown` = '0' AND `timeadded` BETWEEN '%s' and '%s'", Sanitise::Long($customerno), Sanitise::DateTime($time), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($queueQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $singlequeue = new VOCommunicationQueue();
                $singlequeue->notif = $row['message'];
                $queue[] = $singlequeue;
            }
            return $queue;
        }
        return null;
    }

    public function getnews($notid) {
        $queueQuery = sprintf("SELECT notification FROM " . DB_PARENT . ".`notifications` WHERE notid = %d", Sanitise::Long($notid));
        $this->_databaseManager->executeQuery($queueQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $singlequeue = new VOCommunicationQueue();
                $singlequeue->notif = $row['notification'];
            }
            return $singlequeue;
        }
        return null;
    }

    public function getcompany($id) {
        $queueQuery = sprintf("SELECT customercompany FROM " . DB_PARENT . ".`customer` WHERE customerno = %d AND customerno <> 1", Sanitise::Long($id));
        $this->_databaseManager->executeQuery($queueQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $singlequeue = new VOCommunicationQueue();
                $singlequeue->company = $row['customercompany'];
            }
            return $singlequeue;
        }
        return null;
    }

    public function getrandnews() {
        $queue = array();
        $queueQuery = sprintf("SELECT notid FROM " . DB_PARENT . ".`notifications` WHERE type = 1");
        $this->_databaseManager->executeQuery($queueQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $queue[] = $row["notid"];
            }
            return $queue;
        }
        return null;
    }

    public function getrandtips() {
        $queue = array();
        $queueQuery = sprintf("SELECT notid FROM " . DB_PARENT . ".`notifications` WHERE type = 2");
        $this->_databaseManager->executeQuery($queueQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $queue[] = $row["notid"];
            }
            return $queue;
        }
        return null;
    }

    public function getrandlinks() {
        $queue = array();
        $queueQuery = sprintf("SELECT notid FROM " . DB_PARENT . ".`notifications` WHERE type = 3");
        $this->_databaseManager->executeQuery($queueQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $queue[] = $row["notid"];
            }
            return $queue;
        }
        return null;
    }

    public function getrandcust() {
        $queue = array();
        $queueQuery = sprintf("SELECT customerno FROM " . DB_PARENT . ".`customer`");
        $this->_databaseManager->executeQuery($queueQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $queue[] = $row["customerno"];
            }
            return $queue;
        }
        return null;
    }

    public function setnotif($customerno, $type) {
        $SQL = sprintf("Update " . DB_PARENT . ".notifications Set isnotified = 1 WHERE customerno = %d AND type = %d", Sanitise::Long($customerno), Sanitise::Long($type));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function UpdateComQueue($queueid) {
        $SQL = sprintf("UPDATE " . DB_PARENT . ".`comqueue` SET `processed` = '1' WHERE `cqid` = %d", Sanitise::Long($queueid));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getalerthistleftdiv($vehicleid, $customerno) {
        $queue = array();
        $newdate = date('Y-m-d');
        $checkdate = '%' . $newdate . '%';
        if ($vehicleid != '') {
            $queueQuery = sprintf("SELECT comqueue.timeadded,comqueue.message FROM " . DB_PARENT . ".`comqueue`"
                . "WHERE comqueue.customerno = %d AND  comqueue.vehicleid= %d  AND  comqueue.timeadded LIKE '%s' ", Sanitise::Long($customerno), Sanitise::Long($vehicleid), Sanitise::String($checkdate));
        }
        $queueQuery .= "  ORDER BY  comqueue.timeadded DESC  limit 10 ";
        $this->_databaseManager->executeQuery($queueQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $singlequeue = new stdClass();
                $singlequeue->timeadded = convertDateToFormat($row["timeadded"], speedConstants::DEFAULT_TIME);
                $singlequeue->message = $row['message'];
                $queue[] = $singlequeue;
            }
            return $queue;
        }
        return null;
    }

    public function insertcronrecords($cron_record_count) {
        $today = date("Y-m-d H:i:s");
        $SQL = sprintf("INSERT INTO " . DB_PARENT . ".croncounts
                        (`recordscount`,`timesent`) VALUES
                        ( %d,'%s')", Sanitise::Long($cron_record_count), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function send_notification($registatoin_ids, $message, $customerno) {
        $this->send_notification_fcm($registatoin_ids, $message, $customerno);
        // $this->send_notification_gcm($registatoin_ids, $message, $customerno);
        return true;
    }

    public function send_notification_fcm($registatoin_ids, $message, $customerno) {
        // $this->insert_gcm_cron($registatoin_ids,$message,$customerno);
        // Set POST variables
        //$url = 'https://android.googleapis.com/gcm/send';
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message
        );
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY_FCM,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        return $result;
    }

    public function send_notification_gcm($registatoin_ids, $message, $customerno) {
        // $this->insert_gcm_cron($registatoin_ids,$message,$customerno);
        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
        //$url = 'https://fcm.googleapis.com/fcm/send';
        $message['message'] = "GCM - " . $message['message'];
        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message
        );
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY_GCM,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        return $result;
    }

    public function insert_gcm_cron($registatoin_ids, $message, $customerno) {
        $registatoin_ids = implode(",", $registatoin_ids);
        $message = implode($message);
        $today = date("Y-m-d H:i:s");
        $SQL = sprintf("INSERT INTO " . DB_PARENT . ".gcmcron
                        (`reg_gcm_id`,`message`,`customerno`,`entrytime`) VALUES
                        ('%s','%s',%d,'%s')", Sanitise::string($registatoin_ids), Sanitise::string($message), $customerno, Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function checkComQueExistance($objComQue) {
        $isExists = 0;
        $time = date("Y-m-d H:i:s", strtotime("-90 seconds"));
        $queue = array();
        $queueQuery = sprintf("SELECT comqueue.cqid FROM " . DB_PARENT . ".`comqueue`
        WHERE customerno  =  %d
        AND chkid = %d
        AND status = %d
        AND `timeadded` >= '%s'", Sanitise::Long($objComQue->customerno), Sanitise::Long($objComQue->chkid), Sanitise::Long($objComQue->status), Sanitise::DateTime($time));
        $this->_databaseManager->executeQuery($queueQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $isExists = 1;
        }
        return $isExists;
    }

    public function markedQueued($firstCqid, $lastCqid) {
        $Query = "Update comqueue Set `isQueued`= 1 WHERE cqid between %d AND %d AND processed = 0";
        $SQL = sprintf($Query, Sanitise::long($lastCqid), Sanitise::long($firstCqid));
        $this->_databaseManager->executeQuery($SQL);
    }

    public function GetHistory($customerno, $dateTime = null) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $queue = array();
        $queueData = array();
        $isDateCondition = '';
        if (isset($dateTime)) {
            $isDateCondition = " AND DATE(`timesent`) = '" . $dateTime . "'";
        }
        $dt = strtotime(date("Y-m-d H:i:s"));
        $date = date("Y-m-d", $dt);
        $queueQuery = sprintf("SELECT * FROM " . DB_PARENT . ".`comhistory` WHERE `customerno` = %d   AND `timesent` <> '%s' " . $isDateCondition . "", Sanitise::Long($customerno), Sanitise::String($date));
        $queueData = $pdo->query($queueQuery)->fetchAll(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        if (isset($queueData) && !empty($queueData)) {
            foreach ($queueData as $row) {
                $singlequeue = new stdClass();
                $singlequeue->cqhid = $row['cqhid'];
                $singlequeue->cqid = $row['comqid'];
                $singlequeue->customerno = $row['customerno'];
                $singlequeue->userid = $row['userid'];
                $singlequeue->type = $row['comtype'];
                $singlequeue->enh_checkpointid = $row['enh_checkpointid'];
                $singlequeue->timesent = $row['timesent'];
                $queue[] = $singlequeue;
            }
        }
        return $queue;
    }

    public function GetLoginHistory($customerno, $dateTime = null) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $queue = array();
        $queueData = array();
        $dt = strtotime(date("Y-m-d H:i:s"));
        $date = date("Y-m-d", $dt);
        $isDateCondition = '';
        if (isset($dateTime)) {
            $isDateCondition = " AND DATE(`created_on`) = '" . $dateTime . "'";
        }
        $queueQuery = sprintf("SELECT * FROM " . DB_PARENT . ".`login_history_details` WHERE `customerno` = %d  AND `created_on` <> '%s' " . $isDateCondition . " ", Sanitise::Long($customerno), Sanitise::String($date));
        $queueData = $pdo->query($queueQuery)->fetchAll(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        if (isset($queueData) && !empty($queueData)) {
            foreach ($queueData as $row) {
                $loghistory = new stdClass();
                $loghistory->logHistoryId = $row['logHistoryId'];
                $loghistory->page_master_id = $row['page_master_id'];
                $loghistory->type = $row['type'];
                $loghistory->customerno = $row['customerno'];
                $loghistory->created_on = $row['created_on'];
                $loghistory->created_by = $row['created_by'];
                $queue[] = $loghistory;
            }
        }
        return $queue;
    }

    public function getcomqueue($customerno, $dateTime = null) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $queue = array();
        $queueData = array();
        $dt = strtotime(date("Y-m-d H:i:s"));
        $date = date("Y-m-d", $dt);
        $isDateCondition = '';
        if (isset($dateTime)) {
            $isDateCondition = " AND DATE(`timeadded`) = '" . $dateTime . "'";
        }
        $queueQuery = sprintf("SELECT * FROM " . DB_PARENT . ".`comqueue` WHERE `customerno` = %d AND `timeadded` <> '%s' " . $isDateCondition . "", Sanitise::Long($customerno), Sanitise::String($date));
        $queueData = $pdo->query($queueQuery)->fetchAll(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        if (isset($queueData) && !empty($queueData)) {
            foreach ($queueData as $row) {
                $singlequeue = new stdClass();
                $singlequeue->cqid = $row['cqid'];
                $singlequeue->customerno = $row['customerno'];
                $singlequeue->vehicleid = $row['vehicleid'];
                $singlequeue->lat = $row['devlat'];
                $singlequeue->long = $row['devlong'];
                $singlequeue->type = $row['type'];
                $singlequeue->timeadded = $row['timeadded'];
                $singlequeue->status = $row['status'];
                $singlequeue->message = $row['message'];
                $singlequeue->processed = $row['processed'];
                $singlequeue->is_shown = $row['is_shown'];
                $singlequeue->chkid = $row['chkid'];
                $singlequeue->fenceid = $row['fenceid'];
                $queue[] = $singlequeue;
            }
        }
        return $queue;
    }

    public function DeleteHistory($customerno, $dateTime = null) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $dt = strtotime(date("Y-m-d H:i:s"));
        $date = date("Y-m-d", $dt);
        $isDateCondition = '';
        if (isset($dateTime)) {
            $isDateCondition = " AND DATE(`timesent`) = '" . $dateTime . "'";
        }
        $queueQuery = sprintf("DELETE FROM " . DB_PARENT . ".`comhistory` WHERE `customerno` = %d  AND `timesent` <> '%s' " . $isDateCondition . "", Sanitise::Long($customerno), Sanitise::String($date));
        $pdo->query($queueQuery);
        $this->_databaseManager->ClosePDOConn($pdo);
    }

    public function DeleteLoginHistory($customerno, $dateTime = null) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $dt = strtotime(date("Y-m-d H:i:s"));
        $date = date("Y-m-d", $dt);
        $isDateCondition = '';
        if (isset($dateTime)) {
            $isDateCondition = " AND DATE(`created_on`) = '" . $dateTime . "'";
        }
        $queueQuery = sprintf("DELETE FROM " . DB_PARENT . ".`login_history_details` WHERE `customerno` = %d  AND `created_on` <> '%s' " . $isDateCondition . "", Sanitise::Long($customerno), Sanitise::String($date));
        $pdo->query($queueQuery);
        $this->_databaseManager->ClosePDOConn($pdo);
    }

    public function DeleteComQueue($customerno, $dateTime = null) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $dt = strtotime(date("Y-m-d H:i:s"));
        $date = date("Y-m-d", $dt);
        $isDateCondition = '';
        if (isset($dateTime)) {
            $isDateCondition = " AND DATE(`timeadded`) = '" . $dateTime . "'";
        }
        $queueQuery = sprintf("DELETE FROM " . DB_PARENT . ".`comqueue` WHERE `customerno` = %d  AND DATE(`timeadded`) <> '%s' " . $isDateCondition . " ", Sanitise::Long($customerno), Sanitise::String($date));
        $pdo->query($queueQuery);
        $this->_databaseManager->ClosePDOConn($pdo);
    }

    public function getTemperatureAlertHist($request) {
        $outputResult = array();
        $pdo = $this->_databaseManager->CreatePDOConn();
        $sp_params = "'" . $request->startdate . "'"
        . ",'" . $request->enddate . "'"
        . ",'" . $request->customerno . "'"
        . ",'" . $request->vehicleid . "'"
        . ",'" . $request->type . "'"
        . ",'" . $request->interval . "'";
        $queryCallSP = $this->PrepareSP(speedConstants::SP_GET_TEMP_ALERT_HISTORY, $sp_params);
        $outputResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        return $outputResult;
    }

    public function PrepareSP($sp_name, $sp_params) {
        return "call " . $sp_name . "(" . $sp_params . ");";
    }

    public function getalerthistprev($date, $prevDate, $type, $vehicleid, $checkpointid, $fenceid, $customerno, $groupid = null) {
        //echo $date.'_'.$type.'_'.$vehicleid.'_'.$checkpointid.'_'.$fenceid.'_'.$customerno;
        if (isset($groupid)) {
            $_SESSION['groupid'] = $groupid;
        }
        $queue = array();
        $newdate = date('Y-m-d', strtotime($date));
        //$dt = strtotime(date("Y-m-d H:i:s"));
        $checkdate = '%' . $newdate . '%';
        $dateCondition = ' DATE_FORMAT(comqueue.timeadded, "%Y-%m-%d") between "' . $prevDate . '" AND "' . $newdate . '" ';
        switch ($type) {
            case '-1':
                {
                    if ($vehicleid != '') {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.vehicleid= $vehicleid AND " . $dateCondition . " ";
                    } else {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.timeadded LIKE " . $dateCondition . " ";
                    }
                }
                break;
            case '2':
                {
                    if ($vehicleid != '' && $checkpointid != '') {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.vehicleid= " . $vehicleid . " AND comqueue.type= " . $type . " AND comqueue.chkid=" . $checkpointid . " AND " . $dateCondition . " ";
                    } elseif ($vehicleid == '' && $checkpointid != '') {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.type= " . $type . " AND comqueue.chkid=" . $checkpointid . " AND " . $dateCondition . " ";
                    } elseif ($vehicleid != '' && $checkpointid == '') {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.vehicleid= " . $vehicleid . " AND comqueue.type= " . $type . " AND " . $dateCondition . " ";
                    } else {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.type= " . $type . " AND " . $dateCondition . " ";
                    }
                }
                break;
            case '3':
                {
                    if ($vehicleid != '' && $fenceid != '') {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.vehicleid= " . $vehicleid . " AND comqueue.type= " . $type . " AND comqueue.fenceid=" . $fenceid . " AND " . $dateCondition . " ";
                    } elseif ($vehicleid == '' && $fenceid != '') {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.type= " . $type . " AND comqueue.fenceid=" . $fenceid . " AND " . $dateCondition . " ";
                    } elseif ($vehicleid != '' && $fenceid == '') {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.vehicleid= " . $vehicleid . " AND comqueue.type= " . $type . " AND " . $dateCondition . " ";
                    } else {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.type= " . $type . " AND " . $dateCondition . " ";
                    }
                }
                break;
            default:{
                    if ($vehicleid != '') {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.vehicleid= " . $vehicleid . " AND comqueue.type= " . $type . " AND " . $dateCondition . " ";
                    } else {
                        $queueQuery = "SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone FROM " . DB_PARENT . ".`comqueue` LEFT OUTER JOIN " . DB_PARENT . ".comhistory on comqueue.cqid = comhistory.comqid
                    INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN user ON user.userid = comhistory.userid
                    WHERE comqueue.customerno = " . $customerno . " AND comqueue.type= " . $type . " AND " . $dateCondition . " ";
                    }
                }
                break;
        }
        if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
            $queueQuery .= " AND vehicle.groupid ='" . $_SESSION['groupid'] . "'";
        }
        $queueQuery .= " ORDER BY  comqueue.timeadded ASC ";
        //echo $queueQuery;
        $this->_databaseManager->executeQuery($queueQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $singlequeue = new stdClass();
                $singlequeue->type = $row['type'];
                $singlequeue->timeadded = convertDateToFormat($row["timeadded"], speedConstants::DEFAULT_TIME);
                $singlequeue->message = $row['message'];
                $singlequeue->processed = $row['processed'];
                $singlequeue->comtype = $row['comtype'];
                $singlequeue->email = $row['email'];
                $singlequeue->phone = $row['phone'];
                $queue[] = $singlequeue;
            }
            return $queue;
        }
        return null;
    }

    public function getComqueueHistory($dateTime, $customerno) {
        $pdo = $this->_databaseManager->CreatePDOConn();
        $queue = array();
        $queueData = array();
        $dt = strtotime(date("Y-m-d H:i:s"));
        $date = date("Y-m-d", $dt);
        $isDateCondition = '';
        $isCustomerCondition = '';
        if (isset($dateTime)) {
            $isDateCondition = " AND DATE(`timeadded`) = '" . $dateTime . "'";
        }
        if ($customerno && isset($customerNo)) {
            $isCustomerCondition = " AND customerno = '" . $customerno . "'";
        }
        $queueQuery = sprintf("SELECT * FROM comqueue WHERE DATE(`timeadded`) <> '%s' " . $isCustomerCondition . $isDateCondition . " LIMIT 50000 ", Sanitise::String($date));
        $queueData = $pdo->query($queueQuery)->fetchAll(PDO::FETCH_ASSOC);
        $this->_databaseManager->ClosePDOConn($pdo);
        return $queueData;
    }

    public function deleteComQueueDetails($objComqueue) {
        $Query = sprintf('DELETE FROM comqueue WHERE cqid between %d and %d and customerno = %d and DATE(timeadded) = "%s" ', Sanitise::Long($objComqueue->fromcqid), Sanitise::Long($objComqueue->tocqid), Sanitise::Long($objComqueue->customerno), Sanitise::String($objComqueue->date));
        $this->_databaseManager->executeQuery($Query);
    }
}
?>