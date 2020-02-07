/* Create dbpatch */
    INSERT INTO `dbpatches` (
    `patchid`,
    `patchdate`,
    `appliedby`,
    `patchdesc`,
    `isapplied`
    )
    VALUES
    (
    '716', '2019-06-01 12:48:00',
    'Kartik Joshi','Changes  for user_audit_trail','0');



ALTER TABLE user_audit_trail ADD COLUMN createdBy INT;
ALTER TABLE user_audit_trail ADD COLUMN createdOn DATETIME;
ALTER TABLE user_audit_trail ADD COLUMN updatedBy INT;
ALTER TABLE user_audit_trail ADD COLUMN updatedOn DATETIME;

DELIMITER $$
DROP TRIGGER IF EXISTS `after_user_update`$$
CREATE TRIGGER `after_user_update` AFTER UPDATE ON `user`
 FOR EACH ROW BEGIN

        BEGIN

        IF(OLD.realname <> NEW.realname)
        OR (OLD.email <> NEW.email)
        OR (OLD.username <> NEW.username)
        OR (OLD.phone <> NEW.phone)
        OR (OLD.stateid <> NEW.stateid)
        OR (OLD.roleid <> NEW.roleid)
        OR (OLD.mess_email <> NEW.mess_email)
        OR (OLD.mess_sms <> NEW.mess_sms)
        OR (OLD.speed_email <> NEW.speed_email)
        OR (OLD.speed_sms <> NEW.speed_sms)
        OR (OLD.power_email <> NEW.power_email)
        OR (OLD.power_sms <> NEW.power_sms)
        OR (OLD.tamper_email <> NEW.tamper_email)
        OR (OLD.tamper_sms <> NEW.tamper_sms)
        OR (OLD.chk_email <> NEW.chk_email)
        OR (OLD.chk_sms <> NEW.chk_sms)
        OR (OLD.ac_email <> NEW.ac_email)
        OR (OLD.ac_sms <> NEW.ac_sms)
        OR (OLD.ignition_email <> NEW.ignition_email)
        OR (OLD.aci_email <> NEW.aci_email)
        OR (OLD.aci_sms <> NEW.aci_sms)
        OR (OLD.temp_email <> NEW.temp_email)
        OR (OLD.temp_sms <> NEW.temp_sms)
        OR (OLD.panic_email <> NEW.panic_email)
        OR (OLD.panic_sms <> NEW.panic_sms)
        OR (OLD.immob_email <> NEW.immob_email)
        OR (OLD.immob_sms <> NEW.immob_sms)
        OR (OLD.door_sms <> NEW.door_sms)
        OR (OLD.door_email <> NEW.door_email)
        OR (OLD.modifiedby <> NEW.modifiedby)
        OR (OLD.isdeleted <> NEW.isdeleted)
        OR (OLD.chgpwd <> NEW.chgpwd)
        OR (OLD.chgalert <> NEW.chgalert)
        OR (OLD.groupid <> NEW.groupid)
        OR (OLD.start_alert <> NEW.start_alert)
        OR (OLD.stop_alert <> NEW.stop_alert)
        OR (OLD.fuel_alert_sms <> NEW.fuel_alert_sms)
        OR (OLD.fuel_alert_email <> NEW.fuel_alert_email)
        OR (OLD.fuel_alert_percentage <> NEW.fuel_alert_percentage)
        OR (OLD.heirarchy_id <> NEW.heirarchy_id)
        OR (OLD.dailyemail <> NEW.dailyemail)
        OR (OLD.dailyemail_csv <> NEW.dailyemail_csv)
        OR (OLD.harsh_break_sms <> NEW.harsh_break_sms)
        OR (OLD.harsh_break_mail <> NEW.harsh_break_mail)
        OR (OLD.high_acce_sms <> NEW.high_acce_sms)
        OR (OLD.high_acce_mail <> NEW.high_acce_mail)
        OR (OLD.sharp_turn_sms <> NEW.sharp_turn_sms)
        OR (OLD.sharp_turn_mail <> NEW.sharp_turn_mail)
        OR (OLD.towing_sms <> NEW.towing_sms)
        OR (OLD.towing_mail <> NEW.towing_mail)
        OR (OLD.tempinterval <> NEW.tempinterval)
        OR (OLD.igninterval <> NEW.igninterval)
        OR (OLD.speedinterval <> NEW.speedinterval)
        OR (OLD.acinterval <> NEW.acinterval)
        OR (OLD.doorinterval <> NEW.doorinterval)
        OR (OLD.chkpushandroid <> NEW.chkpushandroid)
        OR (OLD.chkmanpushandroid <> NEW.chkmanpushandroid)
        OR (OLD.notification_status <> NEW.notification_status)
        OR (OLD.hum_sms <> NEW.hum_sms)
        OR (OLD.hum_email <> NEW.hum_email)
        OR (OLD.hum_telephone <> NEW.hum_telephone)
        OR (OLD.hum_mobilenotification <> NEW.hum_mobilenotification)
        OR (OLD.huminterval <> NEW.huminterval)
        OR (OLD.refreshtime <> NEW.refreshtime)
        OR (OLD.isTempInrangeAlertRequired <> NEW.isTempInrangeAlertRequired)
        OR (OLD.isAdvTempConfRange <> NEW.isAdvTempConfRange)
        THEN

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

            ,insertedOn       = NEW.updatedOn

            ,updatedBy       = NEW.updatedBy

            ,updatedOn       = NEW.updatedOn

            ,createdBy		  = NEW.createdBy

            ,createdOn		  = NEW.createdOn;
        END IF;
    END;
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `after_user_insert`$$
CREATE TRIGGER `after_user_insert` AFTER insert ON `user`
 FOR EACH ROW BEGIN

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

            ,insertedBy       = NEW.createdBy

            ,insertedOn       = NEW.createdOn

            ,updatedBy       = NEW.updatedBy

            ,updatedOn       = NEW.updatedOn

            ,createdBy		  = NEW.createdBy

            ,createdOn		  = NEW.createdOn;
    END;
END $$
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

        SET limitCondition = '';

      IF (limitParam <> -1) THEN
        SET limitCondition = CONCAT(' LIMIT ', limitParam);
      END IF;



      SET @STMT = CONCAT("
		SELECT DISTINCT uat.*,
				r.role,
                u.realname as inserted_by,
                userCreated.realname as createdBy,
                uat.createdOn,
                userUpdated.realname as updatedBy,
                uat.updatedOn
			FROM user_audit_trail uat
            INNER JOIN role r on r.id = uat.roleid
            INNER JOIN user u on u.userid = uat.insertedBy
            LEFT OUTER JOIN user userCreated ON userCreated.userid = uat.userid
            LEFT OUTER JOIN user userUpdated ON userUpdated.userid = uat.userid
		WHERE uat.customerno =", customernoParam, "
        AND uat.userid =", userIdParam,"
        AND date(uat.insertedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"'
        ORDER BY uat.insertedOn desc ",limitCondition);
      PREPARE S FROM @STMT;
      EXECUTE S;
      DEALLOCATE PREPARE S;
    END$$
DELIMITER ;

UPDATE  dbpatches
SET     patchdate = '2019-06-01 12:48:00'
    ,isapplied =1
WHERE   patchid = 716;

