INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'675', '2018-03-02 17:30:00', 'Yash Kanakia','User Audit Trail', '0');


-- ******* USER ALERT *******  
  ALTER TABLE `user`
    ADD COLUMN createdBy INT,
    ADD COLUMN createdOn datetime,
    ADD COLUMN updatedBy INT,
    ADD COLUMN updatedOn datetime;

  DROP TABLE IF EXISTS `user_audit_trail`;
  CREATE TABLE `user_audit_trail` (
    `user_audit_id` INT PRIMARY KEY AUTO_INCREMENT,	
    `userid` int(11) NOT NULL,
    `customerno` int(11) NOT NULL,
    `stateid` int(11) NOT NULL,
    `realname` varchar(50) NOT NULL,
    `username` varchar(50) NOT NULL,
    `password` varchar(150) NOT NULL,
    `role` varchar(50) NOT NULL,
    `roleid` int(11) NOT NULL,
    `email` varchar(50) NOT NULL,
    `phone` varchar(15) NOT NULL,
    `mobile1` varchar(15) NOT NULL DEFAULT '',
    `mobile2` varchar(15) NOT NULL DEFAULT '',
    `lastvisit` datetime NOT NULL,
    `visited` int(11) NOT NULL,
    `userkey` varchar(150) NOT NULL,
    `erpUserToken` varchar(150) NOT NULL,
    `fleetUserToken` varchar(150) NOT NULL,
    `mess_email` tinyint(1) NOT NULL,
    `mess_sms` tinyint(1) NOT NULL,
    `mess_telephone` tinyint(4) NOT NULL,
    `mess_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
    `speed_email` tinyint(1) NOT NULL,
    `speed_sms` tinyint(1) NOT NULL,
    `speed_telephone` tinyint(4) NOT NULL,
    `speed_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
    `power_email` tinyint(1) NOT NULL,
    `power_sms` tinyint(1) NOT NULL,
    `power_telephone` tinyint(4) NOT NULL,
    `power_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
    `tamper_email` tinyint(1) NOT NULL,
    `tamper_sms` tinyint(1) NOT NULL,
    `tamper_telephone` tinyint(4) NOT NULL,
    `tamper_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
    `chk_email` tinyint(1) NOT NULL,
    `chk_sms` tinyint(1) NOT NULL,
    `chk_telephone` tinyint(4) NOT NULL,
    `chk_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
    `ac_email` tinyint(1) NOT NULL,
    `ac_sms` tinyint(1) NOT NULL,
    `ac_telephone` tinyint(4) NOT NULL,
    `ac_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
    `ignition_email` tinyint(4) NOT NULL,
    `ignition_sms` tinyint(4) NOT NULL,
    `ignition_telephone` tinyint(4) NOT NULL,
    `ignition_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
    `aci_email` tinyint(1) NOT NULL,
    `aci_sms` tinyint(1) NOT NULL,
    `aci_telephone` tinyint(4) NOT NULL,
    `aci_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
    `temp_email` tinyint(1) NOT NULL,
    `temp_sms` tinyint(1) NOT NULL,
    `temp_telephone` tinyint(4) NOT NULL,
    `temp_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
    `panic_email` tinyint(1) DEFAULT '0',
    `panic_sms` tinyint(1) DEFAULT '0',
    `panic_telephone` tinyint(4) NOT NULL,
    `panic_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
    `immob_email` tinyint(1) DEFAULT '0',
    `immob_sms` tinyint(1) DEFAULT '0',
    `immob_telephone` tinyint(4) NOT NULL,
    `immob_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
    `door_sms` tinyint(1) DEFAULT '0',
    `door_email` tinyint(1) DEFAULT '0',
    `door_telephone` tinyint(4) NOT NULL,
    `door_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
    `modifiedby` int(11) NOT NULL,
    `isdeleted` tinyint(1) NOT NULL,
    `chgpwd` tinyint(4) NOT NULL,
    `chgalert` tinyint(4) NOT NULL,
    `groupid` int(11) NOT NULL,
    `start_alert` time NOT NULL,
    `stop_alert` time NOT NULL DEFAULT '23:59:59',
    `fuel_alert_sms` tinyint(1) NOT NULL,
    `fuel_alert_email` tinyint(1) NOT NULL,
    `fuel_alert_percentage` int(3) NOT NULL DEFAULT '20',
    `lastlogin_android` datetime NOT NULL,
    `heirarchy_id` int(11) NOT NULL,
    `dailyemail` tinyint(1) DEFAULT '0',
    `vehicle_movement_alert` tinyint(1) NOT NULL DEFAULT '0',
    `dailyemail_csv` tinyint(1) DEFAULT '0',
    `harsh_break_sms` tinyint(1) DEFAULT '0',
    `harsh_break_mail` tinyint(1) DEFAULT '0',
    `harsh_break_telephone` tinyint(4) NOT NULL,
    `harsh_break_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
    `high_acce_sms` tinyint(1) DEFAULT '0',
    `high_acce_mail` tinyint(1) DEFAULT '0',
    `high_acce_telephone` tinyint(4) NOT NULL,
    `high_acce_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
    `sharp_turn_sms` tinyint(1) DEFAULT '0',
    `sharp_turn_mail` tinyint(1) DEFAULT '0',
    `sharp_turn_telephone` tinyint(4) NOT NULL,
    `sharp_turn_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
    `towing_sms` tinyint(1) DEFAULT '0',
    `towing_mail` tinyint(1) DEFAULT '0',
    `towing_telephone` tinyint(4) NOT NULL,
    `towing_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
    `fuel_alert_telephone` tinyint(4) NOT NULL,
    `fuel_alert_mobilenotification` tinyint(4) NOT NULL DEFAULT '1',
    `tempinterval` varchar(10) NOT NULL,
    `igninterval` varchar(10) NOT NULL,
    `speedinterval` varchar(10) NOT NULL,
    `acinterval` varchar(10) NOT NULL,
    `doorinterval` varchar(10) NOT NULL,
    `chkpushandroid` tinyint(1) NOT NULL,
    `chkmanpushandroid` tinyint(1) NOT NULL,
    `gcmid` text NOT NULL,
    `delivery_vehicleid` int(11) NOT NULL DEFAULT '0',
    `notification_status` tinyint(1) NOT NULL DEFAULT '1',
    `hum_sms` tinyint(1) DEFAULT '0',
    `hum_email` tinyint(1) DEFAULT '0',
    `hum_telephone` tinyint(4) DEFAULT '0',
    `hum_mobilenotification` tinyint(4) DEFAULT '1',
    `huminterval` varchar(10) DEFAULT NULL,
    `refreshtime` tinyint(1) DEFAULT '1',
    `sms_count` int(11) NOT NULL DEFAULT '0',
    `sms_lock` tinyint(1) NOT NULL DEFAULT '0',
    `smsalert_status` tinyint(2) NOT NULL DEFAULT '0',
    `emailalert_status` tinyint(2) NOT NULL DEFAULT '0',
    `isTempInrangeAlertRequired` tinyint(1) DEFAULT '1',
    `isAdvTempConfRange` tinyint(1) DEFAULT '0',
    `insertedBy` INT,
    `insertedOn` datetime,
    KEY `index_customerno` (`customerno`)
  );


  DELIMITER $$
  DROP TRIGGER IF EXISTS after_user_insert $$
  CREATE TRIGGER `after_user_insert` AFTER INSERT ON user FOR EACH ROW BEGIN

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
              
  			,notification_status 	= NEW.notification_status
              
              ,hum_sms 				= NEW.hum_sms
              
              ,hum_email 				= NEW.hum_email
              
              ,hum_telephone 			= NEW.hum_telephone
              
              ,hum_mobilenotification = NEW.hum_mobilenotification
              
              ,huminterval 			= NEW.huminterval
              
              ,refreshtime 			= NEW.refreshtime
              
              ,sms_count 				= NEW.sms_count
              
              ,sms_lock 				= NEW.sms_lock
              
              ,smsalert_status 		= NEW.smsalert_status
              
              ,emailalert_status 		= NEW.emailalert_status

              ,isTempInrangeAlertRequired = NEW.isTempInrangeAlertRequired

              ,isAdvTempConfRange = NEW.isAdvTempConfRange
              	
          		,insertedBy				= NEW.createdBy 
          		
          		,insertedOn 			= NEW.createdOn;
  END;
  END $$
  DELIMITER ;


  DELIMITER $$
  DROP TRIGGER IF EXISTS after_user_update $$
  CREATE TRIGGER `after_user_update` AFTER UPDATE ON user FOR EACH ROW BEGIN

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

-- ******* USER ALERT ******* 



-- ******* TRIP EXCEPTION ALERT *******

  ALTER TABLE trip_exception_alerts
    ADD COLUMN createdBy INT,
    ADD COLUMN createdOn datetime,
    ADD COLUMN updatedBy INT,
    ADD COLUMN updatedOn datetime;

  DROP TABLE IF EXISTS `trip_exception_alerts_trail`;
  CREATE TABLE trip_exception_alerts_trail( 
    `exc_alert_audit_id` INT PRIMARY KEY AUTO_INCREMENT,
    `exception_id` int(11),
    `customerno` int(11) DEFAULT NULL,
    `userid` int(11) DEFAULT NULL,
    `send_sms` tinyint(1) DEFAULT '0',
    `send_email` tinyint(1) DEFAULT '0',
    `send_telephone` tinyint(4) NOT NULL,
    `send_mobilenotification` tinyint(4) NOT NULL,
    `start_checkpoint_id` int(11) DEFAULT NULL,
    `end_checkpoint_id` int(11) DEFAULT NULL,
    `report_type` varchar(25) DEFAULT NULL,
    `report_type_condition` varchar(25) DEFAULT NULL,
    `report_type_val` float(10,2) DEFAULT NULL,
    `vehicleid` int(11) DEFAULT NULL,
    `trip_endtime_flag` datetime DEFAULT NULL,
    `is_deleted` tinyint(1) DEFAULT '0',
    `entry_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_by` int(11) DEFAULT NULL,
    `insertedBy` int(11) DEFAULT NULL,
    `insertedOn` datetime DEFAULT NULL
    );


  DELIMITER $$
  DROP TRIGGER IF EXISTS after_trip_exception_insert $$
  CREATE TRIGGER `after_trip_exception_insert` AFTER INSERT ON trip_exception_alerts FOR EACH ROW BEGIN

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
            `insertedBy` = NEW.createdBy, 
            `insertedOn` = NEW.createdOn;
  END;
  END $$
  DELIMITER ;


  DELIMITER $$
  DROP TRIGGER IF EXISTS after_trip_exception_update $$
  CREATE TRIGGER `after_trip_exception_update` AFTER UPDATE ON trip_exception_alerts FOR EACH ROW BEGIN

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

-- ******* TRIP EXCEPTION ALERT *******



-- ******* STOPPAGE ALERT *******

  ALTER TABLE stoppage_alerts
    ADD COLUMN createdBy INT,
    ADD COLUMN createdOn datetime,
    ADD COLUMN updatedBy INT,
    ADD COLUMN updatedOn datetime;


  DROP TABLE IF EXISTS `stoppage_alerts_audit_trail`;
  CREATE TABLE `stoppage_alerts_audit_trail` (
    `st_alert_auditId` int(11) NOT NULL AUTO_INCREMENT,
    `id` int(11) NOT NULL,
    `customerno` int(11) NOT NULL,
    `userid` int(11) NOT NULL,
    `is_chk_sms` tinyint(1) NOT NULL,
    `is_trans_sms` tinyint(1) NOT NULL,
    `is_chk_email` tinyint(1) NOT NULL,
    `is_chk_telephone` tinyint(4) NOT NULL,
    `is_chk_mobilenotification` tinyint(4) NOT NULL,
    `is_trans_email` tinyint(1) NOT NULL,
    `is_trans_telephone` tinyint(4) NOT NULL,
    `is_trans_mobilenotification` tinyint(4) NOT NULL,
    `chkmins` int(11) NOT NULL,
    `transmins` int(11) NOT NULL,
    `isdeleted` tinyint(1) NOT NULL,
    `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `alert_sent` tinyint(1) NOT NULL,
    `vehicleid` int(11) NOT NULL,
    `insertedBy` int(11) DEFAULT NULL,
    `insertedOn` datetime DEFAULT NULL,
    PRIMARY KEY (`st_alert_auditId`),
    KEY `customerno` (`customerno`),
    KEY `vehicleid` (`vehicleid`)
  );

  DELIMITER $$
  DROP TRIGGER IF EXISTS after_stoppageAlert_insert $$
  CREATE TRIGGER after_stoppageAlert_insert AFTER INSERT ON stoppage_alerts FOR EACH ROW BEGIN

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
      insertedBy           = NEW.createdBy,
      insertedOn         = NEW.createdOn;
      END;
  END $$
  DELIMITER ;


  DELIMITER $$
  DROP TRIGGER IF EXISTS after_stoppageAlert_update $$
  CREATE TRIGGER after_stoppageAlert_update AFTER UPDATE ON stoppage_alerts FOR EACH ROW BEGIN

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


-- ******* STOPPAGE ALERT *******



 
 
-- ******* USER_REPORT_MAPPING *******
  DROP TABLE IF EXISTS `userreportmapping_audit_trail`;
  CREATE TABLE `userreportmapping_audit_trail` (
    `userReportId_audit_trail` INT AUTO_INCREMENT,
    `userReportId` int(11) NOT NULL,
    `reportId` int(11) NOT NULL,
    `reportTime` varchar(10) NOT NULL,
    `isActivated` tinyint(4) NOT NULL DEFAULT '0',
    `userid` int(11) NOT NULL,
    `customerno` int(11) NOT NULL,
    `inserted_on` int(11) NOT NULL,
    `inserted_by` datetime DEFAULT NULL,
    `isdeleted` tinyint(4) DEFAULT '0',
    PRIMARY KEY (`userReportId_audit_trail`)
  );
  
  DELIMITER $$
  DROP TRIGGER IF EXISTS after_userReportmapping_insert $$
  CREATE TRIGGER after_userReportmapping_insert AFTER INSERT ON userReportMapping FOR EACH ROW BEGIN

    BEGIN

      INSERT INTO userreportmapping_audit_trail

      SET userReportId = NEW.userReportId,
      reportId = NEW.reportId, 
      isActivated = NEW.isActivated,
      userid = NEW.userid, 
      customerno  = NEW.customerno,
      inserted_on = NEW.created_on,
      inserted_by = NEW.created_by,
      isdeleted = NEW.isdeleted;
  END;
  END $$
  DELIMITER ; 


  DELIMITER $$
  DROP TRIGGER IF EXISTS after_userReportmapping_update $$
  CREATE TRIGGER after_userReportmapping_update AFTER UPDATE ON userReportMapping FOR EACH ROW BEGIN

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


-- ******* USER_REPORT_MAPPING *******




-- ******* TEMP_INTERVAL_MAPPING *******

  DROP TABLE IF EXISTS `tempreportinterval_audit_trail`;
  CREATE TABLE `tempreportinterval_audit_trail` (
    `tr_audit_trail_id` INT AUTO_INCREMENT,
    `trid` int(11) NOT NULL ,
    `userid` int(11) NOT NULL,
    `customerno` int(11) NOT NULL,
    `interval` int(11) NOT NULL,
    `insertedby` int(11) NOT NULL,
    `insertedon` datetime DEFAULT NULL,
    `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`tr_audit_trail_id`)
  );


  DELIMITER $$
  DROP TRIGGER IF EXISTS after_tempReportmapping_insert $$
  CREATE TRIGGER after_tempReportmapping_insert AFTER INSERT ON tempreportinterval FOR EACH ROW BEGIN

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
  DROP TRIGGER IF EXISTS after_tempReportmapping_update $$
  CREATE TRIGGER after_tempReportmapping_update AFTER UPDATE ON tempreportinterval FOR EACH ROW BEGIN

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


-- ******* TEMP_INTERVAL_MAPPING *******




-- ******* VEH_INTERVAL_MAPPING *******

  DROP TABLE IF EXISTS `vehrepinterval_audit_trail`;
  CREATE TABLE `vehrepinterval_audit_trail` (
    `vr_audit_trail_id` INT AUTO_INCREMENT,
    `vrid` int(11) NOT NULL ,
    `userid` int(11) NOT NULL,
    `customerno` int(11) NOT NULL,
    `interval` int(11) NOT NULL,
    `insertedby` int(11) NOT NULL,
    `insertedon` datetime DEFAULT NULL,
    `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`vr_audit_trail_id`)
  );



  DELIMITER $$
  DROP TRIGGER IF EXISTS after_vehReportmapping_insert $$
  CREATE TRIGGER after_vehReportmapping_insert AFTER INSERT ON vehrepinterval FOR EACH ROW BEGIN

    BEGIN

      INSERT INTO vehrepinterval_audit_trail

      SET vrid = NEW.vrid,
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
  DROP TRIGGER IF EXISTS after_vehReportmapping_update $$
  CREATE TRIGGER after_vehReportmapping_update AFTER UPDATE ON vehrepinterval FOR EACH ROW BEGIN

    BEGIN

      INSERT INTO vehrepinterval_audit_trail

      SET vrid = OLD.vrid,
      userid = OLD.userid, 
      customerno = OLD.customerno,
      `interval` = OLD.`interval`, 
      insertedby  = OLD.updatedby,
      insertedon = OLD.updatedon,
      isdeleted = OLD.isdeleted;
  END;
  END $$
  DELIMITER ; 


-- ******* VEH_INTERVAL_MAPPING *******




-- ******* CHK_USER_MAPPING *******

  DROP TABLE IF EXISTS `chkptexusermapping_audit_trail`;
  CREATE TABLE `chkptexusermapping_audit_trail` (
    `chkExUserMapping_audit_Id` INT AUTO_INCREMENT,
    `chkExUserMappingId` int(11),
    `chkExId` int(11),
    `userid` int(11) NOT NULL,
    `customerno` int(11) NOT NULL,
    `insertedby` int(11) NOT NULL,
    `insertedon` datetime DEFAULT NULL,
    `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`chkExUserMapping_audit_Id`)
  );


  DELIMITER $$
  DROP TRIGGER IF EXISTS after_chkptexusermapping_insert $$
  CREATE TRIGGER after_chkptexusermapping_insert AFTER INSERT ON chkptexusermapping FOR EACH ROW BEGIN

    BEGIN

      INSERT INTO chkptexusermapping_audit_trail

      SET chkExUserMappingId = NEW.chkExUserMappingId,
      chkExId = NEW.chkExId,
      userid = NEW.userid, 
      customerno = NEW.customerno,
      insertedby  = NEW.created_by,
      insertedon = NEW.created_on,
      isdeleted = NEW.isdeleted;
  END;
  END $$
  DELIMITER ; 


  DELIMITER $$
  DROP TRIGGER IF EXISTS after_chkptexusermapping_update $$
  CREATE TRIGGER after_chkptexusermapping_update AFTER UPDATE ON chkptexusermapping FOR EACH ROW BEGIN

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

-- ******* CHK_USER_MAPPING *******




-- ******* USER_ALERT_MAPPING *******

  DROP TABLE IF EXISTS `useralertmapping_audit_trail`;
  CREATE TABLE `useralertmapping_audit_trail` (
    `alertMappingId_audit_trail` INT  AUTO_INCREMENT,
    `alertMappingId` int(11) NOT NULL ,
    `userId` int(11) NOT NULL,
    `alertId` int(11) NOT NULL,
    `alertTypeId` int(11) NOT NULL,
    `isActive` tinyint(1) NOT NULL,
    `customerno` int(11) NOT NULL,
    `insertedby` int(11) NOT NULL,
    `insertedon` datetime DEFAULT NULL,
    `isdeleted` tinyint(1) DEFAULT '0',
    PRIMARY KEY (`alertMappingId_audit_trail`)
  );


  DELIMITER $$
  DROP TRIGGER IF EXISTS after_useralertmapping_insert $$
  CREATE TRIGGER after_useralertmapping_insert AFTER INSERT ON userAlertMapping FOR EACH ROW BEGIN

    BEGIN

      INSERT INTO useralertmapping_audit_trail

      SET alertMappingId = NEW.alertMappingId,
      userId = NEW.userid,
      alertId = NEW.alertId, 
      alertTypeId = NEW.alertTypeId,
      isActive  = NEW.isActive,
      customerno = NEW.customerno,
      insertedby = NEW.created_by,
      insertedby =  NEW.created_on,
      isdeleted =  NEW.isdeleted;
  END;
  END $$
  DELIMITER ; 


  DELIMITER $$
  DROP TRIGGER IF EXISTS after_useralertmapping_update $$
  CREATE TRIGGER after_useralertmapping_update AFTER UPDATE ON userAlertMapping FOR EACH ROW BEGIN

    BEGIN

      INSERT INTO useralertmapping_audit_trail

      SET alertMappingId = OLD.alertMappingId,
      userId = OLD.userid,
      alertId = OLD.alertId, 
      alertTypeId = OLD.alertTypeId,
      isActive  = OLD.isActive,
      customerno = OLD.customerno,
      insertedby = OLD.updated_by,
      insertedby =  OLD.updated_on,
    isdeleted =  OLD.isdeleted;
  END;
  END $$
  DELIMITER ; 

-- ******* USER_ALERT_MAPPING *******





-- ******* VEH_WISE_ALERT_MAPPING *******

  ALTER TABLE `vehiclewise_alert`
    ADD COLUMN updated_by INT;

  DROP TABLE IF EXISTS `vehiclewise_alert_audit_trail`;
  CREATE TABLE `vehiclewise_alert_audit_trail` (
    `veh_alert_auditId` INT AUTO_INCREMENT,
    `veh_alert_id` int(11) NOT NULL ,
    `customerno` int(11) DEFAULT NULL,
    `userid` int(11) DEFAULT NULL,
    `vehicleid` int(11) DEFAULT NULL,
    `temp_active` tinyint(1) DEFAULT '0',
    `temp_starttime` time DEFAULT '00:00:00',
    `temp_endtime` time DEFAULT '23:59:00',
    `ignition_active` tinyint(1) DEFAULT '0',
    `ignition_starttime` time DEFAULT '00:00:00',
    `ignition_endtime` time DEFAULT '23:59:00',
    `speed_active` tinyint(1) DEFAULT '0',
    `speed_starttime` time DEFAULT '00:00:00',
    `speed_endtime` time DEFAULT '23:59:00',
    `ac_active` tinyint(1) DEFAULT '0',
    `ac_starttime` time DEFAULT '00:00:00',
    `ac_endtime` time DEFAULT '23:59:00',
    `powerc_active` tinyint(1) DEFAULT '0',
    `powerc_starttime` time DEFAULT '00:00:00',
    `powerc_endtime` time DEFAULT '23:59:00',
    `tamper_active` tinyint(1) DEFAULT '0',
    `tamper_starttime` time DEFAULT '00:00:00',
    `tamper_endtime` time DEFAULT '23:59:00',
    `harsh_break_active` tinyint(1) DEFAULT '0',
    `harsh_break_starttime` time DEFAULT '00:00:00',
    `harsh_break_endtime` time DEFAULT '23:59:00',
    `high_acce_active` tinyint(1) DEFAULT '0',
    `high_acce_starttime` time DEFAULT '00:00:00',
    `high_acce_endtime` time DEFAULT '23:59:00',
    `towing_active` tinyint(1) DEFAULT '0',
    `towing_starttime` time DEFAULT '00:00:00',
    `towing_endtime` time DEFAULT '23:59:00',
    `panic_active` tinyint(1) DEFAULT '0',
    `panic_starttime` time DEFAULT '00:00:00',
    `panic_endtime` time DEFAULT '23:59:00',
    `immob_active` tinyint(1) DEFAULT '0',
    `immob_starttime` time DEFAULT '00:00:00',
    `immob_endtime` time DEFAULT '23:59:00',
    `door_active` tinyint(1) DEFAULT '0',
    `door_starttime` time DEFAULT '00:00:00',
    `door_endtime` time DEFAULT '23:59:00',
    `hum_active` tinyint(1) DEFAULT '0',
    `hum_starttime` time DEFAULT '00:00:00',
    `hum_endtime` time DEFAULT '23:59:00',
    `insertedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `insertedBy` INT,
    `isdeleted` tinyint(1) DEFAULT '0',
    PRIMARY KEY (`veh_alert_auditId`),
    KEY `vehicleid` (`vehicleid`),
    KEY `userid` (`userid`)
  );


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
      temp_endtimetime  =  NEW.temp_endtime ,
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
  DROP TRIGGER IF EXISTS after_vehwise_alert_update $$
  CREATE TRIGGER after_vehwise_alert_update AFTER UPDATE ON vehiclewise_alert FOR EACH ROW BEGIN

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


-- ******* VEH_WISE_ALERT_MAPPING *******


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
      


      SET @STMT = CONCAT("SELECT uat.*,r.role, FROM user_audit_trail uat INNER JOIN `group` g on g.groupid = uat.groupid INNER JOIN role r on r.id = uat.roleid WHERE uat.customerno =", customernoParam, " AND uat.userid =", userIdParam," AND date(uat.insertedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY insertedOn desc ",limitCondition);
          PREPARE S FROM @STMT;
      EXECUTE S;
      DEALLOCATE PREPARE S; 
        
      END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_stoppage_alerts_logs`$$
CREATE PROCEDURE `fetch_stoppage_alerts_logs`(
  IN customernoParam INT,
  IN userIdParam INT,
  IN startdateParam date,
  IN enddateParam date,
  IN limitParam INT
  )
  BEGIN

   DECLARE limitCondition VARCHAR(10);
    
      SET limitCondition = CONCAT(' LIMIT ', limitParam);
      


      SET @STMT = CONCAT("SELECT stat.*,u.realname FROM stoppage_alerts_audit_trail stat INNER JOIN user u on u.userid = stat.insertedBy  WHERE stat.customerno =", customernoParam, " AND stat.userid =", userIdParam," AND date(stat.insertedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY insertedOn desc ",limitCondition);
      PREPARE S FROM @STMT;
      EXECUTE S;
      DEALLOCATE PREPARE S; 
        
      END$$

DELIMITER ;

UPDATE  dbpatches
SET     patchdate = '2018-03-02 17:30:00'
        ,isapplied =1
WHERE   patchid = 675;

