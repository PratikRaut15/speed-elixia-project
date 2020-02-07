<?php

class VODevices {

}

define("SP_GET_ODOMETER_READING", "get_odometer_reading");

class api {

    var $status;
    var $status_time;

    // construct
    function __construct() {
        $this->db = new database(DATABASE_HOST, DATABASE_PORT, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
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

    //find location
    function location($lat, $long, $customerno, $usegeolocation) { //OVER_QUERY_LIMIT
        $address = NULL;
        if ($lat != '0' && $long != '0') {
            if ($usegeolocation == 2) {
                $API = "http://www.speed.elixiatech.com/location.php?lat=" . $lat . "&long=" . $long . "";
                $location = json_decode(file_get_contents("$API&sensor=false"));
                if (isset($location) && $location->status != 'OVER_QUERY_LIMIT') {
                    @$address = "Near " . $location->results[0]->formatted_address;
                    if (isset($location->results[0]->formatted_address) && $location->results[0]->formatted_address == "") {
                        $GeoCoder_Obj = new GeoCoder($customerno);
                        $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
                    }
                } else {
                    $address = $this->get_location_bylatlong($lat, $long, $customerno);
                }
            } else {
                $address = $this->get_location_bylatlong($lat, $long, $customerno);
            }
        }
        return $address;
    }

    public function get_location_bylatlong($lat, $long, $customerno) {
        $latint = floor($lat);
        $longint = floor($long);

        $geoloc_query = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( " . $lat . "- `lat` ) * PI( ) /180 /2 ) , 2 ) +
                         COS( " . $lat . " * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( " . $long . " - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
                         AS distance FROM geotest WHERE `latfloor` = " . $latint . " AND `longfloor` = " . $longint . " HAVING distance <2 AND customerno = " . $customerno . " ORDER BY distance LIMIT 0,1 ";
        $record = $this->db->query($geoloc_query, __FILE__, __LINE__);
        $record_counts = $this->db->query($geoloc_query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record_counts);
        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                if ($row['distance'] > 1) {
                    $location_string = round($row['distance'], 2) . " Km from " . $row['location'] . ", " . $row['city'] . ", " . $row['state'];
                } else {
                    $location_string = "Near " . $row['location'] . ", " . $row['city'] . ", " . $row['state'];
                }
            }
            return $location_string;
        } else {

            $geolocation_query = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( " . $lat . "- `lat` ) * PI( ) /180 /2 ) , 2 ) +
                             COS( " . $lat . " * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( " . $long . " - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
                             AS distance FROM geotest WHERE `latfloor` = " . $latint . " AND `longfloor` = " . $longint . " HAVING distance <10 AND customerno IN(0, " . $customerno . ") ORDER BY distance LIMIT 0,1 ";
            $records = $this->db->query($geolocation_query, __FILE__, __LINE__);
            $record_countss = $this->db->query($geolocation_query, __FILE__, __LINE__);
            $row_counts = $this->db->num_rows($record_countss);
            if ($row_counts > 0) {
                while ($row = $this->db->fetch_array($records)) {
                    if ($row['distance'] > 1) {
                        $location_string = round($row['distance'], 2) . " Km from " . $row['location'] . ", " . $row['city'] . ", " . $row['state'];
                    } else {
                        $location_string = "Near " . $row['location'] . ", " . $row['city'] . ", " . $row['state'];
                    }
                }
                return $location_string;
            } else {
                return "google temporarily down";
            }
            return null;
        }
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

    function checkforvalidity($customerno) {
        $devices = Array();
        $Query = "SELECT deviceid,expirydate, Now() as today FROM `devices` where customerno=%d";
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
        $realtime = strtotime($currentdate);
        $expirytime = strtotime($expirydate);
        $diff = $expirytime - $realtime;
        return $diff;
    }

    // checks for login
    function check_login($username, $password) {
        $sql = "select *," . TBL_ADMIN_CUSTOMER . ".customerno as customer_no, (SELECT groupman.isdeleted FROM groupman WHERE groupman.userid = " . TBL_ADMIN_USER . ".userid
                            ORDER BY groupman.gmid DESC LIMIT 0 , 1) AS grpdel from " . TBL_ADMIN_USER . " INNER JOIN " . TBL_ADMIN_CUSTOMER . " ON " . TBL_ADMIN_CUSTOMER . ".customerno = " . TBL_ADMIN_USER . ".customerno INNER JOIN `android_version` where username='" . $username . "' AND isdeleted=0 limit 1";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $row = $this->db->fetch_array($record);
        $retarray = array();
        if ($username == $row['username'] and $password == $row['password'] AND $row['grpdel'] != 1) {
            $devices = $this->checkforvalidity($row['customer_no']);
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
                $retarray['userkey'] = $row['userkey'];
                $this->update_push_android_chk($row['userkey'], 1);
                $this->update_push_android_chk_main($row['userkey'], 1);
                $retarray['customerno'] = $row['customer_no'];
                $retarray['username'] = $row['username'];
                $retarray['customername'] = $row['customercompany'];
                $retarray['version'] = $row['version'];
                $retarray['role'] = $row['role'];

                $today = date("Y-m-d H:i:s");
                $sql = "UPDATE user SET lastlogin_android='" . $today . "' where userkey = '" . $row['userkey'] . "' AND customerno= '" . $row['customer_no'] . "' LIMIT 1";
                $this->db->query($sql, __FILE__, __LINE__);
            } else {
                $retarray['status'] = "failure";
                $retarray['version'] = '';
                $retarray['customername'] = null;
                $retarray['userkey'] = 0;
            }
        } else {
            $retarray['status'] = "failure";
            $retarray['version'] = '';
            $retarray['customername'] = null;
            $retarray['userkey'] = 0;
        }
        echo json_encode($retarray);
        return $retarray;
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

    function device_list($userkey) {

        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";

        if ($validation['status'] == "successful") {
            // successful
            $customerno = $validation["customerno"];
            $arr_p['groups'] = $this->pull_groups($userkey);
            $arr_p['checkpoints_count'] = $this->get_checkpoint_customer_count($userkey);
            $arr_p['checkpointsman_count'] = $this->get_checkpoints_count($userkey);
            if ($arr_p['checkpoints_count'] == 1) {
                $arr_p['checkpoints'] = $this->get_checkpoint_customer($customerno, $userkey);
            }
            $groupidsql = "SELECT * ,groupman.isdeleted as gmdel,groupman.groupid as gmgrpid, (SELECT groupman.isdeleted FROM groupman WHERE groupman.userid = user.userid
                                                ORDER BY groupman.gmid DESC LIMIT 0 , 1) AS grpdel FROM user
                                                LEFT OUTER JOIN groupman ON groupman.userid = user.userid
                                                WHERE user.userkey =$userkey
                                                ORDER BY groupman.gmid DESC";
            $recordgrp = $this->db->query($groupidsql, __FILE__, __LINE__);
            $groupids = array();
            while ($rowgrp = $this->db->fetch_array($recordgrp)) {
                if ($rowgrp['gmdel'] == 0) {
                    $groupid = $rowgrp['gmgrpid'];
                    $groupids[] = $groupid;
                }
            }

            $firstgroup = array_shift(array_values($groupids));

            $sql = "SELECT unit.humidity, vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max, vehicle.kind, vehicle.groupid, unit.tempsen1, unit.tempsen2, unit.analog1, unit.analog2, unit.analog3, unit.analog4,
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
                                                            AND vehicle.isdeleted=0 and driver.isdeleted=0 and devices.lastupdated <> '0000-00-00 00:00:00' ORDER BY devices.lastupdated DESC";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            $json_p = array();
            $x = 0;
            while ($row = $this->db->fetch_array($record)) {
                if ($firstgroup == '') {
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
                        if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0') {
                            if ($row['humidity'] != '0') {
                                $json_p[$x]['temp1'] = $this->gethumidity($row['analog' . $row['tempsen1']]);
                            } else {
                                $json_p[$x]['temp1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                            }
                        } else
                            $json_p[$x]['temp1'] = '0';
                    }

                    if ($row['temp_sensors'] == '2') {
                        if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0')
                            if ($row['humidity'] != '0') {
                                $json_p[$x]['temp1'] = $this->gethumidity($row['analog' . $row['tempsen1']]);
                            } else {
                                $json_p[$x]['temp1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                            } else
                            $json_p[$x]['temp1'] = '0';

                        if ($row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0')
                            $json_p[$x]['temp2'] = $this->gettemp($row['analog' . $row['tempsen2']]);
                        else
                            $json_p[$x]['temp2'] = '0';
                    }

                    if ($row['humidity'] != '0') {
                        $json_p[$x]['temp2'] = $this->gethumidity($row['analog' . $row['humidity']]);
                    }

                    $x++;
                } else if (in_array($row['veh_grpid'], $groupids)) {
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
                    $json_p[$x]['stoppage_flag'] = $row['stoppage_flag'];
                    $json_p[$x]['distance'] = $this->distance($row['customer_no'], $row['unitno']);
                    if ($row['acsensor'] == '1') {
                        if ($row['digitalio'] == '0') {
                            if ($row["is_ac_opp"] == '0') {
                                $json_p[$x]['acstatus'] = "1";
                            } else {
                                $json_p[$x]['acstatus'] = "0";
                            }
                        } else
                        if ($row["is_ac_opp"] == '0') {
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
                            if ($row['humidity'] != '0') {
                                $json_p[$x]['temp1'] = $this->gethumidity($row['analog' . $row['tempsen1']]);
                            } else {
                                $json_p[$x]['temp1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                            }
                        } else
                            $json_p[$x]['temp1'] = '0';
                    }

                    if ($row['temp_sensors'] == '2') {
                        if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0')
                            if ($row['humidity'] != '0') {
                                $json_p[$x]['temp1'] = $this->gethumidity($row['analog' . $row['tempsen1']]);
                            } else {
                                $json_p[$x]['temp1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                            } else
                            $json_p[$x]['temp1'] = '0';

                        if ($row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0')
                            $json_p[$x]['temp2'] = $this->gettemp($row['analog' . $row['tempsen2']]);
                        else
                            $json_p[$x]['temp2'] = '0';
                    }

                    if ($row['humidity'] != '0') {
                        $json_p[$x]['temp2'] = $this->gethumidity($row['analog' . $row['humidity']]);
                    }
                    $x++;
                }
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

    public function checkvalidity($expirydate) {
        $today = date('Y-m-d H:i:s');
//        $today = add_hours($today, 0);
        if (strtotime($today) <= strtotime($expirydate)) {
            return TRUE;
        } else {
            return FALSE;
        }
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

    public function client_code_details($ecodeid) {
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
        echo $devicesQuery = sprintf($Query, $ecodeid);
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

    public function update_push_android_chk($userkey, $val) {
        $sql = "UPDATE user SET chkmanpushandroid = " . $val . " WHERE userkey = '" . $userkey . "'";
        $this->db->query($sql, __FILE__, __LINE__);
    }

    public function update_push_android_chk_main($userkey, $val) {
        $sql = "UPDATE user SET chkpushandroid = " . $val . " WHERE userkey = '" . $userkey . "'";
        $this->db->query($sql, __FILE__, __LINE__);
    }

    public function get_checkpoints($vehicleid) {
        $Query = "SELECT checkpoint.checkpointid, checkpoint.cname, checkpoint.cgeolat, checkpoint.cgeolong, checkpoint.crad FROM checkpointmanage INNER JOIN checkpoint ON checkpoint.checkpointid = checkpointmanage.checkpointid WHERE checkpointmanage.isdeleted = 0 AND checkpointmanage.vehicleid = %d";
        $devicesQuery = sprintf($Query, $vehicleid);
        $record = $this->db->query($devicesQuery, __FILE__, __LINE__);
        $json_p = Array();
        $x = 0;
        while ($row = $this->db->fetch_array($record)) {
            $json_p[$x]['checkpointid'] = $row['checkpointid'];
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

    function device_list_details($userkey, $vehicleid) {

        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";

        if ($validation['status'] == "successful") {
            $arr_p['checkpointsman_count'] = $this->get_checkpoints_count($userkey);
            // successful
            $customerno = $validation["customerno"];
            $sql = "SELECT unit.humidity, vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max, vehicle.kind, vehicle.groupid, unit.tempsen1, unit.tempsen2, unit.analog1, unit.analog2, unit.analog3, unit.analog4,
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
                                                            AND vehicle.isdeleted=0 and driver.isdeleted=0 and devices.lastupdated <> '0000-00-00 00:00:00' AND vehicle.vehicleid = $vehicleid ORDER BY devices.lastupdated DESC";
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
                        if ($row['humidity'] != '0') {
                            $json_p[$x]['temp1'] = $this->gethumidity($row['analog' . $row['tempsen1']]);
                        } else {
                            $json_p[$x]['temp1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                        } else
                        $json_p[$x]['temp1'] = '0';
                }

                if ($row['temp_sensors'] == '2') {
                    if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0')
                        if ($row['humidity'] != '0') {
                            $json_p[$x]['temp1'] = $this->gethumidity($row['analog' . $row['tempsen1']]);
                        } else {
                            $json_p[$x]['temp1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                        } else
                        $json_p[$x]['temp1'] = '0';

                    if ($row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0')
                        $json_p[$x]['temp2'] = $this->gettemp($row['analog' . $row['tempsen2']]);
                    else
                        $json_p[$x]['temp2'] = '0';
                }

                if ($row['humidity'] != '0') {
                    $json_p[$x]['temp2'] = $this->gethumidity($row['analog' . $row['humidity']]);
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

    function updateLogin($userkey) {
        $today = date('Y-m-d H:i:s');
        $sql = "select * from " . TBL_ADMIN_USER . " where userkey='" . $userkey . "' AND isdeleted = 0";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $row = $this->db->fetch_array($record);
        if ($row['userkey'] != "") {
            $userid = $row['userid'];
            $customerno = $row['customerno'];
            $sqlInsert = "insert into login_history(userid, customerno,type,timestamp)values($userid,$customerno,1,'" . $today . "')";
            $this->db->query($sqlInsert, __FILE__, __LINE__);
        }
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
                            $Datacap->DriverName = $vehicle->drivername;
                            $Datacap->Group = $this->getgroupname_byuid($rowa['uid']);
                            $Datacap->SL = $this->location($rowa['first_dev_lat'], $rowa['first_dev_long'], $customerno, $row['use_geolocation']);
                            $Datacap->EL = $this->location($rowa['dev_lat'], $rowa['dev_long'], $customerno, $row['use_geolocation']);
                            $Datacap->DT = round($rowa['totaldistance'] / 1000, 2) . " KM";
                            $Datacap->AS = round($rowa['avgspeed'] / 1000, 2);
                            $Datacap->RT = $hours . ":" . $minutes;
                            if ($rowa['gensetusage'] > 0) {

                                if ($row['use_ac_sensor'] == 1) {
                                    $Datacap->genset_name = "ACU";
                                } else if ($row['use_genset_sensor'] == 1) {
                                    $Datacap->genset_name = "GNU";
                                } else if ($row['use_door_sensor'] == 1) {
                                    $Datacap->genset_name = "DSU";
                                } else {
                                    $Datacap->genset_name = "";
                                }

                                $Datacap->genset = $hourss . ":" . $minutess;
                            }
                            $Datacap->OS = $rowa['overspeed'];
                            $Datacap->TS = $rowa['topspeed'];
                            $Datacap->TSL = $this->location($rowa['topspeed_lat'], $rowa['topspeed_long'], $customerno, $row['use_geolocation']);
                            $Datacap->HB = $rowa['harsh_break'];
                            $Datacap->SA = $rowa['sudden_acc'];
                            if ($rowa['towing'] == 0) {
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
                    //$location = "../../customer/".$_SESSION['customerno']."/reports/dailyreport.sqlite";
                } else {
                    $location = "../../../customer/" . $customerno . "/reports/dailyreport.sqlite";
                }

                if (file_exists($location)) {
                    $path = "sqlite:$location";
                    $db = new PDO($path);
                    $REPORT = array();
                    $sqlday = date("dmy", strtotime($date));
                    $query = "SELECT * from A$sqlday";
                    $result = $db->query($query);
                    if (isset($result) && $result != "") {
                        foreach ($result as $row) {

                            $hours = floor($row['runningtime'] / 60);
                            $minutes = $row['runningtime'] % 60;
                            if ($minutes < 10) {
                                $minutes = '0' . $minutes;
                            }
                            $hourss = floor($row['gensetusage'] / 60);
                            $minutess = $row['gensetusage'] % 60;
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
                            $Datacap->DriverName = $vehicle->drivername;
                            $Datacap->Group = $this->getgroupname_byuid($row['uid']);
                            $Datacap->SL = $this->location($row['first_dev_lat'], $row['first_dev_long'], $customerno, $rowlist['use_geolocation']);
                            $Datacap->EL = $this->location($row['dev_lat'], $row['dev_long'], $customerno, $rowlist['use_geolocation']);
                            $Datacap->DT = round($row['totaldistance'] / 1000, 2) . " KM";
                            $Datacap->AS = round($row['avgspeed'] / 1000, 2);
                            $Datacap->RT = $hours . ":" . $minutes;
                            if ($row['gensetusage'] > 0) {

                                if ($rowlist['use_ac_sensor'] == 1) {
                                    $Datacap->genset_name = "ACU";
                                } else if ($rowlist['use_genset_sensor'] == 1) {
                                    $Datacap->genset_name = "GNU";
                                } else if ($rowlist['use_door_sensor'] == 1) {
                                    $Datacap->genset_name = "DSU";
                                } else {
                                    $Datacap->genset_name = "";
                                }

                                $Datacap->genset = $hourss . ":" . $minutess;
                            }
                            $Datacap->OS = $row['overspeed'];
                            $Datacap->TS = $row['topspeed'];
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

        echo json_encode($arr_p);
        return json_encode($arr_p);
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

    public function getgroupname_byuid($uid) {
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
        $Query = 'SELECT * FROM vehicle WHERE customerno=' . $customerno . ' AND vehicleid=' . $vehicleid . ' AND isdeleted=0';
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

    function contractinfo($userkey, $pageno, $pagesize) {
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        //$arr_p['status'] = "unsuccessful";

        if ($validation['status'] == "successful") {
            $customerno = $validation['customerno'];

            $today = date('Y-m-d');
            $device = new VODevices();
            $Query = "SELECT c.totalsms, c.customerno, c.customername, c.smsleft, c.customercompany,count(unit.unitno) AS cunit                     FROM customer c
                 LEFT OUTER JOIN unit ON c.customerno=unit.customerno and unit.trans_statusid not in(10,22)
                 Where c.customerno = $customerno";
            $record = $this->db->query($Query, __FILE__, __LINE__);
            $row_count = $this->db->num_rows($record);
            if ($row_count > 0) {
                $row = $this->db->fetch_array($record);
                $device->unit = $row['cunit'];
            } else {
                $device->unit = 0;
            }

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
                $device->pending_amount = 0;
            }

            $SQL3 = "SELECT devices.deviceid,devices.customerno,devices.uid,count(simcardid) AS sim FROM devices
                     INNER JOIN unit ON unit.uid =devices.uid AND unit.trans_statusid =5
                     WHERE devices.customerno = $customerno";
            $record3 = $this->db->query($SQL3, __FILE__, __LINE__);
            $row_count3 = $this->db->num_rows($record3);
            if ($row_count3 > 0) {
                $row = $this->db->fetch_array($record3);

                $device->simcard = $row["sim"];
                if ($row["sim"] == "" || $row["sim"] == "0") {
                    $device->simcard = 0;
                }
            } else {
                $device->simcard = 0;
            }



            $SQL5 = "SELECT devices.expirydate from devices WHERE devices.customerno = $customerno and devices.expirydate >= '$today' Order by expirydate ASC limit 1";
            $record5 = $this->db->query($SQL5, __FILE__, __LINE__);
            $row_count5 = $this->db->num_rows($record5);
            if ($row_count5 > 0) {
                $row = $this->db->fetch_array($record5);

                $device->expiry_date = $row["expirydate"];
                if ($row["expirydate"] == "" || $row["expirydate"] == "0000-00-00") {
                    $device->expiry_date = '';
                }
            } else {
                $device->expiry_date = '';
            }

            $SQL6 = "SELECT count(devices.deviceid) as did from devices
            LEFT OUTER JOIN unit ON devices.uid=unit.uid and unit.trans_statusid not in(10,22)
              WHERE devices.customerno = $customerno and devices.expirydate = '$device->expiry_date' ";
            $record6 = $this->db->query($SQL6, __FILE__, __LINE__);
            $row_count6 = $this->db->num_rows($record6);
            if ($row_count6 > 0) {
                $row = $this->db->fetch_array($record6);

                $device->expire = $row["did"];
                if ($row["did"] == "" || $row["did"] == "0") {
                    $device->expire = 0;
                }
            } else {
                $device->expire = 0;
            }

            $SQL7 = "SELECT count(deviceid) as didr from devices
            inner join unit ON devices.uid=unit.uid and unit.trans_statusid not in(10,22)
            WHERE devices.customerno = $customerno and devices.expirydate < '$device->expiry' ";
            $record7 = $this->db->query($SQL7, __FILE__, __LINE__);
            $row_count7 = $this->db->num_rows($record7);
            if ($row_count7 > 0) {
                $row = $this->db->fetch_array($record7);

                $device->expired = $row["didr"];
            } else {
                $device->expired = 0;
            }
            $ServerIST = new DateTime();
            $ServerIST->modify('-60 minutes');
            $active = 0;
            $inactive = 0;
            $SQL8 = "SELECT lastupdated from devices
            inner join unit ON devices.uid=unit.uid and unit.trans_statusid not in(10,22)
            WHERE devices.customerno = $customerno";
            $record8 = $this->db->query($SQL8, __FILE__, __LINE__);
            $row_count8 = $this->db->num_rows($record8);
            if ($row_count8 > 0) {
                while ($row = $this->db->fetch_array($record8)) {
                    $device->lastupdated = new DateTime($row["lastupdated"]);
                    if ($device->lastupdated < $ServerIST) {
                        $inactive++;
                    } else {
                        $active++;
                    }
                }

                $device->inactive = $inactive;
                $device->active = $active;
            }


            $inwarranty = 0;
            $SQL9 = "SELECT warrantyexpiry from devices
            inner join unit ON devices.uid=unit.uid and unit.trans_statusid not in(10,22)
            WHERE devices.customerno = $customerno";
            $record9 = $this->db->query($SQL9, __FILE__, __LINE__);
            $row_count9 = $this->db->num_rows($record9);

            if ($row_count9 > 0) {
                while ($row = $this->db->fetch_array($record9)) {
                    $device->inwarranty = $row["warrantyexpiry"];
                    if ($device->inwarranty > $today) {
                        $inwarranty++;
                    }
                }

                $device->warranty = $inwarranty;
            }


            $SQL10 = "SELECT sum(paid_amt) as paid_amount FROM invoice WHERE customerno = $customerno";
            $record10 = $this->db->query($SQL10, __FILE__, __LINE__);
            $row_count10 = $this->db->num_rows($record10);
            if ($row_count10 > 0) {
                $row = $this->db->fetch_array($record10);
                $device->paid = $row["paid_amount"];
                if ($row["paid_amount"] == "" || $row["paid_amount"] == "0") {
                    $device->paid = 0;
                }
            } else {
                $device->paid = 0;
            }

            $new_install = 0;

            $SQL11 = "SELECT registeredon from devices
            inner join unit ON devices.uid=unit.uid and unit.trans_statusid not in(10,22)
            WHERE devices.customerno = $customerno";
            $record11 = $this->db->query($SQL11, __FILE__, __LINE__);
            $row_count11 = $this->db->num_rows($record11);

            if ($row_count11 > 0) {

                while ($row = $this->db->fetch_array($record11)) {

                    $device->registeredon = Date('Y-m-d', strtotime($row["registeredon"]));
                    if ($device->registeredon == $today) {

                        $new_install++;
                    }
                }
            } else {
                $new_install = 0;
            }
            $device->new_install = $new_install;

            $info = new VODevices();
            $info->PendingAmount = $device->pending_amount;
            $info->PaidAmount = $device->paid;
            $info->TotalDevices = $device->unit;
            $info->TotalSimcard = $device->simcard;
            $info->TotalVehiclesCoverd = $device->unit;
            $info->TotalDevicesActive = $device->active;
            $info->TotalDevicesInActive = $device->inactive;
            $info->TotalDevicesInWarranty = $device->warranty;




            $devices = Array();
            $Query = "SELECT devices.deviceid, vehicle.vehicleno,devices.installdate,vehicle.vehicleid, devices.invoiceno,
            devices.devicekey,simcard.simcardno as phone,devices.expirydate,devices.uid,devices.po_no,devices.po_date, devices.warrantyexpiry, unit.unitno, vehicle.groupid,
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
                    $device1->Simcard = $row['phone'];
                    $group = $this->getGroupname($row["groupid"], $customerno);
                    $device1->Group = $group['groupname'];
                    if ($row["installdate"] != '0000-00-00') {
                        $device1->DateOfInstallation = $row["installdate"];
                        $device1->DateWarrantyStart = $row["installdate"];
                    }
                    $device1->DateOfInstallation = '';
                    $device1->DateWarrantyStart = '';

                    if ($row["warrantyexpiry"] != '0000-00-00') {
                        $warrenty = date("d-M-Y", strtotime($row["warrantyexpiry"]));
                        $device1->DateWarrantyExpiry = $warrenty;
                    } else {
                        $device1->DateWarrantyExpiry = date("d-M-Y", strtotime($row["installdate"] . '+365 days'));
                    }

                    if ($row["warrantyexpiry"] != '0000-00-00') {
                        $expirydate = date("Y-m-d", strtotime($row["warrantyexpiry"]));
                        $dStart = new DateTime($today);
                        $dEnd = new DateTime($expirydate);
                        $dDiff = $dStart->diff($dEnd);
                        // echo $dDiff->format('%R'); // use for point out relation: smaller/greater

                        if ($today > $expirydate) {
                            $device1->WarrantyDaysLeft = "Expired";
                        } else {
                            $device1->WarrantyDaysLeft = $dDiff->days . " Days Left";
                        }
                    }
                    $device1->PoNo = $row["po_no"];
                    $device1->PoDate = $row["po_date"];


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

    function getGroupname($groupid, $customerno) {
        $Query = "SELECT groupid,groupname,cityid,code,address FROM `group` where customerno=$customerno AND groupid=$groupid AND isdeleted=0";

        $record = $this->db->query($Query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);


        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $group = new VODevices();
                $group->groupid = $row['groupid'];
                $group->groupname = $row['groupname'];
                $group->cityid = $row['cityid'];
                $group->code = $row['code'];
                $group->address = $row['address'];
            }

            return $group;
        }
        return null;
    }

    public function get_vehicle($vehicleid, $customerno) {
        $Query = "SELECT vehicle.vehicleno, driver.drivername, driver.driverphone FROM vehicle left join driver on driver.driverid=vehicle.driverid
            WHERE vehicle.customerno =$customerno AND vehicle.vehicleid=$vehicleid AND vehicle.isdeleted=0 group by vehicle.vehicleid";

        $record = $this->db->query($Query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);
        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $vehicle = new VODevices();
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverphone = $row['driverphone'];
            }
            return $vehicle;
        }
        return null;
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

                $info['Total No Of Vehicles'] = $total_vehicles;
                $info['Total Distance Travelled'] = $total_distance . " KM";
                $info['Total Overspeed Incidents'] = $total_overspeedincidents;
                $info['Total Fence Conflicts'] = $total_fenceconflicts;

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
            $response['overspeed'] = $i;
            $response['running'] = $j;
            $response['idleignon'] = $k;
            $response['idleignoff'] = $l;
            $response['inactive'] = $m;

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
            if (isset($checkpoints)) {
                foreach ($checkpoints as $chkpoint) {

                    $chklist[$chkpoint['cname']] = $chkpoint['count'];
                    $inside+=$chkpoint['count'];
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

}
?>
