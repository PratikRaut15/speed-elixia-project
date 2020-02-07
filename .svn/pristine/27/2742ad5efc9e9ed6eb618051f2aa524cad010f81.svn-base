<?php

include_once '../api/V7/class/database.inc.php';
include_once '../api/V7/class/global.config.php';
include_once '../../lib/autoload.php';
include_once '../../lib/system/utilities.php';
define("SP_GET_ODOMETER_READING", "get_odometer_reading");

define("SP_GET_VEHICLES_DRIVERS_USERS", "get_vehicles_drivers_users");
define("SP_MAP_VEHICLE_DRIVER_USER", "map_vehicle_user_driver");
define("SP_INSERT_SMSLOG", "insert_smslog");
        const PER_SMS_CHARACTERS = 160;

class drivermap {

    function __construct() {
        $this->db = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, SPEEDDB);
    }

    function get_vehicles_drivers_users_web() {
        $arr_p = array();
        $customerno = $_SESSION['customerno'];
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
        return $arr_p;
    }

    function map_vehicle_driver_user($vehicleid, $userids, $driverid, $drivername) {
        $customermanager = new CustomerManager();
        $smsStatus = new SmsStatus();

        $SMS_TEMPLATE_FOR_VEHICLE_USER_DRIVER_MAPPING = "Dear {{USERNAME}}, {{VEHICLENO}} has been allotted for your pickup. Driver Name: {{DRIVERNAME}} ({{DRIVERPHONE}})";
        $userid = implode(',', $userids);
        $driverid = isset($driverid) ? $driverid : 0;
        $drivername = isset($drivername) ? $drivername : '';
        $todaysdate = date('Y-m-d H:i:s');
        $customerno = $_SESSION['customerno'];
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
                    $smsText = $SMS_TEMPLATE_FOR_VEHICLE_USER_DRIVER_MAPPING;
                    $smsText = str_replace("{{USERNAME}}", $username, $smsText);
                    $smsText = str_replace("{{VEHICLENO}}", $vehicleno, $smsText);
                    $smsText = str_replace("{{DRIVERNAME}}", $drivername, $smsText);
                    $smsText = str_replace("{{DRIVERPHONE}}", $driverphone, $smsText);
                    $smsStatus->customerno = $customerno;
                    $smsStatus->userid = $userid;
                    $smsStatus->vehicleid = $vehicleid;
                    $smsStatus->mobileno = $userphone;
                    $smsStatus->message = $smsText;
                    $smsStatus->cqid = 0;
                    $smsstat = $customermanager->getSMSStatus($smsStatus);
                    if ($smsstat == 0) {
                        $response = '';
                        $isSMSSent = sendSMSUtil($userphone, $smsText, $response);
                        $moduleid = 3;
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
                $arr_p['message'] = "SMS sent. "; //. (($smsLogId > 0) ? "SMS logged" : " SMS logging failed.");
            } else {
                $arr_p['message'] = trim($errorMessage);
            }
        }
        return json_encode($arr_p);
    }

}

?>
