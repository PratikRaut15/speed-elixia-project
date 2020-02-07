<?php
class VOTrips {
}
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
include_once $RELATIVE_PATH_DOTS . 'config.inc.php';
include_once $RELATIVE_PATH_DOTS . '/lib/system/DatabaseManager.php';
class Trips extends DatabaseManager {
    public function __construct($customerno, $userid) {
        parent::__construct($customerno, $userid);
        $this->customerno = $customerno;
        $this->userid = $userid;
        $this->today = date("Y-m-d H:i:s");
    }

    public function is_status_exists($statusname) {
        $Query = "SELECT tripstatus FROM tripstatus WHERE customerno=$this->customerno AND tripstatus='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($statusname));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function is_tripend_check($vehicleid) {
        $Query = "SELECT is_tripend FROM tripdetails WHERE vehicleid =" . $vehicleid . " AND is_tripend=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function gettripdetails($vehicleid = NULL, $triplogno = NULL) {
        //api call this function
        $data = array();
        $query = "select t.tripid, t.vehicleno, t.vehicleid, t.triplogno, t.tripstatusid ,t.odometer
            ,t.statusdate, t.routename, t.budgetedkms, t.budgetedhrs, t.actualkms, t.actualhrs
            ,t.consignor, t.consignee, t.consignorid, t.consigneeid, t.billingparty, t.mintemp, t.maxtemp
            ,t.drivername, t.drivermobile1, t.drivermobile2, t.remark, t.perdaykm, t.customerno
            , t.entrytime, t.is_tripend, ts.tripstatus
            from tripdetails as t
            left join tripstatus as ts on ts.tripstatusid = t.tripstatusid
            where t.isdeleted=0
            AND t.customerno = $this->customerno";
        if (isset($vehicleid)) {
            $query .= " AND t.vehicleid='" . $vehicleid . "' ";
        }
        if (isset($triplogno)) {
            $query .= " AND t.triplogno='" . $triplogno . "' "
                . " AND t.is_tripend = 0 ";
        }
        $query .= "order by t.tripid desc limit 1";
        $SQL = sprintf($query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $data = array(
                    "tripid" => $row['tripid']
                    , "vehicleno" => $row['vehicleno']
                    , "vehicleid" => $row['vehicleid']
                    , "triplogno" => $row['triplogno']
                    , "tripstatusid" => $row['tripstatusid']
                    , "odometer" => $row['odometer']
                    , "statusdate" => $row['statusdate']
                    , "routename" => $row['routename']
                    , "budgetedkms" => $row['budgetedkms']
                    , "budgetedhrs" => $row['budgetedhrs']
                    , "actualkms" => $row['actualkms']
                    , "actualhrs" => $row['actualhrs']
                    , "consignor" => $row['consignor']
                    , "consignee" => $row['consignee']
                    , "consignorid" => $row['consignorid']
                    , "consigneeid" => $row['consigneeid']
                    , "billingparty" => $row['billingparty']
                    , "mintemp" => $row['mintemp']
                    , "maxtemp" => $row['maxtemp']
                    , "temprange" => $row['mintemp'] . "-" . $row['maxtemp']
                    , "drivername" => $row['drivername']
                    , "drivermobile1" => $row['drivermobile1']
                    , "drivermobile2" => $row['drivermobile2']
                    , "remark" => $row['remark']
                    , "perdaykm" => $row['perdaykm']
                    , "customerno" => $row['customerno']
                    , "entrytime" => $row['entrytime']
                    , "is_tripend" => $row['is_tripend']
                    , "tripstatus" => $row['tripstatus'],
                );
            }
            return $data;
        }
        return NULL;
    }

    public function getunitno($vehicleid) {
        $Query = 'SELECT unitno FROM unit WHERE customerno = %d AND vehicleid = %d';
        $unitQuery = sprintf($Query, $this->customerno, Sanitise::Long($vehicleid));
        $this->executeQuery($unitQuery);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $unitno = $row['unitno'];
                return $unitno;
            }
        }
        return NULL;
    }

    public function add_tripdetails($data) {
        //print_r($data);
        $vehicleid = $data['vehicleid'];
        $statusdatetime = $data['statusdatetime'];
        $unitno = isset($data['unitno']) ? $data['unitno'] : 0;
        $etarrivaldate = isset($data['etarrivaldate']) ? $data['etarrivaldate'] : "";
        $materialtype = isset($data['materialtype']) ? $data['materialtype'] : "";
        $orderno = isset($data['lrno']) ? $data['lrno'] : "";
        $orderdatetime = isset($data['lrdatetime']) ? $data['lrdatetime'] : "";
        if ($orderdatetime != '') {
            $orderdatetime = date('Y-m-d H:i:s', strtotime($data['lrdatetime']));
        }
        if ($vehicleid > 0) {
            if ($unitno == "") {
                $unitno = $this->getunitno($vehicleid);
            }
            $customerno = isset($data['customerno']) ? $data['customerno'] : $this->customerno;
            $statusdate = date('Y-m-d', strtotime($data['statusdatetime']));
            $devicelat = '';
            $devicelong = '';
            if (!empty($statusdatetime)) {
                $location = "../../customer/$customerno/unitno/$unitno/sqlite/$statusdate.sqlite";
                if (file_exists($location)) {
                    $path = "sqlite:$location";
                    $db = new PDO($path);
                    $statusdatetime2 = $data['statusdatetime'];
                    $statusdatequery = "SELECT devicelat,devicelong FROM devicehistory WHERE lastupdated >= '" . $statusdatetime2 . "'
                ORDER BY lastupdated ASC LIMIT 1;;";
                    $result1 = $db->query($statusdatequery);
                    $arrQueryResult1 = $result1->fetchAll();
                    $devicelat = isset($arrQueryResult1[0]['devicelat']) ? $arrQueryResult1[0]['devicelat'] : '0';
                    $devicelong = isset($arrQueryResult1[0]['devicelong']) ? $arrQueryResult1[0]['devicelong'] : '0';
                }
            }
            $statusodometer = $data['statusodometer'];
            //Set default status odometer as current odometer in case we are unable to get it from sqlite
            if ($statusodometer == 0) {
                $sql = "select odometer from vehicle where customerno= $this->customerno AND vehicleid=" . $vehicleid . " AND isdeleted=0";
                $SQL = sprintf($sql);
                $this->executeQuery($SQL);
                if ($this->get_rowCount() > 0) {
                    while ($row = $this->get_nextRow()) {
                        $statusodometer = $row['odometer'];
                    }
                }
            }
            $Query = "Insert into tripdetails (customerno,vehicleno,vehicleid,triplogno,tripstatusid,odometer,devicelat,devicelong,statusdate,
        routename,budgetedkms,budgetedhrs,consignor,consignee,consignorid,consigneeid,billingparty,mintemp,maxtemp,drivername,drivermobile1,
        drivermobile2,remark,perdaykm,etarrivaldate,materialtype,entrytime,addedby,orderno,orderdatetime) " . "VALUES($this->customerno,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
        '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','$this->today',$this->userid,'%s','%s')";
            $SQL = sprintf($Query, Sanitise::String($data['vehicleno']), Sanitise::String($data['vehicleid']), Sanitise::String($data['triplogno']), Sanitise::String($data['tripstatus']), Sanitise::String($statusodometer), Sanitise::String($devicelat), Sanitise::String($devicelong), Sanitise::String($data['statusdatetime']), Sanitise::String($data['routename']), Sanitise::String($data['budgetedkms']), Sanitise::String($data['budgetedhrs']), Sanitise::String($data['consignor']), Sanitise::String($data['consignee']), Sanitise::String($data['consignorid']), Sanitise::String($data['consigneeid']), Sanitise::String($data['billingparty']), Sanitise::String($data['mintemp']), Sanitise::String($data['maxtemp']), Sanitise::String($data['drivername']), Sanitise::String($data['drivermobile1']), Sanitise::String($data['drivermobile2']), Sanitise::String($data['remark']), Sanitise::String($data['perdaykm']), Sanitise::String($etarrivaldate), Sanitise::String($materialtype), Sanitise::String($data['perdaykm']), Sanitise::String($data['lrno']), Sanitise::String($data['lrdatetime']));
            $this->executeQuery($SQL);
            $triplastid = $this->get_insertedId();
            //check loading date exists or not
            $loadingdate = isset($data['loadingdate']) ? $data['loadingdate'] : "";
            $statusdatetime = '';
            if (!empty($loadingdate)) {
                $statusdatetime = date('Y-m-d H:i:s', strtotime($loadingdate));
                $tripstatusid = 2;
                $Query = "Insert into tripdetail_history (customerno,tripid,vehicleno,vehicleid,triplogno,tripstatusid,odometer,devicelat,devicelong,
            statusdate,routename,budgetedkms,budgetedhrs,consignor,consignee,consignorid,consigneeid,billingparty,mintemp,maxtemp,drivername,
            drivermobile1,drivermobile2,etarrivaldate,materialtype,entrytime,addedby,orderno,orderdatetime) " . "VALUES($this->customerno,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
            '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','$this->today',$this->userid,'%s','%s')";
                $SQL = sprintf($Query, Sanitise::String($triplastid), Sanitise::String($data['vehicleno']), Sanitise::String($data['vehicleid']), Sanitise::String($data['triplogno']), Sanitise::String($tripstatusid), Sanitise::String($statusodometer), Sanitise::String($devicelat), Sanitise::String($devicelong), Sanitise::String($statusdatetime), Sanitise::String($data['routename']), Sanitise::String($data['budgetedkms']), Sanitise::String($data['budgetedhrs']), Sanitise::String($data['consignor']), Sanitise::String($data['consignee']), Sanitise::String($data['consignorid']), Sanitise::String($data['consigneeid']), Sanitise::String($data['billingparty']), Sanitise::String($data['mintemp']), Sanitise::String($data['maxtemp']), Sanitise::String($data['drivername']), Sanitise::String($data['drivermobile1']), Sanitise::String($data['drivermobile2']), Sanitise::String($etarrivaldate), Sanitise::String($materialtype), Sanitise::String($data['lrno']), Sanitise::String($data['lrdatetime']));
                $this->executeQuery($SQL);
            }
            //also insert in triphistory tables
            $Query = "Insert into tripdetail_history (customerno,tripid,vehicleno,vehicleid,triplogno,tripstatusid,odometer,devicelat,devicelong,statusdate,
        routename,budgetedkms,budgetedhrs,consignor,consignee,consignorid,consigneeid,billingparty,mintemp,maxtemp,drivername,drivermobile1,drivermobile2,
        etarrivaldate,materialtype,entrytime,addedby,orderno,orderdatetime) " . "VALUES($this->customerno,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
        '%s','%s','%s','%s','%s','$this->today',$this->userid,'%s','%s')";
            $SQL = sprintf($Query, Sanitise::String($triplastid), Sanitise::String($data['vehicleno']), Sanitise::String($data['vehicleid']), Sanitise::String($data['triplogno']), Sanitise::String($data['tripstatus']), Sanitise::String($statusodometer), Sanitise::String($devicelat), Sanitise::String($devicelong), Sanitise::String($data['statusdatetime']), Sanitise::String($data['routename']), Sanitise::String($data['budgetedkms']), Sanitise::String($data['budgetedhrs']), Sanitise::String($data['consignor']), Sanitise::String($data['consignee']), Sanitise::String($data['consignorid']), Sanitise::String($data['consigneeid']), Sanitise::String($data['billingparty']), Sanitise::String($data['mintemp']), Sanitise::String($data['maxtemp']), Sanitise::String($data['drivername']), Sanitise::String($data['drivermobile1']), Sanitise::String($data['drivermobile2']), Sanitise::String($etarrivaldate), Sanitise::String($materialtype), Sanitise::String($data['lrno']), Sanitise::String($data['lrdatetime']));
            $this->executeQuery($SQL);
            //Check customer has temp sensor & update according to no of sensors
            //Update the temperature values in the vehicle table
            $minTemp = Sanitise::String($data['mintemp']);
            $maxTemp = Sanitise::String($data['maxtemp']);
            $noOfTempSensors = isset($data['temp_sensors']) ? $data['temp_sensors'] : '';
            if (isset($noOfTempSensors) && !empty($noOfTempSensors) && $vehicleid > 0) {
                $Querytemp = " UPDATE vehicle SET ";
                switch ($noOfTempSensors) {
                case 4:
                    $Querytemp .= " temp4_min='$minTemp', temp4_max='$maxTemp' , ";
                case 3:
                    $Querytemp .= " temp3_min='$minTemp', temp3_max='$maxTemp' , ";
                case 2:
                    $Querytemp .= " temp2_min='$minTemp', temp2_max='$maxTemp' , ";
                case 1:
                    $Querytemp .= " temp1_min='$minTemp', temp1_max='$maxTemp'  ";
                }
                $Querytemp .= " WHERE vehicleid = $vehicleid ";
                //$Query = "UPDATE vehicle SET temp1_min=$minTemp,temp2_min=$minTemp, temp1_max=$maxTemp,temp2_max=$maxTemp WHERE vehicleid = $vehicleid";
                $this->executeQuery($Querytemp);
            }
        }
    }

    public function edittripdetails($data, $isApi = 0) {
        $tripid = $data['tripid'];
        $statusodometer = $data['statusodometer'];
        $vehicleid = $data['vehicleid'];
        $tripstatusid = 0;
        /*echo "user counts ". count($data['users']);*/
        //print("<pre>"); print_r($data); die;
        if (isset($data['statusdatetime'])) {
            $statusdatetimeedit = $data['statusdatetime'];
        } else {
            $statusdatetimeedit = $data['sdate'] . " " . $data['stime'];
            $statusdatetimeedit = date('Y-m-d H:i:s', strtotime($statusdatetimeedit));
        }
        $Query = "SELECT tripid,odometer,tripstatusid "
            . "FROM tripdetails "
            . "WHERE customerno=$this->customerno AND tripid=" . $tripid . " AND isdeleted=0 AND is_tripend=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $tripid = $row['tripid'];
                $tripstatusid = $row['tripstatusid'];
                $odometer = $row['odometer']; //previous odometer
            }
            if ($tripstatusid == $data['tripstatus']) {
                //If status is same, we need to keep the status odometer same
                $statusodometer = $odometer;
            }
        }
///////// if trip status is 10(unloading start)/////
        if (isset($data['tripstatus']) && $data['tripstatus'] == '10') {
            if (isset($isApi) && $isApi != "1") {
                $this->deMapDataForRTD($data);
            }
        }
/////// end if status is 10 /////////////////////
        //Set default status odometer as current odometer in case we are unable to get it from sqlite
        if ($statusodometer == 0) {
            $sql = "select odometer from vehicle where customerno = $this->customerno AND vehicleid = $vehicleid  AND isdeleted=0";
            $SQL = sprintf($sql);
            $this->executeQuery($SQL);
            if ($this->get_rowCount() > 0) {
                while ($row = $this->get_nextRow()) {
                    $statusodometer = $row['odometer'];
                }
            }
        }
        $unitno = $data['unitno'];
        $devicelat = 0;
        $devicelong = 0;
        if (!empty($statusdatetimeedit)) {
            $statusdate = date('Y-m-d', strtotime($statusdatetimeedit));
            $location = "../../customer/" . $this->customerno . "/unitno/$unitno/sqlite/$statusdate.sqlite";
            if (file_exists($location)) {
                $path = "sqlite:$location";
                $db = new PDO($path);
                $statusdatetime2 = $data['statusdatetime'];
                $statusdatequery = "SELECT devicelat,devicelong FROM devicehistory WHERE lastupdated >= '" . $statusdatetimeedit . "'
                    ORDER BY lastupdated ASC LIMIT 1;";
                $result1 = $db->query($statusdatequery);
                if ($result1 !== false) {
                    $arrQueryResult1 = $result1->fetchAll();
                    $devicelat = isset($arrQueryResult1[0]['devicelat']) ? $arrQueryResult1[0]['devicelat'] : 0;
                    $devicelong = isset($arrQueryResult1[0]['devicelong']) ? $arrQueryResult1[0]['devicelong'] : 0;
                }
            }
        }
        $Query = "update tripdetails set "
        . "vehicleno='" . $data['vehicleno'] . "' ,"
        . "vehicleid='" . $data['vehicleid'] . "' ,"
        . "triplogno='" . $data['triplogno'] . "' ,"
        . "tripstatusid ='" . $data['tripstatus'] . "' ,"
        . "odometer ='" . $statusodometer . "' ,"
        . "devicelat ='" . $devicelat . "' ,"
        . "devicelong ='" . $devicelong . "' ,"
        . "statusdate ='" . $statusdatetimeedit . "' ,"
        . "routename ='" . $data['routename'] . "' ,"
        . "budgetedkms='" . $data['budgetedkms'] . "' ,"
        . "budgetedhrs='" . $data['budgetedhrs'] . "' ,"
        . "consignor='" . $data['consignor'] . "' ,"
        . "consignee='" . $data['consignee'] . "' ,"
        . "consignorid='" . $data['consignorid'] . "' ,"
        . "consigneeid='" . $data['consigneeid'] . "' ,"
        . "billingparty='" . $data['billingparty'] . "' ,"
        . "mintemp='" . $data['mintemp'] . "' ,"
        . "maxtemp='" . $data['maxtemp'] . "' ,"
        . "drivername='" . $data['drivername'] . "' ,"
        . "drivermobile1='" . $data['drivermobile1'] . "' ,"
        . "drivermobile2='" . $data['drivermobile2'] . "' ,"
        . "remark='" . $data['remark'] . "' ,"
        . "perdaykm='" . $data['perdaykm'] . "' ,"
        . "is_tripend='" . $data['istripend'] . "' ,"
        . "etarrivaldate='" . $data['etarrivaldate'] . "' ,"
        . "materialtype='" . $data['materialtype'] . "' ,"
        . "updatedtime='" . $this->today . "', updated_by='" . $this->userid
            . "' where tripid=" . $data['tripid'];
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        $this->get_affectedRows();
/*Code for insert users into new mapping Tables*/
        if (isset($_SESSION['role_modal']) && ($_SESSION['role_modal'] == 'Administrator' || $_SESSION['role_modal'] == 'elixir')) {
            $users = array();
            if (isset($data['users']) && $data['users'] != "") {
                $users = $data['users'];
            } else {
                $users[] = '0';
            }
            if (isset($data['oldUsers']) && $data['oldUsers'] != "") {
                foreach ($data['oldUsers'] as $oldUser) {
                    if (in_array($oldUser, $users)) {
                        // echo "Match found";
                    } else {
                        $Query2 = "UPDATE vehicleusermapping SET isdeleted='%d',
                                 updated_by = '%d',
                                updated_on = '%s'
                                WHERE vehicleid = " . $data['vehicleid'] . " AND userid = " . $oldUser;
                        $SQL2 = sprintf($Query2
                            , Sanitise::Long(1)
                            , Sanitise::Long($_SESSION['userid'])
                            , Sanitise::DateTime($this->today)
                        );
                        $this->executeQuery($SQL2);
                        $Query = "UPDATE groupman SET isdeleted='%d',`timestamp` = '%s' WHERE groupid = " . $data['groupid'] . " AND userid = " . $oldUser;
                        $SQL = sprintf($Query
                            , Sanitise::Long(1)
                            , Sanitise::DateTime($this->today)
                        );
                        $this->executeQuery($SQL);
                        //echo "Match not found";
                    }
                }
            }
            if (isset($data['users']) && !empty($data['users'])) {
                //$sql = "select tripmapuserid from tripusers where tripid = ".$data['tripid']." AND isdeleted = 0";
                $Query = "UPDATE tripusers SET isdeleted='%d',
                             updatedby = '%d',
                            updatedon = '%s'
                            WHERE tripid = " . $data['tripid'];
                $SQL = sprintf($Query
                    , Sanitise::Long(1)
                    , Sanitise::Long($_SESSION['userid'])
                    , Sanitise::DateTime($this->today)
                );
                $this->executeQuery($SQL);
                foreach ($data['users'] as $user) {
                    if ((isset($data['istripend']) && $data['istripend'] == '1') || (isset($data['tripstatus']) && $data['tripstatus'] == '10')) {
                        $Query = "SELECT vehicleid, groupid, userid "
                            . "FROM vehicleusermapping "
                            . "WHERE vehicleid=" . $data['vehicleid'] . " AND userid=" . $user . " AND isdeleted=0";
                        $SQL = sprintf($Query);
                        $this->executeQuery($SQL);
                        if ($this->get_rowCount() > 0) {
                            $Query2 = "UPDATE vehicleusermapping SET isdeleted='%d',
                             updated_by = '%d',
                            updated_on = '%s'
                            WHERE vehicleid = " . $data['vehicleid'] . " AND userid = " . $user;
                            $SQL2 = sprintf($Query2
                                , Sanitise::Long(1)
                                , Sanitise::Long($_SESSION['userid'])
                                , Sanitise::DateTime($this->today)
                            );
                            $this->executeQuery($SQL2);
                        } else {
                            $Query = "INSERT INTO vehicleusermapping (vehicleid, groupid, userid, customerno, created_on, updated_on, created_by, updated_by, isdeleted)
                            VALUES ('%d', '%d', '%d', '%d', '%s', '%s', '%d', '%d', '%d');";
                            $SQL = sprintf($Query
                                , Sanitise::Long($data['vehicleid'])
                                , Sanitise::Long($data['groupid'])
                                , Sanitise::Long($user) // mapping user id
                                , Sanitise::Long($data['customerno'])
                                , Sanitise::DateTime($this->today)
                                , Sanitise::DateTime($this->today)
                                , Sanitise::Long($_SESSION['userid'])
                                , Sanitise::DateTime($_SESSION['userid'])
                                , Sanitise::Long(1)
                            );
                            $this->executeQuery($SQL);
                        }
                    } else {
                        $Query = "SELECT vehicleid, groupid, userid "
                            . "FROM vehicleusermapping "
                            . "WHERE vehicleid=" . $data['vehicleid'] . " AND userid=" . $user . " AND isdeleted=0";
                        $SQL = sprintf($Query);
                        $this->executeQuery($SQL);
                        //if ($this->get_rowCount() > 0) {
                        if ($this->get_rowCount() == 0) {
                            $row = $this->get_nextRow();
                            $Query = "INSERT INTO vehicleusermapping (vehicleid, groupid, userid, customerno, created_on, updated_on, created_by, updated_by, isdeleted)
                            VALUES ('%d', '%d', '%d', '%d', '%s', '%s', '%d', '%d', '%d');";
                            $SQL = sprintf($Query
                                , Sanitise::Long($data['vehicleid'])
                                , Sanitise::Long($data['groupid'])
                                , Sanitise::Long($user) // mapping user id
                                , Sanitise::Long($data['customerno'])
                                , Sanitise::DateTime($this->today)
                                , Sanitise::DateTime($this->today)
                                , Sanitise::Long($_SESSION['userid'])
                                , Sanitise::DateTime($_SESSION['userid'])
                                , Sanitise::Long(0)
                            );
                            $this->executeQuery($SQL);
                        }
                    }
                    $Query = "SELECT count(*) as count1 FROM vehicleusermapping WHERE groupid=" . $data['groupid'] . " AND userid=" . $user . " AND isdeleted=0";
                    $SQL = sprintf($Query);
                    $this->executeQuery($SQL);
                    if ($this->get_rowCount() > 0) {
                        $row = $this->get_nextRow();
                        $count1 = $row['count1'];
                    }
                    if (isset($data['istripend']) && $data['istripend'] == '1') {
                        if (isset($count1) && $count1 == '0') {
                            $Query = "SELECT gmid, customerno, userid "
                                . "FROM groupman "
                                . "WHERE groupid=" . $data['groupid'] . " AND userid=" . $user . " AND isdeleted=0";
                            $SQL = sprintf($Query);
                            $this->executeQuery($SQL);
                            if ($this->get_rowCount() > '0') {
                                $Query = "UPDATE groupman SET isdeleted='%d',`timestamp` = '%s' WHERE groupid = " . $data['groupid'] . " AND userid = " . $user;
                                $SQL = sprintf($Query
                                    , Sanitise::Long(1)
                                    , Sanitise::DateTime($this->today)
                                );
                                $this->executeQuery($SQL);
                            }
                        } else {
                            $Query = "SELECT gmid, customerno, userid "
                                . "FROM groupman "
                                . "WHERE groupid=" . $data['groupid'] . " AND userid=" . $user . " AND isdeleted=0";
                            $SQL = sprintf($Query);
                            $this->executeQuery($SQL);
                            if ($this->get_rowCount() == '0') {
                                $Query = "INSERT INTO groupman (groupid,vehicleid, customerno, userid, isdeleted, `timestamp`)
                            VALUES ('%d','%d', '%d', '%d', '%d', '%s');";
                                $SQL = sprintf($Query
                                    , Sanitise::Long($data['groupid'])
                                    , Sanitise::Long(0)
                                    , Sanitise::Long($data['customerno'])
                                    , Sanitise::Long($user) // mapping user id
                                    , Sanitise::Long(1)
                                    , Sanitise::DateTime($this->today)
                                );
                                $this->executeQuery($SQL);
                            }
                        }
                    } // end if for tripend = 1
                    else {
                        if (isset($row['count1']) && $row['count1'] == '0') {
                            $Query = "SELECT gmid, customerno, userid "
                                . "FROM groupman "
                                . "WHERE groupid=" . $data['groupid'] . " AND userid=" . $user . " AND isdeleted=0";
                            $SQL = sprintf($Query);
                            $this->executeQuery($SQL);
                            if ($this->get_rowCount() > '0') {
                                $Query = "UPDATE groupman SET isdeleted='%d', `timestamp` = '%s' WHERE groupid = " . $data['groupid'] . " AND userid = " . $user;
                                $SQL = sprintf($Query, Sanitise::Long(1), Sanitise::DateTime($this->today)
                                );
                                $this->executeQuery($SQL);
                            }
                        } else {
                            $Query = "SELECT gmid, customerno, userid "
                                . "FROM groupman "
                                . "WHERE groupid=" . $data['groupid'] . " AND userid=" . $user . " AND isdeleted=0";
                            $SQL = sprintf($Query);
                            $this->executeQuery($SQL);
                            if ($this->get_rowCount() == '0') {
                                $Query = "INSERT INTO groupman (groupid,vehicleid, customerno, userid, isdeleted, `timestamp`)
                            VALUES ('%d','%d', '%d', '%d', '%d', '%s');";
                                $SQL = sprintf($Query
                                    , Sanitise::Long($data['groupid'])
                                    , Sanitise::Long(0)
                                    , Sanitise::Long($data['customerno'])
                                    , Sanitise::Long($user) // mapping user id
                                    , Sanitise::Long(0)
                                    , Sanitise::DateTime($this->today)
                                );
                                $this->executeQuery($SQL);
                            }
                        }
                    }
                    $Query = "INSERT INTO tripusers (customerno, tripid, addeduserid, createdby, createdon, updatedby, updatedon)
                        VALUES ('%d', '%d', '%d', '%d', '%s', '%d', '%s');";
                    $SQL = sprintf($Query
                        , Sanitise::Long($data['customerno'])
                        , Sanitise::Long($data['tripid'])
                        , Sanitise::Long($user)
                        , Sanitise::Long($_SESSION['userid'])
                        , Sanitise::DateTime($this->today)
                        , Sanitise::Long($_SESSION['userid'])
                        , Sanitise::DateTime($this->today)
                    );
                    $this->executeQuery($SQL);
                } // end foreach
                // $inserted = $this->get_insertedId();
            } else {
                $Query = "UPDATE tripusers SET isdeleted='%d', updatedby = '%d', updatedon = '%s' WHERE tripid = " . $data['tripid'];
                $SQL = sprintf($Query
                    , Sanitise::Long(1)
                    , Sanitise::Long($_SESSION['userid'])
                    , Sanitise::DateTime($this->today)
                );
                $this->executeQuery($SQL);
            }
        } // end main if checking for role
        /*End Code for insert users into new mapping Table*/
        if ($tripstatusid != $data['tripstatus']) {
            if (isset($data['materialtype'])) {
                $data['materialtype'] = $data['materialtype'];
            } else {
                $data['materialtype'] = "0";
            }
            //insert the current record in the history if status changes
            $Query = "Insert into tripdetail_history "
                . " (customerno,actualhrs,actualkms,tripid,vehicleno,vehicleid,triplogno,tripstatusid"
                . " ,odometer,devicelat,devicelong,statusdate,routename,budgetedkms,budgetedhrs"
                . " ,consignor,consignee,consignorid,consigneeid,billingparty"
                . " ,mintemp,maxtemp,drivername,drivermobile1,drivermobile2,etarrivaldate,materialtype,is_tripend,entrytime,addedby) "
                . " VALUES($this->customerno,'%s','%s','%s','%s','%s','%s','%s' "
                . " ,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s' "
                . " ,'%s','%s','%s','%s','%s','%s','%s','%s','$this->today',$this->userid)";
            $SQL = sprintf($Query
                , Sanitise::String($data['actualhrs'])
                , Sanitise::String($data['actualkms'])
                , Sanitise::String($tripid)
                , Sanitise::String($data['vehicleno'])
                , Sanitise::String($data['vehicleid'])
                , Sanitise::String($data['triplogno'])
                , Sanitise::String($data['tripstatus'])
                , Sanitise::String($statusodometer)
                , Sanitise::String($devicelat)
                , Sanitise::String($devicelong)
                , Sanitise::String($statusdatetimeedit)
                , Sanitise::String($data['routename'])
                , Sanitise::String($data['budgetedkms'])
                , Sanitise::String($data['budgetedhrs'])
                , Sanitise::String($data['consignor'])
                , Sanitise::String($data['consignee'])
                , Sanitise::String($data['consignorid'])
                , Sanitise::String($data['consigneeid'])
                , Sanitise::String($data['billingparty'])
                , Sanitise::String($data['mintemp'])
                , Sanitise::String($data['maxtemp'])
                , Sanitise::String($data['drivername'])
                , Sanitise::String($data['drivermobile1'])
                , Sanitise::String($data['drivermobile2'])
                , Sanitise::String($data['etarrivaldate'])
                , Sanitise::String($data['materialtype'])
                , Sanitise::String($data['istripend'])
            );
            $this->executeQuery($SQL);
        }
        $minTemp = Sanitise::String($data['mintemp']);
        $maxTemp = Sanitise::String($data['maxtemp']);
        //Update the temperature values in the vehicle table
        /* TODO:
        //Check customer has temp sensor & update according to no of sensors
        $noOfTempSensors = '';
        if (isset($noOfTempSensors) && !empty($noOfTempSensors)) {
        $Query = "UPDATE vehicle SET ";
        switch ($noOfTempSensors) {
        case 4:$Query .= "temp4_min=$minTemp,temp4_max=$minTemp,";
        case 3:$Query .= "temp3_min=$minTemp,temp3_max=$minTemp,";
        case 2:$Query .= "temp2_min=$minTemp,temp2_max=$minTemp,";
        case 1:$Query .= "temp1_min=$minTemp,temp1_max=$minTemp ";
        }
        $Query .= "WHERE vehicleid = $vehicleid";
        }
         */
        $Query = "UPDATE vehicle SET temp1_min=$minTemp,temp2_min=$minTemp, temp1_max=$maxTemp,temp2_max=$maxTemp WHERE vehicleid = $vehicleid";
        $this->executeQuery($Query);
        if (isset($data['istripend'])) {
            if ($data['istripend'] == '1') {
                $query = "update tripdetails set actualhrs = '" . $data['actualhrs'] . "',  actualkms = '" . $data['actualkms'] . "'
                where tripid =" . $data['tripid'];
                $SQL = sprintf($query);
                $this->executeQuery($SQL);
                $query = "update tripdetail_history set is_tripend = 1 where tripid = " . $data['tripid'];
                $SQL = sprintf($query);
                $this->executeQuery($SQL);
                //Reset the temperature values in the vehicle table to default 1 and 50
                $Query = "UPDATE vehicle "
                    . "SET temp1_min=1,temp1_max=50 "
                    . ",temp2_min=1, temp2_max=50 "
                    . ",temp3_min=1, temp3_max=50 "
                    . ",temp4_min=1, temp4_max=50 "
                    . "WHERE vehicleid = $vehicleid";
                $this->executeQuery($Query);
            }
        }
    }

    public function add_statusdata($statusname) {
        $Query = "Insert into tripstatus (customerno,tripstatus,entrytime,addedby) VALUES($this->customerno,'%s','$this->today',$this->userid)";
        $SQL = sprintf($Query, Sanitise::String($statusname));
        $this->executeQuery($SQL);
    }

    public function add_consigneedata($consigneename, $cemail, $cphone) {
        $Query = "Insert into tripconsignee (customerno,consigneename,email,phone,entrytime,addedby)
        VALUES($this->customerno,'%s','%s','%s','$this->today',$this->userid)";
        $SQL = sprintf($Query, Sanitise::String($consigneename), Sanitise::String($cemail), Sanitise::String($cphone));
        $this->executeQuery($SQL);
    }

    public function add_consignordata($consigneename, $cemail, $cphone) {
        $Query = "Insert into tripconsignor (customerno,consignorname,email,phone,entrytime,addedby)
        VALUES($this->customerno,'%s','%s','%s','$this->today',$this->userid)";
        $SQL = sprintf($Query, Sanitise::String($consigneename), Sanitise::String($cemail), Sanitise::String($cphone));
        $this->executeQuery($SQL);
    }

    public function update_tripstatusdata($statusname, $statusid) {
        $Query = "update tripstatus set tripstatus='" . $statusname . "' ,updatedtime='" . $this->today . "', updated_by='" . $this->userid . "'
        where tripstatusid=" . $statusid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function update_consignee($consigneename, $cemail, $cphone, $consid) {
        $Query = "update tripconsignee set consigneename='" . $consigneename . "' ,email='" . $cemail . "',phone='" . $cphone . "',
        updatedtime='" . $this->today . "', updated_by='" . $this->userid . "' where consid=" . $consid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function update_consignor($consigneename, $cemail, $cphone, $consid) {
        $Query = "update tripconsignor set consignorname='" . $consigneename . "' ,email='" . $cemail . "',phone='" . $cphone . "',
        updatedtime='" . $this->today . "', updated_by='" . $this->userid . "' where consrid=" . $consid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function delete_tripstatusdata($statusid) {
        $Query = "update tripstatus set isdeleted=1 where tripstatusid=" . $statusid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function delete_consignee($consid) {
        $Query = "update tripconsignee set isdeleted=1 where consid=" . $consid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function delete_consignor($consrid) {
        $Query = "update  tripconsignor set isdeleted=1 where consrid=" . $consrid;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
    }

    public function get_tripstatusedit($tripstatusid) {
        $tripstatusdata = array();
        $Query = "SELECT * FROM tripstatus WHERE customerno=$this->customerno AND tripstatusid =" . $tripstatusid . " AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $tripstatusdit = new stdClass();
                $tripstatusdit->tripstatusid = $row['tripstatusid'];
                $tripstatusdit->tripstatus = $row['tripstatus'];
                $tripstatusdit->customerno = $row['customerno'];
                $tripstatusdit->entrytime = $row['entrytime'];
                $tripstatusdit->addedby = $row['addedby'];
                $tripstatusdit->updatedtime = $row['updatedtime'];
                $tripstatusdit->updated_by = $row['updated_by'];
                $tripstatusdit->isdeleted = $row['isdeleted'];
                $tripstatusdata[] = $tripstatusdit;
            }
            return $tripstatusdata;
        }
        return null;
    }

    public function get_consigneeedit($consid) {
        $constdata = array();
        $Query = "SELECT * FROM  tripconsignee WHERE customerno=$this->customerno AND consid =" . $consid . " AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $consedit = new stdClass();
                $consedit->consid = $row['consid'];
                $consedit->consigneename = $row['consigneename'];
                $consedit->phone = $row['phone'];
                $consedit->email = $row['email'];
                $consedit->entrytime = $row['entrytime'];
                $consedit->addedby = $row['addedby'];
                $consedit->updatedtime = $row['updatedtime'];
                $consedit->updated_by = $row['updated_by'];
                $consedit->isdeleted = $row['isdeleted'];
                $constdata[] = $consedit;
            }
            return $constdata;
        }
        return null;
    }

    public function get_consignoredit($consid) {
        $constdata = array();
        $Query = "SELECT * FROM  tripconsignor WHERE customerno=$this->customerno AND consrid =" . $consid . " AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $consedit = new stdClass();
                $consedit->consrid = $row['consrid'];
                $consedit->consignorname = $row['consignorname'];
                $consedit->phone = $row['phone'];
                $consedit->email = $row['email'];
                $consedit->entrytime = $row['entrytime'];
                $consedit->addedby = $row['addedby'];
                $consedit->updatedtime = $row['updatedtime'];
                $consedit->updated_by = $row['updated_by'];
                $consedit->isdeleted = $row['isdeleted'];
                $constdata[] = $consedit;
            }
            return $constdata;
        }
        return null;
    }

    public function get_consignor() {
        $constdata = array();
        $Query = "SELECT * FROM  tripconsignor WHERE customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $consedit = new stdClass();
                $consedit->consrid = $row['consrid'];
                $consedit->consignorname = $row['consignorname'];
                $consedit->phone = $row['phone'];
                $consedit->email = $row['email'];
                $consedit->entrytime = $row['entrytime'];
                $consedit->addedby = $row['addedby'];
                $consedit->updatedtime = $row['updatedtime'];
                $consedit->updated_by = $row['updated_by'];
                $consedit->isdeleted = $row['isdeleted'];
                $constdata[] = $consedit;
            }
            return $constdata;
        }
        return null;
    }

    public function gettripdetailshistory($tripid) {
        $tripdata = array();
        $Query = "SELECT a.entrytime,a.devicelat,a.devicelong,a.vehicleno, a.triplogno,ts.tripstatus,ts.tripstatusid,a.statusdate,a.routename,
        a.budgetedkms,a.budgetedhrs,a.consignor,a.consignee, a.billingparty,a.mintemp,a.maxtemp,a.drivername,a.drivermobile1,a.drivermobile2,a.tripid
                 FROM  tripdetail_history as a left join  tripstatus as ts  on a.tripstatusid= ts.tripstatusid
                 WHERE a.customerno=$this->customerno AND a.tripid = $tripid AND a.isdeleted=0  order by triphisid desc";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $tripedit = new stdClass();
                $tripedit->vehicleno = $row['vehicleno'];
                $tripedit->triplogno = $row['triplogno'];
                $tripedit->devicelat = $row['devicelat'];
                $tripedit->devicelong = $row['devicelong'];
                $tripedit->tripstatus = $row['tripstatus'];
                $tripedit->tripstatusid = $row['tripstatusid'];
                $tripedit->statusdate = $row['statusdate'];
                $tripedit->routename = $row['routename'];
                $tripedit->budgetedkms = $row['budgetedkms'];
                $tripedit->budgetedhrs = $row['budgetedhrs'];
                $tripedit->consignor = $row['consignor'];
                $tripedit->consignee = $row['consignee'];
                $tripedit->billingparty = $row['billingparty'];
                $tripedit->mintemp = $row['mintemp'];
                $tripedit->maxtemp = $row['maxtemp'];
                $tripedit->drivername = $row['drivername'];
                $tripedit->drivermobile1 = $row['drivermobile1'];
                $tripedit->drivermobile2 = $row['drivermobile2'];
                $tripedit->tripid = $row['tripid'];
                $tripedit->entrytime = $row['entrytime'];
                $tripdata[] = $tripedit;
            }
            return $tripdata;
        }
        return null;
    }

    public function actuallododmeter($vehicleid) {
        $Query = "select odometer from vehicle where vehicleid = " . $vehicleid;
        $sql = sprintf($Query);
        $this->executeQuery($sql);
        $getactuallodometer = 0;
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getactuallodometer = $row['odometer'];
            }
        }
        return $getactuallodometer;
    }

    public function GetOdometerMax($date, $unitno) {
        $date = substr($date, 0, 11);
        $customerno = $_SESSION['customerno'];
        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
        $ODOMETER = 0;
        if (file_exists($location)) {
            $path = "sqlite:$location";
            $db = new PDO($path);
            $query = "SELECT max(odometer) as odometerm from vehiclehistory";
            $result = $db->query($query);
            foreach ($result as $row) {
                $ODOMETER = $row['odometerm'];
            }
        }
        return $ODOMETER;
    }

    public function gettripdetailsedit($tripid) {
        $tripdata = array();
        $Query = "SELECT u.unitno,a.remark,a.odometer, a.perdaykm, a.vehicleid"
            . ", v.vehicleno, v.groupid, a.triplogno,ts.tripstatus,ts.tripstatusid,a.statusdate"
            . ",a.routename,a.budgetedkms,a.budgetedhrs,consr.consignorname,con.consigneename,a.consignorid,a.consigneeid, "
            . " a.billingparty,a.mintemp,a.maxtemp,a.drivername,a.drivermobile1"
            . ",a.drivermobile2,a.tripid,a.entrytime,u.unitno,v.odometer as vehicleodometer "
            . " ,a.etarrivaldate, a.materialtype  "
            . " FROM  tripdetails as a "
            . " left join  tripstatus as ts  on a.tripstatusid = ts.tripstatusid "
            . " left join tripconsignee as con on con.consid = a.consigneeid "
            . " left join tripconsignor as consr on consr.consrid = a.consignorid "
            . " left join vehicle as v on v.vehicleid = a.vehicleid  "
            . " left join unit as u on u.vehicleid = v.vehicleid "
            . "WHERE a.customerno=$this->customerno AND a.tripid = $tripid AND a.isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $tripedit = new stdClass();
                $tripedit->vehicleno = $row['vehicleno'];
                $tripedit->vehicleid = $row['vehicleid'];
                $tripedit->groupid = $row['groupid'];
                $tripedit->unitno = $row['unitno'];
                $tripedit->loadingodometer = $row['odometer'];
                $tripedit->triplogno = $row['triplogno'];
                $tripedit->tripstatus = $row['tripstatus'];
                $tripedit->tripstatusid = $row['tripstatusid'];
                $tripedit->statusdate = $row['statusdate'];
                $tripedit->routename = $row['routename'];
                $tripedit->budgetedkms = $row['budgetedkms'];
                $tripedit->budgetedhrs = $row['budgetedhrs'];
                $tripedit->consignor = $row['consignorname'];
                $tripedit->consignee = $row['consigneename'];
                $tripedit->consignorid = $row['consignorid'];
                $tripedit->consigneeid = $row['consigneeid'];
                $tripedit->billingparty = $row['billingparty'];
                $tripedit->mintemp = $row['mintemp'];
                $tripedit->maxtemp = $row['maxtemp'];
                $tripedit->drivername = $row['drivername'];
                $tripedit->drivermobile1 = $row['drivermobile1'];
                $tripedit->drivermobile2 = $row['drivermobile2'];
                $tripedit->tripid = $row['tripid'];
                $tripedit->remark = $row['remark'];
                $tripedit->perdaykm = $row['perdaykm'];
                $tripedit->unitno = $row['unitno'];
                $tripedit->entrytime = $row['entrytime'];
                $tripedit->vehicleodometer = $row['vehicleodometer'];
                $tripedit->etarrivaldate = $row['etarrivaldate'];
                $tripedit->materialtype = $row['materialtype'];
                $tripdata[] = $tripedit;
            }
            return $tripdata;
        }
        return null;
    }

    public function get_consignee() {
        $constdata = array();
        $Query = "SELECT * FROM  tripconsignee WHERE customerno=$this->customerno AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $consedit = new stdClass();
                $consedit->consid = $row['consid'];
                $consedit->consigneename = $row['consigneename'];
                $consedit->phone = $row['phone'];
                $consedit->email = $row['email'];
                $consedit->entrytime = $row['entrytime'];
                $consedit->addedby = $row['addedby'];
                $consedit->updatedtime = $row['updatedtime'];
                $consedit->updated_by = $row['updated_by'];
                $consedit->isdeleted = $row['isdeleted'];
                $constdata[] = $consedit;
            }
            return $constdata;
        }
        return null;
    }

    public function get_tripstatus() {
        $tripstatusdata = array();
        $Query = "SELECT * FROM tripstatus WHERE customerno IN ($this->customerno,0) AND isdeleted=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $tripstatusdit = new stdClass();
                $tripstatusdit->tripstatusid = $row['tripstatusid'];
                $tripstatusdit->tripstatus = $row['tripstatus'];
                $tripstatusdit->customerno = $row['customerno'];
                $tripstatusdit->entrytime = $row['entrytime'];
                $tripstatusdit->addedby = $row['addedby'];
                $tripstatusdit->updatedtime = $row['updatedtime'];
                $tripstatusdit->updated_by = $row['updated_by'];
                $tripstatusdit->isdeleted = $row['isdeleted'];
                $tripstatusdata[] = $tripstatusdit;
            }
            return $tripstatusdata;
        }
        return null;
    }

    public function getconsignorautotdata($q) {
        $getdata = array();
        $lq = "%$q%";
        $Query = "select consignorname,consrid FROM tripconsignor WHERE customerno=$this->customerno AND consignorname LIKE '%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($lq));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getdata[] = array(
                    'id' => $row['consrid'],
                    'value' => $row['consignorname'],
                );
            }
            return $getdata;
        }
        return null;
    }

    public function getconsigneeautotdata($q) {
        $getdata = array();
        $lq = "%$q%";
        $Query = "select consigneename,consid FROM tripconsignee WHERE customerno=$this->customerno AND consigneename LIKE '%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($lq));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getdata[] = array(
                    'id' => $row['consid'],
                    'value' => $row['consigneename'],
                );
            }
            return $getdata;
        }
        return null;
    }

    public function get_closed_triprecord($sdate = null, $edate = null) {
        // echo "report_type - ".$report_type; die;
        if ($sdate != null) {
            $sdate = strtotime($sdate);
            $sdate = date('Y-m-d H:i:s', $sdate);
        }
        if ($edate != null) {
            $edate = strtotime($edate);
            $edate = date('Y-m-d H:i:s', $edate);
        }
        $datebet = '';
        if ($sdate != null && $edate != null) {
            //$startdate = $this->getTripStartTime($row['tripid']);
            $datebet = " COALESCE(th.statusdate, '') between '" . $sdate . "' and '" . $edate . "' AND ";
        }
        $data = array();
        $getdata = array();
        if ($_SESSION['role_modal'] == 'Custom') {
            $Query = "select tripusers.addeduserid, ";
        } else {
            $Query = "select ";
        }
        $Query .= "COALESCE(th.statusdate, '') as starttime, a.tripid,ts.tripstatus,a.routename,a.drivername,a.drivermobile1,a.drivermobile2,a.remark,a.budgetedkms,a.mintemp,
                a.maxtemp,a.budgetedhrs,a.billingparty,consr.consignorname,con.consigneename,a.triplogno,a.vehicleno,v.vehicleid,a.updatedtime,
                a.statusdate,a.actualkms,a.actualhrs, a.updated_by, u.realname
                from  tripdetails as a
                left outer join tripdetail_history as th on a.tripid = th.tripid AND th.tripstatusid=3 AND th.isdeleted=0";
        //if($_SESSION['role_modal']=='Tracker'){
        if ($_SESSION['role_modal'] == 'Custom') {
            $Query .= " inner join tripusers on tripusers.tripid = a.tripid AND tripusers.addeduserid = " . $_SESSION['userid'] . " AND tripusers.isdeleted = 0";
        }
        $Query .= " left outer join  vehicle as v on a.vehicleid= v.vehicleid AND a.customerno = v.customerno"
        . " left join  tripstatus as ts  on a.tripstatusid= ts.tripstatusid "
        . " left join tripconsignee as con on con.consid = a.consigneeid "
        . " left join tripconsignor as consr on consr.consrid = a.consignorid "
        . " left outer join user as u on u.userid = a.updated_by"
        . " where a.customerno=" . $this->customerno . " AND " . $datebet . "  a.is_tripend=1 AND a.tripstatusid=10 order by a.updatedtime desc ";
        // echo $Query; die;
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $data[] = $row;
            }
            $today = date("Y-m-d");
            foreach ($data as $row) {
                $startdate = $row['starttime']; //$this->getTripStartTime($row['tripid']);
                if ($startdate != '') {
                    $starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($startdate));
                } else {
                    $starttime = '';
                }
                if ($starttime != "") {
////////////////////////////////////////////////////////////////////
                    $statusdate = date(speedConstants::DEFAULT_DATETIME, strtotime($row['statusdate']));
                    $datediffcheck = date_SDiff($starttime, $today);
                    if ($sdate != null && $edate != null) {
                        $getdata[] = array(
                            'tripid' => $row['tripid'],
                            'actualkms' => $row['actualkms'],
                            'actualhrs' => $row['actualhrs'],
                            'vehicleno' => $row['vehicleno'],
                            'triplogno' => $row['triplogno'],
                            'tripstatus' => $row['tripstatus'],
                            'routename' => $row['routename'],
                            'budgetedkms' => $row['budgetedkms'],
                            'budgetedhrs' => $row['budgetedhrs'],
                            'consignorname' => $row['consignorname'],
                            'consigneename' => $row['consigneename'],
                            'billingparty' => $row['billingparty'],
                            'mintemp' => $row['mintemp'],
                            'maxtemp' => $row['maxtemp'],
                            'drivername' => $row['drivername'],
                            'drivermobile1' => $row['drivermobile1'],
                            'drivermobile2' => $row['drivermobile2'],
                            'startdate' => $starttime,
                            'remark' => $row['remark'],
                            'updated_on' => $row['updatedtime'],
                            'realname' => $row['realname'],
                            'statusdate' => date(speedConstants::DEFAULT_DATETIME, strtotime($row['statusdate'])),
                        );
                    } elseif ($datediffcheck <= 90) {
                        $getdata[] = array(
                            'tripid' => $row['tripid'],
                            'actualkms' => $row['actualkms'],
                            'actualhrs' => $row['actualhrs'],
                            'vehicleno' => $row['vehicleno'],
                            'triplogno' => $row['triplogno'],
                            'tripstatus' => $row['tripstatus'],
                            'routename' => $row['routename'],
                            'budgetedkms' => $row['budgetedkms'],
                            'budgetedhrs' => $row['budgetedhrs'],
                            'consignorname' => $row['consignorname'],
                            'consigneename' => $row['consigneename'],
                            'billingparty' => $row['billingparty'],
                            'mintemp' => $row['mintemp'],
                            'maxtemp' => $row['maxtemp'],
                            'drivername' => $row['drivername'],
                            'drivermobile1' => $row['drivermobile1'],
                            'drivermobile2' => $row['drivermobile2'],
                            'startdate' => $starttime,
                            'remark' => $row['remark'],
                            'updated_on' => $row['updatedtime'],
                            'realname' => $row['realname'],
                            'statusdate' => date(speedConstants::DEFAULT_DATETIME, strtotime($row['statusdate'])),
                        );
                    }
                }
            } //end foreach
            //return $getdata;
        }
        return $getdata;
    }

    public function getTripStartTime($tripid) {
        $Query = "SELECT a.statusdate FROM  tripdetail_history as a
      WHERE a.customerno=$this->customerno AND a.tripid = $tripid AND tripstatusid=3 AND a.isdeleted=0 order by triphisid desc limit 1";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            $data = $this->get_nextRow();
            foreach ($data as $row) {
                return $row;
            }
        }
        return null;
    }

    public function get_viewtriprecords() {
        $tripdata = array();
        if (isset($_SESSION['role_modal']) && $_SESSION['role_modal'] == 'Custom') {
            $Query = "select tripusers.addeduserid, ";
        } else {
            $Query = "select ";
        }
        $Query .= "v.vehicleno,v.vehicleid,
td.vehicleid,td.entrytime as startdate,td.triplogno,td.tripstatusid,td.odometer,td.devicelat,td.devicelong,
td.actualhrs,td.actualkms,td.consignor,td.consignee,td.consignorid,td.consigneeid,td.remark,td.perdaykm,
td.etarrivaldate,td.materialtype,td.billingparty,td.routename,td.budgetedkms,td.budgetedhrs,td.mintemp,
td.maxtemp,td.drivername,td.drivermobile1,td.drivermobile2,td.tripid,td.statusdate,
                ts.tripstatus,
                consr.consignorname,con.consigneename,con.checkpointid as conchkid,consr.checkpointid as consrchkid,
                u.unitno
                from tripdetails as td
                inner join vehicle as v on v.vehicleid = td.vehicleid";
        //if($_SESSION['role_modal']=='Tracker'){
        if (isset($_SESSION['role_modal']) && $_SESSION['role_modal'] == 'Custom') {
            $Query .= " inner join tripusers on tripusers.tripid = td.tripid AND tripusers.addeduserid = " . $_SESSION['userid'] . " AND tripusers.isdeleted = 0";
        }
        $Query .= " inner join unit as u on u.uid = v.uid
                left join tripstatus as ts on td.tripstatusid = ts.tripstatusid
                left join tripconsignee as con on con.consid = td.consigneeid
                left join tripconsignor as consr on consr.consrid = td.consignorid
                where td.is_tripend=0 AND td.customerno=$this->customerno AND td.isdeleted=0 ";
        //echo $Query; die;
        /*$Query = "select v.vehicleno,v.vehicleid,
        td.vehicleid,td.entrytime as startdate,td.triplogno,td.tripstatusid,td.odometer,td.devicelat,td.devicelong,
        td.actualhrs,td.actualkms,td.consignor,td.consignee,td.consignorid,td.consigneeid,td.remark,td.perdaykm,
        td.etarrivaldate,td.materialtype,td.billingparty,td.routename,td.budgetedkms,td.budgetedhrs,td.mintemp,
        td.maxtemp,td.drivername,td.drivermobile1,td.drivermobile2,td.tripid,td.statusdate,
        ts.tripstatus,
        consr.consignorname,con.consigneename,con.checkpointid as conchkid,consr.checkpointid as consrchkid,
        u.unitno
        from tripdetails as td
        inner join vehicle as v on v.vehicleid = td.vehicleid
        inner join unit as u on u.uid = v.uid
        left join tripstatus as ts on td.tripstatusid = ts.tripstatusid
        left join tripconsignee as con on con.consid = td.consigneeid
        left join tripconsignor as consr on consr.consrid = td.consignorid
        where td.is_tripend=0 AND td.customerno=$this->customerno AND td.isdeleted=0 ";*/
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            //$result = $this->get_nextRow();
            while ($row = $this->get_nextRow()) {
                $data[] = $row;
            }
            foreach ($data as $row) {
                $startdate = $this->getTripStartTime($row['tripid']);
                $tripdata[] = array(
                    'vehicleno' => $row['vehicleno'],
                    'odometer' => $row['odometer'],
                    'devicelat' => $row['devicelat'],
                    'devicelong' => $row['devicelong'],
                    'actualhrs' => $row['actualhrs'],
                    'actualkms' => $row['actualkms'],
                    'perdaykm' => $row['perdaykm'],
                    'triplogno' => $row['triplogno'],
                    'tripstatusid' => $row['tripstatusid'],
                    'billingparty' => $row['billingparty'],
                    'routename' => $row['routename'],
                    'budgetedkms' => $row['budgetedkms'],
                    'budgetedhrs' => $row['budgetedhrs'],
                    'mintemp' => $row['mintemp'],
                    'maxtemp' => $row['maxtemp'],
                    'drivername' => $row['drivername'],
                    'drivermobile1' => $row['drivermobile1'],
                    'drivermobile2' => $row['drivermobile2'],
                    'tripid' => $row['tripid'],
                    'statusdate' => $row['statusdate'],
                    'startdate' => $startdate,
                    'tripstatus' => $row['tripstatus'],
                    'consignorname' => $row['consignorname'],
                    'consignor' => $row['consignor'],
                    'consigneename' => $row['consigneename'],
                    'consignee' => $row['consignee'],
                    'remark' => $row['remark'],
                    'consignorid' => $row['consignorid'],
                    'consigneeid' => $row['consigneeid'],
                    'conchkid' => $row['conchkid'],
                    'consrchkid' => $row['consrchkid'],
                    'unitno' => $row['unitno'],
                    'vehicleid' => $row['vehicleid'],
                    'etarrivaldate' => $row['etarrivaldate'],
                    'materialtype' => $row['materialtype'],
                );
            }
            return $tripdata;
        }
        return null;
    }

    public function is_triplogno_existing($triplogno) {
        $is_triplogno_exists = 0;
        $Query = "SELECT tripid FROM tripdetails WHERE trim(triplogno) = '" . trim($triplogno) . "'";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            $is_triplogno_exists = 1;
        }
        return $is_triplogno_exists;
    }

    public function gettripidbytriplogno($triplogno) {
        $tripid = 0;
        $Query = "SELECT tripid FROM tripdetails WHERE trim(triplogno) = '" . trim($triplogno) . "'";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        while ($row = $this->get_nextRow()) {
            $tripid = $row['tripid'];
        }
        return $tripid;
    }

    public function closedtripdetails_end($tripid) {
        $tripdata = array();
        $Query = "select odometer,statusdate from tripdetail_history "
            . "  WHERE customerno=$this->customerno AND is_tripend = 1 AND tripstatusid = 10 AND  tripid = $tripid AND isdeleted=0
                order by triphisid desc limit 0,1";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $tripdata[] = array(
                    'lasttripend_odometer' => $row['odometer'],
                    'tripend_date' => $row['statusdate'],
                );
            }
            return $tripdata;
        }
        return null;
    }

    public function closedtripdetails_start($tripid) {
        $tripdata = array();
        $Query = "select odometer,statusdate from tripdetail_history "
            . "  WHERE customerno=$this->customerno AND tripstatusid = 3 AND  tripid = $tripid AND isdeleted=0 order by triphisid asc limit 0,1";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $tripdata[] = array(
                    'starttripend_odometer' => $row['odometer'],
                    'tripstart_date' => $row['statusdate'],
                );
            }
            return $tripdata;
        }
        return null;
    }

    public function getOdometer($location, $date) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $Query = "SELECT * FROM vehiclehistory where lastupdated >= '$date' Order by lastupdated ASC Limit 1 ";
        $result = $db->query($Query);
        if (isset($result) && $result != '') {
            foreach ($result as $row) {
                return $row['odometer'];
            }
        } else {
            return 0;
        }
    }

    public function getodometerform_mysql($vehicleid) {
        $odometer = "";
        $Query = "select odometer from vehicle where vehicleid=$vehicleid AND customerno=$this->customerno";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $odometer = $row['odometer'];
            }
        }
        return $odometer;
    }

    public function is_consignee_exists($consname) {
        $Query = "SELECT * FROM tripconsignee WHERE consigneename='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($consname));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function is_consignor_exists($consrname) {
        $Query = "SELECT * FROM tripconsignor WHERE consignorname='%s' AND isdeleted=0";
        $SQL = sprintf($Query, Sanitise::String($consrname));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function edittripdetailscron($data, $statusid, $istripend) {
        $statusodometer = 0;
        $tripid = $data['tripid'];
        $vehicleid = $data['vehicleid'];
        $tripstatusid = 0;
        $statusdatetimeedit = date('Y-m-d H:i:s');
        $Query = "SELECT tripid,odometer,tripstatusid "
            . "FROM tripdetails "
            . "WHERE customerno=$this->customerno AND tripid=" . $tripid . " AND isdeleted=0 AND is_tripend=0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $tripid = $row['tripid'];
                $tripstatusid = $row['tripstatusid'];
                $odometer = $row['odometer']; //previous odometer
            }
            if ($tripstatusid == $statusid) {
                //If status is same, we need to keep the status odometer same
                $statusodometer = $odometer;
            }
        }
        //Set default status odometer as current odometer in case we are unable to get it from sqlite
        if ($statusodometer == 0) {
            $sql = "select odometer from vehicle where customerno = $this->customerno AND vehicleid = $vehicleid  AND isdeleted=0";
            $SQL = sprintf($sql);
            $this->executeQuery($SQL);
            if ($this->get_rowCount() > 0) {
                while ($row = $this->get_nextRow()) {
                    $statusodometer = $row['odometer'];
                }
            }
        }
        $unitno = $data['unitno'];
        $devicelat = 0;
        $devicelong = 0;
        if (!empty($statusdatetimeedit)) {
            $statusdate = date('Y-m-d', strtotime($statusdatetimeedit));
            $location = "../../customer/" . $this->customerno . "/unitno/$unitno/sqlite/$statusdate.sqlite";
            if (file_exists($location)) {
                $path = "sqlite:$location";
                $db = new PDO($path);
                $statusdatetime2 = $data['statusdatetime'];
                $statusdatequery = "SELECT devicelat,devicelong FROM devicehistory WHERE lastupdated >= '" . $statusdatetimeedit . "'
                    ORDER BY lastupdated ASC LIMIT 1;";
                $result1 = $db->query($statusdatequery);
                if ($result1 !== false) {
                    $arrQueryResult1 = $result1->fetchAll();
                    $devicelat = isset($arrQueryResult1[0]['devicelat']) ? $arrQueryResult1[0]['devicelat'] : 0;
                    $devicelong = isset($arrQueryResult1[0]['devicelong']) ? $arrQueryResult1[0]['devicelong'] : 0;
                }
            }
        }
        $Query = "update tripdetails set "
        . "vehicleno='" . $data['vehicleno'] . "' ,"
        . "vehicleid='" . $data['vehicleid'] . "' ,"
        . "triplogno='" . $data['triplogno'] . "' ,"
        . "tripstatusid ='" . $statusid . "' ,"
        . "odometer ='" . $statusodometer . "' ,"
        . "devicelat ='" . $devicelat . "' ,"
        . "devicelong ='" . $devicelong . "' ,"
        . "statusdate ='" . $statusdatetimeedit . "' ,"
        . "updatedtime='" . $this->today . "', updated_by='" . $this->userid
            . "' where tripid=" . $data['tripid'];
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($tripstatusid != $statusid) {
            //insert the current record in the history if status changes
            $Query = "Insert into tripdetail_history "
                . " (customerno,actualhrs,actualkms,tripid,vehicleno,vehicleid,triplogno,tripstatusid"
                . " ,odometer,devicelat,devicelong,statusdate,routename,budgetedkms,budgetedhrs"
                . " ,consignor,consignee,consignorid,consigneeid,billingparty"
                . " ,mintemp,maxtemp,drivername,drivermobile1,drivermobile2,etarrivaldate,materialtype,is_tripend,entrytime,addedby) "
                . " VALUES($this->customerno,'%s','%s','%s','%s','%s','%s','%s' "
                . " ,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s' "
                . " ,'%s','%s','%s','%s','%s','%s','%s','%s','$this->today',$this->userid)";
            $SQL = sprintf($Query
                , Sanitise::String($data['actualhrs'])
                , Sanitise::String($data['actualkms'])
                , Sanitise::String($tripid)
                , Sanitise::String($data['vehicleno'])
                , Sanitise::String($data['vehicleid'])
                , Sanitise::String($data['triplogno'])
                , Sanitise::String($statusid)
                , Sanitise::String($statusodometer)
                , Sanitise::String($devicelat)
                , Sanitise::String($devicelong)
                , Sanitise::String($statusdatetimeedit)
                , Sanitise::String($data['routename'])
                , Sanitise::String($data['budgetedkms'])
                , Sanitise::String($data['budgetedhrs'])
                , Sanitise::String($data['consignor'])
                , Sanitise::String($data['consignee'])
                , Sanitise::String($data['consignorid'])
                , Sanitise::String($data['consigneeid'])
                , Sanitise::String($data['billingparty'])
                , Sanitise::String($data['mintemp'])
                , Sanitise::String($data['maxtemp'])
                , Sanitise::String($data['drivername'])
                , Sanitise::String($data['drivermobile1'])
                , Sanitise::String($data['drivermobile2'])
                , Sanitise::String($data['etarrivaldate'])
                , Sanitise::String($data['materialtype'])
                , Sanitise::String($istripend)
            );
            $this->executeQuery($SQL);
        }
        $minTemp = Sanitise::String($data['mintemp']);
        $maxTemp = Sanitise::String($data['maxtemp']);
        //Update the temperature values in the vehicle table
        /* TODO:
        //Check customer has temp sensor & update according to no of sensors
        $noOfTempSensors = '';
        if (isset($noOfTempSensors) && !empty($noOfTempSensors)) {
        $Query = "UPDATE vehicle SET ";
        switch ($noOfTempSensors) {
        case 4:$Query .= "temp4_min=$minTemp,temp4_max=$minTemp,";
        case 3:$Query .= "temp3_min=$minTemp,temp3_max=$minTemp,";
        case 2:$Query .= "temp2_min=$minTemp,temp2_max=$minTemp,";
        case 1:$Query .= "temp1_min=$minTemp,temp1_max=$minTemp ";
        }
        $Query .= "WHERE vehicleid = $vehicleid";
        }
         */
        $Query = "UPDATE vehicle SET temp1_min=$minTemp,temp2_min=$minTemp, temp1_max=$maxTemp,temp2_max=$maxTemp WHERE vehicleid = $vehicleid";
        $this->executeQuery($Query);
        if (isset($istripend)) {
            if ($istripend == '1') {
                $enddate = date("Y-m-d H:i:s");
                $today = date("Y-m-d H:i:s");
                $resulteditdata = $this->gettripdetailsedit($tripid);
                if (!empty($resulteditdata)) {
                    $date = date('Y-m-d');
                    foreach ($resulteditdata as $thisdata) {
                        $firstodometer = 0;
                        $lastodometer = 0;
                        $lastodometer = $thisdata->vehicleodometer; // last odometer
                        $firstodometer = $thisdata->loadingodometer; // first odometer
                        if ($lastodometer < $firstodometer) {
                            $lastodometermax = $this->GetOdometerMax($date, $data['unitno']);
                            $lastodometer = $lastodometermax + $lastodometer;
                        }
                        $totaldistance = $lastodometer - $firstodometer;
                        $tripstartdetails = $this->closedtripdetails_start($tripid);
                        $tripstart_date = $tripstartdetails[0]['tripstart_date'];
                        $actualhrs = round((strtotime($enddate) - strtotime($tripstart_date)) / (60 * 60));
                        if ($totaldistance != 0) {
                            $actualkm = $totaldistance / 1000;
                        } else {
                            $actualkm = 0;
                        }
                    }
                }
                $query = "update tripdetails set actualhrs = '" . $actualhrs . "',  actualkms = '" . $actualkm . "'
                where tripid =" . $tripid;
                $SQL = sprintf($query);
                $this->executeQuery($SQL);
                $query = "update tripdetail_history set is_tripend = 1 where tripid = " . $tripid;
                $SQL = sprintf($query);
                $this->executeQuery($SQL);
                //Reset the temperature values in the vehicle table to default 1 and 50
                $Query = "UPDATE vehicle "
                    . "SET temp1_min=1,temp1_max=50 "
                    . ",temp2_min=1, temp2_max=50 "
                    . ",temp3_min=1, temp3_max=50 "
                    . ",temp4_min=1, temp4_max=50 "
                    . "WHERE vehicleid = $vehicleid";
                $this->executeQuery($Query);
            }
        }
    }

    function deMapDataForRTD($data) {
        // print_r($data); die;
        $tripid = $data['tripid'];
        $vehicleid = $data['vehicleid'];
        //$sql_tripuser = "select addeduserid from tripusers where customerno = $this->customerno AND tripid = $tripid  AND isdeleted=0";
        $sql_trip = "select tripusers.addeduserid as tripuserid, vehicle.groupid from tripdetails
                            INNER JOIN vehicle on tripdetails.vehicleid = vehicle.vehicleid
                            INNER JOIN tripusers on tripusers.tripid = tripdetails.tripid
                            where tripusers.isdeleted = 0 AND tripusers.tripid = $tripid";
        $isValidQuery = sprintf($sql_trip);
        $this->executeQuery($isValidQuery);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $tripuserid = $row['tripuserid'];
                $groupid = $row['groupid'];
                if ((isset($tripuserid) && $tripuserid != "") && (isset($groupid) && $groupid != "")) {
                    $Query2 = "UPDATE vehicleusermapping SET isdeleted='%d',
                                     updated_by = '%d',
                                    updated_on = '%s'
                                    WHERE vehicleid = " . $data['vehicleid'] . " AND userid = " . $tripuserid;
                    $SQL2 = sprintf($Query2
                        , Sanitise::Long(1)
                        , Sanitise::Long($this->userid)
                        , Sanitise::DateTime($this->today)
                    );
                    $this->executeQuery($SQL2);
                    $Query = "SELECT count(vehmapid) as count1 FROM vehicleusermapping WHERE groupid=" . $groupid . " AND userid=" . $tripuserid . " AND isdeleted=0";
                    $SQL = sprintf($Query);
                    $this->executeQuery($SQL);
                    if ($this->get_rowCount() > 0) {
                        $row = $this->get_nextRow();
                        $c = $row['count1'];
                    }
                    if (isset($c) && $c == 0) {
                        $Query = "UPDATE groupman SET isdeleted='%d',`timestamp` = '%s' WHERE groupid = " . $groupid . " AND userid = " . $tripuserid;
                        $SQL = sprintf($Query
                            , Sanitise::Long(1)
                            , Sanitise::DateTime($this->today)
                        );
                        $this->executeQuery($SQL);
                    }
                }
            }
        }
    }

    public function addTripConsignor($objConsignor) {
        $consignorId = 0;
        try {
            $pdo = $this->CreatePDOConn();
            $sp_params = "'" . $objConsignor->consignorname . "'"
            . ",'" . $objConsignor->email . "'"
            . ",'" . $objConsignor->phone . "'"
            . ",'" . $objConsignor->checkpointid . "'"
            . ",'" . $objConsignor->customerno . "'"
                . ",@currentConsignorId";
            $queryCallSP = "CALL " . speedConstants::SP_INSERT_TRIP_CONSIGNOR . "($sp_params)";
            $pdo->query($queryCallSP);
            $result = $pdo->query('SELECT @currentConsignorId')->fetch(PDO::FETCH_ASSOC);
            $consignorId = $result["@currentConsignorId"];
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, 0, "Trip", __FUNCTION__);
        }
        return $consignorId;
    }

    public function addTripConsignee($objConsignee) {
        $consigneeId = 0;
        try {
            $pdo = $this->CreatePDOConn();
            $sp_params = "'" . $objConsignee->consigneename . "'"
            . ",'" . $objConsignee->email . "'"
            . ",'" . $objConsignee->phone . "'"
            . ",'" . $objConsignee->checkpointid . "'"
            . ",'" . $objConsignee->customerno . "'"
                . ",@currentConsigneeId";
            $queryCallSP = "CALL " . speedConstants::SP_INSERT_TRIP_CONSIGNEE . "($sp_params)";
            $pdo->query($queryCallSP);
            $result = $pdo->query('SELECT @currentConsigneeId')->fetch(PDO::FETCH_ASSOC);
            $consigneeId = $result["@currentConsigneeId"];
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, $this->userid, "Trip", __FUNCTION__);
        }
        return $consigneeId;
    }

    public function checkTripMemoNo($triplogno) {
        $is_triplogno_exists = 0;
        $Query = "SELECT tripid FROM tripdetails WHERE trim(triplogno) = '" . trim($triplogno) . "'";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $is_triplogno_exists = $row['tripid'];
            }
            //$is_triplogno_exists = 1;
        }
        return $is_triplogno_exists;
    }

    public function insertTripLrMapping($objTrip) {
        try {
            $pdo = $this->CreatePDOConn();
            $sp_params = "'" . $objTrip->tripid . "'"
            . ",'" . $objTrip->lrno . "'"
            . ",'" . $objTrip->lrdatetime . "'"
            . ",'" . $objTrip->consignorid . "'"
            . ",'" . $objTrip->consigneeid . "'"
            . ",'" . $objTrip->customerno . "'"
            . ",'" . $objTrip->userid . "'"
            . ",'" . $this->today . "'";
            $queryCallSP = "CALL " . speedConstants::SP_INSERT_TRIP_LR_MAPPING . "($sp_params)";
            $pdo->query($queryCallSP);
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, 0, "Trip", __FUNCTION__);
        }
    }

    public function getTripDroppoints($objTrip) {
        $arrDroppoints = null;
        try {
            $pdo = $this->CreatePDOConn();
            $sp_params = "'" . $objTrip->tripid . "'"
            . ",'" . $objTrip->customerno . "'";
            $queryCallSP = "CALL " . speedConstants::SP_GET_TRIP_DROPPOINTS . "($sp_params)";
            $arrDroppoints = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);;
        } catch (Exception $ex) {
            $log = new Log();
            $log->createlog($this->customerno, $ex, 0, "Trip", __FUNCTION__);
        }
        return $arrDroppoints;
    }
}
?>
