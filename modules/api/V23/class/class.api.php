<?php

require_once "global.config.php";
require_once "database.inc.php";
require_once "reports.api.php";

date_default_timezone_set('Asia/Kolkata');

class api {
    static $SMS_TEMPLATE_FOR_VEHICLE_USER_DRIVER_MAPPING = "Dear {{USERNAME}}, {{VEHICLENO}} has been allotted for your pickup. Driver Name: {{DRIVERNAME}} ({{DRIVERPHONE}})";
    //static $SMS_TEMPLATE_FOR_QUICK_SHARE = "{{USERNAME}} wants you to track the trip at {{URL}}";
    static $SMS_TEMPLATE_FOR_QUICK_SHARE = "Vehicle No: {{VEHICLENO}}\r\nLocation: {{LOCATION}}\r\nShared by: {{USERNAME}}";
    public $status;
    public $status_time;

    //<editor-fold defaultstate="collapsed" desc="Constructor">
    // construct
    public function __construct() {
        $this->db = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, SPEEDDB);
        $this->db1 = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, SPEEDDB);
    }

    // </editor-fold>
    //
    //<editor-fold defaultstate="collapsed" desc="API functions">
    // checks for login
    public function check_login($username, $password) {
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
            $otpSent = "No";
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
                    $retarray['userid'] = $arrResult['userid'];
                    $retarray['userkey'] = $arrResult['userkey'];
                    $retarray['userkey_sha1'] = sha1($arrResult['userkey']);
                    $retarray['customerno'] = $arrResult['customerno'];
                    $retarray['username'] = $arrResult['username'];
                    $retarray['realname'] = $arrResult['realname'];
                    $retarray['customername'] = $arrResult['customercompany'];
                    $retarray['version'] = $arrResult['version'];
                    $retarray['role'] = $arrResult['role'];
                    $retarray['notification_status'] = $arrResult['notification_status'];
                    $retarray['email'] = $arrResult['email'];
                    $retarray['phone'] = $arrResult['phone'];
                    $retarray['temp_sensors'] = $arrResult['temp_sensors'];
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
            }
            if ($usertype == 1 && $userkeyparam != 0) {
                $otpSent = "Yes";
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

    public function PullAccountSwitchDetails($objReqDetails) {
        $isValidUserKey = 0;

        $arrResult['status'] = 0;
        $arrResult['message'] = speedConstants::API_INVALID_USERKEY;
        /* Set Default response */
        $arrResponse = array();

        $arrResponse["isValidUserKey"] = $isValidUserKey;
        $arrResponse["userKey"] = $objReqDetails->userkey;
        $arrResponse["userkey_sha1"] = sha1($objReqDetails->userkey);
        $arrResponse["customerno"] = 0;
        $arrResponse["userrole"] = "";
        $arrResponse["userid"] = "";
        $arrResponse["accountswitch"] = array();

        $arrValidationResult = $this->check_userkey($objReqDetails->userkey);
        if ($arrValidationResult['status'] == "successful") {
            $isValidUserKey = 1;
            $arrResult['status'] = 1;
            $arrResult['message'] = speedConstants::API_SUCCESS;
            $arrResponse["isValidUserKey"] = $isValidUserKey;
            $customerno = $arrValidationResult["customerno"];
            $userRoleName = $arrValidationResult["role"];
            $userId = $arrValidationResult["userid"];
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
            $arrResponse["customerno"] = $customerno;
            $arrResponse["userrole"] = $userRoleName;
            $arrResponse["userid"] = $userId;
            $arrResponse["accountswitch"] = $this->GetUsersForAccountSwitch($userId, $userRoleName);
        }
        $arrResult['result'] = $arrResponse;
        return $arrResult;
    }

    public function update_user_notification_status($userid, $notification_status) {
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
        $arr_p = array();
        $arr_p['status'] = "successful";
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    public function update_gcmid($userid, $gcmid) {
        $sql = "update user set gcmid='" . $gcmid . "' where userid =" . $userid;
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $arr_p = array();
        $arr_p['status'] = "successful";
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    public function pullcrm($userkey) {
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        $json_p = array();
        if ($validation['status'] == "successful") {
            // successful
            $customerno = $validation["customerno"];
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
            $sql = "SELECT *  FROM " . DB_PARENT . ".customer LEFT OUTER JOIN " . DB_PARENT . ".relationship_manager ON relationship_manager.rid = customer.rel_manager
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

    public function device_list($userkey, $pageIndex, $pageSize, $searchstring, $groupidparam, $isRequiredThirdParty, $isClientCode = null) {
        $isWareHouse = 0;
        $totalVehicleCount = 0;
        $fuel_alert_percentage = 0;
        $use_fuel_sensor = 0;
        $grpIDS = array(); // For Grp Based Vehicles - 30 Nov
        $isClientCode = isset($isClientCode) ? $isClientCode : 0;
        if ($isClientCode) {
            $validation = $this->checkClientCode($userkey);
        } else {
            $validation = $this->check_userkey($userkey);
        }
        //print_r($validation);

        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        $temp_coversion = new TempConversion();
        if ($validation['status'] == "successful") {
            if ($isClientCode) {
                $arr_p['version'] = "V23";
                $arr_p['customername'] = $validation['customercompany'];
                $arr_p['userkey'] = $userkey;
                $arr_p['isClientCode'] = "1";
                $arr_p['customerno'] = $validation['customerno'];
                $arr_p['email'] = $validation['ecodeemail'];
                $arr_p['phone'] = $validation['ecodesms'];
                $arr_p['role'] = $validation['role'];
            }

            $fuel_alert_percentage = isset($validation['fuel_alert_percentage']) ? $validation['fuel_alert_percentage'] : 0;
            $use_fuel_sensor = isset($validation['use_fuel_sensor']) ? $validation['use_fuel_sensor'] : 0;
            $use_ac_sensor = isset($validation['use_ac_sensor']) ? $validation['use_ac_sensor'] : 0;
            $use_door_sensor = isset($validation['use_door_sensor']) ? $validation['use_door_sensor'] : 0;
            $use_genset_sensor = isset($validation['use_genset_sensor']) ? $validation['use_genset_sensor'] : 0;
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

                //custom fields
                $arr_p['custom'] = $this->custom_fields($customerno);

                if ($customerno == 97) {
                    date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
                }
                $userId = $validation["userid"];
                $userRoleId = $validation["roleid"];
                $isUserMappedToVehicle = 0;
                $mappedgroupid = -1;

                if ($userRoleId == 39 || $userRoleId == 43) {
                    $isUserMappedToVehicle = $this->check_vehicle_user_mapping($userId, $groupidparam, $mappedgroupid);
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

                if ($isClientCode) {
                    //Prepare parameters
                    $sp_params = "'" . $pageIndex . "'"
                        . ",'" . $pageSize . "'"
                        . "," . $customerno . ""
                        . "," . $isWareHouse . ""
                        . ",'" . $searchstring . "'"
                        . ",'" . $groupidparam . "'"
                        . ",'" . $userkey . "'"
                        . ",'" . $isRequiredThirdParty . "'"
                        . ",'" . $userkey . "'";
                    $queryCallSP = "CALL " . speedConstants::SP_GET_VEHICLEWAREHOUSE_ECODE_DETAILS . "($sp_params)";
                } else {
                    //Prepare parameters
                    if (isset($validation['consigneeid']) && $validation['consigneeid'] != null && $validation['consigneeid'] != '') {
                        $consigneeidParam = $validation['consigneeid'];
                        /*  $consigneeidParam = null; */
                    } else {
                        $consigneeidParam = 0;
                    }
                    $sp_params = "'" . $pageIndex . "'"
                        . ",'" . $pageSize . "'"
                        . "," . $customerno . ""
                        . "," . $isWareHouse . ""
                        . ",'" . $searchstring . "'"
                        . ",'" . $groupidparam . "'"
                        . ",'" . $userkey . "'"
                        . ",'" . $isRequiredThirdParty . "'"
                        . ",'" . $consigneeidParam . "'";

                    $queryCallSP = "CALL " . speedConstants::SP_GET_VEHICLEWAREHOUSE_DETAILS . "($sp_params)";
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
                        $json_p[$x]['unitno'] = $row['unitno'];
                        $json_p[$x]['customercompany'] = $row['customercompany'];
                        $json_p[$x]['groupid'] = $row['groupid'];
                        $json_p[$x]['groupname'] = '';
                        if (isset($row['groupid']) && $row['groupid'] > 0) {
                            $arrGrp = $this->getGroupname($row['groupid'], $customerno);
                            $json_p[$x]['groupname'] = $arrGrp;
                        }
                        $kind = 0;
                        if ($row['kind'] == "Car") {
                            $kind = 1;
                        } elseif ($row['kind'] == "Truck") {
                            $kind = 2;
                        } elseif ($row['kind'] == "Bus") {
                            $kind = 3;
                        }
                        $json_p[$x]['kind'] = $kind;
                        $json_p[$x]['location'] = $this->location($row['devicelat'], $row['devicelong'], $row['use_geolocation'], $row['customer_no']);
                        if (isset($row['lastupdated']) && $row['lastupdated'] != '0000-00-00 00:00:00' && $row['lastupdated'] != '') {
                            $json_p[$x]['lastupdated'] = $this->getduration1($row['lastupdated']);
                        } else {
                            $json_p[$x]['lastupdated'] = '';
                        }
                        $json_p[$x]['driverphone'] = $row['driverphone'];
                        $json_p[$x]['simcardno'] = $row['simcardno'];
                        $json_p[$x]['sequenceno'] = $row['sequenceno'];
                        $json_p[$x]['isFrozen'] = isset($row['is_freeze']) ? $row['is_freeze'] : '0';
                        $json_p[$x]['distance'] = $this->distance($customerno, $row['unitno']);
                        $diff = $this->getduration1($row["stoppage_transit_time"]);
                        $temp_coversion->unit_type = $row['get_conversion'];
                        if ($row["stoppage_flag"] == '1') {
                            $json_p[$x]['ignstatus'] = "Running since $diff";
                        } elseif ($row["stoppage_flag"] == '0') {
                            $json_p[$x]['ignstatus'] = "Idle since $diff";
                        }
                        $temp_coversion->unit_type = $row['get_conversion'];
                        //status start
                        $status = "1";
                        $ServerIST_less1 = new DateTime();
                        $ServerIST_less1->modify('-60 minutes');
                        $lastupdated = new DateTime($row['lastupdated']);
                        $temp1_conflicted = 0;
                        $temp2_conflicted = 0;
                        $temp3_conflicted = 0;
                        $temp4_conflicted = 0;
                        $speed_conflicted = 0;
                        $temp1 = $temp2 = $temp3 = $temp4 = "Not Active";
                        $tempsensor = 0;
                        if ($lastupdated < $ServerIST_less1 || !isset($row['lastupdated']) || $row['lastupdated'] == '0000-00-00 00:00:00' || $row['lastupdated'] == '') {
//                            $status = "1"; //inactive grey
                        } else {
                            //<editor-fold defaultstate="collapsed" desc="Check for temperature conflict">
                            if (isset($row['temp_sensors'])) {
//                                $status = "1";
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
                                $tempsensor = $row['temp_sensors'];
                                switch ($row['temp_sensors']) {
                                    case 4:
                                        if (isset($row['tempsen4'])) {
                                            //Set default temp conflict as 0
                                            $json_p[$x]['temp4_conflicted'] = $temp4_conflicted;
                                            $s = "analog" . $row['tempsen4'];
                                            $analogValue = isset($row[$s]) ? $row[$s] : 0;
                                            if ($row['tempsen4'] != 0 && $analogValue != 0) {
                                                //$temp4 = $this->gettemplist($analogValue, $row['use_humidity']);
                                                if ($row['use_humidity'] != '0') {
                                                    $temp4 = $this->gethumidity($analogValue);
                                                } else {
                                                    $temp_coversion->rawtemp = $analogValue;
                                                    $temp4 = getTempUtil($temp_coversion);
                                                }
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
                                                //$temp3 = $this->gettemplist($analogValue, $row['use_humidity']);
                                                if ($row['use_humidity'] != '0') {
                                                    $temp3 = $this->gethumidity($analogValue);
                                                } else {
                                                    $temp_coversion->rawtemp = $analogValue;
                                                    $temp3 = getTempUtil($temp_coversion);
                                                }
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
                                                //$temp2 = $this->gettemplist($analogValue, $row['use_humidity']);
                                                if ($row['use_humidity'] != '0') {
                                                    $temp2 = $this->gethumidity($analogValue);
                                                } else {
                                                    $temp_coversion->rawtemp = $analogValue;
                                                    $temp2 = getTempUtil($temp_coversion);
                                                }
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
                                                //$temp1 = $this->gettemplist($analogValue, $row['use_humidity']);
                                                if ($row['use_humidity'] != '0') {
                                                    $temp1 = $this->gethumidity($analogValue);
                                                } else {
                                                    $temp_coversion->rawtemp = $analogValue;
                                                    $temp1 = getTempUtil($temp_coversion);
                                                }
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
                                                //$temp1 = $this->gettemplist($analogValue, $row['use_humidity']);
                                                if ($row['use_humidity'] != '0') {
                                                    $temp1 = $this->gethumidity($analogValue);
                                                } else {
                                                    $temp_coversion->rawtemp = $analogValue;
                                                    $temp1 = getTempUtil($temp_coversion);
                                                }
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
                                $status = "2"; //orange yellow
                            } else {
                                $currentSpeed = isset($row['curspeed']) ? $row['curspeed'] : '';
                                $overspeedLimit = isset($row['overspeed_limit']) ? $row['overspeed_limit'] : '';
                                if ($currentSpeed != '' && $overspeedLimit != '' && ($currentSpeed > $overspeedLimit)) {
                                    $status = "4"; //red overspeed
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

                        $use_buzzer = $row['use_buzzer'];
                        $immobiliser = $row['use_immobiliser'];

                        if ($use_buzzer == 1 && $row['is_buzzer'] == 1) {
                            $buzzer_status = 1; //unit has buzzer
                        } elseif ($use_buzzer == 1 && $row['is_buzzer'] == 0) {
                            $buzzer_status = 0; //unit has NO buzzer
                        } else {
                            $buzzer_status = -1; //no buzzer
                        }

                        if ($row['use_immobiliser'] == 1 && $row['is_mobiliser'] == 0) {
                            //gray  green - disable
                            //Immobilizer Not Installed In Your Vehicle. * Note: For further information please contact an elixir.
                            $mobiliser_status = -1;
                        } elseif ($row['use_immobiliser'] == 1 && $row['is_mobiliser'] == 1 && $row['mobiliser_flag'] == 0) {
                            //green - on  //   Would You Wish To Start The Vehicle ?
                            $mobiliser_status = 0;
                        } elseif ($row['use_immobiliser'] == 1 && $row['is_mobiliser'] == 1 && $row['mobiliser_flag'] == 1) {
                            //red - stop
                            $mobiliser_status = 1; //stop
                        } else {
                            $mobiliser_status = -1; //no mobiliser
                        }

                        if ($row['use_freeze'] == 1 && $row['is_freeze'] == 1) {
                            $freeze_status = 1; //isfreezed
                        } elseif ($row['use_freeze'] == 1 && $row['is_freeze'] == 0) {
                            $freeze_status = 0; //isunfreezed
                        } else {
                            $freeze_status = -1; //freeze not enabled for customer
                        }

                        /*
                        Changes Made By : Pratik Raut
                        Change : Added Paramater isRoute => routeDirection
                        Date : 16-09-2019
                         */

                        $userInRouteConstant = false;
                        if (in_array($customerno, explode(',', speedConstants::ROUTE_DASHBOARD_CUSTNO))) {
                            $userInRouteConstant = true;
                        }

                        if (!empty($row['routeDirection']) && $userInRouteConstant) {
                            $vehiclemanager = new VehicleManager($customerno);
                            //$vehicledata = $vehiclemanager->getvehiclesforrtdwithpagination();
                            $arrTemp = array();
                            $arrTemp['customerno'] = $customerno;
                            $arrTemp['userid'] = $userId;
                            $arrTemp['groupid'] = $groupidparam;
                            $vehicledata['vehicleData'] = $vehiclemanager->getvehiclesforrtdwithpagination(null, $arrTemp);

                            foreach ($vehicledata['vehicleData'] as $key => $vehicle) {
                                if ($vehicle->vehicleid == $row['vehicleid'] && $row['vehicleno'] == $vehicle->vehicleno) {
                                    if (isset($vehicle->routeDirection)) {
                                        if (!empty($vehicle->routeDirection)) {
                                            $cronManagerObj = new CronManager();
                                            $arrCheckpoints = $cronManagerObj->checkVehicleExistsInRoute($vehicle->checkpointId, $vehicle->customerno, $vehicle->vehicleid);
                                            if (isset($arrCheckpoints) && !empty($arrCheckpoints)) {
                                                $arrRouteCheckpoints = array();
                                                //echo $arrCheckpoints[0]->rmid;
                                                $routeManagerObj = new RouteManager($vehicle->customerno);
                                                //print_r($routeManagerObj);
                                                $arrRouteCheckpoints = $routeManagerObj->getchksforroute($arrCheckpoints[0]->routeid);
                                                //print_r($arrRouteCheckpoints);
                                                if (!empty($arrRouteCheckpoints)) {
                                                    $firstChk = reset($arrRouteCheckpoints);
                                                    $lastChk = end($arrRouteCheckpoints);
                                                }

                                                $json_p[$x]['isRoute'] = '1';
                                                $json_p[$x]['Route']['From'] = $firstChk->cname;
                                                $json_p[$x]['Route']['To'] = $lastChk->cname;

                                                if (empty($json_p[$x]['Route']['direction'])) {
                                                    $json_p[$x]['Route']['direction'] = $vehicle->routeDirection;
                                                }
                                            }
                                        }
                                    } else {
                                        $json_p[$x]['isRoute'] = '0';
                                    }
                                }
                            }
                        } else {
                            $json_p[$x]['isRoute'] = '0';
                        }
                        if (empty($json_p[$x]['isRoute'])) {
                            $json_p[$x]['isRoute'] = '0';
                        }

                        /*
                        changes Ends Here
                         */
                        $json_p[$x]['buzzerstatus'] = $buzzer_status;
                        $json_p[$x]['mobilizerstatus'] = $mobiliser_status;
                        $json_p[$x]['freezestatus'] = $freeze_status;

                        $json_p[$x]['temp1'] = $temp1;
                        $json_p[$x]['temp2'] = $temp2;
                        $json_p[$x]['temp3'] = $temp3;
                        $json_p[$x]['temp4'] = $temp4;
                        $json_p[$x]['temp_sensors'] = $tempsensor;
                        $json_p[$x]['vehicle_color'] = $status;
                        $json_p[$x]['powercut'] = $row['powercut'];
                        $json_p[$x]['vehicle_speed'] = isset($row['curspeed']) ? $row['curspeed'] : '';
                        $json_p[$x]['driver_name'] = isset($row['drivername']) ? $row['drivername'] : '';
                        $json_p[$x]['devlat'] = $row['devicelat'];
                        $json_p[$x]['devlong'] = $row['devicelong'];
                        $json_p[$x]['devdir'] = isset($row['directionchange']) ? $row['directionchange'] : 0;
                        $json_p[$x]['userkey'] = $userkey;
                        $thresholdltr = ($row['fuelcapacity'] / 100) * $fuel_alert_percentage;
                        $json_p[$x]['use_fuel_sensor'] = $use_fuel_sensor;
                        $json_p[$x]['fuelsensor'] = $row['fuelsensor'];
                        $json_p[$x]['fuel_alert_percentage'] = $fuel_alert_percentage;
                        $json_p[$x]['thresholdltr'] = $thresholdltr;
                        $json_p[$x]['totaltankcapacity_ltr'] = $row['fuelcapacity'];
                        $json_p[$x]['current_fuel_ltr'] = $row['fuel_balance'];
                        
                        //<editor-fold defaultstate="collapsed" desc="AC Sensor">
                        if ($use_ac_sensor == 1 || $use_genset_sensor == 1) {
                            if ($row['acsensor'] == 1) {
                                if ($row['digitalioupdated'] != '0000-00-00 00:00:00') {
                                    $digitaldiff = $this->getduration_digitalio($row['digitalioupdated'], date('Y-m-d H:i:s', strtotime($row['lastupdated'])));
                                }
                                if ($customerno == 528) {
                                    $arrAcOn = explode(',', speedConstants::IS_AC_ON);
                                    if (in_array($row['digitalio'], $arrAcOn)) {
                                        $acsensor = 1;
                                    } else {
                                        $acsensor = 0;
                                    }
                                } else {
                                    if ($row['digitalio'] == 0) {
                                        if ($row['is_ac_opp'] == 0) {
                                            $acsensor = 1;
                                        } else {
                                            $acsensor = 0;
                                        }
                                    } elseif ($row['is_ac_opp'] == 0) {
                                        $acsensor = 0;
                                    } else {
                                        $acsensor = 1;
                                    }
                                }
                            } else {
                                $acsensor = "NA";
                            }
                            $json_p[$x]['acStatus'] = $acsensor;
                        } else {
                            $json_p[$x]['acStatus'] = 'NA';
                        }
                        //</editor-fold>
                        
                        //<editor-fold defaultstate="collapsed" desc="Door Sensor">
                        if ($use_door_sensor) {
                            if ($row['doorsensor'] == 1) {
                                $digitaldiff = 'NA';
//                            if ($customerno == 528) {
//                                $arrDoor1 = explode(',', speedConstants::IS_DOOR_1);
//                                $arrDoor2 = explode(',', speedConstants::IS_DOOR_2);
//                                $door1String = '';
//                                $door2String = '';
//                                $doorString = '';
//                                $door1diff = $this->getduration_digitalio($row['door_digitalioupdated'], $row['lastupdated']->format('Y-m-d H:i:s'));
//                                $door2diff = $this->getduration_digitalio($row['extra_digitalioupdated'], $row['lastupdated']->format('Y-m-d H:i:s'));
//                                if (in_array($row['digitalio'], $arrDoor1)) {
//                                    $door1String = speedConstants::CUST_528_DOOR_BIG . " Open<br/>since $door1diff";
//                                } else {
//                                    $door1String = speedConstants::CUST_528_DOOR_BIG . " Closed<br/>since $door1diff";
//                                }
//                                if (in_array($row['digitalio'], $arrDoor2)) {
//                                    $door2String = speedConstants::CUST_528_DOOR_SMALL . " Open<br/>since $door2diff";
//                                } else {
//                                    $door2String = speedConstants::CUST_528_DOOR_SMALL . " Closed<br/>since $door2diff";
//                                }
//                                $digitaldiff = $door1String . " " . $door2String;
//                            } else
                                if ($row['isDoorExt'] == 1) {
                                    if ($row['door_digitalioupdated'] != '0000-00-00 00:00:00') {
                                        $digitaldiff = $this->getduration_digitalio($row['door_digitalioupdated'], date('Y-m-d H:i:s', strtotime($row['lastupdated'])));
                                        // $row['lastupdated->format('Y-m-d H:i:s')
                                        if ($row['door_digitalio'] == 1) {
                                            $digitaldiff = 1;
                                        } else {
                                            $digitaldiff = 0;
                                        }
                                    }
                                } elseif ($row['door_digitalioupdated'] != '0000-00-00 00:00:00') {
                                    $digitaldiff = $this->getduration_digitalio($row['door_digitalioupdated'], date('Y-m-d H:i:s', strtotime($row['lastupdated'])));
                                    $door_status = door_status($row['is_door_opp'], $row['digitalio']);
                                    if (!$door_status) {
                                        $digitaldiff = 1;
                                    } else {
                                        $digitaldiff = 0;
                                    }
                                }
                            } else {
                                $digitaldiff = 'NA';
                            }
                            /* Shortcut for image to redirect on door senor report starts here */
//                            $doorSensorReportShortcut = "<br><a href='" . BASE_PATH . "/modules/reports/reports.php?id=39&STdate=" . date('d-m-Y') . "&EDdate=" . date('d-m-Y') . "&deviceid=" . $row['deviceid'] . "&vehicleno=" . $row['vehicleno'] . "' target='_blank'><img title='Door Sensor Report'  src='../../images/temp_report.png' width='15' height='15'></a>";
                            /* Shortcut for image to redirect on door senor report ends here */
                            $json_p[$x]['doorStatus'] = $digitaldiff;
                        } else {
                            $json_p[$x]['doorStatus'] = 'NA';
                        }
                        //</editor-fold>
                                                
                        $json_p[$x]['customerno'] = $customerno;
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

    public function device_list_wh($userkey, $pageIndex, $pageSize, $searchstring, $groupidparam) {
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
                if ($customerno == 97) {
                    date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
                }
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
                //. ",'" . $userkey . "'"
                 . "," . $isWareHouse . ""
                    . ",'" . $searchstring . "'"
                    . ",'" . $groupidparam . "'"
                ;
                $queryCallSP = "CALL " . speedConstants::SP_GET_VEHICLEWAREHOUSE_DETAILS . "($sp_params)";

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
                                            $temp4 = $this->gettemplist($analogValue, $row['use_humidity']);
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
                                            $temp3 = $this->gettemplist($analogValue, $row['use_humidity']);
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
                                            $temp2 = $this->gettemplist($analogValue, $row['use_humidity']);
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
                    $json_p[$x]['devlat'] = $row['devicelat'];
                    $json_p[$x]['devlong'] = $row['devicelong'];
                    $json_p[$x]['devdir'] = isset($row['directionchange']) ? $row['directionchange'] : 0;
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

    public function device_list_details($userkey, $vehicleid) {
        $temp_coversion = new TempConversion();
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
                if ($customerno == 97) {
                    date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
                }
                $sql = "SELECT  unit.humidity
                                , vehicle.temp1_min
                                , unit.is_buzzer
                                , unit.digitalioupdated
                                , unit.extra_digitalioupdated
                                ,unit.is_freeze
                                ,unit.mobiliser_flag
                                ,unit.is_mobiliser
                                ,unit.get_conversion
                                , customer.use_buzzer
                                , customer.use_freeze
                                ,customer.use_immobiliser
                                , vehicle.temp1_max
                                , vehicle.temp2_min
                                , vehicle.temp2_max
                                , vehicle.kind
                                , vehicle.groupid
                                , unit.tempsen1
                                , unit.tempsen2
                                , unit.tempsen3
                                , unit.tempsen4
                                , unit.analog1
                                , unit.analog2
                                , unit.analog3
                                , unit.analog4
                                , customer.temp_sensors
                                , vehicle.stoppage_transit_time
                                , vehicle.stoppage_flag
                                , customer.use_geolocation
                                , user.customerno as customer_no
                                , vehicle.vehicleid
                                , vehicle.overspeed_limit
                                , vehicle.extbatt
                                , vehicle.vehicleno
                                , vehicle.curspeed
                                , unit.unitno
                                , unit.digitalio
                                , unit.acsensor 
                                , unit.doorsensor 
                                , unit.door_digitalio
                                , unit.door_digitalioupdated 
                                , unit.is_ac_opp
                                , driver.drivername
                                , driver.driverphone
                                , devices.lastupdated
                                , devices.ignition
                                , devices.powercut
                                , devices.gsmstrength
                                , devices.devicelat
                                , devices.devicelong
                                , ignitionalert.status as igstatus
                                , ignitionalert.ignchgtime
                                , vehicle.odometer as vehicleodometer
                                , devices.directionchange
                                , COALESCE(tripdetails.statusdate
                                , tripdetail_history.statusdate) as loadingtime
                                , COALESCE(tripdetails.odometer
                                , tripdetail_history.odometer) as loadingodometer
                                , unit.isDoorExt
                                , unit.is_door_opp
                        FROM    vehicle
                        INNER JOIN devices ON devices.uid = vehicle.uid
                        INNER JOIN driver ON driver.driverid = vehicle.driverid
                        INNER JOIN unit ON devices.uid = unit.uid
                        INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno
                        INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                        INNER JOIN user ON vehicle.customerno = user.customerno
                        LEFT OUTER JOIN tripdetails ON vehicle.vehicleid = tripdetails.vehicleid AND tripdetails.tripstatusid = 3 AND tripdetails.is_tripend = 0
                        LEFT OUTER JOIN tripdetail_history ON tripdetails.vehicleid = tripdetail_history.vehicleid AND tripdetails.tripid = tripdetail_history.tripid AND tripdetail_history.tripstatusid = 3 AND tripdetail_history.is_tripend = 0
                        WHERE   vehicle.customerno = " . $customerno . "
                        AND     user.userkey = " . $userkey . "
                        AND     unit.trans_statusid NOT IN (10,22)
                        AND     vehicle.isdeleted=0
                        AND     driver.isdeleted=0
                        AND     vehicle.vehicleid = " . $vehicleid . "
                        ORDER BY devices.lastupdated DESC";
                $record = $this->db->query($sql, __FILE__, __LINE__);
                $json_p = array();
                $x = 0;
                while ($row = $this->db->fetch_array($record)) {
                    $status = "";
                    $ServerIST_less1 = new DateTime();
                    $ServerIST_less1->modify('-60 minutes');
                    $lastupdated = new DateTime($row['lastupdated']);
                    $kind = 0;
                    if ($row['kind'] == "Car") {
                        $kind = 1;
                    } elseif ($row['kind'] == "Truck") {
                        $kind = 2;
                    } elseif ($row['kind'] == "Bus") {
                        $kind = 3;
                    }
                    $json_p[$x]['checkpoints'] = $this->get_checkpoints($row['vehicleid']);
                    if (isset($row['lastupdated']) && $row['lastupdated'] != '0000-00-00 00:00:00' && $row['lastupdated'] != '') {
                        $json_p[$x]['lastupdated'] = $this->getduration1($row['lastupdated']);
                    } else {
                        $json_p[$x]['lastupdated'] = '';
                    }
                    $json_p[$x]['vehicleid'] = $row['vehicleid'];
                    $json_p[$x]['vehicleno'] = $row['vehicleno'];
                    $json_p[$x]['groupid'] = $row['groupid'];
                    $json_p[$x]['devlat'] = $row['devicelat'];
                    $json_p[$x]['devlong'] = $row['devicelong'];
                    $json_p[$x]['devdir'] = $row['directionchange'];
                    $json_p[$x]['kind'] = $kind;
                    $json_p[$x]['location'] = $this->location($row['devicelat'], $row['devicelong'], $row['use_geolocation'], $row['customer_no']);
                    $json_p[$x]['unitno'] = $row['unitno'];
                    $json_p[$x]['drivername'] = $row['drivername'];
                    $diff = $this->getduration(date('Y-m-d H:i:s'), $row["stoppage_transit_time"]);
                    $temp_coversion->unit_type = $row['get_conversion'];
                    if ($row["stoppage_flag"] == '1') {
                        $json_p[$x]['ignstatus'] = "Running since $diff";
                    } elseif ($row["stoppage_flag"] == '0') {
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
                    
                    if ($row['doorsensor'] == 1) {
                        $digitaldiff = 'NA';
                        if ($customerno == 528) {
                            $arrDoor1 = explode(',', speedConstants::IS_DOOR_1);
                            $arrDoor2 = explode(',', speedConstants::IS_DOOR_2);
                            $door1String = '';
                            $door2String = '';
                            $doorString = '';
                            $door1diff = $this->getduration_digitalio($row['door_digitalioupdated'], $row['lastupdated']->format('Y-m-d H:i:s'));
                            $door2diff = $this->getduration_digitalio($row['extra_digitalioupdated'], $row['lastupdated']->format('Y-m-d H:i:s'));
                            if (in_array($row['digitalio'], $arrDoor1)) {
                                $door1String = speedConstants::CUST_528_DOOR_BIG . " Open since $door1diff";
                            } else {
                                $door1String = speedConstants::CUST_528_DOOR_BIG . " Closed since $door1diff";
                            }
                            if (in_array($row['digitalio'], $arrDoor2)) {
                                $door2String = speedConstants::CUST_528_DOOR_SMALL . " Open since $door2diff";
                            } else {
                                $door2String = speedConstants::CUST_528_DOOR_SMALL . " Closed since $door2diff";
                            }
                            $digitaldiff = $door1String . " " . $door2String;
                        } elseif ($row['isDoorExt'] == 1) {
                            if ($row['door_digitalioupdated'] != '0000-00-00 00:00:00') {
                                $digitaldiff = $this->getduration_digitalio($row['door_digitalioupdated'], date('Y-m-d H:i:s', strtotime($row['lastupdated'])));
                                // $row['lastupdated->format('Y-m-d H:i:s')
                                if ($row['door_digitalio'] == 1) {
                                    $digitaldiff = "Open since $digitaldiff";
                                } else {
                                    $digitaldiff = "Closed since $digitaldiff";
                                }
                            }
                        } elseif ($row['door_digitalioupdated'] != '0000-00-00 00:00:00') {
                            $digitaldiff = $this->getduration_digitalio($row['door_digitalioupdated'], date('Y-m-d H:i:s', strtotime($row['lastupdated'])));
                            $door_status = door_status($row['is_door_opp'], $row['digitalio']);
                            if (!$door_status) {
                                $digitaldiff = "Open since $digitaldiff";
                            } else {
                                $digitaldiff = "Closed since $digitaldiff";
                            }
                        }

                        /* Shortcut for image to redirect on door senor report starts here */

//                            $doorSensorReportShortcut = "<br><a href='" . BASE_PATH . "/modules/reports/reports.php?id=39&STdate=" . date('d-m-Y') . "&EDdate=" . date('d-m-Y') . "&deviceid=" . $row['deviceid'] . "&vehicleno=" . $row['vehicleno'] . "' target='_blank'><img title='Door Sensor Report'  src='../../images/temp_report.png' width='15' height='15'></a>";

                        /* Shortcut for image to redirect on door senor report ends here */
                        $json_p[$x]['door_status'] = $digitaldiff;
                    }  else {
                        $json_p[$x]['door_status'] = 'NA';
                    }
                    
                    
                    $json_p[$x]['cust_digital'] = $this->getCustomizeName($customerno, 1, 'Digital');
                    if (isset($row['extbatt']) && $row['extbatt'] != '') {
                        $json_p[$x]['extbatt'] = round($row['extbatt'] / 100, 2);
                    } else {
                        $json_p[$x]['extbatt'] = '';
                    }

                    $json_p[$x]['powercut'] = $row['powercut'];
                    if (isset($row['gsmstrength']) && $row['gsmstrength'] != '') {
                        $json_p[$x]['simsignal'] = round($row['gsmstrength'] / 31 * 100);
                    } else {
                        $json_p[$x]['simsignal'] = '';
                    }
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
                        if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0') {
                            if ($row['humidity'] != '0') {
                                $json_p[$x]['analog1'] = $this->gethumidity($row['analog' . $row['tempsen1']]);
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            } else {
                                $temp_coversion->rawtemp = $row['analog' . $row['tempsen1']];
                                $json_p[$x]['analog1'] = getTempUtil($temp_coversion);
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            }
                        } else {
                            $json_p[$x]['analog1'] = 'Error';
                        }
                        $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                    }

                    if ($row['temp_sensors'] == '2') {
                        if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0') {
                            if ($row['humidity'] != '0') {
                                $json_p[$x]['analog1'] = $this->gethumidity($row['analog' . $row['tempsen1']]);
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            } else {
                                $temp_coversion->rawtemp = $row['analog' . $row['tempsen1']];
                                $json_p[$x]['analog1'] = getTempUtil($temp_coversion);
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            }
                        } else {
                            $json_p[$x]['analog1'] = 'Error';
                        }
                        $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');

                        if ($row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0') {
                            $temp_coversion->rawtemp = $row['analog' . $row['tempsen2']];
                            $json_p[$x]['analog2'] = getTempUtil($temp_coversion);
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
                            $temp_coversion->rawtemp = $row['analog' . $row['tempsen1']];
                            $json_p[$x]['analog1'] = getTempUtil($temp_coversion);
                            $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                        } else {
                            $json_p[$x]['analog1'] = 'Error';
                            $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                        }
                        if ($row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0') {
                            $temp_coversion->rawtemp = $row['analog' . $row['tempsen2']];
                            $json_p[$x]['analog2'] = getTempUtil($temp_coversion);
                            $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                        } else {
                            $json_p[$x]['analog2'] = 'Error';
                            $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                        }
                        if ($row['tempsen3'] != '0' && $row['analog' . $row['tempsen3']] != '0') {
                            $temp_coversion->rawtemp = $row['analog' . $row['tempsen3']];
                            $json_p[$x]['analog3'] = getTempUtil($temp_coversion);
                            $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 22, 'Analog3');
                        } else {
                            $json_p[$x]['analog3'] = 'Error';
                            $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 22, 'Analog3');
                        }
                    }

                    if (isset($row['temp_sensors']) && $row['temp_sensors'] == 4) {
                        if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0') {
                            $temp_coversion->rawtemp = $row['analog' . $row['tempsen1']];
                            $json_p[$x]['analog1'] = getTempUtil($temp_coversion);
                            $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                        } else {
                            $json_p[$x]['analog1'] = 'Error';
                            $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                        }

                        if ($row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0') {
                            $temp_coversion->rawtemp = $row['analog' . $row['tempsen2']];
                            $json_p[$x]['analog2'] = getTempUtil($temp_coversion);
                            $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                        } else {
                            $json_p[$x]['analog2'] = 'Error';
                            $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                        }

                        if ($row['tempsen3'] != '0' && $row['analog' . $row['tempsen3']] != '0') {
                            $temp_coversion->rawtemp = $row['analog' . $row['tempsen3']];
                            $json_p[$x]['analog3'] = getTempUtil($temp_coversion);
                            $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 2, 'Analog3');
                        } else {
                            $json_p[$x]['analog3'] = 'Error';
                            $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 22, 'Analog3');
                        }

                        if ($row['tempsen4'] != '0' && $row['analog' . $row['tempsen4']] != '0') {
                            $temp_coversion->rawtemp = $row['analog' . $row['tempsen4']];
                            $json_p[$x]['analog4'] = getTempUtil($temp_coversion);
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
                        $buzzer_status = 1; //unit has buzzer
                    } elseif ($use_buzzer == 1 && $row['is_buzzer'] == 0) {
                        $buzzer_status = 0; //unit has NO buzzer
                    } else {
                        $buzzer_status = -1; //no buzzer
                    }

                    if ($row['use_immobiliser'] == 1 && $row['is_mobiliser'] == 0) {
                        //gray  green - disable
                        //Immobilizer Not Installed In Your Vehicle. * Note: For further information please contact an elixir.
                        $mobiliser_status = -1;
                    } elseif ($row['use_immobiliser'] == 1 && $row['is_mobiliser'] == 1 && $row['mobiliser_flag'] == 0) {
                        //green - on  //   Would You Wish To Start The Vehicle ?
                        $mobiliser_status = 0;
                    } elseif ($row['use_immobiliser'] == 1 && $row['is_mobiliser'] == 1 && $row['mobiliser_flag'] == 1) {
                        //red - stop
                        $mobiliser_status = 1; //stop
                    } else {
                        $mobiliser_status = -1; //no mobiliser
                    }

                    if ($row['use_freeze'] == 1 && $row['is_freeze'] == 1) {
                        $freeze_status = 1; //isfreezed
                    } elseif ($row['use_freeze'] == 1 && $row['is_freeze'] == 0) {
                        $freeze_status = 0; //isunfreezed
                    } else {
                        $freeze_status = -1; //freeze not enabled for customer
                    }

                    /* Vehicle Color */
                    if ($lastupdated < $ServerIST_less1 || !isset($row['lastupdated']) || $row['lastupdated'] == '0000-00-00 00:00:00' || $row['lastupdated'] == '') {
                        $status = "1"; //inactive grey
                    } elseif ($row['ignition'] == '0') {
                        $status = "2"; //orange yellow
                    } else {
                        $currentSpeed = isset($row['curspeed']) ? $row['curspeed'] : '';
                        $overspeedLimit = isset($row['overspeed_limit']) ? $row['overspeed_limit'] : '';
                        if ($currentSpeed != '' && $overspeedLimit != '' && ($currentSpeed > $overspeedLimit)) {
                            $status = "4"; //red overspeed
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

                    $json_p[$x]['buzzerstatus'] = $buzzer_status;
                    $json_p[$x]['mobilizerstatus'] = $mobiliser_status;
                    $json_p[$x]['freezestatus'] = $freeze_status;
                    $json_p[$x]['vehicle_color'] = $status;

                    ///use buzzer  or use mobilizer  - end
                    $tripdata = $this->gettripdetails($row['vehicleid'], $customerno);
                    /*$json_p[$x]['temprange'] = 'Not Defined';
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
                    $json_p[$x]['loadingtime'] = 'Not Defined';*/

                    $json_p[$x]['temprange'] = 'Not Available';
                    $json_p[$x]['triplogno'] = 'Not Available';
                    $json_p[$x]['status'] = 'Not Available';
                    $json_p[$x]['buddist'] = 'Not Available';
                    $json_p[$x]['budhours'] = 'Not Available';
                    $json_p[$x]['actualhrs'] = 'Not Available';
                    $json_p[$x]['actualkms'] = 'Not Available';
                    $json_p[$x]['consignor'] = 'Not Available';
                    $json_p[$x]['consignee'] = 'Not Available';
                    $json_p[$x]['billingparty'] = 'Not Available';
                    $json_p[$x]['tripdrivername'] = 'Not Available';
                    $json_p[$x]['tripdriverno'] = 'Not Available';
                    $json_p[$x]['routename'] = 'Not Available';
                    $json_p[$x]['remark'] = 'Not Available';
                    $json_p[$x]['loadingtime'] = 'Not Available';

                    if (isset($tripdata)) {
                        if (isset($tripdata['is_tripend']) && $tripdata['is_tripend'] == 0) {
                            $closedtripenddetails = $this->closedtripdetails_end($tripdata['tripid'], $customerno);
                            $closedtripstartdetails = $this->closedtripdetails_start($tripdata['tripid'], $customerno);
                            $tripstart_date = $closedtripstartdetails[0]['tripstart_date'];
                            $tripend_date = $closedtripenddetails[0]['tripend_date'];
                            if (empty($tripend_date) && $tripend_date == "") {
                                $tripend_date = date('Y-m-d');
                            } else {
                                $tripend_date = $closedtripenddetails[0]['tripend_date'];
                            }
                            if (!empty($tripstart_date) && !empty($tripend_date)) {
                                $getstart_odometer = $getend_odometer = '';
                                $startododate = trim(substr($tripstart_date, 0, 11));
                                $unitno = $this->getunitno($tripdata['vehicleid']);
                                $strlocation = "../../../customer/$customerno/unitno/$unitno/sqlite/$startododate.sqlite";
                                $getstart_odometer = $this->getOdometer($strlocation, $tripstart_date);

                                $endododate = trim(substr($tripend_date, 0, 11));
                                $endlocation = "../../../customer/$customerno/unitno/$unitno/sqlite/$endododate.sqlite";
                                $today = date('Y-m-d');
                                if (strtotime($tripend_date) == strtotime($today)) {
                                    $getend_odometer = $this->getodometerform_mysql($tripdata['vehicleid'], $customerno);
                                } else {
                                    $getend_odometer = $this->getOdometer($endlocation, $tripend_date);
                                }
                                $tripend_odometer = 0;
                                $tripstart_odometer = 0;
                                $firstodometer = 0;
                                $lastodometer = 0;
                                $lastodometermax = 0;
                                $lastodometer = $getend_odometer; // last odometer
                                $firstodometer = $getstart_odometer; // first odometer
                                if ($lastodometer < $firstodometer) {
                                    $days = $this->gendays($tripstart_date, $tripend_date);
                                    if (count($days) > 0) {
                                        $lastodometerarr = array();
                                        foreach ($days as $day) {
                                            $lastodometerarr[] = $this->GetOdometer_Max($day, $unitno, $customerno);
                                        }
                                        $lastodometermax = max($lastodometerarr);
                                    }
                                    $lastodometer = $lastodometermax + $lastodometer;
                                }
                                $totaldistance = $lastodometer - $firstodometer;
                                $actualhrs = round((strtotime($tripstart_date) - strtotime($tripend_date)) / (60 * 60));
                                if ($totaldistance != 0) {
                                    $res = $totaldistance / 1000;
                                } else {
                                    $res = 0;
                                }

                                ////////////////Estimated Time calculate///////////////////////
                                $estimated_time = 0;
                                $estimated_time = intval($tripdata['budgetedhrs']) - intval($actualhrs);
                                ////////////////Estimated Time calculate///////////////////////

                                $json_p[$x]['temprange'] = $tripdata['temprange'];
                                $json_p[$x]['triplogno'] = $tripdata['triplogno'];
                                $json_p[$x]['status'] = $tripdata['status'];
                                $actualhrs = $estimated_time;
                                $actualkms = $res;
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
                                $json_p[$x]['loadingtime'] = ($row['loadingtime'] != null && $row['loadingtime'] != '') ? date(speedConstants::DEFAULT_DATETIME, strtotime($row['loadingtime'])) : 'Not Defined';
                            }
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

    public function device_list_details_wh($userkey, $vehicleid) {
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
                if ($customerno == 97) {
                    date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
                }
                $sql = "SELECT unit.humidity, vehicle.temp1_min,unit.is_buzzer,unit.digitalioupdated
                ,unit.extra_digitalioupdated,unit.is_freeze,unit.mobiliser_flag,unit.is_mobiliser,unit.get_conversion
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
                INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno
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
                    $temp_coversion->unit_type = $row['get_conversion'];
                    if (isset($row['temp_sensors']) && isset($row['humidity'])) {
                        if ($row['temp_sensors'] == '1') {
                            if (isset($row['tempsen1']) && $row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0') {
                                if ($row['humidity'] != '0') {
                                    $json_p[$x]['analog1'] = $this->gethumidity($row['analog' . $row['tempsen1']]);
                                    $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                                } else {
                                    $temp_coversion->rawtemp = $row['analog' . $row['tempsen1']];
                                    $json_p[$x]['analog1'] = getTempUtil($temp_coversion);
                                    $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                                }
                            } else {
                                $json_p[$x]['analog1'] = 'Error';
                            }
                            $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                        }

                        if ($row['temp_sensors'] == '2') {
                            if (isset($row['tempsen1']) && $row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0') {
                                if ($row['humidity'] != '0') {
                                    $json_p[$x]['analog1'] = $this->gethumidity($row['analog' . $row['tempsen1']]);
                                    $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                                } else {
                                    $temp_coversion->rawtemp = $row['analog' . $row['tempsen1']];
                                    $json_p[$x]['analog1'] = getTempUtil($temp_coversion);
                                    $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                                }
                            } else {
                                $json_p[$x]['analog1'] = 'Error';
                            }

                            $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');

                            if (isset($row['tempsen2']) && $row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0') {
                                $temp_coversion->rawtemp = $row['analog' . $row['tempsen2']];
                                $json_p[$x]['analog2'] = getTempUtil($temp_coversion);
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
                                $temp_coversion->rawtemp = $row['analog' . $row['tempsen1']];
                                $json_p[$x]['analog1'] = getTempUtil($temp_coversion);
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            } else {
                                $json_p[$x]['analog1'] = 'Error';
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            }
                            if (isset($row['tempsen2']) && $row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0') {
                                $temp_coversion->rawtemp = $row['analog' . $row['tempsen2']];
                                $json_p[$x]['analog2'] = getTempUtil($temp_coversion);
                                $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                            } else {
                                $json_p[$x]['analog2'] = 'Error';
                                $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                            }
                            if (isset($row['tempsen3']) && $row['tempsen3'] != '0' && $row['analog' . $row['tempsen3']] != '0') {
                                $temp_coversion->rawtemp = $row['analog' . $row['tempsen3']];
                                $json_p[$x]['analog3'] = getTempUtil($temp_coversion);
                                $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 22, 'Analog3');
                            } else {
                                $json_p[$x]['analog3'] = 'Error';
                                $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 22, 'Analog3');
                            }
                        }

                        if ($row['temp_sensors'] == '4') {
                            if (isset($row['tempsen1']) && $row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0') {
                                $temp_coversion->rawtemp = $row['analog' . $row['tempsen1']];
                                $json_p[$x]['analog1'] = getTempUtil($temp_coversion);
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            } else {
                                $json_p[$x]['analog1'] = 'Error';
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            }

                            if (isset($row['tempsen2']) && $row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0') {
                                $temp_coversion->rawtemp = $row['analog' . $row['tempsen2']];
                                $json_p[$x]['analog2'] = getTempUtil($temp_coversion);
                                $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                            } else {
                                $json_p[$x]['analog2'] = 'Error';
                                $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                            }

                            if (isset($row['tempsen3']) && $row['tempsen3'] != '0' && $row['analog' . $row['tempsen3']] != '0') {
                                $temp_coversion->rawtemp = $row['analog' . $row['tempsen3']];
                                $json_p[$x]['analog3'] = getTempUtil($temp_coversion);
                                $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 2, 'Analog3');
                            } else {
                                $json_p[$x]['analog3'] = 'Error';
                                $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 22, 'Analog3');
                            }

                            if (isset($row['tempsen4']) && $row['tempsen4'] != '0' && $row['analog' . $row['tempsen4']] != '0') {
                                $temp_coversion->rawtemp = $row['analog' . $row['tempsen4']];
                                $json_p[$x]['analog4'] = getTempUtil($temp_coversion);
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

    public function pushbuzzer($userkey, $vehicleid, $status) {
        //buzzer status =1 on // buzzer status==0 off  //buzzerstatus ==-1
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        if ($validation['status'] == "successful") {
            $arr_p['status'] = 'successful';
            $customerno = $validation["customerno"];
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
            $userid = $validation["userid"];
            if ($status == 1) {
                //Do You Like To Alarm The Vehicle ?
                //send alert
                $query = "select u.unitno from vehicle as v inner join unit as u on v.uid = u.uid  where v.vehicleid =" . $vehicleid . " AND v.isdeleted=0";
                $record = $this->db->query($query, __FILE__, __LINE__);
                while ($row = $this->db->fetch_array($record)) {
                    $unitno = $row['unitno'];
                }
                if (isset($unitno) && $unitno != "") {
                    $Que = "UPDATE unit SET  setcom = 1, command='buzz' WHERE unitno='" . $unitno . "' AND customerno=" . $customerno;
                    $record1 = $this->db->query($Que, __FILE__, __LINE__);
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

    public function pushmobiliser($userkey, $vehicleid, $status) {
        //status  0=>mobiliser off // 1=>on  // 2 => stop // -1 ->not mobiliser
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        if ($validation['status'] == "successful") {
            $arr_p['status'] = 'successful';
            $customerno = $validation["customerno"];
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
            $userid = $validation["userid"];
            $query = "select u.unitno from vehicle as v inner join unit as u on v.uid = u.uid  where v.vehicleid =" . $vehicleid . " AND  v.isdeleted=0";
            $record = $this->db->query($query, __FILE__, __LINE__);
            while ($row = $this->db->fetch_array($record)) {
                $unitno = $row['unitno'];
            }
            if (isset($unitno) && $unitno != "") {
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

                if ($check != "") {
                    if ($command == 'STARTV') {
                        $flag = 0;
                    } else {
                        $flag = 1;
                    }
                    $Que = "UPDATE unit SET  setcom = 1, command='" . $command . "', mobiliser_flag=" . $flag . "  WHERE unitno='" . $unitno . "' AND customerno=" . $customerno;
                    $record1 = $this->db->query($Que, __FILE__, __LINE__);
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

    public function freezevehicle($userkey, $vehicleid, $fstatus) {
        //by vehicleid get last updated device lat long and insert in new table freeze or status update in unittable is_freeze
        $arr_p['status'] = "unsuccessful";
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        if ($validation['status'] == "successful") {
            $customerno = $validation["customerno"];
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
            $userid = $validation["userid"];
            $datavehdata = $this->getvehicledetail($vehicleid, $customerno);
            if (isset($datavehdata)) {
                $vehicleid_freeze = $datavehdata['vehicleid'];
                $uid_freeze = $datavehdata['uid'];
                $devicelat_freeze = $datavehdata['devicelat'];
                $devicelong_freeze = $datavehdata['devicelong'];
                $odometer = $datavehdata['odometer'];
                $arr_p['status'] = "successful";
                $today = date("Y-m-d H:i:s");

                if ($fstatus == '0') {
                    //freeze vehicle
                    $Que = "UPDATE unit set is_freeze=1 where uid = " . $uid_freeze;
                    $record1 = $this->db->query($Que, __FILE__, __LINE__);

                    $sql = "INSERT INTO freezelog (uid, vehicleid, devicelat,devicelong,odometer,customerno,createdby ,createdon,updatedby,updatedon,is_api) "
                        . "VALUES ('" . $uid_freeze . "','" . $vehicleid_freeze . "','" . $devicelat_freeze . "','" . $devicelong_freeze . "','" . $odometer . "','" . $customerno . "','" . $userid . "','" . $today . "','" . $userid . "','" . $today . "',1)";
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

    public function device_list_byname($userkey, $vehicleno) {
        $temp_coversion = new TempConversion();
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";

        if ($validation['status'] == "successful") {
            $arr_p['checkpointsman_count'] = $this->get_checkpoints_count($userkey);
            // successful
            $customerno = $validation["customerno"];
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
            $sql = "SELECT vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max, vehicle.kind, vehicle.groupid, unit.tempsen1, unit.tempsen2, unit.analog1, unit.analog2, unit.analog3, unit.analog4,unit.get_conversion,
            customer.temp_sensors, vehicle.stoppage_transit_time, vehicle.stoppage_flag, customer.use_geolocation, user.customerno as customer_no,vehicle.groupid as veh_grpid,vehicle.vehicleid, vehicle.overspeed_limit, vehicle.extbatt, vehicle.vehicleno,vehicle.curspeed,unit.unitno, unit.digitalio, unit.acsensor, unit.is_ac_opp, driver.drivername,driver.driverphone,
            devices.lastupdated, devices.ignition, devices.inbatt, devices.tamper, devices.powercut, devices.gsmstrength, devices.devicelat, devices.devicelong,
            (SELECT customname FROM `customfield` where customerno=user.customerno AND name='Digital' AND `usecustom`=1) as digital,
            ignitionalert.status as igstatus,ignitionalert.ignchgtime FROM vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno
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
                $temp_coversion->unit_type = $row['get_conversion'];
                if ($arr_p['checkpointsman_count'] == 1) {
                    $json_p[$x]['checkpoints'] = $this->get_checkpoints($row['vehicleid']);
                }
                $json_p[$x]['vehicleno'] = $row['vehicleno'];
                $json_p[$x]['kind'] = $row['kind'];
                $json_p[$x]['groupid'] = $row['groupid'];
                $json_p[$x]['location'] = $this->location($row['devicelat'], $row['devicelong'], $row['use_geolocation'], $row['customer_no']);
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
                    } elseif ($row["is_ac_opp"] == 0) {
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
                } elseif ($row["stoppage_flag"] == '0') {
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
                    if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0') {
                        $temp_coversion->rawtemp = $row['analog' . $row['tempsen1']];
                        $json_p[$x]['temp1'] = getTempUtil($temp_coversion);
                    } else {
                        $json_p[$x]['temp1'] = '0';
                    }
                }

                if ($row['temp_sensors'] == '2') {
                    if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0') {
                        $temp_coversion->rawtemp = $row['analog' . $row['tempsen1']];
                        $json_p[$x]['temp1'] = getTempUtil($temp_coversion);
                    } else {
                        $json_p[$x]['temp1'] = '0';
                    }

                    if ($row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0') {
                        $temp_coversion->rawtemp = $row['analog' . $row['tempsen2']];
                        $json_p[$x]['temp2'] = getTempUtil($temp_coversion);
                    } else {
                        $json_p[$x]['temp2'] = '0';
                    }
                }

                $x++;
            }

            $arr_p['status'] = "successful";
            $arr_p['result'] = $json_p;
            $this->update_push_android_chk($userkey, 0);
        } else {
            $arr_p['status'] = "unsuccessful";
        }

        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    public function client_code_details($ecodeid, $searchstring) {
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        $Query = "  SELECT  vehicle.overspeed_limit
                            , vehicle.stoppage_flag
                            , vehicle.vehicleno
                            , vehicle.vehicleid
                            , devices.deviceid
                            , devices.devicelat
                            , devices.devicelong
                            , driver.drivername
                            , driver.driverphone
                            , vehicle.curspeed
                            , devices.lastupdated
                            , vehicle.kind
                            , devices.ignition
                            , devices.status
                            , devices.directionchange
                            , devices.uid
                            , elixiacode.expirydate
                    FROM    `devices`
                    INNER JOIN unit ON unit.uid = devices.uid
                    INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                    INNER JOIN driver ON vehicle.driverid = driver.driverid
                    INNER JOIN ecodeman ON ecodeman.vehicleid = vehicle.vehicleid
                    INNER JOIN elixiacode ON elixiacode.id = ecodeman.ecodeid
                    where   elixiacode.ecode = '$ecodeid'
                    AND     unit.trans_statusid NOT IN (10,22)";
        if ($searchstring != '' || $searchstring != null) {
            $searchstring = "%" . $searchstring . "%";
            $Query .= " AND vehicle.vehicleno LIKE '$searchstring'";
        }
        // echo $devicesQuery = sprintf($Query);
        $record = $this->db->query($Query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);
        $json_p = array();
        $arr_p['error'] = 'Invalid Client Code';
        $x = 0;
        if ($row_count == 0 && $searchstring != '') {
            $arr_p['error'] = 'No Data Found';
        }
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
                        }

                        //status start
                        $status = "";
                        $ServerIST_less1 = new DateTime();
                        $ServerIST_less1->modify('-60 minutes');
                        $lastupdated = new DateTime($row['lastupdated']);
                        $speed_conflicted = 0;
                        if ($lastupdated < $ServerIST_less1) {
                            $status = "1"; //inactive grey
                        } else {
                            //<editor-fold defaultstate="collapsed" desc="Decide Vehicle colour">
                            if ($row['ignition'] == '0') {
                                $status = "2"; //orange yellow
                            } else {
                                $currentSpeed = isset($row['curspeed']) ? $row['curspeed'] : '';
                                $overspeedLimit = isset($row['overspeed_limit']) ? $row['overspeed_limit'] : '';
                                if ($currentSpeed != '' && $overspeedLimit != '' && ($currentSpeed > $overspeedLimit)) {
                                    $status = "4"; //red overspeed
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

    public function create_client_code($userkey, $code) {
        //print_r($code);
        $smsStatus = new SmsStatus();
        $code = json_decode($code);
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        $today = date('Y-m-d H:i:s');
        $code->menuoption = 0;
        if ($validation['status'] == "successful") {
            $ecoderandom = mt_rand(0, 999999);
            $customerno = $validation["customerno"];
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
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
                        $content = 'Your Client Code is ' . $ecoderandom . '. <br/>Generated by ' . $validation['realname'] . '. <br/>Please visit www.speed.elixiatech.com and trace your vehicle. <br/> Valid Until: ' . convertDateToFormat($code->enddate, speedConstants::DEFAULT_DATETIME);
                        $headers = "From: noreply@elixiatech.com\r\n";
                        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                        @mail($code->email, $subject, $content, $headers);
                    }

                    if ($code->sms != '') {
                        $smsStatus->customerno = $customerno;
                        $smsStatus->userid = $userid;
                        $smsStatus->vehicleid = 0;
                        $smsStatus->mobileno = array($code->sms);
                        $smsStatus->message = $smsmessage;
                        $smsStatus->cqid = 0;
                        $smscount = $customermanager->getSMSStatus($smsStatus);
                        if ($smscount == 0) {
                            $isSMSSent = 0;
                            $moduleid = 1;
                            $response = '';
                            $smsmessage = 'Client Code: ' . $ecoderandom . '. Generated by ' . $validation['realname'] . '. Validity: ' . convertDateToFormat($code->enddate, speedConstants::DEFAULT_DATETIME) . '. Link: www.speed.elixiatech.com';
                            $response = '';
                            $isSMSSent = sendSMSUtil(array($code->sms), $smsmessage, $response);
                            if ($isSMSSent == 1) {
                                $customermanager->sentSmsPostProcess($customerno, $code->sms, $smsmessage, $response, $isSMSSent, $userid, 0, $moduleid);
                            }
                        }
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

    public function register_gcm($userkey, $regid) {
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

    public function unregister_gcm($userkey) {
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

    public function summary($userkey, $vehicleid, $date) {
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        //$arr_p['status'] = "unsuccessful";
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
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

                            $Datacap = new stdClass();
                            //$Datacap->date = strtotime($date);
                            $Datacap->VehicleName = $vehicle->vehicleno;
                            $Datacap->DeviceId = $vehicle->deviceid;
                            $Datacap->DriverName = $vehicle->drivername;
                            $Datacap->Group = $this->getgroupname_byuid($rowa['uid']);
                            if (isset($rowa['lat']) && isset($rowa['long'])) {
                                $Datacap->SL = $this->location($rowa['lat'], $rowa['long'], $row['use_geolocation'], $customerno);
                            } else {
                                $Datacap->SL = "";
                            }
                            if (isset($rowa['lat']) && isset($rowa['long'])) {
                                $Datacap->EL = $this->location($rowa['lat'], $rowa['long'], $row['use_geolocation'], $customerno);
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
                                $Datacap->TSL = $this->location($rowa['topspeed_lat'], $rowa['topspeed_long'], $row['use_geolocation'], $customerno);
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
                    $arr_p['Error'] = "File Not exists";
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

                            $Datacap = new stdClass();
                            $Datacap->VehicleName = $vehicle->vehicleno;
                            $Datacap->DeviceId = $vehicle->deviceid;
                            $Datacap->DriverName = $vehicle->drivername;
                            $Datacap->Group = $this->getgroupname_byuid($row['uid']);
                            $Datacap->SL = $this->location($row['first_dev_lat'], $row['first_dev_long'], $rowlist['use_geolocation'], $customerno);
                            $Datacap->EL = $this->location($row['dev_lat'], $row['dev_long'], $rowlist['use_geolocation'], $customerno);
                            $Datacap->DT = round($row['totaldistance'] / 1000, 2) . " km";
                            $Datacap->AS = round($row['avgspeed'] / 1000, 2) . " km/hr";
                            $Datacap->RT = $hours . ":" . $minutes . " (hh:mm)";
                            if ($row['genset'] > 0) {
                                $Datacap->digital_cust = $this->getCustomizeName($customerno, 1, 'Digital');
                                $Datacap->digital = $hourss . ":" . $minutess . " (hh:mm)";
                            }
                            $Datacap->OS = $row['overspeed'];
                            $Datacap->TS = $row['topspeed'] . " km/hr";
                            $Datacap->TSL = $this->location($row['topspeed_lat'], $row['topspeed_long'], $rowlist['use_geolocation'], $customerno);
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
                    $arr_p['Error'] = "File Not exists";
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

    public function summary_wh($userkey, $vehicleid, $date) {
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
            $userid = $validation['userid'];

            $vehicle = $this->get_vehicle($vehicleid, $customerno);

            $sql = "select * from unit inner join " . DB_PARENT . ".customer on unit.customerno = customer.customerno where vehicleid='" . $vehicleid . "' AND unit.customerno= '" . $customerno . "'";
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
                    $Datacap = new stdClass();
                    $Datacap->WarehouseName = $vehicle->vehicleno;
                    $Datacap->DeviceId = $vehicle->deviceid;
                    $Datacap->useHumidity = $useHumidity;
                    $Datacap->StartTime = date(speedConstants::DEFAULT_DATETIME, strtotime($stime));
                    $Datacap->EndTime = date(speedConstants::DEFAULT_DATETIME, strtotime($etime));
                    $Datacap->Interval = $interval . " min";

                    $report = array();
                    foreach ($Data as $datarow) {
                        $reportarr['Time'] = date(speedConstants::DEFAULT_TIME, strtotime($datarow->starttime));
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

    public function contractinfo($userkey) {
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        //$arr_p['status'] = "unsuccessful";

        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            $userId = $validation['userid'];
            $roleid = $validation['roleid'];
            $userid = $validation['userid'];
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }

            $today = date('Y-m-d');
            $device = new stdClass();

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

            $info = new stdClass();
            $info->PendingAmount = $device->pending_amount;

            $devices = Array();
            $Query = "SELECT devices.deviceid, vehicle.vehicleno,devices.installdate,vehicle.vehicleid, devices.invoiceno,
		    devices.devicekey,devices.expirydate,devices.uid,devices.po_no,devices.po_date, devices.warrantyexpiry, unit.unitno,
		    registeredon,district.name as dname,city.name as cname,devices.device_invoiceno, Now() as today FROM `devices`
		    INNER JOIN unit ON unit.uid = devices.uid
		    INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid";
            if (isset($userId) && isset($roleid) && $roleid == 43) {
                $Query .= " INNER JOIN vehicleusermapping vm on vm.vehicleid = vehicle.vehicleid and vm.userid =  " . $userId . " and vm.isdeleted = 0 ";
            }
            $Query .= "LEFT JOIN `group` ON vehicle.groupid=`group`.groupid
            LEFT JOIN city ON `group`.cityid=city.cityid
            LEFT JOIN district ON city.districtid=district.districtid
            LEFT JOIN state ON district.stateid=state.stateid
            LEFT JOIN nation ON state.nationid=nation.nationid
            LEFT OUTER JOIN simcard ON devices.simcardid = simcard.id
            where devices.customerno=$customerno AND unit.trans_statusid NOT IN (10,22)";
            $um = new UserManager();
            $grouplist = $um->get_groups_fromuser($customerno, $userid);
            if (isset($grouplist)) {
                foreach ($grouplist as $group) {
                    $groups[] = $group->groupid;
                }
            } else {
                $groups[] = 0;
            }
            if ($groups[0] != 0) {
                $groupid_ids = implode(',', $groups);
                $Query .= " AND vehicle.groupid in (" . $groupid_ids . ") ";
            }
            $record12 = $this->db->query($Query, __FILE__, __LINE__);
            $row_count12 = $this->db->num_rows($record12);
            $today = date("Y-m-d");
            $month = date("Y-m-d", strtotime($today . '+30 days'));
            if ($row_count12 > 0) {
                while ($row = $this->db->fetch_array($record12)) {
                    $device1 = new stdClass();
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

    public function dashboard($userkey) {
        $validation = $this->check_userkey($userkey);
        //print_r($validation);
        $arr_p = array();
        $info = array();
        $devicelist = array();
        if ($validation['status'] == "successful") {
            $userId = $validation['userid'];
            $roleid = $validation['roleid'];
            $customRoleCheck = false;
            if ($validation['role'] == 'Custom') {
                $customRoleCheck = true;
            }
            $customerno = $validation['customerno'];
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
            $today = date('Y-m-d');
            $Query = "  SELECT  *
                        FROM    `dailyreport`
                        INNER JOIN unit ON unit.uid = dailyreport.uid
                        INNER JOIN vehicle v on v.vehicleid = dailyreport.vehicleid and v.isdeleted = 0 ";
            if (isset($userId) && isset($roleid) && $roleid == 43) {
                $Query .= "INNER JOIN vehicleusermapping vm on vm.vehicleid = v.vehicleid and vm.userid =  " . $userId . " and vm.isdeleted = 0 ";
            }
            $Query .= " WHERE   dailyreport.daily_date = '$today'
                        AND     dailyreport.customerno = $customerno
                        AND     dailyreport.vehicleid <> 0
                        AND     dailyreport.uid <> 0 ";
            $um = new UserManager();
            $grouplist = $um->get_groups_fromuser($customerno, $userId);
            if (isset($grouplist)) {
                foreach ($grouplist as $group) {
                    $groups[] = $group->groupid;
                }
            } else {
                $groups[] = 0;
            }
            if ($groups[0] != 0) {
                $groupid_ids = implode(',', $groups);
                $Query .= " AND v.groupid in (" . $groupid_ids . ") ";
            }
            $record = $this->db->query($Query, __FILE__, __LINE__);

            $row_count = $this->db->num_rows($record);
            $info_count = $row_count;
            $total_vehicles = 0;
            $total_distance = 0;
            $total_overspeedincidents = 0;
            $total_fenceconflicts = 0;
            if ($row_count > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    // $total_vehicles++;
                    $total_overspeedincidents += $row['overspeed'];
                    $total_fenceconflicts += $row['fenceconflict'];
                    $distance = $this->distance($customerno, $row['unitno']);
                    $total_distance += round($distance, 2);
                }

                $query = "SELECT topspeed_lat, topspeed_long, max(dailyreport.topspeed) as maxtopspeed,vehicle.vehicleid,vehicle.vehicleno
                            FROM `dailyreport`
                            left join vehicle on vehicle.vehicleid = dailyreport.vehicleid ";
                if (isset($userId) && isset($roleid) && $roleid == 43) {
                    $query .= " INNER JOIN vehicleusermapping vm on vm.vehicleid = vehicle.vehicleid and vm.userid =  " . $userId . " and vm.isdeleted = 0 ";
                }
                $query .= " where dailyreport.customerno=$customerno
                        and dailyreport.daily_date = '$today'
                        AND dailyreport.vehicleid <> 0
                        AND dailyreport.uid <> 0
                        AND dailyreport.topspeed = (select max(d.topspeed) from `dailyreport` d where d.customerno=$customerno and d.daily_date = '$today')
                        ";
                if ($groups[0] != 0) {
                    $groupid_ids = implode(',', $groups);
                    $query .= " AND vehicle.groupid in (" . $groupid_ids . ") ";
                }
                $query .= "ORDER BY dailyreport.topspeed DESC
                    LIMIT 1";
                $record = $this->db->query($query, __FILE__, __LINE__);
                while ($row = $this->db->fetch_array($record)) {
                    $topspeed = $row['maxtopspeed'];
                    $vehicleno = $row['vehicleno'];
                    $topspeedlat = $row['topspeed_lat'];
                    $topspeedlong = $row['topspeed_long'];
                }

                //$info['Total No Of Vehicles'] = $total_vehicles;
                $info['Total Distance Travelled'] = $total_distance . " km";
                $info['Total Overspeed Incidents'] = $total_overspeedincidents;
                $info['Total Fence Conflicts'] = $total_fenceconflicts;
                $info['Top Speed'] = $vehicleno . "(" . $topspeed . " km/hr)";
                if ($topspeedlat == 0 || $topspeedlong == 0) {
                    $info['Top Speed Location'] = "Data Not Available";
                } else {
                    $info['Top Speed Location'] = $this->location($topspeedlat, $topspeedlong, 1, $customerno);
                }
            }
            $Query = "SELECT * FROM `devices`
		    INNER JOIN unit ON unit.uid = devices.uid
		    INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
		    INNER JOIN driver ON vehicle.driverid = driver.driverid ";
            if (isset($userId) && isset($roleid) && $roleid == 43) {
                $Query .= " INNER JOIN vehicleusermapping vm on vm.vehicleid = vehicle.vehicleid and vm.userid =  " . $userId . " and vm.isdeleted = 0 ";
            }
            $Query .= " where   devices.customerno=$customerno
                        AND     unit.trans_statusid NOT IN (10,22)
                        AND     vehicle.isdeleted = 0
                        AND     vehicle.kind<>'Warehouse' ";
            if ($groups[0] != 0) {
                $groupid_ids = implode(',', $groups);
                $Query .= " AND vehicle.groupid in (" . $groupid_ids . ") ";
            }
            $Query .= " ORDER BY vehicle.vehicleno";
            $record = $this->db->query($Query, __FILE__, __LINE__);
            $row_count = $this->db->num_rows($record);
            $total_vehicles = $row_count;
            if ($row_count > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $devicelist[] = $row;
                }
                foreach ($devicelist as $row) {
                    $device = new stdClass();
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

            $i = 0;
            $j = 0;
            $k = 0;
            $l = 0;
            $m = 0;
            if (isset($devices)) {
                foreach ($devices as $device) {
                    $ServerIST_less1 = new DateTime();
                    $ServerIST_less1->modify('-60 minutes');
                    $lastupdated = new DateTime($device->lastupdated);
                    if ($lastupdated < $ServerIST_less1 || !isset($device->lastupdated)) {
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
		    INNER JOIN vehicle on vehicle.vehicleid=checkpointmanage.vehicleid ";
            if (isset($userId) && isset($roleid) && $roleid == 43) {
                $Query .= " INNER JOIN vehicleusermapping vm on vm.vehicleid = vehicle.vehicleid and vm.userid =  " . $userId . " and vm.isdeleted = 0 ";
            }
            $Query .= " WHERE checkpointmanage.customerno=$customerno AND checkpointmanage.isdeleted=0  and  checkpointmanage.conflictstatus=0 ";
            if ($groups[0] != 0) {
                $groupid_ids = implode(',', $groups);
                $Query .= " AND vehicle.groupid in (" . $groupid_ids . ") ";
            }
            $Query .= " group by checkpointmanage.checkpointid";

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
                    $inside += $chkpoint['count'];
                }
            }
            $outside = $total_vehicles - $inside;
            $chklist['Outside'] = $outside;
            $arr_p['Checkpoint Status'] = $chklist;
            $info['Total No Of Vehicles'] = $total_vehicles;

            if ($info_count > 0) {
                $arr_p['status'] = "successful";
                $arr_p['info'] = $info;
            } else {
                $arr_p['status'] = "unsuccessful";
                $arr_p['Error'] = "Data Not Available";
            }
        } else {
            $arr_p['status'] = "unsuccessful";
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    public function updateLogin($userkey, $phone, $version) {
        $today = date('Y-m-d H:i:s');
        $sql = "select * from user where userkey='" . $userkey . "' AND isdeleted = 0";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $row = $this->db->fetch_array($record);
        if ($row['userkey'] != "") {
            $userid = $row['userid'];
            $customerno = $row['customerno'];
            $sqlInsert = "insert into " . DB_PARENT . ".login_history(userid, customerno,type,timestamp,phonetype,version)"
                . "values($userid,$customerno,1,'" . $today . "','$phone','$version')";
            $this->db->query($sqlInsert, __FILE__, __LINE__);
        }
    }

    public function get_otp_forgotpwd($username) {
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
        $sqlCallSP = "CALL " . speedConstants::SP_SPEED_FORGOT_PASSWORD . "($sp_params)";
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

                if ($phone != '') {
                    $smsStatus->customerno = $customerno;
                    $smsStatus->userid = $userid;
                    $smsStatus->vehicleid = 0;
                    $smsStatus->mobileno = $phone;
                    $smsStatus->message = $message;
                    $smsStatus->cqid = 0;
                    $smscount = $customermanager->getSMSStatus($smsStatus);
                    if ($smscount == 0) {
                        $response = '';
                        $isSMSSent = sendSMSUtil($phone, $message, $response);
                        $moduleid = 1;
                        if ($isSMSSent == 1) {
                            $customermanager->sentSmsPostProcess($customerno, $phone, $message, $response, $isSMSSent, $userid, 0, $moduleid);
                            //$statusMessage = "OTP Number SMS sent successfully. " . (($smsLogId > 0) ? "SMS logged" : " SMS logging failed.");
                            $statusMessage = "OTP Number SMS sent successfully. ";
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
                        $emailMessage = "OTP Email sent successfully";
                        $statusMessage = $statusMessage != '' ? $statusMessage . ", " . $emailMessage : $emailMessage;
                    } else {
                        $emailMessage = "OTP Email sending failed";
                        $statusMessage = $statusMessage != '' ? $statusMessage . ", " . $emailMessage : $emailMessage;
                    }
                }
                $arr_p['status'] = "successful";
                $arr_p['message'] = $statusMessage;
            }
        }

        return $arr_p;
    }

    public function update_password($userkey, $newpwd) {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "update password failed.";

        $todaysdate = date("Y-m-d H:i:s");
        $sp_params = "'" . $newpwd . "'"
            . ",'" . $userkey . "'"
            . ",'" . $todaysdate . "'";

        $queryCallSP = "CALL " . speedConstants::SP_UPDATE_NEWFORGOTPASSWORD . "($sp_params)";
        $result = $this->db->query($queryCallSP, __FILE__, __LINE__);
        // print_r($result);
        $affectedRows = $this->db->get_affectedRows($result);
        //echo $affectedRows;
        if ($affectedRows > 0) {
            $arr_p['status'] = "successful";
            $arr_p['message'] = "update password successful.";
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    public function get_vehicles_drivers_users($userkey) {
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
            //Prepare parameters
            $sp_params = "'" . $customerno . "'";

            $sqlGetVehDriverUserSP = "CALL " . speedConstants::SP_GET_VEHICLES_DRIVERS_USERS . "($sp_params)";
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

    public function map_vehicle_driver_user($userkey, $vehicleid, $userid, $driverid, $drivername) {
        $customermanager = new CustomerManager();
        $smsStatus = new SmsStatus();
        $validation = $this->check_userkey($userkey);
        $todaysdate = date('Y-m-d H:i:s');
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
            //Prepare parameters
            $sp_params = "'" . $customerno . "'"
                . ",'" . $vehicleid . "'"
                . ",'" . $userid . "'"
                . ",'" . $driverid . "'"
                . ",'" . $drivername . "'"
                . ",'" . $todaysdate . "'";

            $sqlMapVehDriverUserSP = "CALL " . speedConstants::SP_MAP_VEHICLE_DRIVER_USER . "($sp_params)";
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

                        $smsStatus->customerno = $customerno;
                        $smsStatus->userid = $userid;
                        $smsStatus->vehicleid = $vehicleid;
                        $smsStatus->mobileno = array($userphone);
                        $smsStatus->message = $smsText;
                        $smsStatus->cqid = 0;
                        $smscount = $customermanager->getSMSStatus($smsStatus);
                        if ($smscount == 0) {
                            $response = '';
                            $isSMSSent = sendSMSUtil($userphone, $smsText, $response);
                            $moduleid = 1;
                            if ($isSMSSent == 1) {
                                $customermanager->sentSmsPostProcess($customerno, $userphone, $smsText, $response, $isSMSSent, $userid, $vehicleid, $moduleid);
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

    public function sendSummaryReport($userkey, $vehicleid, $date, $toaddresses, $comments, $mail_type) {
        $reportsObj = new reports();
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        $tempDate = new DateTime($date);

        $validation = $this->check_userkey($userkey);
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
            $arrReportData = json_decode($this->summary($userkey, $vehicleid, $date));
            if (isset($arrReportData->status) && isset($arrReportData->report)) {
                if ($arrReportData->status == "successful") {
                    $Datacap = new stdClass();
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

    public function sendSummaryReport_wh($userkey, $vehicleno, $deviceid, $startDate, $startTime, $endDate, $endTime, $reportInterval, $toaddresses, $comments, $mail_type, $isWareHouse, $useHumidity) {
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
                if ($customerno == 97) {
                    date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
                }
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

    public function getquicksharetext($userkey, $vehicleid) {
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        $validation = $this->check_userkey($userkey);
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
            $username = $validation['realname'];
            $objVehicleDetails = $this->get_vehicle($vehicleid, $customerno);
            if (isset($objVehicleDetails)) {
                $vehicleno = $objVehicleDetails->vehicleno;
                $usegeolocation = 1;
                $strlocation = $this->location($objVehicleDetails->devicelat, $objVehicleDetails->devicelong, $usegeolocation, $customerno);
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

    public function sendquicksharesms($userkey, $vehicleid, $quickShareText, $mobilenolist) {
        $customermanager = new CustomerManager();
        $smsStatus = new SmsStatus();
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        $validation = $this->check_userkey($userkey);
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
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
                $smsStatus->customerno = $customerno;
                $smsStatus->userid = $userid;
                $smsStatus->vehicleid = $vehicleid;
                $smsStatus->mobileno = array($arrCleanedPhoneNo);
                $smsStatus->message = $quickShareText;
                $smsStatus->cqid = 0;
                $smscount = $customermanager->getSMSStatus($smsStatus);
                if ($smscount == 0) {
                    $response = '';
                    $isSMSSent = sendSMSUtil($arrCleanedPhoneNo, $quickShareText, $response);
                    $moduleid = 1;
                    if ($isSMSSent == 1) {
                        $customermanager->sentSmsPostProcess($customerno, $arrCleanedPhoneNo, $quickShareText, $response, $isSMSSent, $userid, $vehicleid, $moduleid);
                        $arr_p['status'] = "successful";
                        //$arr_p['message'] = "SMS sent. " . (($smsLogId > 0) ? "SMS logged" : " SMS logging failed.");
                        $arr_p['message'] = "SMS sent";
                    } else {
                        $arr_p['message'] = "Unable to send SMS";
                    }
                } elseif ($smscount == -3) {
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

    public function pull_user_group($userkey) {
        $grouplist = NULL;
        $validation = $this->check_userkey($userkey);
        if ($validation['status'] == "successful") {
            // successful
            $customerno = $validation["customerno"];
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
            $grouplist = array();

            $groupidsql = "select group.groupid,group.groupname from `group`
            INNER JOIN groupman ON groupman.groupid = group.groupid
            INNER JOIN user ON user.userid = groupman.userid
            where user.userkey=$userkey AND group.isdeleted=0 AND groupman.isdeleted=0 order by group.groupname ASC";
            $recordgrp = $this->db->query($groupidsql, __FILE__, __LINE__);
            while ($rowgrp = $this->db->fetch_array($recordgrp)) {
                $group = new stdClass();
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
                $group = new stdClass();
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
                    $group = new stdClass();
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

    public function route_histNewRefined_checkpointdetails($vehicleid, $SDdate, $EDdate, $holdtime, $flag, $validation) {
        $arr_p = Array();
        $distancetravelled = 0;
        $cumulative = 0;
        $device = Array();
        $device2 = Array();
        $totaldays = Array();
        $userid = $validation['userid'];
        $customerno = $validation['customerno'];
        $roleid = $validation['roleid'];

        $currentdate = date("Y-m-d H:i:s");
        $unit = $this->getunitdetailsfromvehid($vehicleid, $customerno);

        $SDdate = date('Y-m-d H:i:s', strtotime($SDdate));
        $EDdate = date('Y-m-d H:i:s', strtotime($EDdate));

        $startdate = $SDdate;
        $enddate = $EDdate;
        $SDdate = date('Y-m-d', strtotime($SDdate));
        $EDdate = date('Y-m-d', strtotime($EDdate));

        if ($SDdate != $EDdate) {
            $devicedata[] = NULL;

            $STdate = $startdate;
            $STdate_end = date('Y-m-d', strtotime('+1 day', strtotime($STdate)));
            $STdate_end .= " 23:59:59";
            $counter = 0;
            while (strtotime($STdate) < strtotime($EDdate)) {
                $totaldays[$counter][0] = $STdate;
                $totaldays[$counter][1] = $STdate_end;
                $STdate = date('Y-m-d', strtotime('+1 day', strtotime($STdate)));
                $STdate_end = $STdate . " 23:59:59";
                $counter += 1;
            }
            $totaldays[$counter][0] = date('Y-m-d', strtotime($enddate));
            $totaldays[$counter][1] = date('Y-m-d H:i:s', strtotime($enddate));
        } else {
            $totaldays[0][0] = $startdate;
            $totaldays[0][1] = $enddate;
        }

        if (isset($totaldays)) {
            foreach ($totaldays as $Date) {
                $date = date("Y-m-d", strtotime($Date[0]));
                $unitno = $this->getunitno($vehicleid);
                $location = "../../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";

                if (file_exists($location)) {
                    $location = "sqlite:" . $location;
                    $device = $this->getdatafromsqlite_newRefined($location, $Date, $vehicleid, $device, $holdtime, $distancetravelled, $unitno, $unit, $flag, $validation);

                    foreach ($device as $thisdevice) {
                        $cumulative = $thisdevice->cumulative - $distancetravelled;
                    }
                    $distancetravelled += $cumulative;
                }
            }
        }

        if (isset($device2) && $device2 != NULL) {
            $devicedata = array_merge($device, $device2);
        } else {
            $devicedata = array_merge($device);
        }

        $finaloutput = array();
        $checkpoints = array();
        if (isset($devicedata) && count($devicedata) > 0) {
            $finaloutput = $this->vehicleonmap_route_history($validation, $devicedata, $unit);
        } else {
            $Date = $totaldays[0];
            $date = date("Y-m-d", strtotime($Date[0]));
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
            if (file_exists($location)) {
                $unitno = $this->getunitno($vehicleid);
                $location = "sqlite:" . $location;

                $device = $this->firstmappingforvehiclebydate_fromsqlite_newRefined($validation, $location, $Date, $unit);
            }
            $devicedata[] = $device;
            if (count(end($totaldays)) > 0) {
                $Date = end($totaldays);
            }
            $date = date("Y-m-d", strtotime($Date[0]));
            if (file_exists($location)) {
                $unitno = $this->getunitno($vehicleid);
                $location = "sqlite:" . $location;
                $device = $this->firstmappingforvehiclebydate_fromsqlite_newRefined($validation, $location, $Date, $unit);
            }
            $devicedata[] = $device;
            $finaloutput = $this->vehicleonmap_route_history($validation, $devicedata);
        }
        $checkpoints = $this->get_checkpoint_from_chkmanagedata($vehicleid, $validation);
        $arr_p['routehistory'] = $finaloutput;
        $arr_p['checkpoints'] = $checkpoints;
        $arr_p['status'] = "successful";
        echo json_encode($arr_p);
        return $arr_p;
    }

    public function PullReportDetails($objReqDetails) {
        $isValidUserKey = 0;

        $arrResult['status'] = 0;
        $arrResult['message'] = speedConstants::API_INVALID_USERKEY;
        /* Set Default response */
        $arrResponse = array();
        $arrResponse["isValidUserKey"] = $isValidUserKey;
        $arrResponse["userKey"] = $objReqDetails->userkey;
        $arrResponse["vehicleId"] = $objReqDetails->vehicleId;
        $arrResponse["vehicleNumber"] = $objReqDetails->vehicleNumber;
        $arrResponse["startDate"] = $objReqDetails->startDate;
        $arrResponse["endDate"] = $objReqDetails->endDate;
        $arrResponse["startTime"] = $objReqDetails->startTime;
        $arrResponse["endTime"] = $objReqDetails->endTime;
        $arrResponse["interval"] = $objReqDetails->interval;
        $arrValidationResult = $this->check_userkey($objReqDetails->userkey);
        if ($arrValidationResult['status'] == "successful") {
            $isValidUserKey = 1;
            $arrResult['status'] = 1;
            $arrResult['message'] = speedConstants::API_SUCCESS;
            $arrResponse["isValidUserKey"] = $isValidUserKey;
            $objReqDetails->customerNo = $arrValidationResult["customerno"];
            $objReqDetails->userid = $arrValidationResult["userid"];
            if ($objReqDetails->customerNo == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
            $arrResponse["report"] = $this->GetReportDetails($objReqDetails);
        }
        $arrResult['result'] = $arrResponse;
        return $arrResult;
    }

    public function InsertChkptDetails($objChkPtReqDetails) {
        $arrResult = array();
        $arrResult['status'] = 0;
        $isExists = 0;
        $arrResult['message'] = speedConstants::API_INVALID_USERKEY;
        $arrResponse = array();
        $arrResponse['request'] = (array) $objChkPtReqDetails;
        $validation = $this->check_userkey($objChkPtReqDetails->userkey);
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            $userid = $validation['userid'];
            //TODO: CALL function from existing FUNCTION FILE
            /* Insert Checkpoint */
            $checkpoint = new stdClass();
            $checkpoint->cname = $objChkPtReqDetails->chkPtName;
            $checkpoint->cgeolat = $objChkPtReqDetails->chkPtLat;
            $checkpoint->cgeolong = $objChkPtReqDetails->chkPtLng;
            $checkpoint->cadd = $objChkPtReqDetails->chkAddress;
            $checkpoint->crad = $objChkPtReqDetails->chkPtRadius;
            $checkpoint->vehicles = explode(',', $objChkPtReqDetails->vehicleIdList);
            $checkpoint->customerno = $customerno;
            $checkpoint->cphone = '';
            $checkpoint->cemail = '';
            $checkpoint->eta = '';
            $chkManager = new CheckpointManager($customerno);

            $isExists = $chkManager->CheckName_exists($checkpoint->cname, $checkpoint->customerno);
            if (isset($isExists) && $isExists != 0) {
                $arrResult['status'] = 0;
                $arrResult['message'] = "Checkpoint name is already present";
                $arrResult['result'] = '';
            } else {
                $chckpointId = $chkManager->SaveCheckpoint($checkpoint, $userid);
                if ($chckpointId != 0) {
                    $arrResult['status'] = 1;
                    $arrResult['message'] = "Successfully added the checkpoint";
                    $arrResult['result'] = $chckpointId;
                }
            }
        }
        /*
        if (isset($objChkPtReqDetails)) {
        $arrResult['status'] = 1;
        $arrResult['message'] = "Successfully added the checkpoint";
        $arrResult['result'] = $arrResponse;
        }
         */
        return $arrResult;
    }

    public function PullAlertHistory($objAlertHistReqDetails) {
        $arrResult = array();
        $arrResult['status'] = 0;
        $arrResult['message'] = speedConstants::API_INVALID_USERKEY;
        $arrResponse = array();
        $validation = $this->check_userkey($objAlertHistReqDetails->userkey);
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            $userid = $validation['userid'];
            $objAlertHistReqDetails->customerno = $customerno;
            $objAlertHistReqDetails->userid = $userid;
            $objAlertHistReqDetails->checkpointId = '';
            $objAlertHistReqDetails->fenceId = '';
            //TODO: CALL function from existing FUNCTION FILE (Check alert history report)
            /* Pull Alert History */
            $arrAlertHistory = $this->getAlertHistory($objAlertHistReqDetails);
            if (isset($arrAlertHistory) && !empty($arrAlertHistory)) {
                $arrResponse = $arrAlertHistory;
                $arrResult['status'] = 1;
                $arrResult['message'] = "Successfully fetched the records";
                $arrResult['result'] = $arrResponse;
            } else {
                $arrResult['message'] = "Data Not Available.";
                $arrResult['result'] = array();
            }
        }

        return $arrResult;
    }

    public function UpdateLoginHistory($objLogHistoryDetails) {
        $logHistoryId = 0;
        $arrResult = array();
        $arrResult['status'] = 0;
        $arrResult['message'] = speedConstants::API_INVALID_USERKEY;
        $arrResponse = array();
        $validation = $this->check_userkey($objLogHistoryDetails->userkey);
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            $userid = $validation['userid'];
            $objLogHistoryDetails->customerno = $customerno;
            $objLogHistoryDetails->userid = $userid;
            $objLogHistoryDetails->todaysdate = date('Y-m-d H:i:s');
            //TODO: CALL function from existing FUNCTION FILE (Check alert history report)
            $objUserManager = new UserManager();
            $logHistoryId = $objUserManager->InsertLoginHistory($objLogHistoryDetails);
            if ($logHistoryId != 0) {
                $arrResult['status'] = 1;
                $arrResult['message'] = " Login Details Updated Successfully.";
                $arrResult['result'] = array("Login History Id" => $logHistoryId);
            }
        }

        return $arrResult;
    }

    public function validateOtpForAuthentication($jsonRequest) {
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

    public function setRestoreId($jsonRequest) {
        $arrResult = array();
        $arrResult['status'] = 0;
        $arrResult['message'] = speedConstants::API_INVALID_USERKEY;
        $arrResponse = array();
        $validation = $this->check_userkey($jsonRequest->userkey);
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            $userid = $validation['userid'];

            $objChat = new stdClass();
            $objChat->externalId = $validation['userid'];
            $objChat->restoreId = $jsonRequest->restoreId;
            $objChat->customerno = $validation['customerno'];
            $um = new UserManager();
            $externalId = $um->setRestoreId($objChat);
            if (isset($externalId) && $externalId != 0) {
                $arrResult['status'] = 1;
                $arrResult['message'] = "externalId and restoreId set";
                $arrResult['result'] = array("externalId" => $externalId);
            } elseif (isset($externalId) && $externalId == 0) {
                $arrResult['status'] = 0;
                $arrResult['message'] = "externalId and restoreId not set";
            }
        }

        return $arrResult;
    }

    public function getRestoreId($jsonRequest) {
        $arrResult = array();
        $arrResult['status'] = 0;
        $arrResult['message'] = speedConstants::API_INVALID_USERKEY;
        $arrResponse = array();
        $validation = $this->check_userkey($jsonRequest->userkey);

        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            $userid = $validation['userid'];

            $um = new UserManager();
            $restoreId = $um->getRestoreId($validation['userid']);
            if (isset($restoreId) && $restoreId != '0') {
                $arrResult['status'] = 1;
                $arrResult['message'] = "Valid ExternalId";
                $arrResult['restoreId'] = ($restoreId);
            }
            if (isset($restoreId) && $restoreId == '0') {
                $arrResult['status'] = 0;
                $arrResult['message'] = "Invalid ExternalId";
            }
        }

        return $arrResult;
    }

    public function sendEnquiry($objEnquiry) {
        $arrResult = array();
        $arrResult['status'] = 0;
        $arrResult['message'] = speedConstants::API_INVALID_USERKEY;
        $arrResponse = array();
        $validation = $this->check_userkey($objEnquiry->userkey);
        //print_r($validation);
        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];
            $userid = $validation['userid'];
            $objEnquiry->customerno = $customerno;
            $objEnquiry->userid = $userid;
            /* Pull Alert History */
            $datavehdata = $this->getvehicledetail($objEnquiry->vehicleid, $customerno);
            if (isset($datavehdata) && !empty($datavehdata)) {
                $arrResponse = array();
                $vehicleNo = $datavehdata['vehicleno'];

                $arrToMailIds = array("sales@elixiatech.com");
                if (isset($arrToMailIds) && !empty($arrToMailIds)) {
                    $strCCMailIds = '';
                    $strBCCMailIds = '';
                    $file_name = '';
                    $full_path = '';
                    $ext = '';
                    $subject = "Fuel Sensor Enquiry For " . $validation['customercompany'] . " ";
                    $mail_content = "";
                    $mail_content .= "Hello Sales Team,<br/><br/>";
                    $mail_content .= "";
                    $mail_content .= "We have fuel sensor enquiry for client " . $validation['customercompany'] . "<br/>";
                    $mail_content .= "Customer No : " . $customerno . "<br/>";
                    $mail_content .= "Vehicle No : " . $vehicleNo . "<br/>";
                    $mail_content .= "";
                    //$isMailSent = $this->sendMail($arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $mail_content, $full_path, $file_name . $ext);
                    $isMailSent = sendMailUtil($arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $mail_content, '', '');
                    if ($isMailSent) {
                        $arrResult['status'] = "1";
                        $arrResult['message'] = "Mail Sent successfully.";
                    } else {
                        $arrResult['message'] = "Unable to send mail.";
                    }
                }
            } else {
                $arrResult['message'] = "Vehicle No is not correct.";
                $arrResult['result'] = array();
            }
        }

        return $arrResult;
    }

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Area Master Lat-Lng functions">
    public function updateAreaMaster($objArea) {
        $sql = "UPDATE areamaster SET lat='$objArea->lat', lng='$objArea->lng' WHERE areaid= $objArea->areaid ";
        $this->db->query($sql, __FILE__, __LINE__);
    }

    public function getAreaMasterList() {
        $arrArea = array();
        $sql = "select * from areamaster where lat = '0' and lng = '0'";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        while ($row = $this->db->fetch_array($record)) {
            $area = new stdClass();
            $area->areaid = $row['areaid'];
            $area->address = $row['address'];
            $arrArea[] = $area;
        }
        return $arrArea;
    }

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Helper functions">
    //find location

    public function GetUsersForAccountSwitch($userId, $userRoleName) {
        $objUserManager = new UserManager();
        $arrUserResult = array();
        if (strtolower($userRoleName) === strtolower(speedConstants::ROLE_ELIXIR)) {
            $arrAccSwitchUser = $objUserManager->getAllElixirs();
            foreach ($arrAccSwitchUser as $accSwitchUser) {
                $user = new stdClass();
                $user->childuserid = $accSwitchUser->userid;
                $user->childuserkey = $accSwitchUser->userkey;
                $user->childuserkey_sha1 = sha1($accSwitchUser->userkey);
                $user->childusername = $accSwitchUser->username;
                $user->childrealname = $accSwitchUser->realname . $accSwitchUser->customerno;
                $user->childuserrole = $accSwitchUser->role;
                $user->childcustomerno = $accSwitchUser->customerno;
                $user->childcustomercompany = $accSwitchUser->customercompany;
                $user->child_temp_sensors = $accSwitchUser->temp_sensors;
                $user->childnotificationstatus = $accSwitchUser->notification_status;
                $user->childuseremail = $accSwitchUser->email;
                $user->childuserphone = $accSwitchUser->phone;
                $arrUserResult[] = $user;
            }
        } else {
            $arrAccSwitchUser = $objUserManager->getUserForAccountSwitch($userId);
            foreach ($arrAccSwitchUser as $accSwitchUser) {
                $user = new stdClass();
                $user->childuserid = $accSwitchUser->userid;
                $user->childuserkey = $accSwitchUser->childuserkey;
                $user->childuserkey_sha1 = sha1($accSwitchUser->userkey);
                $user->childusername = $accSwitchUser->childusername;
                $user->childrealname = $accSwitchUser->childrealname;
                $user->childuserrole = $accSwitchUser->childuserrole;
                $user->childcustomerno = $accSwitchUser->customerno;
                $user->childcustomercompany = $accSwitchUser->customercompany;
                $user->child_temp_sensors = $accSwitchUser->temp_sensors;
                $user->childnotificationstatus = $accSwitchUser->childnotificationstatus;
                $user->childuseremail = $accSwitchUser->childuseremail;
                $user->childuserphone = $accSwitchUser->childuserphone;
                $arrUserResult[] = $user;
            }
        }
        return $arrUserResult;
    }

    public function location($lat, $long, $usegeolocation, $customerno) {
        $address = NULL;
        $GeoCoder_Obj = new GeoCoder($customerno);
        $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
        return $address;
    }

    //calculate distance
    public function distance($customerno, $unitno) {
        $totaldistance = 0;
        $todaysDate = date('Y-m-d');
        /* realtime-data distance calculation */
        //Prepare parameters
        $this->db->next_result();
        $sp_params = "'" . $unitno . "'"
            . "," . $customerno
            . ",'" . $todaysDate . "'"
        ;
        $queryCallSP = "CALL " . speedConstants::SP_GET_ODOMETER_READING . "($sp_params)";

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
            if (round($totaldistance) > 0) {
                $totaldistance = round(($totaldistance / 1000), 2);
            } else {
                $totaldistance = 0;
            }
        }
        // Free result set
        $records->close();
        $this->db->next_result();
        return $totaldistance;
    }

    // difference in time
    public function getduration($EndTime, $StartTime) {
        //echo $EndTime.'_'.$StartTime.'<br>';
        $idleduration = strtotime($EndTime) - strtotime($StartTime);
        $years = floor($idleduration / (365 * 60 * 60 * 24));
        $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        if ($years > 0 || $months > 0) {
            $diff = date('Y-m-d', strtotime($StartTime));
        } elseif ($days > 0) {
            $diff = $days . ' Days ' . $hours . ' hrs and ' . $minutes . ' mins';
        } elseif ($hours > 0) {
            $diff = $hours . ' hrs and ' . $minutes . ' mins';
        } else {
            $diff = $minutes . ' mins';
        }
        return $diff;
    }

    public function checkforvalidity($customerno, $deviceid = null) {
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

    public function custom_fields($customerno) {
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

    public function check_validity_login($expirydate, $currentdate) {
        date_default_timezone_set("Asia/Calcutta");
        $expirytimevalue = '23:59:59';
        $expirydate = date('Y-m-d H:i:s', strtotime("$expirydate $expirytimevalue"));
        $realtime = strtotime($currentdate);
        $expirytime = strtotime($expirydate);
        $diff = $expirytime - $realtime;
        return $diff;
    }

    public function check_userkey($userkey) {
        $retarray = array();
        $retarray['status'] = "unsuccessful";
        if (is_numeric($userkey)) {
            $sql = "SELECT  u.userid
                            , c.use_fuel_sensor
                            , u.customerno
                            , u.realname
                            , u.fuel_alert_percentage
                            , u.userkey
                            , u.roleid
                            , u.role
                            , u.heirarchy_id
                            , c.multiauth
                            , c.customercompany
                            , user_consignee_mapping.consigneeid
                            , c.use_door_sensor
                            , c.use_ac_sensor
                            , c.use_genset_sensor
                    FROM    user u
                    INNER JOIN customer c on c.customerno = u.customerno
                    LEFT JOIN user_consignee_mapping ON user_consignee_mapping.userid = u.userid
                    WHERE   u.userkey = '" . $userkey . "' 
                    AND     u.isdeleted = 0 
                    AND     c.isoffline = 0";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            $row = $this->db->fetch_array($record);

            if ($row['userkey'] != "") {
                $devices = $this->checkforvalidity($row["customerno"]);
                $initday = 0;
                if (isset($devices)) {
                    foreach ($devices as $thisdevice) {
                        $days = $this->check_validity_login($thisdevice->expirydate, $thisdevice->today);
                        if ($days > 0) {
                            $initday = $days;
                        }
                    }
                    if ($initday > 0) {
                        $retarray['status'] = "successful";
                        $retarray['customerno'] = $row["customerno"];
                        $retarray['userid'] = $row["userid"];
                        $retarray['realname'] = $row["realname"];
                        $retarray['roleid'] = $row["roleid"];
                        $retarray['role'] = $row["role"];
                        $retarray['heirarchy_id'] = $row["heirarchy_id"];
                        $retarray['multiauth'] = $row["multiauth"];
                        $retarray['fuel_alert_percentage'] = $row["fuel_alert_percentage"];
                        $retarray['use_fuel_sensor'] = $row["use_fuel_sensor"];
                        $retarray['customercompany'] = $row["customercompany"];
                        $retarray['consigneeid'] = $row['consigneeid'];
                        $retarray['use_door_sensor'] = $row['use_door_sensor'];
                        $retarray['use_ac_sensor'] = $row['use_ac_sensor'];
                        $retarray['use_genset_sensor'] = $row['use_genset_sensor'];
                    }
                }
            }
        }

        return $retarray;
    }

    public function gethumidity($rawtemp) {
        $temp = round(($rawtemp / 100), 2);
        return $temp;
    }

    public function getDigitalTemp($rawValue) {
        $value = round(($rawValue / 100), 2);
        return $value;
    }

    public function pull_groups($userkey) {
        $validation = $this->check_userkey($userkey);
        if ($validation['status'] == "successful") {
            // successful
            $customerno = $validation["customerno"];
            if ($customerno == 97) {
                date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
            }
            $groupids = array();

            $groupidsql = "select distinct group.groupid,group.groupname from `group`
            INNER JOIN groupman ON groupman.groupid = group.groupid
            INNER JOIN user ON user.userid = groupman.userid
            where user.userkey=$userkey AND group.isdeleted=0 AND groupman.isdeleted=0 order by group.groupname ASC";
            $recordgrp = $this->db->query($groupidsql, __FILE__, __LINE__);
            while ($rowgrp = $this->db->fetch_array($recordgrp)) {
                $group = new stdClass();
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
                $group = new stdClass();
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
                    $group = new stdClass();
                    $group->groupid = $rowgrp['groupid'];
                    $group->groupname = $rowgrp['groupname'];
                    $groupids[] = $group;
                }
            }

            return $groupids;
        }
    }

    public function checkvalidity($expirydate) {
        $today = date('Y-m-d H:i:s');
        //$today = add_hours($today, 0);
        if (strtotime($today) <= strtotime($expirydate)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function update_push_android_chk($userkey, $val) {
        $sql = "UPDATE user SET chkmanpushandroid = " . $val . " WHERE userkey = '" . $userkey . "'";
        $this->db->query($sql, __FILE__, __LINE__);
    }

    public function update_push_android_chk_main($userkey, $val) {
        $sql = "UPDATE user SET chkpushandroid = " . $val . " WHERE userkey = '" . $userkey . "'";
        $this->db->query($sql, __FILE__, __LINE__);
    }

    public function get_checkpoints($vehicleid) {
        $pdo = $this->db->CreatePDOConn();
        $Query = "SELECT checkpoint.checkpointid, checkpoint.cname, checkpoint.cadd,checkpoint.cgeolat, checkpoint.cgeolong, checkpoint.crad FROM checkpointmanage INNER JOIN checkpoint ON checkpoint.checkpointid = checkpointmanage.checkpointid WHERE checkpointmanage.isdeleted = 0 AND checkpointmanage.vehicleid = $vehicleid";
        $arrResult = $pdo->query($Query)->fetchAll(PDO::FETCH_ASSOC);
        $this->db->ClosePDOConn($pdo);
        $json_p = Array();
        $x = 0;
        foreach ($arrResult as $row) {
            $json_p[$x]['checkpointid'] = $row['checkpointid'];
            $json_p[$x]['cname'] = $row['cname'];
            $json_p[$x]['completeaddress'] = $row['cadd'];
            $json_p[$x]['cgeolat'] = $row['cgeolat'];
            $json_p[$x]['cgeolong'] = $row['cgeolong'];
            $json_p[$x]['crad'] = $row['crad'];
            $x++;
        }
        return $json_p;
    }

    public function get_checkpoint_customer($customerno, $userkey) {
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

    public function get_checkpoint_customer_count($userkey) {
        $q = "SELECT chkpushandroid FROM user WHERE userkey = %s";
        $dq = sprintf($q, $userkey);
        $record = $this->db->query($dq, __FILE__, __LINE__);
        $p = 0;

        while ($row = $this->db->fetch_array($record)) {
            $p = $row['chkpushandroid'];
        }

        return $p;
    }

    public function get_checkpoints_count($userkey) {
        $q = "SELECT chkmanpushandroid FROM user WHERE userkey = %s";
        $dq = sprintf($q, $userkey);
        $record = $this->db->query($dq, __FILE__, __LINE__);
        $p = 0;

        while ($row = $this->db->fetch_array($record)) {
            $p = $row['chkmanpushandroid'];
        }

        return $p;
    }

    public function getvehicledetail($vehicleid, $customerno) {
        $sql = "SELECT v.vehicleid,v.uid,v.odometer, v.vehicleno,u.unitno,d.lastupdated,d.devicelat, d.devicelong
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
                    "vehicleno" => $row['vehicleno'],
                    "devicelat" => $row['devicelat'],
                    "devicelong" => $row['devicelong'],
                    "uid" => $row['uid'],
                    "odometer" => $row['odometer']
                );
            }
            return $data;
        }
        return null;
    }

    public function getCustomizeName($customerno, $customeid, $name) {
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

    public function dateDifference($diff) {
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
                $str .= $hours . " hr " . $minutes . " min ";
            } elseif ($minutes > 0) {
                $str .= $minutes . " min ";
            } else {
                $str .= $seconds . " sec ago";
            }
        }
        echo $str;
    }

    public function getacinvertval($unitno, $customerno) {
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

    public function getspeedlimit($vehicleid) {
        $Query = "SELECT `overspeed_limit` FROM `vehicle` WHERE vehicleid=$vehicleid";
        $record = $this->db->query($Query, __FILE__, __LINE__);

        while ($row = $this->db->fetch_array($record)) {
            $vehicle = new stdClass();
            $vehicle->overspeed_limit = $row['overspeed_limit'];
            return $vehicle;
        }
        return null;
    }

    public function getgroupname_byuid($uid) {
        $Query = "SELECT `group`.groupname FROM `vehicle` INNER JOIN `group` ON `group`.groupid = vehicle.groupid
        where vehicle.uid = '$uid'";
        $record = $this->db->query($Query, __FILE__, __LINE__);
        while ($row = $this->db->fetch_array($record)) {
            return $row['groupname'];
        }
        return "NA";
    }

    public function get_all_vehicles($customerno, $vehicleid) {
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
            $vehicle = new stdClass();
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

    public function DailyReport($device, $date, $info, $overspeed_limit, $acinvertval, $acsensor) {
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
        if ($acsensor == '1') {
            $record['gensetusage'] = $this->gensetusage($acdat, $acinvertval);
        } else {
            $record['gensetusage'] = 0;
        }

        //Tampering PowerCut Overspeed FenceConflict
        $times = $this->monitoring($device->vehicleid, $device->customerno, $info, $overspeed_limit);
        $record['overspeed'] = $times[0];
        $record['date'] = $date;
        $record['lat'] = $last_lat;
        $record['long'] = $last_long;
        //print_r($record);
        return $record;
    }

    public function gensetusage($info, $acinvertval) {
        $days = array();
        $data = $this->getacdata($info);
        if ($data != NULL && count($data) > 1) {
            $report = $this->createrep($data);
            if ($report != NULL) {
                $days = array_merge($days, $report);
            }
        }
        if ($days != NULL && count($days) > 0) {
            $finalreport = $this->getacusagereport($days, $acinvertval);
        }
        return $finalreport;
    }

    public function getacdata($info) {
        $count = count($info);
        $devices = array();
        if ($count > 1) {
            $DRM2 = new stdClass();
            $DRM2->ignition = $info[$count - 1]->ignition;
            $DRM2->status = $info[$count - 1]->status;
            $DRM2->lastupdated = $info[$count - 1]->lastupdated;
            $DRM2->digitalio = $info[$count - 1]->digitalio;
            $devices2 = $DRM2;
            unset($info[$count - 1]);

            foreach ($info as $data) {
                if (@$laststatus['digitalio'] != $data->digitalio) {
                    $DRM = new stdClass();
                    $DRM->ignition = $data->ignition;
                    $DRM->status = $data->status;
                    $DRM->lastupdated = $data->lastupdated;
                    $DRM->digitalio = $data->digitalio;
                    $laststatus['ig'] = $data->ignition;
                    $laststatus['digitalio'] = $data->digitalio;
                    $devices[] = $DRM;
                }
            }
            $devices[] = $devices2;
            return $devices;
        }
    }

    public function createrep($data) {
        $currentrow = new stdClass();
        $currentrow->digitalio = $data[0]->digitalio;
        $currentrow->ignition = $data[0]->ignition;
        $currentrow->starttime = $data[0]->lastupdated;
        $currentrow->endtime = 0;

        $gen_report = array();
        $a = 0;
        $counter = 0;
        //Creating Rows Of Data Where Duration Is Greater Than 3
        while (TRUE) {
            $i = 1;
            /* while(isset($data[$a+$i]) && getduration($data[$a+$i]->lastupdated,$currentrow->starttime)<3)
            {
            $i+=1;
             */
            while (isset($data[$a + $i]) && $this->checkdate_status($data[$a + $i], $currentrow, $data, ($a + $i))) {
                $i += 1;
            }
            if (isset($data[$a + $i])) {
                $currentrow->endtime = $data[$a + $i]->lastupdated;
                $currentrow->duration = round($this->getduration($currentrow->endtime, $currentrow->starttime), 0);

                $gen_report[] = $currentrow;

                $currentrow = new stdClass();
                $currentrow->starttime = $data[$a + $i]->lastupdated;

                $currentrow_count = $a + $i;
                //Just To Check That Data For The Next Row Is Not Wrong
                while (isset($data[$a + $i + 1]) && $this->getduration($data[$a + $i + 1]->lastupdated, $currentrow->starttime) < 3) {
                    $i += 1;
                }
                if (($a + $i) > $currentrow_count) {
                    $gen_report[$counter]->endtime = $data[$a + $i]->lastupdated;
                    $gen_report[$counter]->duration = round($this->getduration($gen_report[$counter]->endtime, $gen_report[$counter]->starttime), 0);
                    $currentrow->starttime = $data[$a + $i]->lastupdated;
                }
                $currentrow->digitalio = $data[$a + $i]->digitalio;
                $currentrow->ignition = $data[$a + $i]->ignition;

                $a += $i;
            } else {
                break;
            }
            $counter += 1;
        }
        //var_dump($gen_report);
        //Clubing Data With Similar AC & Ignition [Both Together]
        $gen_report = $this->optimizerep_clean($gen_report);

        return $gen_report;
    }

    public function optimizerep_clean($gen_report) {
        while (TRUE) {
            $gen_report = $this->markremove($gen_report);

            $remove = 0;

            $count = count($gen_report);
            for ($i = 0; $i <= $count; $i++) {
                if (isset($gen_report[$i]) && $gen_report[$i] == 'Remove') {
                    $remove += 1;
                    unset($gen_report[$i]);
                }
            }

            if ($remove != 0) {
                $a = $gen_report;
                $gen_report = array();
                $i = 0;
                if (isset($a)) {
                    foreach ($a as $value) {
                        $gen_report[$i] = $value;
                        $i += 1;
                    }
                }
            } else {
                break;
            }
        }

        $i = 0;
        $array_length = count($gen_report);
        while (TRUE) {
            if ($i < $array_length - 1) {
                if (isset($gen_report[$i]) && $gen_report[$i]->duration < 3) {
                    $gen_report[$i - 1]->endtime = $gen_report[$i]->endtime;
                    $gen_report[$i - 1]->duration = round($this->getduration($gen_report[$i - 1]->endtime, $gen_report[$i - 1]->starttime), 0);
                    unset($gen_report[$i]);
                }
            } else {
                break;
            }
            $i += 1;
        }

        $a = $gen_report;
        $gen_report = array();
        $i = 0;
        if (isset($a)) {
            foreach ($a as $value) {
                $gen_report[$i] = $value;
                $i += 1;
            }
        }

        return $gen_report;
    }

    public function markremove($gen_report) {
        //var_dump($gen_report);

        $i = 0;
        while (TRUE) {
            if (isset($gen_report[$i]) && isset($gen_report[$i + 1]) && $gen_report[$i] != 'Remove') {
                if ($gen_report[$i]->ignition == $gen_report[$i + 1]->ignition && $gen_report[$i]->digitalio == $gen_report[$i + 1]->digitalio) {
                    $gen_report[$i]->endtime = $gen_report[$i + 1]->endtime;
                    $gen_report[$i]->duration = round($this->getduration($gen_report[$i]->endtime, $gen_report[$i]->starttime), 0);
                    $gen_report[$i + 1] = 'Remove';
                }
            } elseif (isset($gen_report[$i]) && $gen_report[$i] == 'Remove') {
                if (isset($gen_report[$i - 1]) && isset($gen_report[$i + 1])) {
                    if (gettype($gen_report[$i - 1]) == 'object' && $gen_report[$i - 1]->ignition == $gen_report[$i + 1]->ignition && $gen_report[$i - 1]->digitalio == $gen_report[$i + 1]->digitalio) {
                        $gen_report[$i - 1]->endtime = $gen_report[$i + 1]->endtime;
                        $gen_report[$i - 1]->duration = round($this->getduration($gen_report[$i + 1]->endtime, $gen_report[$i - 1]->starttime), 0);
                        $gen_report[$i + 1] = 'Remove';
                    }
                }
            } else {
                break;
            }
            $i += 1;
        }

        return $gen_report;
    }

    public function checkdate_status($data, $currentrow, $entire_array, $currentrowcount) {
        $duration = $this->getduration($data->lastupdated, $currentrow->starttime);
        if ($duration > 3) {
            return FALSE;
        } else {
            if (isset($entire_array[$currentrowcount + 1])) {
                if ($this->getduration($entire_array[$currentrowcount + 1]->lastupdated, $currentrow->starttime) > 3) {
                    return FALSE;
                } else {
                    return TRUE;
                }
            }
            return FALSE;
        }
    }

    public function getacusagereport($datarows, $acinvert) {
        $runningtime = 0;
        $idletime = 0;
        $lastdate = NULL;
        $display = '';
        if (isset($datarows)) {
            foreach ($datarows as $change) {
                if ($acinvert == 1) {
                    if ($change->digitalio == 0) {
                        $runningtime += $change->duration;
                    } else {
                        $idletime += $change->duration;
                    }
                } else {
                    if ($change->digitalio == 0) {
                        $runningtime += $change->duration;
                    } else {
                        $idletime += $change->duration;
                    }
                }
            }
        }
        if ($acinvert == 1) {
            $display .= $idletime;
        } else {
            $display .= $runningtime;
        }
        return $display;
    }

    public function ret_issetor(&$var) {
        return isset($var) ? true : false;
    }

    public function monitoring($vehicleid, $custno, $deviceinfo, $overspeed_limit) {
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
            } elseif ($device->tamper == 0 && $tamper == 1) {
                $tamper = 0;
            }
            if ($device->powercut == 0 && $powercut == 0) {
                $powercutcount += 1;
                $powercut = 1;
            } elseif ($device->powercut == 1 && $powercut == 1) {
                $powercut = 0;
            }
            if ($device->curspeed > $overspeed_limit && $overspeed == 0) {
                $overspeedcount += 1;
                $overspeed = 1;
            } elseif ($device->curspeed <= $overspeed_limit && $overspeed == 1) {
                $overspeed = 0;
            }
        }

        $counters[0] = $overspeedcount;
        $counters[1] = $tampercount;
        $counters[2] = $powercutcount;

        return $counters;
    }

    public function GetOdometer_Max($date, $unitno, $customerno) {
        $date = substr($date, 0, 11);
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

    public function processtraveldata($devicedata, $lastrow) {
        //    print_r($lastrow);
        $count = count($devicedata);
        $devices2 = $devicedata;
        $data = Array();
        $datalen = count($devices2);
        if (isset($devices2) && count($devices2) > 1) {
            foreach ($devices2 as $device) {
                $datacap = new stdClass();
                $laststatus = $device->ignition;
                $datacap->ignition = $device->ignition;

                $ArrayLength = count($data);

                if ($ArrayLength == 0) {
                    //echo $firstidle = '1st starttime'.$device->lastupdated.'_'.$device->id.'<br>';
                    $datacap->starttime = $device->lastupdated;
                    $datacap->startlat = $device->devicelat;
                    $datacap->startlong = $device->devicelong;
                    $datacap->startodo = $device->odometer;
                } elseif ($ArrayLength == 1) {
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
        } elseif (isset($devices2) && count($devices2) == 1) {
            $datacap = new stdClass();
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

    public function displaytraveldata($datarows) {
        $runningtime = 0;
        $idletime = 0;
        if (isset($datarows)) {
            foreach ($datarows as $change) {
                //Removing Date Details From DateTime
                $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
                $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));

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

    public function getduration_dashboard($EndTime, $StartTime) {
        $diff = strtotime($EndTime) - strtotime($StartTime);
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        return $hours * 60 + $minutes;
    }

    public function optimizereptime($data) {
        $datarows = array();
        $ArrayLength = count($data);
        $currentrow = $data[0];
        $a = 0;
        while ($currentrow != $data[$ArrayLength - 1]) {
            $i = 1;
            while (($i + $a) < $ArrayLength - 1 && $data[$i + $a]->duration < 3) {
                $i += 2;
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
            if (($a) >= $ArrayLength - 1) {
                $currentrow = $data[$ArrayLength - 1];
            }
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

            $currentrow->duration = $this->getduration_dashboard($currentrow->endtime, $currentrow->starttime);
            $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;
            $datarows[] = $currentrow;
        }
        return $datarows;
    }

    public function DataFromSqlite($location) {
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
                $DRM = new stdClass();

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

    public function getGroupname($groupid, $customerno) {
        $name = '';
        $con = $this->db1;
        $Query = "SELECT groupid,groupname,cityid,code,address FROM `group` where customerno=$customerno AND groupid=$groupid AND isdeleted=0 order by groupid ASC limit 1 ";
        //print_r($this->db);
        $record = $con->query($Query, __FILE__, __LINE__);
        $rowcount = $con->num_rows($record);
        if ($rowcount > 0) {
            $row = $con->fetch_array($record);
            //print_r($row);
            return $row['groupname'];
        } else {
            return $name;
        }
        $con->close();
        return $list;
    }

    public function get_vehicle($vehicleid, $customerno) {
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
                $vehicle = new stdClass();
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

    public function getduration1($StartTime) {
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

    public function gettemplist($rawtemp, $use_humidity) {
        if ($use_humidity == 1) {
            $temp = round($rawtemp / 100);
        } else {
            $temp = round((($rawtemp - 1150) / 4.45), 1);
        }
        return $temp;
    }

    public function gettripdetails($vehicleid, $customerno) {
        //api call this function
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
                    $statusdate = date(speedConstants::DEFAULT_DATETIME, strtotime($row['statusdate']));
                } else {
                    $statusdate = 'Not Defined';
                }
                $data = array(
                    "tripid" => $row['tripid']
                    , "status" => $row['statusdate']
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

    public function getduration_digitalio($StartTime, $EndTime1) {
        $EndTime = date('Y-m-d H:i:s', strtotime($EndTime1));
        $idleduration = strtotime($EndTime) - strtotime($StartTime);
        $years = floor($idleduration / (365 * 60 * 60 * 24));
        $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        if ($years >= '1' || $months >= '1') {
            $diff = date('d-m-Y', strtotime($StartTime));
        } elseif ($days > 0) {
            $diff = $days . ' days ' . $hours . ' hrs ';
        } elseif ($hours > 0) {
            $diff = $hours . ' hrs and ' . $minutes . ' mins ';
        } else {
            $diff = $minutes . ' mins ';
        }
        return $diff;
    }

    public function DataForHumidity($path, $deviceid, $startdate, $enddate, $interval) {
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
                    $device = new stdClass();
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

    public function DataForTemprature($path, $deviceid, $startdate, $enddate, $interval, $temp_sensors) {
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
                    $device = new stdClass();
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

    public function insertSMSLog($phone, $message, $response, $vehicleid, $userid, $customerno, $isSMSSent, $todaysdate, $moduleid) {
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

        $queryCallSP = "CALL " . speedConstants::SP_INSERT_SMSLOG . "($sp_params)";
        $pdo->query($queryCallSP);
        $this->db->ClosePDOConn($pdo);
        $outputParamsQuery = "SELECT @smsid AS smsid";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        if (count($outputResult) > 0) {
            $smsid = $outputResult['smsid'];
        }
        return $smsid;
    }

    public function check_vehicle_user_mapping($userid, $groupid, &$mappedgroupid) {
        $isUserMappedToVehicle = 0;
        $groupid = isset($groupid) ? $groupid : 0;
        //Prepare parameters
        $sp_params = "'" . $userid . "'"
            . "," . $groupid . ""
            . "," . '@groupidparam' . ""
            . "," . '@isUserMappedToVehicle' . "";
        $queryCallSP = "CALL " . speedConstants::SP_CHECK_VEHICLE_USER_MAPPING . "($sp_params)";
        $this->db->query($queryCallSP, __FILE__, __LINE__);
        $outputParamQuery = "SELECT @isUserMappedToVehicle as isUserMappedToVehicle, @groupidparam as groupid";
        $outParamResult = $this->db->query($outputParamQuery, __FILE__, __LINE__);
        while ($row = $this->db->fetch_array($outParamResult)) {
            $isUserMappedToVehicle = $row['isUserMappedToVehicle'];
            $mappedgroupid = $row['groupid'];
        }
        return $isUserMappedToVehicle;
    }

    public function PrepareHtmlData($Datacap, $customerno, $mail_type) {
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
        } elseif ($mail_type == 'xls') {
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

    public function closedtripdetails_end($tripid, $customerno) {
        $tripdata = array();
        $Query = "select odometer,statusdate from tripdetail_history  WHERE customerno=$customerno AND is_tripend = 1 AND tripstatusid = 10 AND  tripid = $tripid AND isdeleted=0 order by triphisid desc limit 0,1";
        $record = $this->db->query($Query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);

        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $tripdata[] = array(
                    'lasttripend_odometer' => $row['odometer'],
                    'tripend_date' => $row['statusdate']
                );
            }
            return $tripdata;
        }
        return null;
    }

    public function closedtripdetails_start($tripid, $customerno) {
        $tripdata = array();
        $Query = "select odometer,statusdate from tripdetail_history WHERE customerno=$customerno AND tripstatusid = 3 AND  tripid = $tripid AND isdeleted=0 order by triphisid asc limit 0,1";
        $record = $this->db->query($Query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);

        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $tripdata[] = array(
                    'starttripend_odometer' => $row['odometer'],
                    'tripstart_date' => $row['statusdate']
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

    public function getunitno($vehicleid) {
        $unitno = "";
        $Query = "select unitno from unit where vehicleid = $vehicleid";
        $record = $this->db->query($Query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);
        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $unitno = $row['unitno'];
            }
            return $unitno;
        }
        return null;
    }

    public function getodometerform_mysql($vehicleid, $customerno) {
        $odometer = "";
        $Query = "select odometer from vehicle where vehicleid=$vehicleid AND customerno=$customerno";
        $record = $this->db->query($Query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);

        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $odometer = $row['odometer'];
            }
        }
        return $odometer;
    }

    public function GetOdometerMax($date, $unitno, $customerno) {
        $date = substr($date, 0, 11);
        $location = "../../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
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

    public function getunitdetailsfromvehid($vehicleid, $customerno) {
        $Query = 'SELECT vehicle.fuel_min_volt, vehicle.fuel_max_volt, vehicle.fuelcapacity,vehicle.max_voltage,unit.fuelsensor, vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max,vehicle.temp3_min, vehicle.temp3_max,vehicle.temp4_min, vehicle.temp4_max, unit.unitno, unit.tempsen1, unit.tempsen2, unit.tempsen3, unit.tempsen4,unit.get_conversion FROM unit INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
    WHERE unit.customerno =' . $customerno . ' AND unit.vehicleid =' . $vehicleid;
        $record = $this->db->query($Query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);

        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $unit = new stdClass();
                $unit->unitno = $row['unitno'];
                $unit->tempsen1 = $row['tempsen1'];
                $unit->tempsen2 = $row['tempsen2'];
                $unit->tempsen3 = $row['tempsen3'];
                $unit->tempsen4 = $row['tempsen4'];
                $unit->get_conversion = $row['get_conversion'];
                $unit->temp1_min = $row['temp1_min'];
                $unit->temp1_max = $row['temp1_max'];
                $unit->temp2_min = $row['temp2_min'];
                $unit->temp2_max = $row['temp2_max'];
                $unit->temp3_min = $row['temp3_min'];
                $unit->temp3_max = $row['temp3_max'];
                $unit->temp4_min = $row['temp4_min'];
                $unit->temp4_max = $row['temp4_max'];
                $unit->fuelsensor = $row['fuelsensor'];
                $unit->fuelcapacity = $row['fuelcapacity'];
                $unit->maxvoltage = $row['max_voltage'];
                $unit->fuel_min_volt = $row['fuel_min_volt'];
                $unit->fuel_max_volt = $row['fuel_max_volt'];
                return $unit;
            }
        }
        return NULL;
    }

    public function getcustomerdetails($customerno) {
        $sql = "select * from " . DB_PARENT . ".customer where customerno= '" . $customerno . "'";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);

        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $cust = new stdClass();
                $cust->temp_sensors = $row['temp_sensors'];
                $cust->use_hierarchy = $row['use_hierarchy'];
                $cust->use_panic = $row['use_panic'];
                $cust->use_buzzer = $row['use_buzzer'];
                $cust->use_immobiliser = $row['use_immobiliser'];
                $cust->use_freeze = $row['use_freeze'];
                $cust->customername = $row['customername'];
                $cust->customerno = $row['customerno'];
                $cust->use_portable = $row['use_portable'];
                $cust->use_geolocation = $row['use_geolocation'];
                return $cust;
            }
        }
        return NULL;
    }

    public function getdatafromsqlite_newRefined($location, $Date, $vehicleid, $device, $holdtime, $distancetravelled, $unitno, $unit, $flag, $validation) {
        $userid = $validation['userid'];
        $customerno = $validation['customerno'];
        $roleid = $validation['roleid'];

        $basequery = "SELECT vehiclehistory.vehicleid,vehiclehistory.driverid,vehiclehistory.vehicleno,vehiclehistory.odometer, devicehistory.lastupdated, vehiclehistory.curspeed,devicehistory.deviceid, devicehistory.devicelong, devicehistory.devicelat, devicehistory.uid, devicehistory.ignition, devicehistory.status, devicehistory.directionchange,unithistory.analog1,unithistory.analog2,unithistory.analog3,unithistory.analog4
    FROM vehiclehistory
    LEFT OUTER JOIN devicehistory ON devicehistory.lastupdated = vehiclehistory.lastupdated
    LEFT OUTER JOIN unithistory ON unithistory.lastupdated = vehiclehistory.lastupdated ";
        $devicequery = "WHERE vehiclehistory.lastupdated BETWEEN '$Date[0]' AND '$Date[1]' ORDER BY vehiclehistory.lastupdated ASC";
        $database = new PDO($location);
        $result = $database->query($basequery . $devicequery);
        $drivers = $this->get_all_drivers_with_vehicles($validation);

        $counter_first = 0;
        if (isset($result)) {
            $lastdistance = 0;
            $hold_count = 0;
            $total_hold_time = 0;
            $lastdistance_acc = 0;
            $lastdistance_acc_diff = 0; // To Check the 40 Meter Diff
            $cumulative_dist = 0;
            if (isset($result) && $result != "") {
                foreach ($result as $row) {
                    $row['holdtime'] = 0;
                    if ($row['uid'] > 0) {
                        if ($row['devicelat'] > 0 && $row['devicelong'] > 0) {
                            if ($row['odometer'] < $lastdistance_acc_diff) {
                                $max = $this->GetOdometerMax($row['lastupdated'], $unitno, $customerno);
                                $row['odometer'] = $max + $row['odometer'];
                            }
                            $diffmeter = $row['odometer'] - $lastdistance_acc_diff; // To Check the 40 Meter Diff

                            if ($diffmeter > 40) {
                                // To Check the 40 Meter Diff
                                if ($hold_count > 0) {
                                    $minus = strtotime($row['lastupdated']) - strtotime($total_hold_time);
                                    $minutes = floor(($minus) / 60);

                                    $row_old['total_hold_time'] = $minutes;

                                    if ($minutes > $holdtime) {
                                        $row_old['holdtime'] = 1;
                                        $row_old['devicelat'] = $row['devicelat'];
                                        $row_old['devicelong'] = $row['devicelong'];
                                        /*
                                        Changes Made By : Pratik Raut
                                        Date : 19-09-2019
                                        Reason : Added Parameter => Stoppage Details
                                         */
                                        $row_old['isStoppage'] = '1';
                                        $row_old['stoppageDetails']['timeStamp'] = $row['lastupdated'];
                                        $row_old['stoppageDetails']['stopppageMinutes'] = $minutes;
                                        $row_old['stoppageDetails']['DistanceTravelled'] = $diffmeter;
                                        $customer = $this->getcustomerdetails($customerno);
                                        $usegeolocation = $customer->use_geolocation;
                                        $Location = $this->location($row['devicelat'], $row['devicelong'], $usegeolocation, $customerno);
                                        if (!empty($Location)) {
                                            $row_old['stoppageDetails']['location'] = $Location;
                                        }

                                        /*
                                        Changes ends here
                                         */

                                        $device[] = $this->managerow_newRefined_hold($row_old, $cumulative_dist, $unit, $flag, $validation);
                                        $total_hold_time = $row['lastupdated'];
                                    }
                                    $hold_count = 0;
                                    if (empty($row_old['isStoppage'])) {
                                        $row_old['isStoppage'] = '0';
                                    }

                                    $row_old = array();
                                }

                                $cumulative_dist = $row['odometer'] - $lastdistance_acc + $distancetravelled;
                                $counter_first++;
                                $customerdetail = $this->getcustomerdetails($customerno);

                                if ($customerdetail->use_portable != '1') {
                                    if ($counter_first == 1) {
                                        // condition for startpoint
                                        $total_hold_time = $row['lastupdated'];
                                        $lastdistance = $row['odometer'];
                                        $lastdistance_acc = $row['odometer'];
                                        if ($row['odometer'] < $lastdistance_acc) {
                                            $max = $this->GetOdometerMax($row['lastupdated'], $unitno, $customerno);
                                            $row['odometer'] = $max + $row['odometer'];
                                            $lastdistance_acc = $max + $row['odometer'];
                                        }
                                        $cumulative_dist = $row['odometer'] - $lastdistance_acc + $distancetravelled;
                                        $row['total_hold_time'] = $total_hold_time;
                                        $device[] = $this->managerow_newRefined($validation, $row, $cumulative_dist, $unit, $flag);
                                    }
                                    if (($lastdistance_acc) < $row['odometer']) {
                                        $lastdistance = $row['odometer'];
                                        $row['total_hold_time'] = $total_hold_time;
                                        $device[] = $this->managerow_newRefined($validation, $row, $cumulative_dist, $unit, $flag);
                                    }
                                } else {
                                    $row['total_hold_time'] = $total_hold_time;
                                    $device[] = $this->managerow_newRefined($validation, $row, $cumulative_dist);
                                }
                            } else {
                                if ($hold_count == 0) {
                                    $total_hold_time = $row['lastupdated'];
                                    $row_old = $row;
                                }
                                $hold_count++;
                                $lastdistance = $row['odometer'];
                            } // To Check the 40 Meter Diff
                            $lastdistance_acc_diff = $row['odometer']; // To Check the 40 Meter Diff
                        }
                    }
                }
            }
        }

        return $device;
    }

    public function managerow_newRefined($validation, $row, $cumulative_dist, $unit, $flag) {
        $output = new stdClass();
        $output->devicelat = $row['devicelat'];
        $output->devicelong = $row['devicelong'];
        $output->location = "";
        $output->cumulative = $cumulative_dist;
        $output->curspeed = $row['curspeed'];
        $output->lastupdated = $row['lastupdated'];
        $output->status = $row['status'];
        $output->ignition = $row['ignition'];
        $output->holdtime = $row['holdtime'];
        $output->total_hold_time = $row['total_hold_time'];
        $output->test = $unit->unitno;
        if ($flag != 0) {
            $output->temp = $this->set_temp_graph_data($row['lastupdated'], $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], $flag, $validation);
        }
        $output->directionchange = round($row['directionchange'] / 10, 0);

        return $output;
    }

    public function get_all_drivers_with_vehicles($validation) {
        $userid = $validation['userid'];
        $customerno = $validation['customerno'];
        $roleid = $validation['roleid'];
        $heirarchy_id = isset($validation['heirarchy_id']) ? $validation['heirarchy_id'] : 0;

        $groupid = 0;
        $drivers = Array();
        $Query = "SELECT *,driver.driverid FROM driver
        LEFT OUTER JOIN vehicle ON driver.vehicleid = vehicle.vehicleid
        LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid ";
        if ($roleid == '2') {
            $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid
            LEFT OUTER JOIN `district` ON `district`.districtid = city.districtid
            LEFT OUTER JOIN `state` ON `state`.stateid = district.stateid ";
        }
        if ($roleid == '3') {
            $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid
            LEFT OUTER JOIN `district` ON `district`.districtid = city.districtid ";
        }
        if ($roleid == '4') {
            $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid ";
        }
        $Query .= " WHERE driver.customerno =%d AND driver.isdeleted=0";
        if ($groupid != 0) {
            $Query .= " AND vehicle.groupid =%d";
        }

        if ($groupid != 0) {
            $driversQuery = sprintf($Query, $customerno, $groupid);
        } else {
            $driversQuery = sprintf($Query, $customerno);
        }
        $heir_query = "";
        if ($roleid == '2') {
            $heir_query = sprintf(" AND state.stateid = %d ", $heirarchy_id);
        }
        if ($roleid == '3') {
            $heir_query = sprintf(" AND district.districtid = %d ", $heirarchy_id);
        }
        if ($roleid == '4') {
            $heir_query = sprintf(" AND city.cityid = %d ", $heirarchy_id);
        }
        $driversQuery .= $heir_query;
        $record = $this->db->query($driversQuery, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);
        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $driver = new stdClass();
                $driver->driverid = $row['driverid'];
                $driver->drivername = $row['drivername'];
                $driver->driverlicno = $row['driverlicno'];
                $driver->driverphone = $row['driverphone'];
                $driver->vehicleid = $row['vehicleid'];
                $driver->vehicleno = $row['vehicleno'];
                $drivers[] = $driver;
            }
            return $drivers;
        }
        return null;
    }

    public function set_temp_graph_data($updated_date, $unit, $analog1, $analog2, $analog3, $analog4, $flag, $validation) {
        $customerdetail = $this->getcustomerdetails($validation['customerno']);
        $temp_coversion = new TempConversion();
        $temp_sensors = $customerdetail->temp_sensors;
        $temp = "";
        $temp_coversion->unit_type = $unit->get_conversion;
        if ($temp_sensors == 1) {
            $s = "analog" . $unit->tempsen1;
            if ($unit->tempsen1 != 0 && $$s != 0) {
                $temp_coversion->rawtemp = $$s;
                $temp = getTempUtil($temp_coversion);
            } else {
                $temp = '-';
            }
        }

        /**/elseif ($temp_sensors == 2) {
            $temp1 = 'Not Active';
            $temp2 = 'Not Active';
            $s = "analog" . $unit->tempsen1;
            if ($unit->tempsen1 != 0 && $$s != 0) {
                $temp_coversion->rawtemp = $$s;
                $temp1 = getTempUtil($temp_coversion);
            } else {
                $temp1 = '-';
            }

            $s = "analog" . $unit->tempsen2;
            if ($unit->tempsen2 != 0 && $$s != 0) {
                $temp_coversion->rawtemp = $$s;
                $temp2 = getTempUtil($temp_coversion);
            } else {
                $temp2 = '-';
            }

            if ($flag == 1) {
                $temp = (int) $temp1;
            } else {
                $temp = (int) $temp2;
            }
        }
        /**/

        return $temp;
    }

    public function managerow_newRefined_hold($row, $cumulative_dist, $unit, $flag, $validation) {
        $customer = $this->getcustomerdetails($validation['customerno']);
        $usegeolocation = $customer->use_geolocation;
        $output = new stdClass();
        $output->devicelat = $row['devicelat'];
        $output->devicelong = $row['devicelong'];
        /*
        Changes Made By : Pratik Raut
        Date : 19-09-2019
        Reason : Added Parameter => Stoppage Details
         */

        if (!empty($row['isStoppage'])) {
            $output->isStoppage = $row['isStoppage'];
        } else {
            $output->isStoppage = '0';
        }
        if (!empty($row['stoppageDetails'])) {
            $output->stoppageDetails = $row['stoppageDetails'];
        }

        /*  Changes Ends Here  */

        $output->location = $this->location($output->devicelat, $output->devicelong, $usegeolocation, $validation['customerno']);
        $output->cumulative = $cumulative_dist;
        $output->curspeed = $row['curspeed'];
        $output->lastupdated = $row['lastupdated'];
        $output->status = $row['status'];
        $output->ignition = $row['ignition'];
        $output->holdtime = $row['holdtime'];
        $output->total_hold_time = $row['total_hold_time'];
        $output->test = $unit->unitno;
        if ($flag != 0) {
            $output->temp = $this->set_temp_graph_data($row['lastupdated'], $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], $flag, $validation);
        }
        $output->directionchange = round($row['directionchange'] / 10, 0);
        return $output;
    }

    public function vehicleonmap_route_history($validation, $device) {
        $customerno = $validation['customerno'];
        $userid = $validation['userid'];
        $finaloutput = array();
        $length = count($device);
        $counter = 0;
        foreach ($device as $thisdevice) {
            if ($thisdevice == null) {
                break;
            }
            $counter++;
            $output = new stdClass();
            $date = new DateTime($thisdevice->lastupdated);
            $output->cgeolat = $thisdevice->devicelat;
            $output->cgeolong = $thisdevice->devicelong;
            $output->cspeed = $thisdevice->curspeed;
            $output->cignition = $thisdevice->ignition;
            $output->holdtime = $thisdevice->holdtime;
            /*
            changes Made By : Pratik Raut
            Date : 19-09-2019
            Change : Added Paramter => stoppageDetails
             */

            if (!empty($thisdevice->isStoppage)) {
                $output->isStoppage = $thisdevice->isStoppage;
            } else {
                $output->isStoppage = '0';
            }

            if (!empty($thisdevice->stoppageDetails)) {
                $output->stoppageDetails = $thisdevice->stoppageDetails;
            }

            /*
            Changes Ends Here
             */
            $output->cumulative = $thisdevice->cumulative / 1000;
            $output->clastupdated = $date->format('D d-M-Y H:i');
            $output->directionchange = $thisdevice->directionchange;
            $output->total_hold_time = $thisdevice->total_hold_time;
            $output->location = $thisdevice->location;
            $output->test = $thisdevice->test;
            $output->temp = $thisdevice->temp;
            if ($userid != 391 && $userid != 392) {
                $output->users_spec = 0;
            } else {
                $output->users_spec = 1;
            }
            $finaloutput[] = $output;
        }
        return $finaloutput;
    }

    public function firstmappingforvehiclebydate_fromsqlite_newRefined($validation, $location, $Date, $validation1) {
        $customerno = $validation['customerno'];
        $basequery = "SELECT vehiclehistory.vehicleid, vehiclehistory.driverid, vehiclehistory.vehicleno,vehiclehistory.odometer, devicehistory.lastupdated, vehiclehistory.curspeed,devicehistory.deviceid, devicehistory.devicelong, devicehistory.devicelat, devicehistory.uid, devicehistory.ignition, devicehistory.status, devicehistory.directionchange FROM `vehiclehistory` LEFT OUTER JOIN devicehistory ON devicehistory.lastupdated = vehiclehistory.lastupdated ";
        $devicequery = "WHERE vehiclehistory.lastupdated between '$Date[0]' and '$Date[1]' ORDER BY `vehiclehistory`.`lastupdated` ASC LIMIT 0,1";
        $database = new PDO($location);
        $result = $database->query($basequery . $devicequery);
        $drivers = $this->get_all_drivers_with_vehicles($validation);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if ($row['uid'] > 0) {
                    if ($row['devicelat'] > 0 && $row['devicelong'] > 0) {
                        $device = $this->managerow_newRefined($validation, $drivers, $row, $customerno);
                    }
                }
            }
        }
        return $device;
    }

    public function get_checkpoint_from_chkmanage($vehicleid, $validation) {
        $checkpoints = Array();
        $Query = "SELECT *,checkpointmanage.vehicleid as cvehicleid, checkpoint.checkpointid as ccheckpointid FROM `checkpoint`
        INNER JOIN checkpointmanage ON checkpointmanage.checkpointid = checkpoint.checkpointid
        WHERE checkpointmanage.vehicleid = %d and checkpoint.customerno=%d AND checkpoint.isdeleted=0 AND checkpointmanage.isdeleted=0";
        $checkpointsQuery = sprintf($Query, $vehicleid, $validation['customerno']);

        $record = $this->db->query($checkpointsQuery, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);
        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $checkpoint = new stdClass();
                $checkpoint->checkpointid = $row['ccheckpointid'];
                $checkpoint->cname = $row['cname'];
                $checkpoint->completeaddress = $row['cadd'];
                $checkpoint->cgeolat = $row['cgeolat'];
                $checkpoint->cgeolong = $row['cgeolong'];
                $checkpoint->crad = $row['crad'];
                $checkpoint->vehicleid = $row['cvehicleid'];
                $checkpoints[] = $checkpoint;
            }
        }
        return $checkpoints;
    }

    public function get_checkpoint_from_chkmanagedata($vehicleid, $validation) {
        $checkpoints = $this->get_checkpoint_from_chkmanage($vehicleid, $validation);
        return $checkpoints;
    }

    public function GetReportDetails($objReqDetails) {
        $arrRowData = array();
        $arrFormattedData = array();
        $objReport = new reports();
        $objReqDetails->reportStartDate = date("Y-m-d", strtotime($objReqDetails->startDate));
        $objReqDetails->reportStartDate = $objReqDetails->reportStartDate . " " . $objReqDetails->startTime . ":00";
        $objReqDetails->reportEndDate = date("Y-m-d", strtotime($objReqDetails->endDate));
        $objReqDetails->reportEndDate = $objReqDetails->reportEndDate . " " . $objReqDetails->endTime . ":59";
        $arrRowData = $objReport->GetReport($objReqDetails);
        return $arrRowData;
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

    public function getcomhist_sqlite($location, $cqid) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $queues = array();
        $query = "SELECT userid,comtype from comhistory where comqid = $cqid";
        $result = $db->query($query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $Datacap = new stdClass();
                $Datacap->userid = $row['userid'];
                $Datacap->comtype = $row['comtype'];
                $queues[] = $Datacap;
            }
            return $queues;
        }
        return null;
    }

    public function getalerthist_sqlite($location, $date, $type, $vehicleid, $checkpointid, $fenceid, $customerno, $groupid = null) {
        $newdate = date('Y-m-d', strtotime($date));
        $path = "sqlite:$location";
        $db = new PDO($path);
        $queues = array();
        switch ($type) {
            case '-1':
                {
                    if ($vehicleid != '') {
                        $query = "SELECT cqid,timeadded,message,processed,vehicleid FROM `comqueue` where vehicleid = $vehicleid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                    } else {
                        $query = "SELECT cqid,timeadded,message,processed,vehicleid FROM `comqueue` where DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                    }
                }
                break;
            case '2':
                {
                    if ($vehicleid != '' && $checkpointid != '') {
                        $query = "SELECT cqid,timeadded,message,processed,vehicleid FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND chkid = $checkpointid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                    } elseif ($vehicleid == '' && $checkpointid != '') {
                        $query = "SELECT cqid,timeadded,message,processed,vehicleid FROM `comqueue` where type = $type AND chkid = $checkpointid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                    } elseif ($vehicleid != '' && $checkpointid == '') {
                        $query = "SELECT cqid,timeadded,message,processed,vehicleid FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                    } else {
                        $query = "SELECT cqid,timeadded,message,processed,vehicleid FROM `comqueue` where type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                    }
                }
                break;
            case '3':
                {
                    if ($vehicleid != '' && $fenceid != '') {
                        $query = "SELECT cqid,timeadded,message,processed,vehicleid FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND fenceid = $fenceid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                    } elseif ($vehicleid == '' && $fenceid != '') {
                        $query = "SELECT cqid,timeadded,message,processed,vehicleid FROM `comqueue` where type = $type AND fenceid = $fenceid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                    } elseif ($vehicleid != '' && $fenceid == '') {
                        $query = "SELECT cqid,timeadded,message,processed,vehicleid FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                    } else {
                        $query = "SELECT cqid,timeadded,message,processed,vehicleid FROM `comqueue` where type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                    }
                }
                break;
            default:{
                    if ($vehicleid != '') {
                        $query = "SELECT cqid,timeadded,message,processed,vehicleid FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                    } else {
                        $query = "SELECT cqid,timeadded,message,processed,vehicleid FROM `comqueue` where type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                    }
                }
                break;
        }
        $query .= "ORDER BY  `comqueue`.`timeadded` ASC ";
        //echo $query;
        $result = $db->query($query);
        if ($vehicleid == '' && isset($groupid) && $groupid != null) {
            $gm = new GroupManager($customerno);
            $groupedvehicles = $gm->getvehicleforgroup($groupid);
            if (isset($result) && $result != "") {
                foreach ($result as $row) {
                    if (in_array($row['vehicleid'], $groupedvehicles)) {
                        $Datacap = new stdClass();
                        $Datacap->cqid = $row['cqid'];
                        $Datacap->timeadded = convertDateToFormat($row["timeadded"], speedConstants::DEFAULT_TIME);
                        $Datacap->message = $row['message'];
                        $Datacap->processed = $row['processed'];
                        $queues[] = $Datacap;
                    }
                }
                return $queues;
            }
            return null;
        } else {
            if (isset($result) && $result != "") {
                foreach ($result as $row) {
                    $Datacap = new stdClass();
                    $Datacap->cqid = $row['cqid'];
                    $Datacap->timeadded = convertDateToFormat($row["timeadded"], speedConstants::DEFAULT_TIME);
                    $Datacap->message = $row['message'];
                    $Datacap->processed = $row['processed'];
                    $queues[] = $Datacap;
                }
                return $queues;
            }
            return null;
        }
    }

    public function multiauthRequest($userid, $customerno) {
        $status = "notok";
        $usermanager = new UserManager;
        $cm = new CustomerManager();
        $smsStatus = new SmsStatus();
        $getuser = $usermanager->check2WayAuthUser($userid);
        if (!empty($getuser)) {
            $response = '';
            $smsStatus->customerno = $customerno;
            $smsStatus->userid = $userid;
            $smsStatus->vehicleid = 0;
            $smsStatus->mobileno = array($getuser['userphone']);
            $smsStatus->message = $message;
            $smsStatus->cqid = 0;
            $smsstatus = $cm->getSMSStatus($smsStatus);
            if ($smsstatus == 0) {
                if ($getuser['userphone'] != '' && $getuser['otp'] != '-1') {
                    $message = "OTP: " . $getuser['otp'] . "\r\n" . "Valid Until: " . date('d-M-Y h:i:s A', strtotime($getuser['otpvalidupto']));
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

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Utility functions">
    public function sendMail(array $arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName) {
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
        $mail->Body;
        if ($mail->Send()) {
            $isEmailSent = 1; // or use booleans here
        }
        return $isEmailSent;
    }

    public function gendays($STdate, $EDdate) {
        $TOTALDAYS = Array();
        $STdate = date("Y-m-d", strtotime($STdate));
        $EDdate = date("Y-m-d", strtotime($EDdate));
        while (strtotime($STdate) <= strtotime($EDdate)) {
            $TOTALDAYS[] = $STdate;
            $STdate = date("Y-m-d", strtotime($STdate . ' + 1 day'));
        }
        return $TOTALDAYS;
    }

    //</editor-fold>
    //
    public function getAllCheckpoints($getObjectData) {
        if ($getObjectData->isClientCode) {
            $validation = $this->checkClientCode($getObjectData->userkey);
        } else {
            $validation = $this->check_userkey($getObjectData->userkey);
        }

        $arr_p = array();
        $json_p = array();
        if ($validation['status'] == "successful") {
            $pdo = $this->db->CreatePDOConn();
            $queryCallSP = "CALL " . speedConstants::SP_GET_CHKPNT_DETAIL . "(" . $validation['customerno'] . ")";
            $record = $this->db->query($queryCallSP, __FILE__, __LINE__);
            $row_count = $this->db->num_rows($record);
            if ($row_count > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['checkpointid'] = $row['checkpointid'];
                    $arr_p['customerno'] = $row['customerno'];
                    $arr_p['cname'] = $row['cname'];
                    $arr_p['cadd'] = $row['cadd'];
                    $arr_p['cgeolat'] = $row['cgeolat'];
                    $arr_p['cgeolong'] = $row['cgeolong'];
                    $arr_p['crad'] = $row['crad'];
                    $arr_p['userid'] = $row['userid'];
                    $arr_p['phoneno'] = $row['phoneno'];
                    $arr_p['eta'] = $row['eta'];
                    $arr_p['eta_starttime'] = $row['eta_starttime'];
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

    public function failure($text) {
        return array('Status' => '0', 'Message' => $text);
    }

    public function success($message, $result) {
        return array('Status' => '1', 'Message' => $message, 'Result' => $result);
    }

    public function get_userdetails_by_key($userkey) {
        $SQL = "SELECT customerno,userid,role,roleid,realname FROM user WHERE isdeleted=0 and sha1(userkey)='" . $userkey . "'";
        $Query = sprintf($SQL);
        $result = $this->db->query($Query, __FILE__, __LINE__);
        if ($this->db->num_rows($result) > 0) {
            while ($row = $this->db->fetch_array($result)) {
                $data = array(
                    'customerno' => $row['customerno'],
                    'userid' => $row['userid'],
                    'role' => $row['role'],
                    'roleid' => $row['roleid'],
                    'realname' => $row['realname']
                );
                return $data;
            }
        }
        return null;
    }

    public function checkValidity_customer($customerno) {
        $devices = $this->checkforvalidity($customerno);
        $initday = 0;
        if (isset($devices)) {
            foreach ($devices as $thisdevice) {
                $days = $this->check_validity_login($thisdevice->expirydate, $thisdevice->today);
                if ($days > 0) {
                    $initday = $days;
                }
            }
        }
        return $initday;
    }

    public function devicesformapping_byId($srhstring, $customerno) {
        $srhstring = "%" . $srhstring . "%";
        $devices = Array();
        $vehicle_ses = '';
        $list = " vehicle.vehicleid
            ,vehicle.customerno
            ,vehicle.vehicleno
            ,vehicle.kind
            ,vehicle.overspeed_limit
            ,unit.uid
            ,unit.unitno
            ,`group`.groupname
            ,driver.drivername
            ,driver.driverphone
            ,devices.deviceid
            ,devices.devicelat
            ,devices.devicelong
            ,vehicle.lastupdated
            ,devices.ignition
            ,devices.status
            ,vehicle.curspeed
            ,vehicle.stoppage_flag
            ,vehicle.stoppage_transit_time
            ,devices.directionchange ";

        // $vehicle_ses = " AND vehicle.kind <> 'Warehouse' ";

        $Query = "SELECT $list FROM `devices`
                INNER JOIN unit ON unit.uid = devices.uid
                INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                INNER JOIN driver ON vehicle.driverid = driver.driverid
                LEFT JOIN `group` on vehicle.groupid = `group`.groupid
                where (devices.customerno=%d )
                AND unit.trans_statusid NOT IN (10,22)
                AND vehicle.isdeleted = 0
                AND vehicleno LIKE '%s' $vehicle_ses ";
        $Query .= " ORDER BY vehicle.vehicleno";
        $devicesQuery = sprintf($Query, $customerno, Sanitise::String($srhstring));

        $record = $this->db->query($devicesQuery, __FILE__, __LINE__);
        //$this->db->executeQuery($devicesQuery);
        // print("<pre>"); print_r($record);die;
        if ($this->db->num_rows($record) > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $device = new stdClass();
                if ($row['uid'] > 0) {
                    $device->vehicleno = $row['vehicleno'];
                    $device->vehicleid = $row['vehicleid'];
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
                    $device->stoppage_flag = $row['stoppage_flag'];
                    $device->stoppage_transit_time = $row['stoppage_transit_time'];
                    $device->directionchange = $row['directionchange'];
                    $device->groupname = $row['groupname'];
                    $devices[] = $device;
                }
            }
            return $devices;
        }
        return null;
    }

    public function gettemptabularreport($objReqData) {
        //Prepare Reponse
        //print_r($objReqData); die;
        /* {"userkey":"b4a58c9ad31d5ce7439a13a1f33c70cf100c8795",
        "vehicleNo":"HR 46  D 7431",
        "SDate":"2018-05-18",
        "EDate":"2018-05-18",
        "STime":"10:00:00",
        "ETime":"11:00:00",
        "interval":"15"
         */

        if (!isset($objReqData->vehicleId)) {
            $objReqData->vehicleId = "";
        }

        $startDateTime = $objReqData->SDate . " " . $objReqData->STime;
        $endDateTime = $objReqData->EDate . " " . $objReqData->ETime;

        $arrResponse = array();
        $final_result = new stdClass();
        $arrResponse['vehicleNo'] = $objReqData->vehicleNo;
        $arrResponse['SDate'] = $objReqData->SDate;
        $arrResponse['EDate'] = $objReqData->EDate;
        $arrResponse['STime'] = $objReqData->STime;
        $arrResponse['ETime'] = $objReqData->ETime;
        $arrResponse['interval'] = $objReqData->interval;
        $arrResponse['customParams'] = $objReqData->customParams;

        $arrResponse['finalreport'] = $final_result;

        $objReqData->chkPtId = 0;
        $objReqData->reportType = 0;
        $tempselect = null;

        // $deviceid = $objReqData->deviceid;

        if (($objReqData->vehicleId > 0 || $objReqData->vehicleNo != "") && ($objReqData->customerNo > 0)) {
            if (strtotime($endDateTime) >= strtotime($startDateTime)) {
                $diffInDays = $this->date_SDiff($startDateTime, $endDateTime);
                if ($diffInDays <= 30) {
                    $objVehManager = new VehicleManager($objReqData->customerNo);
                    if ($objReqData->vehicleId > 0) {
                        $objRequest = $objVehManager->get_vehicle_details($objReqData->vehicleId);
                    } elseif ($objReqData->vehicleNo != "") {
                        $objReq = $objVehManager->get_vehicle_by_vehno($objReqData->vehicleNo, 1);

                        $objRequest = (object) $objReq[0];
                        $objRequest->vehicleNo = $objReqData->vehicleNo;
                        $objRequest->vehicleId = $objRequest->vehicleid;
                    }
                    $totaldays = gendays_cmn($objReqData->SDate, $objReqData->EDate);
                    $count = count($totaldays);
                    $endelement = end($totaldays);
                    $firstelement = $totaldays[0];

                    $days = array();

                    $unit = $this->getunitdetails($objReqData->deviceid, $objReqData->customerNo);

                    $objCustomerManager = new CustomerManager();
                    $customer_details = $objCustomerManager->getcustomerdetail_byid($objReqData->customerNo);

                    if (isset($customer_details) && !empty($customer_details)) {
                        $tempselect = $customer_details->temp_sensors;
                    }

                    if (isset($totaldays)) {
                        foreach ($totaldays as $userdate) {
                            //Date Check Operations
                            $data = null;
                            $graph_data = null;
                            $STdate = date("Y-m-d", strtotime($objReqData->SDate));
                            $f_STdate = $userdate . " 00:00:00";
                            if ($userdate == $STdate) {
                                $f_STdate = $userdate . " " . $objReqData->STime . ":00";
                            }
                            $EDdate = date("Y-m-d", strtotime($objReqData->EDate));
                            $f_EDdate = $userdate . " 23:59:59";
                            if ($userdate == $EDdate) {
                                $f_EDdate = $userdate . " " . $objReqData->ETime . ":00";
                            }

                            $location = "../../../customer/$objReqData->customerNo/unitno/$unit->unitno/sqlite/$userdate.sqlite";
                            if (file_exists($location)) {
                                // echo "<br>file exist"; die;
                                $location = "sqlite:" . $location;
                                $return = gettempdata_fromsqlite($location, $objReqData->deviceid, $objReqData->interval, $f_STdate, $f_EDdate, false, $unit, $objReqData->customerNo, $isApi = 1);

                                $data = $return[0];
                            }

                            if (isset($data) && count($data) > 1) {
                                $days = array_merge($days, $data);
                                // print("days - <pre>"); print_r($days); die;
                            }
                        }
                    }

                    if (isset($days) && count($days) > 0) {
                        if (isset($return['vehicleid'])) {
                            $veh_temp_details = getunitdetailsfromvehid($return['vehicleid'], $objReqData->customerNo);
                            // print(" veh temp <pre>"); print_r($veh_temp_details); die;
                        }
                        if (isset($tripmin)) {
                            $unit->temp1_min = $tripmin;
                            $unit->temp2_min = $tripmin;
                            $unit->temp3_min = $tripmin;
                            $unit->temp4_min = $tripmin;
                        }
                        if (isset($tripmax)) {
                            $unit->temp1_max = $tripmax;
                            $unit->temp2_max = $tripmax;
                            $unit->temp3_max = $tripmax;
                            $unit->temp4_max = $tripmax;
                        }
                        // print("<pre>"); print_r($objReqData); die;
                        $data = getTripData($objReqData->SDate, $objReqData->EDate, $objReqData->deviceid, $objReqData->interval, $objReqData->STime, $objReqData->ETime, $objReqData->customerNo, $tempselect);
                        // print("<pre>"); print_r($data); die;

                        /*if (isset($data) && !empty($data)) {
                        $startData = reset($data);
                        $lastData = end($data);
                        $distance = calculateOdometerDistance($startData->odometer, $lastData->odometer);
                        $arrResponse['distance'] = "" . round($distance, 2);
                         */

                        $arrResponse['finalreport'] = $this->get_report_with_noncom_reading($days, $unit, $veh_temp_details, $objReqData->interval, $tempselect, $objReqData->customerNo);
                    }
                }
            }
        }

        return $arrResponse;
    }

    public function date_SDiff($dt1, $dt2, $timeZone = 'GMT', $isAbsolute = 1) {
        $tZone = new DateTimeZone($timeZone);
        $dt1 = new DateTime($dt1, $tZone);
        $dt2 = new DateTime($dt2, $tZone);
        $ts1 = $dt1->format('Y-m-d');
        $ts2 = $dt2->format('Y-m-d');
        $diff = ($isAbsolute) ? abs(strtotime($ts1) - strtotime($ts2)) : strtotime($ts1) - strtotime($ts2);
        $diff /= 3600 * 24;
        return $diff;
    }

    public function getunitdetails($deviceid, $customerno) {
        $Query = 'SELECT vehicle.vehicleno,
                vehicle.fuel_min_volt,
                vehicle.fuel_max_volt,
                vehicle.fuelcapacity,
                vehicle.max_voltage
                ,vehicle.kind
            ,vehicle.hum_min,
            vehicle.hum_max,
            unit.unitno,
             unit.tempsen1,
              unit.tempsen2,
              unit.tempsen3,
              unit.tempsen4
            ,unit.n1,unit.n2,
             unit.n3,unit.n4,
             unit.analog1,
             unit.analog2,
             unit.analog3,
             unit.analog4,
             unit.get_conversion
            ,unit.humidity,
            unit.acsensor,
            unit.fuelsensor,
            unit.is_ac_opp,
            unit.uid,
            vehicle.vehicleid
            ,unit.isDoorExt
            ,unit.is_door_opp
            FROM unit
            INNER JOIN devices ON devices.uid = unit.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            WHERE unit.customerno = %d AND devices.deviceid = %d';
        $unitQuery = sprintf($Query, $customerno, Sanitise::Long($deviceid));
        $record = $this->db->query($unitQuery, __FILE__, __LINE__);
        if ($this->db->num_rows($record) > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $unit = new stdClass();
                $unit->uid = $row['uid'];
                $unit->vehicleid = $row['vehicleid'];
                $unit->kind = $row['kind'];
                $unit->unitno = $row['unitno'];
                $unit->vehicleno = $row['vehicleno'];
                $unit->tempsen1 = $row['tempsen1'];
                $unit->tempsen2 = $row['tempsen2'];
                $unit->tempsen3 = $row['tempsen3'];
                $unit->tempsen4 = $row['tempsen4'];
                $unit->n1 = $row['n1'];
                $unit->n2 = $row['n2'];
                $unit->n3 = $row['n3'];
                $unit->n4 = $row['n4'];
                $unit->analog1 = $row['analog1'];
                $unit->analog2 = $row['analog2'];
                $unit->analog3 = $row['analog3'];
                $unit->analog4 = $row['analog4'];
                $unit->get_conversion = $row['get_conversion'];
                $unit->humidity = $row['humidity'];
                $unit->acsensor = $row['acsensor'];
                $unit->fuelsensor = $row['fuelsensor'];
                $unit->fuelcapacity = $row['fuelcapacity'];
                $unit->maxvoltage = $row['max_voltage'];
                $unit->hum_min = $row['hum_min'];
                $unit->hum_max = $row['hum_max'];
                $unit->fuel_min_volt = $row['fuel_min_volt'];
                $unit->fuel_max_volt = $row['fuel_max_volt'];
                $unit->isacopp = $row['is_ac_opp'];
                $unit->isDoorExt = $row['isDoorExt'];
                $unit->is_door_opp = $row['is_door_opp'];
                return $unit;
            }
        }
        return NULL;
    }

    //create_temphtml_from_report_with_noncom_reading
    public function get_report_with_noncom_reading($datarows, $vehicle, $veh_temp_details = null, $interval = 120, $tempselect = null, $customerNo) {
        // print("<pre>"); print_r($customerNo); die;

        $i = 1;
        $tr = 0;
        $totalminute = 0;
        $lastdate = null;
        $display = '';
        $goodcount = 0;
        $temp1_non_comp_count = 0;
        $temp2_non_comp_count = 0;
        $temp3_non_comp_count = 0;
        $temp4_non_comp_count = 0;
        $temp1_bad_reading = 0;
        $temp2_bad_reading = 0;
        $temp3_bad_reading = 0;
        $temp4_bad_reading = 0;
        $temp1_mute_reading = 0;
        $temp2_mute_reading = 0;
        $temp3_mute_reading = 0;
        $temp4_mute_reading = 0;
        $temp1_nonmute_reading = 0;
        $temp2_nonmute_reading = 0;
        $temp3_nonmute_reading = 0;
        $temp4_nonmute_reading = 0;
        $temp1_data = "";
        $temp2_data = "";
        $temp3_data = "";
        $temp4_data = "";
        $restemp1 = array(0);
        $restemp2 = array(0);
        $restemp3 = array(0);
        $restemp4 = array(0);
        $temp1_min = '';
        $temp2_min = '';
        $temp3_min = '';
        $temp4_min = '';
        $temp1_max = '';
        $temp2_max = '';
        $temp3_max = '';
        $temp4_max = '';
        $min_max_temp1 = get_min_max_temp(1, $veh_temp_details, $tempselect); // here $tempselect is Temp Sensors
        $min_max_temp2 = get_min_max_temp(2, $veh_temp_details, $tempselect);
        $min_max_temp3 = get_min_max_temp(3, $veh_temp_details, $tempselect);
        $min_max_temp4 = get_min_max_temp(4, $veh_temp_details, $tempselect);

        $temp1counter = 0;
        $temp2counter = 0;
        $temp3counter = 0;
        $temp4counter = 0;
        $temp1totalreadingcount = 0;
        $temp2totalreadingcount = 0;
        $temp3totalreadingcount = 0;
        $temp4totalreadingcount = 0;

        $non_complaince_check1 = 1;
        $non_complaince_check2 = 1;
        $non_complaince_check3 = 1;
        $non_complaince_check4 = 1;

        $mutedetails = getunitmutedetails($vehicle->vehicleid, $vehicle->uid, $customerNo);
        $switch_to = isset($vehicle->switch_to) ? $vehicle->switch_to : 0;
        $finaldata = array();
        if (isset($datarows)) {
            $kelvinDenominator4 = 0;
            $kelvinDenominator3 = 0;
            $kelvinDenominator2 = 0;
            $kelvinDenominator1 = 0;
            $sumTEMP1 = 0;
            $sumTEMP2 = 0;
            $sumTEMP3 = 0;
            $sumTEMP4 = 0;
            foreach ($datarows as $change) {
                /* Don't show the entire log of 1 min data when customer pulls out this interval for compliance only */
                $finalreport = new stdClass();

                if ($interval != "1") {
                    $comparedate = date('d-m-Y', strtotime($change->endtime));
                    $today = date('d-m-Y', strtotime('Now'));
                    if (strtotime($lastdate) != strtotime($comparedate)) {
                        if ($today == $comparedate) {
                            $todays = date('Y-m-d');
                            $todayhms = date('Y-m-d H:i:s');
                            $to_time = strtotime("$todayhms");
                            $from_time = strtotime("$todays 00:00:00");
                            $totalminute = round(abs($to_time - $from_time) / 60, 2);
                        } else {
                            $count = $i;
                        }
                        /* $display .= "<tr><th align='center' colspan = '100%' style='background:#d8d5d6'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>"; */
                        $lastdate = date('d-m-Y', strtotime($change->endtime));
                        $i++;
                    }

                    $change->lastupdated = $change->starttime;
                    $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
                    $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
                    if ($customerNo == 116) {
                        $starttime_disp = date(speedConstants::DEFAULT_DATETIME, strtotime($change->lastupdated));
                    } else {
                        $starttime_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
                    }

                    $finalreport->starttime = $starttime_disp;

                    $location = get_location_detail($change->devicelat, $change->devicelong, $customerNo);

                    if ($switch_to != 3) {
                        $finalreport->location = $location;
                    }
                }

                $temp1 = $temp2 = $temp3 = $temp4 = speedConstants::TEMP_WARNING;

                $tdstring = '';
                $objTemp = new TempConversion();
                $objTemp->unit_type = $veh_temp_details->get_conversion;
                /* $objTemp->use_humidity = $_SESSION['use_humidity'];
                $objTemp->switch_to = $_SESSION['switch_to']; */
                $arr = explode("-", $tempselect);

                $pass = (isset($arr[0]) ? $arr[0] : $_SESSION['temp_sensors']);

                switch ($pass) {
                    case 4:
                        // echo "case 4 "; die;
                        if ($vehicle->tempsen4 != 0) {
                            $isTemperatureMuted = checkTemperatureMute($mutedetails, 4, $change->lastupdated);
                            if ($isTemperatureMuted) {
                                $temp4 = 'Muted';
                                $temp4_mute_reading++;
                            } else {
                                $temp4_nonmute_reading++;
                                $s4 = "analog" . $vehicle->tempsen4;
                                if ($change->$s4 != 0) {
                                    $objTemp->rawtemp = $change->$s4;
                                    $temp4 = getTempUtil($objTemp);
                                    $sumTEMP4 += $temp4;

                                    if ($temp4 == 0 || $temp4 > NORMAL_MAX_TEMP || $temp4 < NORMAL_MIN_TEMP) {
                                        if ($temp4 == 0) {
                                            $temp4 = 'Wirecut';
                                        } else {
                                            $temp4 = 'Bad Data';
                                        }
                                        $temp4_bad_reading++;
                                    } elseif ($temp4 != 0) {
                                        $temp4_min = set_summary_min_temp4($temp4);
                                        $temp4_max = set_summary_max_temp4($temp4);
                                        if ($temp4 > $min_max_temp4['temp_max_limit'] || $temp4 < $min_max_temp4['temp_min_limit']) {
                                            if ($non_complaince_check4 == 5) {
                                                // $temp4_non_comp_count++;
                                                $temp4_non_comp_count = $temp4_non_comp_count + 5;
                                                $non_complaince_check4 = 1;
                                            } else {
                                                //echo "<br>temp is ".$temp1." else ".$non_complaince_check."<br>";
                                                $non_complaince_check4++;
                                            }
                                        } else {
                                            $non_complaince_check4 = 1;
                                        }
                                        /* $temp4_non_comp_count++;
                                        } */
                                        $temp4 = $temp4 . speedConstants::TEMP_DEGREE;
                                    }
                                } else {
                                    $temp4_bad_reading++;
                                }
                            }
                            $temp4totalreadingcount++;
                        } else {
                            $temp4 = speedConstants::TEMP_NOTACTIVE;
                        }
                        $finalreport->temp4 = "" . round($temp4, 2);

                    case 3:
                        // echo "case 3 "; die;
                        if ($vehicle->tempsen3 != 0) {
                            $isTemperatureMuted = checkTemperatureMute($mutedetails, 3, $change->lastupdated);
                            if ($isTemperatureMuted) {
                                $temp3 = 'Muted';
                                $temp3_mute_reading++;
                            } else {
                                $temp3_nonmute_reading++;
                                $s3 = "analog" . $vehicle->tempsen3;
                                if ($change->$s3 != 0) {
                                    $objTemp->rawtemp = $change->$s3;
                                    $temp3 = getTempUtil($objTemp);
                                    $sumTEMP3 += $temp3;

                                    if ($temp3 == 0 || $temp3 > NORMAL_MAX_TEMP || $temp3 < NORMAL_MIN_TEMP) {
                                        if ($temp3 == 0) {
                                            $temp3 = 'Wirecut';
                                        } else {
                                            $temp3 = 'Bad Data';
                                        }
                                        $temp3_bad_reading++;
                                    } elseif ($temp3 != 0) {
                                        $temp3_min = set_summary_min_temp3($temp3);
                                        $temp3_max = set_summary_max_temp3($temp3);
                                        if ($temp3 > $min_max_temp3['temp_max_limit'] || $temp3 < $min_max_temp3['temp_min_limit']) {
                                            if ($non_complaince_check3 == 5) {
                                                //echo "hello i reached".$non_complaince_check; //die;
                                                // $temp3_non_comp_count++;
                                                $temp3_non_comp_count = $temp3_non_comp_count + 5;
                                                $non_complaince_check3 = 1;
                                            } else {
                                                //echo "<br>temp is ".$temp1." else ".$non_complaince_check."<br>";
                                                $non_complaince_check3++;
                                            }
                                        } else {
                                            $non_complaince_check3 = 1;
                                        }
                                        /* $temp3_non_comp_count++;
                                        } */
                                        $temp3 = $temp3 . speedConstants::TEMP_DEGREE;
                                    }
                                } else {
                                    $temp3_bad_reading++;
                                }
                            }
                            $temp3totalreadingcount++;
                        } else {
                            $temp3 = speedConstants::TEMP_NOTACTIVE;
                        }

                        $finalreport->temp3 = "" . round($temp3, 2);

                    case 2:
                        // echo "case 2 "; die;
                        //echo "tkbfkb ".$vehicle->tempsen2; die;
                        if ($vehicle->tempsen2 != 0) {
                            $isTemperatureMuted = checkTemperatureMute($mutedetails, 2, $change->lastupdated);
                            if ($isTemperatureMuted) {
                                $temp2 = 'Muted';
                                $temp2_mute_reading++;
                            } else {
                                $temp2_nonmute_reading++;
                                $s2 = "analog" . $vehicle->tempsen2;
                                if ($change->$s2 != 0) {
                                    $objTemp->rawtemp = $change->$s2;
                                    $temp2 = getTempUtil($objTemp);

                                    $sumTEMP2 += $temp2;
                                    if ($temp2 == 0 || $temp2 > NORMAL_MAX_TEMP || $temp2 < NORMAL_MIN_TEMP) {
                                        if ($temp2 == 0) {
                                            $temp2 = 'Wirecut';
                                        } else {
                                            $temp2 = 'Bad Data';
                                        }
                                        $temp2_bad_reading++;
                                    } elseif ($temp2 != 0) {
                                        $temp2_min = set_summary_min_temp2($temp2);
                                        $temp2_max = set_summary_max_temp2($temp2);
                                        if ($temp2 > $min_max_temp2['temp_max_limit'] || $temp2 < $min_max_temp2['temp_min_limit']) {
                                            if ($non_complaince_check2 == 5) {
                                                //echo "hello i reached".$non_complaince_check; //die;
                                                // $temp2_non_comp_count++;
                                                $temp2_non_comp_count = $temp2_non_comp_count + 5;
                                                $non_complaince_check2 = 1;
                                            } else {
                                                //echo "<br>temp is ".$temp1." else ".$non_complaince_check."<br>";
                                                $non_complaince_check2++;
                                            }
                                        } else {
                                            $non_complaince_check2 = 1;
                                        }
                                        /* $temp2_non_comp_count++;
                                        } */
                                        $temp2 = $temp2 . speedConstants::TEMP_DEGREE;
                                    }
                                } else {
                                    $temp2_bad_reading++;
                                }
                            }
                            $temp2totalreadingcount++;
                        } else {
                            $temp2 = speedConstants::TEMP_NOTACTIVE;
                        }
                        // $tdstring = "<td>" . $temp2 . "</td>" . $tdstring;
                        $finalreport->temp2 = "" . round($temp2, 2);

                    case 1:
                        // echo "case 1 "; //die;
                        if ($vehicle->tempsen1 != 0) {
                            // echo "tempselect". $tempselect; die;
                            $isTemperatureMuted = checkTemperatureMute($mutedetails, 1, $change->lastupdated);
                            if ($isTemperatureMuted) {
                                $temp1 = 'Muted';
                                $temp1_mute_reading++;
                            } else {
                                $temp1_nonmute_reading++;
                                $s1 = "analog" . $vehicle->tempsen1;
                                if ($change->$s1 != 0) {
                                    $objTemp->rawtemp = $change->$s1;
                                    $temp1 = getTempUtil($objTemp);
                                    $sumTEMP1 += $temp1;

                                    // echo "temperature - ".$temp1."<br>";  die;
                                    // echo NORMAL_MIN_TEMP; die;
                                    if ($temp1 == 0 || $temp1 > NORMAL_MAX_TEMP || $temp1 < NORMAL_MIN_TEMP) {
                                        if ($temp1 == 0) {
                                            $temp1 = 'Wirecut';
                                        } else {
                                            $temp1 = 'Bad Data';
                                        }
                                        $temp1_bad_reading++;
                                    } elseif ($temp1 != 0) {
                                        $temp1_min = set_summary_min_temp($temp1);
                                        $temp1_max = set_summary_max_temp($temp1);

                                        // echo "<br>min". $temp1_min. "max ". $temp1_max; die;
                                        // $non_complaince_check = 0;
                                        if ($temp1 > $min_max_temp1['temp_max_limit'] || $temp1 < $min_max_temp1['temp_min_limit']) {
                                            if ($non_complaince_check1 == 5) {
                                                //echo "hello i reached".$non_complaince_check; //die;
                                                // $temp1_non_comp_count++;
                                                $temp1_non_comp_count = $temp1_non_comp_count + 5;
                                                $non_complaince_check1 = 1;
                                            } else {
                                                //echo "<br>temp is ".$temp1." else ".$non_complaince_check."<br>";
                                                $non_complaince_check1++;
                                            }

                                            //$temp1_non_comp_count++;
                                            //echo "check non compliance - ".$non_complaince_check."<br>";
                                            //echo "count non compliance - ".$temp1_non_comp_count."<br>";
                                        } else {
                                            $non_complaince_check1 = 1;
                                        }
                                        $temp1 = $temp1; //. speedConstants::TEMP_DEGREE;
                                    }
                                } else {
                                    $temp1_bad_reading++;
                                }
                            }
                            $temp1totalreadingcount++;
                        } else {
                            $temp1 = speedConstants::TEMP_NOTACTIVE;
                        }
                        $finalreport->temp1 = "" . round($temp1, 2);
                        // $tdstring = "<td>" . $temp1 . "</td>" . $tdstring;
                }
                /* Don't show the entire log of 1 min data when customer pulls out this interval for compliance only */
                if ($interval != "1") {
                    // $display .= $tdstring;
                    /* $finalreport->temp1= $temp1;
                $finalreport->temp2= $temp2;
                $finalreport->temp3= $temp3;
                 */
                }
                // $display .= '</tr>';
                $finaldata[] = $finalreport;
                $tr++;
            }
        }

        $t1 = $this->getName_ByType($vehicle->n1, $customerNo);
        $t2 = $this->getName_ByType($vehicle->n2, $customerNo);
        $t3 = $this->getName_ByType($vehicle->n3, $customerNo);
        $t4 = $this->getName_ByType($vehicle->n4, $customerNo);
        $t1 = ($t1 == '') ? 'Temperature 1' : $t1;
        $t2 = ($t2 == '') ? 'Temperature 2' : $t2;
        $t3 = ($t3 == '') ? 'Temperature 3' : $t3;
        $t4 = ($t4 == '') ? 'Temperature 4' : $t4;
        $temphtml = '';
        $span = null;

        $finalreport->t1 = $t1;
        // print_r($finalreport);
        $finalRecords = array();
        $statistics1 = array();
        $statistics2 = array();
        $statistics3 = array();
        $statistics4 = array();
        // echo "<br>switch no - ". $pass;
        switch ($pass) {
            case 4:
                $span = isset($span) ? $span : 4;
                $goodcount = $temp4_nonmute_reading - $temp4_bad_reading;
                if ($temp4_nonmute_reading == 0 || $goodcount == 0) {
                    $abv_compliance = "Not Applicable";
                    $within_compliance = "Not Applicable";
                } else {
                    $abv_compliance = round(($temp4_non_comp_count / $goodcount) * 100, 2);
                    $compliance_count = $goodcount - $temp4_non_comp_count;
                    $within_compliance = round($compliance_count / $goodcount * 100, 2);
                }
                $temp4_min = ($temp4_min != '' ? $temp4_min . " &deg;C" : "N/A");
                $temp4_max = ($temp4_max != '' ? $temp4_max . " &deg;C" : "N/A");

                $statistics4["temperature"] = $t4;
                $statistics4["temp_min_limit"] = $min_max_temp4['temp_min_limit'];
                $statistics4["temp_max_limit"] = $min_max_temp4['temp_max_limit'];

                $statistics4["temp4_min"] = $temp4_min;
                $statistics4["temp4_max"] = $temp4_max;
                if ($temp4totalreadingcount > 0) {
                    $statistics4["average_temprature"] = round(($sumTEMP4 / $temp4totalreadingcount), 2);
                } else {
                    $statistics4["average_temprature"] = $sumTEMP4;
                }
                $statistics4["temp4totalreadingcount"] = $temp4totalreadingcount;
                $statistics4["temp4_mute_reading"] = $temp4_mute_reading;
                $statistics4["temp4_non_comp_count"] = $temp4_non_comp_count;
                $statistics4["temp4_bad_reading"] = $temp4_bad_reading;
                $statistics4["abv_compliance"] = $abv_compliance;
                $statistics4["within_compliance"] = $within_compliance;
                $statistics4["kinetic_temprature"] = "";

                $finalRecords['summary4'] = $statistics4;

            //// checked condition comming from session or tempselect(from select box)
            /* if((isset($arr[0]) && $arr[0]==4) && $arr[1]!="all" ){
            break;
             */
            case 3:
                $span = isset($span) ? $span : 3;
                $goodcount = $temp3_nonmute_reading - $temp3_bad_reading;
                if ($temp3_nonmute_reading == 0 || $goodcount == 0) {
                    $abv_compliance = "Not Applicable";
                    $within_compliance = "Not Applicable";
                } else {
                    $abv_compliance = round(($temp3_non_comp_count / $goodcount) * 100, 2);
                    $compliance_count = $goodcount - $temp3_non_comp_count;
                    $within_compliance = round($compliance_count / $goodcount * 100, 2);
                }
                $temp3_min = ($temp3_min != '' ? $temp3_min . " &deg;C" : "N/A");
                $temp3_max = ($temp3_max != '' ? $temp3_max . " &deg;C" : "N/A");

                $statistics3["temperature"] = $t3;
                $statistics3["temp_min_limit"] = $min_max_temp3['temp_min_limit'];
                $statistics3["temp_max_limit"] = $min_max_temp3['temp_max_limit'];

                $statistics3["temp3_min"] = $temp3_min;
                $statistics3["temp3_max"] = $temp3_max;
                if ($temp3totalreadingcount > 0) {
                    $statistics3["average_temprature"] = round(($sumTEMP3 / $temp3totalreadingcount), 2);
                } else {
                    $statistics3["average_temprature"] = $sumTEMP3;
                }
                $statistics3["temp3totalreadingcount"] = $temp3totalreadingcount;
                $statistics3["temp3_mute_reading"] = $temp3_mute_reading;
                $statistics3["temp3_non_comp_count"] = $temp3_non_comp_count;
                $statistics3["temp3_bad_reading"] = $temp3_bad_reading;
                $statistics3["abv_compliance"] = $abv_compliance;
                $statistics3["within_compliance"] = $within_compliance;
                $statistics3["kinetic_temprature"] = "";

                $finalRecords['summary3'] = $statistics3;

            //// checked condition comming from session or tempselect(from select box)
            /* if((isset($arr[0]) && $arr[0]==3) && $arr[1]!="all" ){
            break;
             */
            case 2:
                $span = isset($span) ? $span : 2;
                $goodcount = $temp2_nonmute_reading - $temp2_bad_reading;
                if ($temp2_nonmute_reading == 0 || $goodcount == 0) {
                    $abv_compliance = "Not Applicable";
                    $within_compliance = "Not Applicable";
                } else {
                    $abv_compliance = round(($temp2_non_comp_count / $goodcount) * 100, 2);
                    $compliance_count = $goodcount - $temp2_non_comp_count;
                    $within_compliance = round($compliance_count / $goodcount * 100, 2);
                }
                $temp2_min = ($temp2_min != '' ? $temp2_min . " &deg;C" : "N/A");
                $temp2_max = ($temp2_max != '' ? $temp2_max . " &deg;C" : "N/A");

                $statistics2["temperature"] = $t2;
                $statistics2["temp_min_limit"] = $min_max_temp2['temp_min_limit'];
                $statistics2["temp_max_limit"] = $min_max_temp2['temp_max_limit'];

                $statistics2["temp2_min"] = $temp2_min;
                $statistics2["temp2_max"] = $temp2_max;
                if ($temp2totalreadingcount > 0) {
                    $statistics2["average_temprature"] = round(($sumTEMP2 / $temp2totalreadingcount), 2);
                } else {
                    $statistics2["average_temprature"] = $sumTEMP2;
                }
                $statistics2["temp2totalreadingcount"] = $temp2totalreadingcount;
                $statistics2["temp2_mute_reading"] = $temp2_mute_reading;
                $statistics2["temp2_non_comp_count"] = $temp2_non_comp_count;
                $statistics2["temp2_bad_reading"] = $temp2_bad_reading;
                $statistics2["abv_compliance"] = $abv_compliance;
                $statistics2["within_compliance"] = $within_compliance;
                $statistics2["kinetic_temprature"] = "";

                $finalRecords['summary2'] = $statistics2;

            //// checked condition comming from session or tempselect(from select box)
            /* if((isset($arr[0]) && $arr[0]==2) && $arr[1]!="all" ){
            break;
             */
            case 1:
                $span = isset($span) ? $span : 1;
                $goodcount = $temp1_nonmute_reading - $temp1_bad_reading;
                if ($temp1_nonmute_reading == 0 || $goodcount == 0) {
                    $abv_compliance = "Not Applicable";
                    $within_compliance = "Not Applicable";
                } else {
                    // echo "<br> total count ". $temp1_non_comp_count." and good count is ". $goodcount;
                    $abv_compliance = round(($temp1_non_comp_count / $goodcount) * 100, 2);
                    $compliance_count = $goodcount - $temp1_non_comp_count;
                    $within_compliance = round($compliance_count / $goodcount * 100, 2);
                }
                $temp1_min = ($temp1_min != '' ? $temp1_min . " &deg;C" : "N/A");
                $temp1_max = ($temp1_max != '' ? $temp1_max . " &deg;C" : "N/A");

                $statistics1["temperature"] = $t1;
                // echo "t1 nljbj ".$t1;
                $statistics1["temp_min_limit"] = $min_max_temp1['temp_min_limit'];
                $statistics1["temp_max_limit"] = $min_max_temp1['temp_max_limit'];

                $statistics1["temp1_min"] = $temp1_min;
                $statistics1["temp1_max"] = $temp1_max;
                if ($temp1totalreadingcount > 0) {
                    $statistics1["average_temprature"] = round(($sumTEMP1 / $temp1totalreadingcount), 2);
                } else {
                    $statistics1["average_temprature"] = $sumTEMP1;
                }
                $statistics1["temp1totalreadingcount"] = $temp1totalreadingcount;
                $statistics1["temp1_mute_reading"] = $temp1_mute_reading;
                $statistics1["temp1_non_comp_count"] = $temp1_non_comp_count;
                $statistics1["temp1_bad_reading"] = $temp1_bad_reading;
                $statistics1["abv_compliance"] = $abv_compliance;
                $statistics1["within_compliance"] = $within_compliance;
                $statistics1["kinetic_temprature"] = "";

                $finalRecords['summary1'] = $statistics1;
        }

        //$finalreport->statistics = $statistics;
        // print_r($statistics);

        //$finalRecords['rows'] = $finaldata;

        //return $display;

        return $finalRecords;
    }

    public function getName_ByType($nid, $customerNo) {
        $vehiclemanager = new VehicleManager($customerNo);
        $vehicledata = $vehiclemanager->getNameForTemp($nid);
        return $vehicledata;
    }

    public function checkClientCode($userkey) {
        $retarray = array();
        $retarray['status'] = "unsuccessful";
        if (is_numeric($userkey)) {
            $sql = "SELECT  e.id
                            , e.ecode
                            , c.use_fuel_sensor
                            , e.customerno
                            , c.multiauth
                            , c.customercompany
                            , e.ecodeemail
                            , e.ecodesms
                            , c.use_door_sensor
                            , c.use_ac_sensor
                            , c.use_genset_sensor
                    FROM elixiacode e
                    INNER JOIN customer c on c.customerno = e.customerno
                    WHERE e.ecode='" . $userkey . "' AND e.isdeleted=0 AND c.isoffline = 0";

            $record = $this->db->query($sql, __FILE__, __LINE__);
            $row = $this->db->fetch_array($record);

            if ($row['ecode'] != "") {
                $devices = $this->checkforvalidity($row["customerno"]);
                $initday = 0;
                if (isset($devices)) {
                    foreach ($devices as $thisdevice) {
                        $days = $this->check_validity_login($thisdevice->expirydate, $thisdevice->today);
                        if ($days > 0) {
                            $initday = $days;
                        }
                    }
                    if ($initday > 0) {
                        $retarray['status'] = "successful";
                        $retarray['customerno'] = $row["customerno"];
                        $retarray['userid'] = $row["id"];
                        $retarray['multiauth'] = $row["multiauth"];
                        $retarray['customercompany'] = $row["customercompany"];
                        $retarray['role'] = 'clientCode';
                        $retarray['roleid'] = $row["ecode"];
                        $retarray['ecodeemail'] = $row["ecodeemail"];
                        $retarray['ecodesms'] = $row["ecodesms"];
                        $retarray['use_door_sensor'] = $row["use_door_sensor"];
                        $retarray['use_ac_sensor'] = $row["use_ac_sensor"];
                        $retarray['use_genset_sensor'] = $row["use_genset_sensor"];
                    }
                }
            }
        }

        return $retarray;
    }
}

?>
