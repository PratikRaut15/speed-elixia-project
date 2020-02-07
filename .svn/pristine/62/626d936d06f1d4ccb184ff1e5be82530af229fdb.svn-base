INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'688', '2018-03-14 11:30:00', 'Yash Kanakia','Vehicle Audit Trail', '0');


	DELIMITER $$
	DROP TRIGGER IF EXISTS before_vehicle_update $$
	CREATE TRIGGER `before_vehicle_update` BEFORE UPDATE ON vehicle FOR EACH ROW BEGIN
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
				VALUES 	(
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
										, OLD.other_upload1
										, OLD.other_upload2
										, OLD.other_upload3
										, OLD.timestamp
										, OLD.stoppage_odometer
										, OLD.stoppage_transit_time
										, OLD.nodata_alert
										, OLD.stoppage_flag
										, OLD.submission_date
										, OLD.registration_date
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
	END;
	END $$
	DELIMITER ;

	DROP TRIGGER IF EXISTS before_checkpointmanage_update;

	DELIMITER $$
	DROP TRIGGER IF EXISTS after_checkpointmanage_update $$
	CREATE TRIGGER `after_checkpointmanage_update` AFTER UPDATE ON checkpointmanage FOR EACH ROW BEGIN
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

	DROP TRIGGER IF EXISTS before_fenceman_update;

	DELIMITER $$
	DROP TRIGGER IF EXISTS after_fenceman_update $$
	CREATE TRIGGER `after_fenceman_update` AFTER UPDATE ON fenceman FOR EACH ROW BEGIN
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

	DROP TRIGGER IF EXISTS before_batch_update;

	DELIMITER $$
	DROP TRIGGER IF EXISTS after_batch_update $$
	CREATE TRIGGER `after_batch_update` AFTER UPDATE ON batch FOR EACH ROW BEGIN
	BEGIN
		INSERT INTO batch_audit_trail
		SET
		batchid   = NEW.batchid,  
		vehicleid  = NEW.vehicleid,
		customerno   = NEW.customerno,  
		batchno   = NEW.batchno,  
		workkey  = NEW.workkey,
		starttime = NEW.starttime, 
		dummybatch = NEW.dummybatch,
		pmid  = NEW.pmid, 
		isdeleted = NEW.isdeleted, 
		last_fid  = NEW.last_fid, 
		insertedBy = NEW.updatedBy,
		insertedOn = NEW.updatedon;
	END;
	END $$
	DELIMITER ;

	DROP TRIGGER IF EXISTS before_ignitionalert_update;

	DELIMITER $$
	DROP TRIGGER IF EXISTS after_ignitionalert_update $$
	CREATE TRIGGER `after_ignitionalert_update` AFTER UPDATE ON ignitionalert FOR EACH ROW BEGIN
	BEGIN
		INSERT INTO ignitionalert_audit_trail
		SET
		vehicleid   = NEW.vehicleid,  
		last_status  = NEW.last_status,
		last_check   = NEW.last_check,  
		count   = NEW.count,  
		idleignon_count  = NEW.idleignon_count,
		running_count = NEW.running_count, 
		`status` = NEW.`status`,
		customerno  = NEW.customerno, 
		igalertid = NEW.igalertid, 
		ignchgtime  = NEW.ignchgtime, 
		ignontime  = NEW.ignontime, 
		prev_odometer_reading  = NEW.prev_odometer_reading,
		prev_odometer_time = NEW.prev_odometer_time,
		insertedBy = NEW.updatedBy,
		insertedOn = NEW.updatedOn;
	END;
	END $$
	DELIMITER ;

	DROP TRIGGER IF EXISTS before_eventAlert_update;

	DELIMITER $$
	DROP TRIGGER IF EXISTS after_eventAlert_update $$
	CREATE TRIGGER `after_eventAlert_update` AFTER UPDATE ON eventalerts FOR EACH ROW BEGIN
	BEGIN
		INSERT INTO eventalerts_audit_trail
		SET
		vehicleid   = NEW.vehicleid,  
		overspeed  = NEW.overspeed,
		tamper   = NEW.tamper,  
		powercut   = NEW.powercut,  
		temp  = NEW.temp,
		temp2 = NEW.temp2, 
		temp3 = NEW.temp3,
		temp4  = NEW.temp4, 
		temp_sms = NEW.temp_sms, 
		temp2_sms  = NEW.temp2_sms, 
		temp3_sms  = NEW.temp3_sms, 
		temp4_sms  = NEW.temp4_sms,
		temp_email = NEW.temp_email,
		temp2_email  = NEW.temp2_email, 
		temp3_email  = NEW.temp3_email, 
		temp4_email  = NEW.temp4_email, 
		ac   = NEW.ac, 
		customerno   = NEW.customerno,  
		eaid   = NEW.eaid,
		ac_count  = NEW.ac_count,  
		ac_time   = NEW.ac_time,
		door  = NEW.door, 
		door_time  = NEW.door_time,
		insertedBy = NEW.updatedBy,
		insertedOn = NEW.updatedOn;
	END;
	END $$
	DELIMITER ;
	
UPDATE  dbpatches
SET     patchdate = '2018-03-14 11:30:00'
        ,isapplied =1
WHERE   patchid = 688;
