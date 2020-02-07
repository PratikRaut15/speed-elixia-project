INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'687', '2018-03-14 11:30:00', 'Yash Kanakia','USER Audit Trail', '0');

DROP TRIGGER IF EXISTS before_user_update ;
DROP TRIGGER IF EXISTS before_trip_exception_update ;
DROP TRIGGER IF EXISTS before_stoppageAlert_update ;
DROP TRIGGER IF EXISTS before_userReportmapping_update ;
DROP TRIGGER IF EXISTS before_tempReportmapping_update ;
DROP TRIGGER IF EXISTS before_vehReportmapping_update ;
DROP TRIGGER IF EXISTS before_chkptexusermapping_update ;
DROP TRIGGER IF EXISTS before_useralertmapping_update ;
DROP TRIGGER IF EXISTS before_vehwise_alert_update ;

 DELIMITER $$
  DROP TRIGGER IF EXISTS after_user_update $$
  CREATE TRIGGER `after_user_update` after UPDATE ON user FOR EACH ROW BEGIN

      BEGIN

        INSERT INTO user_audit_trail

        SET userid = NEW.userid

        , customerno = NEW.customerno

        , stateid = NEW.stateid

        , realname = NEW.realname

        , username = NEW.username

        , password = NEW.password

        , roleid = NEW.roleid

        , email = NEW.email

        , phone = NEW.phone

        , lastvisit = NEW.lastvisit

        , visited = NEW.visited

        , userkey = NEW.userkey

        , mess_email = NEW.mess_email

        , mess_sms = NEW.mess_sms

        , speed_email = NEW.speed_email

        , speed_sms = NEW.speed_sms

        , power_email = NEW.power_email

        , power_sms = NEW.power_sms

        , tamper_email = NEW.tamper_email

        , tamper_sms = NEW.tamper_sms

        , chk_email = NEW.chk_email

        , chk_sms = NEW.chk_sms

        , ac_email = NEW.ac_email

        , ac_sms = NEW.ac_sms

        , ignition_email = NEW.ignition_email

        , aci_email = NEW.aci_email

        , aci_sms = NEW.aci_sms

        , temp_email = NEW.temp_email

        , temp_sms = NEW.temp_sms

        , panic_email = NEW.panic_email

        , panic_sms = NEW.panic_sms

        , immob_email = NEW.immob_email

        , immob_sms = NEW.immob_sms

        , door_sms = NEW.door_sms

        , door_email = NEW.door_email

        , modifiedby = NEW.modifiedby

        , isdeleted = NEW.isdeleted

        , chgpwd = NEW.chgpwd

        , chgalert = NEW.chgalert

        , groupid = NEW.groupid

        , start_alert = NEW.start_alert

        , stop_alert = NEW.stop_alert

        , fuel_alert_sms = NEW.fuel_alert_sms

        , fuel_alert_email = NEW.fuel_alert_email

        , fuel_alert_percentage = NEW.fuel_alert_percentage

        , lastlogin_android = NEW.lastlogin_android

        , heirarchy_id = NEW.heirarchy_id

        , dailyemail = NEW.dailyemail

        , dailyemail_csv = NEW.dailyemail_csv

        , harsh_break_sms = NEW.harsh_break_sms

        , harsh_break_mail = NEW.harsh_break_mail

        , high_acce_sms = NEW.high_acce_sms

        , high_acce_mail = NEW.high_acce_mail

        , sharp_turn_sms = NEW.sharp_turn_sms

        , sharp_turn_mail = NEW.sharp_turn_mail

        , towing_sms = NEW.towing_sms

        , towing_mail = NEW.towing_mail

        , tempinterval = NEW.tempinterval

        , igninterval = NEW.igninterval

        , speedinterval = NEW.speedinterval

        , acinterval = NEW.acinterval

        , doorinterval = NEW.doorinterval

        , chkpushandroid = NEW.chkpushandroid

        , chkmanpushandroid = NEW.chkmanpushandroid

        , delivery_vehicleid = NEW.delivery_vehicleid
              
        ,notification_status  = NEW.notification_status
              
              ,hum_sms        = NEW.hum_sms
              
              ,hum_email        = NEW.hum_email
              
              ,hum_telephone      = NEW.hum_telephone
              
              ,hum_mobilenotification = NEW.hum_mobilenotification
              
              ,huminterval      = NEW.huminterval
              
              ,refreshtime      = NEW.refreshtime
              
              ,sms_count        = NEW.sms_count
              
              ,sms_lock         = NEW.sms_lock
              
              ,smsalert_status    = NEW.smsalert_status
              
              ,emailalert_status    = NEW.emailalert_status

               ,isTempInrangeAlertRequired = NEW.isTempInrangeAlertRequired

              ,isAdvTempConfRange = NEW.isAdvTempConfRange
              
              ,insertedBy       = NEW.updatedBy

              ,insertedOn       = NEW.updatedOn;
  END;
  END $$
  DELIMITER ;

  DELIMITER $$
  DROP TRIGGER IF EXISTS after_trip_exception_update $$
  CREATE TRIGGER `after_trip_exception_update` after UPDATE ON trip_exception_alerts FOR EACH ROW BEGIN

      BEGIN

        INSERT INTO trip_exception_alerts_trail

        SET`exception_id` = NEW.exception_id,
            `customerno` = NEW.customerno,
            `userid` = NEW.userid,
            `send_sms` = NEW.send_sms,
            `send_email` = NEW.send_email,
            `send_telephone` = NEW.send_telephone,
            `send_mobilenotification` = NEW.send_mobilenotification,
            `start_checkpoint_id` =NEW.start_checkpoint_id,
            `end_checkpoint_id` = NEW.end_checkpoint_id,
            `report_type` = NEW.report_type,
            `report_type_condition` = NEW.report_type_condition,
            `report_type_val` = NEW.report_type_val,
            `vehicleid` = NEW.vehicleid,
            `trip_endtime_flag` = NEW.trip_endtime_flag,
            `is_deleted` = NEW.is_deleted,
            `entry_time` = NEW.entry_time,
            `deleted_by` = NEW.deleted_by,
            `insertedBy` = NEW.updatedBy, 
            `insertedOn` = NEW.updatedOn;
  END;
  END $$
  DELIMITER ;

  DELIMITER $$
  DROP TRIGGER IF EXISTS after_stoppageAlert_update $$
  CREATE TRIGGER after_stoppageAlert_update after UPDATE ON stoppage_alerts FOR EACH ROW BEGIN
  BEGIN

      INSERT INTO stoppage_alerts_audit_trail

      SET id    = NEW.id,
      customerno    = NEW.customerno,
      userid    = NEW.userid,
      is_chk_sms    = NEW.is_chk_sms,
      is_trans_sms    =NEW.is_trans_sms,
      is_chk_email    = NEW.is_chk_email,
      is_chk_telephone    = NEW.is_chk_telephone,
      is_chk_mobilenotification    = NEW.is_chk_mobilenotification,
      is_trans_email    = NEW.is_trans_email,
      is_trans_telephone    = NEW.is_trans_telephone,
      is_trans_mobilenotification    =NEW.is_trans_mobilenotification,
      chkmins    =NEW.chkmins,
      transmins    = NEW.transmins,
      isdeleted    = NEW.isdeleted,
      `timestamp`    = NEW.`timestamp`,
      alert_sent    = NEW.alert_sent,
      vehicleid    = NEW.vehicleid,
      insertedBy           = NEW.updatedBy,
      insertedOn         = NEW.updatedOn;
      END;
  END $$
  DELIMITER ;

  DELIMITER $$
  DROP TRIGGER IF EXISTS after_userReportmapping_update $$
  CREATE TRIGGER after_userReportmapping_update after UPDATE ON userReportMapping FOR EACH ROW BEGIN
  BEGIN

      INSERT INTO userreportmapping_audit_trail

      SET userReportId = NEW.userReportId,
      reportId = NEW.reportId, 
      isActivated = NEW.isActivated,
      userid = NEW.userid, 
      customerno  = NEW.customerno,
      inserted_by = NEW.updated_by,
      inserted_on = NEW.updated_on,
      isdeleted = NEW.isdeleted;
  END;
  END $$
  DELIMITER ;

  DELIMITER $$
  DROP TRIGGER IF EXISTS after_tempReportmapping_update $$
  CREATE TRIGGER after_tempReportmapping_update after UPDATE ON tempreportinterval FOR EACH ROW BEGIN
  BEGIN

      INSERT INTO tempreportinterval_audit_trail

      SET trid = NEW.trid,
      userid = NEW.userid, 
      customerno = NEW.customerno,
      `interval` = NEW.`interval`, 
      insertedby  = NEW.createdby,
      insertedon = NEW.createdon,
      isdeleted = NEW.isdeleted;
  END;
  END $$
  DELIMITER ; 

  DELIMITER $$
  DROP TRIGGER IF EXISTS after_chkptexusermapping_update $$
  CREATE TRIGGER after_chkptexusermapping_update after UPDATE ON chkptexusermapping FOR EACH ROW BEGIN
  BEGIN

      INSERT INTO chkptexusermapping_audit_trail

      SET chkExUserMappingId = NEW.chkExUserMappingId,
      chkExId = NEW.chkExId,
      customerno = NEW.customerno,
      insertedby  = NEW.updated_by,
      insertedon = NEW.updated_on,
      isdeleted = NEW.isdeleted;
  END;
  END $$
  DELIMITER ; 
  

  DELIMITER $$
  DROP TRIGGER IF EXISTS after_vehwise_alert_update $$
  CREATE TRIGGER after_vehwise_alert_update after UPDATE ON vehiclewise_alert FOR EACH ROW BEGIN

      BEGIN

        INSERT INTO vehiclewise_alert_audit_trail
      SET 
      veh_alert_id  =  NEW.veh_alert_id ,
      customerno =  NEW.customerno ,
      userid =  NEW.userid ,
      vehicleid =  NEW.vehicleid , 
      temp_active =  NEW.temp_active ,
      temp_starttime  =  NEW.temp_starttime ,
      temp_endtime  =  NEW.temp_endtime ,
      ignition_active =  NEW.ignition_active , 
      ignition_starttime  =  NEW.ignition_starttime ,
      ignition_endtime  =  NEW.ignition_endtime ,
      speed_active =  NEW.speed_active ,
      speed_starttime =  NEW.speed_starttime ,
      speed_endtime  =  NEW.speed_endtime ,
      ac_active =  NEW.ac_active ,
      ac_starttime  =  NEW.ac_starttime ,
      ac_endtime  =  NEW.ac_endtime ,
      powerc_active =  NEW.powerc_active , 
      powerc_starttime  =  NEW.powerc_starttime ,
      powerc_endtime  =  NEW.powerc_endtime ,
      tamper_active =  NEW.tamper_active , 
      tamper_starttime  =  NEW.tamper_starttime ,
      tamper_endtime  =  NEW.tamper_endtime , 
      harsh_break_active =  NEW.harsh_break_active , 
      harsh_break_starttime  =  NEW.harsh_break_starttime ,
      harsh_break_endtime  =  NEW.harsh_break_endtime ,
      high_acce_active =  NEW.high_acce_active ,
      high_acce_starttime  =  NEW.high_acce_starttime ,
      high_acce_endtime  =  NEW.high_acce_endtime ,
      towing_active =  NEW.towing_active ,
      towing_starttime  =  NEW.towing_starttime ,
      towing_endtime  =  NEW.towing_endtime ,
      panic_active =  NEW.panic_active ,
      panic_starttime  =  NEW.panic_starttime ,
      panic_endtime  =  NEW.panic_endtime ,
      immob_active =  NEW.immob_active , 
      immob_starttime  =  NEW.immob_starttime ,
      immob_endtime  =  NEW.immob_endtime ,
      door_active =  NEW.door_active ,
      door_starttime  =  NEW.door_starttime ,
      door_endtime  =  NEW.door_endtime ,
      hum_active =  NEW.hum_active ,
      hum_starttime  =  NEW.hum_starttime ,
      hum_endtime =  NEW.hum_endtime,
      insertedOn =  NEW.updated_date ,  
      insertedBy =  NEW.updated_by , 
      isdeleted = NEW.isdeleted;
      END;
  END $$
  DELIMITER ;
  
  ALTER TABLE groupman
  ADD COLUMN updatedBy INT,
  ADD COLUMN updatedOn datetime;

  DELIMITER $$
  DROP TRIGGER IF EXISTS after_groupman_insert $$
  CREATE TRIGGER after_groupman_insert after INSERT ON groupman FOR EACH ROW BEGIN
  BEGIN


    INSERT INTO groupman_audit_trail

   SET 
   `gmid` =NEW.gmid,
  `groupid` = NEW.groupid,
  `vehicleid` = NEW.vehicleid,
  `customerno` = NEW.customerno,
  `userid` =  NEW.userid,
  `isdeleted` = NEW.isdeleted,
  `timestamp` = NEW.`timestamp`,
  `createdBy` = NEW.createdBy,
  `createdOn` = NEW.createdOn;
    END;
  END $$
  DELIMITER ;
  
  DELIMITER $$
  DROP TRIGGER IF EXISTS after_groupman_update $$
  CREATE TRIGGER after_groupman_update after UPDATE ON groupman FOR EACH ROW BEGIN
  BEGIN


    INSERT INTO groupman_audit_trail

   SET 
   `gmid` =NEW.gmid,
  `groupid` = NEW.groupid,
  `vehicleid` = NEW.vehicleid,
  `customerno` = NEW.customerno,
  `userid` =  NEW.userid,
  `isdeleted` = NEW.isdeleted,
  `timestamp` = NEW.`timestamp`,
  `createdBy` = NEW.updatedBy,
  `createdOn` = NEW.updatedOn;
    END;
  END $$
  DELIMITER ;
  
  DROP TABLE IF EXISTS `groupman_audit_trail`;
  CREATE TABLE `groupman_audit_trail` (
  `gm_audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `gmid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `isdeleted` int(1) NOT NULL,
  `timestamp` datetime NOT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `createdOn` datetime DEFAULT NULL,
  PRIMARY KEY (`gm_audit_id`),
  KEY `index_userid` (`userid`),
  KEY `index_customerno` (`customerno`)
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=latin1;

	DELIMITER $$
DROP procedure IF EXISTS `fetch_user_logs`$$
CREATE PROCEDURE `fetch_user_logs`(
  IN customernoParam INT,
  IN userIdParam INT,
  IN startdateParam date,
  IN enddateParam date,
  IN limitParam INT
  )
BEGIN

   DECLARE limitCondition VARCHAR(10);
    
      SET limitCondition = CONCAT(' LIMIT ', limitParam);
      


      SET @STMT = CONCAT("SELECT DISTINCT uat.*,r.role,u.realname as inserted_by FROM user_audit_trail uat  INNER JOIN role r on r.id = uat.roleid INNER JOIN user u on u.userid = uat.insertedBy WHERE uat.customerno =", customernoParam, " AND uat.userid =", userIdParam," AND date(uat.insertedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY uat.insertedOn desc ",limitCondition);
      PREPARE S FROM @STMT;
      EXECUTE S;
      DEALLOCATE PREPARE S; 
        
      END$$

DELIMITER ;


	
DELIMITER $$
DROP procedure IF EXISTS `fetch_vehicle_usermapping_logs`$$
CREATE PROCEDURE `fetch_vehicle_usermapping_logs`(
  IN customernoParam INT,
  IN userIdParam INT,
  IN startdateParam date,
  IN enddateParam date,
  IN limitParam INT
  )
BEGIN

   DECLARE limitCondition VARCHAR(10);
    
      SET limitCondition = CONCAT(' LIMIT ', limitParam);
      


      SET @STMT = CONCAT("SELECT vuat.*,v.vehicleno,u.realname FROM vehicleusermapping_audit_trail vuat INNER JOIN `vehicle` v on v.vehicleid = vuat.vehicleid INNER JOIN user u on u.userid = vuat.created_by WHERE vuat.customerno =", customernoParam, " AND vuat.userid =", userIdParam," AND date(vuat.created_on) BETWEEN '",startdateParam,"' AND '",enddateParam,"' GROUP BY vuat.isdeleted,vuat.created_on ORDER BY vuat.created_on desc ",limitCondition);
      PREPARE S FROM @STMT;
      EXECUTE S;
      DEALLOCATE PREPARE S; 
        
      END$$

DELIMITER ;





DELIMITER $$
DROP procedure IF EXISTS `fetch_fence_logs`$$
CREATE PROCEDURE `fetch_fence_logs`(
  IN customernoParam INT,
  IN vehicleIdParam INT,
  IN startdateParam date,
  IN enddateParam date,
  IN limitParam INT
)
BEGIN

 DECLARE limitCondition VARCHAR(10);
  
    SET limitCondition = CONCAT(' LIMIT ', limitParam);
    


    SET @STMT = CONCAT("SELECT fat.*,f.fencename,u.realname,v.vehicleno FROM fenceman_audit_trail fat LEFT JOIN `fence` f on f.fenceid = fat.fenceid INNER JOIN vehicle v on v.vehicleid = fat.vehicleid INNER JOIN user u on u.userid = fat.insertedBy WHERE fat.customerno =", customernoParam, " AND fat.vehicleid =", vehicleIdParam," AND date(fat.insertedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' AND f.fencename IS NOT NULL GROUP BY fat.fmid,fat.insertedOn,fat.isdeleted ORDER BY fat.insertedOn desc ",limitCondition);
        PREPARE S FROM @STMT;
    EXECUTE S;
    DEALLOCATE PREPARE S; 
      
    END$$

DELIMITER ;


DELIMITER $$
DROP TRIGGER IF EXISTS after_vehicleusermapping_update $$
CREATE TRIGGER `after_vehicleusermapping_update` AFTER UPDATE ON  vehicleusermapping FOR EACH ROW 
BEGIN

  BEGIN

    INSERT INTO vehicleusermapping_audit_trail

     SET vehicleid = NEW.vehicleid,
  groupid = NEW.groupid, 
  userid = NEW.userid,
  customerno = NEW.customerno, 
  created_on  = NEW.updated_on,
  created_by = NEW.updated_by,
  isdeleted = NEW.isdeleted;
END;
END $$
DELIMITER ;


UPDATE  dbpatches
SET     patchdate = '2018-03-14 11:30:00'
        ,isapplied =1
WHERE   patchid = 687;




