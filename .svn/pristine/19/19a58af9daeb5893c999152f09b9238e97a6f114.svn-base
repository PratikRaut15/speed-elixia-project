<?php
require_once "database.inc.php";
date_default_timezone_set('Asia/Kolkata');
define("SP_GET_VEHICLEWAREHOUSE_DETAILS", "get_vehiclewarehouse_details_vts");
define("SP_AUTHENTICATE_FOR_LOGIN", "authenticate_for_login");
class api {
    const PER_SMS_CHARACTERS = 160;
    static $SMS_TEMPLATE_FOR_VEHICLE_USER_DRIVER_MAPPING = "Dear {{USERNAME}}, {{VEHICLENO}} has been allotted for your pickup. Driver Name: {{DRIVERNAME}} ({{DRIVERPHONE}})";
    public $status;
    public $status_time;
    //<editor-fold defaultstate="collapsed" desc="Constructor">
    // construct
    public function __construct() {
        $this->db = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, SPEEDDB);
    }

    // </editor-fold>
    //<editor-fold defaultstate="collapsed" desc="API Functions">
    public function dashboard($userkey, $pageIndex, $pageSize, $searchstring, $groupidparam, $isRequiredThirdParty, $dcDetails) {
        $isWareHouse = 0;
        $totalWareHouseCount = 0;
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $depotname = '';
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
                if (count($grouplist) == 1) {
                    $grpid = implode(',', $grouplist);
                    $recordgrp1 = $this->db->query($groupidsql, __FILE__, __LINE__);
                    while ($rowgrp = $this->db->fetch_array($recordgrp1)) {
                        if ($rowgrp['groupid'] == $grpid) {
                            $depotname = $rowgrp['groupname'];
                        }
                    }
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
                    . ",'" . $isRequiredThirdParty . "'";
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
                    $json_p[$x]['temp_sensors'] = $row['temp_sensors'];
                    $json_p[$x]['lastupdated'] = date('d-M-Y H:i A', strtotime($row['lastupdated']));
                    $json_p[$x]['location'] = $this->location($row['devicelat'], $row['devicelong'], $row['use_geolocation'], $row['customer_no']);
                    $vehicleStatus = "vehicleNormal"; // default green
                    $ServerIST_less1 = new DateTime();
                    $ServerIST_less1->modify('-60 minutes');
                    $lastupdated = new DateTime($row['lastupdated']);
                    if ($lastupdated < $ServerIST_less1) {
                        $vehicleStatus = "vehicleInactive"; //inactive grey
                    }
                    $json_p[$x]['eta'] = convertDateToFormat($dcDetails['eta'], speedConstants::TIME_Hi);
                    $json_p[$x]['arrives'] = $lastupdated->format('H:i');
                    $arrives = '';
                    $kmaway = '';
                    $vehicleDcDistance = $this->calculateDistanceFromDC($row['devicelat'], $row['devicelong'], $dcDetails['lat'], $dcDetails['lng']);
                    if ($vehicleDcDistance < $dcDetails['rad']) {
                        $kmaway = 'In DC';
                        $status = "<p style='color:green;'>Gate In</p>";
                        $arrives = $lastupdated->format(speedConstants::TIME_Hi);
                    } else {
                        $status = "<p style='color:green;'>EN Route</p>";
                        $isGoogleDistance = 1;
                        $distance = $this->calculateDistanceFromDC($row['devicelat'], $row['devicelong'], $dcDetails['lat'], $dcDetails['lng'], $isGoogleDistance, $row['customer_no']);
                        if (isset($distance['min']) && $distance['min'] != -1) {
                            //Get current date in temp variable
                            $tempETA = new DateTime();
                            //Replace time part
                            $standardETA = $tempETA->format(speedConstants::DATE_Ymd) . " " . $dcDetails['eta'];
                            //Create new date with above timestamp
                            $standardETADateTime = new DateTime();
                            $standardETADateTime->setTimestamp(strtotime($standardETA));
                            //Get current date
                            $currentDateTime = new DateTime();
                            //Add google driving mode minutes between 2 places
                            $currentDateTime->modify("+" . $distance['min'] . " minutes");
                            $realTimeETA = $currentDateTime->format(speedConstants::TIME_hia);
                            $interval = $standardETADateTime->diff($currentDateTime);
                            if ($interval->invert == 0) {
                                $minutes = $interval->days * 24 * 60;
                                $minutes += $interval->h * 60;
                                $minutes += $interval->i;
                                //GRACE Period of 30 mins
                                if ($minutes <= 30) {
                                    $status = "<p style='color:green;'>On Time</p>";
                                } else {
                                    $status = "<p style='color:red;'>Delay</p>";
                                }
                            }
                            $kmaway = $distance['km'];
                            $arrives = $currentDateTime->format(speedConstants::TIME_Hi);
                        }
                    }
                    $json_p[$x]['location'] = $kmaway;
                    $json_p[$x]['arrives'] = $arrives;
                    $json_p[$x]['status'] = $status;
                    $json_p[$x]['depotname'] = $depotname;
                    $x++;
                }
                // Free result set
                $records->close();
                $this->db->next_result();
                $arr_p['result'] = $json_p;
                $arr_p['totalWareHouseCount'] = $totalWareHouseCount;
                $arr_p['depotname'] = $depotname;
            } else {
                $arr_p['status'] = "expired";
            }
        } else {
            $arr_p['status'] = "unsuccessful";
        }
        return $arr_p;
    }

    public function dashboardDevices($userkey, $pageIndex, $pageSize, $searchstring, $groupidparam, $isRequiredThirdParty, $dcDetails) {
        $isWareHouse = 0;
        $totalWareHouseCount = 0;
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $depotname = '';
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
            if (count($grouplist) == 1) {
                $grpid = implode(',', $grouplist);
                while ($rowgrp = $this->db->fetch_array($recordgrp)) {
                    if ($rowgrp['groupid'] == $grpid) {
                        $depotname = $rowgrp['groupname'];
                    }
                }
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
                //$location = $this->location($row['devicelat'], $row['devicelong'], $row['use_geolocation'], $row['customer_no']);
                $vehicleStatus = "vehicleNormal"; // default green
                $ServerIST_less1 = new DateTime();
                $ServerIST_less1->modify('-60 minutes');
                $lastupdated = new DateTime($row['lastupdated']);
                if ($lastupdated < $ServerIST_less1) {
                    $vehicleStatus = "vehicleInactive"; //inactive grey
                }
                $json_p[$x]['eta'] = convertDateToFormat($dcDetails['eta'], speedConstants::TIME_Hi);
                $arrives = '';
                $kmaway = '';
                $vehicleDcDistance = $this->calculateDistanceFromDC($row['devicelat'], $row['devicelong'], $dcDetails['lat'], $dcDetails['lng']);
                if ($vehicleDcDistance < $dcDetails['rad']) {
                    $kmaway = 'In DC';
                    $status = "<p style='color:green;'>Gate In</p>";
                    $arrives = $lastupdated->format(speedConstants::TIME_Hi);
                } else {
                    $status = "<p style='color:green;'>EN Route</p>";
                    $isGoogleDistance = 1;
                    $distance = $this->calculateDistanceFromDC($row['devicelat'], $row['devicelong'], $dcDetails['lat'], $dcDetails['lng'], $isGoogleDistance, $row['customer_no']);
                    if (isset($distance['min']) && $distance['min'] != -1) {
                        //Get current date in temp variable
                        $tempETA = new DateTime();
                        //Replace time part
                        $standardETA = $tempETA->format(speedConstants::DATE_Ymd) . " " . $dcDetails['eta'];
                        //Create new date with above timestamp
                        $standardETADateTime = new DateTime();
                        $standardETADateTime->setTimestamp(strtotime($standardETA));
                        //Get current date
                        $currentDateTime = new DateTime();
                        //Add google driving mode minutes between 2 places
                        $currentDateTime->modify("+" . $distance['min'] . " minutes");
                        $realTimeETA = $currentDateTime->format(speedConstants::TIME_hia);
                        $interval = $standardETADateTime->diff($currentDateTime);
                        if ($interval->invert == 0) {
                            $minutes = $interval->days * 24 * 60;
                            $minutes += $interval->h * 60;
                            $minutes += $interval->i;
                            //GRACE Period of 30 mins
                            if ($minutes <= 30) {
                                $status = "<p style='color:green;'>On Time</p>";
                            } else {
                                $status = "<p style='color:red;'>Delay</p>";
                            }
                        }
                        $kmaway = $distance['km'];
                        $arrives = $currentDateTime->format(speedConstants::TIME_Hi);
                    }
                }
                $json_p[$x]['location'] = $kmaway;
                $json_p[$x]['arrives'] = $arrives;
                $json_p[$x]['status'] = $status;
                $json_p[$x]['depotname'] = $depotname;
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

    public function location($lat, $long, $usegeolocation, $customerno) {
        $address = NULL;
        $GeoCoder_Obj = new GeoCoder($customerno);
        $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
        return $address;
    }

    public function calculateDistanceFromDC($devicelat, $devicelong, $cgeolat, $cgeolong, $isGoogleDistance = 0, $customerno = 0) {
        if ($isGoogleDistance == 1) {
            $distance = array("min" => -1, "km" => -1);
            $url = signLocationUrl("https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $cgeolat . "," . $cgeolong . "&destinations=" . $devicelat . "," . $devicelong . "&mode=driving&language=pl-PLsensor=false&key=" . GOOGLE_MAP_API_KEY, ''); //echo "<br/><br/>";
            $json = file_get_contents($url);
            $details = json_decode($json, true);
            if (isset($details['rows'][0]['elements'][0]['status']) && $details['rows'][0]['elements'][0]['status'] == "OK") {
                $distance['min'] = ceil(($details['rows'][0]['elements'][0]['duration']['value']) / 60);
                $distance['km'] = round(($details['rows'][0]['elements'][0]['distance']['value']) / 1000, 2);
            }
            return $distance;
        } else {
            //Earth's mean radius in km
            $ERadius = 6371;
            //Difference between devicelatlong and checkpointlatlong
            $diffLat = $this->rad($cgeolat - $devicelat);
            $diffLong = $this->rad($cgeolong - $devicelong);
            //Converting between devicelatlong to radians
            $devlat_rad = $this->rad($devicelat);
            $devlong_rad = $this->rad($cgeolat);
            //Calculation Using Haversine's formula
            //Applying Haversine formula
            $a = sin($diffLat / 2) * sin($diffLat / 2) + cos($devlat_rad) * cos($devlong_rad) * sin($diffLong / 2) * sin($diffLong / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            //Distance
            $diffdist = $ERadius * $c;
            return $diffdist;
        }
    }

    public function rad($x) {
        return $x * pi() / 180;
    }

    // </editor-fold>
    //
}
?>