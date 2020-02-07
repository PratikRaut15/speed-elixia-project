INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`)
VALUES ('723', '2019-07-26 17:32:00', 'Arvind Thakur','vehicle column and trigger change', '0');


-- Move following query to speed 
ALTER TABLE `vehicle` 
CHANGE `other_upload5` `other_upload5` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
CHANGE `other_upload6` `other_upload6` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
CHANGE `other_upload1` `other_upload1` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
CHANGE `other_upload2` `other_upload2` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
CHANGE `other_upload3` `other_upload3` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
CHANGE `other_upload4` `other_upload4` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
CHANGE `timestamp` `timestamp` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `stoppage_odometer` `stoppage_odometer` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
CHANGE `stoppage_transit_time` `stoppage_transit_time` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `submission_date` `submission_date` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `registration_date` `registration_date` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `approval_date` `approval_date` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `additional_amount` `additional_amount` INT(11) NULL DEFAULT '0',
CHANGE `description` `description` VARCHAR(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
CHANGE `sms_count` `sms_count` INT(11) NULL DEFAULT '0',
CHANGE `fuelcapacity` `fuelcapacity` INT(4) NULL DEFAULT '0',
CHANGE `notes` `notes` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
CHANGE `invcustid` `invcustid` INT(11) NULL DEFAULT '0';

ALTER TABLE `vehicle_audit_trail` 
CHANGE `submission_date` `submission_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `registration_date` `registration_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';

ALTER TABLE `vehicle_history` CHANGE `submission_date` `submission_date` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `registration_date` `registration_date` DATETIME NULL DEFAULT '0000-00-00 00:00:00';

-- Move following query to socketDB 
ALTER TABLE `socketDB.vehicle` 
CHANGE `other_upload5` `other_upload5` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
CHANGE `other_upload6` `other_upload6` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
CHANGE `other_upload1` `other_upload1` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
CHANGE `other_upload2` `other_upload2` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
CHANGE `other_upload3` `other_upload3` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
CHANGE `other_upload4` `other_upload4` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
CHANGE `timestamp` `timestamp` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `stoppage_odometer` `stoppage_odometer` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
CHANGE `stoppage_transit_time` `stoppage_transit_time` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `submission_date` `submission_date` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `registration_date` `registration_date` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `approval_date` `approval_date` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `additional_amount` `additional_amount` INT(11) NULL DEFAULT '0',
CHANGE `description` `description` VARCHAR(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
CHANGE `sms_count` `sms_count` INT(11) NULL DEFAULT '0',
CHANGE `fuelcapacity` `fuelcapacity` INT(4) NULL DEFAULT '0',
CHANGE `notes` `notes` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
CHANGE `invcustid` `invcustid` INT(11) NULL DEFAULT '0';

DELIMITER $$
DROP TRIGGER IF EXISTS after_vehicle_insert$$
CREATE TRIGGER `after_vehicle_insert` AFTER INSERT ON `vehicle`
 FOR EACH ROW BEGIN
    BEGIN

        IF NEW.customerno = 219 THEN

            SET @serverTime = NOW();
            SET @istDateTime = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');

            INSERT INTO socketDB.vehicle
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
            createdOn)
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
            ,COALESCE(NEW.other_upload5,'')
            ,COALESCE(NEW.other_upload6,'')
            ,COALESCE(NEW.other_upload1,'')
            ,COALESCE(NEW.other_upload2,'')
            ,COALESCE(NEW.other_upload3,'')
            ,COALESCE(NEW.other_upload4,'')
            ,COALESCE(NEW.`timestamp`,'0000-00-00 00:00:00')
            ,COALESCE(NEW.stoppage_odometer,'')
            ,COALESCE(NEW.stoppage_transit_time,'0000-00-00 00:00:00')
            ,NEW.nodata_alert
            ,NEW.stoppage_flag
            ,COALESCE(NEW.submission_date,'0000-00-00 00:00:00')
            ,COALESCE(NEW.registration_date,'0000-00-00 00:00:00')
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
            ,@istDateTime
            );
    END IF;
END;
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS before_vehicle_update$$
CREATE TRIGGER `before_vehicle_update` BEFORE UPDATE ON `vehicle`
 FOR EACH ROW BEGIN
    BEGIN
        IF (NEW.temp1_min <>  OLD.temp1_min
        OR NEW.temp2_min <>  OLD.temp2_min
        OR NEW.temp3_min <>  OLD.temp3_min
        OR NEW.temp4_min <>  OLD.temp4_min
        OR NEW.temp1_max <>  OLD.temp1_max
        OR NEW.temp2_max <>  OLD.temp2_max
        OR NEW.temp3_max <>  OLD.temp3_max
        OR NEW.temp4_max <>  OLD.temp4_max
        ) THEN
        BEGIN
        INSERT INTO vehicle_history
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
                                    start_meter_reading,
                                    fueltype,
                                    is_insured,
                                    status_id,
                                    temp1_min,
                                    temp1_max,
                                    temp2_min,
                                    temp2_max,
                                    temp3_min,
                                    temp3_max,
                                    temp4_min,
                                    temp4_max,
                                    other_upload1,
                                    other_upload2,
                                    other_upload3,
                                    other_upload4,
                                    other_upload5,
                                    other_upload6,
                                    timestamp,
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
                                    fuel_balance,
                                    fuelcapacity,
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
                                    sequenceno
                                    , insertdate)
            VALUES  (
                                    OLD.vehicleid
                                    , OLD.vehicleno
                                    , OLD.extbatt
                                    , OLD.odometer
                                    , OLD.lastupdated
                                    , OLD.curspeed
                                    , OLD.driverid
                                    , OLD.customerno
                                    , OLD.uid
                                    , OLD.isdeleted
                                    , OLD.kind
                                    , OLD.userid
                                    , OLD.groupid
                                    , OLD.branchid
                                    , OLD.overspeed_limit
                                    , OLD.average
                                    , OLD.modelid
                                    , OLD.manufacturing_month
                                    , OLD.manufacturing_year
                                    , OLD.purchase_date
                                    , OLD.start_meter_reading
                                    , OLD.fueltype
                                    , OLD.is_insured
                                    , OLD.status_id
                                    , OLD.temp1_min
                                    , OLD.temp1_max
                                    , OLD.temp2_min
                                    , OLD.temp2_max
                                    , OLD.temp3_min
                                    , OLD.temp3_max
                                    , OLD.temp4_min
                                    , OLD.temp4_max
                                    , COALESCE(OLD.other_upload1,'')
                                    , COALESCE(OLD.other_upload2,'')
                                    , COALESCE(OLD.other_upload3,'')
                                    , COALESCE(OLD.other_upload4,'')
                                    , COALESCE(OLD.other_upload5,'')
                                    , COALESCE(OLD.other_upload6,'')
                                    , COALESCE(OLD.timestamp,'0000-00-00 00:00:00')
                                    , COALESCE(OLD.stoppage_odometer,'')
                                    , COALESCE(OLD.stoppage_transit_time,'0000-00-00 00:00:00')
                                    , OLD.nodata_alert
                                    , OLD.stoppage_flag
                                    , COALESCE(OLD.submission_date,'0000-00-00 00:00:00')
                                    , COALESCE(OLD.registration_date,'0000-00-00 00:00:00')
                                    , OLD.approval_date
                                    , OLD.additional_amount
                                    , OLD.description
                                    , OLD.sms_count
                                    , OLD.sms_lock
                                    , OLD.fuel_balance
                                    , OLD.fuelcapacity
                                    , OLD.max_voltage
                                    , OLD.fuel_min_volt
                                    , OLD.fuel_max_volt
                                    , OLD.notes
                                    , OLD.rto_location
                                    , OLD.current_location
                                    , OLD.authorized_signatory
                                    , OLD.hypothecation
                                    , OLD.serial_number
                                    , OLD.expiry_date
                                    , OLD.owner_name
                                    , OLD.invcustid
                                    , OLD.sequenceno
                                    , NOW()
                    );
        END;
    END IF;
END;
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS after_vehicle_update$$
CREATE TRIGGER `after_vehicle_update` AFTER UPDATE ON `vehicle`
 FOR EACH ROW BEGIN
    BEGIN
        /* Need to update the data for Prakash Enterprise in socketDB */
        IF NEW.customerno = 219 AND ((NEW.lastupdated <> NEW.lastupdated) OR (NEW.uid <> NEW.uid)) THEN
            UPDATE socketDB.vehicle
            SET     vehicleno = NEW.vehicleno
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
                    ,stoppage_transit_time = COALESCE(NEW.stoppage_transit_time,'0000-00-00 00:00:00')
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
                    ,COALESCE(NEW.other_upload5,'')
                    ,COALESCE(NEW.other_upload6,'')
                    ,COALESCE(NEW.other_upload1,'')
                    ,COALESCE(NEW.other_upload2,'')
                    ,COALESCE(NEW.other_upload3,'')
                    ,COALESCE(NEW.other_upload4,'')
                    ,COALESCE(NEW.timestamp,'0000-00-00 00:00:00')
                    ,COALESCE(NEW.stoppage_odometer,'')
                    ,COALESCE(NEW.stoppage_transit_time,'0000-00-00 00:00:00')
                    ,NEW.nodata_alert
                    ,NEW.stoppage_flag
                    ,COALESCE(NEW.submission_date,'0000-00-00 00:00:00')
                    ,COALESCE(NEW.registration_date,'0000-00-00 00:00:00')
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
END$$
DELIMITER ;


UPDATE  dbpatches
SET     updatedOn = DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE)
        ,isapplied = 1
WHERE   patchid = 723;