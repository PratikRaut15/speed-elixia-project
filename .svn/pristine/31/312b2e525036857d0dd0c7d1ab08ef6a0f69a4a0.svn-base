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
    '711', '2019-05-22 12:32:00',
    'Kartik Joshi','SPs & triggers for audit trail','0');

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

      SET limitCondition = '';
        
      IF (limitParam <> -1) THEN
        SET limitCondition = CONCAT(' LIMIT ', limitParam);
      END IF;



      SET @STMT = CONCAT("SELECT vuat.*,v.vehicleno,u.realname FROM vehicleusermapping_audit_trail vuat INNER JOIN `vehicle` v on v.vehicleid = vuat.vehicleid INNER JOIN user u on u.userid = vuat.created_by WHERE vuat.customerno =", customernoParam, " AND vuat.userid =", userIdParam," AND date(vuat.created_on) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY vuat.created_on desc ",limitCondition);
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

      SET limitCondition = '';
        
      IF (limitParam <> -1) THEN
        SET limitCondition = CONCAT(' LIMIT ', limitParam);
      END IF;



      SET @STMT = CONCAT("SELECT stat.*,u.realname FROM stoppage_alerts_audit_trail stat INNER JOIN user u on u.userid = stat.insertedBy  WHERE stat.customerno =", customernoParam, " AND stat.userid =", userIdParam," AND date(stat.insertedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY stat.insertedOn desc ",limitCondition);
          PREPARE S FROM @STMT;
      EXECUTE S;
      DEALLOCATE PREPARE S;

      END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_groupman_logs`$$
CREATE PROCEDURE `fetch_groupman_logs`(
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



    SET @STMT = CONCAT("SELECT DISTINCT gm.*,g.groupname,u.realname FROM groupman_audit_trail gm LEFT JOIN `group` g on g.groupid = gm.groupid INNER JOIN user u on u.userid = gm.createdBy  WHERE gm.customerno =", customernoParam, " AND gm.userid =", userIdParam," AND date(gm.createdOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY gm.createdOn desc ",limitCondition);
        PREPARE S FROM @STMT;
    EXECUTE S;
    DEALLOCATE PREPARE S;

    END$$

DELIMITER ;

/////////////////////////////////////////VEHICLE/////////////////////////////////////////////////////////

DELIMITER $$
DROP procedure IF EXISTS `fetch_vehicle_logs`$$
CREATE PROCEDURE `fetch_vehicle_logs`(
	IN customernoParam INT,
	IN vehicleIdParam INT,
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



			SET @STMT = CONCAT("SELECT vat.*,g.groupname,u.realname FROM vehicle_audit_trail vat LEFT JOIN `group` g on g.groupid = vat.groupid INNER JOIN user u on u.userid = vat.insertedBy WHERE vat.customerno =", customernoParam, " AND vat.vehicleid =", vehicleIdParam," AND date(vat.insertedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY insertedOn desc ",limitCondition);
	        PREPARE S FROM @STMT;
			EXECUTE S;
			DEALLOCATE PREPARE S;

	    END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_checkpoint_logs`$$
CREATE PROCEDURE `fetch_checkpoint_logs`(
	IN customernoParam INT,
	IN vehicleIdParam INT,
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


		SET @STMT = CONCAT("SELECT cat.*,c.cname,u.realname,v.vehicleno FROM checkpointmanage_audit_trail cat LEFT JOIN `checkpoint` c on c.checkpointid = cat.checkpointid INNER JOIN user u on u.userid = cat.insertedBy INNER JOIN vehicle v on v.vehicleid = cat.vehicleid WHERE cat.customerno =", customernoParam, " AND cat.vehicleid =", vehicleIdParam," AND date(cat.insertedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY cat.insertedOn desc,cat.isdeleted ",limitCondition);
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

           SET limitCondition = '';
        
	   	IF (limitParam <> -1) THEN
    		SET limitCondition = CONCAT(' LIMIT ', limitParam);
    	END IF;




		SET @STMT = CONCAT("SELECT fat.*,f.fencename,u.realname,v.vehicleno FROM fenceman_audit_trail fat LEFT JOIN `fence` f on f.fenceid = fat.fenceid INNER JOIN vehicle v on v.vehicleid = fat.vehicleid INNER JOIN user u on u.userid = fat.insertedBy WHERE fat.customerno =", customernoParam, " AND fat.vehicleid =", vehicleIdParam," AND date(fat.insertedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' AND f.fencename IS NOT NULL GROUP BY fat.fmid,fat.insertedOn,fat.isdeleted ORDER BY fat.insertedOn desc,fat.isdeleted  ",limitCondition);
        PREPARE S FROM @STMT;
		EXECUTE S;
		DEALLOCATE PREPARE S;

    END$$

DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `after_user_update`$$
CREATE TRIGGER `after_user_update` AFTER UPDATE ON `user` 
 FOR EACH ROW BEGIN

        BEGIN
        IF(OLD.realname <> NEW.realname) 
        OR (OLD.email <> NEW.email) 
        OR (OLD.username <> NEW.username) 
        OR (OLD.phone <> NEW.phone) 
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

            ,insertedOn       = NEW.updatedOn;
        END IF;
    END;
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `after_groupman_update`$$
CREATE TRIGGER `after_groupman_update` AFTER UPDATE ON `groupman`
 FOR EACH ROW BEGIN
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

DELIMITER $$
DROP TRIGGER IF EXISTS `after_groupman_insert`$$
CREATE TRIGGER `after_groupman_insert` AFTER INSERT ON `groupman`
 FOR EACH ROW BEGIN
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
DROP TRIGGER IF EXISTS `after_stoppageAlert_update`$$
CREATE TRIGGER `after_stoppageAlert_update` AFTER UPDATE ON `stoppage_alerts`
 FOR EACH ROW BEGIN
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
DROP TRIGGER IF EXISTS `after_vehicleusermapping_insert`$$
CREATE TRIGGER `after_vehicleusermapping_insert` AFTER INSERT ON `vehicleusermapping`
 FOR EACH ROW BEGIN

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

DELIMITER $$
DROP TRIGGER IF EXISTS `after_vehicleusermapping_update`$$
CREATE TRIGGER `after_vehicleusermapping_update` AFTER UPDATE ON `vehicleusermapping`
 FOR EACH ROW BEGIN

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

DELIMITER $$
DROP TRIGGER IF EXISTS `after_groupman_update`$$
CREATE TRIGGER `after_groupman_update` AFTER UPDATE ON `groupman`
 FOR EACH ROW BEGIN
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

DELIMITER $$
DROP TRIGGER IF EXISTS `after_groupman_insert`$$
CREATE TRIGGER `after_groupman_insert` AFTER INSERT ON `groupman`
 FOR EACH ROW BEGIN
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
DROP TRIGGER IF EXISTS after_vehicle_update $$
CREATE TRIGGER `after_vehicle_update` AFTER UPDATE ON vehicle FOR EACH ROW BEGIN
    BEGIN
        /* Need to update the data for Prakash Enterprise in socketDB */
        IF NEW.customerno = 219 AND ((NEW.lastupdated <> NEW.lastupdated) OR (NEW.uid <> NEW.uid)) THEN
            UPDATE socketDB.vehicle
            SET
                    vehicleno = NEW.vehicleno
                    ,extbatt = NEW.extbatt
                    ,odometer = NEW.odometer
                    ,lastupdated = NEW.lastupdated
                    ,driverid = NEW.driverid
                    ,curspeed = NEW.curspeed
                    ,customerno = NEW.customerno
                    ,uid = NEW.uid
                    ,isdeleted = NEW.isdeleted
                    ,kind = NEW.kind
                    ,groupid = NEW.groupid
                    ,stoppage_odometer = NEW.stoppage_odometer
                    ,stoppage_transit_time = NEW.stoppage_transit_time
                    ,stoppage_flag = NEW.stoppage_flag
                    ,fuel_balance = NEW.fuel_balance
                    ,ignition_wirecut = NEW.ignition_wirecut
            WHERE 	vehicleid = NEW.vehicleid;
        END IF;
        IF (NEW.vehicleno <> OLD.vehicleno) OR (NEW.kind <> OLD.kind) OR (NEW.groupid <> OLD.groupid) OR (NEW.overspeed_limit <> OLD.overspeed_limit) OR
        (NEW.fuelcapacity <> OLD.fuelcapacity) OR (NEW.average <> OLD.average) THEN
                INSERT INTO vehicle_audit_trail
                    (
                    vehicleid,
                    vehicleno,
                    extbatt,
                    odometer,
                    lastupdated,
                    curspeed,
                    driverid,
                    customerno,
                    uid,
                    isdeleted,
                    kind,
                    userid,
                    groupid,
                    branchid,
                    overspeed_limit,
                    average,
                    modelid,
                    manufacturing_month,
                    manufacturing_year,
                    purchase_date,
                    tax_date,
                    permit_date,
                    fitness_date,
                    start_meter_reading,
                    fueltype,
                    is_insured,
                    status_id,
                    temp1_min,
                    temp1_max,
                    temp1_mute,
                    temp2_min,
                    temp2_max,
                    temp2_mute,
                    temp3_min,
                    temp3_max,
                    temp3_mute,
                    temp4_min,
                    temp4_max,
                    temp4_mute,
                    no_of_genset,
                    genset1,
                    genset2,
                    transmitter1,
                    transmitter2,
                    puc_filename,
                    registration_filename,
                    insurance_filename,
                    speed_gov_filename,
                    fire_extinguisher_filename,
                    other_upload5,
                    other_upload6,
                    other_upload1,
                    other_upload2,
                    other_upload3,
                    other_upload4,
                    `timestamp`,
                    stoppage_odometer,
                    stoppage_transit_time,
                    nodata_alert,
                    stoppage_flag,
                    submission_date,
                    registration_date,
                    approval_date,
                    additional_amount,
                    description,
                    sms_count,
                    sms_lock,
                    tel_count,
                    tel_lock,
                    fuel_balance,
                    fuelcapacity,
                    fuelMaxVoltCapacity,
                    max_voltage,
                    fuel_min_volt,
                    fuel_max_volt,
                    notes,
                    rto_location,
                    current_location,
                    authorized_signatory,
                    hypothecation,
                    serial_number,
                    expiry_date,
                    owner_name,
                    invcustid,
                    sequenceno,
                    hum_min,
                    hum_max,
                    checkpointId,
                    chkpoint_status,
                    checkpoint_timestamp,
                    ignition_wirecut,
                    isVehicleResetCmdSent,
                    insertedBy,
                    insertedOn)
            VALUES
                    (NEW.vehicleid
                    ,NEW.vehicleno
                    ,NEW.extbatt
                    ,NEW.odometer
                    ,NEW.lastupdated
                    ,NEW.curspeed
                    ,NEW.driverid
                    ,NEW.customerno
                    ,NEW.uid
                    ,NEW.isdeleted
                    ,NEW.kind
                    ,NEW.userid
                    ,NEW.groupid
                    ,NEW.branchid
                    ,NEW.overspeed_limit
                    ,NEW.average
                    ,NEW.modelid
                    ,NEW.manufacturing_month
                    ,NEW.manufacturing_year
                    ,NEW.purchase_date
                    ,NEW.tax_date
                    ,NEW.permit_date
                    ,NEW.fitness_date
                    ,NEW.start_meter_reading
                    ,NEW.fueltype
                    ,NEW.is_insured
                    ,NEW.status_id
                    ,NEW.temp1_min
                    ,NEW.temp1_max
                    ,NEW.temp1_mute
                    ,NEW.temp2_min
                    ,NEW.temp2_max
                    ,NEW.temp2_mute
                    ,NEW.temp3_min
                    ,NEW.temp3_max
                    ,NEW.temp3_mute
                    ,NEW.temp4_min
                    ,NEW.temp4_max
                    ,NEW.temp4_mute
                    ,NEW.no_of_genset
                    ,NEW.genset1
                    ,NEW.genset2
                    ,NEW.transmitter1
                    ,NEW.transmitter2
                    ,NEW.puc_filename
                    ,NEW.registration_filename
                    ,NEW.insurance_filename
                    ,NEW.speed_gov_filename
                    ,NEW.fire_extinguisher_filename
                    ,NEW.other_upload5
                    ,NEW.other_upload6
                    ,NEW.other_upload1
                    ,NEW.other_upload2
                    ,NEW.other_upload3
                    ,NEW.other_upload4
                    ,NEW.timestamp
                    ,NEW.stoppage_odometer
                    ,NEW.stoppage_transit_time
                    ,NEW.nodata_alert
                    ,NEW.stoppage_flag
                    ,NEW.submission_date
                    ,NEW.registration_date
                    ,NEW.approval_date
                    ,NEW.additional_amount
                    ,NEW.description
                    ,NEW.sms_count
                    ,NEW.sms_lock
                    ,NEW.tel_count
                    ,NEW.tel_lock
                    ,NEW.fuel_balance
                    ,NEW.fuelcapacity
                    ,NEW.fuelMaxVoltCapacity
                    ,NEW.max_voltage
                    ,NEW.fuel_min_volt
                    ,NEW.fuel_max_volt
                    ,NEW.notes
                    ,NEW.rto_location
                    ,NEW.current_location
                    ,NEW.authorized_signatory
                    ,NEW.hypothecation
                    ,NEW.serial_number
                    ,NEW.expiry_date
                    ,NEW.owner_name
                    ,NEW.invcustid
                    ,NEW.sequenceno
                    ,NEW.hum_min
                    ,NEW.hum_max
                    ,NEW.checkpointId
                    ,NEW.chkpoint_status
                    ,NEW.checkpoint_timestamp
                    ,NEW.ignition_wirecut
                    ,NEW.isVehicleResetCmdSent
                    ,NEW.updatedBy
                    ,NEW.updatedOn
                    );
    END IF;
    END;
    END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `after_checkpointmanage_update`$$
CREATE TRIGGER `after_checkpointmanage_update` AFTER UPDATE ON `checkpointmanage`
 FOR EACH ROW BEGIN
	BEGIN
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
	END;
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `after_fenceman_update`$$
CREATE TRIGGER `after_fenceman_update` AFTER UPDATE ON `fenceman`
 FOR EACH ROW BEGIN
	BEGIN
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
	END;
END $$
DELIMITER ;


UPDATE  dbpatches
SET     patchdate = '2019-05-22 12:32:00'
    ,isapplied =1
WHERE   patchid = 711;

