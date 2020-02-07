<?php
ini_set('max_execution_time', 1000);

if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
include_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';

class CheckpointManager extends VersionedManager {
    public function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function PrepareSP($sp_name, $sp_params) {
        return "call " . $sp_name . "(" . $sp_params . ");";
    }

    public function SaveCheckpoint($checkpoint, $userid) {
        $checkpointId = 0;
        if (!isset($checkpoint->checkpointid)) {
            $checkpointId = $this->Insert($checkpoint, $userid);
        } else {
            $checkpointId = $this->Update($checkpoint, $userid);
        }
        return $checkpointId;
    }

    public function SaveCheckpointType($checkpoint, $userid) {
        $checkpointId = 0;
        if (!isset($checkpoint->ctid)) {
            $checkpointId = $this->InsertType($checkpoint, $userid);
        } else {
            $checkpointId = $this->UpdateType($checkpoint, $userid);
        }
        return $checkpointId;
    }

    private function Insert($checkpoint, $userid) {
        $checkpointId = 0;
        $checkpoint->chktype = isset($checkpoint->chktype) ? $checkpoint->chktype : 0;
        $today = date('Y-m-d H:i:s');
        $Query = "  INSERT INTO checkpoint (`customerno`
                        ,`cname`
                        ,`chktype`
                        ,`cadd`
                        ,`phoneno`
                        ,`email`
                        ,`crad`
                        ,`cgeolat`
                        ,`cgeolong`
                        ,`isdeleted`
                        ,`userid`
                        , `eta`
                        , `eta_starttime`)
                    VALUES (%d,'%s',%d,'%s','%s','%s',%f,%f,%f,0,%d,'%s','%s')";
        $SQL = sprintf($Query, $this->_Customerno, Sanitise::String($checkpoint->cname), Sanitise::Long($checkpoint->chktype), Sanitise::String($checkpoint->cadd), Sanitise::String($checkpoint->cphone), Sanitise::String($checkpoint->cemail), Sanitise::Float($checkpoint->crad), Sanitise::Float($checkpoint->cgeolat), Sanitise::Float($checkpoint->cgeolong), Sanitise::Long($userid), Sanitise::String($checkpoint->eta), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($SQL);
        $checkpoint->checkpointid = $this->_databaseManager->get_insertedId();
        $insertQuery = "INSERT INTO etamanage(checkpointid, customerno, eta, starttime, timestamp)VALUES(%d,%d,'%s','%s','%s')";
        $insertSQL = sprintf($insertQuery, Sanitise::Long($checkpoint->checkpointid), $this->_Customerno, Sanitise::String($checkpoint->eta), Sanitise::DateTime($today), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($insertSQL);
        if ($checkpoint->vehicles) {
            $Query = "INSERT INTO checkpointmanage (`checkpointid`,`vehicleid`,`customerno`,`conflictstatus`,`userid`,`isdeleted`) VALUES (%d,'%d','%d','1','%d','0')";
            foreach ($checkpoint->vehicles as $vehicle) {
                $SQL = sprintf($Query, Sanitise::Long($checkpoint->checkpointid), Sanitise::Long($vehicle), $this->_Customerno, Sanitise::Long($userid));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        $lat = floor($checkpoint->cgeolat);
        $long = floor($checkpoint->cgeolong);
        $Query = "INSERT INTO " . DB_PARENT . ".geotest(`location`,`lat`,`long`,`latfloor`,`longfloor`,`customerno`,`checkpointid`) VALUES ('%s',%f,%f,%d,%d,%d,%d)";
        $SQL = sprintf($Query, Sanitise::String($checkpoint->cname), Sanitise::Float($checkpoint->cgeolat), Sanitise::Float($checkpoint->cgeolong), Sanitise::Long($lat), Sanitise::Long($long), $this->_Customerno, $checkpoint->checkpointid);
        $this->_databaseManager->executeQuery($SQL);
        $checkpointId = $checkpoint->checkpointid;
        return $checkpointId;
    }

    private function InsertType($checkpoint, $userid) {
        $checkpointId = 0;
        $today = date('Y-m-d H:i:s');
        $Query = "  INSERT INTO `checkpoint_type` (`customerno`
                            ,`name`
                            ,`createdby`
                            ,`createdon`)
                    VALUES (%d,'%s',%d,'%s')";
        $SQL = sprintf($Query, $this->_Customerno, Sanitise::String($checkpoint->name), Sanitise::Long($userid), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($SQL);
        $checkpointId = $this->_databaseManager->get_insertedId();
        return $checkpointId;
    }

    public function CreateCheck($cname, $cadd, $clat, $clong, $userid, $vehicleid, $STime_pop) {
        $Query = "SELECT checkpoint.cname FROM checkpoint WHERE customerno=%d AND userid =%d AND cname='%s'";
        $SQL = sprintf($Query, $this->_Customerno, $userid, $cname);
        $this->_databaseManager->executeQuery($SQL);
        if (!$this->_databaseManager->get_rowCount() > 0) {
            $Query = "INSERT INTO checkpoint(`customerno`,`cname`,`cadd`,`crad`,`cgeolat`,`cgeolong`,`isdeleted`,`userid`) VALUES(%d,'%s','%s',%f,%f,%f,0,%d)";
            $SQL = sprintf($Query, $this->_Customerno, Sanitise::String($cname), Sanitise::String($cadd), Sanitise::Float(1), Sanitise::Float($clat), Sanitise::Float($clong), Sanitise::Long($userid));
            $this->_databaseManager->executeQuery($SQL);
            $checkpointid = $this->_databaseManager->get_insertedId();
            if ($vehicleid) {
                $Query = "INSERT INTO checkpointmanage (`checkpointid`,`vehicleid`,`customerno`,`conflictstatus`,`userid`,`isdeleted`) VALUES (%d,'%d','%d','1','%d','0')";
                $SQL1 = sprintf($Query, Sanitise::Long($checkpointid), Sanitise::Long($vehicleid), $this->_Customerno, Sanitise::Long($userid));
                $this->_databaseManager->executeQuery($SQL1);
                echo "<span style='color:green;'>Checkpoint is added Successfully.</span><br/><br/>";
                echo '<button data-dismiss="modal" class="btn btn-success" onclick="jQuery(\'.popup\').hide();">Close</button>';
            }
            /* ak added below 2 conditions */
            if ($STime_pop) {
                $today = date('Y-m-d H:i:s');
                $insertQuery = "INSERT INTO etamanage(checkpointid, customerno, eta, starttime, timestamp)VALUES(%d,%d,'%s','%s','%s')";
                $insertSQL = sprintf($insertQuery, Sanitise::Long($checkpointid), $this->_Customerno, Sanitise::String($STime_pop), Sanitise::DateTime($today), Sanitise::DateTime($today));
                $this->_databaseManager->executeQuery($insertSQL);
            }
            $f_lat = floor($clat);
            $f_long = floor($clong);
            $Query = "INSERT INTO " . DB_PARENT . ".geotest(`location`,`lat`,`long`,`latfloor`,`longfloor`,`customerno`,`checkpointid`) VALUES ('%s',%f,%f,%d,%d,%d,%d)";
            $SQL = sprintf($Query, Sanitise::String($cname), Sanitise::Float($clat), Sanitise::Float($clong), Sanitise::Long($f_lat), Sanitise::Long($f_long), $this->_Customerno, $checkpointid);
            $this->_databaseManager->executeQuery($SQL);
            /**/
        } else {
            echo "Chackpoint name already exists, Please try another name<br/><br/>";
            echo '<button data-dismiss="modal" class="btn btn-success">Close</button>';
        }
    }

    public function CheckName($cname, $userid, $test) {
        $Query = "SELECT cname FROM checkpoint WHERE customerno=%d AND userid=%d AND cname='%s'";
        $SQL = sprintf($Query, $this->_Customerno, $userid, Sanitise::String($cname));
        $this->_databaseManager->executeQuery($SQL);
        if (!$this->_databaseManager->get_rowCount() > 0) {
            echo "<span style='color:green;'>Checkpoint name is available</span>";
            echo "<br/>";
            echo "<br/>";
            echo "<input type='button' class='btn btn-success' name='Add Checkpoint' id='$test' onclick='createcheck($test)' value='Add Checkpoint'/>";
        } else {
            echo "Checkpoint name is already present";
        }
    }

    public function CheckName_exists($cname, $customerno = null) {
        if (isset($customerno)) {
            $customerno = $customerno;
        } else {
            $customerno = $this->_Customerno;
        }
        $Query = "SELECT cname FROM checkpoint WHERE customerno=%d AND cname='%s' and isdeleted=0";
        $SQL = sprintf($Query, $customerno, Sanitise::String($cname));
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function CheckLocation($lat, $long, $userid) {
        $Query = "SELECT checkpoint.cgeolat,checkpoint.cgeolong,checkpointmanage.customerno,checkpointmanage.userid from checkpoint
            INNER JOIN checkpointmanage ON checkpointmanage.checkpointid = checkpoint.checkpointid
            AND checkpoint.customerno=%d AND checkpoint.userid=%d AND checkpoint.cgeolat LIKE '%f' AND checkpoint.cgeolong LIKE '%f'";
        echo $SQL = sprintf($Query, $this->_Customerno, $userid, Sanitise::Float($lat), Sanitise::Float($long));
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            return true;
        }
    }

    public function save_enhcheckpoint($checkpoint, $userid) {
        if ($checkpoint->vehicles) {
            $Query = "INSERT INTO enh_checkpoint(`checkpointid`,`vehicleid`,`com_details`,`com_type`,`userid`,`customerno`,`timestamp`,`isdeleted`) VALUES (%d,%d,'%s',%d,%d,%d,'%s','0')";
            $date = new Date();
            $todays = $date->MySQLNow();
            foreach ($checkpoint->vehicles as $vehicle) {
                if ($checkpoint->emails) {
                    $emailarray = explode(",", $checkpoint->emails);
                    foreach ($emailarray as $email) {
                        $emailSQL = sprintf($Query, Sanitise::Long($checkpoint->checkpointid), Sanitise::Long($vehicle), Sanitise::String($email), '0', Sanitise::Long($userid), $this->_Customerno, Sanitise::DateTime($todays));
                        $this->_databaseManager->executeQuery($emailSQL);
                        //$checkpoint->enh_checkpointid = $this->_databaseManager->get_insertedId();
                    }
                }
                if ($checkpoint->phones) {
                    $phonearray = explode(",", $checkpoint->phones);
                    foreach ($phonearray as $phone) {
                        $phoneSQL = sprintf($Query, Sanitise::Long($checkpoint->checkpointid), Sanitise::Long($vehicle), Sanitise::String($phone), '1', Sanitise::Long($userid), $this->_Customerno, Sanitise::DateTime($todays));
                        $this->_databaseManager->executeQuery($phoneSQL);
                        //$checkpoint->enh_checkpointid = $this->_databaseManager->get_insertedId();
                    }
                }
            }
        }
    }

    public function edit_enhcheckpoint($checkpoint, $userid) {
        $Query = "Update enh_checkpoint Set `com_details`='%s',`userid`=%d WHERE enh_checkpoint = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::String($checkpoint->comdet), Sanitise::Long($userid), Sanitise::Long($checkpoint->enh_checkpointid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function get_checkpoint($id) {
        $checkpoint = null;
        $Query = "SELECT * FROM `checkpoint` where customerno=%d AND `checkpointid`='%d' LIMIT 1";
        $checkpointDetailsQuery = sprintf($Query, $this->_Customerno, Validator::escapeCharacters($id));
        $this->_databaseManager->executeQuery($checkpointDetailsQuery);
        if ($row = $this->_databaseManager->get_nextRow()) {
            $checkpoint = new VOCheckpoint();
            $checkpoint->checkpointid = $row['checkpointid'];
            $checkpoint->cname = $row['cname'];
            $checkpoint->chktype = isset($row['chktype']) ? $row['chktype'] : 0;
            $checkpoint->cphone = $row['phoneno'];
            $checkpoint->cemail = $row['email'];
            $checkpoint->cadd = $row['cadd'];
            $checkpoint->cgeolat = $row['cgeolat'];
            $checkpoint->cgeolong = $row['cgeolong'];
            $checkpoint->crad = $row['crad'];
            $checkpoint->eta = $row['eta'];
            return $checkpoint;
        }
        return null;
    }

    public function get_checkpointtype($id) {
        $checkpoint = null;
        $Query = "  SELECT  ctid
                            ,name
                    FROM    `checkpoint_type`
                    where   (customerno = %d OR customerno = 0)
                    AND     `ctid` = %d
                    LIMIT   1";
        $checkpointDetailsQuery = sprintf($Query, $this->_Customerno, Validator::escapeCharacters($id));
        $this->_databaseManager->executeQuery($checkpointDetailsQuery);
        if ($row = $this->_databaseManager->get_nextRow()) {
            $checkpoint = new VOCheckpoint();
            $checkpoint->ctid = $row['ctid'];
            $checkpoint->name = $row['name'];
            return $checkpoint;
        }
        return null;
    }

    public function get_checkpointcz($id) {
        $checkpoint = null;
        $Query = "SELECT * FROM `circularzone` where customerno=%d AND `checkpointid`='%d' LIMIT 1";
        $checkpointDetailsQuery = sprintf($Query, $this->_Customerno, Validator::escapeCharacters($id));
        $this->_databaseManager->executeQuery($checkpointDetailsQuery);
        if ($row = $this->_databaseManager->get_nextRow()) {
            $checkpoint = new VOCheckpoint();
            $checkpoint->checkpointid = $row['checkpointid'];
            $checkpoint->cname = $row['cname'];
            $checkpoint->cphone = $row['phoneno'];
            $checkpoint->cemail = $row['email'];
            $checkpoint->cadd = $row['cadd'];
            $checkpoint->cgeolat = $row['cgeolat'];
            $checkpoint->cgeolong = $row['cgeolong'];
            $checkpoint->crad = $row['crad'];
            $checkpoint->eta = $row['eta'];
            $checkpoint->groupid = $row['groupid'];
            return $checkpoint;
        }
        return null;
    }

    public function get_checkpointname($id, $type = NULL) {
        $checkpoint = null;
        if (isset($type) && $type == '2') {
            $Query = "SELECT name AS cname FROM `checkpoint_type` where customerno IN (%d,0) AND `ctid`='%d' LIMIT 1";
        } elseif ((isset($type) && $type == '1') || $type == NULL) {
            $Query = "SELECT cname FROM `checkpoint` where customerno=%d AND `checkpointid`='%d' LIMIT 1";
        }
        $checkpointDetailsQuery = sprintf($Query, $this->_Customerno, Validator::escapeCharacters($id));
        $this->_databaseManager->executeQuery($checkpointDetailsQuery);
        if ($row = $this->_databaseManager->get_nextRow()) {
            $cname = $row['cname'];
            return $cname;
        }
        return null;
    }

    public function get_customer_checkpoints($customerno) {
        $checkpoint = array();
        $Query = "SELECT cname,checkpointid FROM `checkpoint` where customerno=$customerno";
        $this->_databaseManager->executeQuery($Query);
        while ($row = $this->_databaseManager->get_nextRow()) {
            $checkpoint[$row['checkpointid']] = $row['cname'];
        }
        return $checkpoint;
    }

    public function get_enhcheckpoint($id) {
        $checkpoints = array();
        $Query = "SELECT *,enh_checkpoint.checkpointid,enh_checkpoint.vehicleid FROM `enh_checkpoint`
            LEFT OUTER JOIN checkpoint ON checkpoint.checkpointid = enh_checkpoint.checkpointid
            LEFT OUTER JOIN vehicle ON vehicle.vehicleid = enh_checkpoint.vehicleid
            WHERE enh_checkpoint.customerno=%d AND enh_checkpoint.enh_checkpoint=%d AND checkpoint.isdeleted=0";
        $checkpointsQuery = sprintf($Query, $this->_Customerno, $id);
        $this->_databaseManager->executeQuery($checkpointsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VOCheckpoint();
                $checkpoint->enh_checkpointid = $row['enh_checkpoint'];
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->cname = $row['cname'];
                $checkpoint->vehicleid = $row['vehicleid'];
                $checkpoint->vehicleno = $row['vehicleno'];
                $checkpoint->comdet = $row['com_details'];
                $checkpoint->type = $row['com_type'];
            }
            return $checkpoint;
        }
        return null;
    }

    public function getcheckpointsforcustomer($checkpointName = null) {
        $searchCondition = '';
        if (isset($checkpointName)) {
            $searchCondition = " AND trim(cname) LIKE '" . $checkpointName . "'";
        }
        $checkpoints = array();
        $Query = "  SELECT  *
                            ,checkpoint.checkpointid
                            ,checkpointmanage.vehicleid
                            ,checkpoint_type.name as chktype
                    FROM    `checkpoint`
                    LEFT OUTER JOIN checkpointmanage ON checkpointmanage.checkpointid = checkpoint.checkpointid
                    LEFT OUTER JOIN vehicle ON vehicle.vehicleid = checkpointmanage.vehicleid
                    LEFT OUTER JOIN checkpoint_type ON checkpoint_type.ctid = `checkpoint`.chktype
                    WHERE   checkpoint.customerno = $this->_Customerno
                    AND     checkpoint.isdeleted = 0 " . $searchCondition . "
                    GROUP BY checkpoint.checkpointid
                    ORDER by checkpoint.cname";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VOCheckpoint();
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->cname = $row['cname'];
                $checkpoint->chktype = isset($row['chktype']) ? $row['chktype'] : '';
                $checkpoint->completeaddress = $row['cadd'];
                $checkpoint->cgeolat = $row['cgeolat'];
                $checkpoint->cgeolong = $row['cgeolong'];
                $checkpoint->crad = $row['crad'];
                $checkpoint->eta = $row['eta'];
                $checkpoint->vehicleid = $row['vehicleid'];
                $checkpoint->phoneno = $row['phoneno'];
                $checkpoint->email = $row['email'];
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;
        }
        return null;
    }

    public function getcheckpointtypesforcustomer($checkpointName = null) {
        $searchCondition = '';
        if (isset($checkpointName)) {
            $searchCondition = " AND name LIKE '" . $checkpointName . "'";
        }
        $checkpoints = array();
        $Query = "  SELECT  ctid
                            ,name
                            ,customerno
                    FROM    `checkpoint_type`
                    WHERE   (customerno = $this->_Customerno  OR customerno = 0)
                    AND     isdeleted = 0 "
            . $searchCondition . "
                    GROUP BY ctid ORDER by name";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VOCheckpoint();
                $checkpoint->ctid = $row['ctid'];
                $checkpoint->name = $row['name'];
                $checkpoint->customerno = $row['customerno'];

                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;
        }
        return null;
    }

    public function getcheckpointsforcustomercz($groupid, $checkpointName = null) {
        $searchCondition = '';
        if (isset($checkpointName) && $checkpointName != null) {
            $searchCondition = " AND cname LIKE '" . $checkpointName . "'";
        }
        if ($groupid != 0) {
            $searchCondition .= " AND checkpoint.groupid =" . $groupid;
        }

        $checkpoints = array();
        $Query = "SELECT *,checkpoint.checkpointid,`group`.groupname,`group`.groupid FROM `circularzone` as `checkpoint`
             LEFT OUTER JOIN `group` ON `group`.groupid = checkpoint.groupid
            WHERE checkpoint.customerno=$this->_Customerno  AND checkpoint.isdeleted=0 " . $searchCondition . " GROUP BY checkpoint.checkpointid ORDER by checkpoint.cname";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VOCheckpoint();
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->cname = $row['cname'];
                $checkpoint->completeaddress = $row['cadd'];
                $checkpoint->cgeolat = $row['cgeolat'];
                $checkpoint->cgeolong = $row['cgeolong'];
                $checkpoint->crad = $row['crad'];
                $checkpoint->eta = $row['eta'];
                $checkpoint->groupid = $row['groupid'];
                $checkpoint->groupname = $row['groupname'];
                $checkpoint->phoneno = $row['phoneno'];
                $checkpoint->email = $row['email'];
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;
        }
        return null;
    }

    public function getcheckpointsforcustomer_byId($srh) {
        $checkpoints = array();
        $Query = "SELECT *,checkpoint.checkpointid,checkpointmanage.vehicleid FROM `checkpoint`
            LEFT OUTER JOIN checkpointmanage ON checkpointmanage.checkpointid = checkpoint.checkpointid
            LEFT OUTER JOIN vehicle ON vehicle.vehicleid = checkpointmanage.vehicleid
            WHERE checkpoint.customerno=%d AND checkpoint.cname LIKE '%s' AND checkpoint.isdeleted=0 GROUP BY checkpoint.checkpointid ORDER by checkpoint.cname";
        $checkpointsQuery = sprintf($Query, $this->_Customerno, Sanitise::String($srh));
        $this->_databaseManager->executeQuery($checkpointsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VOCheckpoint();
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->cname = $row['cname'];
                $checkpoint->completeaddress = $row['cadd'];
                $checkpoint->cgeolat = $row['cgeolat'];
                $checkpoint->cgeolong = $row['cgeolong'];
                $checkpoint->crad = $row['crad'];
                $checkpoint->eta = $row['eta'];
                $checkpoint->vehicleid = $row['vehicleid'];
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;
        }
        return null;
    }

    public function getenhcheckpointsforcustomer() {
        $checkpoints = array();
        $Query = "SELECT *,enh_checkpoint.checkpointid,enh_checkpoint.vehicleid FROM `enh_checkpoint`
            LEFT OUTER JOIN checkpoint ON checkpoint.checkpointid = enh_checkpoint.checkpointid
            LEFT OUTER JOIN vehicle ON vehicle.vehicleid = enh_checkpoint.vehicleid
            WHERE checkpoint.customerno=%d AND checkpoint.isdeleted=0 AND enh_checkpoint.isdeleted=0";
        $checkpointsQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($checkpointsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VOCheckpoint();
                $checkpoint->enh_checkpointid = $row['enh_checkpoint'];
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->cname = $row['cname'];
                $checkpoint->vehicleid = $row['vehicleid'];
                $checkpoint->vehicleno = $row['vehicleno'];
                $checkpoint->comdet = $row['com_details'];
                $checkpoint->comtype = $row['com_type'];
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;
        }
        return null;
    }

    public function get_checkpoits_with_vehicle_inside($grouped_vehicles_csv = null) {
        $checkpoints = array();
        $Query = "SELECT count(*) as count,cname FROM checkpointmanage
		INNER JOIN checkpoint on checkpoint.checkpointid=checkpointmanage.checkpointid
		INNER JOIN vehicle on vehicle.vehicleid=checkpointmanage.vehicleid
		WHERE checkpointmanage.customerno=%d AND checkpointmanage.isdeleted=0  and  checkpointmanage.conflictstatus=0 ";
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
        }
        if ($grouped_vehicles_csv != null) {
            $Query .= " AND vehicle.vehicleid in ($grouped_vehicles_csv)";
        }
        $Query .= " group by checkpointmanage.checkpointid";
        if ($_SESSION['groupid'] != 0) {
            $checkpointsQuery = sprintf($Query, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $checkpointsQuery = sprintf($Query, $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($checkpointsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint['cname'] = $row['cname'];
                $checkpoint['count'] = $row['count'];
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;
        }
        return null;
    }

    /*    public function getcheckpointsforview()
    {
    $checkpoints = array();
    $Query = "SELECT * FROM `checkpoint` where checkpoint.customerno=%d";
    $checkpointsQuery = sprintf($Query,$this->_Customerno);
    $this->_databaseManager->executeQuery($checkpointsQuery);
    if ($this->_databaseManager->get_rowCount() > 0)
    {
    while ($row = $this->_databaseManager->get_nextRow())
    {
    $checkpoint = new VOCheckpoint();
    $checkpoint->checkpointid = $row['checkpointid'];
    $checkpoint->cname = $row['cname'];
    $checkpoint->cadd1 = $row['cadd1'];
    $checkpoint->cadd2 = $row['cadd2'];
    $checkpoint->cadd3 = $row['cadd3'];
    $checkpoint->ccity = $row['ccity'];
    $checkpoint->cstate = $row['cstate'];
    $checkpoint->czip = $row['czip'];
    $checkpoint->cgeolat = $row['cgeolat'];
    $checkpoint->cgeolong = $row['cgeolong'];
    //Adding Individual Address Details to a single variable
    if($checkpoint->cadd1!=NULL)
    {
    $checkpoint->completeaddress = $checkpoint->cadd1;
    }
    if($checkpoint->cadd2!=NULL)
    {
    $checkpoint->completeaddress .= " ".$checkpoint->cadd2;
    }
    if($checkpoint->cadd3!=NULL)
    {
    $checkpoint->completeaddress .= " ".$checkpoint->cadd3;
    }
    $checkpoints[] = $checkpoint;
    }
    return $checkpoints;
    }
    return null;
    }
     */

    private function Update($checkpoint, $userid) {
        $today = date('Y-m-d H:i:s');
        $checkpoint->chktype = isset($checkpoint->chktype) ? $checkpoint->chktype : 0;
        $startQuery = "SELECT eta_starttime FROM checkpoint WHERE customerno=%d AND checkpointid=%d";
        $startSQL = sprintf($startQuery, $this->_Customerno, $checkpoint->checkpointid);
        $this->_databaseManager->executeQuery($startSQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextRow();
            $checkpoint->eta_starttime = $row['eta_starttime'];
        }
        $Query = "Update checkpoint Set `cname`='%s',`chktype` = %d,`phoneno`='%s',`email`='%s',`crad`='%f',`cgeolat`='%f',`cgeolong`='%f',`userid`=%d, `eta`='%s', `eta_starttime`='%s' WHERE checkpointid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::String($checkpoint->cname), Sanitise::Long($checkpoint->chktype), Sanitise::String($checkpoint->cphone), Sanitise::String($checkpoint->cemail), Sanitise::Float($checkpoint->crad), Sanitise::Float($checkpoint->cgeolat), Sanitise::Float($checkpoint->cgeolong), Sanitise::Long($userid), Sanitise::String($checkpoint->eta), Sanitise::DateTime($today), Sanitise::Long($checkpoint->checkpointid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $updateETA = "UPDATE etamanage SET endtime = '%s' WHERE customerno=%d AND checkpointid=%d Order by etaid DESC Limit 1";
        $updateETASql = sprintf($updateETA, Sanitise::DateTime($today), $this->_Customerno, Sanitise::Long($checkpoint->checkpointid));
        $this->_databaseManager->executeQuery($updateETASql);
        $insertQuery = "INSERT INTO etamanage(checkpointid, customerno, eta, starttime, timestamp)VALUES(%d,%d,'%s','%s','%s')";
        $insertSQL = sprintf($insertQuery, Sanitise::Long($checkpoint->checkpointid), $this->_Customerno, Sanitise::String($checkpoint->eta), Sanitise::DateTime($today), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($insertSQL);
        /* dt: 18th oct 2014, ak added, to aut-update geotest-table */
        $lat = floor($checkpoint->cgeolat);
        $long = floor($checkpoint->cgeolong);
        $updateGeoQuery = "UPDATE " . DB_PARENT . ".geotest SET location = '%s', lat='%f', geotest.long='%f', latfloor='%f',longfloor='%f' WHERE customerno=%d AND checkpointid=%d Order by geotestid DESC Limit 1";
        $updateGeo = sprintf($updateGeoQuery, Sanitise::String($checkpoint->cname), Sanitise::Float($checkpoint->cgeolat), Sanitise::Float($checkpoint->cgeolong), Sanitise::Float($lat), Sanitise::Float($long), $this->_Customerno, $checkpoint->checkpointid);
        $this->_databaseManager->executeQuery($updateGeo);
        /**/
        if (isset($checkpoint->vehicles)) {
            $this->mapVehicleToCheckpoint($checkpoint, $userid);
        }
        return $checkpoint->checkpointid;
    }

    private function UpdateType($checkpoint, $userid) {
        $today = date('Y-m-d H:i:s');

        $Query = "  UPDATE  `checkpoint_type`
                    SET     `name` = '%s'
                            ,`updatedby` = %d
                            ,`updatedon` = '%s'
                    WHERE   ctid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::String($checkpoint->name), Sanitise::Long($userid), Sanitise::DateTime($today), Sanitise::Long($checkpoint->ctid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        return $checkpoint->ctid;
    }

    public function updatechk($chkname, $chkrad, $chkid) {
        $Query = "Update checkpoint Set `cname`='%s',`crad`='%f' WHERE checkpointid = %d AND customerno = %d";
        $SQL = sprintf($Query, $chkname, Sanitise::Float($chkrad), Sanitise::Long($chkid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function DeleteCheckpoint($checkpointid, $userid) {
        $Query = "UPDATE checkpoint SET isdeleted=1,userid=%d WHERE checkpointid = %d and customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($checkpointid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE checkpointmanage SET isdeleted=1,userid=%d WHERE checkpointid = %d and customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($checkpointid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $deleteGeoQuery = "delete from " . DB_PARENT . ".geotest WHERE customerno=%d AND checkpointid=%d";
        $deleteGeo = sprintf($deleteGeoQuery, $this->_Customerno, Sanitise::Long($checkpointid));
        $this->_databaseManager->executeQuery($deleteGeo);
    }

    public function DeleteCheckpointtype($ctid, $userid) {
        $todaysdate = date('Y-m-d H:i:s');
        $Query = "  UPDATE  `checkpoint_type`
                    SET     `isdeleted` = 1
                            ,`updatedby` = %d
                            ,`updatedon` = '%s'
                    WHERE   `ctid` = %d
                    and     `customerno` = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::DateTime($todaysdate), Sanitise::Long($ctid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function DeleteCheckpointcz($checkpointid, $userid) {
        $Query = "UPDATE circularzone SET isdeleted=1,userid=%d WHERE checkpointid = %d and customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($checkpointid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function DeleteEnhCheckpoint($enh_checkpointid, $userid) {
        $Query = "UPDATE enh_checkpoint SET isdeleted=1,userid=%d WHERE enh_checkpoint = %d and customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($enh_checkpointid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function DeleteCheckpointModal($checkpointid, $vehicleid, $userid) {
        $Query = "UPDATE checkpointmanage SET isdeleted=1,userid=%d WHERE checkpointid = %d and vehicleid = %d and customerno = %d";
        echo $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::String($checkpointid), Sanitise::String($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function get_checkpoint_from_chkmanage($vehicleid) {
        $checkpoints = array();
        $Query = "SELECT *,checkpointmanage.vehicleid as cvehicleid, checkpoint.checkpointid as ccheckpointid FROM `checkpoint`
            INNER JOIN checkpointmanage ON checkpointmanage.checkpointid = checkpoint.checkpointid
            WHERE checkpointmanage.vehicleid = %d and checkpoint.customerno=%d AND checkpoint.isdeleted=0 AND checkpointmanage.isdeleted=0";
        $checkpointsQuery = sprintf($Query, Sanitise::String($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($checkpointsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VOCheckpoint();
                $checkpoint->checkpointid = $row['ccheckpointid'];
                $checkpoint->cname = $row['cname'];
                $checkpoint->completeaddress = $row['cadd'];
                $checkpoint->cgeolat = $row['cgeolat'];
                $checkpoint->cgeolong = $row['cgeolong'];
                $checkpoint->crad = $row['crad'];
                $checkpoint->vehicleid = $row['cvehicleid'];
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;
        }
        return null;
    }

    public function getvehicleforchk($chkid) {
        $Query = "select * from checkpoint where checkpointid=%s AND customerno=%s AND isdeleted=0";
        $checkpointsQuery = sprintf($Query, Sanitise::Long($chkid), $this->_Customerno);
        $this->_databaseManager->executeQuery($checkpointsQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VOCheckpoint();
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->cname = $row['cname'];
                $checkpoint->vehicleid = $row['vehicleid'];
            }
            return $checkpoint;
        }
        return NULL;
    }

    public function getchkname($chkid) {
        $chkname = "";
        $Query = "SELECT cname FROM checkpoint
            WHERE checkpoint.customerno =%d AND checkpoint.checkpointid=%d";
        $vehiclesQuery = sprintf($Query, $this->_Customerno, Sanitise::String($chkid));
        $_SESSION["repchkid"] = $vehiclesQuery;
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $chkname = $row["cname"];
            }
            return $chkname;
        }
        return null;
    }

    public function getchkname_customerid($customerid, $chkid) {
        $Query = "SELECT cname FROM checkpoint WHERE checkpoint.customerno =%d AND checkpoint.checkpointid=%d";
        $vehiclesQuery = sprintf($Query, $customerid, Sanitise::String($chkid));
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                return $row["cname"];
            }
        }
        return null;
    }

    public function getchksforvehicle($vehicleid, $routetype = NULL, $checkpointId = NULL, $chkTypeId = NULL) {
        $checkpoints = array();
        $checkpointids = array();
        if ($routetype == 1) {
            $Query1 = "SELECT routeid  FROM `vehiclerouteman` WHERE  vehicleid = " . $vehicleid . " AND `customerno` = $this->_Customerno AND isdeleted=0 ";
            $this->_databaseManager->executeQuery($Query1);
            if ($this->_databaseManager->get_rowCount() > 0) {
                $row = $this->_databaseManager->get_nextRow();
                $routeid = $row['routeid'];
            }
            if (isset($routeid)) {
                $Query2 = "SELECT checkpointid  FROM `routeman` WHERE `routeid` = " . $routeid . " AND `customerno` = " . $this->_Customerno . " AND `isdeleted` = 0";
                $this->_databaseManager->executeQuery($Query2);
                if ($this->_databaseManager->get_rowCount() > 0) {
                    while ($row = $this->_databaseManager->get_nextRow()) {
                        $checkpointids[] = $row['checkpointid'];
                    }
                    $checkpointids = implode(",", $checkpointids);
                }
                $Query3 = "select * from checkpoint
                    where
                    checkpoint.customerno=$this->_Customerno AND
                    checkpoint.checkpointid IN (" . $checkpointids . ") AND
                    checkpoint.isdeleted=0  ORDER BY `checkpointid` DESC";
                $checkpointsQuery = sprintf($Query3);
            }
        }if ($routetype == 2) {
            $whereCondition = "";
            if (isset($checkpointId) && $checkpointId != 0) {
                $whereCondition = " AND checkpoint.checkpointid = " . $checkpointId;
            }
            $Query = "select * from checkpoint
            where checkpoint.customerno=%s " . $whereCondition . " AND  checkpoint.isdeleted=0 ";
            $checkpointsQuery = sprintf($Query, $this->_Customerno);
        }if ($routetype == 3) {
            $whereCondition = "";
            if (isset($chkTypeId) && $chkTypeId != 0) {
                $whereCondition = " AND checkpoint.chktype = " . $chkTypeId;
            }
            $Query = "select *,checkpoint_type.name from checkpoint
                LEFT OUTER JOIN checkpoint_type ON checkpoint_type.ctid = checkpoint.chktype
            where checkpoint.customerno=%s " . $whereCondition . " AND  checkpoint.isdeleted=0 ";
            $checkpointsQuery = sprintf($Query, $this->_Customerno);
        } else {
            $Query = "select * from checkpoint
            INNER JOIN checkpointmanage ON checkpointmanage.checkpointid = checkpoint.checkpointid
            INNER JOIN vehicle ON vehicle.vehicleid = checkpointmanage.vehicleid
            where checkpointmanage.vehicleid=%s AND checkpoint.customerno=%s AND checkpoint.isdeleted=0 AND checkpointmanage.isdeleted=0 AND vehicle.isdeleted=0";
            $checkpointsQuery = sprintf($Query, Sanitise::Long($vehicleid), $this->_Customerno);
        }
        if (isset($checkpointsQuery)) {
            $this->_databaseManager->executeQuery($checkpointsQuery);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()) {
                    $checkpoint = new VOCheckpoint();
                    $checkpoint->checkpointid = $row['checkpointid'];
                    $checkpoint->cname = $row['cname'];
                    $checkpoint->name = isset($row['name']) ? $row['name'] : NULL;
                    $checkpoint->cgeolat = $row['cgeolat'];
                    $checkpoint->cgeolong = $row['cgeolong'];
                    $checkpoint->crad = $row['crad'];
                    $checkpoints[] = $checkpoint;
                }
                return $checkpoints;
            }
        }

        return NULL;
    }

    public function getallcheckpoints() {
        $checkpoints = array();
        //$Query = "SELECT cname,checkpointid,cgeolat, cgeolong, crad FROM `checkpoint` where customerno=%d AND isdeleted=0";
        $Query = "SELECT cname,checkpointid,cgeolat, cgeolong, crad FROM `checkpoint`
                    where customerno=%d AND isdeleted=0 order by cname ASC";
        $checkpointQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($checkpointQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VOCheckpoint();
                $checkpoint->cname = $row['cname'];
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->cgeolat = $row['cgeolat'];
                $checkpoint->cgeolong = $row['cgeolong'];
                $checkpoint->crad = $row['crad'];
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;
        }
        return null;
    }

    public function getallcheckpointtype() {
        $checkpoint_type = array();
        $Query = "  SELECT  name
                            ,ctid
                    FROM    `checkpoint_type`
                    WHERE   customerno IN (%d,0)
                    AND     isdeleted = 0
                    ORDER BY name ASC";
        $checkpointQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($checkpointQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new stdClass();
                $checkpoint->name = $row['name'];
                $checkpoint->ctid = $row['ctid'];
                $checkpoint_type[] = $checkpoint;
            }
            return $checkpoint_type;
        }
        return null;
    }

    public function getallchkpts() {
        $checkpoints = array();
        $Query = "SELECT cname,checkpointid FROM `checkpoint` where customerno=%d AND isdeleted=0";
        $checkpointQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($checkpointQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VOCheckpoint();
                $checkpoint->cname = $row['cname'];
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;
        }
        return null;
    }

    public function getallchkptsTeam($customerno) {
        $checkpoints = array();
        if ($customerno != '-1') {
            $Query = "SELECT cname,checkpointid FROM `checkpoint` where customerno=%d AND isdeleted=0";
        } else {
            $Query = "SELECT cname,checkpointid FROM `checkpoint` where isdeleted=0";
        }
        $checkpointQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($checkpointQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VOCheckpoint();
                $checkpoint->cname = $row['cname'];
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;
        }
        return null;
    }

    public function getallcpoints() {
        $checkpoints = array();
        //$Query = "SELECT cname,checkpointid,cgeolat, cgeolong, crad FROM `checkpoint` where customerno=%d AND isdeleted=0";
        $Query = "SELECT checkpoint.cname,checkpoint.checkpointid,checkpoint.cgeolat, checkpoint.cgeolong, checkpoint.crad FROM `checkpoint`
                   left outer JOIN checkpointmanage ON checkpointmanage.checkpointid = checkpoint.checkpointid
                    left outer JOIN vehicle ON vehicle.vehicleid = checkpointmanage.vehicleid
                    where checkpoint.customerno=%d AND checkpoint.isdeleted=0";
        $Query .= " GROUP by checkpoint.checkpointid";
        $checkpointQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($checkpointQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VOCheckpoint();
                $checkpoint->cname = $row['cname'];
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->cgeolat = $row['cgeolat'];
                $checkpoint->cgeolong = $row['cgeolong'];
                $checkpoint->crad = $row['crad'];
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;
        }
        return null;
    }

    public function getallcpointtypes($chkN) {
        $checkpoints = array();
        $Query = "  SELECT  ct.name
                    FROM    `checkpoint_type` AS ct
                    where   (ct.`customerno` = %d OR ct.`customerno` = 0)
                    AND     LOWER(ct.name) = LOWER('%s')
                    AND     ct.`isdeleted` = 0";
        $checkpointQuery = sprintf($Query, $this->_Customerno, $chkN);
        $this->_databaseManager->executeQuery($checkpointQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new stdClass();
                $checkpoint->name = $row['name'];
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;
        }
        return null;
    }

    public function getallcheckpointsforvehicles() {
        $checkpoints = array();
        $Query = "SELECT * FROM `checkpoint` where customerno=%d AND isdeleted=0 ORDER BY cname ASC";
        $checkpointQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($checkpointQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VOCheckpoint();
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->cname = $row['cname'];
                $checkpoints[] = $checkpoint;
            }
        }
        return $checkpoints;
    }

    public function markoutsidechk($device) {
        $Query = "Update checkpointmanage Set `conflictstatus`=1 WHERE cmid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($device->cmid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function markinsidechk($device) {
        $Query = "Update checkpointmanage Set `conflictstatus`=0 WHERE cmid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($device->cmid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function addcheckpointtovehicle($checkpts, $vehicleid, $userid) {
        $Query = "Update checkpointmanage Set `isdeleted`=1 WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "INSERT INTO checkpointmanage (vehicleid,checkpointid,customerno,userid) VALUES ('%d','%d',%d,%d)";
        foreach ($checkpts as $chkid) {
            echo $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($chkid), $this->_Customerno, Sanitise::Long($userid));
            $this->_databaseManager->executeQuery($SQL);
        }
    }

    public function get_added_vehicles_chkpt($checkpointid) {
        $vehicles = array();
        $Query = 'select * from vehicle
            INNER JOIN checkpointmanage ON checkpointmanage.vehicleid = vehicle.vehicleid
            where checkpointmanage.checkpointid=%s AND checkpointmanage.customerno=%s AND vehicle.customerno=%s AND vehicle.isdeleted=0 AND checkpointmanage.isdeleted=0';
        if ($_SESSION['groupid'] != 0) {
            $Query .= " AND vehicle.groupid =%d";
        }
        if ($_SESSION['groupid'] != 0) {
            $vehiclesQuery = sprintf($Query, Sanitise::Long($checkpointid), $this->_Customerno, $this->_Customerno, $_SESSION['groupid']);
        } else {
            $vehiclesQuery = sprintf($Query, Sanitise::Long($checkpointid), $this->_Customerno, $this->_Customerno);
        }
        $this->_databaseManager->executeQuery($vehiclesQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicle = new VOVehicle();
                $vehicle->vehicleid = $row['vehicleid'];
                //$vehicle->devicekey = $row['devicekey'];
                $vehicle->extbatt = $row['extbatt'];
                $vehicle->odometer = $row['odometer'];
                $vehicle->lastupdated = $row['lastupdated'];
                $vehicle->curspeed = $row['curspeed'];
                $vehicle->driverid = $row['driverid'];
                $vehicle->vehicleno = $row["vehicleno"];
                $vehicles[] = $vehicle;
            }
            return $vehicles;
        }
        return null;
    }

    public function checkmodifyETA($checkpointid, $starttime) {
        $chketas = array();
        $Query = "SELECT checkpoint.checkpointid, checkpoint.eta_starttime, etamanage.eta, etamanage.customerno, etamanage.starttime, etamanage.endtime
                    FROM etamanage INNER JOIN checkpoint ON checkpoint.checkpointid = etamanage.checkpointid
                    WHERE checkpoint.customerno = %d AND checkpoint.checkpointid=%d AND (('%s' BETWEEN etamanage.starttime AND etamanage.endtime) OR etamanage.starttime < '%s')Order by etamanage.starttime ASC ";
        $SQL = sprintf($Query, $this->_Customerno, Sanitise::Long($checkpointid), Sanitise::DateTime($starttime), Sanitise::DateTime($starttime));
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $chketa = new VOCheckpoint();
                $chketa->eta = $row['eta'];
                $chketa->starttime = $row['starttime'];
                $chketa->endtime = $row['endtime'];
                $chketa->eta_start = $row['eta_starttime'];
                $chketas[] = $chketa;
            }
            return $chketas;
        }
        return null;
    }

    public function add_trip_alert($data, $userid) {
        $now = date('Y-m-d H:i:s');
        $query = "insert into trip_alert(trip_alert_id,customerno,userid, vehicleid, start_date, start_time, start_checkpoint_id, end_checkpoint_id, entry_time, isdeleted, driving_distance) values(null, %d, %d, %d, '%s', '%s', %d, %d,'$now', 0, '%s')";
        $SQL = sprintf($query, $this->_Customerno, $userid, $data['vehicleid'], $data['start_date'], $data['start_time'], $data['cp_start'], $data['cp_end'], $data['driving_dist']);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function delete_trip_alert($tripid) {
        $query = "update trip_alert set isdeleted=1 where trip_alert_id=%d";
        $SQL = sprintf($query, $tripid);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function check_trip_alert($vehicleid, $cp_start, $cp_end, $s_date, $s_time) {
        $Query = "SELECT trip_alert_id FROM trip_alert
            WHERE customerno = %d AND vehicleid=%d and start_date='%s' and start_time='%s' and start_checkpoint_id=%d and end_checkpoint_id=%d and isdeleted=0 and userid=%d";
        $SQL = sprintf($Query, $this->_Customerno, $vehicleid, Sanitise::DateTime($s_date), Sanitise::DateTime($s_time), $cp_start, $cp_end, $_SESSION['userid']);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * To get first lat/long and last lat/long of 2 checkpoints
     * @return type
     */
    public function get_checkpoint_lat_long($start_check, $end_check) {
        $Query = "SELECT cgeolat,cgeolong FROM checkpoint as a where checkpointid in ($start_check, $end_check)";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $arr = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $arr[] = $row['cgeolat'];
                $arr[] = $row['cgeolong'];
            }
        }
        return $arr;
    }

    public function get_trip_alert() {
        $Query = "SELECT a.*,c.cname as start_check,d.cname as end_check, vehicleno FROM trip_alert as a left join vehicle as b on a.vehicleid=b.vehicleid
            left join checkpoint as c on a.start_checkpoint_id=c.checkpointid
            left join checkpoint as d on a.end_checkpoint_id=d.checkpointid
            WHERE a.customerno = %d and a.userid=%d and a.isdeleted=0 order by a.trip_alert_id desc";
        $SQL = sprintf($Query, $this->_Customerno, $_SESSION['userid']);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $all_data = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $all_data[] = array(
                    'tripid' => $row['trip_alert_id'],
                    'vehicleno' => $row['vehicleno'],
                    'start_date' => $row['start_date'],
                    'start_time' => $row['start_time'],
                    's_checkpoint' => $row['start_check'],
                    'e_checkpoint' => $row['end_check'],
                    'driving_distance' => $row['driving_distance']
                );
            }
            return $all_data;
        } else {
            return false;
        }
    }

    public function tripdetails_by_id($tripid) {
        $Query = "SELECT a.* FROM trip_alert as a where a.trip_alert_id=$tripid and a.isdeleted=0";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            return $this->_databaseManager->get_nextRow();
        } else {
            return false;
        }
    }

    public function get_trip_alerts_data() {
        $query = 'select a.*, b.realname, b.userkey, b.email, c.vehicleno, d.cname as startCheck, e.cname as endCheck from trip_alert as a left join ' . DB_PARENT . '.user as b on a.userid=b.userid
                left join vehicle as c on a.vehicleid=c.vehicleid
                left join checkpoint as d on a.start_checkpoint_id=d.checkpointid
                left join checkpoint as e on a.end_checkpoint_id=e.checkpointid
                where a.isdeleted=0 and a.mail_status=0 order by a.userid';
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $data = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $data[] = array(
                    'trip_alert_id' => $row['trip_alert_id'],
                    'customerno' => $row['customerno'],
                    'userid' => $row['userid'],
                    'vehicleid' => $row['vehicleid'],
                    'start_date' => $row['start_date'],
                    'start_time' => $row['start_time'],
                    'start_checkpoint_id' => $row['start_checkpoint_id'],
                    'end_checkpoint_id' => $row['end_checkpoint_id'],
                    'realname' => $row['realname'],
                    'userkey' => $row['userkey'],
                    'email' => $row['email'],
                    'vehicleno' => $row['vehicleno'],
                    'startCheck' => $row['startCheck'],
                    'endCheck' => $row['endCheck']
                );
            }
            return $data;
        } else {
            return null;
        }
    }

    public function update_trip_mail($tripid) {
        $Query = "update trip_alert set mail_status=1 where trip_alert_id=$tripid";
        $this->_databaseManager->executeQuery($Query);
    }

    public function insertException($objException) {
        $status = 0;
        if (isset($objException->checkpoints) && isset($objException->vehicles)) {
            $pdo = $this->_databaseManager->CreatePDOConn();
            $sp_params = "'" . $objException->exception . "'"
            . ",'" . $objException->exceptionName . "'"
            . ",'" . $objException->vehicles . "'"
            . ",'" . $objException->checkpoints . "'"
            . ",'" . $objException->startTime . "'"
            . ",'" . $objException->endTime . "'"
            . ",'" . $objException->customerno . "'"
            . ",'" . $objException->userid . "'"
            . ",'" . $objException->todaysdate . "'";
            $QUERY = $this->PrepareSP(speedConstants::SP_INSERT_CHECKPOINT_EXCEPTION, $sp_params);
            if ($result = $pdo->query($QUERY)) {
                $status = 1;
            }
        }
        return $status;
    }

    public function getCheckpointExceptions() {
        $arrException = array();
        $Query = "SELECT cpe.chkExpId,cpe.exceptionId,cpe.checkpointId,cpe.vehicleId,cpe.startTime,cpe.endTime,cpe.userId,cpe.exceptionType,cpe.isSend,cpe.exceptionName,
                    /* u.username, u.email, u.phone, */
                    c.cname as checkpointName, c.cgeolat,c.cgeolong,c.crad,
                    v.vehicleno as vehicleNo,
                    d.deviceid,d.devicelat,d.devicelong,d.lastupdated,
                    cm.conflictstatus, cm.cmid
                  FROM checkPointException as cpe
                  INNER JOIN checkpoint as c on c.checkpointid = cpe.checkpointId
                  INNER JOIN vehicle as v on v.vehicleid = cpe.vehicleId
                  INNER JOIN unit on unit.uid = v.uid
                  INNER JOIN devices as d on d.uid = unit.uid
                  LEFT JOIN checkpointmanage as cm ON cm.vehicleid = cpe.vehicleId
                  /*LEFT JOIN user as u on u.userid = cpe.userId*/
                  WHERE cpe.customerno=%d AND cpe.isdeleted = 0 AND  c.isdeleted=0 AND v.isdeleted=0 ";
        $usersQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($usersQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $exception = new stdClass();
                $exception->chkExpId = $row['chkExpId'];
                $exception->exceptionId = $row['exceptionId'];
                $exception->exceptionName = $row['exceptionName'];
                $exception->checkpointId = $row['checkpointId'];
                $exception->vehicleId = $row['vehicleId'];
                $exception->startTime = $row['startTime'];
                $exception->endTime = $row['endTime'];
                $exception->exceptionType = $row['exceptionType'];
                $exception->exceptionTypeName = '';
                if ($exception->exceptionType == 1) {
                    $exception->exceptionTypeName = "IN";
                } elseif ($exception->exceptionType == 2) {
                    $exception->exceptionTypeName = "OUT";
                }
                $exception->isSend = $row['isSend'];

                //$exception->username = $row['username'];
                //$exception->email = $row['email'];
                //$exception->phone = $row['phone'];

                $exception->vehicleNo = $row['vehicleNo'];

                $exception->checkpointName = $row['checkpointName'];
                $exception->cgeolat = $row['cgeolat'];
                $exception->cgeolong = $row['cgeolong'];
                $exception->crad = $row['crad'];

                $exception->deviceid = $row['deviceid'];
                $exception->devicelat = $row['devicelat'];
                $exception->devicelong = $row['devicelong'];
                $exception->lastupdated = $row['lastupdated'];

                $exception->conflictstatus = $row['conflictstatus'];
                $exception->cmid = $row['cmid'];

                $arrException[] = $exception;
            }
        }
        return $arrException;
    }

    public function updateExceptionToSend($objException) {
        $Query = sprintf("UPDATE checkPointException SET isSend = 1  WHERE chkExpId=%d AND customerno=%d", $objException->chkExpId, $objException->customerno);
        $this->_databaseManager->executeQuery($Query);
    }

    public function logCheckpointException($objException) {
        $lastLogId = 0;
        $SQL = "INSERT INTO chkptExOccuredLog(exceptionId,chkptId,vehicleId,message,customerno,created_on)VALUES(%d,%d,%d,'%s',%d,'%s')";
        $Query = sprintf($SQL, $objException->exceptionId, $objException->checkpointId, $objException->vehicleId, $objException->message, $objException->customerno, $objException->today);
        $this->_databaseManager->executeQuery($Query);
        $lastLogId = $this->_databaseManager->get_insertedId();
        return $lastLogId;
    }

    public function SaveCheckpointcz($checkpoint, $userid) {
        $checkpointId = 0;
        if (!isset($checkpoint->checkpointid)) {
            $checkpointId = $this->Insertcz($checkpoint, $userid);
        } else {
            $checkpointId = $this->Updatecz($checkpoint, $userid);
        }
        return $checkpointId;
    }

    private function Insertcz($checkpoint, $userid) {
        $checkpointId = 0;
        $today = date('Y-m-d H:i:s');
        $Query = "INSERT INTO circularzone (`customerno`,`cname`,`cadd`,`phoneno`,`email`,`crad`,`cgeolat`,`cgeolong`,`isdeleted`,`userid`,`groupid`, `eta`, `eta_starttime`) VALUES (%d,'%s','%s','%s','%s',%f,%f,%f,0,%d,%d,'%s','%s')";
        $SQL = sprintf($Query, $this->_Customerno, Sanitise::String($checkpoint->cname), Sanitise::String($checkpoint->cadd), Sanitise::String($checkpoint->cphone), Sanitise::String($checkpoint->cemail), Sanitise::Float($checkpoint->crad), Sanitise::Float($checkpoint->cgeolat), Sanitise::Float($checkpoint->cgeolong), Sanitise::Long($userid), Sanitise::Long($checkpoint->groupid), Sanitise::String($checkpoint->eta), Sanitise::DateTime($today));
        $this->_databaseManager->executeQuery($SQL);
        $checkpoint->checkpointid = $this->_databaseManager->get_insertedId();
        $checkpointId = $checkpoint->checkpointid;
        return $checkpointId;
    }

    private function Updatecz($checkpoint, $userid) {
        $today = date('Y-m-d H:i:s');
        $Query = "Update circularzone Set `cname`='%s',`phoneno`='%s',`email`='%s',`crad`='%f',`cgeolat`='%f',`cgeolong`='%f',`userid`=%d, `groupid`=%d, `eta`='%s', `eta_starttime`='%s' WHERE checkpointid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::String($checkpoint->cname), Sanitise::String($checkpoint->cphone), Sanitise::String($checkpoint->cemail), Sanitise::Float($checkpoint->crad), Sanitise::Float($checkpoint->cgeolat), Sanitise::Float($checkpoint->cgeolong), Sanitise::Long($userid), Sanitise::Long($checkpoint->groupid), Sanitise::String($checkpoint->eta), Sanitise::DateTime($today), Sanitise::Long($checkpoint->checkpointid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        return $checkpoint->checkpointid;
    }

    public function mapVehicleToCheckpoint($checkpoint, $userid, $deleteParticularVehicle = 0) {
        $Query = "INSERT INTO checkpointmanage (`checkpointid`,`vehicleid`,`customerno`,`conflictstatus`,`userid`,`isdeleted`) "
            . "VALUES (%d,'%d','%d','1','%d','0')";
        if ($deleteParticularVehicle == 1) {
            if ($checkpoint->vehicles) {
                foreach ($checkpoint->vehicles as $vehicleid) {
                    $chkptManageQuery = "UPDATE checkpointmanage SET isdeleted=1, userid = %d Where checkpointid=%d AND customerno = %d AND vehicleid = %d";
                    //echo $routearrays = $routearray;
                    $chkptManageQueryValue = sprintf($chkptManageQuery, Sanitise::Long($userid), Sanitise::Long($checkpoint->checkpointid), $this->_Customerno, $vehicleid);
                    $this->_databaseManager->executeQuery($chkptManageQueryValue);

                    $SQL = sprintf($Query, Sanitise::Long($checkpoint->checkpointid), Sanitise::Long($vehicleid), $this->_Customerno, Sanitise::Long($userid));
                    $this->_databaseManager->executeQuery($SQL);
                }
            }
        } else {
            $chkptManageQuery = "UPDATE checkpointmanage SET isdeleted=1, userid = %d Where checkpointid=%d AND customerno = %d";
            //echo $routearrays = $routearray;
            $chkptManageQueryValue = sprintf($chkptManageQuery, Sanitise::Long($userid), Sanitise::Long($checkpoint->checkpointid), $this->_Customerno);
            $this->_databaseManager->executeQuery($chkptManageQueryValue);
            if ($checkpoint->vehicles) {
                foreach ($checkpoint->vehicles as $vehicleid) {
                    $SQL = sprintf($Query, Sanitise::Long($checkpoint->checkpointid), Sanitise::Long($vehicleid), $this->_Customerno, Sanitise::Long($userid));
                    $this->_databaseManager->executeQuery($SQL);
                }
            }
        }
    }

    public function getCheckpointTypebyId($id) {
        $Query = "  SELECT  chktype
                    FROM    `checkpoint`
                    where   customerno = %d
                    AND     `checkpointid` = '%d'
                    LIMIT   1";
        $checkpointDetailsQuery = sprintf($Query, $this->_Customerno, Validator::escapeCharacters($id));
        $this->_databaseManager->executeQuery($checkpointDetailsQuery);
        if ($row = $this->_databaseManager->get_nextRow()) {
            return $row['chktype'];
        }
        return null;
    }

    public function getallcheckpointTypes() {
        $checkpoints = array();
        //$Query = "SELECT cname,checkpointid,cgeolat, cgeolong, crad FROM `checkpoint` where customerno=%d AND isdeleted=0";
        $Query = "SELECT
        ct.`name`, ct.ctid as check_point_typeid/*,c.checkpointid, c.chktype, c.cgeolat, c.cgeolong, c.crad*/
        FROM
            `checkpoint_type` as ct
        INNER JOIN
            `checkpoint` as c
        ON
        ct.ctid = c.chktype
        WHERE
            c.customerno = %d  AND c.isdeleted = 0
            GROUP BY check_point_typeid
        ORDER BY ct.`name` ASC ";

        /*  $Query = "SELECT cname,checkpointid,cgeolat, cgeolong, crad FROM `checkpoint`
        where customerno=%d AND isdeleted=0 order by cname ASC"; */
        $checkpointQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($checkpointQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VOCheckpoint();
                $checkpoint->cname = $row['name'];
                $checkpoint->checkPointTypeId = $row['check_point_typeid'];
                /* $checkpoint->cgeolat = $row['cgeolat'];
                $checkpoint->cgeolong = $row['cgeolong'];
                $checkpoint->crad = $row['crad']; */
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;
        }
        return null;
    }

    public function getCheckPointsByCheckPointId($checkPointId) {
        if ($checkPointId == 0 || $checkPointId == -1) {
            $whereClause = '';
        } else {
            $whereClause = ' AND chktype=' . $checkPointId;
        }
        $Query = "SELECT cname,checkpointid,cgeolat, cgeolong, crad FROM `checkpoint`
                    where customerno=%d AND isdeleted=0 AND checkPointCategory=1" . $whereClause . " order by cname ASC";
        $checkpointQuery = sprintf($Query, $this->_Customerno); //echo"Query is: ".$checkpointQuery; exit();
        $this->_databaseManager->executeQuery($checkpointQuery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $checkpoint = new VOCheckpoint();
                $checkpoint->cname = $row['cname'];
                $checkpoint->checkpointid = $row['checkpointid'];
                $checkpoint->cgeolat = $row['cgeolat'];
                $checkpoint->cgeolong = $row['cgeolong'];
                $checkpoint->crad = $row['crad'];
                $checkpoints[] = $checkpoint;
            }
            return $checkpoints;
        }
        return null;
    }

    public function getCheckPointWiseDataForReverseRoute($customerNo, $route) {
        $QUERY = 'CALL fetch_data_for_route_wise_report_checkpoints(' . $route . ',' . $this->_Customerno . ')';
        /* $QUERY = 'CALL '.speedConstants::SP_FETCH_DATA_FOR_ROUTE_WISE_REPORT.'('.$route.','.$this->_Customerno.')';  */
        $this->_databaseManager->executeQuery($QUERY);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $dataArray = [];
            $allData = $this->_databaseManager->get_recordSet();
            return $allData;
        }
    }
}
