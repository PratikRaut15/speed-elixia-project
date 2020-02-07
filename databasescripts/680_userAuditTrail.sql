INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'680', '2018-03-08 15:30:00', 'Yash Kanakia','User Audit Trail', '0');

DROP TRIGGER IF EXISTS after_user_update ;
DROP TRIGGER IF EXISTS after_trip_exception_update ;
DROP TRIGGER IF EXISTS after_stoppageAlert_update ;
DROP TRIGGER IF EXISTS after_userReportmapping_update ;
DROP TRIGGER IF EXISTS after_tempReportmapping_update ;
DROP TRIGGER IF EXISTS after_vehReportmapping_update ;
DROP TRIGGER IF EXISTS after_chkptexusermapping_update ;
DROP TRIGGER IF EXISTS after_useralertmapping_update ;
DROP TRIGGER IF EXISTS after_vehwise_alert_update ;


  DELIMITER $$
  DROP TRIGGER IF EXISTS before_user_update $$
  CREATE TRIGGER `before_user_update` BEFORE UPDATE ON user FOR EACH ROW BEGIN

  		BEGIN

  			INSERT INTO user_audit_trail

  			SET userid = OLD.userid

  			, customerno = OLD.customerno

  			, stateid = OLD.stateid

  			, realname = OLD.realname

  			, username = OLD.username

  			, password = OLD.password

  			, roleid = OLD.roleid

  			, email = OLD.email

  			, phone = OLD.phone

  			, lastvisit = OLD.lastvisit

  			, visited = OLD.visited

  			, userkey = OLD.userkey

  			, mess_email = OLD.mess_email

  			, mess_sms = OLD.mess_sms

  			, speed_email = OLD.speed_email

  			, speed_sms = OLD.speed_sms

  			, power_email = OLD.power_email

  			, power_sms = OLD.power_sms

  			, tamper_email = OLD.tamper_email

  			, tamper_sms = OLD.tamper_sms

  			, chk_email = OLD.chk_email

  			, chk_sms = OLD.chk_sms

  			, ac_email = OLD.ac_email

  			, ac_sms = OLD.ac_sms

  			, ignition_email = OLD.ignition_email

  			, aci_email = OLD.aci_email

  			, aci_sms = OLD.aci_sms

  			, temp_email = OLD.temp_email

  			, temp_sms = OLD.temp_sms

  			, panic_email = OLD.panic_email

  			, panic_sms = OLD.panic_sms

  			, immob_email = OLD.immob_email

  			, immob_sms = OLD.immob_sms

  			, door_sms = OLD.door_sms

  			, door_email = OLD.door_email

  			, modifiedby = OLD.modifiedby

  			, isdeleted = OLD.isdeleted

  			, chgpwd = OLD.chgpwd

  			, chgalert = OLD.chgalert

  			, groupid = OLD.groupid

  			, start_alert = OLD.start_alert

  			, stop_alert = OLD.stop_alert

  			, fuel_alert_sms = OLD.fuel_alert_sms

  			, fuel_alert_email = OLD.fuel_alert_email

  			, fuel_alert_percentage = OLD.fuel_alert_percentage

  			, lastlogin_android = OLD.lastlogin_android

  			, heirarchy_id = OLD.heirarchy_id

  			, dailyemail = OLD.dailyemail

  			, dailyemail_csv = OLD.dailyemail_csv

  			, harsh_break_sms = OLD.harsh_break_sms

  			, harsh_break_mail = OLD.harsh_break_mail

  			, high_acce_sms = OLD.high_acce_sms

  			, high_acce_mail = OLD.high_acce_mail

  			, sharp_turn_sms = OLD.sharp_turn_sms

  			, sharp_turn_mail = OLD.sharp_turn_mail

  			, towing_sms = OLD.towing_sms

  			, towing_mail = OLD.towing_mail

  			, tempinterval = OLD.tempinterval

  			, igninterval = OLD.igninterval

  			, speedinterval = OLD.speedinterval

  			, acinterval = OLD.acinterval

  			, doorinterval = OLD.doorinterval

  			, chkpushandroid = OLD.chkpushandroid

  			, chkmanpushandroid = OLD.chkmanpushandroid

  			, delivery_vehicleid = OLD.delivery_vehicleid

  			,notification_status 	= OLD.notification_status

              ,hum_sms 				= OLD.hum_sms

              ,hum_email 				= OLD.hum_email

              ,hum_telephone 			= OLD.hum_telephone

              ,hum_mobilenotification = OLD.hum_mobilenotification

              ,huminterval 			= OLD.huminterval

              ,refreshtime 			= OLD.refreshtime

              ,sms_count 				= OLD.sms_count

              ,sms_lock 				= OLD.sms_lock

              ,smsalert_status 		= OLD.smsalert_status

              ,emailalert_status 		= OLD.emailalert_status

               ,isTempInrangeAlertRequired = OLD.isTempInrangeAlertRequired

              ,isAdvTempConfRange = OLD.isAdvTempConfRange

          		,insertedBy				= OLD.updatedBy

          		,insertedOn 			= OLD.updatedOn;
  END;
  END $$
  DELIMITER ;

  DELIMITER $$
  DROP TRIGGER IF EXISTS before_trip_exception_update $$
  CREATE TRIGGER `before_trip_exception_update` BEFORE UPDATE ON trip_exception_alerts FOR EACH ROW BEGIN

      BEGIN

        INSERT INTO trip_exception_alerts_trail

        SET`exception_id` = OLD.exception_id,
            `customerno` = OLD.customerno,
            `userid` = OLD.userid,
            `send_sms` = OLD.send_sms,
            `send_email` = OLD.send_email,
            `send_telephone` = OLD.send_telephone,
            `send_mobilenotification` = OLD.send_mobilenotification,
            `start_checkpoint_id` =OLD.start_checkpoint_id,
            `end_checkpoint_id` = OLD.end_checkpoint_id,
            `report_type` = OLD.report_type,
            `report_type_condition` = OLD.report_type_condition,
            `report_type_val` = OLD.report_type_val,
            `vehicleid` = OLD.vehicleid,
            `trip_endtime_flag` = OLD.trip_endtime_flag,
            `is_deleted` = OLD.is_deleted,
            `entry_time` = OLD.entry_time,
            `deleted_by` = OLD.deleted_by,
            `insertedBy` = OLD.updatedBy,
            `insertedOn` = OLD.updatedOn;
  END;
  END $$
  DELIMITER ;

  DELIMITER $$
  DROP TRIGGER IF EXISTS before_stoppageAlert_update $$
  CREATE TRIGGER before_stoppageAlert_update BEFORE UPDATE ON stoppage_alerts FOR EACH ROW BEGIN
  BEGIN

      INSERT INTO stoppage_alerts_audit_trail

      SET id    = OLD.id,
      customerno    = OLD.customerno,
      userid    = OLD.userid,
      is_chk_sms    = OLD.is_chk_sms,
      is_trans_sms    =OLD.is_trans_sms,
      is_chk_email    = OLD.is_chk_email,
      is_chk_telephone    = OLD.is_chk_telephone,
      is_chk_mobilenotification    = OLD.is_chk_mobilenotification,
      is_trans_email    = OLD.is_trans_email,
      is_trans_telephone    = OLD.is_trans_telephone,
      is_trans_mobilenotification    =OLD.is_trans_mobilenotification,
      chkmins    =OLD.chkmins,
      transmins    = OLD.transmins,
      isdeleted    = OLD.isdeleted,
      `timestamp`    = OLD.`timestamp`,
      alert_sent    = OLD.alert_sent,
      vehicleid    = OLD.vehicleid,
      insertedBy           = OLD.updatedBy,
      insertedOn         = OLD.updatedOn;
      END;
  END $$
  DELIMITER ;

  DELIMITER $$
  DROP TRIGGER IF EXISTS before_userReportmapping_update $$
  CREATE TRIGGER before_userReportmapping_update BEFORE UPDATE ON userReportMapping FOR EACH ROW BEGIN
  BEGIN

      INSERT INTO userreportmapping_audit_trail

      SET userReportId = OLD.userReportId,
      reportId = OLD.reportId,
      isActivated = OLD.isActivated,
      userid = OLD.userid,
      customerno  = OLD.customerno,
      inserted_by = OLD.updated_by,
      inserted_on = OLD.updated_on,
      isdeleted = OLD.isdeleted;
  END;
  END $$
  DELIMITER ;

  DELIMITER $$
  DROP TRIGGER IF EXISTS before_tempReportmapping_update $$
  CREATE TRIGGER before_tempReportmapping_update BEFORE UPDATE ON tempreportinterval FOR EACH ROW BEGIN
  BEGIN

      INSERT INTO tempreportinterval_audit_trail

      SET trid = OLD.trid,
      userid = OLD.userid,
      customerno = OLD.customerno,
      `interval` = OLD.`interval`,
      insertedby  = OLD.createdby,
      insertedon = OLD.createdon,
      isdeleted = OLD.isdeleted;
  END;
  END $$
  DELIMITER ;

  DELIMITER $$
  DROP TRIGGER IF EXISTS before_chkptexusermapping_update $$
  CREATE TRIGGER before_chkptexusermapping_update BEFORE UPDATE ON chkptexusermapping FOR EACH ROW BEGIN
  BEGIN

      INSERT INTO chkptexusermapping_audit_trail

      SET chkExUserMappingId = OLD.chkExUserMappingId,
      chkExId = OLD.chkExId,
      customerno = OLD.customerno,
      insertedby  = OLD.updated_by,
      insertedon = OLD.updated_on,
      isdeleted = OLD.isdeleted;
  END;
  END $$
  DELIMITER ;


  DELIMITER $$
  DROP TRIGGER IF EXISTS after_vehwise_alert_insert $$
  CREATE TRIGGER after_vehwise_alert_insert AFTER INSERT ON vehiclewise_alert FOR EACH ROW BEGIN

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

   DELIMITER $$
  DROP TRIGGER IF EXISTS before_vehwise_alert_update $$
  CREATE TRIGGER before_vehwise_alert_update BEFORE UPDATE ON vehiclewise_alert FOR EACH ROW BEGIN

      BEGIN

        INSERT INTO vehiclewise_alert_audit_trail
      SET
      veh_alert_id  =  OLD.veh_alert_id ,
      customerno =  OLD.customerno ,
      userid =  OLD.userid ,
      vehicleid =  OLD.vehicleid ,
      temp_active =  OLD.temp_active ,
      temp_starttime  =  OLD.temp_starttime ,
      temp_endtime  =  OLD.temp_endtime ,
      ignition_active =  OLD.ignition_active ,
      ignition_starttime  =  OLD.ignition_starttime ,
      ignition_endtime  =  OLD.ignition_endtime ,
      speed_active =  OLD.speed_active ,
      speed_starttime =  OLD.speed_starttime ,
      speed_endtime  =  OLD.speed_endtime ,
      ac_active =  OLD.ac_active ,
      ac_starttime  =  OLD.ac_starttime ,
      ac_endtime  =  OLD.ac_endtime ,
      powerc_active =  OLD.powerc_active ,
      powerc_starttime  =  OLD.powerc_starttime ,
      powerc_endtime  =  OLD.powerc_endtime ,
      tamper_active =  OLD.tamper_active ,
      tamper_starttime  =  OLD.tamper_starttime ,
      tamper_endtime  =  OLD.tamper_endtime ,
      harsh_break_active =  OLD.harsh_break_active ,
      harsh_break_starttime  =  OLD.harsh_break_starttime ,
      harsh_break_endtime  =  OLD.harsh_break_endtime ,
      high_acce_active =  OLD.high_acce_active ,
      high_acce_starttime  =  OLD.high_acce_starttime ,
      high_acce_endtime  =  OLD.high_acce_endtime ,
      towing_active =  OLD.towing_active ,
      towing_starttime  =  OLD.towing_starttime ,
      towing_endtime  =  OLD.towing_endtime ,
      panic_active =  OLD.panic_active ,
      panic_starttime  =  OLD.panic_starttime ,
      panic_endtime  =  OLD.panic_endtime ,
      immob_active =  OLD.immob_active ,
      immob_starttime  =  OLD.immob_starttime ,
      immob_endtime  =  OLD.immob_endtime ,
      door_active =  OLD.door_active ,
      door_starttime  =  OLD.door_starttime ,
      door_endtime  =  OLD.door_endtime ,
      hum_active =  OLD.hum_active ,
      hum_starttime  =  OLD.hum_starttime ,
      hum_endtime =  OLD.hum_endtime,
      insertedOn =  OLD.updated_date ,
      insertedBy =  OLD.updated_by ,
      isdeleted = OLD.isdeleted;
      END;
  END $$
  DELIMITER ;

DROP TABLE IF EXISTS `vehicleusermapping_audit_trail`;
CREATE TABLE `vehicleusermapping_audit_trail` (
  `vehmapid_audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `vehmapid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `isdeleted` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`vehmapid_audit_id`)
);



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



      SET @STMT = CONCAT("SELECT vuat.*,v.vehicleno FROM vehicleusermapping_audit_trail vuat INNER JOIN `vehicle` v on v.vehicleid = vuat.vehicleid  WHERE vuat.customerno =", customernoParam, " AND vuat.userid =", userIdParam," AND date(vuat.created_on) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY created_on desc ",limitCondition);
	  PREPARE S FROM @STMT;
      EXECUTE S;
      DEALLOCATE PREPARE S;

      END$$

DELIMITER ;


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



      SET @STMT = CONCAT("SELECT uat.*,r.role,g.groupname FROM user_audit_trail uat LEFT JOIN `group` g on g.groupid = uat.groupid INNER JOIN role r on r.id = uat.roleid WHERE uat.customerno =", customernoParam, " AND uat.userid =", userIdParam," AND date(uat.insertedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY insertedOn desc ",limitCondition);
    PREPARE S FROM @STMT;
      EXECUTE S;
      DEALLOCATE PREPARE S;

      END$$

DELIMITER ;





UPDATE user
SET
createdBy =298,
createdOn = NOW(),
updatedBy =298,
updatedOn =NOW()
WHERE customerno = 64;

UPDATE trip_exception_alerts
SET
createdBy =298,
createdOn = NOW(),
updatedBy =298,
updatedOn =NOW()
WHERE customerno = 64;

UPDATE stoppage_alerts
SET
createdBy =298,
createdOn = NOW(),
updatedBy =298,
updatedOn =NOW()
WHERE customerno = 64;


UPDATE vehiclewise_alert
SET
updated_by = 298
WHERE customerno = 64;


UPDATE  dbpatches
SET     patchdate = '2018-03-08 15:30:00'
        ,isapplied =1
WHERE   patchid = 680;
