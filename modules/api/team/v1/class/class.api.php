<?php
require_once "database.inc.php";
date_default_timezone_set('Asia/Kolkata');
class api {
//<editor-fold defaultstate="collapsed" desc="Constructor">
    // construct
    public function __construct() {
        $this->db = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, SPEEDDB);
    }

// </editor-fold>
    public function check_login($getData) {
        $userkeyparam = 0;
        $pdo = $this->db->CreatePDOConn();
        $todaysdate = date("Y-m-d H:i:s");
        $sp_params = "'" . $getData->username . "'"
        . ",'" . $getData->password . "'"
            . "," . '@userkeyparam,@teamidparam,@roleParam';
        $queryCallSP = "CALL " . speedConstants::SP_AUTHENTICATE_FOR_TEAM_LOGIN . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $this->db->ClosePDOConn($pdo);
        $outputParamsQuery = "SELECT @userkeyparam AS userkeyparam,@teamidparam AS teamidparam, @roleParam AS roleParam";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $responseData = new stdClass();
        if ($outputResult['userkeyparam'] != 'Empty') {
            $responseData->userkey = $outputResult['userkeyparam'];
            $responseData->teamid = $outputResult['teamidparam'];
            $responseData->role = $outputResult['roleParam'];
        } else {
            $responseData->userkey = 0;
        }
        return $responseData;
    }

    public function pullunit($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
// successful
            $sql = "SELECT u.uid,u.unitno,v.vehicleno,v.vehicleid "
                . " FROM unit as u INNER JOIN vehicle v ON v.uid=u.uid "
                . " WHERE u.customerno=$getObjectData->customerno "
                . " AND v.isdeleted = 0"
                . " ORDER BY u.uid ASC";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['uid'] = $row["uid"];
                    $arr_p['unitno'] = $row["unitno"];
                    $arr_p['vehicleid'] = $row["vehicleid"];
                    $arr_p['vehicleno'] = $row["vehicleno"];
                    $json_p[] = $arr_p;
                }
                return $json_p;
            } else {
                return null;
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function pullCustomer($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
// successful
            $sql = "SELECT customerno,customercompany "
                . " FROM customer "
                . " ORDER BY customerno";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['customerno'] = $row["customerno"];
                    $arr_p['customercompany'] = $row["customercompany"] . "(" . $row["customerno"] . ")";
                    $json_p[] = $arr_p;
                }
                return $json_p;
            }
        } else {
            return null;
        }
    }

    public function pullQuery($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
            // successful
            $dt = date("Y-m-d");
            $location = "../../../../customer/" . $getObjectData->customerno . "/unitno/$getObjectData->unitno/sqlite/$dt.sqlite";
            if (file_exists($location)) {
                $path = "sqlite:$location";
                $db = new PDO($path);
                $query = "SELECT dh.devicelat,dh.devicelong,dh.lastupdated,dh.inbatt,dh.status,dh.ignition,dh.powercut,dh.tamper,gpsfixed,dh.`online/offline`,dh.gsmstrength,dh.gsmregister,dh.gprsregister,dh.swv,dh.hwv,unithistory.analog1,unithistory.analog2,unithistory.analog3,unithistory.analog4,unithistory.digitalio,unithistory.commandkey,unithistory.commandkeyval,vehiclehistory.extbatt,vehiclehistory.odometer,vehiclehistory.curspeed from devicehistory as dh
        INNER JOIN unithistory ON unithistory.lastupdated = dh.lastupdated
        INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = dh.lastupdated
        ORDER by dh.lastupdated DESC LIMIT 20";
                $result = $db->query($query);
                if (isset($result) && $result != "") {
                    foreach ($result as $row) {
                        $Datacap = new stdClass();
                        $Datacap->devicelat = $row['devicelat'];
                        $Datacap->devicelong = $row['devicelong'];
                        $Datacap->lastupdated = date("g:i:s a", strtotime($row["lastupdated"]));
                        $Datacap->inbatt = $row['inbatt'];
                        $Datacap->status = $row['status'];
                        $Datacap->ignition = $row['ignition'];
                        $Datacap->powercut = $row['powercut'];
                        $Datacap->tamper = $row['tamper'];
                        $Datacap->gpsfixed = $row['gpsfixed'];
                        $Datacap->onoff = $row['online/offline'];
                        $Datacap->gsmstrength = $row['gsmstrength'];
                        $Datacap->gsmregister = $row['gsmregister'];
                        $Datacap->gprsregister = $row['gprsregister'];
                        $Datacap->swv = $row['swv'];
                        $Datacap->hwv = $row['hwv'];
                        $Datacap->analog1 = $row['analog1'];
                        $Datacap->analog2 = $row['analog2'];
                        $Datacap->analog3 = $row['analog3'];
                        $Datacap->analog4 = $row['analog4'];
                        $Datacap->digitalio = $row['digitalio'];
                        $Datacap->commandkey = $row['commandkey'];
                        $Datacap->commandkeyval = $row['commandkeyval'];
                        $Datacap->extbatt = $row['extbatt'];
                        $Datacap->odometer = $row['odometer'];
                        $Datacap->curspeed = $row['curspeed'];
                        $queues[] = $Datacap;
                    }
                    return $queues;
                }
            } else {
                return "Empty";
            }
        } else {
            return null;
        }
    }

    public function searchUnit($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
            $SQL = "SELECT      team.name
                                ,d.installdate
                                ,d.lastupdated
                                , s.simcardno
                                , d.expirydate
                                , d.invoiceno
                                , u.uid
                                , u.unitno
                                , t.status
                                , u.customerno
                    FROM        unit u
                    INNER JOIN  trans_status t ON t.id = u.trans_statusid
                    LEFT OUTER JOIN devices d ON d.uid = u.uid
                    LEFT OUTER JOIN simcard s ON s.id = d.simcardid
                    LEFT OUTER JOIN team ON team.teamid = u.teamid
                    WHERE   t.type=0
                    AND u.unitno LIKE '%$getObjectData->unitno%'
                    ORDER BY u.customerno ASC";

            $record = $this->db->query($SQL, __FILE__, __LINE__);
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['name'] = $row["name"];
                    $arr_p['installdate'] = $row["installdate"];
                    $arr_p['invoiceno'] = $row["invoiceno"];
                    $arr_p['lastupdated'] = $row["lastupdated"];
                    $arr_p['simcardno'] = $row["simcardno"];
                    $arr_p['expirydate'] = $row["expirydate"];
                    $arr_p['uid'] = $row["uid"];
                    $arr_p['unitno'] = $row["unitno"];
                    $arr_p['status'] = $row["status"];
                    $arr_p['customerno'] = $row["customerno"];
                    $json_p[] = $arr_p;
                }
                return $json_p;
            } else {
                return "Empty";
            }
        } else {
            return null;
        }
    }

    public function searchSim($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
            $SQL = "SELECT      unit.unitno
                                , s.simcardno
                                , t.status
                                ,devices.invoiceno
                                , s.customerno
                                , s.id
                                , v.vendorname
                    FROM        simcard s
                    INNER JOIN  trans_status t ON t.id = s.trans_statusid
                    INNER JOIN  vendor v ON v.id = s.vendorid
                    LEFT OUTER JOIN devices ON devices.simcardid = s.id
                    LEFT OUTER JOIN unit ON devices.uid = unit.uid
                    WHERE       t.type=1
                    AND         s.simcardno LIKE '%$getObjectData->simno%'
                    ORDER BY    s.customerno ASC";
            $record = $this->db->query($SQL, __FILE__, __LINE__);
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['unitno'] = $row["unitno"];
                    $arr_p['simcardno'] = $row["simcardno"];
                    $arr_p['status'] = $row["status"];
                    $arr_p['invoiceno'] = $row["invoiceno"];
                    $arr_p['customerno'] = $row["customerno"];
                    $arr_p['id'] = $row["id"];
                    $arr_p['vendorname'] = $row["vendorname"];
                    $json_p[] = $arr_p;
                }
                return $json_p;
            } else {
                return "Empty";
            }
        } else {
            return null;
        }
    }

    public function suspect_Device($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $todaysdate = date("Y-m-d H:i:s");
            $sp_params = "'" . $getObjectData->comment . "'"
            . ",'" . $getObjectData->unitid . "'"
            . ",'" . $getObjectData->simcardid . "'"
            . ",'" . $getObjectData->customerno . "'"
            . ",'" . $getObjectData->aptdate . "'"
            . ",'" . $getObjectData->coname . "'"
            . ",'" . $getObjectData->cophone . "'"
            . ",'" . $getObjectData->priority . "'"
            . ",'" . $getObjectData->location . "'"
            . ",'" . $getObjectData->timeslot . "'"
            . ",'" . $getObjectData->purpose . "'"
            . ",'" . $getObjectData->details . "'"
            . ",'" . $getObjectData->coordinatorid . "'"
            . ",'" . $getObjectData->lteamid . "'"
                . ",'" . $todaysdate . "'"
                . ",@is_executed,@vehiclenoOut,@unitnoOut,@simcardnoOut,@usernameOut,@realnameOut,@emailOut,@elixirOut,@msgOut";
            $queryCallSP = "CALL " . speedConstants::SP_SUSPECT_UNIT . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            $outputParamsQuery = "SELECT @is_executed AS is_executed,@vehiclenoOut AS vehicleno,@unitnoOut AS unitno,@simcardnoOut AS simcardno,@elixir AS elixir,@username AS username,@realname AS realname,@email AS email,@msgOut AS msgOut";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            if ($outputResult['is_executed'] == 1) {
                if ($getObjectData->sendmail == 1) {
                    $mail = new stdClass();
                    $mail->username = $outputResult['username'];
                    $mail->realname = $outputResult["realname"];
                    $mail->email = $outputResult["email"];
                    $mail->unitno = $outputResult["unitno"];
                    $mail->simcardno = $outputResult["simcardno"];
                    $mail->elixir = $outputResult["elixir"];
                    $mail->vehicleno = $outputResult["vehicleno"];
                    $mail->comment = $getObjectData->comment;
                    $mail->subject = 'Suspect Device Details';
                    $status = $this->SendEmailSuspect($mail);
                    if ($status == 1) {
                        return "Successful Sent";
                    } else {
                        return $status;
                    }
                } else {
                    return "Successful";
                }
            } else {
                return "Suspect Fail";
            }
        } else {
            return null;
        }
    }

    public function newInstallRequest($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $todaysdate = date("Y-m-d H:i:s");
            $sp_params = "'" . $todaysdate . "'"
            . ",'" . $getObjectData->aptDate . "'"
            . ",'" . $getObjectData->priority . "'"
            . ",'" . $getObjectData->location . "'"
            . ",'" . $getObjectData->timeslot . "'"
            . ",'" . $getObjectData->details . "'"
            . ",'" . $getObjectData->coordinatorid . "'"
            . ",'" . $getObjectData->coname . "'"
            . ",'" . $getObjectData->cophone . "'"
            . ",'" . $getObjectData->installCount . "'"
            . ",'" . $getObjectData->customerno . "'"
            . ",'" . $getObjectData->lteamid . "'"
                . ",@is_executed";
            $queryCallSP = "CALL " . speedConstants::SP_NEW_INSTALL_REQUEST . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            $outputParamsQuery = "SELECT @is_executed AS is_executed";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            if ($outputResult['is_executed'] == 1) {
                return "Successful";
            } else {
                return "Fail";
            }
        } else {
            return null;
        }
    }

    public function pullBucketList($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        //print_r($validation); exit;
        if ($validation['status'] == "successful") {
            $sp_params = "'" . $getObjectData->date . "'";
            $bucketList = array();
            $pdo = $this->db->CreatePDOConn();
            $SQL = "CALL " . speedConstants::SP_PULL_BUCKET_LIST . "(" . $sp_params . ")";
            // echo $SQL; exit;
            $arrResult = $pdo->query($SQL)->fetchAll(PDO::FETCH_ASSOC);
            // print_r($arrResult); exit;
            if (!empty($arrResult)) {
                foreach ($arrResult as $row) {
                    if ($row['purposeid'] == 1) {
                        $purname = "New Installation";
                    } elseif ($row['purposeid'] == 2) {
                        $purname = "Repair";
                    } elseif ($row['purposeid'] == 3) {
                        $purname = "Removal";
                    } elseif ($row['purposeid'] == 4) {
                        $purname = "Replacement";
                    } elseif ($row['purposeid'] == 5) {
                        $purname = "Reinstall";
                    }
                    if ($row['status'] == 1) {
                        $status = "Reschedule";
                    } elseif ($row['status'] == 2) {
                        $status = "Successful";
                    } elseif ($row['status'] == 3) {
                        $status = "Unsuccessful";
                    } elseif ($row['status'] == 4) {
                        $status = "FE Assigned";
                    } elseif ($row['status'] == 5) {
                        $status = "Cancel";
                    } elseif ($row['status'] == 0) {
                        $status = "Modify";
                    }
                    $bucketList[] = array(
                        'bucketid' => $row['bucketid'],
                        'customerno' => isset($row['customerno']) ? $row['customerno'] : '',
                        'customercompany' => isset($row['customercompany']) ? $row['customercompany'] : '',
                        'priority' => isset($row['priority']) ? $row['priority'] : '',
                        'vehicleno' => isset($row['vehicleno']) ? $row['vehicleno'] : '',
                        'location' => isset($row['location']) ? $row['location'] : '',
                        'purposeid' => isset($row['purposeid']) ? $row['purposeid'] : '',
                        'purpose_name' => isset($purname) ? $purname : '',
                        'cp_name' => isset($row['person_name']) ? $row['person_name'] : '',
                        'cp_phone' => isset($row['cp_phone1']) ? $row['cp_phone1'] : '',
                        'fe_name' => isset($row['fe_name']) ? $row['fe_name'] : '',
                        'fe_id' => isset($row['fe_id']) ? $row['fe_id'] : '',
                        'status_id' => isset($row['status']) ? $row['status'] : '',
                        'status_text' => isset($status) ? $status : '',
                        'vehno' => isset($row['vehno']) ? $row['vehno'] : '',
                        'vehicleid' => isset($row['vehicleid']) ? $row['vehicleid'] : '',
                        'unitid' => isset($row['uid']) ? $row['uid'] : '',
                        'created_by' => isset($row['created_by']) ? $row['created_by'] : '',
                        'created_by_name' => isset($row['created_by_name']) ? $row['created_by_name'] : '',
                        'details' => isset($row['details']) ? $row['details'] : '',
                        'apt_date' => isset($row['apt_date']) ? $row['apt_date'] : '',
                        'timeslot' => isset($row['timeslot']) ? $row['timeslot'] : '');
                }
                return $bucketList;
            } else {
                return "Empty";
            }
        } else {
            return null;
        }
    }

    public function pullCoordinator($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        if ($validation['status'] == "successful") {
            $sp_params = "'" . $getObjectData->customerno . "'";
            $bucketList = array();
            $pdo = $this->db->CreatePDOConn();
            $SQL = "CALL " . speedConstants::SP_PULL_COORDINATOR . "(" . $sp_params . ")";
            $arrResult = $pdo->query($SQL)->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($arrResult)) {
                foreach ($arrResult as $row) {
                    $bucketList[] = array(
                        'cpid' => $row['cpdetailid'],
                        'cpname' => $row['person_name']);
                }
                return $bucketList;
            } else {
                return "Empty";
            }
        } else {
            return null;
        }
    }

    public function pullReason($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        if ($validation['status'] == "successful") {
            $reasons = array();
            $pdo = $this->db->CreatePDOConn();
            $SQL = "CALL " . speedConstants::SP_PULL_REASON . "()";
            $arrResult = $pdo->query($SQL)->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($arrResult)) {
                foreach ($arrResult as $row) {
                    $reasons[] = array(
                        'reasonid' => $row['reasonid'],
                        'reason' => $row['reason']);
                }
                return $reasons;
            } else {
                return "Empty";
            }
        } else {
            return null;
        }
    }

    public function editBucketOperation($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $todaysdate = date("Y-m-d H:i:s");
            $sp_params = "'" . $getObjectData->status . "'"
            . ",'" . $getObjectData->bucketid . "'"
            . ",'" . $getObjectData->data . "'"
                . ",'" . $todaysdate . "'"
                . ",@is_executed";
            $queryCallSP = "CALL " . speedConstants::SP_EDIT_BUCKET_OPERATION . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            $outputParamsQuery = "SELECT @is_executed AS is_executed";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            if ($outputResult['is_executed'] == 1) {
                return "success";
            } else {
                return "fail";
            }
        } else {
            return null;
        }
    }

    public function editBucketCRM($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $todaysdate = date("Y-m-d H:i:s");
            $sp_params = "'" . $getObjectData->status . "'"
            . ",'" . $getObjectData->customerno . "'"
            . ",'" . $getObjectData->vehicleid . "'"
            . ",'" . $getObjectData->createdby . "'"
            . ",'" . $getObjectData->priorityid . "'"
            . ",'" . $getObjectData->location . "'"
            . ",'" . $getObjectData->timeslot . "'"
            . ",'" . $getObjectData->purposeid . "'"
            . ",'" . $getObjectData->details . "'"
            . ",'" . $getObjectData->data . "'"
            . ",'" . $getObjectData->coordinator . "'"
            . ",'" . $getObjectData->aptdate . "'"
            . ",'" . $getObjectData->coname . "'"
            . ",'" . $getObjectData->cophone . "'"
            . ",'" . $getObjectData->bucketid . "'"
                . ",'" . $todaysdate . "'"
                . ",@is_executed";
            $queryCallSP = "CALL " . speedConstants::SP_EDIT_BUCKET_CRM . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            $outputParamsQuery = "SELECT @is_executed AS is_executed";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            if ($outputResult['is_executed'] == 1) {
                return "success";
            } else {
                return "fail";
            }
        } else {
            return null;
        }
    }

    public function registerDevice($getObjectData) {
        //         $relativepath = "../..";

        // mkdir($relativepath . '/amit/2/unitno/5', 0777, true) or die("Could not create directory");
        $validation = $this->check_userkey($getObjectData->userkey);
        $todaysdate = date("Y-m-d H:i:s");
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $sp_params = "'" . $todaysdate . "'"
            . ",'" . $getObjectData->unitid . "'"
            . ",'" . $getObjectData->utype . "'"
            . ",'" . $getObjectData->simcardid . "'"
            . ",'" . $getObjectData->customerno . "'"
            . ",'" . $getObjectData->pono . "'"
            . ",'" . $getObjectData->podate . "'"
            . ",'" . $getObjectData->cexpirydate . "'"
            . ",'" . $getObjectData->cinstalldate . "'"
            . ",'" . $getObjectData->end_date . "'"
            . ",'" . $getObjectData->cinvoiceno . "'"
            . ",'" . $getObjectData->cvehicleno . "'"
            . ",'" . $getObjectData->kind . "'"
            . ",'" . $getObjectData->lease . "'"
            . ",'" . $getObjectData->eteamid . "'"
            . ",'" . $getObjectData->lteamid . "'"
            . ",'" . $getObjectData->status . "'"
            . ",'" . $getObjectData->unsuccess_problem . "'"
            . ",'" . $getObjectData->incomplete_date . "'"
            . ",'" . $getObjectData->reschedule_date . "'"
            . ",'" . $getObjectData->bucketid . "'"
            . ",'" . $getObjectData->comment . "'"
            . ",'" . $getObjectData->docketid . "'"
                . ",@is_executed,@username,@realname,@email,@unitnumber,@simcardno,@elixir,@errormsg";
            $queryCallSP = "CALL " . speedConstants::SP_REGISTER_DEVICE . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            $outputParamsQuery = "SELECT    @is_executed AS is_executed
                                            ,@username AS username
                                            ,@realname AS realname
                                            ,@email AS email
                                            ,@unitnumber AS unitnumber
                                            ,@simcardno AS simcardno
                                            ,@elixir AS elixir
                                            ,@errormsg AS errormsg";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            $relativepath = "../..";

            if ($outputResult['is_executed'] == 1) {
                if ($getObjectData->status == 2) {
                    //      Create unit directory
                    $relativepath = "../..";
                    if (!is_dir($relativepath . '/customer/')) {
                        mkdir($relativepath . '/customer/', 0777, true) or die("Could not create directory");
                    }
                    if (!is_dir($relativepath . '/customer/' . $getObjectData->customerno)) {
                        mkdir($relativepath . '/customer/' . $getObjectData->customerno, 0777, true) or die("Could not create directory");
                    }
                    if (!is_dir($relativepath . '/customer/' . $getObjectData->customerno . '/unitno/')) {
                        mkdir($relativepath . '/customer/' . $getObjectData->customerno . '/unitno/', 0777, true) or die("Could not create directory");
                    }
                    if (!is_dir($relativepath . '/customer/' . $getObjectData->customerno . '/unitno/' . $outputResult['unitnumber'])) {
                        mkdir($relativepath . '/customer/' . $getObjectData->customerno . '/unitno/' . $outputResult['unitnumber'], 0777, true) or die("Could not create directory");
                    }
                    if (!is_dir($relativepath . '/customer/' . $getObjectData->customerno . '/unitno/' . $outputResult['unitnumber'])) {
                        mkdir($relativepath . '/customer/' . $getObjectData->customerno . '/unitno/' . $outputResult['unitnumber'], 0777, true) or die("Could not create directory");
                    }
                    if (!is_dir($relativepath . '/customer/' . $getObjectData->customerno . '/unitno/' . $outputResult['unitnumber'] . '/sqlite')) {
                        mkdir($relativepath . '/customer/' . $getObjectData->customerno . '/unitno/' . $outputResult['unitnumber'] . '/sqlite', 0777, true) or die("Could not create directory");
                    }
                    if ($getObjectData->sendmailr == 1) {
                        $mail = new stdClass();
                        $mail->username = $outputResult['username'];
                        $mail->realname = $outputResult["realname"];
                        $mail->email = $outputResult["email"];
                        $mail->unitnumber = $outputResult["unitnumber"];
                        $mail->simcardno = $outputResult["simcardno"];
                        $mail->elixir = $outputResult["elixir"];
                        $mail->vehicleno = $getObjectData->cvehicleno;
                        $mail->installdate = $getObjectData->cinstalldate;
                        $mail->expirydate = $getObjectData->cexpirydate;
                        $mail->comment = $getObjectData->comment;
                        $mail->subject = 'Unit Installation Details';
                        $status = $this->SendEmail($mail);
                        if ($status == 1) {
                            return "Successful Sent";
                        } else {
                            return $status;
                        }
                    } else {
                        return "Successful installed";
                    }
                } else {
                    return "Successful";
                }
            } else {
                return "Fail";
            }
        } else {
            return null;
        }
    }

    public function replaceDevice($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $todaysdate = date("Y-m-d H:i:s");
            $sp_params = "'" . $todaysdate . "'"
            . ",'" . $getObjectData->customerno . "'"
            . ",'" . $getObjectData->oldvehicleid . "'"
            . ",'" . $getObjectData->oldunitid . "'"
            . ",'" . $getObjectData->eteamid . "'"
            . ",'" . $getObjectData->newunitid . "'"
            . ",'" . $getObjectData->lteamid . "'"
            . ",'" . $getObjectData->bucketid . "'"
            . ",'" . $getObjectData->comments . "'"
                . ",@is_executed,@username,@realname,@email,@vehicleno,@oldunitno,@newunitno,@simcardno,@elixir,@errormsg";
            $queryCallSP = "CALL " . speedConstants::SP_REPLACE_DEVICE . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            $outputParamsQuery = "SELECT    @is_executed AS is_executed
                                            ,@username AS username
                                            ,@realname AS realname
                                            ,@email AS email
                                            ,@vehicleno AS vehicleno
                                            ,@oldunitno AS oldunitno
                                            ,@newunitno AS newunitno
                                            ,@simcardno AS simcardno
                                            ,@elixir AS elixir
                                            ,@errormsg AS errormsg";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            if ($outputResult['is_executed'] == 1) {
                //      Create unit directory
                $relativepath = "../..";
                if (!is_dir($relativepath . '/customer/' . $getObjectData->customerno . '/unitno/' . $outputResult['newunitno'])) {
                    // Directory doesn't exist.
                    mkdir($relativepath . '/customer/' . $getObjectData->customerno . '/unitno/' . $outputResult['newunitno'], 0777, true) or die("Could not create directory");
                }
                if (!is_dir($relativepath . '/customer/' . $getObjectData->customerno . '/unitno/' . $outputResult['newunitno'] . '/sqlite')) {
                    // Directory doesn't exist.
                    mkdir($relativepath . '/customer/' . $getObjectData->customerno . '/unitno/' . $outputResult['newunitno'] . '/sqlite', 0777, true) or die("Could not create directory");
                }
                if (!is_dir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'])) {
                    // Directory doesn't exist.
                    mkdir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'], 0777, true) or die("Could not create directory");
                }
                if (!is_dir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'] . '/sqlite')) {
                    // Directory doesn't exist.
                    mkdir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'] . '/sqlite', 0777, true) or die("Could not create directory");
                }
                if ($getObjectData->sendmailr == 1) {
                    $mail = new stdClass();
                    $mail->username = $outputResult['username'];
                    $mail->realname = $outputResult['realname'];
                    $mail->email = $outputResult['email'];
                    $mail->vehicleno = $outputResult['vehicleno'];
                    $mail->oldunitno = $outputResult['oldunitno'];
                    $mail->newunitno = $outputResult['newunitno'];
                    $mail->simcard = $outputResult['simcardno'];
                    $mail->elixir = $outputResult['elixir'];
                    $mail->comments = $getObjectData->comments;
                    $mail->subject = 'Unit Replace Details';
                    $status = $this->SendEmailReplace($mail);
                    if ($status == 1) {
                        return "Successful sent";
                    } else {
                        return $status;
                    }
                } else {
                    return "Successful";
                }
            } else {
                return "Replace failed";
            }
        } else {
            return null;
        }
    }

    public function replaceSimcard($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $todaysdate = date("Y-m-d H:i:s");
            $sp_params = "'" . $todaysdate . "'"
            . ",'" . $getObjectData->customerno . "'"
            . ",'" . $getObjectData->oldvehicleid . "'"
            . ",'" . $getObjectData->unitid . "'"
            . ",'" . $getObjectData->eteamid . "'"
            . ",'" . $getObjectData->newsimid . "'"
            . ",'" . $getObjectData->lteamid . "'"
            . ",'" . $getObjectData->bucketid . "'"
            . ",'" . $getObjectData->comments . "'"
                . ",@is_executed,@username,@realname,@email,@vehicleno,@oldsimcardno,@newsimcardno,@elixir";
            $queryCallSP = "CALL " . speedConstants::SP_REPLACE_SIM . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            $outputParamsQuery = "SELECT @is_executed AS is_executed,@username AS username,@realname AS realname,@email AS email,@vehicleno AS vehicleno,@oldsimcardno AS oldsimcardno,@newsimcardno AS newsimcardno,@elixir AS elixir";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            if ($outputResult['is_executed'] == 1) {
                if ($getObjectData->sendmailr == 1) {
                    $mail = new stdClass();
                    $mail->username = $outputResult['username'];
                    $mail->realname = $outputResult['realname'];
                    $mail->email = $outputResult['email'];
                    $mail->vehicleno = $outputResult['vehicleno'];
                    $mail->oldsimcardno = $outputResult['oldsimcardno'];
                    $mail->newsimcardno = $outputResult['newsimcardno'];
                    $mail->elixir = $outputResult['elixir'];
                    $mail->comments = $getObjectData->comments;
                    $status = $this->SendEmailSimReplace($mail);
                    if ($status == 1) {
                        return "Successful sent";
                    } else {
                        return $status;
                    }
                } else {
                    return "Successful";
                }
            } else {
                return "Replace failed";
            }
        } else {
            return null;
        }
    }

    public function replaceBoth($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $todaysdate = date("Y-m-d H:i:s");
            $sp_params = "'" . $todaysdate . "'"
            . ",'" . $getObjectData->customerno . "'"
            . ",'" . $getObjectData->oldvehicleid . "'"
            . ",'" . $getObjectData->oldunitid . "'"
            . ",'" . $getObjectData->eteamid . "'"
            . ",'" . $getObjectData->newunitid . "'"
            . ",'" . $getObjectData->newsimcardid . "'"
            . ",'" . $getObjectData->lteamid . "'"
            . ",'" . $getObjectData->status . "'"
            . ",'" . $getObjectData->unsuccess_problem . "'"
            . ",'" . $getObjectData->incomplete_date . "'"
            . ",'" . $getObjectData->reschedule_date . "'"
            . ",'" . $getObjectData->bucketid . "'"
            . ",'" . $getObjectData->comments . "'"
                . ",@is_executed,@username,@realname,@email,@vehicleno,@oldunitno,@oldsimno,@newunitno,@newsimno,@elixir,@errormsg";
            $queryCallSP = "CALL " . speedConstants::SP_REPLACE_BOTH . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            $outputParamsQuery = "SELECT @is_executed AS is_executed,@username AS username,@realname AS realname,@email AS email,@vehicleno AS vehicleno,@oldunitno AS oldunitno,@oldsimno AS oldsimno,@newunitno AS newunitno,@newsimno AS newsimno,@elixir AS elixir";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            if ($outputResult['is_executed'] == 1) {
                if ($getObjectData->status == 2) {
                    //Create unit directory
                    $relativepath = "../..";
                    if (!is_dir($relativepath . '/customer/' . $getObjectData->customerno . '/unitno/' . $outputResult['newunitno'])) {
                        // Directory doesn't exist.
                        mkdir($relativepath . '/customer/' . $getObjectData->customerno . '/unitno/' . $outputResult['newunitno'], 0777, true) or die("Could not create directory");
                    }
                    if (!is_dir($relativepath . '/customer/' . $getObjectData->customerno . '/unitno/' . $outputResult['newunitno'] . '/sqlite')) {
                        // Directory doesn't exist.
                        mkdir($relativepath . '/customer/' . $getObjectData->customerno . '/unitno/' . $outputResult['newunitno'] . '/sqlite', 0777, true) or die("Could not create directory");
                    }
                    //Create unit directory
                    if (!is_dir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'])) {
                        // Directory doesn't exist.
                        mkdir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'], 0777, true) or die("Could not create directory");
                    }
                    if (!is_dir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'] . '/sqlite')) {
                        // Directory doesn't exist.
                        mkdir($relativepath . '/customer/1/unitno/' . $outputResult['oldunitno'] . '/sqlite', 0777, true) or die("Could not create directory");
                    }
                    if ($getObjectData->sendmailr == 1) {
                        $mail = new stdClass();
                        $mail->username = $outputResult['username'];
                        $mail->realname = $outputResult['realname'];
                        $mail->email = $outputResult['email'];
                        $mail->vehicleno = $outputResult['vehicleno'];
                        $mail->oldunitno = $outputResult['oldunitno'];
                        $mail->oldsimno = $outputResult['oldsimno'];
                        $mail->newsimno = $outputResult['newsimno'];
                        $mail->newunitno = $outputResult['newunitno'];
                        $mail->elixir = $outputResult['elixir'];
                        $mail->comments = $getObjectData->comments;
                        $status = $this->SendEmailBothReplace($mail);
                        if ($status == 1) {
                            return "Successful sent";
                        } else {
                            return $status;
                        }
                    } else {
                        return "Successful replace";
                    }
                } else {
                    return "Successful";
                }
            } else {
                return "Failed";
            }
        } else {
            return null;
        }
    }

    public function removeBoth($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $todaysdate = date("Y-m-d H:i:s");
            $sp_params = "'" . $todaysdate . "'"
            . ",'" . $getObjectData->customerno . "'"
            . ",'" . $getObjectData->unitid . "'"
            . ",'" . $getObjectData->eteamid . "'"
            . ",'" . $getObjectData->lteamid . "'"
            . ",'" . $getObjectData->status . "'"
            . ",'" . $getObjectData->unsuccess_problem . "'"
            . ",'" . $getObjectData->incomplete_date . "'"
            . ",'" . $getObjectData->reschedule_date . "'"
            . ",'" . $getObjectData->bucketid . "'"
            . ",'" . $getObjectData->comments . "'"
                . ",@is_executed,@username,@realname,@email,@vehicleno,@unitno,@simno,@elixir";
            $queryCallSP = "CALL " . speedConstants::SP_REMOVE_BOTH . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            $outputParamsQuery = "SELECT @is_executed AS is_executed,@username AS username,@realname AS realname,@email AS email,@vehicleno AS vehicleno,@unitno AS unitno,@simno AS simno,@elixir AS elixir";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            if ($outputResult['is_executed'] == 1) {
                if ($getObjectData->status == 2) {
                    // Create unit directory
                    $relativepath = "../..";
                    if (!is_dir($relativepath . '/customer/1/unitno/' . $outputResult['unitno'])) {
                        // Directory doesn't exist.
                        mkdir($relativepath . '/customer/1/unitno/' . $outputResult['unitno'], 0777, true) or die("Could not create directory");
                    }
                    if (!is_dir($relativepath . '/customer/1/unitno/' . $outputResult['unitno'] . '/sqlite')) {
                        // Directory doesn't exist.
                        mkdir($relativepath . '/customer/1/unitno/' . $outputResult['unitno'] . '/sqlite', 0777, true) or die("Could not create directory");
                    }
                    if ($getObjectData->sendmailr == 1) {
                        $mail = new stdClass();
                        $mail->username = $outputResult['username'];
                        $mail->realname = $outputResult['realname'];
                        $mail->email = $outputResult['email'];
                        $mail->vehicleno = $outputResult['vehicleno'];
                        $mail->unitno = $outputResult['unitno'];
                        $mail->simno = $outputResult['simno'];
                        $mail->elixir = $outputResult['elixir'];
                        $mail->comments = $getObjectData->comments;
                        $status = $this->SendEmailBothRemove($mail);
                        if ($status == 1) {
                            return "Successful sent";
                        } else {
                            return $status;
                        }
                    } else {
                        return "Successful remove";
                    }
                } else {
                    return "Successful";
                }
            } else {
                return "Failed";
            }
        } else {
            return null;
        }
    }

    public function repair($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $todaysdate = date("Y-m-d H:i:s");
            $sp_params = "'" . $todaysdate . "'"
            . ",'" . $getObjectData->unitid . "'"
            . ",'" . $getObjectData->simcardid . "'"
            . ",'" . $getObjectData->eteamid . "'"
            . ",'" . $getObjectData->lteamid . "'"
            . ",'" . $getObjectData->customerno . "'"
            . ",'" . $getObjectData->status . "'"
            . ",'" . $getObjectData->unsuccess_problem . "'"
            . ",'" . $getObjectData->incomplete_date . "'"
            . ",'" . $getObjectData->reschedule_date . "'"
            . ",'" . $getObjectData->bucketid . "'"
            . ",'" . $getObjectData->comments . "'"
                . ",@is_executed,@username,@realname,@email,@vehicleno,@unitno,@simcardno,@elixir";
            $queryCallSP = "CALL " . speedConstants::SP_REPAIR . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            $outputParamsQuery = "SELECT @is_executed AS is_executed,@username AS username,@realname AS realname,@email AS email,@vehicleno AS vehicleno,@unitno AS unitno,@simcardno AS simcardno,@elixir AS elixir";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            if ($outputResult['is_executed'] == 1) {
                if ($getObjectData->status == 2) {
                    if ($getObjectData->sendmailr == 1) {
                        $mail = new stdClass();
                        $mail->realname = $outputResult['realname'];
                        $mail->vehicleno = $outputResult['vehicleno'];
                        $mail->email = $outputResult['email'];
                        $mail->unitnumber = $outputResult['unitno'];
                        $mail->simcardno = $outputResult['simcardno'];
                        $mail->elixir = $outputResult['elixir'];
                        $mail->comments = $getObjectData->comments;
                        $status = $this->SendEmailRepair($mail);
                        if ($status == 1) {
                            return "Successful sent";
                        } else {
                            return $status;
                        }
                    } else {
                        return "Successful repair";
                    }
                } else {
                    return "Successful";
                }
            } else {
                return "Failed";
            }
        } else {
            return null;
        }
    }

    public function reinstall($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $todaysdate = date("Y-m-d H:i:s");
            $sp_params = "'" . $todaysdate . "'"
            . ",'" . $getObjectData->unitid . "'"
            . ",'" . $getObjectData->eteamid . "'"
            . ",'" . $getObjectData->newvehicleno . "'"
            . ",'" . $getObjectData->lteamid . "'"
            . ",'" . $getObjectData->status . "'"
            . ",'" . $getObjectData->unsuccess_problem . "'"
            . ",'" . $getObjectData->incomplete_date . "'"
            . ",'" . $getObjectData->reschedule_date . "'"
            . ",'" . $getObjectData->bucketid . "'"
            . ",'" . $getObjectData->comments . "'"
                . ",@is_executed,@newvehicleno,@oldvehicleno,@username,@realname,@email,@elixir,@errormsg";
            $queryCallSP = "CALL " . speedConstants::SP_REINSTALLDEV . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            $outputParamsQuery = "SELECT @is_executed AS is_executed,@newvehicleno AS newvehicleno,@oldvehicleno AS oldvehicleno,@username AS username,@realname AS realname,@email AS email,@elixir AS elixir,@errormsg AS errormsg";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            if ($outputResult['is_executed'] == 1) {
                if ($getObjectData->status == 2) {
                    if ($getObjectData->sendmailr == 1) {
                        $mail = new stdClass();
                        $mail->realname = $outputResult['realname'];
                        $mail->oldvehicleno = $outputResult['oldvehicleno'];
                        $mail->newvehicleno = $outputResult['newvehicleno'];
                        $mail->elixir = $outputResult['elixir'];
                        $mail->comments = $getObjectData->comments;
                        $status = $this->SendEmailReinstall($mail);
                        if ($status == 1) {
                            return "Successful sent";
                        } else {
                            return $status;
                        }
                    } else {
                        return "Successful reinstall";
                    }
                } else {
                    return "Successful";
                }
            } else {
                return "Failed";
            }
        } else {
            return null;
        }
    }

    public function pullTeam($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $queryCallSP = "CALL " . speedConstants::SP_PULL_TEAM . "()";
            $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            if (!empty($arrResult)) {
                $team = array();
                foreach ($arrResult as $data) {
                    $teamno['teamid'] = $data['teamid'];
                    $teamno['name'] = $data['name'];
                    $team[] = $teamno;
                }
                return $team;
            }
        } else {
            return "WrongUserkey";
        }
        return null;
    }

    public function elixir_Unit_Sim($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $queryCallSP = "CALL " . speedConstants::SP_ELIXIR_UNIT . "(" . $getObjectData->teamid . ")";
            $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($arrResult)) {
                $final['unitno'] = $arrResult;
            } else {
                $final['unitno'] = array();
            }
            $queryCallSP = "CALL " . speedConstants::SP_ELIXIR_SIM . "(" . $getObjectData->teamid . ")";
            $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($arrResult)) {
                $final['simno'] = $arrResult;
            } else {
                $final['simno'] = array();
            }
            $this->db->ClosePDOConn($pdo);
            if (empty($final['unitno']) && empty($final['simno'])) {
                return null;
            } else {
                return $final;
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function cust_Unit_Sim_Veh($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $queryCallSP = "CALL " . speedConstants::SP_CUST_UNIT_SIM_VEH . "(" . $getObjectData->customerno . ")";
            $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            if (!empty($arrResult)) {
                $cust = array();
                foreach ($arrResult as $data) {
                    $custno['vehicleid'] = $data['vehicleid'];
                    $custno['vehicleno'] = $data['vehicleno'];
                    $custno['unitid'] = $data['uid'];
                    $custno['unitno'] = $data['unitno'];
                    if ($data["id"] == NULL) {
                        $custno["simid"] = "";
                    } else {
                        $custno['simid'] = $data['id'];
                    }
                    if ($data['simcardno'] == NULL) {
                        $custno['simcardno'] = "";
                    } else {
                        $custno['simcardno'] = $data['simcardno'];
                    }
                    $cust[] = $custno;
                }
                return $cust;
            } else {
                return NULL;
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function myticket($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $queryCallSP = "CALL " . speedConstants::SP_PULL_MY_TICKET . "(" . $getObjectData->teamid . ")";
            $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            if (!empty($arrResult)) {
                $tickets = array();
                foreach ($arrResult as $data) {
                    $tick['ticketstatus'] = $data['ticketstatus'];
                    $tick['uid'] = $data['uid'];
                    $tick['ticketid'] = $data['ticketid'];
                    $tick['title'] = $data['title'];
                    $tick['ticket_type'] = $data['ticket_type'];
                    $tick['tickettype'] = $data['tickettype'];
                    $tick['sub_ticket_issue'] = $data['sub_ticket_issue'];
                    $tick['customerid'] = $data['customerid'];
                    $tick['eclosedate'] = $data['eclosedate'];
                    $tick['priority'] = $data['priority'];
                    $tick['prname'] = $data['prname'];
                    $tick['create_on_date'] = $data['create_on_date'];
                    $tick['create_by'] = $data['create_by'];
                    $tick['status'] = $data['status'];
                    $tick['allot_to'] = $data['allot_to'];
                    $tick['description'] = $data['description'];
                    $tickets[] = $tick;
                }
                return $tickets;
            } else {
                return NULL;
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function pullStatusPriorityType($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $queryCallSP = "CALL " . speedConstants::SP_PULL_TICKET_STATUS . "()";
            $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            if (!empty($arrResult)) {
                $status = array();
                foreach ($arrResult as $data) {
                    $stat['id'] = $data['id'];
                    $stat['status'] = $data['status'];
                    $status[] = $stat;
                }
            }
            $pdo = $this->db->CreatePDOConn();
            $queryCallSP = "CALL " . speedConstants::SP_PULL_TICKET_PRIORITY . "()";
            $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            if (!empty($arrResult)) {
                $priority = array();
                foreach ($arrResult as $data) {
                    $prior['prid'] = $data['prid'];
                    $prior['priority'] = $data['priority'];
                    $priority[] = $prior;
                }
            }
            $pdo = $this->db->CreatePDOConn();
            $queryCallSP = "CALL " . speedConstants::SP_PULL_TICKET_TYPE . "()";
            $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            if (!empty($arrResult)) {
                $types = array();
                foreach ($arrResult as $data) {
                    $type['typeid'] = $data['typeid'];
                    $type['tickettype'] = $data['tickettype'];
                    $types[] = $type;
                }
            }
            $detail = array('status' => $status, 'priority' => $priority, 'type' => $types);
            return $detail;
        } else {
            return "WrongUserkey";
        }
    }

    public function addTicket($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $todaysdate = date("Y-m-d H:i:s");
            $platform = 2;
            $sp_params = "'" . $getObjectData->title . "'"
            . ",'" . $getObjectData->customerno . "'"
            . ",'" . $getObjectData->desc . "'"
            . ",'" . $getObjectData->tickettype . "'"
            . ",'" . $getObjectData->allot_to . "'"
            . ",'" . preg_replace('/^.{10}/', "$0 ", $getObjectData->raise_on_date) . "'"
            . ",'" . $getObjectData->expecteddate . "'"
            . ",'" . $getObjectData->send_mail_to_cust . "'"
            . ",'" . $getObjectData->ticketmailid . "'"
            . ",'" . $getObjectData->ccemailid . "'"
            . ",'" . $getObjectData->priority . "'"
            . ",'" . $todaysdate . "'"
            . ",'" . $getObjectData->createdby . "'"
            . ",'" . $getObjectData->lteamid . "'"
                . ",'" . $platform . "'"
                . ",@is_executed,@ticketid,@tickettypename,@priorityname,@allottoemail";
            $queryCallSP = "CALL " . speedConstants::SP_ADD_TICKET . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            $outputParamsQuery = "SELECT @is_executed AS is_executed,@ticketid AS ticketid,@tickettypename AS tickettypename,@priorityname AS priorityname,@allottoemail AS allottoemail";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            if ($outputResult['is_executed'] == 1) {
                $data_mail = array(
                    'tomail' => $outputResult['allottoemail'],
                    'title' => $getObjectData->title,
                    'ticket_type' => $outputResult['tickettypename'],
                    'ticket_typeid' => $getObjectData->tickettype,
                    'custid' => $getObjectData->customerno,
                    'ecd' => $getObjectData->expecteddate,
                    'priority' => $outputResult['priorityname'],
                    'ticketid' => $outputResult['ticketid'],
                    'ticket_desc' => $getObjectData->desc,
                    'sendmailid' => $getObjectData->ticketmailid,
                    'sendmailstatus' => $getObjectData->send_mail_to_cust,
                    'ticket_allot' => $getObjectData->allot_to,
                    'ccemailids' => $getObjectData->ccemailid,
                    'login_team_id' => $getObjectData->lteamid,
                    'status' => "Open"
                );
                $this->send_mail_allot_to($data_mail);
            } else {
                return "Failed";
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function editTicket($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $todaysdate = date("Y-m-d H:i:s");
            $getObjectData->toemailid = !empty($getObjectData->toemailid) ? $getObjectData->toemailid : 0;
            $getObjectData->ccemailid = !empty($getObjectData->ccemailid) ? $getObjectData->ccemailid : 0;
            $sp_params = "'" . $getObjectData->ticketid . "'"
            . ",'" . $getObjectData->customerno . "'"
            . ",'" . $getObjectData->ticket_allot . "'"
            . ",'" . $getObjectData->ticketdesc . "'"
            . ",'" . $getObjectData->ticket_status . "'"
            . ",'" . $getObjectData->created_by . "'"
            . ",'" . $getObjectData->expecteddate . "'"
            . ",'" . $getObjectData->ticket_type . "'"
            . ",'" . $getObjectData->sendemailstatus . "'"
            . ",'" . $getObjectData->toemailid . "'"
            . ",'" . $getObjectData->ccemailid . "'"
            . ",'" . $getObjectData->priorityid . "'"
            . ",'" . $todaysdate . "'"
            . ",'" . $getObjectData->note . "'"
            . ",'" . $getObjectData->create_type . "'"
                . ",@is_executed,@mailsendto,@createbyname,@allottoname";
            $queryCallSP = "CALL " . speedConstants::SP_EDIT_TICKET . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            $outputParamsQuery = "SELECT @is_executed AS is_executed,@mailsendto AS mailsendto,@createbyname AS createbyname,@allottoname AS allottoname";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            if ($outputResult['is_executed'] == 1) {
                $created_datetime = Sanitise::DateTime($todaysdate);
                $close_data_mail = array(
                    'tomail' => $outputResult['mailsendto'],
                    'closeby' => $outputResult['createbyname'],
                    'customer' => $getObjectData->customerno,
                    'ticket_desc' => $getObjectData->ticketdesc,
                    'title' => $getObjectData->title,
                    'created_datetime' => $created_datetime,
                    'status' => $getObjectData->ticket_status,
                    'ticketid' => $getObjectData->ticketid,
                    'ticket_type' => $getObjectData->ticket_type,
                    'ticket_allot' => $outputResult['allottoname'],
                    'customeremailids' => $this->get_email($getObjectData->toemailid),
                    'sendemailcust' => $getObjectData->sendemailstatus,
                    'priorityid' => $getObjectData->priorityid,
                    'ccemailids' => $this->get_email($getObjectData->ccemailid),
                    'note' => $getObjectData->note
                );
                $this->send_ticket_mail($close_data_mail);
            } else {
                return "Failed";
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function addNote($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $todaysdate = date("Y-m-d H:i:s");
            $sp_params = "'" . $getObjectData->ticketid . "'"
            . ",'" . $getObjectData->note . "'"
            . ",'" . $getObjectData->lteamid . "'"
                . ",'" . $todaysdate . "'"
                . ",@is_executed";
            $queryCallSP = "CALL " . speedConstants::SP_ADD_NOTE . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            $outputParamsQuery = "SELECT @is_executed AS is_executed";
            $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
            if ($outputResult['is_executed'] == 1) {
                return "Success";
            } else {
                return "Failed";
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function pullNote($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $todaysdate = date("Y-m-d H:i:s");
            $sp_params = "'" . $getObjectData->ticketid . "'";
            $queryCallSP = "CALL " . speedConstants::SP_PULL_NOTE . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            if (!empty($arrResult)) {
                $notes = array();
                foreach ($arrResult as $data) {
                    $note['note'] = $data['note'];
                    $note['name'] = isset($data['realname']) ? $data['realname'] : $data['name'];
                    $note['name'] = isset($note['name']) ? $note['name'] : '';
                    $note['create_on_date'] = $data['create_on_date'];
                    $notes[] = $note;
                }
                return $notes;
            } else {
                return NULL;
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function pullemail($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $todaysdate = date("Y-m-d H:i:s");
            $sp_params = "'" . $getObjectData->customerno . "'";
            $queryCallSP = "CALL " . speedConstants::SP_PULL_EMAIL . "($sp_params)";
            $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            if (!empty($arrResult)) {
                $emails = array();
                foreach ($arrResult as $data) {
                    $email['eid'] = $data['eid'];
                    $email['email_id'] = $data['email_id'];
                    $emails[] = $email;
                }
                return $emails;
            } else {
                return NULL;
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function check_userkey($userkey) {
        $sql = "select * from team where userkey='" . $userkey . "'";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $row = $this->db->fetch_array($record);
        $retarray = array();
        $retarray['status'] = "unsuccessful";
        if ($row['userkey'] != "") {
            $retarray['status'] = "successful";
        }
        return $retarray;
    }

    public function failure($text) {
        return array('Status' => '0', 'Message' => $text);
    }

    public function success($message, $result) {
        return array('Status' => '1', 'Message' => $message, 'Result' => $result);
    }

    public function SendEmail($mail) {
        // send email
        $arrTo = array($mail->email);
        $strCCMailIds = "";
        $strBCCMailIds = "";
        $subject = "Unit Installation Details";
        $message = file_get_contents('../../../emailtemplates/registerDevice.html');
        $message = str_replace("{{REALNAME}}", $mail->realname, $message);
        $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
        $message = str_replace("{{UNITNO}}", $mail->unitnumber, $message);
        $message = str_replace("{{SIMCARD}}", $mail->simcardno, $message);
        $message = str_replace("{{INSTALLDATE}}", $mail->installdate, $message);
        $message = str_replace("{{EXPIRYDATE}}", $mail->expirydate, $message);
        $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
        $message = str_replace("{{COMMENTS}}", $mail->comment, $message);
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = 1;
        $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
        return $isSmsSent;
    }

    public function SendEmailSuspect($mail) {
        // send email
        $arrTo = array($mail->email);
        $strCCMailIds = "";
        $strBCCMailIds = "";
        $subject = $mail->subject;
        $message = file_get_contents('../../../emailtemplates/suspectDevice.html');
        $message = str_replace("{{REALNAME}}", $mail->realname, $message);
        $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
        $message = str_replace("{{UNITNO}}", $mail->unitno, $message);
        $message = str_replace("{{SIMCARDNO}}", $mail->simcardno, $message);
        $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
        $message = str_replace("{{COMMENTS}}", $mail->comment, $message);
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = 1;
        $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
        return $isSmsSent;
    }

    public function SendEmailReplace($mail) {
        // send email
        $arrTo = array($mail->email);
        $strCCMailIds = "";
        $strBCCMailIds = "";
        $subject = "Unit Installation Details";
        $message = file_get_contents('../../../emailtemplates/replaceDevice.html');
        $message = str_replace("{{REALNAME}}", $mail->realname, $message);
        $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
        $message = str_replace("{{OLDUNITNO}}", $mail->oldunitno, $message);
        $message = str_replace("{{NEWUNITNO}}", $mail->newunitno, $message);
        $message = str_replace("{{SIMCARD}}", $mail->simcard, $message);
        $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
        $message = str_replace("{{COMMENTS}}", $mail->comments, $message);
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = 1;
        $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
        return $isSmsSent;
    }

    public function SendEmailSimReplace($mail) {
        // send email
        $arrTo = array($mail->email);
        $strCCMailIds = "";
        $strBCCMailIds = "";
        $subject = "Simcard Replace Details";
        $message = file_get_contents('../../../emailtemplates/replaceSim.html');
        $message = str_replace("{{REALNAME}}", $mail->realname, $message);
        $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
        $message = str_replace("{{OLDSIMCARD}}", $mail->oldsimcardno, $message);
        $message = str_replace("{{NEWSIMCARD}}", $mail->newsimcardno, $message);
        $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
        $message = str_replace("{{COMMENTS}}", $mail->comments, $message);
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = 1;
        $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
        return $isSmsSent;
    }

    public function SendEmailBothReplace($mail) {
        // send email
        $arrTo = array($mail->email);
        $strCCMailIds = "";
        $strBCCMailIds = "";
        $subject = "Simcard Replace Details";
        $message = file_get_contents('../../../emailtemplates/replaceUnitSim.html');
        $message = str_replace("{{REALNAME}}", $mail->realname, $message);
        $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
        $message = str_replace("{{OLDUNIT}}", $mail->oldunitno, $message);
        $message = str_replace("{{NEWUNIT}}", $mail->newunitno, $message);
        $message = str_replace("{{OLDSIM}}", $mail->oldsimno, $message);
        $message = str_replace("{{NEWSIM}}", $mail->newsimno, $message);
        $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
        $message = str_replace("{{COMMENTS}}", $mail->comments, $message);
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = 1;
        $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
        return $isSmsSent;
    }

    public function SendEmailRepair($mail) {
        // send email
        $arrTo = array($mail->email);
        $strCCMailIds = "";
        $strBCCMailIds = "";
        $subject = "Unit Repair Details";
        $message = file_get_contents('../../../emailtemplates/repairDevice.html');
        $message = str_replace("{{REALNAME}}", $mail->realname, $message);
        $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
        $message = str_replace("{{UNITNO}}", $mail->unitnumber, $message);
        $message = str_replace("{{SIMCARD}}", $mail->simcardno, $message);
        $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
        $message = str_replace("{{COMMENTS}}", $mail->comment, $message);
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = 1;
        $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
        return $isSmsSent;
    }

    public function SendEmailReinstall($mail) {
        // send email
        $arrTo = array($mail->email);
        $strCCMailIds = "";
        $strBCCMailIds = "";
        $subject = "Unit Repair Details";
        $message = file_get_contents('../../../emailtemplates/reinstall.html');
        $message = str_replace("{{REALNAME}}", $mail->realname, $message);
        $message = str_replace("{{OLDVEHICLENO}}", $mail->vehicleno, $message);
        $message = str_replace("{{NEWVEHICLENO}}", $mail->unitnumber, $message);
        $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
        $message = str_replace("{{COMMENTS}}", $mail->comment, $message);
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = 1;
        $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
        return $isSmsSent;
    }

    public function SendEmailBothRemove($mail) {
        // send email
        $arrTo = array($mail->email);
        $strCCMailIds = "";
        $strBCCMailIds = "";
        $subject = "Simcard Replace Details";
        $message = file_get_contents('../../../emailtemplates/removeUnitSim.html');
        $message = str_replace("{{REALNAME}}", $mail->realname, $message);
        $message = str_replace("{{VEHICLENO}}", $mail->vehicleno, $message);
        $message = str_replace("{{UNITNO}}", $mail->unitno, $message);
        $message = str_replace("{{SIMNO}}", $mail->simno, $message);
        $message = str_replace("{{ELIXIR}}", $mail->elixir, $message);
        $message = str_replace("{{COMMENTS}}", $mail->comments, $message);
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = 1;
        $isSmsSent = sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
        return $isSmsSent;
    }

    public function send_mail_allot_to($data_mail) {
        $message = "";
        $messageTeam = "";
        $tomail = $data_mail['tomail'];
        $title = $data_mail['title'];
        $ticket_type = $data_mail['ticket_type'];
        $custid = $data_mail['custid'];
        $custname = $this->get_customername($custid);
        $ecd = $data_mail['ecd'];
        $priority = $data_mail['priority'];
        $ticketid = "ET00" . $data_mail['ticketid'];
        $ticket_desc = $data_mail['ticket_desc'];
        $createby = $data_mail['login_team_id'];
        $sendmailid = $data_mail['sendmailid'];
        $sendmailstatus = $data_mail['sendmailstatus'];
        $createdname = $this->get_teamname($createby);
        $allotToName = $this->get_teamname($data_mail['ticket_allot']);
        $ticketstatus = $data_mail['status'];
        $ticket_typeid = $data_mail['ticket_typeid'];
        $ccemailids = $data_mail['ccemailids'];
        $toTeam = array($tomail);
        $subjectTeam = "Elixia Speed - Ticket No: " . $ticketid . "(Alloted to - " . $allotToName . " ) - Ticket For: " . ucfirst($ticket_type) . " (Customer No: " . $custid . ")";
        $strCCMailIds = "";
        $strBCCMailIds = "";
        $strTeamBCCMailIds = "sanketsheth1@gmail.com";
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $messageTeam = "
        <table>
            <tr><td>Ticket No : </td><td>" . $ticketid . "</td></tr>
            <tr><td>Created By : </td><td>" . $createdname . "</td></tr>
            <tr><td>Expected Closure date : </td><td>" . date("d-M-Y", strtotime($ecd)) . "</td></tr>
            <tr><td>Title : </td><td>" . $title . "</td></tr>
            <tr><td>Customer : </td><td>" . $custname . "</td></tr>
            <tr><td>Ticket Type : </td><td>" . $ticket_type . "</td></tr>
            <tr><td>Priority : </td><td>" . $priority . "</td></tr>
            <tr><td>Description :</td><td>" . $ticket_desc . "</td></tr>
            <tr><td>Status :</td><td>" . $ticketstatus . "</td></tr>
        </table>";
        if (!empty($ccemailids)) {
            $strCCMailIds = $this->get_email($ccemailids);
            $strCCMailIds = "'" . implode(',', $strCCMailIds) . "'";
        }
        if ($sendmailstatus == 1 && isset($sendmailid) && !empty($sendmailid)) {
            $sendmailidtoteam = $sendmailid;
            $to = $this->get_email($sendmailid);
            $subject = "Elixia Speed - Support Ticket No: " . $ticketid . " - Ticket For: " . ucfirst($ticket_type) . " (Customer No: " . $custid . ")";
            $message .= "<h4> A new support ticket has been created for you. Kindly interact with Elixia team providing the ticket number for further assistance. </h4>";
            $message .= $messageTeam;
            sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);
        }
        sendMailUtil($toTeam, $strCCMailIds, $strTeamBCCMailIds, $subjectTeam, $messageTeam, $attachmentFilePath, $attachmentFileName);
    }

    public function get_customername($id) {
        $db = new DatabaseManager();
        $SQL = sprintf("select customercompany from " . DB_PARENT . ".customer where customerno=" . $id);
        $db->executeQuery($SQL);
        while ($row = $db->get_nextRow()) {
            $customercompany = $row["customercompany"];
        }
        return $customercompany;
    }

    public function get_teamname($id) {
        $db = new DatabaseManager();
        $SQL = sprintf("select name from " . DB_PARENT . ".team where teamid=" . $id);
        $db->executeQuery($SQL);
        while ($row = $db->get_nextRow()) {
            $teamname = $row["name"];
        }
        return $teamname;
    }

    public function get_email($id) {
        $db = new DatabaseManager();
        $SQL = sprintf("select email_id from " . DB_PARENT . ".report_email_list where eid IN (" . $id . ");");
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $email[] = $row["email_id"];
            }
            return $email;
        } else {
            return NULL;
        }
    }

    public function send_ticket_mail($close_data_mail) {
        $message = "";
        $tomail = $close_data_mail['tomail'];
        $closeby = $close_data_mail['closeby'];
        $ticket_desc = $close_data_mail['ticket_desc'];
        $created_datetime = $close_data_mail['created_datetime'];
        $status = $close_data_mail['status'];
        $ticketid = "ET00" . $close_data_mail['ticketid'];
        $title = $close_data_mail['title'];
        $customer = $close_data_mail['customer'];
        $ticket_type = $close_data_mail['ticket_type'];
        $allotToName = $close_data_mail['ticket_allot'];
        $customeremailids = $close_data_mail['customeremailids'];
        $sendemailcust = $close_data_mail['sendemailcust'];
        $priorityid = $close_data_mail['priorityid'];
        $note = $close_data_mail['note'];
        $toteam = array($tomail);
        $strCCMailIds = '';
        if (!empty($close_data_mail['ccemailids'])) {
            $strCCMailIds = "'" . implode(',', $close_data_mail['ccemailids']) . "'";
        }
        $strBCCMailIds = "sanketsheth@elixiatech.com";
        $attachmentFilePath = "";
        $attachmentFileName = "";
        $subject = "Elixia Speed - Ticket No: " . $ticketid . "(Alloted to - " . $allotToName . " ) - Ticket For: " . ucfirst($ticket_type) . " (Customer No: " . $customer . ")";
        $message = file_get_contents('../../../emailtemplates/ticketMailTemplate.html');
        $message = str_replace("{{SUBJECT}}", $subject, $message);
        $message = str_replace("{{TICKETID}}", $ticketid, $message);
        $message = str_replace("{{TICKETTITLE}}", $title, $message);
        $message = str_replace("{{TICKETSTATUS}}", $this->get_status($status), $message);
        $message = str_replace("{{TICKETDESC}}", $ticket_desc, $message);
        $message = str_replace("{{NOTE}}", $note, $message);
        if ($close_data_mail['status'] == 2) {
            $close_data = " <tr><td><b>Ticket Closed By :</b></td><td colspan='3'>" . $closeby . "</td></tr>
                            <tr><td><b>Closed date :</b></td><td colspan='3'>" . $created_datetime . "</td></tr>";
            $message = str_replace("{{CLOSEDATA}}", $close_data, $message);
        } else {
            $message = str_replace("{{CLOSEDATA}}", '', $message);
        }
        sendMailUtil($toteam, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);
        if ($sendemailcust == 1 && isset($customeremailids) && !empty($customeremailids)) {
            $to = $customeremailids;
            $subject = "Elixia Speed - Support Ticket No: " . $ticketid;
            sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);
        }
    }

    public function get_status($statusid) {
        $db = new DatabaseManager();
        $SQL = sprintf("select status from " . DB_PARENT . ".ticket_status where id=" . $statusid . " LIMIT 1");
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $data = $row['status'];
            }
        }
        return $data;
    }

// ***** SALES MODULE ***** //
    public function pullstage($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
// successful
            $sql = "SELECT stageid, stage_name "
                . " FROM sales_stage ";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['stageid'] = $row["stageid"];
                    $arr_p['stage_name'] = $row["stage_name"];
                    $json_p[] = $arr_p;
                }
                return $json_p;
            } else {
                return null;
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function pullsource($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
// successful
            $sql = "SELECT sourceid, source_name "
                . " FROM sales_source ";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['sourceid'] = $row["sourceid"];
                    $arr_p['source_name'] = $row["source_name"];
                    $json_p[] = $arr_p;
                }
                return $json_p;
            } else {
                return null;
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function pullproduct($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
// successful
            $sql = "SELECT productid, product_name "
                . " FROM sales_product ";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['productid'] = $row["productid"];
                    $arr_p['product_name'] = $row["product_name"];
                    $json_p[] = $arr_p;
                }
                return $json_p;
            } else {
                return null;
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function pullindustry($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
// successful
            $sql = "SELECT industryid, industry_type "
                . " FROM sales_industry_type ";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['industryid'] = $row["industryid"];
                    $arr_p['industry_type'] = $row["industry_type"];
                    $json_p[] = $arr_p;
                }
                return $json_p;
            } else {
                return null;
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function pullmode($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
// successful
            $sql = "SELECT modeid, mode "
                . " FROM sales_mode ";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['modeid'] = $row["modeid"];
                    $arr_p['mode'] = $row["mode"];
                    $json_p[] = $arr_p;
                }
                return $json_p;
            } else {
                return null;
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function pullcustomers($getObjectData) {
        $todaysdate = date("Y-m-d H:i:s");
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
            $sql = "SELECT sales_pipeline.timestamp, sales_pipeline.pipelineid, sales_pipeline.company_name, sales_product.product_name, sales_stage.stage_name, sales_pipeline.quotation_request "
            . " FROM sales_pipeline INNER JOIN sales_product ON sales_product.productid = sales_pipeline.productid INNER JOIN sales_stage ON sales_stage.stageid = sales_pipeline.stageid WHERE sales_pipeline.isdeleted = 0 AND sales_pipeline.stageid NOT IN (9,10) AND sales_pipeline.teamid = " . $getObjectData->teamid . " ORDER BY sales_pipeline.timestamp DESC";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['pipelineid'] = $row["pipelineid"];
                    $arr_p['pipelineid_display'] = "POO" . $row["pipelineid"];
                    $arr_p['company_name'] = $row["company_name"];
                    $arr_p['product_name'] = $row["product_name"];
                    $arr_p['stage_name'] = $row["stage_name"];
                    $arr_p["quotation_request"] = $row["quotation_request"];
                    $date1 = date_create_from_format('Y-m-d H:i:s', $row["timestamp"]);
                    $date2 = date_create_from_format('Y-m-d H:i:s', $todaysdate);
                    $diff = date_diff($date1, $date2);
                    $str = '';
                    //print_r($diff);
                    if ($diff->y > 0) {
                        $str .= $diff->y . " year";
                        if ($diff->y > 1) {
                            $str .= "s";
                        }
                    }if ($diff->m > 0) {
                        if ($str != '') {
                            $str .= ', ';
                        }
                        $str .= $diff->m . " month";
                        if ($diff->m > 1) {
                            $str .= "s";
                        }
                    }
                    if ($diff->d > 0) {
                        if ($str != '') {
                            $str .= " and ";
                        }
                        $str .= $diff->d . " day";
                        if ($diff->d > 1) {
                            $str .= "s";
                        }
                    }if ($str == '') {
                        if ($diff->h > 0) {
                            $str .= $diff->h . " hour";
                            if ($diff->h > 1) {
                                $str .= "s";
                            }
                        }
                        if ($diff->i > 0) {
                            if ($str != '') {
                                $str .= ', ';
                            }
                            $str .= $diff->i . " minute";
                            if ($diff->i > 1) {
                                $str .= 's ';
                            }
                        }
                    }
                    if ($str == '') {
                        $str = "Just now";
                    } else {
                        $str .= " ago";
                    }
                    $arr_p['lastupdated'] = $str;
                    $json_p[] = $arr_p;
                }
                return $json_p;
            } else {
                return null;
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function pullcustomers_frozen($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $todaysdate = date("Y-m-d H:i:s");
        $json_p = array();
        if ($validation['status'] == "successful") {
// successful
            $sql = "SELECT sales_pipeline.timestamp, sales_pipeline.pipelineid, sales_pipeline.company_name, sales_product.product_name, sales_stage.stage_name, sales_pipeline.quotation_request "
            . " FROM sales_pipeline INNER JOIN sales_product ON sales_product.productid = sales_pipeline.productid INNER JOIN sales_stage ON sales_stage.stageid = sales_pipeline.stageid WHERE sales_pipeline.isdeleted = 0 AND sales_pipeline.stageid IN (9,10) AND sales_pipeline.teamid = " . $getObjectData->teamid . " ORDER BY sales_pipeline.timestamp DESC";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['pipelineid'] = $row["pipelineid"];
                    $arr_p['pipelineid_display'] = "POO" . $row["pipelineid"];
                    $arr_p['company_name'] = $row["company_name"];
                    $arr_p['product_name'] = $row["product_name"];
                    $arr_p['stage_name'] = $row["stage_name"];
                    $date1 = date_create_from_format('Y-m-d H:i:s', $row["timestamp"]);
                    $date2 = date_create_from_format('Y-m-d H:i:s', $todaysdate);
                    $diff = date_diff($date1, $date2);
                    $str = '';
                    if ($diff->y > 0) {
                        $str .= $diff->y . " year";
                        if ($diff->y > 1) {
                            $str .= "s";
                        }
                    }if ($diff->m > 0) {
                        if ($str != '') {
                            $str .= ', ';
                        }
                        $str .= $diff->m . " month";
                        if ($diff->m > 1) {
                            $str .= "s";
                        }
                    }
                    if ($diff->d > 0) {
                        if ($str != '') {
                            $str .= " and ";
                        }
                        $str .= $diff->d . " day";
                        if ($diff->d > 1) {
                            $str .= "s";
                        }
                    }if ($str == '') {
                        if ($diff->h > 0) {
                            $str .= $diff->h . " hour";
                            if ($diff->h > 1) {
                                $str .= "s";
                            }
                        }
                        if ($diff->i > 0) {
                            if ($str != '') {
                                $str .= ' and ';
                            }
                            $str .= $diff->i . " minute";
                            if ($diff->i > 1) {
                                $str .= 's ';
                            }
                        }
                    }
                    if ($str == '') {
                        $str = "Just now";
                    } else {
                        $str .= " ago";
                    }
                    $arr_p['lastupdated'] = $str;
                    $arr_p['quotation_request'] = $row["quotation_request"];
                    $json_p[] = $arr_p;
                }
                return $json_p;
            } else {
                return null;
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function pullcustomerdetails($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
// successful
            $sql = "SELECT pipelineid, company_name, productid, stageid, pipeline_date, sourceid, industryid, modeid, location, remarks, timestamp "
            . " FROM sales_pipeline WHERE isdeleted = 0 AND teamid = " . $getObjectData->teamid . " AND pipelineid = " . $getObjectData->pipelineid;
            $record = $this->db->query($sql, __FILE__, __LINE__);
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['pipelineid'] = $row["pipelineid"];
                    $arr_p['pipelineid_display'] = "POO" . $row["pipelineid"];
                    $arr_p['company_name'] = $row["company_name"];
                    $arr_p['productid'] = $row["productid"];
                    $arr_p['stageid'] = $row["stageid"];
                    $arr_p['pipeline_date'] = $row["pipeline_date"];
                    $arr_p['sourceid'] = $row["sourceid"];
                    $arr_p['industryid'] = $row["industryid"];
                    $arr_p['modeid'] = $row["modeid"];
                    $arr_p['location'] = $row["location"];
                    $arr_p['remarks'] = $row["remarks"];
                    $arr_p['timestamp'] = $row["timestamp"];
                    $json_p[] = $arr_p;
                }
                return $json_p;
            } else {
                return null;
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function pullpipelinehistory($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
// successful
            $sql = "SELECT * "
            . " FROM sales_pipeline_history INNER JOIN sales_stage ON sales_stage.stageid = sales_pipeline_history.stageid  WHERE sales_pipeline_history.isdeleted = 0 AND sales_pipeline_history.pipelineid = " . $getObjectData->pipelineid . " ORDER BY sales_pipeline_history.timestamp DESC";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['pipelineid'] = $row["pipelineid"];
                    $arr_p['pipelineid'] = "P00" . $row["pipelineid"];
                    $arr_p['pipeline_date'] = $row["pipeline_date"];
                    $arr_p['stagename'] = $row["stage_name"];
                    $arr_p['remarks'] = $row["remarks"];
                    $arr_p['timestamp'] = $row["timestamp"];
                    $json_p[] = $arr_p;
                }
                return $json_p;
            } else {
                return null;
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function pullreminders($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
// successful
            $sql = "SELECT reminderid, reminder_datetime, content, pipelineid, status "
            . " FROM sales_reminder WHERE isdeleted = 0 AND teamid_creator = " . $getObjectData->teamid . " AND pipelineid = " . $getObjectData->pipelineid . " ORDER BY reminder_datetime DESC";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['reminderid'] = $row["reminderid"];
                    $arr_p['reminder_datetime'] = $row["reminder_datetime"];
                    $arr_p['content'] = $row["content"];
                    $arr_p['pipelineid'] = $row["pipelineid"];
                    $arr_p['status'] = $row["status"];
                    $json_p[] = $arr_p;
                }
                return $json_p;
            } else {
                return null;
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function pullusers($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
// successful
            $sql = "SELECT contactid, pipelineid, name, designation, phone, email "
            . " FROM sales_contact WHERE isdeleted = 0 AND teamid_creator = " . $getObjectData->teamid . " AND pipelineid = " . $getObjectData->pipelineid;
            $record = $this->db->query($sql, __FILE__, __LINE__);
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['contactid'] = $row["contactid"];
                    $arr_p['pipelineid'] = $row["pipelineid"];
                    $arr_p['name'] = $row["name"];
                    $arr_p['designation'] = $row["designation"];
                    $arr_p['phone'] = $row["phone"];
                    $arr_p['email'] = $row["email"];
                    $json_p[] = $arr_p;
                }
                return $json_p;
            } else {
                return null;
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function setstage($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
            // successful
            $sql = "UPDATE sales_pipeline SET stageid = " . $getObjectData->stageid . ", update_platform = " . $getObjectData->create_platform . " WHERE pipelineid = " . $getObjectData->pipelineid;
            $this->db->query($sql, __FILE__, __LINE__);
            return "Success";
        }
    }

    public function updatereminder($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
            // successful
            $sql = "UPDATE sales_reminder SET reminder_datetime = '" . $getObjectData->reminder_datetime . "', content = '" . $getObjectData->content . "' WHERE reminderid = " . $getObjectData->reminderid;
            $this->db->query($sql, __FILE__, __LINE__);
            return "Success";
        }
    }

    public function updateuser($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
            // successful
            $sql = "UPDATE sales_contact SET name = '" . $getObjectData->name . "', designation = '" . $getObjectData->designation . "', phone = '" . $getObjectData->phone . "',email = '" . $getObjectData->email . "' WHERE contactid = " . $getObjectData->contactid;
            $this->db->query($sql, __FILE__, __LINE__);
            return "Success";
        }
    }

    public function updatecustomer($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $todaysdate = date("Y-m-d H:i:s");
        $json_p = array();
        if ($validation['status'] == "successful") {
            $sql = "UPDATE sales_pipeline SET company_name = '" . $getObjectData->company_name . "', sourceid = " . $getObjectData->sourceid . ",productid = " . $getObjectData->productid . ", industryid = " . $getObjectData->industryid . ", modeid = " . $getObjectData->modeid . ", location = '" . $getObjectData->location . "', remarks = '" . $getObjectData->remarks . "', loss_reason = '" . $getObjectData->loss_reason . "', update_platform = " . $getObjectData->create_platform . ", timestamp = '" . $todaysdate . "' WHERE pipelineid = " . $getObjectData->pipelineid;
            $this->db->query($sql, __FILE__, __LINE__);
            if ($getObjectData->quotation_request != 0) {
                $sql = "UPDATE sales_pipeline SET quotation_request = '" . $getObjectData->quotation_request . "', quotation_text = '" . $getObjectData->quotation_text . "' WHERE pipelineid = " . $getObjectData->pipelineid;
                $this->db->query($sql, __FILE__, __LINE__);
            }
            $sql = "INSERT INTO "
                . "`sales_pipeline_history` "
                . "(`pipelineid`, `pipeline_date`, `company_name`, `sourceid`, `productid`, `industryid`, `modeid`, `teamid`, `location`, `remarks`, `stageid`, `timestamp`, `isdeleted`, `quotation_request`, `quotation_text`) "
                . "VALUES ($getObjectData->pipelineid, '$getObjectData->pipeline_date', '$getObjectData->company_name', $getObjectData->sourceid,$getObjectData->productid, $getObjectData->industryid,$getObjectData->modeid,$getObjectData->teamid,'$getObjectData->location','$getObjectData->remarks',1,'$todaysdate',0,'$getObjectData->quotation_request','$getObjectData->quotation_text'); ";
            $this->db->query($sql, __FILE__, __LINE__);
            function getsourcename($source) {
                $sourcename = "";
                $db = new DatabaseManager();
                $SQL = sprintf("select source_name from " . DB_PARENT . ".sales_source where sourceid=" . $source);
                $db->executeQuery($SQL);
                while ($row = $db->get_nextRow()) {
                    $sourcename = $row["source_name"];
                }
                return $sourcename;
            }
            $sql = sprintf("INSERT INTO " . DB_PARENT . ".`sales_pipeline_history`(
                                        `pipelineid`,
                                        `stageid`,
                                        `pipeline_date`,
                                        `company_name`,
                                        `sourceid`,
                                        `productid`,
                                        `industryid`,
                                        `modeid`,
                                        `location`,
                                        `remarks`,
                                        `loss_reason`,
                                        `update_platform`,
                                        `timestamp`)
                                VALUES (%d,%d,'%s','%s',%d,%d,%d,%d,'%s','%s','%s',%d, '%s');"
                , Sanitise::Long($getObjectData->pipelineid)
                , Sanitise::Long($getObjectData->stageid)
                , Sanitise::String($getObjectData->pipeline_date)
                , Sanitise::String($getObjectData->company_name)
                , Sanitise::Long($getObjectData->sourceid)
                , Sanitise::Long($getObjectData->productid)
                , Sanitise::Long($getObjectData->industryid)
                , Sanitise::Long($getObjectData->modeid)
                , Sanitise::String($getObjectData->location)
                , Sanitise::String($getObjectData->remarks)
                , Sanitise::String($getObjectData->loss_reason)
                , Sanitise::Long($getObjectData->create_platform)
                , Sanitise::String($todaysdate));
            $this->db->query($sql, __FILE__, __LINE__);
            return "Success";
        }
    }

    public function deleteuser($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
// successful
            $sql = "UPDATE sales_contact SET isdeleted = 1 WHERE contactid = " . $getObjectData->contactid;
            $this->db->query($sql, __FILE__, __LINE__);
            return "Success";
        }
    }

    public function deletecustomer($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
            $sql = "UPDATE sales_pipeline SET isdeleted = 1, delete_platform = " . $getObjectData->create_platform . " WHERE pipelineid = " . $getObjectData->pipelineid;
            $this->db->query($sql, __FILE__, __LINE__);
            return "Success";
        }
    }

    public function deletereminder($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
            // successful
            $sql = "UPDATE sales_reminder SET isdeleted = 1 WHERE reminderid = " . $getObjectData->reminderid;
            $this->db->query($sql, __FILE__, __LINE__);
            return "Success";
        }
    }

    public function pull_sales_dashboard($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
// successful
            $arr_p['totalscore'] = "25 points";
            $arr_p['customer_won'] = "0";
            $arr_p['licenses_sold'] = "0";
            $arr_p['demo_devices_aged'] = "0";
            $arr_p['customers_lost'] = "0";
            $arr_p['conversion_ratio'] = "0 %";
            $arr_p['revenue_generated'] = "Rs 0 /-";
            $arr_p['incentive_generated'] = "Rs 0 /-";
            $arr_p['average_sales_closure_days'] = "0";
            $json_p[] = $arr_p;
            return $json_p;
        } else {
            return "WrongUserkey";
        }
    }

    public function pull_elixia_game_result($getObjectData) {
        function get_target_achieved($id, $teamid) {
            $db = new DatabaseManager();
            $today = date('M Y');
            $date_array = explode(' ', $today);
            $count = 0;
            if ($id == 1) {
                $SQL1 = "   SELECT  count(customerno) AS count1
                            FROM    customer
                            WHERE       salesid='" . $teamid . "'
                            AND     year(createdtime) LIKE '%" . $date_array['1'] . "%'
                            AND     monthname(createdtime) LIKE '%" . $date_array['0'] . "%'";
                $db->executeQuery($SQL1);
                if ($db->get_rowCount() > 0) {
                    while ($row = $db->get_nextRow()) {
                        $count += $row['count1'];
                    }
                }
            }
            if ($id == 2) {
            }
            if ($id == 3) {
            }
            if ($id == 4) {
            }
            if ($id == 5) {
                $SQL1 = "   SELECT  count(*) AS count1
                            FROM    sales_pipeline_history
                            WHERE       stageid = 5 AND teamid = '" . $teamid . "'
                            AND     year(timestamp) LIKE '%" . $date_array['1'] . "%'
                            AND     monthname(timestamp) NOT LIKE '%" . $date_array['0'] . "%'";
                $db->executeQuery($SQL1);
                if ($db->get_rowCount() > 0) {
                    while ($row = $db->get_nextRow()) {
                        $count += $row['count1'];
                    }
                }
            }
            if ($id == 6) {
                $SQL1 = "   SELECT  count(pipelineid) AS count1
                            FROM    sales_pipeline
                            WHERE       teamid='" . $teamid . "'
                            AND     year(timestamp) LIKE '%" . $date_array['1'] . "%'
                            AND     monthname(timestamp) LIKE '%" . $date_array['0'] . "%' AND stageid IN (9,10)";
                $db->executeQuery($SQL1);
                if ($db->get_rowCount() > 0) {
                    while ($row = $db->get_nextRow()) {
                        $count += $row['count1'];
                    }
                }
            }
            if ($id == 7) {
                $SQL1 = "   SELECT (management_points + (SELECT points FROM management_points WHERE team_id='" . $teamid . "'
                            AND     year(timestamp) LIKE '%" . $date_array['1'] . "%'
                            AND     monthname(timestamp) LIKE '%" . $date_array['0'] . "%' )) as count1"
                    . " FROM    team"
                    . " WHERE teamid = '" . $teamid . "'";
                $db->executeQuery($SQL1);
                if ($db->get_rowCount() > 0) {
                    while ($row = $db->get_nextRow()) {
                        $count += $row['count1'];
                    }
                }
            }
            return $count;
        }
        function get_points_collected($id, $target_achieved, $base_points, $value) {
            $count = $base_points + ($target_achieved * $value);
            return $count;
        }
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
// successful
            $sql = "SELECT * "
            . " FROM perfomance_parameter WHERE role IN ('" . $getObjectData->role . "', 'All')";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $status = 1;
                    $arr_p['id'] = $row["id"];
                    $arr_p['name'] = $row["name"];
                    $arr_p['target'] = $row["target"];
                    $arr_p['constraint'] = $row["constraint"];
                    $arr_p['score_desc'] = $row["point_desc"];
                    $arr_p['target_achieved'] = get_target_achieved($row["id"], $getObjectData->teamid);
                    $arr_p['points_collected'] = get_points_collected($row["id"], $arr_p['target_achieved'], $row["base_points"], $row["value"]);
                    if ($row["constraint"] == 1) {
                        $constraint = ">";
                        if ($arr_p['target_achieved'] > $arr_p['target']) {
                            $status = 0;
                        }
                    } elseif ($row["constraint"] == 2) {
                        $constraint = "<";
                        if ($arr_p['target_achieved'] < $arr_p['target']) {
                            $status = 0;
                        }
                    } elseif ($row["constraint"] == 3) {
                        $constraint = "=";
                        if ($arr_p['target_achieved'] == $arr_p['target']) {
                            $status = 0;
                        }
                    } elseif ($row["constraint"] == 4) {
                        $constraint = ">=";
                        if ($arr_p['target_achieved'] >= $arr_p['target']) {
                            $status = 0;
                        }
                    } else {
                        $constraint = "=";
                    }
                    $arr_p['name'] = $row["name"] . " ( " . $constraint . " " . $row["target"] . " )";
                    $arr_p['status'] = $status;
                    $json_p[] = $arr_p;
                }
                return $json_p;
            } else {
                return null;
            }
        } else {
            return "WrongUserkey";
        }
    }

    public function setreminder($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        $todaysdate = date("Y-m-d H:i:s");
        if ($validation['status'] == "successful") {
// successful
            $sql = "INSERT INTO "
                . "`sales_reminder` "
                . "(`reminder_datetime`, `content`, `pipelineid`, `timestamp`, `isdeleted`, `teamid_creator`) "
                . "VALUES ('$getObjectData->reminder_datetime', '$getObjectData->content', $getObjectData->pipelineid, '$todaysdate',0,$getObjectData->teamid); ";
            $this->db->query($sql, __FILE__, __LINE__);
            return "Success";
        }
    }

    public function setcustomer($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        $todaysdate = date("Y-m-d H:i:s");
        if ($validation['status'] == "successful") {
// successful
            $sql = "INSERT INTO "
                . "`sales_pipeline` "
                . "(`pipeline_date`, `company_name`, `sourceid`, `productid`, `industryid`, `modeid`, `teamid`, `location`, `remarks`, `stageid`, `timestamp`, `isdeleted`,`create_platform`) "
                . "VALUES ('$getObjectData->pipeline_date', '$getObjectData->company_name', $getObjectData->sourceid,$getObjectData->productid, $getObjectData->industryid,$getObjectData->modeid,$getObjectData->teamid,'$getObjectData->location','$getObjectData->remarks',1, '$todaysdate',0,'$getObjectData->create_platform'); ";
            $this->db->query($sql, __FILE__, __LINE__);
            $id = $this->db->last_insert_id();
            $sql = "INSERT INTO "
                . "`sales_pipeline_history` "
                . "(`pipeline_date`, `company_name`, `sourceid`, `productid`, `industryid`, `modeid`, `teamid`, `location`, `remarks`, `stageid`, `timestamp`, `isdeleted`,`create_platform`) "
                . "VALUES ('$getObjectData->pipeline_date', '$getObjectData->company_name', $getObjectData->sourceid,$getObjectData->productid, $getObjectData->industryid,$getObjectData->modeid,$getObjectData->teamid,'$getObjectData->location','$getObjectData->remarks',1, '$todaysdate',0,'$getObjectData->create_platform'); ";
            $this->db->query($sql, __FILE__, __LINE__);
            $sql = "INSERT INTO "
                . "`sales_contact` "
                . "(`pipelineid`, `designation`, `name`, `phone`, `email`, `timestamp`, `isdeleted`) "
                . "VALUES ($id,'$getObjectData->designation', '$getObjectData->name', '$getObjectData->phone','$getObjectData->email', '$todaysdate',0); ";
            $this->db->query($sql, __FILE__, __LINE__);
            function getsourcename($source) {
                $sourcename = "";
                $db = new DatabaseManager();
                $SQL = sprintf("select source_name from " . DB_PARENT . ".sales_source where sourceid=" . $source);
                $db->executeQuery($SQL);
                while ($row = $db->get_nextRow()) {
                    $sourcename = $row["source_name"];
                }
                return $sourcename;
            }
            function getproductname($product) {
                $db = new DatabaseManager();
                $SQL = sprintf("select product_name from " . DB_PARENT . ".sales_product where productid=" . $product);
                $db->executeQuery($SQL);
                while ($row = $db->get_nextRow()) {
                    $product_name = $row["product_name"];
                }
                return $product_name;
            }
            function getindustry($industry) {
                $db = new DatabaseManager();
                $SQL = sprintf("select industry_type from " . DB_PARENT . ".sales_industry_type where industryid=" . $industry);
                $db->executeQuery($SQL);
                while ($row = $db->get_nextRow()) {
                    $industry_type = $row["industry_type"];
                }
                return $industry_type;
            }
            function getmode($mode) {
                $db = new DatabaseManager();
                $SQL = sprintf("select mode from " . DB_PARENT . ".sales_mode where modeid=" . $mode);
                $db->executeQuery($SQL);
                while ($row = $db->get_nextRow()) {
                    $mode = $row["mode"];
                }
                return $mode;
            }
            function getteam($teamid) {
                $db = new DatabaseManager();
                $SQL = sprintf("select name from " . DB_PARENT . ".team where teamid=" . $teamid);
                $db->executeQuery($SQL);
                while ($row = $db->get_nextRow()) {
                    $name = $row["name"];
                }
                return $name;
            }
            function getstage($stageid) {
                $db = new DatabaseManager();
                $SQL = sprintf("select stage_name from " . DB_PARENT . ".sales_stage where stageid=" . $stageid);
                $db->executeQuery($SQL);
                while ($row = $db->get_nextRow()) {
                    $stage_name = $row["stage_name"];
                }
                return $stage_name;
            }
            function send_mail($data) {
                $pipelineid = $data['pipelineid'];
                $to = array('sanketsheth@elixiatech.com');
                $subject = "New sales order -(Orderid:" . $pipelineid . ") ";
                $strCCMailIds = "";
                $strBCCMailIds = "sanketsheth1@gmail.com";
                $attachmentFilePath = "";
                $attachmentFileName = "";
                $message = "";
                $message = "<table border='1'>";
                $message .= "<tr><td>Pipeline ID</td><td>" . $data['pipelineid'] . "</td></tr>";
                $message .= "<tr><td>Pipeline Date</td><td>" . $data['pipelinedate'] . "</td></tr>";
                $message .= "<tr><td>Company Name</td><td>" . $data['companyname'] . "</td></tr>";
                $message .= "<tr><td>Source </td><td>" . $data['source'] . "</td></tr>";
                $message .= "<tr><td>Product</td><td>" . $data['product'] . "</td></tr>";
                $message .= "<tr><td>industry</td><td>" . $data['industry'] . "</td></tr>";
                $message .= "<tr><td>Mode</td><td>" . $data['mode'] . "</td></tr>";
                $message .= "<tr><td>Team</td><td>" . $data['team'] . "</td></tr>";
                $message .= "<tr><td>Location</td><td>" . $data['location'] . "</td></tr>";
                $message .= "<tr><td>Remarks</td><td>" . $data['remarks'] . "</td></tr>";
                $message .= "<tr><td>Stage </td><td>" . $data['stage'] . "</td></tr>";
                $message .= "<tr><td>Designation </td><td>" . $data['designation'] . "</td></tr>";
                $message .= "<tr><td>Contact Person </td><td>" . $data['contactperson'] . "</td></tr>";
                $message .= "<tr><td>Contact No </td><td>" . $data['contactno'] . "</td></tr>";
                $message .= "<tr><td>Contact Email id </td><td>" . $data['emailid'] . "</td></tr>";
                $message .= "</table>";
                sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);
            }
            $maildata = array(
                'pipelineid' => $id,
                'pipelinedate' => $getObjectData->pipeline_date,
                'companyname' => $getObjectData->company_name,
                'source' => getsourcename($getObjectData->sourceid),
                'product' => getproductname($getObjectData->productid),
                'industry' => getindustry($getObjectData->industryid),
                'mode' => getmode($getObjectData->modeid),
                'team' => getteam($getObjectData->teamid),
                'location' => $getObjectData->location,
                'remarks' => $getObjectData->remarks,
                'stage' => getstage(1),
                'date' => date("d-m-Y", strtotime($todaysdate)),
                'designation' => $getObjectData->designation,
                'contactperson' => $getObjectData->name,
                'contactno' => $getObjectData->phone,
                'emailid' => $getObjectData->email
            );
            send_mail($maildata);
            return "Success";
        }
    }

    public function setuser($getObjectData) {
        $validation = $this->check_userkey($getObjectData->userkey);
        $arr_p = array();
        $json_p = array();
        $todaysdate = date("Y-m-d H:i:s");
        if ($validation['status'] == "successful") {
// successful
            $sql = "INSERT INTO "
                . "`sales_contact` "
                . "(`pipelineid`, `designation`, `name`, `phone`, `email`, `timestamp`, `isdeleted`, `teamid_creator`) "
                . "VALUES ($getObjectData->pipelineid,'$getObjectData->designation', '$getObjectData->name', '$getObjectData->phone','$getObjectData->email', '$todaysdate',0,$getObjectData->teamid); ";
            $this->db->query($sql, __FILE__, __LINE__);
            return "Success";
        }
    }

    public function fetchTeamList() {
        $arr_p = array();
        $json_p = array();
        $sql = "SELECT name,teamid from team WHERE company_roleId NOT IN(14) AND is_deleted = 0 ORDER BY name";
        $record = array();
        $record = $this->db->query($sql, __FILE__, __LINE__);
        if ($this->db->num_rows($record) > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $arr_p['teamid'] = $row["teamid"];
                $arr_p['name'] = $row["name"];
                $arr_p['status'] = $this->getBusyStatus($arr_p['teamid']);
                $arr_p['time'] = $this->getBusyStatusForTime($arr_p['teamid']);
                $json_p[] = $arr_p;
            }
            return $json_p;
        } else {
            return null;
        }
    }

    public function insertTeamAttendance($obj) {
        $todaydatetime = date("Y-m-d H:i:s");
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->teamId . "'" .
        ",'" . $obj->check_value . "'" .
        ",'" . $obj->location . "'" .
            ",'" . $todaydatetime . "'" .
            "," . "@isExecutedOut";
        $queryCallSP = "CALL " . speedConstants::SP_INSERT_TEAM_ATTENDANCE . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @isExecutedOut as isExecutedOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        $result = $outputResult['isExecutedOut'];
        return $result;
    }

    public function getOfficeLocation($obj) {
        $db = new DatabaseManager();
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $obj->center . "'" .
            "," . "@locationOut";
        $queryCallSP = "CALL " . speedConstants::SP_GET_OFFICE_LOCATION . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $outputParamsQuery = "SELECT @locationOut as locationOut";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        $result = $outputResult['locationOut'];
        return $result;
    }

    public function getBusyStatus($teamId) {
        $db = new DatabaseManager();
        $today = date("Y-m-d");
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $teamId . "'" .
            ",'" . $today . "'" .
            "," . "@isExistsOut,@latestTimeOut";
        $queryCallSP = "CALL " . speedConstants::SP_GET_TEAM_BUSY_STATUS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        if ($arrResult['isExistOut'] == 1) {
            $arrResult['isExistOut'] = "2";
        }
        if ($arrResult['isExistOut'] == 0) {
            $arrResult['isExistOut'] = "3";
        }
        return $arrResult['isExistOut'];
    }

    public function getBusyStatusForTime($teamId) {
        $db = new DatabaseManager();
        $today = date("Y-m-d");
        $pdo = $db->CreatePDOConnForTech();
        $sp_params = "'" . $teamId . "'" .
            ",'" . $today . "'" .
            "," . "@isExistsOut,@latestTimeOut";
        $queryCallSP = "CALL " . speedConstants::SP_GET_TEAM_BUSY_STATUS . "(" . $sp_params . ")";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $db->ClosePDOConn($pdo);
        $arrResult['latestTimeOut'] = date("H:i", strtotime($arrResult['latestTimeOut']));
        // if($arrResult['latestTimeOut']=='00:00'){
        //     $arrResult['latestTimeOut']='N/A';
        // }
        return $arrResult['latestTimeOut'];
    }
}
?>
