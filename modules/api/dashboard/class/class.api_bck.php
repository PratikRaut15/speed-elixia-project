<?php
require_once "database.inc.php";
date_default_timezone_set('Asia/Kolkata');
define("SP_GET_VEHICLEWAREHOUSE_DETAILS", "get_vehiclewarehouse_details_vts");
define("SP_AUTHENTICATE_FOR_LOGIN", "authenticate_for_login");

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

    //<editor-fold defaultstate="collapsed" desc="API Functions">
    function dashboard($userkey, $pageIndex, $pageSize, $searchstring, $groupidparam, $isRequiredThirdParty, $isWareHouse) {
        // $isWareHouse = 1;
        $totalWareHouseCount = 0;
        $validation = $this->check_userkey($userkey);
        $arr_p = array();

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

                $grouplist = array();
                $groupidsql = "select group.groupid,group.groupname from `group`
                INNER JOIN groupman ON groupman.groupid = group.groupid
                INNER JOIN user ON user.userid = groupman.userid
                where user.userid=" . $validation['userid'] . " AND group.isdeleted=0 AND groupman.isdeleted=0 order by group.groupname ASC";
                $recordgrp = $this->db->query($groupidsql, __FILE__, __LINE__);
                while ($rowgrp = $this->db->fetch_array($recordgrp)) {
                    $grouplist[] = $rowgrp['groupid'];
                }
                if (isset($grouplist) && count($grouplist) > 0) {
                $groupidparam = implode(',', $grouplist);
            }

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
                $queryCallSP = "CALL " . speedConstants::SP_GET_VEHICLEWAREHOUSE_DETAILS_VTS . "($sp_params)";
                $records = $this->db->query($queryCallSP, __FILE__, __LINE__);
                $json_p = array();
                $x = 0;
                while ($row = $this->db->fetch_array($records)) {

                    if ($totalWareHouseCount == 0) {
                        $totalWareHouseCount = $row['recordCount'];
                    }

                    $json_p[$x]['vehicleid'] = $row['vehicleid'];
                    $json_p[$x]['vehicleno'] = $row['vehicleno'];
                    $json_p[$x]['unitno'] = $row['unitno'];
                    $json_p[$x]['groupid'] = $row['groupid'];
                    $x++;

                }
                // Free result set
                $records->close();
                $this->db->next_result();
                $arr_p['result'] = $json_p;
                $arr_p['totalWareHouseCount'] = $totalWareHouseCount;

            } else {
                $arr_p['status'] = "expired";
            }
        } else {
            $arr_p['status'] = "unsuccessful";
        }
        return $arr_p;
    }

    function dashboardDevices($userkey, $pageIndex, $pageSize, $searchstring, $groupidparam, $isRequiredThirdParty, $isWareHouse) {
        // $isWareHouse = 1;
        $totalWareHouseCount = 0;
        $validation = $this->check_userkey($userkey);
        $arr_p = array();

        $temp_coversion = new TempConversion();
        if ($validation['status'] == "successful") {
            $customerno = $validation["customerno"];

            $grouplist = array();
            $groupidsql = "select group.groupid,group.groupname from `group`
            INNER JOIN groupman ON groupman.groupid = group.groupid
            INNER JOIN user ON user.userid = groupman.userid
            where user.userid=" . $validation['userid'] . " AND group.isdeleted=0 AND groupman.isdeleted=0 order by group.groupname ASC";
            $recordgrp = $this->db->query($groupidsql, __FILE__, __LINE__);
            while ($rowgrp = $this->db->fetch_array($recordgrp)) {
                $grouplist[] = $rowgrp['groupid'];
            }
            if (isset($grouplist) && count($grouplist) > 0) {
                $groupidparam = implode(',', $grouplist);
            }

            $arr_p['custom'] = $this->custom_fields($customerno);
            $sp_params = "'" . $pageIndex . "'"
                . ",'" . $pageSize . "'"
                . "," . $customerno . ""
                . "," . $isWareHouse . ""
                . ",'" . $searchstring . "'"
                . ",'" . $groupidparam . "'"
                . ",'" . $userkey . "'"
                . ",'" . $isRequiredThirdParty . "'";
            $queryCallSP = "CALL " . SP_GET_VEHICLEWAREHOUSE_DETAILS . "($sp_params)";
            $records = $this->db->query($queryCallSP, __FILE__, __LINE__);
            $json_p = array();
            $x = 0;
            while ($row = $this->db->fetch_array($records)) {
                if ($totalWareHouseCount == 0) {
                    $totalWareHouseCount = $row['recordCount'];
                }
                $json_p[$x]['vehicleid'] = $row['vehicleid'];
                $json_p[$x]['vehicleno'] = $row['vehicleno'];
                $json_p[$x]['unitno'] = $row['unitno'];
                $json_p[$x]['groupid'] = $row['groupid'];
                $json_p[$x]['temp_sensors'] = $row['temp_sensors'];
                $json_p[$x]['lastupdated'] = date('d-M-Y H:i A', strtotime($row['lastupdated']));

                $temp_coversion->unit_type = $row['get_conversion'];
                $temp_coversion->use_humidity = $row['use_humidity'];
                $temp_coversion->switch_to = 3;
                $vehicleStatus = "vehicleNormal"; // default green
                $ServerIST_less1 = new DateTime();
                $ServerIST_less1->modify('-60 minutes');
                $lastupdated = new DateTime($row['lastupdated']);
                if ($lastupdated < $ServerIST_less1) {
                    $vehicleStatus = "vehicleInactive"; //inactive grey
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
                    $status4 = 'vehicleNormal';
                    $status3 = 'vehicleNormal';
                    $status2 = 'vehicleNormal';
                    $status1 = 'vehicleNormal';
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
                                if ($temp4 < $temp4_min || $temp4 > $temp4_max && $temp4_min != $temp4_max && $vehicleStatus != "vehicleInactive") {
                                    $status4 = "vehicleConflict"; // temperature 4 conflict
                                    $vehicleStatus = "vehicleConflict"; // temperature 4 conflict
                                }
                                $temp4 = $temp4 . " &degC";
                            } else {
                                $temp4 = speedConstants::TEMP_WIRECUT;
                            }
                        }
                        $json_p[$x]['n4'] = isset($t4) ? $t4 : 'Temperature4 ';
                        $json_p[$x]['temp4'] = isset($temp4) ? $temp4 : "";
                        $json_p[$x]['status4'] = $status4;
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
                                if ($temp3 < $temp3_min || $temp3 > $temp3_max && $temp3_min != $temp3_max && $vehicleStatus != "vehicleInactive") {
                                    $status3 = "vehicleConflict"; // temperature 3 conflict
                                    $vehicleStatus = "vehicleConflict"; // temperature 3 conflict
                                }
                                $temp3 = $temp3 . " &degC";
                            } else {
                                $temp3 = speedConstants::TEMP_WIRECUT;
                            }
                        }
                        $json_p[$x]['n3'] = isset($t3) ? $t3 : 'Temperature3 ';
                        $json_p[$x]['temp3'] = isset($temp3) ? $temp3 : "";
                        $json_p[$x]['status3'] = $status3;
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
                                if ($temp2 < $temp2_min || $temp2 > $temp2_max && $temp2_min != $temp2_max && $vehicleStatus != "vehicleInactive") {
                                    $status2 = "vehicleConflict"; // temperature 2 conflict
                                    $vehicleStatus = "vehicleConflict"; // temperature 2 conflict
                                }
                                $temp2 = $temp2 . " &degC";
                            } else {
                                $temp2 = speedConstants::TEMP_WIRECUT;
                            }
                        }
                        $json_p[$x]['n2'] = isset($t2) ? $t2 : 'Temperature2 ';
                        $json_p[$x]['temp2'] = isset($temp2) ? $temp2 : "";
                        $json_p[$x]['status2'] = $status2;
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
                                if ($temp1 < $temp1_min || $temp1 > $temp1_max && $temp1_min != $temp1_max && $vehicleStatus != "vehicleInactive") {
                                    $status1 = "vehicleConflict"; // temperature 1 conflict
                                    $vehicleStatus = "vehicleConflict"; // temperature 1 conflict
                                }
                                $temp1 = $temp1 . " &degC";
                            } else {
                                $temp1 = speedConstants::TEMP_WIRECUT;
                            }
                        }
                        $json_p[$x]['n1'] = isset($t1) ? $t1 : 'Temperature1 ';
                        $json_p[$x]['temp1'] = isset($temp1) ? $temp1 : "";
                        $json_p[$x]['status1'] = $status1;
                        break;
                    }
                }
                if ($row['use_humidity'] == 1 && $row['humidity'] != 0) {
                    $temp4 = 0;
                    $s = "analog" . $row['humidity'];
                    $analogValue = isset($row[$s]) ? $row[$s] : 0;
                    if ($analogValue != 0) {
                        $temp_coversion->rawtemp = $analogValue;
                        $temp4 = getTempUtil($temp_coversion) . " %";
                    }
                    $json_p[$x]['n4'] = "Humidity";
                    $json_p[$x]['temp4'] = isset($temp4) ? $temp4 : "";
                }
                $json_p[$x]['vehicleStatus'] = $vehicleStatus;
                $json_p[$x]['temp1_min'] = $row['temp1_min'] . " &degC";
                $json_p[$x]['temp2_min'] = $row['temp2_min'] . " &degC";
                $json_p[$x]['temp3_min'] = $row['temp3_min'] . " &degC";
                $json_p[$x]['temp4_min'] = $row['temp4_min'] . " &degC";
                $json_p[$x]['temp1_max'] = $row['temp1_max'] . " &degC";
                $json_p[$x]['temp2_max'] = $row['temp2_max'] . " &degC";
                $json_p[$x]['temp3_max'] = $row['temp3_max'] . " &degC";
                $json_p[$x]['temp4_max'] = $row['temp4_max'] . " &degC";
                $x++;

            }
            $records->close();
            $this->db->next_result();
            $arr_p['result'] = $json_p;
            $arr_p['totalWareHouseCount'] = $totalWareHouseCount;
        } else {
            $arr_p['status'] = "unsuccessful";
        }
        return $arr_p;
    }

    //</editor-fold>

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
            $device = new stdClass();
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
                    WHERE sha1(userkey)='" . $userkey . "' AND isdeleted=0";
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

    function gethumidity($rawtemp) {
        $temp = round(($rawtemp / 100), 2);
        return $temp;
    }

    function custom_fields($customerno) {
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
            return $custs;
        } else {
            return NULL;
        }
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

    function getName($nid, $customerno) {
        $vehiclemanager = new VehicleManager($customerno);
        $vehicledata = $vehiclemanager->getNameForTemp($nid);
        return $vehicledata;
    }

    function getTempSensors($userkey) {
        $sql = "SELECT u.customerno, u.realname, u.userkey, c.temp_sensors
                    FROM user u
                    INNER JOIN customer c on c.customerno = u.customerno
                    WHERE sha1(userkey)='" . $userkey . "' AND isdeleted=0";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $row = $this->db->fetch_array($record);
        $retarray = array();
        if ($row['userkey'] != "") {
            $retarray['status'] = "successful";
            $retarray['customerno'] = $row["customerno"];
            $retarray['realname'] = $row["realname"];
            $retarray['userkey'] = $row["userkey"];
            $retarray['temp_sensors'] = $row["temp_sensors"];

        } else {
            $retarray['status'] = "unsuccessful";
        }
        return $retarray;
    }


    // </editor-fold>
    //

}
?>
