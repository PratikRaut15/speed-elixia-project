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
