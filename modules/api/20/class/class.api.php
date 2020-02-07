<?php
require_once("global.config.php");
require_once("database.inc.php");
require_once("reports.api.php");
date_default_timezone_set('Asia/Kolkata');

class VODevices {

}

class object {

}

define("SP_GET_ODOMETER_READING", "get_odometer_reading");
define("SP_GET_VEHICLEWAREHOUSE_DETAILS", "get_vehiclewarehouse_details");
define("SP_GET_VEHICLEWAREHOUSE_DETAILS_VIEWER", "get_vehiclewarehouse_details_viewer");
define("SP_GET_VEHICLES_DRIVERS_USERS", "get_vehicles_drivers_users");
define("SP_MAP_VEHICLE_DRIVER_USER", "map_vehicle_user_driver");
define("SP_CHECK_VEHICLE_USER_MAPPING", "check_vehicle_user_mapping");
define("SP_AUTHENTICATE_FOR_LOGIN", "authenticate_for_login");
define("SP_SPEED_FORGOT_PASSWORD", "speed_forgot_password");
define("SP_UPDATE_NEWFORGOTPASSWORD", "update_newforgotpassword");
define("SP_INSERT_SMSLOG", "insert_smslog");

class api {

    const PER_SMS_CHARACTERS = 160;

    static $SMS_TEMPLATE_FOR_VEHICLE_USER_DRIVER_MAPPING = "Dear {{USERNAME}}, {{VEHICLENO}} has been allotted for your pickup. Driver Name: {{DRIVERNAME}} ({{DRIVERPHONE}})";
    //static $SMS_TEMPLATE_FOR_QUICK_SHARE = "{{USERNAME}} wants you to track the trip at {{URL}}";
    static $SMS_TEMPLATE_FOR_QUICK_SHARE = "Vehicle No: {{VEHICLENO}}\r\nLocation: {{LOCATION}}\r\nShared by: {{USERNAME}}";
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
                . "," . '@userkeyparam';

        $queryCallSP = "CALL " . SP_AUTHENTICATE_FOR_LOGIN . "($sp_params)";
        $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
        $this->db->ClosePDOConn($pdo);
        $outputParamsQuery = "SELECT @usertype AS usertype, @userkeyparam AS userkeyparam";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        $usertype = $outputResult['usertype'];
        $userkeyparam = $outputResult['userkeyparam'];
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
                if ($initday > 0) {
                    $retarray['status'] = "successful";
                    $retarray['userkey'] = $arrResult['userkey'];
                    $this->update_push_android_chk($arrResult['userkey'], 1);
                    $this->update_push_android_chk_main($arrResult['userkey'], 1);
                    $retarray['customerno'] = $arrResult['customerno'];
                    $retarray['username'] = $arrResult['username'];
                    $retarray['customername'] = $arrResult['customercompany'];
                    $retarray['version'] = $arrResult['version'];
                    $retarray['role'] = $arrResult['role'];

                    $today = date("Y-m-d H:i:s");
                    $sql = "UPDATE user SET lastlogin_android='" . $today . "' where userkey = '" . $arrResult['userkey'] . "' AND customerno= '" . $arrResult['customerno'] . "' LIMIT 1";
                    $this->db->query($sql, __FILE__, __LINE__);
                } else {
                    $retarray['status'] = "expired";
                    $retarray['version'] = '';
                    $retarray['customername'] = null;
                    $retarray['userkey'] = 0;
                }
            } else if ($usertype == 1 && $userkeyparam != 0) {
                $retarray['status'] = "forgot_password_success";
                $retarray['version'] = '';
                $retarray['customername'] = null;
                $retarray['userkey'] = $userkeyparam;
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

    function device_list($userkey, $pageIndex, $pageSize, $searchstring, $groupidparam) {
        $isWareHouse = 0;
        $totalVehicleCount = 0;
        $grouplistString = 0;   // For Grp Based Vehicles - 30 Nov
        $grpIDS = array(); // For Grp Based Vehicles - 30 Nov
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
                $userId = $validation["userid"];
                $userRoleId = $validation["roleid"];
                $isUserMappedToVehicle = 0;
                $mappedgroupid = -1;
                if ($userRoleId == 39) {
                    $isUserMappedToVehicle = $this->check_vehicle_user_mapping($userId, $mappedgroupid);
                    $groupidparam = $isUserMappedToVehicle ? $mappedgroupid : -1;
                }
                if ($userRoleId != 39 || ($isUserMappedToVehicle && $userRoleId == 39)) {
                    $arr_p['groups'] = $this->pull_groups($userkey);
                    // For Grp Based Vehicles - 30 Nov
                    if ($validation['role'] == 'Viewer') {
                        foreach ($arr_p['groups'] as $grp) {
                            $grpIDS[] = $grp->groupid;
                        }
                        $groupidparam = implode(',', $grpIDS);
                    }
                }

                /*
                  $groupidsql = "SELECT * ,groupman.isdeleted as gmdel,groupman.groupid as gmgrpid, (SELECT groupman.isdeleted FROM groupman WHERE groupman.userid = user.userid ORDER BY groupman.gmid DESC LIMIT 0 , 1) AS grpdel FROM user LEFT OUTER JOIN groupman ON groupman.userid = user.userid
                  WHERE user.userkey =$userkey ORDER BY groupman.gmid DESC";
                  $recordgrp = $this->db->query($groupidsql, __FILE__, __LINE__);
                  $groupids = array();
                  while ($rowgrp = $this->db->fetch_array($recordgrp)) {
                  if ($rowgrp['gmdel'] == 0) {
                  $groupid = $rowgrp['gmgrpid'];
                  $groupids[] = $groupid;
                  }
                  }

                  $firstgroup = array_values($groupids);
                  $firstgroup = array_shift($firstgroup);
                 */
                //Prepare parameters
                $sp_params = "'" . $pageIndex . "'"
                        . ",'" . $pageSize . "'"
                        . "," . $customerno . ""
                        . ",'" . $userkey . "'"
                        . "," . $isWareHouse . ""
                        . ",'" . $searchstring . "'"
                        . ",'" . $groupidparam . "'"
                ;
                if ($validation['role'] != 'Viewer') {
                     $queryCallSP = "CALL " . SP_GET_VEHICLEWAREHOUSE_DETAILS . "($sp_params)";
                } else {
                     $queryCallSP = "CALL " . SP_GET_VEHICLEWAREHOUSE_DETAILS_VIEWER . "($sp_params)"; // For Grp Based Vehicles - 30 Nov
                }
                $records = $this->db->query($queryCallSP, __FILE__, __LINE__);
                $json_p = array();
                $x = 0;
                while ($row = $this->db->fetch_array($records)) {
                    if ($totalVehicleCount == 0) {
                        $totalVehicleCount = $row['recordCount'];
                    }
                    //if ($firstgroup == '' || (in_array($row['veh_grpid'], $groupids))) {
                    if ($userRoleId != 39 || ($isUserMappedToVehicle && $userRoleId == 39)) {
                        $json_p[$x]['vehicleid'] = $row['vehicleid'];
                        $json_p[$x]['vehicleno'] = $row['vehicleno'];
                        $json_p[$x]['groupid'] = $row['groupid'];
                        $kind = 0;
                        if ($row['kind'] == "Car") {
                            $kind = 1;
                        } else if ($row['kind'] == "Truck") {
                            $kind = 2;
                        } else if ($row['kind'] == "Bus") {
                            $kind = 3;
                        }
                        $json_p[$x]['kind'] = $kind;
                        $json_p[$x]['location'] = $this->location($row['devicelat'], $row['devicelong'], $row['customer_no'], $row['use_geolocation']);
                        $json_p[$x]['lastupdated'] = $this->getduration1($row['lastupdated']);
                        $json_p[$x]['driverphone'] = $row['driverphone'];
                        $json_p[$x]['simcardno'] = $row['simcardno'];
                        $json_p[$x]['sequenceno'] = $row['sequenceno'];
                        $json_p[$x]['isFrozen'] = isset($row['is_freeze']) ? $row['is_freeze'] : '0';
                        //status start
                        $status = "";
                        $ServerIST_less1 = new DateTime();
                        $ServerIST_less1->modify('-60 minutes');
                        $lastupdated = new DateTime($row['lastupdated']);
                        $temp1_conflicted = 0;
                        $temp2_conflicted = 0;
                        $temp3_conflicted = 0;
                        $temp4_conflicted = 0;
                        $speed_conflicted = 0;
                        if ($lastupdated < $ServerIST_less1) {
                            $status = "1";  //inactive grey
                        } else {

                            //<editor-fold defaultstate="collapsed" desc="Check for temperature conflict">
                            if (isset($row['temp_sensors'])) {
                                $status = "1";
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
                                            //Set default temp conflict as 0
                                            $json_p[$x]['temp4_conflicted'] = $temp4_conflicted;
                                            $s = "analog" . $row['tempsen4'];
                                            $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                            if ($row['tempsen4'] != 0 && $analogValue != 0) {
                                                $temp4 = $this->gettemplist($analogValue, $row['use_humidity']);
                                            } else {
                                                $temp4 = '';
                                            }
                                            $temp4_min = (isset($row['temp4_min'])) ? $row['temp4_min'] : '';
                                            $temp4_max = (isset($row['temp4_max'])) ? $row['temp4_max'] : '';
                                        }
                                    case 3:
                                        if (isset($row['tempsen3'])) {
                                            //Set default temp conflict as 0
                                            $json_p[$x]['temp3_conflicted'] = $temp3_conflicted;
                                            $s = "analog" . $row['tempsen3'];
                                            $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                            if ($row['tempsen3'] != 0 && $analogValue != 0) {
                                                $temp3 = $this->gettemplist($analogValue, $row['use_humidity']);
                                            } else {
                                                $temp3 = '';
                                            }
                                            $temp3_min = (isset($row['temp3_min'])) ? $row['temp3_min'] : '';
                                            $temp3_max = (isset($row['temp3_max'])) ? $row['temp3_max'] : '';
                                        }
                                    case 2:
                                        if (isset($row['tempsen2'])) {
                                            //Set default temp conflict as 0
                                            $json_p[$x]['temp2_conflicted'] = $temp2_conflicted;
                                            $s = "analog" . $row['tempsen2'];
                                            $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                            if ($row['tempsen2'] != 0 && $analogValue != 0) {
                                                $temp2 = $this->gettemplist($analogValue, $row['use_humidity']);
                                            } else {
                                                $temp2 = '';
                                            }
                                            $temp2_min = (isset($row['temp2_min'])) ? $row['temp2_min'] : '';
                                            $temp2_max = (isset($row['temp2_max'])) ? $row['temp2_max'] : '';
                                        }
                                    case 1:
                                        if (isset($row['tempsen1'])) {
                                            $json_p[$x]['temp1_conflicted'] = $temp1_conflicted;
                                            $s = "analog" . $row['tempsen1'];
                                            $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                            if ($row['tempsen1'] != 0 && $analogValue != 0) {
                                                $temp1 = $this->gettemplist($analogValue, $row['use_humidity']);
                                            } else {
                                                $temp1 = '';
                                            }
                                            $temp1_min = (isset($row['temp1_min'])) ? $row['temp1_min'] : '';
                                            $temp1_max = (isset($row['temp1_max'])) ? $row['temp1_max'] : '';
                                        }
                                        break;

                                    default:
                                        if (isset($row['tempsen1'])) {
                                            $json_p[$x]['temp1_conflicted'] = $temp1_conflicted;
                                            $s = "analog" . $row['tempsen1'];
                                            $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                            if ($row['tempsen1'] != 0 && $analogValue != 0) {
                                                $temp1 = $this->gettemplist($analogValue, $row['use_humidity']);
                                            } else {
                                                $temp1 = '';
                                            }
                                            $temp1_min = (isset($row['temp1_min'])) ? $row['temp1_min'] : '';
                                            $temp1_max = (isset($row['temp1_max'])) ? $row['temp1_max'] : '';
                                        }
                                        break;
                                }
                                if ($temp1 != '') {
                                    if (!empty($temp1_min) && !empty($temp1_max) && !($temp1_min == 0 && $temp1_max == 0)) {
                                        if ($temp1 < $temp1_min || $temp1 > $temp1_max) {
                                            // temperature 1 conflict
                                            $temp1_conflicted = 1;
                                        }
                                    }
                                    $json_p[$x]['temp1_conflicted'] = $temp1_conflicted;
                                }
                                if ($temp2 != '') {
                                    if (!empty($temp2_min) && !empty($temp2_max) && !($temp2_min == 0 && $temp2_max == 0)) {
                                        if ($temp2 < $temp2_min || $temp2 > $temp2_max) {
                                            // temperature 2 conflict
                                            $temp2_conflicted = 1;
                                        }
                                    }
                                    $json_p[$x]['temp2_conflicted'] = $temp2_conflicted;
                                }
                                if ($temp3 != '') {
                                    if (!empty($temp3_min) && !empty($temp3_max) && !($temp3_min == 0 && $temp3_max == 0)) {
                                        if ($temp3 < $temp3_min || $temp3 > $temp3_max) {
                                            // temperature 3 conflict
                                            $temp3_conflicted = 1;
                                        }
                                        $json_p[$x]['temp3_conflicted'] = $temp3_conflicted;
                                    }
                                }
                                if ($temp4 != '') {
                                    if (!empty($temp4_min) && !empty($temp4_max) && !($temp4_min == 0 && $temp4_max == 0)) {
                                        if ($temp4 < $temp4_min || $temp4 > $temp4_max) {
                                            // temperature 4 conflict
                                            $temp4_conflicted = 1;
                                        }
                                        $json_p[$x]['temp4_conflicted'] = $temp4_conflicted;
                                    }
                                }
                            }
                            //</editor-fold>
                            //
                            //<editor-fold defaultstate="collapsed" desc="Decide Vehicle colour">
                            if ($row['ignition'] == '0') {
                                $status = "2";  //orange yellow
                            } else {
                                $currentSpeed = isset($row['curspeed']) ? $row['curspeed'] : '';
                                $overspeedLimit = isset($row['overspeed_limit']) ? $row['overspeed_limit'] : '';
                                if ($currentSpeed != '' && $overspeedLimit != '' && ($currentSpeed > $overspeedLimit)) {
                                    $status = "4";  //red overspeed
                                    $speed_conflicted = 1;
                                } else {
                                    $stoppage_flag = isset($row['stoppage_flag']) ? $row['stoppage_flag'] : '';
                                    if ($stoppage_flag != '' && $stoppage_flag == '0') {
                                        $status = "5"; //blue idle ignition
                                    } else {
                                        $status = "3"; //green  run
                                    }
                                }
                            }
                            $json_p[$x]['speed_conflicted'] = $speed_conflicted;
                            //</editor-fold>
                        }
                        $json_p[$x]['vehicle_color'] = $status;
                        $json_p[$x]['devlat'] = $row['devicelat'];
                        $json_p[$x]['devlong'] = $row['devicelong'];
                        $x++;
                    }
                }
                // Free result set
                $records->close();
                $this->db->next_result();
                $arr_p['status'] = "successful";
                $arr_p['result'] = $json_p;
                $arr_p['totalVehicleCount'] = $totalVehicleCount;
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

    function device_list_wh($userkey, $pageIndex, $pageSize, $searchstring, $groupidparam) {
        $isWareHouse = 1;
        $totalWareHouseCount = 0;
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
                $arr_p['groups'] = $this->pull_groups($userkey);
                /*
                  $groupidsql = "SELECT * ,groupman.isdeleted as gmdel,groupman.groupid as gmgrpid, (SELECT groupman.isdeleted FROM groupman WHERE groupman.userid = user.userid ORDER BY groupman.gmid DESC LIMIT 0 , 1) AS grpdel FROM user LEFT OUTER JOIN groupman ON groupman.userid = user.userid
                  WHERE user.userkey =$userkey ORDER BY groupman.gmid DESC";
                  $recordgrp = $this->db->query($groupidsql, __FILE__, __LINE__);
                  $groupids = array();
                  while ($rowgrp = $this->db->fetch_array($recordgrp)) {
                  if ($rowgrp['gmdel'] == 0) {
                  $groupid = $rowgrp['gmgrpid'];
                  $groupids[] = $groupid;
                  }
                  }

                  $firstgroup = array_values($groupids);
                  $firstgroup = array_shift($firstgroup);
                 */
                //Prepare parameters
                $sp_params = "'" . $pageIndex . "'"
                        . ",'" . $pageSize . "'"
                        . "," . $customerno . ""
                        . ",'" . $userkey . "'"
                        . "," . $isWareHouse . ""
                        . ",'" . $searchstring . "'"
                        . ",'" . $groupidparam . "'"
                ;
                $queryCallSP = "CALL " . SP_GET_VEHICLEWAREHOUSE_DETAILS . "($sp_params)";

                $records = $this->db->query($queryCallSP, __FILE__, __LINE__);

                $json_p = array();
                $x = 0;
                while ($row = $this->db->fetch_array($records)) {
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
                    //status start
                    $status = "";
                    $ServerIST_less1 = new DateTime();
                    $ServerIST_less1->modify('-60 minutes');
                    $lastupdated = new DateTime($row['lastupdated']);

                    if ($lastupdated < $ServerIST_less1) {
                        $status = "1";  //inactive grey
                    } else {
                        if (isset($row['temp_sensors'])) {
                            $status = "1";
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
                                        if ($row['tempsen4'] != 0 && $s != 0) {
                                            $temp4 = $this->gettemplist($s, $row['use_humidity']);
                                        } else {
                                            $temp4 = '';
                                        }
                                        $temp4_min = (isset($row['temp4_min'])) ? $row['temp4_min'] : '';
                                        $temp4_max = (isset($row['$temp4_max'])) ? $row['$temp4_max'] : '';
                                    }
                                case 3:
                                    if (isset($row['tempsen3'])) {
                                        $s = "analog" . $row['tempsen3'];
                                        if ($row['tempsen3'] != 0 && $s != 0) {
                                            $temp3 = $this->gettemplist($s, $row['use_humidity']);
                                        } else {
                                            $temp3 = '';
                                        }
                                        $temp3_min = (isset($row['temp3_min'])) ? $row['temp3_min'] : '';
                                        $temp3_max = (isset($row['$temp3_max'])) ? $row['$temp3_max'] : '';
                                    }
                                case 2:
                                    if (isset($row['tempsen2'])) {
                                        $s = "analog" . $row['tempsen2'];
                                        if ($row['tempsen2'] != 0 && $s != 0) {
                                            $temp2 = $this->gettemplist($s, $row['use_humidity']);
                                        } else {
                                            $temp2 = '';
                                        }
                                        $temp2_min = (isset($row['temp2_min'])) ? $row['temp2_min'] : '';
                                        $temp2_max = (isset($row['$temp2_max'])) ? $row['$temp2_max'] : '';
                                    }
                                case 1:
                                    if (isset($row['tempsen1'])) {
                                        $s = "analog" . $row['tempsen1'];
                                        if ($row['tempsen1'] != 0 && $s != 0) {
                                            $temp1 = $this->gettemplist($s, $row['use_humidity']);
                                        } else {
                                            $temp1 = '';
                                        }
                                        $temp1_min = (isset($row['temp1_min'])) ? $row['temp1_min'] : '';
                                        $temp1_max = (isset($row['$temp1_max'])) ? $row['$temp1_max'] : '';
                                    }
                                    break;

                                default:
                                    if (isset($row['tempsen1'])) {
                                        $s = "analog" . $row['tempsen1'];
                                        if ($row['tempsen1'] != 0 && $s != 0) {
                                            $temp1 = $this->gettemplist($s, $row['use_humidity']);
                                        } else {
                                            $temp1 = '';
                                        }
                                        $temp1_min = (isset($row['temp1_min'])) ? $row['temp1_min'] : '';
                                        $temp1_max = (isset($row['$temp1_max'])) ? $row['$temp1_max'] : '';
                                    }
                                    break;
                            }
                            if ($temp1 != '' && $temp1_min != '' && $temp1_max != '') {
                                if ($temp1 < $temp1_min || $temp1 > $temp1_max) {
                                    $status = "2"; // temperature 1 conflict
                                }
                            }
                            if ($temp2 != '' && $temp2_min != '' && $temp2_max != '') {
                                if ($temp2 < $temp2_min || $temp2 > $temp2_max) {
                                    $status = "2"; // temperature 2 conflict
                                }
                            }
                            if ($temp3 != '' && $temp3_min != '' && $temp3_max != '') {
                                if ($temp3 < $temp3_min || $temp3 > $temp3_max) {
                                    $status = "2"; // temperature 3 conflict
                                }
                            }
                            if ($temp4 != '' && $temp4_min != '' && $temp4_max != '') {
                                if ($temp4 < $temp4_min || $temp4 > $temp4_max) {
                                    $status = "2"; // temperature 4 conflict
                                }
                            }
                        }
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

    function device_list_details($userkey, $vehicleid) {
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";

        $todaysdate = date('Y-m-d H:i:s');
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
                $sql = "SELECT unit.humidity, vehicle.temp1_min,unit.is_buzzer,unit.digitalioupdated
                    , unit.extra_digitalioupdated,unit.is_freeze,unit.mobiliser_flag,unit.is_mobiliser
                    , customer.use_buzzer,customer.use_freeze,customer.use_immobiliser, vehicle.temp1_max
                    , vehicle.temp2_min, vehicle.temp2_max, vehicle.kind, vehicle.groupid
                    , unit.tempsen1, unit.tempsen2, unit.tempsen3, unit.tempsen4
                    , unit.analog1, unit.analog2, unit.analog3, unit.analog4, customer.temp_sensors
                    , vehicle.stoppage_transit_time, vehicle.stoppage_flag, customer.use_geolocation
                    , user.customerno as customer_no,vehicle.vehicleid, vehicle.overspeed_limit, vehicle.extbatt
                    , vehicle.vehicleno,vehicle.curspeed,unit.unitno, unit.digitalio, unit.acsensor, unit.is_ac_opp
                    , driver.drivername,driver.driverphone, devices.lastupdated, devices.ignition, devices.powercut
                    , devices.gsmstrength, devices.devicelat, devices.devicelong,ignitionalert.status as igstatus
                    , ignitionalert.ignchgtime, vehicle.odometer as vehicleodometer
                    , COALESCE(tripdetails.statusdate, tripdetail_history.statusdate) as loadingtime
                    , COALESCE(tripdetails.odometer, tripdetail_history.odometer) as loadingodometer
                FROM vehicle INNER JOIN devices ON devices.uid = vehicle.uid
                INNER JOIN driver ON driver.driverid = vehicle.driverid
                INNER JOIN unit ON devices.uid = unit.uid
                INNER JOIN customer ON customer.customerno = vehicle.customerno
                INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                INNER JOIN user ON vehicle.customerno = user.customerno
                LEFT OUTER JOIN tripdetails ON vehicle.vehicleid = tripdetails.vehicleid AND tripdetails.tripstatusid = 3 AND tripdetails.is_tripend = 0
                LEFT OUTER JOIN tripdetail_history ON vehicle.vehicleid = tripdetail_history.vehicleid AND tripdetail_history.tripstatusid = 3 AND tripdetail_history.is_tripend = 0
                WHERE vehicle.customerno =$customerno "
                        . "AND user.userkey =$userkey
                AND unit.trans_statusid NOT IN (10,22)
                AND vehicle.isdeleted=0
                AND driver.isdeleted=0
                and devices.lastupdated <> '0000-00-00 00:00:00'
                AND vehicle.vehicleid = $vehicleid "
                        . "ORDER BY devices.lastupdated DESC";
                $record = $this->db->query($sql, __FILE__, __LINE__);
                $json_p = array();
                $x = 0;
                while ($row = $this->db->fetch_array($record)) {
                    $json_p[$x]['lastupdated'] = $this->getduration1($row['lastupdated']);
                    $json_p[$x]['checkpoints'] = $this->get_checkpoints($row['vehicleid']);
                    $json_p[$x]['vehicleid'] = $row['vehicleid'];
                    $json_p[$x]['unitno'] = $row['unitno'];
                    $json_p[$x]['drivername'] = $row['drivername'];
                    $diff = $this->getduration(date('Y-m-d H:i:s'), $row["stoppage_transit_time"]);

                    if ($row["stoppage_flag"] == '1') {
                        $json_p[$x]['ignstatus'] = "Running since $diff";
                    } else if ($row["stoppage_flag"] == '0') {
                        $json_p[$x]['ignstatus'] = "Idle since $diff";
                    }
                    $json_p[$x]['speed'] = $row['curspeed'];
                    $json_p[$x]['distance'] = (string) $this->distance($row['customer_no'], $row['unitno']);


                    if ($row['acsensor'] == 1) {
                        if ($row['digitalioupdated'] != '0000-00-00 00:00:00') {
                            $digitaldiff = $this->getduration_digitalio($row['digitalioupdated'], $row['lastupdated']);
                        }

                        if ($row['digitalio'] == 0) {
                            if ($row["is_ac_opp"] == 0) {
                                if ($row['digitalioupdated'] != '0000-00-00 00:00:00') {
                                    $json_p[$x]['digital_status'] = "ON Since" . $digitaldiff;
                                } else {
                                    $json_p[$x]['digital_status'] = "ON";
                                }
                            } else {
                                if ($row['digitalioupdated'] != '0000-00-00 00:00:00') {
                                    $json_p[$x]['digital_status'] = "OFF Since" . $digitaldiff;
                                } else {
                                    $json_p[$x]['digital_status'] = "OFF";
                                }
                            }
                        } else {
                            if ($row["is_ac_opp"] == 0) {

                                if ($row['digitalioupdated'] != '0000-00-00 00:00:00') {
                                    $json_p[$x]['digital_status'] = "OFF Since" . $digitaldiff;
                                } else {
                                    $json_p[$x]['digital_status'] = "OFF";
                                }
                            } else {
                                if ($row['digitalioupdated'] != '0000-00-00 00:00:00') {
                                    $json_p[$x]['digital_status'] = "ON Since" . $digitaldiff;
                                } else {
                                    $json_p[$x]['digital_status'] = "ON";
                                }
                            }
                        }
                    } else {
                        $json_p[$x]['digital_status'] = "Not Active";
                    }
                    $json_p[$x]['cust_digital'] = $this->getCustomizeName($customerno, 1, 'Digital');
                    $json_p[$x]['extbatt'] = round($row['extbatt'] / 100, 2);
                    $json_p[$x]['powercut'] = $row['powercut'];
                    $json_p[$x]['simsignal'] = round($row['gsmstrength'] / 31 * 100);
                    $json_p[$x]['overspeed_limit'] = $row['overspeed_limit'];
                    // Temperature Sensor
                    $json_p[$x]['analog_sensors'] = $row['temp_sensors'];
                    $json_p[$x]['analog1'] = 'Not Active';
                    $json_p[$x]['analog2'] = 'Not Active';
                    $json_p[$x]['analog3'] = 'Not Active';
                    $json_p[$x]['analog4'] = 'Not Active';

                    $json_p[$x]['cust_analog1'] = 'Not Active';
                    $json_p[$x]['cust_analog2'] = 'Not Active';
                    $json_p[$x]['cust_analog3'] = 'Not Active';
                    $json_p[$x]['cust_analog4'] = 'Not Active';

                    //$json_p[$x]['cust_analog3'] = $row['cust_analog3'];
                    //$json_p[$x]['cust_analog4'] = $row['cust_analog4'];

                    if ($row['temp_sensors'] == '1') {
                        if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0')
                            if ($row['humidity'] != '0') {
                                $json_p[$x]['analog1'] = $this->gethumidity($row['analog' . $row['tempsen1']]);
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            } else {
                                $json_p[$x]['analog1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            } else {
                            $json_p[$x]['analog1'] = 'Error';
                        }
                        $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                    }

                    if ($row['temp_sensors'] == '2') {
                        if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0')
                            if ($row['humidity'] != '0') {
                                $json_p[$x]['analog1'] = $this->gethumidity($row['analog' . $row['tempsen1']]);
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            } else {
                                $json_p[$x]['analog1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            } else {
                            $json_p[$x]['analog1'] = 'Error';
                        }
                        $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');

                        if ($row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0') {
                            $json_p[$x]['analog2'] = $this->gettemp($row['analog' . $row['tempsen2']]);
                            $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                        } else {
                            $json_p[$x]['analog2'] = 'Error';
                            $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                        }
                    }

                    if ($row['humidity'] != '0') {
                        $json_p[$x]['analog2'] = $this->gethumidity($row['analog' . $row['humidity']]);
                        $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                    }


                    if (isset($row['temp_sensors']) && $row['temp_sensors'] == 3) {
                        if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0') {
                            $json_p[$x]['analog1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                            $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                        } else {
                            $json_p[$x]['analog1'] = 'Error';
                            $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                        }
                        if ($row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0') {
                            $json_p[$x]['analog2'] = $this->gettemp($row['analog' . $row['tempsen2']]);
                            $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                        } else {
                            $json_p[$x]['analog2'] = 'Error';
                            $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                        }
                        if ($row['tempsen3'] != '0' && $row['analog' . $row['tempsen3']] != '0') {
                            $json_p[$x]['analog3'] = $this->gettemp($row['analog' . $row['tempsen3']]);
                            $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 22, 'Analog3');
                        } else {
                            $json_p[$x]['analog3'] = 'Error';
                            $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 22, 'Analog3');
                        }
                    }

                    if (isset($row['temp_sensors']) && $row['temp_sensors'] == 4) {
                        if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0') {
                            $json_p[$x]['analog1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                            $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                        } else {
                            $json_p[$x]['analog1'] = 'Error';
                            $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                        }

                        if ($row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0') {
                            $json_p[$x]['analog2'] = $this->gettemp($row['analog' . $row['tempsen2']]);
                            $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                        } else {
                            $json_p[$x]['analog2'] = 'Error';
                            $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                        }

                        if ($row['tempsen3'] != '0' && $row['analog' . $row['tempsen3']] != '0') {
                            $json_p[$x]['analog3'] = $this->gettemp($row['analog' . $row['tempsen3']]);
                            $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 2, 'Analog3');
                        } else {
                            $json_p[$x]['analog3'] = 'Error';
                            $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 22, 'Analog3');
                        }

                        if ($row['tempsen4'] != '0' && $row['analog' . $row['tempsen4']] != '0') {
                            $json_p[$x]['analog4'] = $this->gettemp($row['analog' . $row['tempsen4']]);
                            $json_p[$x]['cust_analog4'] = $this->getCustomizeName($customerno, 23, 'Analog4');
                        } else {
                            $json_p[$x]['analog4'] = 'Error';
                            $json_p[$x]['cust_analog4'] = $this->getCustomizeName($customerno, 23, 'Analog4');
                        }
                    }

                    ///use buzzer  or use mobilizer or freeze  - start
                    $use_buzzer = $row['use_buzzer'];
                    $immobiliser = $row['use_immobiliser'];


                    if ($use_buzzer == 1 && $row['is_buzzer'] == 1) {
                        $buzzer_status = 1;   //unit has buzzer
                    } else if ($use_buzzer == 1 && $row['is_buzzer'] == 0) {
                        $buzzer_status = 0;  //unit has NO buzzer
                    } else {
                        $buzzer_status = -1;  //no buzzer
                    }

                    if ($row['use_immobiliser'] == 1 && $row['is_mobiliser'] == 0) {
                        //gray  green - disable
                        //Immobilizer Not Installed In Your Vehicle. * Note: For further information please contact an elixir.
                        $mobiliser_status = -1;
                    } elseif ($row['use_immobiliser'] == 1 && $row['is_mobiliser'] == 1 && $row['mobiliser_flag'] == 0) {
                        //green - on  //   Would You Wish To Start The Vehicle ?
                        $mobiliser_status = 0;
                    } else if ($row['use_immobiliser'] == 1 && $row['is_mobiliser'] == 1 && $row['mobiliser_flag'] == 1) {
                        //red - stop
                        $mobiliser_status = 1;  //stop
                    } else {
                        $mobiliser_status = -1;  //no mobiliser
                    }

                    if ($row['use_freeze'] == 1 && $row['is_freeze'] == 1) {
                        $freeze_status = 1;    //isfreezed
                    } else if ($row['use_freeze'] == 1 && $row['is_freeze'] == 0) {
                        $freeze_status = 0;  //isunfreezed
                    } else {
                        $freeze_status = -1;  //freeze not enabled for customer
                    }


                    $json_p[$x]['buzzerstatus'] = $buzzer_status;
                    $json_p[$x]['mobilizerstatus'] = $mobiliser_status;
                    $json_p[$x]['freezestatus'] = $freeze_status;

                    ///use buzzer  or use mobilizer  - end
                    $tripdata = $this->gettripdetails($row['vehicleid'], $customerno);
                    $json_p[$x]['temprange'] = 'Not Defined';
                    $json_p[$x]['triplogno'] = 'Not Defined';
                    $json_p[$x]['status'] = 'Not Defined';
                    $json_p[$x]['buddist'] = 'Not Defined';
                    $json_p[$x]['budhours'] = 'Not Defined';
                    $json_p[$x]['actualhrs'] = 'Not Defined';
                    $json_p[$x]['actualkms'] = 'Not Defined';
                    $json_p[$x]['consignor'] = 'Not Defined';
                    $json_p[$x]['consignee'] = 'Not Defined';
                    $json_p[$x]['billingparty'] = 'Not Defined';
                    $json_p[$x]['tripdrivername'] = 'Not Defined';
                    $json_p[$x]['tripdriverno'] = 'Not Defined';
                    $json_p[$x]['routename'] = 'Not Defined';
                    $json_p[$x]['remark'] = 'Not Defined';
                    $json_p[$x]['loadingtime'] = 'Not Defined';
                    if (isset($tripdata)) {
                        if (isset($tripdata['is_tripend']) && $tripdata['is_tripend'] == '0') {
                            $json_p[$x]['temprange'] = $tripdata['temprange'];
                            $json_p[$x]['triplogno'] = $tripdata['triplogno'];
                            $json_p[$x]['status'] = $tripdata['status'];
                            $actualhrs = round((strtotime($todaysdate) - strtotime($row['loadingtime'])) / (60 * 60));
                            $actualkms = round(($row['vehicleodometer'] - $row['loadingodometer']) / 1000, 2);
                            $json_p[$x]['buddist'] = $tripdata['budgetedkms'] . $actualkms;
                            $json_p[$x]['budhours'] = $tripdata['budgetedhrs'] . $actualhrs;
                            //$json_p[$x]['actualhrs'] = $tripdata['actualhrs'];
                            //$json_p[$x]['actualkms'] = (round($tripdata['vehicleodometer'] - $tripdata['odometer'] / 100, 2));
                            //$json_p[$x]['actualkms'] = $tripdata['actualkms'];
                            $json_p[$x]['consignor'] = $tripdata['consignor'];
                            $json_p[$x]['consignee'] = $tripdata['consignee'];
                            $json_p[$x]['billingparty'] = $tripdata['billingparty'];
                            $json_p[$x]['tripdrivername'] = $tripdata['drivername'];
                            $json_p[$x]['tripdriverno'] = $tripdata['drivermobile2'] . ',' . $tripdata['drivermobile1'];
                            $json_p[$x]['routename'] = $tripdata['routename'];
                            $json_p[$x]['remark'] = $tripdata['remark'];
                            $json_p[$x]['loadingtime'] = ($row['loadingtime'] != null && $row['loadingtime'] != '') ? date("d-m-Y, h:i a", strtotime($row['loadingtime'])) : 'Not Defined';
                        }
                    }
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

    function device_list_details_wh($userkey, $vehicleid) {

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
                $sql = "SELECT unit.humidity, vehicle.temp1_min,unit.is_buzzer,unit.digitalioupdated
                    ,unit.extra_digitalioupdated,unit.is_freeze,unit.mobiliser_flag,unit.is_mobiliser
                    ,customer.use_buzzer,customer.use_freeze,customer.use_immobiliser
                    , vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max, vehicle.kind
                    , vehicle.groupid, unit.tempsen1, unit.tempsen2, unit.tempsen3, unit.tempsen4
                    , unit.analog1, unit.analog2, unit.analog3, unit.analog4, customer.temp_sensors
                    , vehicle.stoppage_transit_time, vehicle.stoppage_flag, customer.use_geolocation
                    , user.customerno as customer_no,vehicle.vehicleid, vehicle.overspeed_limit
                    , vehicle.extbatt, vehicle.vehicleno,vehicle.curspeed,unit.unitno, unit.digitalio
                    , unit.acsensor, unit.is_ac_opp, driver.drivername,driver.driverphone, devices.lastupdated
                    , devices.ignition, devices.powercut, devices.gsmstrength, devices.devicelat, devices.devicelong
                    , ignitionalert.status as igstatus,ignitionalert.ignchgtime
                    , COALESCE((SELECT name FROM nomens where nid = unit.n1), '') AS n1
                    , COALESCE((SELECT name FROM nomens where nid = unit.n2), '') AS n2
                    , COALESCE((SELECT name FROM nomens where nid = unit.n3), '') AS n3
                    , COALESCE((SELECT name FROM nomens where nid = unit.n4), '') AS n4
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
                    $json_p[$x]['analog1'] = 'Not Active';
                    $json_p[$x]['analog2'] = 'Not Active';
                    $json_p[$x]['analog3'] = 'Not Active';
                    $json_p[$x]['analog4'] = 'Not Active';

                    $json_p[$x]['cust_analog1'] = 'Not Active';
                    $json_p[$x]['cust_analog2'] = 'Not Active';
                    $json_p[$x]['cust_analog3'] = 'Not Active';
                    $json_p[$x]['cust_analog4'] = 'Not Active';
                    if (isset($row['temp_sensors']) && isset($row['humidity'])) {
                        if ($row['temp_sensors'] == '1') {
                            if (isset($row['tempsen1']) && $row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0')
                                if ($row['humidity'] != '0') {
                                    $json_p[$x]['analog1'] = $this->gethumidity($row['analog' . $row['tempsen1']]);
                                    $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                                } else {
                                    $json_p[$x]['analog1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                                    $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                                } else {
                                $json_p[$x]['analog1'] = 'Error';
                            }
                            $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                        }

                        if ($row['temp_sensors'] == '2') {
                            if (isset($row['tempsen1']) && $row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0')
                                if ($row['humidity'] != '0') {
                                    $json_p[$x]['analog1'] = $this->gethumidity($row['analog' . $row['tempsen1']]);
                                    $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                                } else {
                                    $json_p[$x]['analog1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                                    $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                                } else
                                $json_p[$x]['analog1'] = 'Error';
                            $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');

                            if (isset($row['tempsen2']) && $row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0') {
                                $json_p[$x]['analog2'] = $this->gettemp($row['analog' . $row['tempsen2']]);
                                $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                            } else {
                                $json_p[$x]['analog2'] = 'Error';
                                $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                            }
                        }

                        if ($row['humidity'] != '0') {
                            $json_p[$x]['analog2'] = $this->gethumidity($row['analog' . $row['humidity']]);
                            $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                        }

                        if ($row['temp_sensors'] == '3') {
                            if (isset($row['tempsen1']) && $row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0') {
                                $json_p[$x]['analog1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            } else {
                                $json_p[$x]['analog1'] = 'Error';
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            }
                            if (isset($row['tempsen2']) && $row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0') {
                                $json_p[$x]['analog2'] = $this->gettemp($row['analog' . $row['tempsen2']]);
                                $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                            } else {
                                $json_p[$x]['analog2'] = 'Error';
                                $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                            }
                            if (isset($row['tempsen3']) && $row['tempsen3'] != '0' && $row['analog' . $row['tempsen3']] != '0') {
                                $json_p[$x]['analog3'] = $this->gettemp($row['analog' . $row['tempsen3']]);
                                $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 22, 'Analog3');
                            } else {
                                $json_p[$x]['analog3'] = 'Error';
                                $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 22, 'Analog3');
                            }
                        }

                        if ($row['temp_sensors'] == '4') {
                            if (isset($row['tempsen1']) && $row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0') {
                                $json_p[$x]['analog1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            } else {
                                $json_p[$x]['analog1'] = 'Error';
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            }

                            if (isset($row['tempsen2']) && $row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0') {
                                $json_p[$x]['analog2'] = $this->gettemp($row['analog' . $row['tempsen2']]);
                                $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                            } else {
                                $json_p[$x]['analog2'] = 'Error';
                                $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                            }

                            if (isset($row['tempsen3']) && $row['tempsen3'] != '0' && $row['analog' . $row['tempsen3']] != '0') {
                                $json_p[$x]['analog3'] = $this->gettemp($row['analog' . $row['tempsen3']]);
                                $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 2, 'Analog3');
                            } else {
                                $json_p[$x]['analog3'] = 'Error';
                                $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 22, 'Analog3');
                            }

                            if (isset($row['tempsen4']) && $row['tempsen4'] != '0' && $row['analog' . $row['tempsen4']] != '0') {
                                $json_p[$x]['analog4'] = $this->gettemp($row['analog' . $row['tempsen4']]);
                                $json_p[$x]['cust_analog4'] = $this->getCustomizeName($customerno, 23, 'Analog4');
                            } else {
                                $json_p[$x]['analog4'] = 'Error';
                                $json_p[$x]['cust_analog4'] = $this->getCustomizeName($customerno, 23, 'Analog4');
                            }
                        }
                    }
                    /* If there are specific nomenclatures we need to send it overriding custom names */
                    if (isset($row['n1'])) {
                        $json_p[$x]['cust_analog1'] = $row['n1'];
                    }
                    if (isset($row['n2'])) {
                        $json_p[$x]['cust_analog2'] = $row['n2'];
                    }
                    if (isset($row['n3'])) {
                        $json_p[$x]['cust_analog3'] = $row['n3'];
                    }
                    if (isset($row['n4'])) {
                        $json_p[$x]['cust_analog4'] = $row['n4'];
                    }
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
        //buzzer status =1 on // buzzer status==0 off  //buzzerstatus ==-1
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        if ($validation['status'] == "successful") {
            $arr_p['status'] = 'successful';
            $customerno = $validation["customerno"];
            $userid = $validation["userid"];
            if ($status == 1) {  //Do You Like To Alarm The Vehicle ?
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
                    $arr_p['message'] = 'Start Vehicle';  //start
                    $command = 'STARTV';
                    $check = 1;
                } else if ($status == '1') {
                    $command = 'STOPV';
                    $arr_p['message'] = 'Stop Vehicle';   // stop vehicle
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

                if ($fstatus == '0') {  //freeze vehicle
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

    function device_list_byname($userkey, $vehicleno) {

        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";

        if ($validation['status'] == "successful") {
            $arr_p['checkpointsman_count'] = $this->get_checkpoints_count($userkey);
            // successful
            $customerno = $validation["customerno"];
            $sql = "SELECT vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max, vehicle.kind, vehicle.groupid, unit.tempsen1, unit.tempsen2, unit.analog1, unit.analog2, unit.analog3, unit.analog4,
                                                            customer.temp_sensors, vehicle.stoppage_transit_time, vehicle.stoppage_flag, customer.use_geolocation, user.customerno as customer_no,vehicle.groupid as veh_grpid,vehicle.vehicleid, vehicle.overspeed_limit, vehicle.extbatt, vehicle.vehicleno,vehicle.curspeed,unit.unitno, unit.digitalio, unit.acsensor, unit.is_ac_opp, driver.drivername,driver.driverphone,
                                                    devices.lastupdated, devices.ignition, devices.inbatt, devices.tamper, devices.powercut, devices.gsmstrength, devices.devicelat, devices.devicelong,
                                                            (SELECT customname FROM `customfield` where customerno=user.customerno AND name='Digital' AND `usecustom`=1) as digital,
                                                            ignitionalert.status as igstatus,ignitionalert.ignchgtime FROM vehicle
                                                            INNER JOIN devices ON devices.uid = vehicle.uid
                                                            INNER JOIN driver ON driver.driverid = vehicle.driverid
                                                            INNER JOIN unit ON devices.uid = unit.uid
                                                            INNER JOIN customer ON customer.customerno = vehicle.customerno
                                                            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                                                            INNER JOIN user ON vehicle.customerno = user.customerno
                                                            WHERE vehicle.customerno =$customerno
                                                            AND user.userkey =$userkey
                                                            AND unit.trans_statusid NOT IN (10,22)
                                                            AND vehicle.isdeleted=0 and driver.isdeleted=0 and devices.lastupdated <> '0000-00-00 00:00:00' AND vehicle.vehicleno = '$vehicleno' ORDER BY devices.lastupdated DESC";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            $json_p = array();
            $x = 0;
            while ($row = $this->db->fetch_array($record)) {
                $json_p[$x]['vehicleid'] = $row['vehicleid'];
                if ($arr_p['checkpointsman_count'] == 1) {
                    $json_p[$x]['checkpoints'] = $this->get_checkpoints($row['vehicleid']);
                }
                $json_p[$x]['vehicleno'] = $row['vehicleno'];
                $json_p[$x]['kind'] = $row['kind'];
                $json_p[$x]['groupid'] = $row['groupid'];
                $json_p[$x]['location'] = $this->location($row['devicelat'], $row['devicelong'], $row['customer_no'], $row['use_geolocation']);
                $json_p[$x]['unitno'] = $row['unitno'];
                $json_p[$x]['lastupdated'] = $row['lastupdated'];
                $json_p[$x]['drivername'] = $row['drivername'];
                $json_p[$x]['driverphone'] = $row['driverphone'];
                $json_p[$x]['ignition'] = $row['ignition'];
                $json_p[$x]['curspeed'] = $row['curspeed'];
                $json_p[$x]['distance'] = $this->distance($row['customer_no'], $row['unitno']);
                if ($row['acsensor'] == 1) {
                    if ($row['digitalio'] == 0) {
                        if ($row["is_ac_opp"] == 0) {
                            $json_p[$x]['acstatus'] = "1";
                        } else {
                            $json_p[$x]['acstatus'] = "0";
                        }
                    } else
                    if ($row["is_ac_opp"] == 0) {
                        $json_p[$x]['acstatus'] = "0";
                    } else {
                        $json_p[$x]['acstatus'] = "1";
                    }
                } else {
                    $json_p[$x]['acstatus'] = "-1";
                }
                $diff = $this->getduration(date('Y-m-d H:i:s'), $row["stoppage_transit_time"]);
                if ($row["stoppage_flag"] == '1') {
                    $json_p[$x]['ignstatus'] = "Running since $diff";
                } else if ($row["stoppage_flag"] == '0') {
                    $json_p[$x]['ignstatus'] = "Idle since $diff";
                }
                $json_p[$x]['extbatt'] = round($row['extbatt'] / 100, 2);
                $json_p[$x]['inbatt'] = $row['inbatt'] / 1000;
                $json_p[$x]['tamper'] = $row['tamper'];
                $json_p[$x]['powercut'] = $row['powercut'];
                $json_p[$x]['gsmstrength'] = round($row['gsmstrength'] / 31 * 100);
                $json_p[$x]['devicelat'] = $row['devicelat'];
                $json_p[$x]['devicelong'] = $row['devicelong'];
                $json_p[$x]['digital'] = $row['digital'];
                $json_p[$x]['stoppage_flag'] = $row['stoppage_flag'];
                $json_p[$x]['overspeed_limit'] = $row['overspeed_limit'];
                $json_p[$x]['temp1_min'] = $row['temp1_min'];
                $json_p[$x]['temp1_max'] = $row['temp1_max'];
                $json_p[$x]['temp2_min'] = $row['temp2_min'];
                $json_p[$x]['temp2_max'] = $row['temp2_max'];

                // Temperature Sensor
                $json_p[$x]['temp_sensors'] = $row['temp_sensors'];
                $json_p[$x]['temp1'] = 'Not Active';
                $json_p[$x]['temp2'] = 'Not Active';
                if ($row['temp_sensors'] == '1') {
                    if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0')
                        $json_p[$x]['temp1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                    else
                        $json_p[$x]['temp1'] = '0';
                }

                if ($row['temp_sensors'] == '2') {
                    if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0')
                        $json_p[$x]['temp1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                    else
                        $json_p[$x]['temp1'] = '0';

                    if ($row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0')
                        $json_p[$x]['temp2'] = $this->gettemp($row['analog' . $row['tempsen2']]);
                    else
                        $json_p[$x]['temp2'] = '0';
                }

                $x++;
            }

            $arr_p['status'] = "successful";
            $arr_p['result'] = $json_p;
            $this->update_push_android_chk($userkey, 0);
        }else {
            $arr_p['status'] = "unsuccessful";
        }

        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function client_code_details($ecodeid) {
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";

        $Query = "SELECT vehicle.overspeed_limit, vehicle.stoppage_flag, vehicle.vehicleno,vehicle.vehicleid,devices.deviceid,
            devices.devicelat,devices.devicelong,driver.drivername,driver.driverphone,vehicle.curspeed,
            devices.lastupdated,vehicle.kind,devices.ignition,devices.status,devices.directionchange,
            devices.uid,elixiacode.expirydate FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid
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
                        } else if ($row['kind'] == "Truck") {
                            $kind = 2;
                        } else if ($row['kind'] == "Bus") {
                            $kind = 3;
                        }
                        $json_p[$x]['kind'] = $kind;
                        $json_p[$x]['ignition'] = $row['ignition'];
                        $json_p[$x]['devicestatus'] = $row['status'];
                        $json_p[$x]['directionchange'] = $row['directionchange'];
                        $json_p[$x]['overspeed_limit'] = $row['overspeed_limit'];
                        $json_p[$x]['stoppage_flag'] = $row['stoppage_flag'];
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

    function create_client_code($userkey, $code) {
        //print_r($code);

        $code = json_decode($code);
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        $today = date('Y-m-d H:i:s');
        $code->menuoption = 0;
        if ($validation['status'] == "successful") {
            $ecoderandom = mt_rand(0, 999999);
            $customerno = $validation["customerno"];
            $userid = $validation['userid'];

            //1567123285
            //1335747238
            $code->reports = str_replace(', ', ',', $code->reports);
            $reports = explode(",", $code->reports);

            if (in_array('Route History', $reports)) {
                $code->menuoption = $code->menuoption + 1;
            }
            if (in_array('Travel History', $reports)) {
                $code->menuoption = $code->menuoption + 2;
            }
            if (in_array('Alert History', $reports)) {
                $code->menuoption = $code->menuoption + 4;
            }
            if (in_array('Fuel Refill History', $reports)) {
                $code->menuoption = $code->menuoption + 8;
            }
            if (in_array('Location History', $reports)) {
                $code->menuoption = $code->menuoption + 16;
            }
            if (in_array('Stoppage History', $reports)) {
                $code->menuoption = $code->menuoption + 32;
            }
            if (in_array('Overspeed History', $reports)) {
                $code->menuoption = $code->menuoption + 64;
            }
            if (in_array('Genset History', $reports)) {
                $code->menuoption = $code->menuoption + 128;
            }
            if (in_array('Trip Report', $reports)) {
                $code->menuoption = $code->menuoption + 256;
                $code->menuoption = $code->menuoption + 1048576;
            }
            if (in_array('Checkpoint Report', $reports)) {
                $code->menuoption = $code->menuoption + 1024;
            }
            if (in_array('Fuel Comsumption Report', $reports)) {
                $code->menuoption = $code->menuoption + 2048;
                $code->menuoption = $code->menuoption + 524288;
            }
            if (in_array('Route Report', $reports)) {
                $code->menuoption = $code->menuoption + 4096;
            }
            if (in_array('Distance Report', $reports)) {
                $code->menuoption = $code->menuoption + 8192;
            }
            if (in_array('Idle-Time Report', $reports)) {
                $code->menuoption = $code->menuoption + 16384;
            }
            if (in_array('Genset Report', $reports)) {
                $code->menuoption = $code->menuoption + 32768;
            }
            if (in_array('Overspeed Report', $reports)) {
                $code->menuoption = $code->menuoption + 65536;
            }
            if (in_array('Fence Cnflict Report', $reports)) {
                $code->menuoption = $code->menuoption + 131072;
            }
            if (in_array('Location Report', $reports)) {
                $code->menuoption = $code->menuoption + 262144;
            }
            if (in_array('Temperature Report', $reports)) {
                $code->menuoption = $code->menuoption + 2097152;
                $code->menuoption = $code->menuoption + 4194304;
            }

            //echo $code->menuoption;
            $vids = Array();

            $code->vehicles = str_replace(', ', ',', $code->vehicles);
            $vehicles = explode(",", $code->vehicles);
            foreach ($vehicles as $vehicle) {
                $SQL = "select * from vehicle where customerno=$customerno and REPLACE(vehicleno, ' ', '') = '$vehicle'";
                $rec = $this->db->query($SQL, __FILE__, __LINE__);
                while ($row = $this->db->fetch_array($rec)) {
                    $vids[] = $row['vehicleid'];
                }
            }

            if (!empty($vids)) {
                $sqlInsert = "INSERT INTO elixiacode (`customerno`,`startdate`,`expirydate`,`ecode`,`datecreated`,isdeleted, userid, ecodeemail, ecodesms, menuoption) VALUES ( $customerno,'" . $code->startdate . "','" . $code->enddate . "','$ecoderandom','" . $today . "',0,$userid,'" . $code->email . "','" . $code->sms . "',$code->menuoption)";
                $record = $this->db->query($sqlInsert, __FILE__, __LINE__);
                $lastid = $this->db->last_insert_id();
                if (isset($record)) {


                    foreach ($vids as $vid) {
                        $Query = "INSERT INTO ecodeman (`customerno`,`vehicleid`,`ecodeid`,isdeleted, userid) VALUES ( $customerno,$vid,$lastid,0,$userid)";
                        $this->db->query($Query, __FILE__, __LINE__);
                    }
                    $arr_p['status'] = "Successful";
                    $arr_p['clientcode'] = $ecoderandom;

                    if ($code->email != '') {
                        $subject = "Client Code";
                        $content = 'Your Client Code is ' . $ecoderandom . '. <br/>Generated by ' . $validation['realname'] . '. <br/>Please visit www.speed.elixiatech.com and trace your vehicle. <br/> Valid Until: ' . date("F j, Y, g:i a", strtotime($code->enddate));
                        $headers = "From: noreply@elixiatech.com\r\n";
                        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                        @mail($code->email, $subject, $content, $headers);
                    }

                    if ($code->sms != '') {
                        $smsmessage = 'Client Code: ' . $ecoderandom . '. Generated by ' . $validation['realname'] . '. Validity: ' . date("F j, Y, g:i a", strtotime($code->enddate)) . '. Link: www.speed.elixiatech.com';
                        $url = "http://pacems.asia:8080/bulksms/bulksms?username=xzt-elixia&password=elixia&type=0&dlr=1&destination=91" . urlencode($code->sms) . "&source=ELIXIA&message=" . urlencode($smsmessage);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
                        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                        $result = curl_exec($ch);
                        curl_close($ch);
                    }
                } else {
                    $arr_p['status'] = "unsuccessful";
                }
            } else {
                $arr_p['status'] = "unsuccessful";
                $arr_p['error'] = "please check the vehicle number";
            }
            //print_r($vids);
        } else {
            $arr_p['status'] = "unsuccessful";
        }

        echo json_encode($arr_p);
        return json_encode($arr_p);
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

    function summary($userkey, $vehicleid, $date) {
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        //$arr_p['status'] = "unsuccessful";
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            $userid = $validation['userid'];
            $vehicle = $this->get_vehicle($vehicleid, $customerno);
            $sql = "select * from unit inner join customer on unit.customerno = customer.customerno where vehicleid='" . $vehicleid . "' AND unit.customerno= '" . $customerno . "'";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            $row = $this->db->fetch_array($record);
            $rowlist = $row;
            if ($date == date('d-m-Y')) {
                $date = date('Y-m-d', strtotime($date));
                $location = "../../../customer/" . $customerno . "/unitno/" . $row['unitno'] . "/sqlite/" . $date . ".sqlite";
                if (file_exists($location)) {
                    $path = "sqlite:$location";
                    $Data = $this->DataFromSqlite($path);
                    if ($Data != 0) {
                        if (count($Data) > 0) {
                            $acdata = $this->getacinvertval($row['unitno'], $customerno);
                            $acinvertval = $acdata['0'];
                            $acsensor = $acdata['1'];

                            $vm = $this->getspeedlimit($vehicleid);
                            $vehicle = $this->get_all_vehicles($customerno, $vehicleid);
                            $vehicle->customerno = $customerno;
                            $rowa = $this->DailyReport($vehicle, $date, $Data, $vm->overspeed_limit, $acinvertval, $acsensor);

                            $hours = floor($rowa['runningtime'] / 60);
                            $minutes = $rowa['runningtime'] % 60;
                            if ($minutes < 10) {
                                $minutes = '0' . $minutes;
                            }
                            $hourss = floor($rowa['gensetusage'] / 60);
                            $minutess = $rowa['gensetusage'] % 60;
                            if ($minutess < 10) {
                                $minutess = '0' . $minutess;
                            }

                            $idlehourss = floor($rowa['idletime'] / 60);
                            $idelminutess = $rowa['idletime'] % 60;
                            if ($idelminutess < 10) {
                                $idelminutess = '0' . $idelminutess;
                            }

                            $Datacap = new VODevices();
                            //$Datacap->date = strtotime($date);
                            $Datacap->VehicleName = $vehicle->vehicleno;
                            $Datacap->DeviceId = $vehicle->deviceid;
                            $Datacap->DriverName = $vehicle->drivername;
                            $Datacap->Group = $this->getgroupname_byuid($rowa['uid']);
                            if (isset($rowa['lat']) && isset($rowa['long'])) {
                                $Datacap->SL = $this->location($rowa['lat'], $rowa['long'], $customerno, $row['use_geolocation']);
                            } else {
                                $Datacap->SL = "";
                            }
                            if (isset($rowa['lat']) && isset($rowa['long'])) {
                                $Datacap->EL = $this->location($rowa['lat'], $rowa['long'], $customerno, $row['use_geolocation']);
                            }
                            $Datacap->DT = round($rowa['totaldistance'] / 1000, 2) . " km";
                            $Datacap->AS = round($rowa['averagespeed'] / 1000, 2) . " km/hr";
                            $Datacap->RT = $hours . ":" . $minutes . " (hh:mm)";

                            if ((isset($rowa['genset']) ? $rowa['genset'] : 0) > 0) {
                                $Datacap->digital_cust = $this->getCustomizeName($customerno, 1, 'Digital');
                                $Datacap->digital = $hourss . ":" . $minutess . " (hh:mm)";
                            }
                            $Datacap->OS = $rowa['overspeed'];
                            $Datacap->TS = isset($rowa['topspeed']) ? $rowa['topspeed'] : "0" . " km/hr";
                            if (isset($rowa['topspeed_lat']) && isset($rowa['topspeed_long'])) {
                                $Datacap->TSL = $this->location($rowa['topspeed_lat'], $rowa['topspeed_long'], $customerno, $row['use_geolocation']);
                            } else {
                                $Datacap->TSL = "";
                            }
                            $Datacap->HB = isset($rowa['harsh_break']) ? $rowa['harsh_break'] : '';
                            $Datacap->SA = isset($rowa['sudden_acc']) ? $rowa['sudden_acc'] : '';
                            if ((isset($rowa['towing']) ? $rowa['towing'] : 0) == 0) {
                                $Datacap->TO = "No";
                            } else {
                                $Datacap->TO = "Yes";
                            }
                            $arr_p['status'] = "successful";
                            $arr_p['report'] = $Datacap;
                        }
                    } else {
                        $Bad = 0;
                    }
                } else {
                    //echo "File Not exists";
                    $arr_p['status'] = "unsuccessful";
                    $arr_p['error'] = "File Not exists";
                }
            } else {
                $date = date('d-m-Y', strtotime($date));
                $startdate = "27-02-2015";
                $startdate1 = "09-03-2015";
                $startdate1 = strtotime($startdate1);
                $startdate = strtotime($startdate);
                $curdate = strtotime($date);
                $date = date('d-m-Y', strtotime($date));
                if ($startdate1 == $curdate) {
                    $location = "../../../customer/" . $customerno . "/reports/dailyreport_new.sqlite";
                } else {
                    $location = "../../../customer/" . $customerno . "/reports/dailyreport.sqlite";
                }

                if (file_exists($location)) {
                    $path = "sqlite:$location";
                    $db = new PDO($path);
                    $REPORT = array();
                    $sqlday = date("dmy", strtotime($date));
                    $query = "SELECT * from A$sqlday WHERE vehicleid = $vehicleid";
                    $result = $db->query($query);
                    if (isset($result) && $result != "") {
                        foreach ($result as $row) {

                            $hours = floor($row['runningtime'] / 60);
                            $minutes = $row['runningtime'] % 60;
                            if ($minutes < 10) {
                                $minutes = '0' . $minutes;
                            }
                            $hourss = floor($row['genset'] / 60);
                            $minutess = $row['genset'] % 60;
                            if ($minutess < 10) {
                                $minutess = '0' . $minutess;
                            }

                            $idlehourss = floor($row['idletime'] / 60);
                            $idelminutess = $row['idletime'] % 60;
                            if ($idelminutess < 10) {
                                $idelminutess = '0' . $idelminutess;
                            }

                            $Datacap = new VODevices();
                            $Datacap->VehicleName = $vehicle->vehicleno;
                            $Datacap->DeviceId = $vehicle->deviceid;
                            $Datacap->DriverName = $vehicle->drivername;
                            $Datacap->Group = $this->getgroupname_byuid($row['uid']);
                            $Datacap->SL = $this->location($row['first_dev_lat'], $row['first_dev_long'], $customerno, $rowlist['use_geolocation']);
                            $Datacap->EL = $this->location($row['dev_lat'], $row['dev_long'], $customerno, $rowlist['use_geolocation']);
                            $Datacap->DT = round($row['totaldistance'] / 1000, 2) . " km";
                            $Datacap->AS = round($row['avgspeed'] / 1000, 2) . " km/hr";
                            $Datacap->RT = $hours . ":" . $minutes . " (hh:mm)";
                            if ($row['genset'] > 0) {
                                $Datacap->digital_cust = $this->getCustomizeName($customerno, 1, 'Digital');
                                $Datacap->digital = $hourss . ":" . $minutess . " (hh:mm)";
                            }
                            $Datacap->OS = $row['overspeed'];
                            $Datacap->TS = $row['topspeed'] . " km/hr";
                            $Datacap->TSL = $this->location($row['topspeed_lat'], $row['topspeed_long'], $customerno, $rowlist['use_geolocation']);
                            $Datacap->HB = $row['harsh_break'];
                            $Datacap->SA = $row['sudden_acc'];
                            if ($row['towing'] == 0) {
                                $Datacap->TO = "No";
                            } else {
                                $Datacap->TO = "Yes";
                            }
                            //$Datacap->FC = $row['fenceconflict'];
                            //$Datacap->idletime = $idlehourss . ":" . $idelminutess;
                            //$Datacap->avgdistance = $row['average_distance'];
                            $arr_p['status'] = "successful";
                            $arr_p['report'] = $Datacap;
                        }
                    }
                } else {
                    $arr_p['status'] = "unsuccessful";
                    $arr_p['error'] = "File Not exists";
                }
            }
        } else {
            $arr_p['status'] = "unsuccessful";
        }

        if (empty($arr_p)) {
            $arr_p['status'] = "unsuccessful";
            $arr_p['Error'] = "Data Not Available";
        }
        return json_encode($arr_p);
    }

    function summary_wh($userkey, $vehicleid, $date) {
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            $userid = $validation['userid'];

            $vehicle = $this->get_vehicle($vehicleid, $customerno);

            $sql = "select * from unit inner join customer on unit.customerno = customer.customerno where vehicleid='" . $vehicleid . "' AND unit.customerno= '" . $customerno . "'";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            $row = $this->db->fetch_array($record);
            $useHumidity = isset($row['use_humidity']) ? $row['use_humidity'] : 0;
            $temp_sensors = isset($row['temp_sensors']) ? $row['temp_sensors'] : 0;
            //$rowlist = $row;
            $date = date('Y-m-d', strtotime($date));
            $interval = 60;
            $stime = date('Y-m-d 00:00:00', strtotime($date));
            $etime = date('Y-m-d 23:59:59', strtotime($date));
            $location = "../../../customer/" . $customerno . "/unitno/" . $row['unitno'] . "/sqlite/" . $date . ".sqlite";
            if (file_exists($location)) {
                $path = "sqlite:$location";
                if ($useHumidity == 1) {
                    $Data = $this->DataForHumidity($path, $vehicle->deviceid, $stime, $etime, $interval);
                } else {
                    $Data = $this->DataForTemprature($path, $vehicle->deviceid, $stime, $etime, $interval, $temp_sensors);
                    //populate temperature report
                }
                if (isset($Data) && count($Data) > 0) {
                    $vehicle->customerno = $customerno;
                    $Datacap = new VODevices();
                    $Datacap->WarehouseName = $vehicle->vehicleno;
                    $Datacap->DeviceId = $vehicle->deviceid;
                    $Datacap->useHumidity = $useHumidity;
                    $Datacap->StartTime = date('d-m-Y h:i A', strtotime($stime));
                    $Datacap->EndTime = date('d-m-Y h:i A', strtotime($etime));
                    $Datacap->Interval = $interval . " min";

                    $report = array();
                    foreach ($Data as $datarow) {
                        $reportarr['Time'] = date('h:i A', strtotime($datarow->starttime));
                        $reportarr['Humidity'] = $datarow->humidity . " %";
                        $reportarr['Temperature'] = $datarow->temperature . " &degC";
                        $report[] = $reportarr;
                    }
                    $Datacap->whsummary = $report;
                    $arr_p['status'] = "successful";
                    $arr_p['report'] = $Datacap;
                } else {
                    $arr_p['status'] = "unsuccessful";
                    $arr_p['error'] = "No data found";
                }
            } else {
                //echo "File Not exists";
                $arr_p['status'] = "unsuccessful";
                $arr_p['error'] = "File Not exists";
            }
        } else {
            $arr_p['status'] = "unsuccessful";
        }

        if (empty($arr_p)) {
            $arr_p['status'] = "unsuccessful";
            $arr_p['Error'] = "Data Not Available";
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

            $SQL2 = "SELECT sum(pending_amt) as pending_amount FROM invoice WHERE customerno = $customerno";
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

    function dashboard($userkey) {
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $info = array();
        $devicelist = array();
        //$arr_p['status'] = "unsuccessful";

        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            $Query = "SELECT * FROM `dailyreport` inner join unit on unit.uid=dailyreport.uid where dailyreport.customerno=$customerno AND dailyreport.vehicleid <> 0 AND dailyreport.uid <> 0 ";
            $record = $this->db->query($Query, __FILE__, __LINE__);

            $row_count = $this->db->num_rows($record);

            $total_vehicles = 0;
            $total_distance = 0;
            $total_overspeedincidents = 0;
            $total_fenceconflicts = 0;
            if ($row_count > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $total_vehicles++;
                    $total_overspeedincidents += $row['overspeed'];
                    $total_fenceconflicts += $row['fenceconflict'];
                    $distance = $this->distance($customerno, $row['unitno']);
                    $total_distance += round($distance, 2);
                }


                $query = "SELECT topspeed_lat, topspeed_long, max(dailyreport.topspeed) as maxtopspeed,vehicle.vehicleid,vehicle.vehicleno FROM `dailyreport` left join vehicle on vehicle.vehicleid = dailyreport.vehicleid "
                        . " where dailyreport.customerno=$customerno AND dailyreport.vehicleid <> 0 AND dailyreport.uid <> 0 ORDER BY dailyreport.topspeed DESC LIMIT 1";
                $record = $this->db->query($query, __FILE__, __LINE__);
                while ($row = $this->db->fetch_array($record)) {
                    $topspeed = $row['maxtopspeed'];
                    $vehicleno = $row['vehicleno'];
                    $topspeedlat = $row['topspeed_lat'];
                    $topspeedlong = $row['topspeed_long'];
                }

                $info['Total No Of Vehicles'] = $total_vehicles;
                $info['Total Distance Travelled'] = $total_distance . " km";
                $info['Total Overspeed Incidents'] = $total_overspeedincidents;
                $info['Total Fence Conflicts'] = $total_fenceconflicts;
                $info['Top Speed'] = $vehicleno . "(" . $topspeed . " km/hr)";
                if ($topspeedlat == 0 || $topspeedlong == 0) {
                    $info['Top Speed Location'] = "Data Not Available";
                } else {
                    $info['Top Speed Location'] = $this->location($topspeedlat, $topspeedlong, $customerno, 1);
                }
                $arr_p['status'] = "successful";
                $arr_p['info'] = $info;
            } else {
                $arr_p['status'] = "unsuccessful";
                $arr_p['Error'] = "Data Not Available";
            }
            //$arr_p['status'] = "successful";
            //$arr_p['status'] = "successful";
            //$arr_p['info'] = $info;
            $Query = "SELECT * FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN driver ON vehicle.driverid = driver.driverid
                where devices.customerno=$customerno AND unit.trans_statusid NOT IN (10,22)and vehicle.kind<>'Warehouse' ";
            $Query.=" ORDER BY vehicle.vehicleno";

            $record = $this->db->query($Query, __FILE__, __LINE__);
            $row_count = $this->db->num_rows($record);
            $total_vehicles = $this->db->num_rows($record);
            if ($row_count > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $devicelist[] = $row;
                }
                foreach ($devicelist as $row) {
                    $device = new VODevices();


                    $device->vehicleno = $row['vehicleno'];
                    $device->vehicleid = $row['vehicleid'];
                    $device->type = $row['kind'];
                    $device->deviceid = $row['deviceid'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->drivername = $row['drivername'];
                    $device->driverphone = $row['driverphone'];
                    $device->curspeed = $row['curspeed'];
                    $device->overspeed_limit = $row['overspeed_limit'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->type = $row['kind'];
                    $device->ignition = $row['ignition'];
                    $device->status = $row['status'];
                    $device->tempsen1 = $row['tempsen1'];
                    $device->tempsen2 = $row['tempsen2'];
                    $device->temp1_min = $row['temp1_min'];
                    $device->temp1_max = $row['temp1_max'];
                    $device->temp2_min = $row['temp2_min'];
                    $device->temp2_max = $row['temp2_max'];
                    $device->tempsen1 = $row['tempsen1'];
                    $device->analog1 = $row["analog1"];
                    $device->analog2 = $row["analog2"];
                    $device->analog3 = $row["analog3"];
                    $device->analog4 = $row["analog4"];
                    $device->stoppage_flag = $row['stoppage_flag'];
                    $device->stoppage_transit_time = $row['stoppage_transit_time'];
                    $device->directionchange = $row['directionchange'];
                    $devices[] = $device;
                }
            }

            if (isset($devices)) {
                $i = 0;
                $j = 0;
                $k = 0;
                $l = 0;
                $m = 0;
                foreach ($devices as $device) {
                    $ServerIST_less1 = new DateTime();
                    $ServerIST_less1->modify('-60 minutes');
                    $lastupdated = new DateTime($device->lastupdated);
                    if ($lastupdated < $ServerIST_less1) {
                        $m++;
                    } else {
                        if ($device->ignition == '0') {
                            $l++;
                        } else {
                            if ($device->curspeed > $device->overspeed_limit) {
                                $i++;
                            } elseif ($device->stoppage_flag == '0') {
                                $k++;
                            } else {
                                $j++;
                            }
                        }
                    }
                }
            }
            $response = array();
            $response['Overspeed'] = $i;
            $response['Running'] = $j;
            $response['Idle - Ignition On'] = $k;
            $response['Idle - Ignition Off'] = $l;
            $response['Inactive'] = $m;

            $arr_p['Vehicle Status'] = $response;



            $checkpoints = Array();
            $Query = "SELECT count(*) as count,cname FROM checkpointmanage
		INNER JOIN checkpoint on checkpoint.checkpointid=checkpointmanage.checkpointid
		INNER JOIN vehicle on vehicle.vehicleid=checkpointmanage.vehicleid
		WHERE checkpointmanage.customerno=$customerno AND checkpointmanage.isdeleted=0  and  checkpointmanage.conflictstatus=0 ";


            $Query.=" group by checkpointmanage.checkpointid";



            $record = $this->db->query($Query, __FILE__, __LINE__);
            $row_count = $this->db->num_rows($record);

            if ($row_count > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $checkpoint['cname'] = $row['cname'];
                    $checkpoint['count'] = $row['count'];

                    $checkpoints[] = $checkpoint;
                }
            }
            $chklist = array();
            $inside = 0;

            if (isset($checkpoints)) {
                foreach ($checkpoints as $chkpoint) {

                    $chklist[$chkpoint['cname']] = $chkpoint['count'];
                    $inside+= $chkpoint['count'];
                }
            }
            $outside = $total_vehicles - $inside;
            $chklist['Outside'] = $outside;

            $arr_p['Checkpoint Status'] = $chklist;
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
                    $smscount = $this->getSMSCount($customerno);
                    if ($smscount > 0) {
                        $response = '';
                        $isSMSSent = $this->sendSMS($phone, $message, $response);
                        $moduleid = 1;
                        if ($isSMSSent) {
                            $this->updateSMSCount($smscount, $message, $customerno);
                            $todaysdate = date("Y-m-d H:i:s");
                            $vehicleid = 0;
                            if (is_array($phone)) {
                                foreach ($phone as $phoneno) {
                                    $smsLogId = $this->insertSMSLog($phoneno, $message, $response, $vehicleid, $userid, $customerno, $isSMSSent, $todaysdate, $moduleid);
                                }
                            } else {
                                $smsLogId = $this->insertSMSLog($phone, $message, $response, $vehicleid, $userid, $customerno, $isSMSSent, $todaysdate, $moduleid);
                            }
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

    function get_vehicles_drivers_users($userkey) {
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            //Prepare parameters
            $sp_params = "'" . $customerno . "'";

            $sqlGetVehDriverUserSP = "CALL " . SP_GET_VEHICLES_DRIVERS_USERS . "($sp_params)";
            $arrResult = $this->db->multi_query($sqlGetVehDriverUserSP, __FILE__, __LINE__);
            if (count($arrResult) > 0) {
                $arr_p["VehicleDetails"] = !empty($arrResult[0]) ? $arrResult[0] : array();
                $arr_p["DriverDetails"] = !empty($arrResult[1]) ? $arrResult[1] : array();
                $arr_p["UserDetails"] = !empty($arrResult[2]) ? $arrResult[2] : array();
                $arr_p['status'] = "successful";
            }
        } else {
            $arr_p['status'] = "unsuccessful";
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function map_vehicle_driver_user($userkey, $vehicleid, $userid, $driverid, $drivername) {
        $validation = $this->check_userkey($userkey);
        $todaysdate = date('Y-m-d H:i:s');
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            //Prepare parameters
            $sp_params = "'" . $customerno . "'"
                    . ",'" . $vehicleid . "'"
                    . ",'" . $userid . "'"
                    . ",'" . $driverid . "'"
                    . ",'" . $drivername . "'"
                    . ",'" . $todaysdate . "'";

            $sqlMapVehDriverUserSP = "CALL " . SP_MAP_VEHICLE_DRIVER_USER . "($sp_params)";
            $records = $this->db->query($sqlMapVehDriverUserSP, __FILE__, __LINE__);
            $recordCount = $this->db->num_rows($records);
            if (is_a($records, 'mysqli_result') && $recordCount > 0) {
                $successCount = 0;
                $errorMessage = "";
                $smsLogId = 0;
                while ($row = $this->db->fetch_array($records)) {
                    $username = '';
                    $useridparam = '';
                    $userphone = '';
                    $useremail = '';
                    $vehicleno = '';
                    $drivername = '';
                    $driverphone = '';
                    $useridparam = '';
                    $username = isset($row['username']) ? $row['username'] : '';
                    $useridparam = isset($row['userid']) ? $row['userid'] : '';
                    $userphone = isset($row['userphone']) ? $row['userphone'] : '';
                    $useremail = isset($row['useremail']) ? $row['useremail'] : '';
                    $vehicleno = isset($row['vehicleno']) ? $row['vehicleno'] : '';
                    $drivername = isset($row['drivername']) ? $row['drivername'] : '';
                    $driverphone = isset($row['driverphone']) ? $row['driverphone'] : '';
                    $driverphone = ($driverphone == '8888888888') ? '' : $driverphone;
                    if ($username != '' && $userphone != '' && $vehicleno != '' && $drivername != '') {
                        $smsText = api::$SMS_TEMPLATE_FOR_VEHICLE_USER_DRIVER_MAPPING;
                        $smsText = str_replace("{{USERNAME}}", $username, $smsText);
                        $smsText = str_replace("{{VEHICLENO}}", $vehicleno, $smsText);
                        $smsText = str_replace("{{DRIVERNAME}}", $drivername, $smsText);
                        $smsText = str_replace("{{DRIVERPHONE}}", $driverphone, $smsText);
                        $smscount = $this->getSMSCount($customerno);
                        if ($smscount > 0) {
                            $response = '';
                            $isSMSSent = $this->sendSMS($userphone, $smsText, $response);
                            $moduleid = 3;
                            if ($isSMSSent) {
                                $this->updateSMSCount($smscount, $smsText, $customerno);
                                if (is_array($userphone)) {
                                    foreach ($userphone as $phone) {
                                        $smsLogId = $this->insertSMSLog($phone, $smsText, $response, $vehicleid, $useridparam, $customerno, $isSMSSent, $todaysdate, $moduleid);
                                    }
                                } else {
                                    $smsLogId = $this->insertSMSLog($userphone, $smsText, $response, $vehicleid, $useridparam, $customerno, $isSMSSent, $todaysdate, $moduleid);
                                }
                                $successCount += 1;
                            } else {
                                $successCount -= 1;
                                $errorMessage .= "SMS sending FAILED to $username" . "\r\n";
                            }
                        } else {
                            $successCount -= 1;
                            $errorMessage .= "Unable to send SMS to $username due to insufficient SMS balance." . "\r\n";
                        }
                    } else {
                        $successCount -= 1;
                        $errorMessage .= "Unable to retrieve the required details to send SMS for $username" . "\r\n";
                    }
                }
                if ($recordCount == $successCount) {
                    $arr_p['status'] = "successful";
                    $arr_p['message'] = "SMS sent. "; // . (($smsLogId > 0) ? "SMS logged" : " SMS logging failed.");
                } else {
                    $arr_p['message'] = trim($errorMessage);
                }
            } else {
                $arr_p['message'] = "Unable to retrieve the data";
            }
        } else {
            $arr_p['message'] = "Invalid Userkey";
        }

        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function sendSummaryReport($userkey, $vehicleid, $date, $toaddresses, $comments, $mail_type) {
        $reportsObj = new reports();
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        $tempDate = new DateTime($date);

        $validation = $this->check_userkey($userkey);
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            $arrReportData = json_decode($this->summary($userkey, $vehicleid, $date));
            if (isset($arrReportData->status) && isset($arrReportData->report)) {
                if ($arrReportData->status == "successful") {
                    $Datacap = new VODevices();
                    $Datacap = $arrReportData->report;
                    $serverpath = "../../..";
                    $arrToMailIds = explode(",", $toaddresses);
                    //Remove empty elements of an array
                    $arrToMailIds = array_filter($arrToMailIds);
                    $mail_content = $comments;
                    $file_end = "_SummaryReport_" . $tempDate->format('d M Y');
                    $subject = "Summary Report - " . $tempDate->format('d M Y');
                    $veh_no = isset($Datacap->VehicleName) ? str_replace(' ', '', $Datacap->VehicleName) : '';
                    $file_name = $veh_no . "_" . date("d-m-Y") . $file_end;
                    $full_path = $serverpath . "/customer/" . $customerno . "/";
                    $reportHtmlData = $this->PrepareHtmlData($Datacap, $customerno, $mail_type);

                    if ($mail_type === 'pdf') {
                        $ext = ".pdf";
                        $reportsObj->save_pdf($full_path . $file_name . $ext, $reportHtmlData);
                    } else {
                        $ext = ".xls";
                        $reportsObj->save_xls($full_path, $file_name . $ext, $reportHtmlData);
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
                    $arr_p['message'] = "Unable to fetch data. Please try after sometime.";
                }
            } else {
                $arr_p['message'] = "Error retrieving summary data. Please try after sometime.";
            }
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
                    $veh_no = isset($vehicleno) ? str_replace(' ', '', $vehicleno) : '';
                    $file_name = $veh_no . "_" . date("d-m-Y") . $file_end;
                    $full_path = $serverpath . "/customer/" . $customerno . "/";
                    if ($useHumidity) {
                        $reportHtmlData = $reportsObj->gettemphumidityreport($customerno, $startDate, $endDate, $deviceid, $vehicleno, $reportInterval, $startTime, $endTime, $switchto, $mail_type);
                    } else {
                        $reportHtmlData = $reportsObj->gettempreport($customerno, $startDate, $endDate, $deviceid, $vehicleno, $reportInterval, $startTime, $endTime, $switchto, $mail_type);
                    }
                    if (!empty($reportHtmlData)) {
                        if ($mail_type === 'pdf') {
                            $ext = ".pdf";
                            $reportsObj->save_pdf($full_path . $file_name . $ext, $reportHtmlData);
                        } else {
                            $ext = ".xls";
                            $reportsObj->save_xls($full_path, $file_name . $ext, $reportHtmlData);
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

    function getquicksharetext($userkey, $vehicleid) {
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        $validation = $this->check_userkey($userkey);
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            $username = $validation['realname'];
            $objVehicleDetails = $this->get_vehicle($vehicleid, $customerno);
            if (isset($objVehicleDetails)) {
                $vehicleno = $objVehicleDetails->vehicleno;
                $usegeolocation = 1;
                $strlocation = $this->location($objVehicleDetails->devicelat, $objVehicleDetails->devicelong, $customerno, $usegeolocation);
                /*
                 * TODO:
                 * Logic to generate tiny url for passed vehicle.
                 * Expiry time 1 hr.
                 * $url = "http://speed.elixiatech.com";
                 */

                $quickShareText = api::$SMS_TEMPLATE_FOR_QUICK_SHARE;
                $quickShareText = str_replace("{{USERNAME}}", $username, $quickShareText);
                $quickShareText = str_replace("{{VEHICLENO}}", $vehicleno, $quickShareText);
                $quickShareText = str_replace("{{LOCATION}}", $strlocation, $quickShareText);
                //$quickShareText = str_replace("{{URL}}", $url, $quickShareText);
                $arr_p['status'] = "successful";
                $arr_p['message'] = $quickShareText;
            } else {
                $arr_p['message'] = "Unable to fetch vehicle details.";
            }
        } else {
            $arr_p['message'] = "Invalid user.";
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function sendquicksharesms($userkey, $vehicleid, $quickShareText, $mobilenolist) {
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        $validation = $this->check_userkey($userkey);
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            $userid = $validation['userid'];
            $todaysdate = date("Y-m-d H:i:s");
            $arrPhoneNo = explode(",", $mobilenolist);
            //Remove empty elements of an array
            $arrPhoneNo = array_filter($arrPhoneNo);
            $arrCleanedPhoneNo = array();
            /* Remove all the characters except digits */
            foreach ($arrPhoneNo as $phoneno) {
                $tempphoneno = "";
                $tempphoneno = preg_replace("/[^0-9]/", "", $phoneno);
                $tempphoneno = substr($tempphoneno, -10);
                if (strlen($tempphoneno) === 10) {
                    $arrCleanedPhoneNo[] = $tempphoneno;
                }
            }
            if (count($arrCleanedPhoneNo) >= 1) {
                $smscount = $this->getSMSCount($customerno);
                if ($smscount > 0) {
                    $response = '';
                    $isSMSSent = $this->sendSMS($arrCleanedPhoneNo, $quickShareText, $response);
                    $moduleid = 4;
                    if ($isSMSSent) {
                        $this->updateSMSCount($smscount, $quickShareText, $customerno);
                        if (is_array($arrPhoneNo)) {
                            foreach ($arrPhoneNo as $phone) {
                                $smsLogId = $this->insertSMSLog($phone, $quickShareText, $response, $vehicleid, $userid, $customerno, $isSMSSent, $todaysdate, $moduleid);
                            }
                        } else {
                            $smsLogId = $this->insertSMSLog($arrPhoneNo, $quickShareText, $response, $vehicleid, $userid, $customerno, $isSMSSent, $todaysdate, $moduleid);
                        }
                        $arr_p['status'] = "successful";
                        //$arr_p['message'] = "SMS sent. " . (($smsLogId > 0) ? "SMS logged" : " SMS logging failed.");
                        $arr_p['message'] = "SMS sent";
                    } else {
                        $arr_p['message'] = "Unable to send SMS";
                    }
                } else {
                    $arr_p['message'] = "Insufficient SMS balance.";
                }
            } else {
                $arr_p['message'] = "Invalid mobile number.";
            }
        } else {
            $arr_p['message'] = "Invalid user.";
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
             $group = new VODevices();
             $group->groupid = 0;
             $group->groupname = "All Groups";
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
        }else {
         $arr_p['message'] = "Invalid user.";
         $arr_p['status'] = "unsuccessful";
         echo json_encode($arr_p);
         return $arr_p;
        }
    }

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Helper functions">
    //find location
    function location($lat, $long, $customerno, $usegeolocation) { //OVER_QUERY_LIMIT
        $address = NULL;
        if ($lat != '0' && $long != '0') {
            if ($usegeolocation == 1) {
                $API = "http://www.speed.elixiatech.com/location.php?lat=" . $lat . "&long=" . $long . "";
                $location = json_decode(file_get_contents("$API&sensor=false"));
                if (isset($location) && $location->status != 'OVER_QUERY_LIMIT') {
                    @$address = "Near " . $location->results[0]->formatted_address;
                    if (isset($location->results[0]->formatted_address) && $location->results[0]->formatted_address == "") {
                        $GeoCoder_Obj = new GeoCoder($customerno);
                        $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
                    }
                } else {
                    $reportsObj = new reports();
                    $address = $reportsObj->get_location_bylatlong($lat, $long, $customerno);
                }
            } else {
                $reportsObj = new reports();
                $address = $reportsObj->get_location_bylatlong($lat, $long, $customerno);
            }
        }
        return $address;
    }

    //calculate distance
    function distance($customerno, $unitno) {
        $totaldistance = 0;
        /* realtime-data distance calculation */
        //Prepare parameters
        $sp_params = "'" . $unitno . "'";
        $sp_params = $sp_params . "," . $customerno;
        $queryCallSP = "CALL " . SP_GET_ODOMETER_READING . "($sp_params)";

        $records = $this->db->query($queryCallSP, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($records);

        $arrResult = array();
        if ($row_count > 0) {
            $arrResult = $this->db->fetch_array($records);
        }
        if (!empty($arrResult)) {
            $firstodometer = $arrResult['first_odometer'];
            $lastodometer = $arrResult['last_odometer'];

            if ($lastodometer < $firstodometer) {
                $lastodometer = $arrResult['max_odometer'] + $lastodometer;
            }
            $totaldistance = $lastodometer - $firstodometer;
            if (round($totaldistance) != 0) {
                $totaldistance = round(($totaldistance / 1000), 2);
            }
        }
        // Free result set
        $records->close();
        $this->db->next_result();
        return $totaldistance;
    }

    // difference in time
    function getduration($EndTime, $StartTime) {
//                echo $EndTime.'_'.$StartTime.'<br>';
        $idleduration = strtotime($EndTime) - strtotime($StartTime);
        $years = floor($idleduration / (365 * 60 * 60 * 24));
        $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        if ($years > 0 || $months > 0) {
            $diff = date('Y-m-d', strtotime($StartTime));
        } else if ($days > 0) {
            $diff = $days . ' Days ' . $hours . ' hrs and ' . $minutes . ' mins';
        } else if ($hours > 0) {
            $diff = $hours . ' hrs and ' . $minutes . ' mins';
        } else {
            $diff = $minutes . ' mins';
        }
        return $diff;
    }

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
        $sql = "select * from " . TBL_ADMIN_USER . " where userkey='" . $userkey . "' AND isdeleted=0";
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
        } else {
            $retarray['status'] = "unsuccessful";
        }
        return $retarray;
    }

    function gettemp($rawtemp) {
        $temp = round((($rawtemp - 1150) / 4.45), 2);
        return $temp;
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
                $group = new VODevices();
                $group->groupid = 0;
                $group->groupname = "All Groups";
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

    function get_checkpoints($vehicleid) {
        $pdo = $this->db->CreatePDOConn();
        $Query = "SELECT checkpoint.checkpointid, checkpoint.cname, checkpoint.cgeolat, checkpoint.cgeolong, checkpoint.crad FROM checkpointmanage INNER JOIN checkpoint ON checkpoint.checkpointid = checkpointmanage.checkpointid WHERE checkpointmanage.isdeleted = 0 AND checkpointmanage.vehicleid = $vehicleid";
        $arrResult = $pdo->query($Query)->fetchAll(PDO::FETCH_ASSOC);
        $this->db->ClosePDOConn($pdo);
        $json_p = Array();
        $x = 0;
        foreach ($arrResult as $row) {
            $json_p[$x]['checkpointid'] = $row['checkpointid'];
            $x++;
        }
        return $json_p;
    }

    function get_checkpoint_customer($customerno, $userkey) {
        $Query = "SELECT distinct(checkpoint.checkpointid), checkpoint.cname, checkpoint.cgeolat, checkpoint.cgeolong, checkpoint.crad FROM checkpointmanage INNER JOIN checkpoint ON checkpoint.checkpointid = checkpointmanage.checkpointid WHERE checkpointmanage.isdeleted = 0 AND checkpoint.isdeleted = 0 AND checkpointmanage.customerno = %d";
        $devicesQuery = sprintf($Query, $customerno);
        $record = $this->db->query($devicesQuery, __FILE__, __LINE__);
        $json_p = array();
        $x = 0;
        while ($row = $this->db->fetch_array($record)) {
            $json_p[$x]['cname'] = $row['cname'];
            $json_p[$x]['checkpointid'] = $row['checkpointid'];
            $json_p[$x]['cgeolat'] = $row['cgeolat'];
            $json_p[$x]['cgeolong'] = $row['cgeolong'];
            $json_p[$x]['crad'] = $row['crad'];
            $x++;
        }

        $sql = "UPDATE user SET chkpushandroid = 0 WHERE userkey = '" . $userkey . "'";
        $this->db->query($sql, __FILE__, __LINE__);

        return $json_p;
    }

    function get_checkpoint_customer_count($userkey) {
        $q = "SELECT chkpushandroid FROM user WHERE userkey = %s";
        $dq = sprintf($q, $userkey);
        $record = $this->db->query($dq, __FILE__, __LINE__);
        $p = 0;

        while ($row = $this->db->fetch_array($record)) {
            $p = $row['chkpushandroid'];
        }

        return $p;
    }

    function get_checkpoints_count($userkey) {
        $q = "SELECT chkmanpushandroid FROM user WHERE userkey = %s";
        $dq = sprintf($q, $userkey);
        $record = $this->db->query($dq, __FILE__, __LINE__);
        $p = 0;

        while ($row = $this->db->fetch_array($record)) {
            $p = $row['chkmanpushandroid'];
        }

        return $p;
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
                    "uid" => $row['uid']
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

    function dateDifference($diff) {
        $str;


        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));

        $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        $seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
        if ($days > 0) {
            $str = $years . " years";
        } else {
            if ($hours > 0) {
                $str.= $hours . " hr " . $minutes . " min ";
            } elseif ($minutes > 0) {
                $str.= $minutes . " min ";
            } else {
                $str.= $seconds . " sec ago";
            }
        }
        echo $str;
    }

    function getacinvertval($unitno, $customerno) {
        $sql = "SELECT acsensor,is_ac_opp FROM unit
            WHERE customerno = $customerno AND unitno = $unitno";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        //$row = $this->db->fetch_array($record);
        while ($row = $this->db->fetch_array($record)) {
            $acopp['0'] = $row['is_ac_opp'];
            $acopp['1'] = $row['acsensor'];
            return $acopp;
        }

        return NULL;
    }

    function getspeedlimit($vehicleid) {
        $Query = "SELECT `overspeed_limit` FROM `vehicle` WHERE vehicleid=$vehicleid";
        $record = $this->db->query($Query, __FILE__, __LINE__);

        while ($row = $this->db->fetch_array($record)) {
            $vehicle = new VODevices();
            $vehicle->overspeed_limit = $row['overspeed_limit'];
            return $vehicle;
        }


        return null;
    }

    function getgroupname_byuid($uid) {
        $Query = "SELECT `group`.groupname FROM `vehicle` INNER JOIN `group` ON `group`.groupid = vehicle.groupid
            where vehicle.uid = '$uid'";
        $record = $this->db->query($Query, __FILE__, __LINE__);
        while ($row = $this->db->fetch_array($record)) {
            return $row['groupname'];
        }
        return "NA";
    }

    function get_all_vehicles($customerno, $vehicleid) {
        $vehicles = Array();
        $Query = 'SELECT *, devices.deviceid , driver.drivername'
                . ' FROM vehicle'
                . ' inner join devices on devices.uid = vehicle.uid '
                . ' left join driver on driver.driverid = vehicle.driverid'
                . ' WHERE vehicle.customerno=' . $customerno
                . ' AND vehicle.vehicleid=' . $vehicleid
                . ' AND vehicle.isdeleted=0';

        $record = $this->db->query($Query, __FILE__, __LINE__);
        while ($row = $this->db->fetch_array($record)) {
            $vehicle = new VODevices();
            $vehicle->vehicleid = $row['vehicleid'];
            $vehicle->uid = $row['uid'];
            $vehicle->extbatt = $row['extbatt'];
            $vehicle->odometer = $row['odometer'];
            $vehicle->lastupdated = $row['lastupdated'];
            $vehicle->curspeed = $row['curspeed'];
            $vehicle->driverid = $row['driverid'];
            $vehicle->vehicleno = $row["vehicleno"];
            $vehicle->drivername = $row['drivername'];
            $vehicle->deviceid = $row['deviceid'];
            return $vehicle;
        }
        return null;
    }

    function DailyReport($device, $date, $info, $overspeed_limit, $acinvertval, $acsensor) {
        //Device Info
        $record['vehicleid'] = $device->vehicleid;
        $record['uid'] = $device->uid;
        $record['customerno'] = $device->customerno;

        //Idle & Running Time Calculation
        $dat = '';
        $dat = array();
        foreach ($info as $inf) {
            if ($inf->condition == 1) {
                $dat[] = $inf;
            }
        }
        $enddat = end($info);
        $data = $this->processtraveldata($dat, $enddat);
        $display = $this->displaytraveldata($data);
        //$utilizationtime = utilization($data);
        $record['runningtime'] = $display[0];
        $record['idletime'] = $display[1];

        //Odometer Calculation
        $lastrow = end($info);
        $firstrow = $info[0];

        $lastodometer = $lastrow->odometer;
        $firstodometer = $firstrow->odometer;

        $last_lat = $lastrow->devicelat;
        $last_long = $lastrow->devicelong;
        if ($lastodometer < $firstodometer) {
            $max = $this->GetOdometer_Max($date, $device->unitno);
            $lastodometer = $max + $lastodometer;
        }
        $record['totaldistance'] = $lastodometer - $firstodometer;
        $record['totaldistanceKM'] = $lastodometer / 1000 - $firstodometer / 1000;
        if (isset($record['totaldistanceKM']) && $record['totaldistanceKM'] > 0) {
            if (isset($record['runningtime']) && $record['runningtime'] != 0) {
                $AverageSpeed = (int) ($record['totaldistanceKM'] / ($record['runningtime'] / 60));
            } else {
                $AverageSpeed = 0;
            }
        } else {
            $AverageSpeed = 0;
        }
        $record['averagespeed'] = $AverageSpeed;
        $acdat = '';
        $acdat = array();
        foreach ($info as $inf) {
            if ($inf->status != 'F') {
                $acdat[] = $inf;
            }
        }
        if ($acsensor == '1')
            $record['gensetusage'] = gensetusage($acdat, $acinvertval);
        else
            $record['gensetusage'] = 0;
        //Tampering PowerCut Overspeed FenceConflict
        $times = $this->monitoring($device->vehicleid, $device->customerno, $info, $overspeed_limit);
        $record['overspeed'] = $times[0];
        $record['date'] = $date;
        $record['lat'] = $last_lat;
        $record['long'] = $last_long;
        print_r($record);
        return $record;
    }

    function ret_issetor(&$var) {
        return isset($var) ? true : false;
    }

    function monitoring($vehicleid, $custno, $deviceinfo, $overspeed_limit) {
        //Statuses
        $tamper = 0;
        $powercut = 1;
        $overspeed = 0;

        //Counters
        $tampercount = 0;
        $overspeedcount = 0;
        $powercutcount = 0;

        foreach ($deviceinfo as $device) {
            if ($device->tamper == 1 && $tamper == 0) {
                $tampercount += 1;
                $tamper = 1;
            } else if ($device->tamper == 0 && $tamper == 1) {
                $tamper = 0;
            }
            if ($device->powercut == 0 && $powercut == 0) {
                $powercutcount += 1;
                $powercut = 1;
            } else if ($device->powercut == 1 && $powercut == 1) {
                $powercut = 0;
            }
            if ($device->curspeed > $overspeed_limit && $overspeed == 0) {
                $overspeedcount += 1;
                $overspeed = 1;
            } else if ($device->curspeed <= $overspeed_limit && $overspeed == 1) {
                $overspeed = 0;
            }
        }

        $counters[0] = $overspeedcount;
        $counters[1] = $tampercount;
        $counters[2] = $powercutcount;

        return $counters;
    }

    function GetOdometer_Max($date, $unitno) {
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

    function processtraveldata($devicedata, $lastrow) {
//    print_r($lastrow);
        $count = count($devicedata);
        $devices2 = $devicedata;
        $data = Array();
        $datalen = count($devices2);
        if (isset($devices2) && count($devices2) > 1) {
            foreach ($devices2 as $device) {
                $datacap = new VODevices();
                $laststatus = $device->ignition;
                $datacap->ignition = $device->ignition;

                $ArrayLength = count($data);

                if ($ArrayLength == 0) {
                    //echo $firstidle = '1st starttime'.$device->lastupdated.'_'.$device->id.'<br>';
                    $datacap->starttime = $device->lastupdated;
                    $datacap->startlat = $device->devicelat;
                    $datacap->startlong = $device->devicelong;
                    $datacap->startodo = $device->odometer;
                } else if ($ArrayLength == 1) {
                    //Filling Up First Array --- Array[0]
                    //echo $firstidle = '1st starttime'.$device->lastupdated.'_'.$device->id.'<br>';
                    $data[0]->endlat = $device->devicelat;
                    $data[0]->endlong = $device->devicelong;
                    $data[0]->endtime = $device->lastupdated;
                    $data[0]->endodo = $device->odometer;
                    $data[0]->distancetravelled = $data[0]->endodo / 1000 - $data[0]->startodo / 1000;
                    $data[0]->duration = $this->getduration_dashboard($data[0]->endtime, $data[0]->starttime);

                    $datacap->starttime = $data[0]->endtime;
                    $datacap->startlat = $data[0]->endlat;
                    $datacap->startlong = $data[0]->endlong;
                    $datacap->startodo = $data[0]->endodo;
                    $datacap->endtime = $lastrow->lastupdated;
                    $datacap->endlat = $lastrow->devicelat;
                    $datacap->endlong = $lastrow->devicelong;
                    $datacap->endodo = $lastrow->odometer;
                } else {
                    $last = $ArrayLength - 1;
                    $data[$last]->endtime = $device->lastupdated;
                    $data[$last]->endlat = $device->devicelat;
                    $data[$last]->endlong = $device->devicelong;
                    $data[$last]->endodo = $device->odometer;

                    $data[$last]->duration = $this->getduration_dashboard($data[$last]->endtime, $data[$last]->starttime);

                    $data[$last]->distancetravelled = $data[$last]->endodo / 1000 - $data[$last]->startodo / 1000;


                    $datacap->starttime = $data[$last]->endtime;
                    $datacap->startlat = $data[$last]->endlat;
                    $datacap->startlong = $data[$last]->endlong;
                    $datacap->startodo = $data[$last]->endodo;

                    if ($datalen - 1 == $ArrayLength) {
                        $datacap->endtime = $lastrow->lastupdated;
                        $datacap->endlat = $lastrow->devicelat;
                        $datacap->endlong = $lastrow->devicelong;
                        $datacap->endodo = $lastrow->odometer;
                        $datacap->duration = $this->getduration_dashboard($datacap->endtime, $datacap->starttime);
                        $datacap->distancetravelled = $datacap->endodo / 1000 - $datacap->startodo / 1000;
                        $datacap->ignition = $device->ignition;
                    }
                }
                $data[] = $datacap;
            }
            if ($data != NULL && count($data) > 0) {
                $optdata = $this->optimizereptime($data);
            }
            return $optdata;
        } else if (isset($devices2) && count($devices2) == 1) {
            $datacap = new VODevices();
            $datacap->starttime = $devices2[0]->lastupdated;
            $datacap->startlat = $devices2[0]->devicelat;
            $datacap->startlong = $devices2[0]->devicelong;
            $datacap->startodo = $devices2[0]->odometer;
            $datacap->endtime = $lastrow->lastupdated;
            $datacap->endlat = $lastrow->devicelat;
            $datacap->endlong = $lastrow->devicelong;
            $datacap->endodo = $lastrow->odometer;
            $datacap->duration = $this->getduration_dashboard($datacap->endtime, $datacap->starttime);
            $datacap->distancetravelled = $datacap->endodo / 1000 - $datacap->startodo / 1000;
            $datacap->ignition = $devices2[0]->ignition;
            $data[] = $datacap;

            return $data;
        }
    }

    function displaytraveldata($datarows) {
        $runningtime = 0;
        $idletime = 0;
        if (isset($datarows)) {
            foreach ($datarows as $change) {

                //Removing Date Details From DateTime
                $change->starttime = substr($change->starttime, 11);
                $change->endtime = substr($change->endtime, 11);

                $hour = floor(($change->duration) / 60);
                $minutes = ($change->duration) % 60;
                if ($change->ignition == 1) {
                    $runningtime += $minutes + ($hour * 60);
                } else {
                    $idletime += $minutes + ($hour * 60);
                }
            }
        }

        $utilizationtime[0] = $runningtime;
        $utilizationtime[1] = $idletime;
        return $utilizationtime;
    }

    function getduration_dashboard($EndTime, $StartTime) {
        $diff = strtotime($EndTime) - strtotime($StartTime);
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        return $hours * 60 + $minutes;
    }

    function optimizereptime($data) {
        $datarows = array();
        $ArrayLength = count($data);
        $currentrow = $data[0];
        $a = 0;
        while ($currentrow != $data[$ArrayLength - 1]) {
            $i = 1;
            while (($i + $a) < $ArrayLength - 1 && $data[$i + $a]->duration < 3) {
                $i+=2;
            }
            $currentrow->endtime = $data[$i + $a - 1]->endtime;
            $currentrow->endlat = $data[$i + $a - 1]->endlat;
            $currentrow->endlong = $data[$i + $a - 1]->endlong;
            $currentrow->endodo = $data[$i + $a - 1]->endodo;
            $currentrow->duration = $this->getduration_dashboard($currentrow->endtime, $currentrow->starttime);
            $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;
            $datarows[] = $currentrow;
            if (($a + $i) <= $ArrayLength - 1) {
                $currentrow = $data[$i + $a];
            }
            $a += $i;
            if (($a) >= $ArrayLength - 1)
                $currentrow = $data[$ArrayLength - 1];
        }
        if ($datarows != NULL) {
            $checkop = end($datarows);
            $checkup = end($data);
            if ($checkop->endtime != $checkup->endtime) {
                $currentrow->starttime = $checkop->endtime;
                $currentrow->startlat = $checkop->endlat;
                $currentrow->startlong = $checkop->endlong;
                $currentrow->startodo = $checkop->endodo;

                $currentrow->endtime = $checkup->endtime;
                $currentrow->endlat = $checkup->endlat;
                $currentrow->endlong = $checkup->endlong;
                $currentrow->endodo = $checkup->endodo;
                $currentrow->duration = $this->getduration_dashboard($currentrow->endtime, $currentrow->starttime);
                $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;

                $datarows[] = $currentrow;
            }
        } else {
            $currentrow = end($data);
            $currentrow->endlat = $currentrow->startlat;
            $currentrow->endlong = $currentrow->startlong;
            $currentrow->endtime = date('Y-m-d', strtotime($currentrow->starttime));
            $currentrow->endtime .= " 23:59:59";
            $currentrow->endodo = $currentrow->startodo;
            ;
            $currentrow->duration = $this->getduration_dashboard($currentrow->endtime, $currentrow->starttime);
            $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;
            $datarows[] = $currentrow;
        }
        return $datarows;
    }

    function DataFromSqlite($location) {
        $PATH = "$location";
        $Query = 'select * from devicehistory';
        $Query .= ' INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated';
        $Query .= ' INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = unithistory.lastupdated group by devicehistory.lastupdated';
        $DRMS = array();
        $lastig;
        try {
            $db = new PDO($PATH);
            $result = $db->query($Query);
            $firt_ll_set = 0;
            foreach ($result as $row) {
                $DRM = new VODevices();

                if (!isset($lastig) || $lastig != $row['ignition']) {
                    $DRM->condition = 1;
                } else {
                    $DRM->condition = 0;
                }
                //Unit Part
                if ($firt_ll_set == 0) {
                    $DRM->first_dev_lat = $row['devicelat'];
                    $DRM->first_dev_long = $row['devicelong'];
                    $firt_ll_set = 1;
                }

                $DRM->uhid = $row['uhid'];
                $DRM->uid = $row['uid'];
                $DRM->unitno = $row['unitno'];
                $DRM->customerno = $row['customerno'];
                $DRM->vehicleid = $row['vehicleid'];
                $DRM->analog1 = $row['analog1'];
                $DRM->analog2 = $row['analog2'];
                $DRM->analog3 = $row['analog3'];
                $DRM->analog4 = $row['analog4'];
                $DRM->digitalio = $row['digitalio'];
                $DRM->lastupdated = $row['lastupdated'];
                $DRM->dhid = $this->ret_issetor($row['dhid']);
                $DRM->vhid = $this->ret_issetor($row['vhid']);

                //VehiclePart
                $DRM->vehiclehistoryid = $row['vehiclehistoryid'];
                $DRM->vehicleid = $row['vehicleid'];
                $DRM->vehicleno = $row['vehicleno'];
                $DRM->devicekey = $this->ret_issetor($row['devicekey']);
                $DRM->extbatt = $row['extbatt'];
                $DRM->odometer = $row['odometer'];
                $DRM->curspeed = $row['curspeed'];
                $DRM->customerno = $row['customerno'];
                $DRM->driverid = $row['driverid'];

                //DevicePart
                $DRM->devicehistoryid = $row['id'];
                $DRM->deviceid = $row['deviceid'];
                $DRM->customerno = $row['customerno'];
                $DRM->devicelat = $row['devicelat'];
                $DRM->devicelong = $row['devicelong'];
                $DRM->devicekey = $row['devicekey'];
                $DRM->altitude = $row['altitude'];
                $DRM->directionchange = $row['directionchange'];
                $DRM->inbatt = $row['inbatt'];
                $DRM->hwv = $row['hwv'];
                $DRM->swv = $row['swv'];
                $DRM->msgid = $row['msgid'];
                $DRM->uid = $row['uid'];
                $DRM->status = $row['status'];
                $DRM->ignition = $row['ignition'];
                $DRM->powercut = $row['powercut'];
                $DRM->tamper = $row['tamper'];
                $DRM->gpsfixed = $row['gpsfixed'];
                $DRM->online_offline = $row['online/offline'];
                $DRM->gsmstrength = $row['gsmstrength'];
                $DRM->gsmregister = $row['gsmregister'];
                $DRM->gprsregister = $row['gprsregister'];
                $lastig = $row['ignition'];
                $DRMS[] = $DRM;
            }
        } catch (PDOException $e) {
            $DRMS = 0;
        }
        return $DRMS;
    }

    function getGroupname($groupid, $customerno) {
        $list = array();
        $Query = "SELECT groupid,groupname,cityid,code,address FROM `group` where customerno=$customerno AND groupid=$groupid AND isdeleted=0";

        $record = $this->db->query($Query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);
        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $listitem[] = $row;
            }


            foreach ($listitem as $row1) {
                //print_r($row1);

                $group = new object();
                $group->groupid = $row1['groupid'];
                $group->groupname = $row1['groupname'];
                $group->cityid = $row1['cityid'];
                $group->code = $row1['code'];
                $group->address = $row1['address'];
                $list[] = $group;
            }

            return $list;
        }
        return null;
    }

    function get_vehicle($vehicleid, $customerno) {
        $Query = "SELECT vehicle.vehicleno
                    , driver.drivername
                    , driver.driverphone
                    , devices.deviceid
                    , devices.devicelat
                    , devices.devicelong
                    FROM    vehicle
                    inner join devices on devices.uid=vehicle.uid
                    left join driver on driver.driverid=vehicle.driverid
                    WHERE   vehicle.customerno =$customerno
                    AND     vehicle.vehicleid=$vehicleid
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
            }
            return $vehicle;
        }
        return null;
    }

    function getduration1($StartTime) {
        $EndTime = date('Y-m-d H:i:s');
//                echo $EndTime.'_'.$StartTime.'<br>';
        $idleduration = strtotime($EndTime) - strtotime($StartTime);
        $years = floor($idleduration / (365 * 60 * 60 * 24));
        $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        if ($years >= '1' || $months >= '1') {
            $diff = date('d-m-Y', strtotime($StartTime));
        } else if ($days > 0) {
            $diff = $days . ' days ' . $hours . ' hrs ago';
        } else if ($hours > 0) {
            $diff = $hours . ' hrs and ' . $minutes . ' mins ago';
        } else if ($minutes > 0) {
            $diff = $minutes . ' mins ago';
        } else {
            $seconds = strtotime($EndTime) - strtotime($StartTime);
            $diff = $seconds . ' sec ago';
        }
        return $diff;
    }

    function gettemplist($rawtemp, $use_humidity) {
        if ($use_humidity == 1) {
            $temp = round($rawtemp / 100);
        } else {
            $temp = round((($rawtemp - 1150) / 4.45), 1);
        }
        return $temp;
    }

    function gettripdetails($vehicleid, $customerno) {    //api call this function
        $data = array();
        $query = "select t.tripid"
                . ",t.statusdate"
                . ",t.vehicleno"
                . ",ts.tripstatus"
                . ",t.vehicleid"
                . ",t.triplogno"
                . ",t.tripstatusid"
                . ",t.routename"
                . ",t.remark"
                . ",t.budgetedkms"
                . ",t.budgetedhrs"
                . ",con.consigneename"
                . ",consr.consignorname"
                . ",t.billingparty"
                . ",t.mintemp"
                . ",t.maxtemp"
                . ",t.drivername"
                . ",t.drivermobile1"
                . ",t.drivermobile2"
                . ",t.entrytime "
                . ",t.is_tripend "
                . " from tripdetails as t "
                . " left join tripstatus as ts on ts.tripstatusid = t.tripstatusid "
                . " left join tripconsignee as con  on con.consid = t.consigneeid "
                . " left join tripconsignor as consr  on consr.consrid = t.consignorid "
                . " where t.customerno=" . $customerno
                . " AND t.vehicleid='" . $vehicleid . "'"
                . " AND t.isdeleted=0"
                . " order by t.triplogno desc limit 1";

        $record = $this->db->query($query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);

        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                if (!is_null($row['statusdate'])) {
                    $statusdate = date("d-m-Y, h:i a", strtotime($row['statusdate']));
                } else {
                    $statusdate = 'Not Defined';
                }
                $data = array(
                    "tripid" => $row['tripid']
                    , "status" => $statusdate
                    , "vehicleno" => $row['vehicleno']
                    , "tripstatus" => $row["tripstatus"]
                    , "vehicleid" => $row['vehicleid']
                    , "triplogno" => $row['triplogno']
                    , "tripstatusid " => $row['tripstatusid']
                    , "routename" => $row['routename']
                    , "remark" => $row['remark']
                    , "budgetedkms" => $row['budgetedkms'] . " / "
                    , "budgetedhrs" => $row['budgetedhrs'] . " / "
                    , "consignee" => $row['consigneename']
                    , "consignor" => $row['consignorname']
                    , "billingparty" => $row['billingparty']
                    , "mintemp" => $row['mintemp']
                    , "maxtemp" => $row['maxtemp']
                    , "temprange" => floor($row['mintemp']) . " C To " . floor($row['maxtemp']) . " C "
                    , "drivername" => $row['drivername']
                    , "drivermobile1" => $row['drivermobile1']
                    , "drivermobile2" => $row['drivermobile2']
                    , "entrytime" => $row['entrytime']
                    , "is_tripend" => $row['is_tripend']
                );
            }
            return $data;
        } else {
            $data = array(
                "triplogno" => "Not Defined",
                "status" => "Not Defined",
                //"tripstatusid " => $row['tripstatusid'],
                "routename" => "Not Defined",
                "budgetedkms" => "Not Defined",
                "budgetedhrs" => "Not Defined",
                "actualkms" => "Not Defined",
                "actualhrs" => "Not Defined",
                "consignor" => "Not Defined",
                "consignee" => "Not Defined",
                "billingparty" => "Not Defined",
                "temprange" => "Not Defined",
                "drivername" => "Not Defined",
                "drivermobile1" => "Not Defined",
                "drivermobile2" => "Not Defined",
                "tripid" => "Not Defined",
                "remark" => "Not Defined"
            );
            return $data;
        }
        return NULL;
    }

    function getduration_digitalio($StartTime, $EndTime1) {
        $EndTime = date('Y-m-d H:i:s', strtotime($EndTime1));
        $idleduration = strtotime($EndTime) - strtotime($StartTime);
        $years = floor($idleduration / (365 * 60 * 60 * 24));
        $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        if ($years >= '1' || $months >= '1') {
            $diff = date('d-m-Y', strtotime($StartTime));
        } else if ($days > 0) {
            $diff = $days . ' days ' . $hours . ' hrs ';
        } else if ($hours > 0) {
            $diff = $hours . ' hrs and ' . $minutes . ' mins ';
        } else {
            $diff = $minutes . ' mins ';
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

    function DataForTemprature($path, $deviceid, $startdate, $enddate, $interval, $temp_sensors) {
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

                $temp = 'Not Active';
                $temp1 = 'Not Active';
                $temp2 = 'Not Active';
                $temp3 = 'Not Active';
                $temp4 = 'Not Active';

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
            echo'<pre>';
            print_r($devices);
            die();
            return $devices;
        }
    }

    function getSMSCount($customerno) {
        $smscount = 0;
        $pdo = $this->db->CreatePDOConn();
        $sqlSmsGet = sprintf("SELECT smsleft FROM customer WHERE customerno=%d", $customerno);
        $arrResult = $pdo->query($sqlSmsGet)->fetch(PDO::FETCH_ASSOC);
        $this->db->ClosePDOConn($pdo);
        if (count($arrResult) == 1) {
            $smscount = $arrResult['smsleft'];
        }
        return $smscount;
    }

    function updateSMSCount($existingSMSCount, $smsmessage, $customerno) {
        $smsconsumed = 0;
        $smsleft = 0;
        $smslength = strlen($smsmessage);
        $divide = floor($smslength / api::PER_SMS_CHARACTERS);
        $mod = $smslength % api::PER_SMS_CHARACTERS;
        if ($mod > 0) {
            $smsconsumed = $divide + 1;
        } else if ($mod == 0) {
            $smsconsumed = $divide;
        }
        $smsleft = $existingSMSCount - $smsconsumed;
        $pdo = $this->db->CreatePDOConn();
        $sqlSmsUpdate = sprintf("UPDATE customer SET smsleft=%d WHERE customerno=%d", $smsleft, $customerno);
        $pdo->query($sqlSmsUpdate);
        $this->db->ClosePDOConn($pdo);
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

    function check_vehicle_user_mapping($userid, &$mappedgroupid) {
        $isUserMappedToVehicle = 0;
        //Prepare parameters
        $sp_params = "'" . $userid . "'"
                . "," . '@groupidparam' . ""
                . "," . '@isUserMappedToVehicle' . "";
        $queryCallSP = "CALL " . SP_CHECK_VEHICLE_USER_MAPPING . "($sp_params)";
        $this->db->query($queryCallSP, __FILE__, __LINE__);
        $outputParamQuery = "SELECT @isUserMappedToVehicle as isUserMappedToVehicle, @groupidparam as groupid";
        $outParamResult = $this->db->query($outputParamQuery, __FILE__, __LINE__);
        while ($row = $this->db->fetch_array($outParamResult)) {
            $isUserMappedToVehicle = $row['isUserMappedToVehicle'];
            $mappedgroupid = $row['groupid'];
        }
        return $isUserMappedToVehicle;
    }

    function PrepareHtmlData($Datacap, $customerno, $mail_type) {
        $reportsObj = new reports();
        $customer_details = $reportsObj->getcustomerdetail_byid($customerno);
        $reportHtmlData = "";
        $reportHtmlData .= "<html>";
        $reportHtmlData .= "<head>";
        $reportHtmlData .= "<style type='text/css'>
                                body{
                                    font-family:Arial;
                                    font-size: 11pt;
                                }
                                table{
                                    text-align: center;
                                    border-right:1px solid black;
                                    border-bottom:1px solid black;

                                    border-collapse:collapse;
                                    font-family:Arial;
                                    font-size: 10pt;
                                    width: 60%;
                                }
                            </style>";
        $reportHtmlData .= "</head>";
        $reportHtmlData .= "<body>";
        $title = 'Summary Report';
        $subTitle = array();
        if ($mail_type == 'pdf') {
            $reportHtmlData .= $reportsObj->pdf_header($title, $subTitle, $customer_details);
        } else if ($mail_type == 'xls') {
            $reportHtmlData .= $reportsObj->excel_header($title, $subTitle, $customer_details);
        } else {
            return;
        }

        $reportHtmlData .= "<table align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $reportHtmlData .= "<tr><td width:430px;>Vehicle No</td><td width:650px;>$Datacap->VehicleName</td></tr>";
        $reportHtmlData .= "<tr><td>Driver Name</td><td>$Datacap->DriverName</td></tr>";
        $reportHtmlData .= "<tr><td>Group</td><td>$Datacap->Group</td></tr>";
        $reportHtmlData .= "<tr><td>Start Location</td><td>$Datacap->SL</td></tr>";
        $reportHtmlData .= "<tr><td>End Location</td><td>$Datacap->EL</td></tr>";
        $reportHtmlData .= "<tr><td>Distance Travelled</td><td>$Datacap->DT</td></tr>";
        $reportHtmlData .= "<tr><td>Average Speed</td><td>$Datacap->AS</td></tr>";
        $reportHtmlData .= "<tr><td>Running Time</td><td>$Datacap->RT</td></tr>";
        $reportHtmlData .= "<tr><td>Overspeed</td><td>$Datacap->OS</td></tr>";
        $reportHtmlData .= "<tr><td>Top speed</td><td>$Datacap->TS</td></tr>";
        $reportHtmlData .= "<tr><td>Top speed location</td><td>$Datacap->TSL</td></tr>";
        $reportHtmlData .= "<tr><td>Harsh Break</td><td>$Datacap->HB</td></tr>";
        $reportHtmlData .= "<tr><td>Sudden Acceleration</td><td>$Datacap->SA</td></tr>";
        $reportHtmlData .= "<tr><td>Towing / Freewheeling</td><td>$Datacap->TO</td></tr>";
        $reportHtmlData .= "</table>";
        $reportHtmlData .= "</body>";
        $reportHtmlData .= "</html>";

        return $reportHtmlData;
    }

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Utility functions">
    function sendMail(array $arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName) {
        include_once("../../cron/class.phpmailer.php");
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

    function sendSMS($phoneArray, $message, &$response) {
        $isSMSSent = 0;
        $countryCode = "91";
        $arrPhone = array();
        if (is_array($phoneArray)) {
            foreach ($phoneArray as $phone) {
                if (preg_match('/^\d{10}$/', $phone)) {
                    $arrPhone[] = $countryCode . $phone;
                }
            }
        } else {
            $arrPhone[] = $countryCode . $phoneArray;
        }
        $phone = implode(",", $arrPhone);
        $url = str_replace("{{PHONENO}}", urlencode($phone), SMS_URL);
        $url = str_replace("{{MESSAGETEXT}}", urlencode($message), $url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        if ($response === false) {
            //echo 'Curl error: ' . curl_error($ch);
            $isSMSSent = 0;
        } else {
            $isSMSSent = 1;
        }
        curl_close($ch);
        return $isSMSSent;
    }

    //</editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Not used in this page. Delete if not required">
    function odometerfromSqlite($location) {
        try {
            $DRMS = array();
            $DRMS['first'] = 0;
            $DRMS['last'] = 0;
            if (file_exists($location)) {
                $path = "sqlite:$location";
                $dbs = new PDO($path);
                //$query = "SELECT * from vehiclehistory ORDER BY vehiclehistory.lastupdated desc LIMIT 0,1";
                $Query = "SELECT (SELECT odometer FROM vehiclehistory ORDER BY odometer LIMIT 1) as 'first',(SELECT odometer FROM vehiclehistory ORDER BY odometer DESC LIMIT 1) as 'last'";
                $sobj = $dbs->prepare($Query);
                $sobj->execute();
                /* Fetch all of the remaining rows in the result set */

                $result = $sobj->fetchAll();
                $DRMS['first'] = $result[0]['first'];
                $DRMS['last'] = $result[0]['last'];
            }
        } catch (PDOException $e) {
            $e->getMessage();
        }
        return $DRMS;
    }

    // catch if data is called
    function catchdata($arrj) {
        $insert_sql_array = array();
        $insert_sql_array['payload'] = json_encode($arrj);
        $this->db->insert(API, $insert_sql_array);
        $jsonp = array();
        $jsonp['status'] = "successful";
        echo json_encode($jsonp);
    }

    function devicesformapping($customerno) {
        $devices = Array();
        $devicelist = Array();

        $Query = "SELECT * FROM `devices`
INNER JOIN unit ON unit.uid = devices.uid
INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
INNER JOIN driver ON vehicle.driverid = driver.driverid
where devices.customerno=$customerno AND unit.trans_statusid NOT IN (10,22)and vehicle.kind<>'Warehouse' ";
        $Query.=" ORDER BY vehicle.vehicleno";

        $record = $this->db->query($Query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);

        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $devicelist[] = $row;
            }
            foreach ($devicelist as $row) {
                $device = new VODevices();


                $device->vehicleno = $row['vehicleno'];
                $device->vehicleid = $row['vehicleid'];
                $device->type = $row['kind'];
                $device->deviceid = $row['deviceid'];
                $device->devicelat = $row['devicelat'];
                $device->devicelong = $row['devicelong'];
                $device->drivername = $row['drivername'];
                $device->driverphone = $row['driverphone'];
                $device->curspeed = $row['curspeed'];
                $device->overspeed_limit = $row['overspeed_limit'];
                $device->lastupdated = $row['lastupdated'];
                $device->type = $row['kind'];
                $device->ignition = $row['ignition'];
                $device->status = $row['status'];
                $device->tempsen1 = $row['tempsen1'];
                $device->tempsen2 = $row['tempsen2'];
                $device->temp1_min = $row['temp1_min'];
                $device->temp1_max = $row['temp1_max'];
                $device->temp2_min = $row['temp2_min'];
                $device->temp2_max = $row['temp2_max'];
                $device->tempsen1 = $row['tempsen1'];
                $device->analog1 = $row["analog1"];
                $device->analog2 = $row["analog2"];
                $device->analog3 = $row["analog3"];
                $device->analog4 = $row["analog4"];
                $device->stoppage_flag = $row['stoppage_flag'];
                $device->stoppage_transit_time = $row['stoppage_transit_time'];
                $device->directionchange = $row['directionchange'];
                $devices[] = $device;
            }
//print_r($devices);
//echo json_encode($devices);
            return $devices;
        }
//return null;
    }

    function api_track($skey, $vehicleno) {
        $skey = stripslashes(trim($skey));
        $vehicleno = stripslashes(trim($vehicleno));
        if ($skey != "" && isset($skey)) {
            $sql1 = "select customerno from " . TBL_ADMIN_USER . " where 1 and userkey='" . $skey . "'";
            $result1 = $this->db->query($sql1, __FILE__, __LINE__);
            $row1 = $this->db->fetch_array($result1);

            if ($row1['customerno'] != "") {
                $_SESSION['api_customer_id'] = $row1['customerno'];
                header('Location: map.php?vehicleno=' . $vehicleno . '');
            } else {
                echo "Customer does not exist";
            }
        } else {
            echo "Request denied";
            die();
        }
    }

    function api_device_list($vehicleno) {

        if (isset($_SESSION['api_customer_id']) && $_SESSION['api_customer_id'] != "") {
            $sql = "select  	devices.*,	unit.vehicleid ,	vehicle.vehicleno,vehicle.extbatt,vehicle.curspeed,vehicle.odometer, ";
            $sql.="driver.drivername,driver.drivername,driver.driverphone FROM 	devices INNER JOIN unit ON unit.uid=devices.uid INNER JOIN vehicle ON 	";
            $sql.="vehicle.vehicleid=unit.vehicleid INNER JOIN driver  ON driver.driverid=vehicle.driverid";
            $sql.=" WHERE devices.customerno=" . $_SESSION['api_customer_id'] . " and vehicle.isdeleted=0 and  driver.isdeleted=0   ";
            $str = array();

            if ($vehicleno != "null") {
                $vehicleno = explode(",", $vehicleno);

                $count = count($vehicleno);
                for ($i = 0; $i < $count; $i++) {
                    if ($i == 0) {
                        $sql.=" and ";
                    }

                    if ($vehicleno[$i]) {

                        $sql.=" vehicleno = '" . stripslashes(trim($vehicleno[$i])) . "'  ";
                        if ($count == 1) {

                        } else {
                            if ($i < ($count - 1))
                                $sql.=" or  ";
                        }
                    }
                }
            }

            //echo $sql;

            $record = $this->db->query($sql, __FILE__, __LINE__);
            $json_p = array();
            //$json_p="null";
            $x = 0;

            while ($row = $this->db->fetch_array($record)) {
                if ($row['devicelat'] != 0 and $row['devicelong'] != 0) {

                    $json_p[$x]['deviceid'] = $row['deviceid'];
                    $json_p[$x]['vehicleno'] = $row['vehicleno'];
                    $json_p[$x]['drivername'] = $row['drivername'];
                    $json_p[$x]['driverphoneno'] = $row['driverphoneno'];
                    $json_p[$x]['devicelat'] = $row['devicelat'];
                    $json_p[$x]['devicelong'] = $row['devicelong'];
                    $json_p[$x]['lastupdated'] = $row['lastupdated'];

                    $x++;
                }
            }
            $arr_p = array();
            $arr_p['status'] = "successful";
            $arr_p['dcount'] = $x;

            $arr_p['result'] = $json_p;

            echo json_encode($arr_p);
        } else {

            echo '{"status":"failure"}';
        }
    }

    function event_history_access_handler($skey, $vehicleno, $startdate, $endtime) {

        $skey = stripslashes(trim($skey));
        $endtime = stripslashes(trim($endtime));
        $startdate = stripslashes(trim($startdate));
        $vehicleno = stripslashes(trim($vehicleno));
        if ($skey != "" && isset($skey)) {

            $sql1 = "select customerno from " . TBL_ADMIN_USER . " where 1 and userkey='" . $skey . "'";
            $result1 = $this->db->query($sql1, __FILE__, __LINE__);
            $row1 = $this->db->fetch_array($result1);
            if ($endtime != "") {
                $_SESSION['endtime'] = $endtime;
            } else {
                $_SESSION['endtime'] = "23:59:59";
            }
            if ($startdate != "") {
                $_SESSION['startdate'] = strtotime($startdate);
            } else {
                $_SESSION['startdate'] = strtotime(date("d-m-y h:i:s"));
            }

            if ($row1['customerno'] != "") {
                $_SESSION['api_customer_id'] = $row1['customerno'];
                $sql = "select  	devices.*,unit.unitno,	unit.vehicleid ,	vehicle.vehicleno,vehicle.extbatt,vehicle.curspeed,vehicle.odometer, ";
                $sql.="driver.drivername,driver.drivername,driver.driverphone FROM 	devices INNER JOIN unit ON unit.uid=devices.uid INNER JOIN vehicle ON 	";
                $sql.="vehicle.vehicleid=unit.vehicleid INNER JOIN driver  ON driver.driverid=vehicle.driverid";
                $sql.=" WHERE devices.customerno=" . $_SESSION['api_customer_id'] . " and
                                                            vehicle.vehicleno='" . $vehicleno . "' and vehicle.isdeleted=0 and  driver.isdeleted=0   ";
                $result = $this->db->query($sql, __FILE__, __LINE__);
                $row = $this->db->fetch_array($result);
                $_SESSION['api_unitno'] = $row['unitno'];
                header('Location: eventhistory.php');
            } else {
                echo "Customer does not exist";
            }
        } else {
            echo "Request denied";
            die();
        }
    }

    function event_history_data($devicekey, $startdate, $enddate) {
        $customerno = $_SESSION['api_customer_id'];
        $location = "../../customer/" . $_SESSION['api_customer_id'] . "/unitno/" . $_SESSION['api_unitno'] . "/sqlite/" . date("Y-m-d", $_SESSION['startdate']) . ".sqlite";
        $col_array = array();
        if (file_exists($location)) {

            $path = "sqlite:$location";
            $db_sqllite = new PDO($path);
            if ($db_sqllite) {

                $query_sqllite = "SELECT * FROM unithistory WHERE lastupdated between '" . date("Y-m-d h:i:s", $_SESSION['startdate']) . "' and '" . date("Y-m-d", $_SESSION['startdate']) . " " . $_SESSION['endtime'] . "'";
                $result = $db_sqllite->query($query_sqllite);
                foreach ($result as $row) {
                    // time patch
                    $this->status_time = $row['lastupdated'];
                    // initialisation of a first row
                    if ($c == 0) {
                        $col_array[$i]['stattime'] = $this->status_time;
                        $col_array[$i]['loadstatus'] = $row['msgkey'];
                        //location query
                        $query_sqllite_loc = 'SELECT * FROM devicehistory WHERE id=' . $row['uhid'];
                        $result_loc = $db_sqllite->query($query_sqllite_loc);
                        if (isset($result_loc)) {
                            foreach ($result_loc as $row_loc) {
                                $col_array[$i]['lat'] = $row_loc['devicelat'];
                                $col_array[$i]['long'] = $row_loc['devicelong'];
                            }
                        }
                        // location query
                        $this->status = $row['msgkey'];
                    }
                    // counter condition for the first initailisation
                    $c++;
                    // change checks
                    if ($row['msgkey'] != $this->status) {
                        $col_array[$i]['endtime'] = $this->status_time;

                        //location query
                        $query_sqllite_loc = 'SELECT * FROM devicehistory WHERE id=' . $row['uhid'];
                        $result_loc = $db_sqllite->query($query_sqllite_loc);
                        if (isset($result_loc)) {
                            foreach ($result_loc as $row_loc) {
                                $col_array[$i]['lat'] = $row_loc['devicelat'];
                                $col_array[$i]['long'] = $row_loc['devicelong'];
                            }
                        }
                        // location query
                        // end of row
                        $i++;
                        //array end  init
                        $col_array[$i]['stattime'] = $this->status_time;
                        $col_array[$i]['loadstatus'] = $row['msgkey'];
                        $this->status = $row['msgkey'];
                    }
                }
                $col_array[$i]['endtime'] = $this->status_time;
                //location query
                $query_sqllite_loc = 'SELECT * FROM devicehistory WHERE id=' . $row['uhid'];
                $result_loc = $db_sqllite->query($query_sqllite_loc);
                if (isset($result_loc)) {
                    foreach ($result_loc as $row_loc) {
                        $col_array[$i]['lat'] = $row_loc['devicelat'];
                        $col_array[$i]['long'] = $row_loc['devicelong'];
                    }
                }
                // location query
            } else {
                die($err);
            }
        } else {

            echo "file not found";
        }
        ?>
        <table >
            <tr>
                <th>Start Time</th>
                <th>End time Time</th>
                <th>Location</th>
                <th>Start Time</th>
                <th>Duration </th>
            </tr>
            <?php
            foreach ($col_array as $row_changes) {
                ?>
                <tr>
                    <td> <?php echo $row_changes['stattime']; ?></td>
                    <td> <?php echo $row_changes['endtime']; ?></td>
                    <td>
                        <?php
                        if ($lat != '0' && $long != '0') {
                            $API = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" . $row_changes['lat'] . "," . $row_changes['long'] . "";
                            $location = json_decode(file_get_contents("$API&sensor=false"));
                            @$address = $location->results[0]->formatted_address;
                            if ($location->status == "OVER_QUERY_LIMIT") {
                                @$address = "Temporarily Unavailable";
                            }
                        }
                        echo $address;
                        ?> </td>
                    <td> <?php
                        if ($row_changes['loadstatus'] == 0) {
                            echo "done";
                        } else if ($row_changes['loadstatus'] == 1) {
                            echo "unloading";
                        } else if ($row_changes['loadstatus'] == 2) {
                            echo "loading";
                        }
                        ?></td>
                    <td><?php
                        $diff = (strtotime($row_changes['endtime']) - strtotime($row_changes['stattime']));
                        $this->dateDifference($diff);
                        ?></td>

                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }

    function event_login_access_handler($skey) {
        //$skey=stripslashes(trim($skey));
        if ($skey != "") {
            $sql1 = "select * from " . TBL_ADMIN_USER . " where 1 and userkey='" . $skey . "'";
            $result1 = $this->db->query($sql1, __FILE__, __LINE__);
            $row1 = $this->db->fetch_array($result1);
            if ($row1['customerno'] != "") {
                $_SESSION['customerno'] = $row1['customerno'];
                $_SESSION['username'] = $row1['username'];
                $_SESSION['realname'] = $row1['realname'];
                $_SESSION['userid'] = $row1['userid'];
                $_SESSION['Session_UserRole'] = $row1['role'];
                $_SESSION['subdir'] = "/speed/";
                // Setting session variables
                $_SESSION["customercompany"] = $row1["customercompany"];
                $_SESSION["visits_modal"] = $row1['visited'];
                $_SESSION["role_modal"] = $row1['role'];

                $_SESSION["sessionauth"] = $row1['role'];
                $_SESSION["groupid"] = $row1['groupid'];
                header('Location: http://speed.elixiatech.com/modules/realtimedata/realtimedata.php');
            } else {
                echo "request denied";
            }
        }
    }

    function event_login_access_handler_unsub($skey) {
        //$skey=stripslashes(trim($skey));
        if ($skey != "") {
            $sql1 = "select *,user.customerno from " . TBL_ADMIN_USER . " INNER JOIN customer ON customer.customerno = user.customerno where 1";
            $result1 = $this->db->query($sql1, __FILE__, __LINE__);
            while ($row1 = $this->db->fetch_array($result1)) {
                if (sha1($row1['userkey']) == $skey) {
                    $_SESSION['customerno'] = $row1['customerno'];
                    $_SESSION['username'] = $row1['username'];
                    $_SESSION['realname'] = $row1['realname'];
                    $_SESSION['userid'] = $row1['userid'];
                    $_SESSION['Session_UserRole'] = $row1['role'];
                    $_SESSION['subdir'] = "/speed/";
                    // Setting session variables
                    $_SESSION["customercompany"] = $row1["customercompany"];
                    $_SESSION["visits_modal"] = $row1['visited'];
                    $_SESSION["role_modal"] = $row1['role'];
                    //$_SESSION['Session_User'] = $user;
                    $_SESSION["sessionauth"] = $row1['role'];
                    $_SESSION["groupid"] = $row1['groupid'];

                    $log = new Log();
                    if ($log->createlog($_SESSION['customerno'], "Logged In", $_SESSION['userid'])) {

                    }
                    header('Location: http://www.speed.elixiatech.com/modules/user/accinfo.php?id=3');
                }
            }
        }
    }

    function event_alerts() {
        for ($i = 0; $i <= 19; $i++) {
            $sql = "Select * from " . TBL_VEHICLE . " where isdeleted=0 and customerno=" . $i . " ";
            $result1 = $this->db->query($sql, __FILE__, __LINE__);
            while ($row = $this->db->fetch_array($result1)) {
                $sql2 = "Select * from " . TBL_IGI . " where vehicleid=" . $row['vehicleid'] . " and customerno=" . $row['customerno'] . " ";
                $result12 = $this->db->query($sql2, __FILE__, __LINE__);
                $row2 = $this->db->fetch_array($result12);
                if ($row2['igalertid'] == 0) {
                    $insert_sql_array = array();
                    $insert_sql_array['vehicleid'] = $row['vehicleid'];
                    $insert_sql_array['customerno'] = $row['customerno'];
                    echo "<pre>";
                    print_r($insert_sql_array);
                    echo "</pre>";
                    $this->db->insert(TBL_IGI, $insert_sql_array);
                }
            }
        }
    }

    // </editor-fold>
}
?>
