<?php

class DRIVER {

    function __construct() {

        $this->db = new database(DATABASE_HOST, DATABASE_PORT, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
    }

    public function PrepareSP($sp_name, $sp_params) {
        return "call " . $sp_name . "(" . $sp_params . ");";
    }

// checks for login 
    function check_login($username, $password) {

        $retarray = array();
        if (trim($username) == "" || trim($password) == "") {
            $retarray['status'] = "failure";
            $retarray['customername'] = null;
            $retarray['vehicleno'] = null;
            $retarray['userkey'] = 0;
        } else {
            $sql = sprintf("SELECT *,vehicle.vehicleno,customer.customercompany FROM driver 
                INNER JOIN vehicle ON vehicle.vehicleid=driver.vehicleid
                INNER JOIN customer ON customer.customerno=driver.customerno
                WHERE username= '%s' AND driver.isdeleted=0 limit 1", $username);
            $record = $this->db->query($sql, __FILE__, __LINE__);
            $row = $this->db->fetch_array($record);
            // print_r($row);
            if ($username == $row['username'] && $password == $row['password']) {
                $retarray['status'] = "successful";
                $retarray['userkey'] = $row['userkey'];
                $retarray['customerno'] = $row['customerno'];
                $retarray['customercompany'] = $row['customercompany'];
                $retarray['username'] = $row['username'];
                $retarray['vehicleno'] = $row['vehicleno'];
                $retarray['drivername'] = $row['drivername'];
                $retarray['istripstarted'] = $row['istripstarted'];
            } else {
                $retarray['status'] = "failure";
                $retarray['customername'] = null;
                $retarray['vehicleno'] = null;
                $retarray['userkey'] = 0;
            }
        }
        echo json_encode($retarray);
        return $retarray;
    }

    function start_trip($userkey, $startduration) {
        $data_status = array();
        $start_arr = array();
        $queryResult = 0;
        if (trim($userkey) == "" || trim($startduration) == "") {
            $data_status['status'] = "No userkey or duration";
        } else {
            $start = date("Y-m-d H:i:s", strtotime($startduration));
            $sql = sprintf("SELECT driver.userkey,driver.driverid,driver.vehicleid,driver.customerno,driver.trip_email,vehicle.odometer,devices.devicelat,devices.devicelong,devices.lastupdated FROM driver 
                    INNER JOIN vehicle ON vehicle.vehicleid=driver.vehicleid
                    INNER JOIN unit ON unit.vehicleid=driver.vehicleid
                    INNER JOIN devices ON unit.uid=devices.uid
                    WHERE devices.lastupdated < '%s' AND driver.userkey=%d AND vehicle.isdeleted=0 AND driver.isdeleted=0 
                    ORDER BY devices.lastupdated DESC LIMIT 1", $start, $userkey);
            try {
                $record = $this->db->query($sql, __FILE__, __LINE__);
                $row = $this->db->fetch_array($record);
                if (!empty($row)) {
                    $start_arr['driverid'] = $row['driverid'];
                    $start_arr['vehicleid'] = $row['vehicleid'];
                    $start_arr['customerno'] = $row['customerno'];
                    $start_arr['odometer'] = $row['odometer'];
                    $start_arr['devicelat'] = $row['devicelat'];
                    $start_arr['devicelong'] = $row['devicelong'];
                    $start_arr['trip_email'] = $row['trip_email'];

                    $datetime = date("Y-m-d H:i:s");

                    //Prepare parameters
                    $sp_params = "" . $start_arr['driverid'] . ""
                            . ",'" . $start . "'"
                            . "," . $start_arr['odometer'] . ""
                            . "," . $start_arr['devicelat'] . ""
                            . "," . $start_arr['devicelong'] . ""
                            . "," . $start_arr['driverid'] . ""
                            . ",'" . $datetime . "'"
                            . "," . "@currenttripid";

                    $QUERY = $this->PrepareSP(constants::SP_INSERT_Start_Trip, $sp_params);
                    $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
                    $outputVars = $this->db->query('SELECT @currenttripid', __FILE__, __LINE__);
                    $result = mysql_fetch_assoc($outputVars);
                    $currenttripid = $result["@currenttripid"];

                    if ($queryResult > 0) {
                        $updatesql = sprintf("UPDATE driver SET istripstarted=1 WHERE driverid=%d AND vehicleid=%d AND isdeleted=0 ", $start_arr['driverid'], $start_arr['vehicleid']);
                        $updaterecord = $this->db->query($updatesql, __FILE__, __LINE__);
                        $data_status['status'] = "sucess";
                    } else {
                        $data_status['status'] = "try again";
                    }
                }
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($start_arr['customerno'], $ex, $start_arr['driverid'], constants::Driver, __FUNCTION__);
                $data_status['status'] = "Exception occured.";
            }
            echo json_encode($data_status);
            return $data_status;
        }
    }

    function end_trip($userkey, $endduration) {
        $end_arr = array();
        $data_arr = array();
        $data_status = array();
        $data_trip = array();
        if (trim($userkey) == "" || trim($endduration) == "") {
            $data_status['status'] = "Failure: No userkey or duration";
            echo json_encode($data_status);
            return $data_status;
        } else {
            $end = date("Y-m-d H:i:s", strtotime($endduration));
            $queryResult = 0;
            $sql = sprintf("SELECT driver.userkey,driver.driverid,driver.vehicleid,driver.customerno,driver.trip_email,driver.trip_phone,vehicle.odometer,vehicle.vehicleno,devices.devicelat,devices.devicelong,devices.lastupdated,trip_driver.trip_driver_id,customer.smsleft,driver.drivername FROM driver 
                    INNER JOIN vehicle ON vehicle.vehicleid=driver.vehicleid
                    INNER JOIN unit ON unit.vehicleid=driver.vehicleid
                    INNER JOIN trip_driver ON trip_driver.driverid=driver.driverid
                    INNER JOIN devices ON unit.uid=devices.uid
                    INNER JOIN customer ON customer.customerno=driver.customerno
                    WHERE devices.lastupdated < '%s' AND driver.userkey=%d AND vehicle.isdeleted=0 AND driver.isdeleted=0 
                    ORDER BY devices.lastupdated DESC LIMIT 1", $end, $userkey);
            try {
                $record = $this->db->query($sql, __FILE__, __LINE__);
                $row = $this->db->fetch_array($record);

                if (!empty($row)) {
                    $end_arr['driverid'] = $row['driverid'];
                    $end_arr['drivername'] = $row['drivername'];
                    $end_arr['vehicleid'] = $row['vehicleid'];
                    $end_arr['vehicleno'] = $row['vehicleno'];
                    $end_arr['customerno'] = $row['customerno'];
                    $end_arr['odometer'] = $row['odometer'];
                    $end_arr['devicelat'] = $row['devicelat'];
                    $end_arr['devicelong'] = $row['devicelong'];
                    $end_arr['trip_email'] = $row['trip_email'];
                    $end_arr['trip_phone'] = $row['trip_phone'];
                    $end_arr['smsleft'] = $row['smsleft'];

                    // to get the latest tripid
                    $tripsql = sprintf("SELECT * FROM trip_driver 
                  INNER JOIN driver ON driver.driverid=trip_driver.driverid
                  WHERE trip_driver.driverid=%d AND driver.isdeleted=0 ORDER BY trip_driver_id DESC LIMIT 1 ", $end_arr['driverid']);
                    $triprecord = $this->db->query($tripsql, __FILE__, __LINE__);
                    $row2 = $this->db->fetch_array($triprecord);

                    $data_trip['tripid'] = $row2['trip_driver_id'];

                    $datetime = date("Y-m-d H:i:s");

                    //Prepare parameters
                    $sp_params = "" . $end_arr['driverid'] . ""
                            . "," . $data_trip['tripid'] . ""
                            . ",'" . $end . "'"
                            . "," . $end_arr['odometer'] . ""
                            . "," . $end_arr['devicelat'] . ""
                            . "," . $end_arr['devicelong'] . ""
                            . "," . $end_arr['driverid'] . ""
                            . ",'" . $datetime . "'";

                    $QUERY = $this->PrepareSP(constants::SP_UPDATE_End_Trip, $sp_params);
                    $queryResult = $this->db->query($QUERY, __FILE__, __LINE__);
                    //$noOfRowAffected = $this->db->get_affectedRows($QUERY);
                }
            } catch (Exception $ex) {
                $log = new Log();
                $log->createlog($end_arr['customerno'], $ex, $end_arr['driverid'], constants::Driver, __FUNCTION__);
            }

            if ($queryResult > 0) {
                $sql1 = sprintf("SELECT * FROM trip_driver WHERE trip_driver_id=%d", $data_trip['tripid']);
                $data = $this->db->query($sql1, __FILE__, __LINE__);
                $row1 = $this->db->fetch_array($data);
                $data_arr['trip_start'] = $row1['trip_start'];
                $data_arr['start_odometer'] = $row1['start_odometer'];
                $data_arr['start_lat'] = $row1['start_lat'];
                $data_arr['start_long'] = $row1['start_long'];
                $data_arr['trip_end'] = $row1['trip_end'];
                $data_arr['end_odometer'] = $row1['end_odometer'];
                $data_arr['end_lat'] = $row1['end_lat'];
                $data_arr['end_long'] = $row1['end_long'];
                // calculate distance
                if ($data_arr['start_odometer'] != 0 && $data_arr['end_odometer'] != 0) {
                    $lastodometer = $data_arr['end_odometer'];
                    $firstodometer = $data_arr['start_odometer'];
                    $distance = $lastodometer / 1000 - $firstodometer / 1000;
                    $distancekm = round($distance, 2);
                } else {
                    $distancekm = 0;
                }
                //find locaction
                $startlocation = $this->get_location_bylatlong($data_arr['start_lat'], $data_arr['start_long'], $end_arr['customerno']);
                $endlocation = $this->get_location_bylatlong($data_arr['end_lat'], $data_arr['end_long'], $end_arr['customerno']);

                //to unset the value of istripstarted in driver
                $updatesql = sprintf("UPDATE driver SET istripstarted=0 WHERE driverid=%d AND vehicleid=%d AND isdeleted=0 ", $end_arr['driverid'], $end_arr['vehicleid']);
                $updaterecord = $this->db->query($updatesql, __FILE__, __LINE__);

                // for Email
                $message = '';
                $message = "<html>";
                $message.="<head>
            <style type='text/css'>
            body{
                font-family:Arial;
                font-size: 11pt;
            }
            table{
                text-align: left;
                border-collapse:collapse;
                font-family:Arial;
                font-size: 10pt;
                width: 60%;
            }
            </style>
            </head>
            <body>";
                $message.="<br />";
                $message.="Greetings From Elixia Tech !!!";
                $message.="<br /><br />";
                $message.= "Please find the Trip Summary for Vehicle No." . $end_arr['vehicleno'];
                $message.="<br /><br />";
                $message.="<table>"
                        . "<tr>"
                        . "<td><strong>Trip Started On</strong></td>"
                        . "<td>" . date('d-m-Y H:i:s', strtotime($data_arr['trip_start'])) . "</td></tr>";
                $message.= "<tr>"
                        . "<td><strong>Trip Start Location</strong></td>"
                        . "<td>" . $startlocation . "</td></tr>";
                $message.= "<tr>"
                        . "<td><strong>Trip Ended On</strong></td>"
                        . "<td>" . date('d-m-Y H:i:s', strtotime($data_arr['trip_end'])) . "</td></tr>";
                $message.= "<tr>"
                        . "<td><strong>Trip End Location</strong></td>"
                        . "<td>" . $endlocation . "</td></tr>";
                $message.= "<tr>"
                        . "<td><strong>Total Distance Travelled</strong></td>"
                        . "<td>" . $distancekm . " Kms" . "</td></tr>";

                $message.="</table>";
                $message .= "<br />";
                $message .= "Warm Regards, ";
                $message .= "<br />";
                $message .= "Elixia Support Team";

                $message .= "</body></html>";
                //echo $message;
                /*
                  If message string is longer than 78 characters to the mail function
                  it would show unwanted exclamation marks.
                  To avoid this, we need to divide them into chunks
                  and add the content-transfer-encoding in mail header.
                 * 
                 */

                $message = chunk_split(base64_encode($message));
                $emails = Array();

                $emails[] = $end_arr['trip_email'];

                $subject = "Trip Summary for Driver:" . $end_arr['drivername'];

                foreach ($emails as $email) {
                    $this->sendMail($email, $subject, $message);
                }

                //for SMS
                if ($end_arr['smsleft'] > 0 && $end_arr['trip_phone'] != '') {
                    $smsmessage = "Veh No." . preg_replace("[\s]", "", $end_arr['vehicleno']) . "\n";
                    $smsmessage.="Started On-" . date('d-m-Y H:i:s', strtotime($data_arr['trip_start'])) . "\n";
                    $smsmessage.="Ended On - " . date('d-m-Y H:i:s', strtotime($data_arr['trip_end'])) . "\n";
                    $smsmessage.="Total- " . $distancekm . " Kms";
                    $response = '';

                    $checksms = $this->sendSMS($end_arr['trip_phone'], $smsmessage, $response);
                    if ($checksms === true) {
                        $smsconsumed = 0;
                        $remainsms = 0;
                        $smslength = strlen($smsmessage);
                        $divide = floor($smslength / constants::PER_SMS_CHARACTERS);
                        $mod = $smslength % constants::PER_SMS_CHARACTERS;
                        if ($mod > 0) {
                            $smsconsumed = $divide + 1;
                        } else if ($mod == 0) {
                            $smsconsumed = $divide;
                        }
                        $remainsms = $end_arr['smsleft'] - $smsconsumed;

                        $smssql = sprintf("UPDATE customer SET smsleft=%d WHERE customerno=%d", $remainsms, $end_arr['customerno']);
                        $smsrecord = $this->db->query($smssql, __FILE__, __LINE__);
                    }
                }
                $data_status['status'] = "Mail or SMS Sent Succesfully";
                echo json_encode($data_status);
                return $data_status;
            } else {
                $data_status['status'] = "Mail or SMS Not Sent";
                echo json_encode($data_status);
                return $data_status['status'];
            }
        }
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
                             AS distance FROM geotest WHERE `latfloor` = " . $latint . " AND `longfloor` = " . $longint . " AND customerno IN (0," . $customerno . ") HAVING distance <10 ORDER BY distance LIMIT 0,1 ";
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
                return "unable to pull location";
            }
            return null;
        }
    }

    function sendMail($to, $subject, $content) {
        $subject = $subject;
        // Create email headers
        $headers = "From: noreply@elixiatech.com\r\n";
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= "Content-Transfer-Encoding: base64\r\n\r\n";

        if (!@mail($to, $subject, $content, $headers)) {
            // message sending failed
            return false;
        }
        return true;
    }

    function sendSMS($phoneArray, $message, &$response) {

        $isSMSSent = false;
        $countryCode = "91";
        $arrPhone = array();
        if (is_array($phoneArray)) {
            foreach ($phoneArray as $phone) {
                $arrPhone[] = $countryCode . $phone;
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
            $isSMSSent = false;
        } else {
            $isSMSSent = true;
        }
        curl_close($ch);
        return $isSMSSent;
    }

}

?>