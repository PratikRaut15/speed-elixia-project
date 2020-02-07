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
    DROP TRIGGER IF EXISTS `after_vehicle_update` $$
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
