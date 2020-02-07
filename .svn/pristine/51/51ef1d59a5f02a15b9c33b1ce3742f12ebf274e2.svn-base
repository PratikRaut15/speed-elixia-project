INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'703', '2019-04-26 14:45:00', 'Yash Kanakia','Vehicle Audit Trail', '0');

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




UPDATE  dbpatches
SET     patchdate = '2019-04-26 14:45:00'
        ,isapplied =1
WHERE   patchid = 703;

