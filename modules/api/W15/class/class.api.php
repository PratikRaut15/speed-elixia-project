<?php

require_once "global.config.php";
require_once "database.inc.php";
require_once "reports.api.php";
date_default_timezone_set('Asia/Kolkata');

if (!class_exists('VODevices')) {

    class VODevices {

    }

}


define("SP_GET_VEHICLEWAREHOUSE_DETAILS", "get_vehiclewarehouse_details");
define("SP_AUTHENTICATE_FOR_LOGIN", "authenticate_for_login");
define("SP_SPEED_FORGOT_PASSWORD", "speed_forgot_password");
define("SP_UPDATE_NEWFORGOTPASSWORD", "update_newforgotpassword");
define("SP_INSERT_SMSLOG", "insert_smslog");

class TempConversion {

    public /* int */ $rawtemp;
    public /* boolean */ $unit_type = 0;
    public /* boolean */ $use_humidity = 0;
    public /* boolean */ $switch_to = 0;

}

class api {

    const PER_SMS_CHARACTERS = 160;

    static $SMS_TEMPLATE_FOR_VEHICLE_USER_DRIVER_MAPPING = "Dear {{USERNAME}}, {{VEHICLENO}} has been allotted for your pickup. Driver Name: {{DRIVERNAME}} ({{DRIVERPHONE}})";
    var $status;
    var $status_time;

    //<editor-fold defaultstate="collapsed" desc="Constructor">
    // construct
    function __construct() {
        $this->db = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, SPEEDDB);
    }

    // </editor-fold>
    //
    //<editor-fold defaultstate="collapsed" desc="API functions">
    // checks for login
    function check_login($username, $password) {
        $retarray['status'] = "failure";
        $retarray['version'] = '';
        $retarray['customername'] = null;
        $retarray['userkey'] = 0;
        $userkeyparam = 0;
        $pdo = $this->db->CreatePDOConn();
        $todaysdate = date("Y-m-d H:i:s");
        $sp_params = "'" . $username . "'"
                . ",'" . $password . "'"
                . ",'" . $todaysdate . "'"
                . "," . '@usertype'
                . "," . '@userkeyparam'
                . "," . '@userauthtype';
        $queryCallSP = "CALL " . speedConstants::SP_AUTHENTICATE_FOR_LOGIN . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $this->db->ClosePDOConn($pdo);
        $outputParamsQuery = "SELECT @usertype AS usertype, @userkeyparam AS userkeyparam , @userauthtype AS userauthtype";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $usertype = $outputResult['usertype'];
        $userkeyparam = $outputResult['userkeyparam'];
        $userauthtype = $outputResult['userauthtype'];
        if ($userkeyparam != 0) {
            if ($usertype == 0 && $userkeyparam != 0) {
                $devices = $this->checkforvalidity($arrResult['customerno']);
                $initday = 0;
                if (isset($devices)) {
                    foreach ($devices as $thisdevice) {
                        $days = $this->check_validity_login($thisdevice->expirydate, $thisdevice->today);
                        if ($days > 0) {
                            $initday = $days;
                        }
                    }
                }
                $otpSent = "No";
                if ($initday > 0) {
                    $retarray['status'] = "successful";
                    $retarray['userkey'] = $arrResult['userkey'];
                    $retarray['customerno'] = $arrResult['customerno'];
                    $retarray['username'] = $arrResult['username'];
                    $retarray['realname'] = $arrResult['realname'];
                    $retarray['customername'] = $arrResult['customercompany'];
                    $retarray['version'] = $arrResult['version'];
                    $retarray['role'] = $arrResult['role'];
                    $retarray['notification_status'] = $arrResult['notification_status'];
                    $retarray['userauthtype'] = $userauthtype;
                    $today = date("Y-m-d H:i:s");
                    $this->update_push_android_chk($arrResult['userkey'], 1);
                    $this->update_push_android_chk_main($arrResult['userkey'], 1);
                    $sql = "UPDATE user SET lastlogin_android='" . $today . "' where userkey = '" . $arrResult['userkey'] . "' AND customerno= '" . $arrResult['customerno'] . "' LIMIT 1";
                    $this->db->query($sql, __FILE__, __LINE__);
                    if ($userauthtype == 1) {
                        $status = $this->multiauthRequest($arrResult['userid'], $arrResult['customerno']);
                        if ($status == 'smsSent') {
                            $otpSent = "Yes";
                        } elseif ($status == 'phoneNotAvailable') {
                            $otpSent = "phoneNotAvailable";
                        } elseif ($status == 'limitExceeded') {
                            $otpSent = "limitExceeded";
                        } elseif ($status == 'userLocked') {
                            $otpSent = "userLocked";
                        } elseif ($status == 'noSmsBalance') {
                            $otpSent = "noSmsBalance";
                        }
                    }
                    $retarray['otpSent'] = $otpSent;
                } else {
                    $retarray['status'] = "expired";
                    $retarray['version'] = '';
                    $retarray['customername'] = null;
                    $retarray['userkey'] = 0;
                    $retarray['userauthtype'] = 0;
                    $retarray['otpSent'] = $otpSent;
                }
            } elseif ($usertype == 1 && $userkeyparam != 0) {
                $retarray['status'] = "forgot_password_success";
                $retarray['version'] = '';
                $retarray['customername'] = null;
                $retarray['userkey'] = $userkeyparam;
                $retarray['userauthtype'] = $userauthtype;
                $retarray['otpSent'] = $otpSent;
            }
        }
        echo json_encode($retarray);
        return $retarray;
    }

    function pullcrm($userkey) {
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        $json_p = array();
        if ($validation['status'] == "successful") {
            // successful
            $customerno = $validation["customerno"];
            $sql = "SELECT *  FROM customer LEFT OUTER JOIN relationship_manager ON relationship_manager.rid = customer.rel_manager
                                                WHERE customer.customerno =$customerno";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            while ($row = $this->db->fetch_array($record)) {
                $arr_p['status'] = "successful";
                $json_p['name'] = $row["manager_name"];
                $json_p['mobile'] = $row["manager_mobile"];
                $json_p['email'] = $row["manager_email"];
                $arr_p['result'] = $json_p;
            }
        } else {
            $arr_p['status'] = "unsuccessful";
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function device_list_wh($userkey, $pageIndex, $pageSize, $searchstring, $groupidparam, $isRequiredThirdParty) {
        $isWareHouse = 1;
        $totalWareHouseCount = 0;
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        $temp_coversion = new TempConversion();
        if ($validation['status'] == "successful") {
            $devices = $this->checkforvalidity($validation["customerno"]);
            $initday = 0;
            if (isset($devices)) {
                foreach ($devices as $thisdevice) {
                    $days = $this->check_validity_login($thisdevice->expirydate, $thisdevice->today);
                    if ($days > 0) {
                        $initday = $days;
                    }
                }
            }
            if ($initday > 0) {
                // successful
                $customerno = $validation["customerno"];
                $arr_p['groups'] = $this->pull_groups($userkey);
                //Custom field
                $arr_p['custom'] = $this->custom_fields($customerno);
                //Prepare parameters
                $sp_params = "'" . $pageIndex . "'"
                        . ",'" . $pageSize . "'"
                        . "," . $customerno . ""
                        . "," . $isWareHouse . ""
                        . ",'" . $searchstring . "'"
                        . ",'" . $groupidparam . "'"
                        . ",'" . $userkey . "'"
                        . ",'" . $isRequiredThirdParty . "'"
                ;
                $queryCallSP = "CALL " . SP_GET_VEHICLEWAREHOUSE_DETAILS . "($sp_params)";
                $records = $this->db->query($queryCallSP, __FILE__, __LINE__);
                $json_p = array();
                $x = 0;
                while ($row = $this->db->fetch_array($records)) {
                    //prettyPrint($row);die();
                    if ($totalWareHouseCount == 0) {
                        $totalWareHouseCount = $row['recordCount'];
                    }
                    //if ($firstgroup == '' || (in_array($row['veh_grpid'], $groupids))) {
                    $json_p[$x]['vehicleid'] = $row['vehicleid'];
                    $json_p[$x]['vehicleno'] = $row['vehicleno'];
                    $json_p[$x]['groupid'] = $row['groupid'];
                    $json_p[$x]['lastupdated'] = $this->getduration1($row['lastupdated']);
                    $json_p[$x]['simcardno'] = $row['simcardno'];
                    $json_p[$x]['sequenceno'] = $row['sequenceno'];
                    $temp_coversion->unit_type = $row['get_conversion'];
                    $temp_coversion->use_humidity = $row['use_humidity'];
                    $temp_coversion->switch_to = 3;
                    //status start
                    $status = "3"; // default green
                    $ServerIST_less1 = new DateTime();
                    $ServerIST_less1->modify('-60 minutes');
                    $lastupdated = new DateTime($row['lastupdated']);
                    if ($lastupdated < $ServerIST_less1) {
                        $status = "1"; //inactive grey
                    }
                    if (isset($row['temp_sensors'])) {
                        $temp1 = $temp2 = $temp3 = $temp4 = speedConstants::TEMP_NOTACTIVE;
                        $temp1_min = '';
                        $temp1_max = '';
                        $temp2_min = '';
                        $temp2_max = '';
                        $temp3_min = '';
                        $temp3_max = '';
                        $temp4_min = '';
                        $temp4_max = '';
                        $t1 = $this->getName($row['n1'], $customerno);
                        $t2 = $this->getName($row['n2'], $customerno);
                        $t3 = $this->getName($row['n3'], $customerno);
                        $t4 = $this->getName($row['n4'], $customerno);
                        switch ($row['temp_sensors']) {
                            case 4:
                                if (isset($row['tempsen4']) && $row['tempsen4'] != 0) {
                                    $temp4 = 0;
                                    $s = "analog" . $row['tempsen4'];
                                    $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                    if ($analogValue != 0 && $analogValue != 1150) {
                                        $temp_coversion->rawtemp = $analogValue;
                                        $temp4 = getTempUtil($temp_coversion);
                                        $temp4_min = (isset($row['temp4_min'])) ? $row['temp4_min'] : '';
                                        $temp4_max = (isset($row['temp4_max'])) ? $row['temp4_max'] : '';
                                        if ($temp4 < $temp4_min || $temp4 > $temp4_max && $temp4_min != $temp4_max && $status != '1') {
                                            $status = "2"; // temperature 4 conflict
                                        }
                                        $temp4 = $temp4 . " &degC";
                                    }
                                }
                                $json_p[$x]['n4'] = isset($t4) ? $t4 : 'Temperature4 ';
                                $json_p[$x]['temp4'] = isset($temp4) ? $temp4 : "";
                            case 3:
                                if (isset($row['tempsen3']) && $row['tempsen3'] != 0) {
                                    $temp3 = 0;
                                    $s = "analog" . $row['tempsen3'];
                                    $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                    if ($analogValue != 0 && $analogValue != 1150) {
                                        $temp_coversion->rawtemp = $analogValue;
                                        $temp3 = getTempUtil($temp_coversion);
                                        $temp3_min = (isset($row['temp3_min'])) ? $row['temp3_min'] : '';
                                        $temp3_max = (isset($row['temp3_max'])) ? $row['temp3_max'] : '';
                                        if ($temp3 < $temp3_min || $temp3 > $temp3_max && $temp3_min != $temp3_max && $status != '1') {
                                            $status = "2"; // temperature 3 conflict
                                        }
                                        $temp3 = $temp3 . " &degC";
                                    }
                                }
                                $json_p[$x]['n3'] = isset($t3) ? $t3 : 'Temperature3 ';
                                $json_p[$x]['temp3'] = isset($temp3) ? $temp3 : "";
                            case 2:
                                if (isset($row['tempsen2']) && $row['tempsen2'] != 0) {
                                    $temp2 = 0;
                                    $s = "analog" . $row['tempsen2'];
                                    $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                    if ($analogValue != 0 && $analogValue != 1150) {
                                        $temp_coversion->rawtemp = $analogValue;
                                        $temp2 = getTempUtil($temp_coversion);
                                        $temp2_min = (isset($row['temp2_min'])) ? $row['temp2_min'] : '';
                                        $temp2_max = (isset($row['temp2_max'])) ? $row['temp2_max'] : '';
                                        if ($temp2 < $temp2_min || $temp2 > $temp2_max && $temp2_min != $temp2_max && $status != '1') {
                                            $status = "2"; // temperature 2 conflict
                                        }
                                        $temp2 = $temp2 . " &degC";
                                    }
                                }
                                $json_p[$x]['n2'] = isset($t2) ? $t2 : 'Temperature2 ';
                                $json_p[$x]['temp2'] = isset($temp2) ? $temp2 : "";
                            case 1:
                                if (isset($row['tempsen1']) && $row['tempsen1'] != 0) {
                                    $temp1 = 0;
                                    $s = "analog" . $row['tempsen1'];
                                    $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                    if ($analogValue != 0 && $analogValue != 1150) {
                                        $temp_coversion->rawtemp = $analogValue;
                                        $temp1 = getTempUtil($temp_coversion);
                                        $temp1_min = (isset($row['temp1_min'])) ? $row['temp1_min'] : '';
                                        $temp1_max = (isset($row['temp1_max'])) ? $row['temp1_max'] : '';
                                        if ($temp1 < $temp1_min || $temp1 > $temp1_max && $temp1_min != $temp1_max && $status != '1') {
                                            $status = "2"; // temperature 1 conflict
                                        }
                                        $temp1 = $temp1 . " &degC";
                                    }
                                }
                                $json_p[$x]['n1'] = isset($t1) ? $t1 : 'Temperature1 ';
                                $json_p[$x]['temp1'] = isset($temp1) ? $temp1 : "";
                                break;
                        }
                    }
                    if ($row['use_humidity'] == 1 && $row['humidity'] != 0) {
                        $temp2 = 0;
                        $s = "analog" . $row['humidity'];
                        $analogValue = isset($row[$s]) ? $row[$s] : 0;
                        if ($analogValue != 0) {
                            $temp_coversion->rawtemp = $analogValue;
                            $temp2 = getTempUtil($temp_coversion) . " %";
                        }
                        $json_p[$x]['n2'] = "Humidity";
                        $json_p[$x]['temp2'] = isset($temp2) ? $temp2 : "";
                    }
                    $json_p[$x]['vehicle_color'] = $status;
                    $json_p[$x]['devlat'] = $row['devicelat'];
                    $json_p[$x]['devlong'] = $row['devicelong'];
                    $x++;
                    //}
                }
                // Free result set
                $records->close();
                $this->db->next_result();
                $arr_p['status'] = "successful";
                $arr_p['result'] = $json_p;
                $arr_p['totalWareHouseCount'] = $totalWareHouseCount;
                $this->update_push_android_chk($userkey, 0);
            } else {
                $arr_p['status'] = "expired";
            }
        } else {
            $arr_p['status'] = "unsuccessful";
        }
        echo json_encode($arr_p);
        return;
    }

    function device_list_details_wh($userkey, $vehicleid) {
        $temp_coversion = new TempConversion();
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        if ($validation['status'] == "successful") {
            $devices = $this->checkforvalidity($validation["customerno"]);
            $initday = 0;
            if (isset($devices)) {
                foreach ($devices as $thisdevice) {
                    $days = $this->check_validity_login($thisdevice->expirydate, $thisdevice->today);
                    if ($days > 0) {
                        $initday = $days;
                    }
                }
            }
            if ($initday > 0) {
                // successful
                $customerno = $validation["customerno"];
                $arr_p['custom'] = $this->custom_fields($customerno);
                $sql = "SELECT unit.humidity, vehicle.temp1_min,unit.is_buzzer,unit.digitalioupdated
                    ,unit.extra_digitalioupdated,unit.is_freeze,unit.mobiliser_flag,unit.is_mobiliser,unit.get_conversion
                    ,customer.use_buzzer,customer.use_freeze,customer.use_immobiliser
                    , vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max, vehicle.kind
                    , vehicle.groupid, unit.tempsen1, unit.tempsen2, unit.tempsen3, unit.tempsen4
                    , unit.analog1, unit.analog2, unit.analog3, unit.analog4, customer.temp_sensors
                    , vehicle.stoppage_transit_time, vehicle.stoppage_flag, customer.use_geolocation, customer.use_humidity
                    , user.customerno as customer_no,vehicle.vehicleid, vehicle.overspeed_limit
                    , vehicle.extbatt, vehicle.vehicleno,vehicle.curspeed,unit.unitno, unit.digitalio
                    , unit.acsensor, unit.is_ac_opp, driver.drivername,driver.driverphone, devices.lastupdated
                    , devices.ignition, devices.powercut, devices.gsmstrength, devices.devicelat, devices.devicelong
                    , ignitionalert.status as igstatus,ignitionalert.ignchgtime
                    , COALESCE((SELECT name FROM nomens where nid = unit.n1), 'Temperature1') AS n1
                    , COALESCE((SELECT name FROM nomens where nid = unit.n2), 'Temperature2') AS n2
                    , COALESCE((SELECT name FROM nomens where nid = unit.n3), 'Temperature3') AS n3
                    , COALESCE((SELECT name FROM nomens where nid = unit.n4), 'Temperature4') AS n4
                FROM vehicle INNER JOIN devices ON devices.uid = vehicle.uid
                INNER JOIN driver ON driver.driverid = vehicle.driverid
                INNER JOIN unit ON devices.uid = unit.uid
                INNER JOIN customer ON customer.customerno = vehicle.customerno
                INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                INNER JOIN user ON vehicle.customerno = user.customerno
                WHERE vehicle.customerno =$customerno AND user.userkey =$userkey
                AND unit.trans_statusid NOT IN (10,22) AND vehicle.isdeleted=0
                AND driver.isdeleted=0 and devices.lastupdated <> '0000-00-00 00:00:00'
                AND vehicle.vehicleid = $vehicleid ORDER BY devices.lastupdated DESC";
                $record = $this->db->query($sql, __FILE__, __LINE__);
                $json_p = array();
                $x = 0;
                while ($row = $this->db->fetch_array($record)) {
                    $json_p[$x]['lastupdated'] = $this->getduration1($row['lastupdated']);
                    $json_p[$x]['vehicleid'] = $row['vehicleid'];
                    $json_p[$x]['unitno'] = $row['unitno'];
                    $json_p[$x]['powercut'] = $row['powercut'];
                    $json_p[$x]['simsignal'] = round($row['gsmstrength'] / 31 * 100);
                    $json_p[$x]['analog_sensors'] = $row['temp_sensors'];
                    $temp1 = $temp2 = $temp3 = $temp4 = speedConstants::TEMP_NOTACTIVE;
                    $temp_coversion->unit_type = $row['get_conversion'];
                    $temp_coversion->use_humidity = $row['use_humidity'];
                    $temp_coversion->switch_to = 3;
                    switch ($row['temp_sensors']) {
                        case 4:
                            if (isset($row['tempsen4']) && $row['tempsen4'] != '0') {
                                $temp4 = 0;
                                $s = "analog" . $row['tempsen4'];
                                $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                if ($analogValue && $analogValue != 1150) {
                                    $temp_coversion->rawtemp = $analogValue;
                                    $temp4 = getTempUtil($temp_coversion) . " &degC";
                                }
                            }
                            $json_p[$x]['cust_analog4'] = $row['n4'];
                            $json_p[$x]['analog4'] = $temp4;
                        case 3:
                            if (isset($row['tempsen3']) && $row['tempsen3'] != '0') {
                                $temp3 = 0;
                                $s = "analog" . $row['tempsen3'];
                                $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                if ($analogValue && $analogValue != 1150) {
                                    $temp_coversion->rawtemp = $analogValue;
                                    $temp3 = getTempUtil($temp_coversion) . " &degC";
                                }
                            }
                            $json_p[$x]['cust_analog3'] = $row['n3'];
                            $json_p[$x]['analog3'] = $temp3;
                        case 2:
                            if (isset($row['tempsen2']) && $row['tempsen2'] != '0') {
                                $temp2 = 0;
                                $s = "analog" . $row['tempsen2'];
                                $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                if ($analogValue && $analogValue != 1150) {
                                    $temp_coversion->rawtemp = $analogValue;
                                    $temp2 = getTempUtil($temp_coversion) . " &degC";
                                }
                            }
                            $json_p[$x]['cust_analog2'] = $row['n2'];
                            $json_p[$x]['analog2'] = $temp2;
                        case 1:
                            if (isset($row['tempsen1']) && $row['tempsen1'] != '0') {
                                $temp1 = 0;
                                $s = "analog" . $row['tempsen1'];
                                $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                if ($analogValue && $analogValue != 1150) {
                                    $temp_coversion->rawtemp = $analogValue;
                                    $temp1 = getTempUtil($temp_coversion) . " &degC";
                                }
                            }
                            $json_p[$x]['cust_analog1'] = $row['n1'];
                            $json_p[$x]['analog1'] = $temp1;
                            break;
                    }
                    if ($row['use_humidity'] == 1 && $row['humidity'] != 0) {
                        $temp2 = 0;
                        $s = "analog" . $row['humidity'];
                        $analogValue = isset($row[$s]) ? $row[$s] : 0;
                        if ($analogValue && $analogValue != 1150) {
                            $temp_coversion->rawtemp = $analogValue;
                            $temp2 = getTempUtil($temp_coversion) . " %";
                        }
                        $json_p[$x]['cust_analog2'] = "Humidity";
                        $json_p[$x]['analog2'] = $temp2;
                    }
                    ///use buzzer - start
                    $use_buzzer = $row['use_buzzer'];
                    if ($use_buzzer == 1 && $row['is_buzzer'] == 1) {
                        $buzzer_status = 1; //unit has buzzer
                    } elseif ($use_buzzer == 1 && $row['is_buzzer'] == 0) {
                        $buzzer_status = 0; //unit has NO buzzer
                    } else {
                        $buzzer_status = -1; //no buzzer
                    }
                    $json_p[$x]['buzzerstatus'] = $buzzer_status;
                    $x++;
                }
                $arr_p['status'] = "successful";
                $arr_p['result'] = $json_p;
                $this->update_push_android_chk($userkey, 0);
            } else {
                $arr_p['status'] = "expired";
            }
        } else {
            $arr_p['status'] = "unsuccessful";
        }
        echo json_encode($arr_p);
        return;
    }

    function pushbuzzer($userkey, $vehicleid, $status) {
        //buzzer status =1 on
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        if ($validation['status'] == "successful") {
            $arr_p['status'] = 'successful';
            $customerno = $validation["customerno"];
            $userid = $validation["userid"];
            if ($status == 1) {
                //Do You Like To Alarm The Vehicle ?
                //send alert
                $query = "select u.unitno from vehicle as v inner join unit as u on v.uid = u.uid  where v.vehicleid =" . $vehicleid . " AND v.isdeleted=0";
                $record = $this->db->query($query, __FILE__, __LINE__);
                while ($row = $this->db->fetch_array($record)) {
                    $unitno = $row['unitno'];
                }
                if (!empty($unitno)) {
                    $Que = "UPDATE unit SET  setcom = 1, command='buzz' WHERE unitno='" . $unitno . "' AND customerno=" . $customerno;
                    // $record1 = $this->db->query($Que, __FILE__, __LINE__);
                    //insert into buzzerlog
                    $datavehdata = $this->getvehicledetail($vehicleid, $customerno);
                    if (isset($datavehdata)) {
                        $vehicleid = $datavehdata['vehicleid'];
                        $uid = $datavehdata['uid'];
                        $devicelat = $datavehdata['devicelat'];
                        $devicelong = $datavehdata['devicelong'];
                        $today = date("Y-m-d H:i:s");
                        $sql = "INSERT INTO buzzerlog (uid, vehicleid, devicelat,devicelong,customerno,createdby ,createdon,is_api) "
                                . "VALUES ('" . $uid . "','" . $vehicleid . "','" . $devicelat . "','" . $devicelong . "','" . $customerno . "','" . $userid . "','" . $today . "',1)";
                        $this->db->query($sql, __FILE__, __LINE__);
                    }
                    $arr_p['message'] = 'Buzzer alert active';
                }
            }
        }
        echo json_encode($arr_p);
        return;
    }

    function pushmobiliser($userkey, $vehicleid, $status) {
        //status  0=>mobiliser off // 1=>on  // 2 => stop // -1 ->not mobiliser
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        if ($validation['status'] == "successful") {
            $arr_p['status'] = 'successful';
            $customerno = $validation["customerno"];
            $userid = $validation["userid"];
            $query = "select u.unitno from vehicle as v inner join unit as u on v.uid = u.uid  where v.vehicleid =" . $vehicleid . " AND  v.isdeleted=0";
            $record = $this->db->query($query, __FILE__, __LINE__);
            while ($row = $this->db->fetch_array($record)) {
                $unitno = $row['unitno'];
            }
            if (!empty($unitno)) {
                $check = "";
                if ($status == '0') {
                    $arr_p['message'] = 'Start Vehicle'; //start
                    $command = 'STARTV';
                    $check = 1;
                } elseif ($status == '1') {
                    $command = 'STOPV';
                    $arr_p['message'] = 'Stop Vehicle'; // stop vehicle
                    $check = 1;
                }
                if (!empty($check)) {
                    if ($command == 'STARTV') {
                        $flag = 0;
                    } else {
                        $flag = 1;
                    }
                    $Que = "UPDATE unit SET  setcom = 1, command='" . $command . "', mobiliser_flag=" . $flag . "  WHERE unitno='" . $unitno . "' AND customerno=" . $customerno;
                    //$record1 = $this->db->query($Que, __FILE__, __LINE__);
                    ///Insert in mobiliserlog table
                    $datavehdata = $this->getvehicledetail($vehicleid, $customerno);
                    if (isset($datavehdata)) {
                        $vehicleid = $datavehdata['vehicleid'];
                        $uid = $datavehdata['uid'];
                        $devicelat = $datavehdata['devicelat'];
                        $devicelong = $datavehdata['devicelong'];
                        $today = date("Y-m-d H:i:s");
                        $sql = "INSERT INTO immobiliserlog (uid, vehicleid, devicelat,devicelong,commandname,mobiliser_flag,customerno,createdby ,createdon,is_api) "
                                . "VALUES ('" . $uid . "','" . $vehicleid . "','" . $devicelat . "','" . $devicelong . "','" . $command . "','" . $flag . "','" . $customerno . "','" . $userid . "','" . $today . "',1)";
                        $this->db->query($sql, __FILE__, __LINE__);
                    }
                }
            }
        }
        echo json_encode($arr_p);
        return;
    }

    function freezevehicle($userkey, $vehicleid, $fstatus) {
        //by vehicleid get last updated device lat long and insert in new table freeze or status update in unittable is_freeze
        $arr_p['status'] = "unsuccessful";
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        if ($validation['status'] == "successful") {
            $customerno = $validation["customerno"];
            $userid = $validation["userid"];
            $datavehdata = $this->getvehicledetail($vehicleid, $customerno);
            if (isset($datavehdata)) {
                $vehicleid_freeze = $datavehdata['vehicleid'];
                $uid_freeze = $datavehdata['uid'];
                $devicelat_freeze = $datavehdata['devicelat'];
                $devicelong_freeze = $datavehdata['devicelong'];
                $arr_p['status'] = "successful";
                $today = date("Y-m-d H:i:s");
                if ($fstatus == '0') {
                    //freeze vehicle
                    $Que = "UPDATE unit set is_freeze=1 where uid = " . $uid_freeze;
                    $record1 = $this->db->query($Que, __FILE__, __LINE__);
                    $sql = "INSERT INTO freezelog (uid, vehicleid, devicelat,devicelong,customerno,createdby ,createdon,updatedby,updatedon,is_api) "
                            . "VALUES ('" . $uid_freeze . "','" . $vehicleid_freeze . "','" . $devicelat_freeze . "','" . $devicelong_freeze . "','" . $customerno . "','" . $userid . "','" . $today . "','" . $userid . "','" . $today . "',1)";
                    $this->db->query($sql, __FILE__, __LINE__);
                    $arr_p['message'] = "Freezed Vehicle ";
                } elseif ($fstatus == '1') {
                    $Que = "UPDATE unit set is_freeze=0 where uid = " . $uid_freeze;
                    $record1 = $this->db->query($Que, __FILE__, __LINE__);
                    $sql = "update freezelog set isdeleted=1,updatedon='" . $today . "', updatedby='" . $userid . "' where uid=" . $uid_freeze . " AND isdeleted=0";
                    $this->db->query($sql, __FILE__, __LINE__);
                    $arr_p['message'] = "Unfreezed Vehicle ";
                } else {
                    $arr_p['message'] = "No Freeze ";
                }
            } else {
                $arr_p['message'] = "vehicleid Missing";
            }
        }
        echo json_encode($arr_p);
        return;
    }

    function register_gcm($userkey, $regid) {
        $sql = "select userid from user where userkey='" . $userkey . "'";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $row = $this->db->fetch_array($record);
        $response = array();
        $response['status'] = 'failure';
        if ($row['userid'] != "") {
            $sql = "UPDATE user SET gcmid = '" . $regid . "' WHERE userkey = '" . $userkey . "'";
            $this->db->query($sql, __FILE__, __LINE__);
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failure';
        }
        echo json_encode($response);
    }

    function unregister_gcm($userkey) {
        $sql = "select userid from user where userkey='" . $userkey . "'";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $row = $this->db->fetch_array($record);
        $response = array();
        $response['status'] = 'failure';
        if ($row['userid'] != "") {
            $sql = "UPDATE user SET gcmid = '' WHERE userkey = '" . $userkey . "'";
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failure';
        }
        echo json_encode($response);
    }

    function summary_wh($userkey, $vehicleid, $date) {
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        $arr_p['error'] = "User Not Valid";
        $validation = $this->check_userkey($userkey);
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            $userid = $validation['userid'];
            $vehicle = $this->get_vehicle($vehicleid, $customerno);
            if (isset($vehicle)) {
                $date = date('Y-m-d', strtotime($date));
                $interval = 60;
                $stime = date('Y-m-d 00:00:00', strtotime($date));
                $etime = date('Y-m-d 23:59:59', strtotime($date));
                $location = "../../../customer/" . $customerno . "/unitno/" . $vehicle->unitno . "/sqlite/" . $date . ".sqlite";
                if (file_exists($location)) {
                    $path = "sqlite:$location";
                    $Data = $this->DataForTemprature($path, $vehicle, $stime, $etime, $interval);
                    $row_count = count($Data);
                    if (isset($Data) && count($Data) > 0) {
                        $vehicle->customerno = $customerno;
                        $Datacap = new VODevices();
                        $Datacap->WarehouseName = $vehicle->vehicleno;
                        $Datacap->DeviceId = $vehicle->deviceid;
                        $Datacap->useHumidity = $vehicle->use_humidity;
                        $Datacap->StartTime = date(speedConstants::DEFAULT_DATETIME, strtotime($stime));
                        $Datacap->EndTime = date(speedConstants::DEFAULT_DATETIME, strtotime($etime));
                        $Datacap->Interval = $interval . " min";
                        $temp1_arr = array();
                        $temp2_arr = array();
                        $temp3_arr = array();
                        $temp4_arr = array();
                        $report = array();
                        foreach ($Data as $datarow) {
                            $reportarr['Time'] = date(speedConstants::DEFAULT_TIME, strtotime($datarow->starttime));
                            if ($vehicle->use_humidity == '1') {
                                $reportarr['Humidity'] = $datarow->humidity;
                            }
                            $reportarr['Temperature1'] = $datarow->temp1;
                            if ($datarow->temp1 != speedConstants::TEMP_NOTACTIVE) {
                                $temp1_arr[] = 1;
                            }
                            $reportarr['Temperature2'] = $datarow->temp2;
                            if ($datarow->temp2 != speedConstants::TEMP_NOTACTIVE) {
                                $temp2_arr[] = 1;
                            }
                            $reportarr['Temperature3'] = $datarow->temp3;
                            if ($datarow->temp3 != speedConstants::TEMP_NOTACTIVE) {
                                $temp3_arr[] = 1;
                            }
                            $reportarr['Temperature4'] = $datarow->temp4;
                            if ($datarow->temp4 != speedConstants::TEMP_NOTACTIVE) {
                                $temp4_arr[] = 1;
                            }
                            $report[] = $reportarr;
                        }
                        $temp1 = $temp2 = $temp3 = $temp4 = "0";
                        if (count($temp1_arr) > 0) {
                            $temp1 = 1;
                        }
                        if (count($temp2_arr) > 0) {
                            $temp2 = 1;
                        }
                        if (count($temp3_arr) > 0) {
                            $temp3 = 1;
                        }
                        if (count($temp4_arr) > 0) {
                            $temp4 = 1;
                        }
                        $Datacap->temp1 = $temp1;
                        $Datacap->temp2 = $temp2;
                        $Datacap->temp3 = $temp3;
                        $Datacap->temp4 = $temp4;
                        $Datacap->n1 = $vehicle->n1;
                        $Datacap->n2 = $vehicle->n2;
                        $Datacap->n3 = $vehicle->n3;
                        $Datacap->n4 = $vehicle->n4;
                        $Datacap->whsummary = $report;
                        $arr_p['status'] = "successful";
                        $arr_p['report'] = $Datacap;
                    } else {
                        $arr_p['error'] = "Data Not Available";
                    }
                } else {
                    $arr_p['error'] = "File Not exists";
                }
            } else {
                $arr_p['error'] = "Unable To Fetch Device Details. ";
            }
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function contractinfo($userkey) {
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        //$arr_p['status'] = "unsuccessful";
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            $today = date('Y-m-d');
            $device = new VODevices();
            $SQL2 = "SELECT sum(pending_amt) as pending_amount FROM " . DB_PARENT . ".invoice WHERE customerno = $customerno";
            $record2 = $this->db->query($SQL2, __FILE__, __LINE__);
            $row_count2 = $this->db->num_rows($record2);
            if ($row_count2 > 0) {
                $row = $this->db->fetch_array($record2);
                $device->pending_amount = $row["pending_amount"];
                if ($row["pending_amount"] == "" || $row["pending_amount"] == "0") {
                    $device->pending_amount = 0;
                }
            } else {
                $device->pending_amount = "Not Defined";
            }
            $info = new VODevices();
            $info->PendingAmount = $device->pending_amount;
            $devices = Array();
            $Query = "SELECT devices.deviceid, vehicle.vehicleno,devices.installdate,vehicle.vehicleid, devices.invoiceno,
            devices.devicekey,devices.expirydate,devices.uid,devices.po_no,devices.po_date, devices.warrantyexpiry, unit.unitno,
            registeredon,district.name as dname,city.name as cname,devices.device_invoiceno, Now() as today FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            LEFT JOIN `group` ON vehicle.groupid=`group`.groupid
            LEFT JOIN city ON `group`.cityid=city.cityid
            LEFT JOIN district ON city.districtid=district.districtid
            LEFT JOIN state ON district.stateid=state.stateid
            LEFT JOIN nation ON state.nationid=nation.nationid
            LEFT OUTER JOIN simcard ON devices.simcardid = simcard.id
            where devices.customerno=$customerno AND unit.trans_statusid NOT IN (10,22)";
            $record12 = $this->db->query($Query, __FILE__, __LINE__);
            $row_count12 = $this->db->num_rows($record12);
            $today = date("Y-m-d");
            $month = date("Y-m-d", strtotime($today . '+30 days'));
            if ($row_count12 > 0) {
                while ($row = $this->db->fetch_array($record12)) {
                    $device1 = new VODevices();
                    $device1->Vehicle = $row['vehicleno'];
                    $device1->Unit = $row['unitno'];
                    $device1->expirydate = date("d-m-Y", strtotime($row["expirydate"]));
                    if ($row["installdate"] == '0000-00-00') {
                        $device1->DateOfInstallation = 'Not Defined';
                    } else {
                        $device1->DateOfInstallation = date("d-m-Y", strtotime($row["installdate"]));
                    }
                    if ($row["warrantyexpiry"] == '0000-00-00') {
                        $device1->DateWarrantyExpiry = "Not Defined";
                        $device1->expirydaysleft = "Not Defined";
                    } else {
                        $warranty = date("d-m-Y", strtotime($row["warrantyexpiry"]));
                        $device1->DateWarrantyExpiry = $warranty;
                        $expirydate = date("Y-m-d", strtotime($row["expirydate"]));
                        $dStart = new DateTime($today);
                        $dEnd = new DateTime($expirydate);
                        $dDiff = $dStart->diff($dEnd);
                        // echo $dDiff->format('%R'); // use for point out relation: smaller/greater
                        if ($today > $expirydate) {
                            $device1->expirydaysleft = "Expired";
                        } else {
                            $device1->expirydaysleft = $dDiff->days . " Days Left";
                        }
                    }
                    $device1->PoNo = $row["po_no"];
                    if ($row["po_no"] == "") {
                        $device1->PoNo = "Not Defined";
                    }
                    $device1->PoDate = $row["po_date"];
                    if ($row["po_date"] == "0000-00-00") {
                        $device1->PoDate = "Not Defined";
                    }
                    $device1->device_invoiceno = $row["device_invoiceno"];
                    if ($row["device_invoiceno"] == "") {
                        $device1->device_invoiceno = "Not Defined";
                    }
                    $device1->invoiceno = $row["invoiceno"];
                    if ($row["invoiceno"] == "") {
                        $device1->invoiceno = "Not Defined";
                    }
                    $devices[] = $device1;
                }
            }
            $arr_p['status'] = "successful";
            $arr_p['info'] = $info;
            $arr_p['inventory'] = $devices;
        } else {
            $arr_p['status'] = "unsuccessful";
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function updateLogin($userkey, $phone, $version) {
        $today = date('Y-m-d H:i:s');
        $sql = "select * from " . TBL_ADMIN_USER . " where userkey='" . $userkey . "' AND isdeleted = 0";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $row = $this->db->fetch_array($record);
        if ($row['userkey'] != "") {
            $userid = $row['userid'];
            $customerno = $row['customerno'];
            $sqlInsert = "insert into login_history(userid, customerno,type,timestamp,phonetype,version)"
                    . "values($userid,$customerno,1,'" . $today . "','$phone','$version')";
            $this->db->query($sqlInsert, __FILE__, __LINE__);
        }
    }

    function get_otp_forgotpwd($username) {
        $customermanager = new CustomerManager();
        $smsStatus = new SmsStatus();
        $todaysdate = date('Y-m-d H:i:s');
        $otpparam = '';
        $arr_p = Array();
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Please enter registered username.";
        //Prepare parameters
        $sp_params = "'" . $username . "'"
                . ",'" . $todaysdate . "'"
                . "," . "@userexists" . "";
        $sqlCallSP = "CALL " . SP_SPEED_FORGOT_PASSWORD . "($sp_params)";
        $result = $this->db->query($sqlCallSP, __FILE__, __LINE__);
        $this->db->next_result();
        $outputParamQuery = "SELECT @userexists as isUserExists";
        $outParamResult = $this->db->query($outputParamQuery, __FILE__, __LINE__);
        while ($row = $this->db->fetch_array($outParamResult)) {
            $isUserExists = $row['isUserExists'];
        }
        if ($isUserExists) {
            while ($row = $this->db->fetch_array($result)) {
                $userid = $row['useridparam'];
                $otpparam = $row['otpparam'];
                $validuptodate = $row['otpvalidupto'];
                $email = $row['useremail'];
                $phone = $row['userphone'];
                $customerno = $row['custno'];
            }
            if ($otpparam == -1) {
                $arr_p['status'] = "unsuccessful";
                $arr_p['message'] = "Your otp request limit exceeded today.";
            } else {
                $isSMSSent = 0;
                $isEmailSent = 0;
                $statusMessage = '';
                $message = "OTP: " . $otpparam . "\r\n" . "Valid Until: " . date('d-M-Y h:i:s A', strtotime($validuptodate));
                if (!empty($phone)) {
                    $smsStatus->customerno = $customerno;
                    $smsStatus->userid = $userid;
                    $smsStatus->vehicleid = 0;
                    $smsStatus->mobileno = array($phone);
                    $smsStatus->message = $message;
                    $smsStatus->cqid = 0;
                    $smscount = $customermanager->getSMSStatus($smsStatus);
                    if ($smscount == 0) {
                        $response = '';
                        $isSMSSent = sendSMSUtil($phone, $message, $response);
                        $moduleid = 1;
                        if ($isSMSSent == 1) {
                            $customermanager->sentSmsPostProcess($customerno, $phone, $message, $response, $isSMSSent, $userid, $vehicleid, $moduleid);
                            $statusMessage = "OTP Number SMS sent successfully. " . (($smsLogId > 0) ? "SMS logged" : " SMS logging failed.");
                        } else {
                            $statusMessage = "OTP Number SMS sending failed";
                        }
                    }
                }
                if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $body = '';
                    $body = $message;
                    $body .= '<br/>Please login on your ElixiaSpeed Mobile App with your username and mentioned OTP.<br/><br/>';
                    $subject = "ElixiaSpeed Forgot Password OTP";
                    $arrToMailIds = array($email);
                    $strCCMailIds = '';
                    $strBCCMailIds = '';
                    $attachmentFilePath = '';
                    $attachmentFileName = '';
                    $isEmailSent = $this->sendMail($arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $body, $attachmentFilePath, $attachmentFileName);
                    if ($isEmailSent) {
                        $emailMessage = "OTP Number Email sent successfully";
                        $statusMessage = $statusMessage != '' ? $statusMessage . ", " . $emailMessage : $emailMessage;
                    } else {
                        $emailMessage = "OTP Number Email sending failed";
                        $statusMessage = $statusMessage != '' ? $statusMessage . ", " . $emailMessage : $emailMessage;
                    }
                }
                $arr_p['status'] = "successful";
                $arr_p['message'] = $statusMessage;
            }
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function update_password($userkey, $newpwd) {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "update password failed.";
        $todaysdate = date("Y-m-d H:i:s");
        $sp_params = "'" . $newpwd . "'"
                . ",'" . $userkey . "'"
                . ",'" . $todaysdate . "'";
        $queryCallSP = "CALL " . SP_UPDATE_NEWFORGOTPASSWORD . "($sp_params)";
        $result = $this->db->query($queryCallSP, __FILE__, __LINE__);
        $affectedRows = $this->db->get_affectedRows($result);
        if ($affectedRows > 0) {
            $arr_p['status'] = "successful";
            $arr_p['message'] = "update password successful.";
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function sendSummaryReport_wh($userkey, $vehicleno, $deviceid, $startDate, $startTime, $endDate, $endTime, $reportInterval, $toaddresses, $comments, $mail_type, $isWareHouse, $useHumidity) {
        $reportsObj = new reports();
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        $tempStartDate = new DateTime($startDate);
        $tempEndDate = new DateTime($endDate);
        $datediff = $tempStartDate->diff($tempEndDate)->days;
        $switchto = $isWareHouse ? "3" : "";
        if ($datediff <= 30) {
            $validation = $this->check_userkey($userkey);
            if ($validation['status'] == "successful") {
                $customerno = $validation['customerno'];
                //check whether the vehicle belongs to customer
                $devices = $this->checkforvalidity($customerno, $deviceid);
                if (isset($devices) && !empty($devices)) {
                    $serverpath = "../../..";
                    $arrToMailIds = explode(",", $toaddresses);
                    //Remove empty elements of an array
                    $arrToMailIds = array_filter($arrToMailIds);
                    $mail_content = $comments;
                    if ($useHumidity) {
                        $file_end = "_Humidity&TemperatureReport";
                        $subject = "Humidity & Temperature Report";
                    } else {
                        $file_end = "_TemperatureReport";
                        $subject = "Temperature Report";
                    }
                    $veh_no1 = isset($vehicleno) ? str_replace(' ', '', $vehicleno) : '';
                    $veh_no = isset($veh_no1) ? str_replace('/', '_', $veh_no1) : '';
                    $file_name = $veh_no . "_" . date("d-m-Y") . $file_end;
                    $full_path = $serverpath . "/customer/" . $customerno . "/";
                    if ($useHumidity) {
                        $reportHtmlData = $reportsObj->gettemphumidityreport($customerno, $startDate, $endDate, $deviceid, $vehicleno, $reportInterval, $startTime, $endTime, $switchto, $mail_type);
                    } else {
                        $reportHtmlData = $reportsObj->gettempreport($customerno, $startDate, $endDate, $deviceid, $vehicleno, $reportInterval, $startTime, $endTime, $switchto, $mail_type);
                    }
                   // die($reportHtmlData);
                    
                    if (!empty($reportHtmlData)) {
                        if ($mail_type === 'pdf') {
                            $ext = ".pdf";
                            $fpath = $full_path . $file_name . $ext;
                            $reportsObj->save_pdf($fpath, $reportHtmlData);
                        } else {
                            $ext = ".xls";
                            $fpath = $full_path . $file_name . $ext;
                            $reportsObj->save_xls($fpath, $reportHtmlData);
                        }
                        $arrToMailIds = explode(',', $toaddresses);
                        if (isset($arrToMailIds) && !empty($arrToMailIds)) {
                            $strCCMailIds = '';
                            $strBCCMailIds = '';
                            $isMailSent = $this->sendMail($arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $mail_content, $full_path, $file_name . $ext);
                            if ($isMailSent) {
                                $arr_p['status'] = "successful";
                                $arr_p['message'] = "Mail Sent successfully.";
                            } else {
                                $arr_p['message'] = "Unable to send mail.";
                            }
                        }
                    } else {
                        $arr_p['message'] = "No data found for selected day.";
                    }
                } else {
                    $arr_p['message'] = "Device does not belong to the customer";
                }
            }
        } else {
            $arr_p['message'] = "Please Select Dates With Difference Of Not More Than 30 Days";
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function pull_user_group($userkey) {
        $grouplist = NULL;
        $validation = $this->check_userkey($userkey);
        if ($validation['status'] == "successful") {
            // successful
            $customerno = $validation["customerno"];
            $grouplist = array();
            $groupidsql = "select group.groupid,group.groupname from `group`
                INNER JOIN groupman ON groupman.groupid = group.groupid
                INNER JOIN user ON user.userid = groupman.userid
                where user.userkey=$userkey AND group.isdeleted=0 AND groupman.isdeleted=0 order by group.groupname ASC";
            $recordgrp = $this->db->query($groupidsql, __FILE__, __LINE__);
            while ($rowgrp = $this->db->fetch_array($recordgrp)) {
                $group = new VODevices();
                $group->groupid = $rowgrp['groupid'];
                $group->groupname = $rowgrp['groupname'];
                $grouplist[] = $group;
            }
            if (!isset($grouplist) || count($grouplist) == 0) {
                $grp = '';
                $custom = $this->custom_fields($customerno);
                if (!empty($custom)) {
                    foreach ($custom as $type) {
                        if ($type->name == "Group") {
                            $grp = $type->customname;
                        }
                    }
                }
                $group = new VODevices();
                $group->groupid = 0;
                if ($grp != '') {
                    $group->groupname = "All " . $grp;
                } else {
                    $group->groupname = "All Groups";
                }
                $grouplist[] = $group;
                $Query = "SELECT groupid,groupname FROM `group` where customerno=$customerno AND isdeleted=0 order by groupname ASC";
                $recordgrp = $this->db->query($Query, __FILE__, __LINE__);
                while ($rowgrp = $this->db->fetch_array($recordgrp)) {
                    $group = new VODevices();
                    $group->groupid = $rowgrp['groupid'];
                    $group->groupname = $rowgrp['groupname'];
                    $grouplist[] = $group;
                }
            }
            $arr_p['grouplist'] = $grouplist;
            $arr_p['status'] = "successful";
            echo json_encode($arr_p);
            return $arr_p;
        } else {
            $arr_p['message'] = "Invalid user.";
            $arr_p['status'] = "unsuccessful";
            echo json_encode($arr_p);
            return $arr_p;
        }
    }

    function client_code_details($ecodeid) {
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        $temp_conversion = new TempConversion();
        $Query = "SELECT vehicle.overspeed_limit, vehicle.stoppage_flag, vehicle.vehicleno,vehicle.vehicleid,devices.deviceid,
            devices.devicelat,devices.devicelong,driver.drivername,driver.driverphone,vehicle.curspeed,
            devices.lastupdated,vehicle.kind,devices.ignition,devices.status,devices.directionchange,
            devices.uid,elixiacode.expirydate,vehicle.overspeed_limit,vehicle.temp1_min,vehicle.temp1_max,
            vehicle.temp2_min,vehicle.temp2_max,vehicle.temp3_min,vehicle.temp3_max,vehicle.temp4_min,vehicle.temp4_max,
            unit.tempsen1,unit.tempsen2,unit.tempsen3,unit.tempsen4,unit.get_conversion,
            customer.temp_sensors,customer.use_humidity FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid
            INNER JOIN 	customer ON customer.customerno = unit.customerno
            INNER JOIN ecodeman ON ecodeman.vehicleid = vehicle.vehicleid
            INNER JOIN elixiacode ON elixiacode.id = ecodeman.ecodeid
            where elixiacode.ecode=%d AND unit.trans_statusid NOT IN (10,22)";
        $devicesQuery = sprintf($Query, $ecodeid);
        $record = $this->db->query($devicesQuery, __FILE__, __LINE__);
        $json_p = array();
        $arr_p['error'] = 'Invalid Client Code';
        $x = 0;
        while ($row = $this->db->fetch_array($record)) {
            if ($row['uid'] > 0) {
                if ($row['devicelat'] > 0 & $row['devicelong'] > 0) {
                    $checkstatus = $this->checkvalidity($row["expirydate"]);
                    if ($checkstatus == true) {
                        $json_p[$x]['vehicleno'] = $row['vehicleno'];
                        $json_p[$x]['vehicleid'] = $row['vehicleid'];
                        $json_p[$x]['deviceid'] = $row['deviceid'];
                        $json_p[$x]['devicelat'] = $row['devicelat'];
                        $json_p[$x]['devicelong'] = $row['devicelong'];
                        $json_p[$x]['drivername'] = $row['drivername'];
                        $json_p[$x]['driverphone'] = $row['driverphone'];
                        $json_p[$x]['curspeed'] = $row['curspeed'];
                        $json_p[$x]['lastupdated'] = $row['lastupdated'];
                        $json_p[$x]['type'] = $row['kind'];
                        if ($row['kind'] == "Car") {
                            $kind = 1;
                        } elseif ($row['kind'] == "Truck") {
                            $kind = 2;
                        } elseif ($row['kind'] == "Bus") {
                            $kind = 3;
                        } elseif ($row['kind'] == "Warehouse") {
                            $kind = 4;
                        }
                        $json_p[$x]['kind'] = $kind;
                        $json_p[$x]['ignition'] = $row['ignition'];
                        $json_p[$x]['devicestatus'] = $row['status'];
                        $json_p[$x]['directionchange'] = $row['directionchange'];
                        $json_p[$x]['overspeed_limit'] = $row['overspeed_limit'];
                        $json_p[$x]['stoppage_flag'] = $row['stoppage_flag'];
                        $status = "";
                        $ServerIST_less1 = new DateTime();
                        $ServerIST_less1->modify('-60 minutes');
                        $lastupdated = new DateTime($row['lastupdated']);
                        $temp_conversion->use_humidity = $row['use_humidity'];
                        $temp_conversion->unit_type = $row['get_conversion'];
                        $temp_conversion->switch_to = 3;
                        if ($lastupdated < $ServerIST_less1) {
                            $status = "1"; //inactive grey
                        } else {
                            $status = "3"; // default green
                            if (isset($row['temp_sensors'])) {
                                $temp1 = '';
                                $temp1_min = '';
                                $temp1_max = '';
                                $temp2 = '';
                                $temp2_min = '';
                                $temp2_max = '';
                                $temp3 = '';
                                $temp3_min = '';
                                $temp3_max = '';
                                $temp4 = '';
                                $temp4_min = '';
                                $temp4_max = '';
                                switch ($row['temp_sensors']) {
                                    case 4:
                                        if (isset($row['tempsen4'])) {
                                            $s = "analog" . $row['tempsen4'];
                                            $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                            if ($row['tempsen4'] != 0 && $analogValue != 0) {
                                                $temp_conversion->rawtemp = $analogValue;
                                                $temp4 = getTempUtil($tempConversion);
                                            } else {
                                                $temp4 = '';
                                            }
                                            $temp4_min = (isset($row['temp4_min'])) ? $row['temp4_min'] : '';
                                            $temp4_max = (isset($row['temp4_max'])) ? $row['temp4_max'] : '';
                                        }
                                    case 3:
                                        if (isset($row['tempsen3'])) {
                                            $s = "analog" . $row['tempsen3'];
                                            $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                            if ($row['tempsen3'] != 0 && $analogValue != 0) {
                                                $temp_conversion->rawtemp = $analogValue;
                                                $temp3 = getTempUtil($tempConversion);
                                            } else {
                                                $temp3 = '';
                                            }
                                            $temp3_min = (isset($row['temp3_min'])) ? $row['temp3_min'] : '';
                                            $temp3_max = (isset($row['temp3_max'])) ? $row['temp3_max'] : '';
                                        }
                                    case 2:
                                        if (isset($row['tempsen2'])) {
                                            $s = "analog" . $row['tempsen2'];
                                            $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                            if ($row['tempsen2'] != 0 && $analogValue != 0) {
                                                $temp_conversion->rawtemp = $analogValue;
                                                $temp2 = getTempUtil($tempConversion);
                                            } else {
                                                $temp2 = '';
                                            }
                                            $temp2_min = (isset($row['temp2_min'])) ? $row['temp2_min'] : '';
                                            $temp2_max = (isset($row['temp2_max'])) ? $row['temp2_max'] : '';
                                        }
                                    case 1:
                                        if (isset($row['tempsen1'])) {
                                            $s = "analog" . $row['tempsen1'];
                                            $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                            if ($row['tempsen1'] != 0 && $analogValue != 0) {
                                                $temp_conversion->rawtemp = $analogValue;
                                                $temp1 = getTempUtil($tempConversion);
                                            } else {
                                                $temp1 = '';
                                            }
                                            $temp1_min = (isset($row['temp1_min'])) ? $row['temp1_min'] : '';
                                            $temp1_max = (isset($row['temp1_max'])) ? $row['temp1_max'] : '';
                                        }
                                        break;
                                    default:
                                        if (isset($row['tempsen1'])) {
                                            $s = "analog" . $row['tempsen1'];
                                            $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                            if ($row['tempsen1'] != 0 && $analogValue != 0) {
                                                $temp_conversion->rawtemp = $analogValue;
                                                $temp1 = getTempUtil($tempConversion);
                                            } else {
                                                $temp1 = '';
                                            }
                                            $temp1_min = (isset($row['temp1_min'])) ? $row['temp1_min'] : '';
                                            $temp1_max = (isset($row['temp1_max'])) ? $row['temp1_max'] : '';
                                        }
                                        break;
                                }
                                if ($temp1 != '') {
                                    if ($temp1 < $temp1_min || $temp1 > $temp1_max && $temp1_min != $temp1_max) {
                                        $status = "2"; // temperature 1 conflict
                                    }
                                }
                                if ($temp2 != '') {
                                    if ($temp2 < $temp2_min || $temp2 > $temp2_max && $temp2_min != $temp2_max) {
                                        $status = "2"; // temperature 2 conflict
                                    }
                                }
                                if ($temp3 != '') {
                                    if ($temp3 < $temp3_min || $temp3 > $temp3_max && $temp3_min != $temp3_max) {
                                        $status = "2"; // temperature 3 conflict
                                    }
                                }
                                if ($temp4 != '') {
                                    if ($temp4 < $temp4_min || $temp4 > $temp4_max && $temp4_min != $temp4_max) {
                                        $status = "2"; // temperature 4 conflict
                                    }
                                }
                            }
                        }
                        $json_p[$x]['vehicle_color'] = $status;
                        $arr_p['error'] = '';
                        $arr_p['status'] = "successful";
                        $x++;
                    } else {
                        $arr_p['error'] = 'Client Code Expired';
                    }
                    $arr_p['result'] = $json_p;
                }
            }
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function validateOtpForAuthentication($jsonRequest) {
        $arrResult = array();
        $arrResult['status'] = 0;
        $arrResult['message'] = speedConstants::API_INVALID_USERKEY;
        $arrResponse = array();
        $validation = $this->check_userkey($jsonRequest->userkey);
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            $userid = $validation['userid'];
            if ($validation['multiauth'] == 1 && $jsonRequest->otp != '') {
                $um = new UserManager();
                $validationStatus = $um->validateOtpFor2WayAuthentication($userid, $jsonRequest->otp);
                if (isset($validationStatus) && $validationStatus == 1) {
                    $arrResult['status'] = 1;
                    $arrResult['message'] = "Valid OTP";
                } else {
                    $arrResult['status'] = 0;
                    $arrResult['message'] = "Invalid OTP";
                }
            } elseif ($validation['multiauth'] == 1 && $jsonRequest->otp == '') {
                $arrResult['status'] = 0;
                $arrResult['message'] = "Please Enter OTP";
            }
        }
        return $arrResult;
    }

    function update_gcmid($getObjectData) {
        $arr_p = NULL;
        $userkey = $getObjectData->userkey;
        $gcmid = $getObjectData->gcmid;
        $validation = $this->check_userkeyNew($getObjectData);
        $userid = $validation->userid;
        if ($validation->status == "successful") {
            $sql = "update user set gcmid='" . $gcmid . "' where userid =" . $userid;
            $record = $this->db->query($sql, __FILE__, __LINE__);
            $arr_p['status'] = "successful";
            $arr_p['message'] = "Gcmid updated sucessfully.";
        } else {
            $arr_p['status'] = "unsuccessful";
            $arr_p['message'] = "wronguserkey";
        }
        return $arr_p;
    }

    function update_user_notification_status($getObjectData) {
        $arr_p = NULL;
        $userkey = $getObjectData->userkey;
        $notification_status = $getObjectData->notification_status;
        $validation = $this->check_userkeyNew($getObjectData);
        $userid = $validation->userid;
        if ($validation->status == "successful") {
            $sql = "update user "
                    . " set mess_mobilenotification =" . $notification_status . " ,speed_mobilenotification=" . $notification_status . " "
                    . " ,power_mobilenotification=" . $notification_status . " ,tamper_mobilenotification =" . $notification_status . " "
                    . " ,chk_mobilenotification =" . $notification_status . " ,ac_mobilenotification =" . $notification_status . " "
                    . " ,ignition_mobilenotification=" . $notification_status . " ,aci_mobilenotification =" . $notification_status . " "
                    . " ,temp_mobilenotification=" . $notification_status . " ,panic_mobilenotification=" . $notification_status . " "
                    . " ,immob_mobilenotification =" . $notification_status . " ,door_mobilenotification =" . $notification_status . " "
                    . " ,harsh_break_mobilenotification=" . $notification_status . " ,high_acce_mobilenotification=" . $notification_status . " "
                    . " ,sharp_turn_mobilenotification=" . $notification_status . " ,towing_mobilenotification =" . $notification_status . " "
                    . " ,hum_mobilenotification=" . $notification_status . " ,notification_status=" . $notification_status . " "
                    . " where userid =" . $userid;
            $record = $this->db->query($sql, __FILE__, __LINE__);
            $arr_p['status'] = "successful";
            if ($notification_status == 1) {
                $arr_p['message'] = "user notification status is enable";
            } else {
                $arr_p['message'] = "user notification status is disable";
            }
        } else {
            $arr_p['status'] = "unsuccessful";
            $arr_p['message'] = "wronguserkey";
        }
        return $arr_p;
    }

    function PullAlertHistory($getObjectData) {
        $arr_p = NULL;
        $arrResult = array();
        $validation = $this->check_userkeyNew($getObjectData);
        if ($validation->status == "successful") {
            $customerno = $validation->customerno;
            $userid = $validation->userid;
            $getObjectData->customerno = $customerno;
            $getObjectData->userid = $userid;
            $getObjectData->checkpointId = '';
            $getObjectData->fenceId = '';
            //TODO: CALL function from existing FUNCTION FILE (Check alert history report)
            /* Pull Alert History */
            $arrAlertHistory = $this->getAlertHistory($getObjectData);
            if (isset($arrAlertHistory) && !empty($arrAlertHistory)) {
                $arr_p['status'] = "successful";
                $arr_p['message'] = "Pull alerthistory";
                $arr_p['result'] = $arrAlertHistory;
            } else {
                $arr_p['status'] = "successful";
                $arr_p['message'] = "Data not found";
            }
        } else {
            $arr_p['status'] = "unsuccessful";
            $arr_p['message'] = "wronguserkey";
        }
        return $arr_p;
    }

    public function getAlertHistory($objAlertHistReqDetails) {
        $arrAlertHistory = array();
        $comQueManager = new ComQueueManager($objAlertHistReqDetails->customerno);
        $currentdate = date("d-m-Y");
        $objAlertHistReqDetails->reportDate = date('d-m-Y', strtotime($objAlertHistReqDetails->reportDate));
        if (isset($objAlertHistReqDetails->reportDate) && $objAlertHistReqDetails->reportDate == $currentdate) {
            $queues = $comQueManager->getalerthist($objAlertHistReqDetails->reportDate, $objAlertHistReqDetails->reportType, $objAlertHistReqDetails->vehicleId, $objAlertHistReqDetails->checkpointId, $objAlertHistReqDetails->fenceId, $objAlertHistReqDetails->customerno, $objAlertHistReqDetails->groupid);
            if (isset($queues)) {
                foreach ($queues as $queue) {
                    $objHistory = new stdClass();
                    $objHistory->message = $queue->message;
                    $objHistory->timeadded = $queue->timeadded;
                    $objHistory->email = '';
                    $objHistory->phone = '';
                    if ($queue->processed == 1 && $queue->comtype == 0) {
                        $objHistory->email = $queue->email;
                    } elseif ($queue->processed == 1 && $queue->comtype == 1) {
                        $objHistory->phone = $queue->phone;
                    }
                    $arrAlertHistory[] = $objHistory;
                }
            }
        } else {
            $dt = strtotime(date("Y-m-d", strtotime($objAlertHistReqDetails->reportDate)));
            $file = date("MY", $dt);
            $location = "../../../customer/" . $objAlertHistReqDetails->customerno . "/history/$file.sqlite";
            if (file_exists($location)) {

                $queues = $this->getalerthist_sqlite($location, $objAlertHistReqDetails->reportDate, $objAlertHistReqDetails->reportType, $objAlertHistReqDetails->vehicleId, $objAlertHistReqDetails->checkpointId, $objAlertHistReqDetails->fenceId, $objAlertHistReqDetails->customerno, $objAlertHistReqDetails->groupid);
                if (isset($queues)) {
                    foreach ($queues as $queue) {
                        if ($queue->processed == 1) {
                            $historys = $this->getcomhist_sqlite($location, $queue->cqid);
                            if (isset($historys)) {
                                foreach ($historys as $history) {
                                    $objHistory = new stdClass();
                                    $objHistory->message = $queue->message;
                                    $objHistory->timeadded = $queue->timeadded;
                                    $objHistory->email = '';
                                    $objHistory->phone = '';
                                    if ($history->comtype == 0) {
                                        $users = $comQueManager->getuserdetailss($history->userid);
                                        $objHistory->email = $users->email;
                                    } elseif ($history->comtype == 1) {
                                        $users = $comQueManager->getuserdetailss($history->userid);
                                        $objHistory->phone = $users->phone;
                                    }
                                    $arrAlertHistory[] = $objHistory;
                                }
                            }
                        } else {
                            $objHistory = new stdClass();
                            $objHistory->message = $queue->message;
                            $objHistory->timeadded = $queue->timeadded;
                            $objHistory->email = '';
                            $objHistory->phone = '';
                            $arrAlertHistory[] = $objHistory;
                        }
                    }
                }
            }
        }
        usort($arrAlertHistory, function ($a, $b) {
            return ($a->timeadded > $b->timeadded) ? -1 : 1;
        });

        return $arrAlertHistory;
    }

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Helper functions">
    function checkforvalidity($customerno, $deviceid = null) {
        $devices = Array();
        $Query = "SELECT deviceid,expirydate, Now() as today FROM `devices` where customerno=%d ";
        if ($deviceid != null) {
            $Query .= " AND deviceid = $deviceid";
        }
        $devicesQuery = sprintf($Query, $customerno);
        $record = $this->db->query($devicesQuery, __FILE__, __LINE__);
        while ($row = $this->db->fetch_array($record)) {
            $device = new VODevices();
            $device->deviceid = $row['deviceid'];
            $device->today = $row["today"];
            $device->expirydate = $row["expirydate"];
            $devices[] = $device;
        }
        return $devices;
    }

    function check_validity_login($expirydate, $currentdate) {
        date_default_timezone_set("Asia/Calcutta");
        $expirytimevalue = '23:59:59';
        $expirydate = date('Y-m-d H:i:s', strtotime("$expirydate $expirytimevalue"));
        $realtime = strtotime($currentdate);
        $expirytime = strtotime($expirydate);
        $diff = $expirytime - $realtime;
        return $diff;
    }

    function check_userkey($userkey) {
        $sql = "SELECT u.userid, u.customerno, u.realname, u.userkey, u.roleid, u.role, u.heirarchy_id, c.multiauth
                    FROM user u
                    INNER JOIN customer c on c.customerno = u.customerno
                    WHERE u.userkey='" . $userkey . "' AND u.isdeleted=0 AND c.isoffline = 0";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $row = $this->db->fetch_array($record);
        $retarray = array();
        if ($row['userkey'] != "") {
            $retarray['status'] = "successful";
            $retarray['customerno'] = $row["customerno"];
            $retarray['userid'] = $row["userid"];
            $retarray['realname'] = $row["realname"];
            $retarray['roleid'] = $row["roleid"];
            $retarray['role'] = $row["role"];
            $retarray['heirarchy_id'] = $row["heirarchy_id"];
            $retarray['multiauth'] = $row["multiauth"];
        } else {
            $retarray['status'] = "unsuccessful";
        }
        return $retarray;
    }

    function check_userkeyNew($getObjectData) {
        $retarray = array();
        $retarray['status'] = "unsuccessful";
        if (is_numeric($getObjectData->userkey)) {
            $sql = "SELECT u.smsalert_status,u.emailalert_status,u.notification_status,c.smsleft,u.userid, u.customerno, u.realname, u.userkey, u.roleid, u.role, u.heirarchy_id, c.multiauth
                    FROM user u
                    INNER JOIN customer c on c.customerno = u.customerno
                    WHERE userkey='" . $getObjectData->userkey . "' AND isdeleted=0";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            $row = $this->db->fetch_array($record);
            $responseData = new stdClass();
            if ($row['userkey'] != "") {
                $responseData->status = "successful";
                $responseData->customerno = $row["customerno"];
                $responseData->userid = $row["userid"];
                $responseData->realname = $row["realname"];
                $responseData->roleid = $row["roleid"];
                $responseData->role = $row["role"];
                $responseData->heirarchy_id = $row["heirarchy_id"];
                $responseData->multiauth = $row["multiauth"];
                $responseData->smsleft = $row["smsleft"];
                $responseData->notification_status = $row["notification_status"];
                $responseData->smsalert_status = $row["smsalert_status"];
                $responseData->emailalert_status = $row["emailalert_status"];
            } else {
                $responseData->status = "unsuccessful";
            }
        }
        return $responseData;
    }

    function gethumidity($rawtemp) {
        $temp = round(($rawtemp / 100), 2);
        return $temp;
    }

    function getDigitalTemp($rawValue) {
        $value = round(($rawValue / 100), 2);
        return $value;
    }

    function pull_groups($userkey) {
        $validation = $this->check_userkey($userkey);
        if ($validation['status'] == "successful") {
            // successful
            $customerno = $validation["customerno"];
            $groupids = array();
            $groupidsql = "select group.groupid,group.groupname from `group`
            INNER JOIN groupman ON groupman.groupid = group.groupid
            INNER JOIN user ON user.userid = groupman.userid
            where user.userkey=$userkey AND group.isdeleted=0 AND groupman.isdeleted=0 order by group.groupname ASC";
            $recordgrp = $this->db->query($groupidsql, __FILE__, __LINE__);
            while ($rowgrp = $this->db->fetch_array($recordgrp)) {
                $group = new VODevices();
                $group->groupid = $rowgrp['groupid'];
                $group->groupname = $rowgrp['groupname'];
                $groupids[] = $group;
            }
            if (!isset($groupids) || count($groupids) == 0) {
                $grp = '';
                $custom = $this->custom_fields($customerno);
                if (!empty($custom)) {
                    foreach ($custom as $type) {
                        if ($type->name == "Group") {
                            $grp = $type->customname;
                        }
                    }
                }
                $group = new VODevices();
                $group->groupid = 0;
                if ($grp != '') {
                    $group->groupname = "All " . $grp;
                } else {
                    $group->groupname = "All Groups";
                }
                $groupids[] = $group;
                $Query = "SELECT groupid,groupname FROM `group` where customerno=$customerno AND isdeleted=0 order by groupname ASC";
                $recordgrp = $this->db->query($Query, __FILE__, __LINE__);
                while ($rowgrp = $this->db->fetch_array($recordgrp)) {
                    $group = new VODevices();
                    $group->groupid = $rowgrp['groupid'];
                    $group->groupname = $rowgrp['groupname'];
                    $groupids[] = $group;
                }
            }
            return $groupids;
        }
    }

    function custom_fields($customerno) {
        $custs = array();
        $pdo = $this->db->CreatePDOConn();
        $sp_params = "'" . $customerno . "'";
        $queryCallSP = "CALL " . speedConstants::SP_GET_CUSTOMFIELD_CUSTOMER . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
        $this->db->ClosePDOConn($pdo);
        if (!empty($arrResult)) {
            foreach ($arrResult as $data) {
                $cust = new stdClass();
                $cust->name = $data['name'];
                $cust->customname = $data['customname'];
                $custs[] = $cust;
            }
        }
        return $custs;
    }

    function checkvalidity($expirydate) {
        $today = date('Y-m-d H:i:s');
//        $today = add_hours($today, 0);
        if (strtotime($today) <= strtotime($expirydate)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_push_android_chk($userkey, $val) {
        $sql = "UPDATE user SET chkmanpushandroid = " . $val . " WHERE userkey = '" . $userkey . "'";
        $this->db->query($sql, __FILE__, __LINE__);
    }

    function update_push_android_chk_main($userkey, $val) {
        $sql = "UPDATE user SET chkpushandroid = " . $val . " WHERE userkey = '" . $userkey . "'";
        $this->db->query($sql, __FILE__, __LINE__);
    }

    function getvehicledetail($vehicleid, $customerno) {
        $sql = "SELECT v.vehicleid,v.uid, v.vehicleno,u.unitno,d.lastupdated,d.devicelat, d.devicelong
            FROM vehicle as v
            INNER JOIN devices as d ON d.uid = v.uid
            INNER JOIN unit as u ON d.uid = u.uid
            WHERE v.customerno='" . $customerno . "' AND v.isdeleted=0 AND v.vehicleid ='" . $vehicleid . "' ORDER BY d.lastupdated DESC";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $data = array();
        $rowcount = $this->db->num_rows($record);
        if ($rowcount > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $data = array(
                    "vehicleid" => $row['vehicleid'],
                    "devicelat" => $row['devicelat'],
                    "devicelong" => $row['devicelong'],
                    "uid" => $row['uid'],
                );
            }
            return $data;
        }
        return null;
    }

    function getCustomizeName($customerno, $customeid, $name) {
        $SQL = "SELECT customname FROM `customfield` where customerno=$customerno AND custom_id = $customeid AND `usecustom`=1";
        $record = $this->db->query($SQL, __FILE__, __LINE__);
        $rowcount = $this->db->num_rows($record);
        if ($rowcount > 0) {
            $row = $this->db->fetch_array($record);
            return $row['customname'];
        } else {
            return $name;
        }
    }

    function ret_issetor(&$var) {
        return isset($var) ? true : false;
    }

    function get_vehicle($vehicleid, $customerno) {
        $Query = "SELECT vehicle.vehicleno
                    , driver.drivername
                    , driver.driverphone
                    , devices.deviceid
                    , devices.devicelat
                    , devices.devicelong
                    , unit.unitno
                    , unit.tempsen1
                    , unit.tempsen2
                    , unit.tempsen3
                    , unit.tempsen4
                    , unit.get_conversion
                    , unit.humidity
                    , customer.temp_sensors
                    , customer.use_humidity
                    , COALESCE((SELECT name FROM nomens where nid = unit.n1), 'Temperature1') AS n1
                    , COALESCE((SELECT name FROM nomens where nid = unit.n2), 'Temperature2') AS n2
                    , COALESCE((SELECT name FROM nomens where nid = unit.n3), 'Temperature3') AS n3
                    , COALESCE((SELECT name FROM nomens where nid = unit.n4), 'Temperature4') AS n4
                    FROM    vehicle
                    inner join unit on unit.uid=vehicle.uid
                    inner join devices on devices.uid=unit.uid
                    inner join customer on customer.customerno=vehicle.customerno
                    left join driver on driver.driverid=vehicle.driverid
                    WHERE   vehicle.customerno =$customerno
                    AND     vehicle.vehicleid=$vehicleid
                    AND     unit.customerno=$customerno
                    AND     devices.customerno=$customerno
                    AND     vehicle.isdeleted=0
                    group by vehicle.vehicleid";
        $record = $this->db->query($Query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);
        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $vehicle = new VODevices();
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->devicelat = $row['devicelat'];
                $vehicle->devicelong = $row['devicelong'];
                $vehicle->unitno = $row['unitno'];
                $vehicle->tempsen1 = $row['tempsen1'];
                $vehicle->tempsen2 = $row['tempsen2'];
                $vehicle->tempsen3 = $row['tempsen3'];
                $vehicle->tempsen4 = $row['tempsen4'];
                $vehicle->get_conversion = $row['get_conversion'];
                $vehicle->humidity = $row['humidity'];
                $vehicle->temp_sensors = $row['temp_sensors'];
                $vehicle->use_humidity = $row['use_humidity'];
                $vehicle->n1 = $row['n1'];
                $vehicle->n2 = $row['n2'];
                $vehicle->n3 = $row['n3'];
                $vehicle->n4 = $row['n4'];
            }
            return $vehicle;
        }
        return null;
    }

    function getduration1($StartTime) {
        $EndTime = date('Y-m-d H:i:s');
        //echo $EndTime.'_'.$StartTime.'<br>';
        $idleduration = strtotime($EndTime) - strtotime($StartTime);
        $years = floor($idleduration / (365 * 60 * 60 * 24));
        $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        if ($years >= '1' || $months >= '1') {
            $diff = date('d-m-Y', strtotime($StartTime));
        } elseif ($days > 0) {
            $diff = $days . ' days ' . $hours . ' hrs ago';
        } elseif ($hours > 0) {
            $diff = $hours . ' hrs and ' . $minutes . ' mins ago';
        } elseif ($minutes > 0) {
            $diff = $minutes . ' mins ago';
        } else {
            $seconds = strtotime($EndTime) - strtotime($StartTime);
            $diff = $seconds . ' sec ago';
        }
        return $diff;
    }

    function DataForHumidity($path, $deviceid, $startdate, $enddate, $interval) {
        $devices = array();
        $dbs = new PDO($path);
        $query = "SELECT devicehistory.ignition, devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong, unithistory.vehicleid,unithistory.digitalio,unithistory.analog1, unithistory.analog2, unithistory.analog3, unithistory.analog4,vehiclehistory.vehicleno
            from devicehistory
            INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated and vehiclehistory.uid=devicehistory.uid
            WHERE devicehistory.lastupdated >= '$startdate' AND devicehistory.lastupdated <= '$enddate' AND devicehistory.deviceid= $deviceid AND devicehistory.status!='F' group by devicehistory.lastupdated";
        $sobj = $dbs->prepare($query);
        $sobj->execute();
        /* Fetch all of the remaining rows in the result set */
        $result = $sobj->fetchAll();
        if (isset($result) && !empty($result)) {
            foreach ($result as $row) {
                if (!isset($lastupdated)) {
                    $lastupdated = $row['lastupdated'];
                }
                if (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) >= $interval) {
                    $device = new VODevices();
                    $humidity = 'Not Active';
                    $temp = 'Not Active';
                    //$humidity = getpressure($row['analog3']);
                    if ($row['analog4'] != '0') {
                        $humidity = $this->getDigitalTemp($row['analog4']);
                    } else {
                        $humidity = '-';
                    }
                    if ($row['analog3'] != '0') {
                        $temp = $this->getDigitalTemp($row['analog3']);
                    } else {
                        $temp = '-';
                    }
                    $device->humidity = $humidity;
                    $device->temperature = $temp;
                    $device->starttime = $row['lastupdated'];
                    $device->endtime = $row['lastupdated'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->digitalio = $row['digitalio'];
                    $devices[] = $device;
                    $lastupdated = $row['lastupdated'];
                }
            }
            return $devices;
        }
    }

    function DataForTemprature($path, $vehicle, $startdate, $enddate, $interval) {
        $temp_coversion = new TempConversion();
        $temp_coversion->unit_type = $vehicle->get_conversion;
        $temp_coversion->use_humidity = $vehicle->use_humidity;
        $temp_coversion->switch_to = 3;
        $devices = array();
        $dbs = new PDO($path);
        $query = "SELECT devicehistory.ignition, devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong, unithistory.vehicleid,unithistory.digitalio,unithistory.analog1, unithistory.analog2, unithistory.analog3, unithistory.analog4,vehiclehistory.vehicleno
            from devicehistory
            INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated and vehiclehistory.uid=devicehistory.uid
            WHERE devicehistory.lastupdated >= '$startdate' AND devicehistory.lastupdated <= '$enddate' AND devicehistory.deviceid= $vehicle->deviceid AND devicehistory.status!='F' group by devicehistory.lastupdated";
        $sobj = $dbs->prepare($query);
        $sobj->execute();
        /* Fetch all of the remaining rows in the result set */
        $result = $sobj->fetchAll();
        if (isset($result) && !empty($result)) {
            foreach ($result as $row) {
                if (!isset($lastupdated)) {
                    $lastupdated = $row['lastupdated'];
                }
                $temp = $temp1 = $temp2 = $temp3 = $temp4 = $humidity = speedConstants::TEMP_NOTACTIVE;
                if (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) >= $interval) {
                    $device = new VODevices();
                    //$humidity = 'Not Active';
                    $temp = 'Not Active';
                    //$humidity = getpressure($row['analog3']);
                    switch ($vehicle->temp_sensors) {
                        case 4:
                            if ($vehicle->tempsen4 != 0) {
                                $temp4 = speedConstants::TEMP_WIRECUT;
                                $analogValue = $row['analog' . $vehicle->tempsen4];
                                if ($analogValue != 0 && $analogValue != 1150) {
                                    $temp_coversion->rawtemp = $analogValue;
                                    $temp4 = getTempUtil($temp_coversion) . " &degC";
                                }
                            }
                        case 3:
                            if ($vehicle->tempsen3 != 0) {
                                $temp3 = speedConstants::TEMP_WIRECUT;
                                $analogValue = $row['analog' . $vehicle->tempsen3];
                                if ($analogValue != 0 && $analogValue != 1150) {
                                    $temp_coversion->rawtemp = $analogValue;
                                    $temp3 = getTempUtil($temp_coversion) . " &degC";
                                }
                            }
                        case 2:
                            if ($vehicle->tempsen2 != 0) {
                                $temp2 = speedConstants::TEMP_WIRECUT;
                                $analogValue = $row['analog' . $vehicle->tempsen2];
                                if ($analogValue != 0 && $analogValue != 1150) {
                                    $temp_coversion->rawtemp = $analogValue;
                                    $temp2 = getTempUtil($temp_coversion) . " &degC";
                                }
                            }
                        case 1:
                            if ($vehicle->tempsen1 != 0) {
                                $temp1 = speedConstants::TEMP_WIRECUT;
                                $analogValue = $row['analog' . $vehicle->tempsen1];
                                if ($analogValue != 0 && $analogValue != 1150) {
                                    $temp_coversion->rawtemp = $analogValue;
                                    $temp1 = getTempUtil($temp_coversion) . " &degC";
                                }
                            }
                    }
                    if ($vehicle->use_humidity == 1) {
                        $analogValue = $row['analog' . $vehicle->humidity];
                        $temp_coversion->rawtemp = $analogValue;
                        $humidity = getTempUtil($temp_coversion) . " %";
                    }
                    $device->humidity = $humidity;
                    $device->temp1 = $temp1;
                    $device->temp2 = $temp2;
                    $device->temp3 = $temp3;
                    $device->temp4 = $temp4;
                    $device->starttime = $row['lastupdated'];
                    $device->endtime = $row['lastupdated'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->digitalio = $row['digitalio'];
                    $devices[] = $device;
                    $lastupdated = $row['lastupdated'];
                }
            }
            return $devices;
        }
    }

    function insertSMSLog($phone, $message, $response, $vehicleid, $userid, $customerno, $isSMSSent, $todaysdate, $moduleid) {
        $smsid = 0;
        $pdo = $this->db->CreatePDOConn();
        $sp_params = "'" . $phone . "'"
                . ",'" . $message . "'"
                . ",'" . $response . "'"
                . ",'" . $vehicleid . "'"
                . ",'" . $userid . "'"
                . ",'" . $customerno . "'"
                . ",'" . $isSMSSent . "'"
                . ",'" . $todaysdate . "'"
                . ",'" . $moduleid . "'"
                . "," . '@smsid';
        $queryCallSP = "CALL " . SP_INSERT_SMSLOG . "($sp_params)";
        $pdo->query($queryCallSP);
        $this->db->ClosePDOConn($pdo);
        $outputParamsQuery = "SELECT @smsid AS smsid";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        if (count($outputResult) > 0) {
            $smsid = $outputResult['smsid'];
        }
        return $smsid;
    }

    function multiauthRequest($userid, $customerno) {
        $status = "notok";
        $usermanager = new UserManager;
        $cm = new CustomerManager();
        $smsStatus = new SmsStatus();
        $getuser = $usermanager->check2WayAuthUser($userid);
        if (!empty($getuser)) {
            $message = "OTP: " . $getuser['otp'] . "\r\n" . "Valid Until: " . date('d-M-Y h:i:s A', strtotime($getuser['otpvalidupto']));
            $response = '';
            $smsStatus->customerno = $customerno;
            $smsStatus->userid = $userid;
            $smsStatus->vehicleid = 0;
            $smsStatus->mobileno = $getuser['userphone'];
            $smsStatus->message = $message;
            $smsStatus->cqid = 0;
            $smsstatus = $cm->getSMSStatus($smsStatus);
            if ($smsstatus == 0) {
                if ($getuser['userphone'] != '' && $getuser['otp'] != '-1') {
                    $isSMSSent = sendSMSUtil($getuser['userphone'], $message, $response);
                    if ($isSMSSent == 1) {
                        $cm->sentSmsPostProcess($customerno, $getuser['userphone'], $message, $response, $isSMSSent, $userid, 0, 1);
                        $status = "smsSent";
                    } else {
                        $status = "smsNotSent";
                    }
                } elseif ($getuser['userphone'] != '' && $getuser['otp'] == '-1') {
                    $status = "limitExceeded";
                } else {
                    $status = "phoneNotAvailable";
                }
            } elseif ($smsstatus == -2) {
                $status = "userLocked";
            } elseif ($smsstatus == -3) {
                $status = "noSmsBalance";
            }
        }
        return $status;
    }

    function getName($nid, $customerno) {
        $vehiclemanager = new VehicleManager($customerno);
        $vehicledata = $vehiclemanager->getNameForTemp($nid);
        return $vehicledata;
    }

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Utility functions">


    function failure($text) {
        return array('Status' => '0', 'Message' => $text);
    }

    function success($message, $result) {
        return array('Status' => '1', 'Message' => $message, 'Result' => $result);
    }

    function sendMail(array $arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName) {
        include_once "../../cron/class.phpmailer.php";
        $isEmailSent = 0;
        $completeFilePath = '';
        if ($attachmentFilePath != '' && $attachmentFileName != '') {
            $completeFilePath = $attachmentFilePath . "/" . $attachmentFileName;
        }
        $mail = new PHPMailer();
        $mail->IsMail();
        $mail->ClearAddresses();
        $mail->ClearAllRecipients();
        $mail->ClearAttachments();
        $mail->ClearCustomHeaders();
        if (!empty($arrToMailIds)) {
            foreach ($arrToMailIds as $mailto) {
                $mail->AddAddress($mailto);
            }
            if (!empty($strCCMailIds)) {
                $mail->AddCustomHeader("CC: " . $strCCMailIds);
            }
            if (!empty($strBCCMailIds)) {
                $mail->AddCustomHeader("BCC: " . $strBCCMailIds);
            }
        }
        $mail->From = "noreply@elixiatech.com";
        $mail->FromName = "Elixia Speed";
        $mail->Sender = "noreply@elixiatech.com";
        //$mail->AddReplyTo($from,"Elixia Speed");
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->IsHtml(true);
        if ($completeFilePath != '' && $attachmentFileName != '') {
            $mail->AddAttachment($completeFilePath, $attachmentFileName);
        }
        //SEND Mail
        if ($mail->Send()) {
            $isEmailSent = 1; // or use booleans here
        }
        return $isEmailSent;
    }

    //</editor-fold>
//
}

?>
