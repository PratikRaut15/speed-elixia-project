<?php
    $jdata = json_decode(file_get_contents('php://input'));
    $gps_date = date(DATE_TIME, $jdata->info->dt);
    $unit_no = $jdata->uid;
    $date_time = $gps_date;
    $reason = $jdata->info->txn;
    $msg_serial_no = $jdata->info->msgid;
    $msg_key = $jdata->info->msgkey;
    $command_key = 0;
    $command_key_value = $jdata->info->cmdval;
    $gps_fixed = $jdata->gps->fix;
    $latitude = $jdata->gps->loc[0];
    $longitude = $jdata->gps->loc[1];
    $speed = $jdata->gps->speed;
    $altitude = $jdata->gps->alt;
    $direction = $jdata->gps->dir;
    $odometer = $jdata->gps->odo;
    $sat_mode = $jdata->gps->sat;
    $box_open = $jdata->io->box;
    $ignition = $jdata->io->ign;
    $digital_io = $jdata->io->gpi;
    $analog_in_1 = $jdata->io->analog;
    $power_cut = $jdata->pwr->main;
    $ext_batt_volt = 0; /* Not used */
    $in_batt = $jdata->pwr->volt;
    $gsm_register = $jdata->dbg->status[0];
    $gprs_register = $jdata->dbg->status[1];
    $gsm_strength = $jdata->dbg->status[2];
    $server_avail = $jdata->dbg->status[3];
    $data_type = $jdata->dbg->status[5];
    $hw_version = $jdata->dbg->ver[1];
    $sw_version = $jdata->dbg->ver[0];
    $analog_in_2 = 0;
    $analog_in_3 = 0;
    $analog_in_4 = 0;
    
    $db_hostname = "localhost";
    $db_loginname = "UserSpeed";
    $db_loginpassword = "el!365x!@";
    //$db_loginname = "EPolice";
    //$db_loginpassword = "eptspolice";
    $db_databasename = "speed";

    $link = mysql_connect($db_hostname, $db_loginname, $db_loginpassword);
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }

    @mysql_select_db($db_databasename) or die( "Unable to select database");
    
    $sql = "INSERT INTO trackdata (unitid, msgid, txnreason, cmdkey, cmdkeyval, ign, power, cover, msgkey, odom, speed, gpsmode, gpsfix, lat, lng, alt, dir, dt, location, state, district, `signal`, GSMstatus, GPRSstatus, ServerStatus, Inbatt, Exbatt, Digi, analog1, analog2, analog3, analog4, hwv, swv, type, insertdt, current_landmark, current_landmark_id, fuel_data, last_snap) VALUES ('".addslashes($unit_no)."', '".addslashes($msg_serial_no)."', '".addslashes($reason)."', '".addslashes($command_key)."', '".addslashes($command_key_value)."', '".addslashes($ignition)."', '".addslashes($power_cut)."', '".addslashes($box_open)."', '".addslashes($msg_key)."', '".addslashes($odometer)."', '".addslashes($speed)."', '".addslashes($sat_mode)."', '".addslashes($gps_fixed)."', '".addslashes($latitude)."', '".addslashes($longitude)."', '".addslashes($altitude)."', '".addslashes($direction)."', '".addslashes($gps_date)."', '".addslashes($x_address)."', '".addslashes($state)."', '".addslashes($district)."', '".addslashes($gsm_strength)."', '".addslashes($gsm_register)."', '".addslashes($gprs_register)."', '".addslashes($server_avail)."', '".addslashes($in_batt)."', '".addslashes($ext_batt_volt)."', '".addslashes($digital_io)."', '".addslashes($analog_in_1)."', '".addslashes($analog_in_2)."', '".addslashes($analog_in_3)."', '".addslashes($analog_in_4)."', '".addslashes($hw_version)."', '".addslashes($sw_version)."', '$data_type', '".date(DATE_TIME)."', '".addslashes($current_landmark)."', '".addslashes($current_landmark_id)."', '".addslashes($fuel_data)."', '".addslashes($last_snap)."')";
    mysql_query($sql);
    
    mysql_close($link);
    
    header('Content-Type: application/json');

            if(count($jdata) > 0) {
                    echo "{\"result\":true,\"msg\":\"Data Received\"}";
            } else {
                    echo "{\"result\":false,\"msg\":\"Error Receiving data\"}";
            }
    
?>    