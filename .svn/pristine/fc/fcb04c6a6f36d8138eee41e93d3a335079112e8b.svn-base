INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'704', '2019-04-26 14:45:00', 'Yash Kanakia','Audit Trail Changes', '0');

DELIMITER $$
  DROP TRIGGER IF EXISTS after_user_update $$
  CREATE TRIGGER `after_user_update` after UPDATE ON user FOR EACH ROW BEGIN

      BEGIN
      IF(OLD.realname <> NEW.realname) OR (OLD.email <> NEW.email) OR (OLD.username <> NEW.username)
      OR (OLD.phone <> NEW.phone) THEN
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
      END IF;
  END;
  END $$
  DELIMITER ;

DELIMITER $$
  DROP TRIGGER IF EXISTS after_checkpointmanage_update $$
  CREATE TRIGGER `after_checkpointmanage_update` AFTER UPDATE ON checkpointmanage FOR EACH ROW BEGIN
  BEGIN
  IF(OLD.checkpointid <> NEW.checkpointid) OR (OLD.vehicleid <> NEW.vehicleid) THEN
    INSERT INTO checkpointmanage_audit_trail
    SET
    cmid   = NEW.cmid,
    checkpointid  = NEW.checkpointid,
    vehicleid   = NEW.vehicleid,
    customerno   = NEW.customerno,
    conflictstatus  = NEW.conflictstatus,
    inTime = NEW.inTime,
    outTime = NEW.outTime,
    isDelayExpected  = NEW.isDelayExpected,
    userid = NEW.userid,
    isdeleted  = NEW.isdeleted,
      timestamp  = NEW.timestamp,
    insertedBy = NEW.updatedBy,
    insertedOn = NEW.updatedOn;
  END IF;
  END;
  END $$
  DELIMITER ;

DELIMITER $$
  DROP TRIGGER IF EXISTS after_fenceman_update $$
  CREATE TRIGGER `after_fenceman_update` AFTER UPDATE ON fenceman FOR EACH ROW BEGIN
  BEGIN
  IF(OLD.fenceid <> NEW.fenceid) OR (OLD.vehicleid <> NEW.vehicleid) THEN
    INSERT INTO fenceman_audit_trail
    SET
    fmid   = NEW.fmid,
    fenceid  = NEW.fenceid,
    vehicleid   = NEW.vehicleid,
    customerno   = NEW.customerno,
    conflictstatus  = NEW.conflictstatus,
    userid = NEW.userid,
    isdeleted  = NEW.isdeleted,
    insertedBy = NEW.updatedBy,
    insertedOn = NEW.updatedOn;
  END IF;
  END;
  END $$
  DELIMITER ;

UPDATE  dbpatches
SET     patchdate = '2019-04-26 14:45:00'
        ,isapplied =1
WHERE   patchid = 704;

