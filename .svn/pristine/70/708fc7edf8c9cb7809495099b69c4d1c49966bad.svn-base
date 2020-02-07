INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'678', '2018-03-06 17:50:00', 'Yash Kanakia','Vehicle Audit Trail', '0');

	ALTER TABLE vehicle
	    ADD COLUMN createdBy INT NULL,
	    ADD COLUMN createdOn datetime NULL,
	    ADD COLUMN updatedBy INT NULL,
	    ADD COLUMN updatedOn datetime NULL;


	DROP TABLE IF EXISTS `vehicle_audit_trail`;
	CREATE TABLE `vehicle_audit_trail` (
	  `vehicle_auditId` INT AUTO_INCREMENT,
	  `vehicleid` int(11) NOT NULL ,
	  `vehicleno` varchar(40) NOT NULL,
	  `extbatt` varchar(5) NOT NULL,
	  `odometer` varchar(15) NOT NULL,
	  `lastupdated` datetime NOT NULL,
	  `curspeed` varchar(5) NOT NULL,
	  `driverid` int(11) NOT NULL,
	  `customerno` int(11) NOT NULL,
	  `uid` int(11) NOT NULL,
	  `isdeleted` tinyint(1) NOT NULL,
	  `kind` varchar(11) NOT NULL DEFAULT 'Car',
	  `userid` int(11) NOT NULL,
	  `groupid` int(11) NOT NULL DEFAULT '0',
	  `branchid` int(11) NOT NULL,
	  `overspeed_limit` smallint(3) NOT NULL DEFAULT '80',
	  `average` smallint(2) NOT NULL DEFAULT '0',
	  `modelid` int(11) NOT NULL,
	  `manufacturing_month` int(5) NOT NULL,
	  `manufacturing_year` int(5) NOT NULL,
	  `purchase_date` date NOT NULL,
	  `tax_date` date NOT NULL,
	  `permit_date` date NOT NULL,
	  `fitness_date` date NOT NULL,
	  `start_meter_reading` int(10) NOT NULL,
	  `fueltype` varchar(225) NOT NULL,
	  `is_insured` tinyint(1) NOT NULL,
	  `status_id` tinyint(1) NOT NULL,
	  `temp1_min` decimal(5,2) NOT NULL,
	  `temp1_max` decimal(5,2) NOT NULL,
	  `temp1_allowance` decimal(5,2) DEFAULT '0.00',
	  `temp1_mute` tinyint(1) NOT NULL,
	  `temp2_min` decimal(5,2) NOT NULL,
	  `temp2_max` decimal(5,2) NOT NULL,
	  `temp2_allowance` decimal(5,2) DEFAULT '0.00',
	  `temp2_mute` tinyint(1) NOT NULL,
	  `temp3_min` decimal(5,2) NOT NULL,
	  `temp3_max` decimal(5,2) NOT NULL,
	  `temp3_allowance` decimal(5,2) DEFAULT '0.00',
	  `temp3_mute` tinyint(1) NOT NULL,
	  `temp4_min` decimal(5,2) NOT NULL,
	  `temp4_max` decimal(5,2) NOT NULL,
	  `temp4_allowance` decimal(5,2) DEFAULT '0.00',
	  `temp4_mute` tinyint(1) NOT NULL,
	  `no_of_genset` int(11) DEFAULT NULL,
	  `genset1` int(11) DEFAULT NULL,
	  `genset2` int(11) DEFAULT NULL,
	  `transmitter1` int(11) DEFAULT NULL,
	  `transmitter2` int(11) DEFAULT NULL,
	  `puc_filename` varchar(100) NOT NULL,
	  `registration_filename` varchar(100) NOT NULL,
	  `insurance_filename` varchar(100) NOT NULL,
	  `speed_gov_filename` varchar(100) NOT NULL,
	  `fire_extinguisher_filename` varchar(100) NOT NULL,
	  `other_upload5` varchar(100) NOT NULL,
	  `other_upload6` varchar(100) NOT NULL,
	  `other_upload1` varchar(250) NOT NULL,
	  `other_upload2` varchar(250) NOT NULL,
	  `other_upload3` varchar(250) NOT NULL,
	  `other_upload4` varchar(250) NOT NULL,
	  `timestamp` datetime NOT NULL,
	  `stoppage_odometer` varchar(15) NOT NULL,
	  `stoppage_transit_time` datetime NOT NULL,
	  `nodata_alert` int(11) NOT NULL,
	  `stoppage_flag` tinyint(1) NOT NULL,
	  `submission_date` datetime NOT NULL,
	  `registration_date` datetime NOT NULL,
	  `approval_date` datetime NOT NULL,
	  `additional_amount` int(11) NOT NULL,
	  `description` varchar(40) NOT NULL,
	  `sms_count` int(11) NOT NULL,
	  `sms_lock` tinyint(1) NOT NULL DEFAULT '0',
	  `tel_count` int(11) NOT NULL,
	  `tel_lock` int(11) NOT NULL,
	  `fuel_balance` decimal(6,2) DEFAULT '0.00',
	  `old_fuel_balance` decimal(6,2) DEFAULT NULL,
	  `old_fuel_odometer` bigint(20) NOT NULL DEFAULT '0',
	  `routeDirection` tinyint(4) NOT NULL DEFAULT '0',
	  `fuelcapacity` int(4) NOT NULL,
	  `fuelMaxVoltCapacity` int(11) NOT NULL DEFAULT '0',
	  `max_voltage` int(8) DEFAULT NULL,
	  `fuel_min_volt` decimal(6,2) DEFAULT '0.00',
	  `fuel_max_volt` decimal(6,2) DEFAULT '0.00',
	  `notes` varchar(255) NOT NULL,
	  `rto_location` varchar(105) DEFAULT NULL,
	  `current_location` varchar(105) DEFAULT NULL,
	  `authorized_signatory` varchar(50) DEFAULT NULL,
	  `hypothecation` varchar(50) DEFAULT NULL,
	  `serial_number` varchar(55) DEFAULT NULL,
	  `expiry_date` date DEFAULT NULL,
	  `owner_name` varchar(105) DEFAULT NULL,
	  `invcustid` int(11) NOT NULL,
	  `sequenceno` int(11) NOT NULL DEFAULT '0',
	  `hum_min` int(11) DEFAULT '0',
	  `hum_max` int(11) DEFAULT '0',
	  `checkpointId` int(11) NOT NULL DEFAULT '0',
	  `chkpoint_status` tinyint(2) DEFAULT '0',
	  `checkpoint_timestamp` datetime DEFAULT NULL,
	  `ignition_wirecut` tinyint(1) NOT NULL DEFAULT '0',
	  `isVehicleResetCmdSent` tinyint(1) NOT NULL DEFAULT '0',
	  `subscription_cost` int(11) NOT NULL,
	  `subscription_period` int(11) NOT NULL,
	  `invoice_hold` tinyint(4) DEFAULT '0',
	  `vehicleType` int(20) NOT NULL DEFAULT '0',
	  `insertedBy` int(11) DEFAULT NULL,
	  `insertedOn` datetime DEFAULT NULL,
	  PRIMARY KEY (`vehicle_auditId`),
	  KEY `uid` (`uid`),
	  KEY `index_customerno` (`customerno`),
	  KEY `index_groupid` (`groupid`),
	  KEY `index_driverid` (`driverid`),
	  KEY `index_userid` (`userid`),
	  KEY `index_modelid` (`modelid`)
	);


	DELIMITER $$
	DROP TRIGGER IF EXISTS after_vehicle_insert $$
	CREATE TRIGGER `after_vehicle_insert` AFTER INSERT ON vehicle FOR EACH ROW BEGIN
	BEGIN
		/* Need to update the data for Prakash Enterprise in socketDB */
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
	            ,@istDateTime
				);
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
	            ,NEW.createdBy
	            ,NEW.createdOn
				);
	END;
	END $$
	DELIMITER ;



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
				(OLD.vehicleid
				,OLD.vehicleno
	            ,OLD.extbatt
				,OLD.odometer
				,OLD.lastupdated
				,OLD.curspeed
				,OLD.driverid
				,OLD.customerno
				,OLD.uid
				,OLD.isdeleted
				,OLD.kind
				,OLD.userid
				,OLD.groupid
				,OLD.branchid
				,OLD.overspeed_limit
				,OLD.average
				,OLD.modelid
				,OLD.manufacturing_month
				,OLD.manufacturing_year
				,OLD.purchase_date
				,OLD.tax_date
				,OLD.permit_date
				,OLD.fitness_date
				,OLD.start_meter_reading
				,OLD.fueltype
				,OLD.is_insured
				,OLD.status_id
				,OLD.temp1_min
				,OLD.temp1_max
				,OLD.temp1_mute
				,OLD.temp2_min
				,OLD.temp2_max
				,OLD.temp2_mute
				,OLD.temp3_min
				,OLD.temp3_max
				,OLD.temp3_mute
				,OLD.temp4_min
				,OLD.temp4_max
				,OLD.temp4_mute
				,OLD.no_of_genset
				,OLD.genset1
				,OLD.genset2
				,OLD.transmitter1
				,OLD.transmitter2
				,OLD.puc_filename
				,OLD.registration_filename
				,OLD.insurance_filename
				,OLD.speed_gov_filename
				,OLD.fire_extinguisher_filename
				,OLD.other_upload5
				,OLD.other_upload6
				,OLD.other_upload1
				,OLD.other_upload2
				,OLD.other_upload3
				,OLD.other_upload4
				,OLD.timestamp
				,OLD.stoppage_odometer
				,OLD.stoppage_transit_time
				,OLD.nodata_alert
				,OLD.stoppage_flag
				,OLD.submission_date
				,OLD.registration_date
				,OLD.approval_date
				,OLD.additional_amount
				,OLD.description
				,OLD.sms_count
				,OLD.sms_lock
				,OLD.tel_count
				,OLD.tel_lock
				,OLD.fuel_balance
				,OLD.fuelcapacity
				,OLD.fuelMaxVoltCapacity
				,OLD.max_voltage
				,OLD.fuel_min_volt
				,OLD.fuel_max_volt
				,OLD.notes
				,OLD.rto_location
				,OLD.current_location
				,OLD.authorized_signatory
				,OLD.hypothecation
				,OLD.serial_number
				,OLD.expiry_date
				,OLD.owner_name
				,OLD.invcustid
				,OLD.sequenceno
				,OLD.hum_min
				,OLD.hum_max
				,OLD.checkpointId
				,OLD.chkpoint_status
				,OLD.checkpoint_timestamp
				,OLD.ignition_wirecut
				,OLD.isVehicleResetCmdSent
	            ,OLD.updatedBy
	            ,OLD.updatedOn
				);
	END;
	END $$
	DELIMITER ;


	ALTER TABLE eventalerts
	    ADD COLUMN createdBy INT NULL,
	    ADD COLUMN createdOn datetime NULL,
	    ADD COLUMN updatedBy INT NULL,
	    ADD COLUMN updatedOn datetime NULL;

	DROP TABLE IF EXISTS `eventalerts_audit_trail`;
	CREATE TABLE `eventalerts_audit_trail` (
	    `eventAlert_auditId` INT AUTO_INCREMENT,
	    `vehicleid` INT(11) NOT NULL,
	    `overspeed` TINYINT(4) NOT NULL,
	    `tamper` TINYINT(4) NOT NULL,
	    `powercut` TINYINT(4) NOT NULL,
	    `temp` TINYINT(4) NOT NULL,
	    `temp2` TINYINT(1) NOT NULL,
	    `temp3` INT(4) DEFAULT NULL,
	    `temp4` INT(4) DEFAULT NULL,
	    `temp_sms` TINYINT(1) DEFAULT '0',
	    `temp2_sms` TINYINT(1) DEFAULT '0',
	    `temp3_sms` TINYINT(1) DEFAULT '0',
	    `temp4_sms` TINYINT(1) DEFAULT '0',
	    `temp_email` TINYINT(1) DEFAULT '0',
	    `temp2_email` TINYINT(1) DEFAULT '0',
	    `temp3_email` TINYINT(1) DEFAULT '0',
	    `temp4_email` TINYINT(1) DEFAULT '0',
	    `ac` TINYINT(4) NOT NULL,
	    `customerno` INT(11) NOT NULL,
	    `eaid` INT(11) NOT NULL,
	    `ac_count` INT(11) NOT NULL,
	    `ac_time` DATETIME NOT NULL,
	    `door` TINYINT(1) NOT NULL,
	    `door_time` DATETIME NOT NULL,
	    `insertedBy` INT(11) DEFAULT NULL,
	    `insertedOn` DATETIME DEFAULT NULL,
	    PRIMARY KEY (`eventAlert_auditId`),
	    KEY `vehicleid` (`vehicleid`)
	);

	DELIMITER $$
	DROP TRIGGER IF EXISTS after_eventAlert_insert $$
	CREATE TRIGGER `after_eventAlert_insert` AFTER INSERT ON eventalerts FOR EACH ROW BEGIN
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
		insertedBy = NEW.createdBy,
		insertedOn = NEW.createdOn;
	END;
	END $$
	DELIMITER ;


	DELIMITER $$
	DROP TRIGGER IF EXISTS before_eventAlert_update $$
	CREATE TRIGGER `before_eventAlert_update` BEFORE UPDATE ON eventalerts FOR EACH ROW BEGIN
	BEGIN
		INSERT INTO eventalerts_audit_trail
		SET
		vehicleid   = OLD.vehicleid,  
		overspeed  = OLD.overspeed,
		tamper   = OLD.tamper,  
		powercut   = OLD.powercut,  
		temp  = OLD.temp,
		temp2 = OLD.temp2, 
		temp3 = OLD.temp3,
		temp4  = OLD.temp4, 
		temp_sms = OLD.temp_sms, 
		temp2_sms  = OLD.temp2_sms, 
		temp3_sms  = OLD.temp3_sms, 
		temp4_sms  = OLD.temp4_sms,
		temp_email = OLD.temp_email,
		temp2_email  = OLD.temp2_email, 
		temp3_email  = OLD.temp3_email, 
		temp4_email  = OLD.temp4_email, 
		ac   = OLD.ac, 
		customerno   = OLD.customerno,  
		eaid   = OLD.eaid,
		ac_count  = OLD.ac_count,  
		ac_time   = OLD.ac_time,
		door  = OLD.door, 
		door_time  = OLD.door_time,
		insertedBy = OLD.updatedBy,
		insertedOn = OLD.updatedOn;
	END;
	END $$
	DELIMITER ;

	ALTER TABLE ignitionalert
		ADD COLUMN createdBy INT NULL NULL,
		ADD COLUMN createdOn datetime NULL,
		ADD COLUMN updatedBy INT NULL,
		ADD COLUMN updatedOn datetime NULL;

	DROP TABLE IF EXISTS `ignitionalert_audit_trail`;
	CREATE TABLE `ignitionalert_audit_trail` (
	  `ignitionAlert_auditId` INT AUTO_INCREMENT,
	  `vehicleid` int(11) NOT NULL,
	  `last_status` tinyint(4) NOT NULL,
	  `last_check` datetime NOT NULL,
	  `count` tinyint(4) NOT NULL,
	  `idleignon_count` tinyint(4) NOT NULL DEFAULT '0',
	  `running_count` tinyint(4) NOT NULL DEFAULT '0',
	  `status` tinyint(4) NOT NULL,
	  `customerno` int(11) NOT NULL,
	  `igalertid` int(11) NOT NULL ,
	  `ignchgtime` datetime NOT NULL,
	  `ignontime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `prev_odometer_reading` int(11) NOT NULL,
	  `prev_odometer_time` datetime NOT NULL,
	  `insertedBy` INT(11) DEFAULT NULL,
	  `insertedOn` DATETIME DEFAULT NULL,
	  PRIMARY KEY (`ignitionAlert_auditId`),
	  KEY `vehicleid` (`vehicleid`)
	);


	DROP TABLE IF EXISTS `ignitionalert_audit_trail`;
	CREATE TABLE `ignitionalert_audit_trail` (
	  `ignitionAlert_auditId` INT AUTO_INCREMENT,
	  `vehicleid` int(11) NOT NULL,
	  `last_status` tinyint(4) NOT NULL,
	  `last_check` datetime NOT NULL,
	  `count` tinyint(4) NOT NULL,
	  `idleignon_count` tinyint(4) NOT NULL DEFAULT '0',
	  `running_count` tinyint(4) NOT NULL DEFAULT '0',
	  `status` tinyint(4) NOT NULL,
	  `customerno` int(11) NOT NULL,
	  `igalertid` int(11) NOT NULL ,
	  `ignchgtime` datetime NOT NULL,
	  `ignontime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `prev_odometer_reading` int(11) NOT NULL,
	  `prev_odometer_time` datetime NOT NULL,
	  `insertedBy` INT(11) DEFAULT NULL,
	  `insertedOn` DATETIME DEFAULT NULL,
	  PRIMARY KEY (`ignitionAlert_auditId`),
	  KEY `vehicleid` (`vehicleid`)
	);


	DELIMITER $$
	DROP TRIGGER IF EXISTS after_ignitionalert_insert $$
	CREATE TRIGGER `after_ignitionalert_insert` AFTER INSERT ON ignitionalert FOR EACH ROW BEGIN
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
		insertedBy = NEW.createdBy,
		insertedOn = NEW.createdOn;
	END;
	END $$
	DELIMITER ;



	DELIMITER $$
	DROP TRIGGER IF EXISTS before_ignitionalert_update $$
	CREATE TRIGGER `before_ignitionalert_update` BEFORE UPDATE ON ignitionalert FOR EACH ROW BEGIN
	BEGIN
		INSERT INTO ignitionalert_audit_trail
		SET
		vehicleid   = OLD.vehicleid,  
		last_status  = OLD.last_status,
		last_check   = OLD.last_check,  
		count   = OLD.count,  
		idleignon_count  = OLD.idleignon_count,
		running_count = OLD.running_count, 
		`status` = OLD.`status`,
		customerno  = OLD.customerno, 
		igalertid = OLD.igalertid, 
		ignchgtime  = OLD.ignchgtime, 
		ignontime  = OLD.ignontime, 
		prev_odometer_reading  = OLD.prev_odometer_reading,
		prev_odometer_time = OLD.prev_odometer_time,
		insertedBy = OLD.updatedBy,
		insertedOn = OLD.updatedOn;
	END;
	END $$
	DELIMITER ;

	ALTER TABLE batch
	    ADD COLUMN createdBy INT NULL,
	    ADD COLUMN updatedBy INT NULL;


	DROP TABLE IF EXISTS `batch_audit_trail`;
	CREATE TABLE `batch_audit_trail` (
	  `batch_auditId` INT AUTO_INCREMENT,	
	  `batchid` int(11) NOT NULL ,
	  `vehicleid` varchar(15) NOT NULL,
	  `customerno` varchar(11) NOT NULL,
	  `batchno` varchar(20) NOT NULL,
	  `workkey` varchar(10) NOT NULL,
	  `starttime` varchar(35) NOT NULL,
	  `dummybatch` varchar(15) NOT NULL,
	  `pmid` int(11) NOT NULL,
	  `addedon` datetime NOT NULL,
	  `updatedon` datetime NOT NULL,
	  `isdeleted` tinyint(1) NOT NULL,
	  `last_fid` int(11) DEFAULT NULL,
	  `createdBy` int(11) DEFAULT NULL,
	  `updatedBy` int(11) DEFAULT NULL,
	  PRIMARY KEY (`batch_auditId`)
	);

	DELIMITER $$
	DROP TRIGGER IF EXISTS after_batch_insert $$
	CREATE TRIGGER `after_batch_insert` AFTER INSERT ON batch FOR EACH ROW BEGIN
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
		insertedBy = NEW.createdBy,
		insertedOn = NEW.addedon;
	END;
	END $$
	DELIMITER ;


	DELIMITER $$
	DROP TRIGGER IF EXISTS before_batch_update $$
	CREATE TRIGGER `before_batch_update` BEFORE UPDATE ON batch FOR EACH ROW BEGIN
	BEGIN
		INSERT INTO batch_audit_trail
		SET
		batchid   = OLD.batchid,  
		vehicleid  = OLD.vehicleid,
		customerno   = OLD.customerno,  
		batchno   = OLD.batchno,  
		workkey  = OLD.workkey,
		starttime = OLD.starttime, 
		dummybatch = OLD.dummybatch,
		pmid  = OLD.pmid, 
		isdeleted = OLD.isdeleted, 
		last_fid  = OLD.last_fid, 
		insertedBy = OLD.updatedBy,
		insertedOn = OLD.updatedon;
	END;
	END $$
	DELIMITER ;



	ALTER TABLE checkpointmanage
	    ADD COLUMN createdBy INT NULL,
	    ADD COLUMN createdOn datetime NULL,
	    ADD COLUMN updatedBy INT NULL,
	    ADD COLUMN updatedOn datetime NULL;


	DROP TABLE IF EXISTS `checkpointmanage_audit_trail`;
	CREATE TABLE `checkpointmanage_audit_trail` (
	  `cm_auditId` INT AUTO_INCREMENT,
	  `cmid` int(11) NOT NULL ,
	  `checkpointid` int(11) NOT NULL,
	  `vehicleid` int(11) NOT NULL,
	  `customerno` int(11) NOT NULL,
	  `conflictstatus` tinyint(1) NOT NULL DEFAULT '1',
	  `inTime` datetime DEFAULT NULL,
	  `outTime` datetime DEFAULT NULL,
	  `isDelayExpected` tinyint(1) NOT NULL DEFAULT '0',
	  `userid` int(11) NOT NULL,
	  `isdeleted` tinyint(1) NOT NULL,
	  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  `insertedBy` int(11) DEFAULT NULL,
	  `insertedOn` datetime DEFAULT NULL,
	  PRIMARY KEY (`cm_auditId`),
	  KEY `checkpointid` (`checkpointid`),
	  KEY `vehicleid` (`vehicleid`)
	);


	DELIMITER $$
	DROP TRIGGER IF EXISTS after_checkpointmanage_insert $$
	CREATE TRIGGER `after_checkpointmanage_insert` AFTER INSERT ON checkpointmanage FOR EACH ROW BEGIN
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
		insertedBy = NEW.createdBy,
		insertedOn = NEW.createdOn;
	END;
	END $$
	DELIMITER ;


	DELIMITER $$
	DROP TRIGGER IF EXISTS before_checkpointmanage_update $$
	CREATE TRIGGER `before_checkpointmanage_update` BEFORE UPDATE ON checkpointmanage FOR EACH ROW BEGIN
	BEGIN
		INSERT INTO checkpointmanage_audit_trail
		SET
		cmid   = OLD.cmid,  
		checkpointid  = OLD.checkpointid,
		vehicleid   = OLD.vehicleid,  
		customerno   = OLD.customerno,  
		conflictstatus  = OLD.conflictstatus,
		inTime = OLD.inTime, 
		outTime = OLD.outTime,
		isDelayExpected  = OLD.isDelayExpected, 
		userid = OLD.userid, 
		isdeleted  = OLD.isdeleted, 
	    timestamp  = OLD.timestamp, 
		insertedBy = OLD.updatedBy,
		insertedOn = OLD.updatedOn;
	END;
	END $$
	DELIMITER ;


	ALTER TABLE fenceman
	    ADD COLUMN createdBy INT NULL,
	    ADD COLUMN createdOn datetime NULL,
	    ADD COLUMN updatedBy INT NULL,
	    ADD COLUMN updatedOn datetime NULL;

	DROP TABLE IF EXISTS `fenceman_audit_trail`;
	CREATE TABLE `fenceman_audit_trail` (
		  `fm_auditId` INT AUTO_INCREMENT,
		  `fmid` int(11) NOT NULL ,
		  `fenceid` int(11) NOT NULL,
		  `vehicleid` int(11) NOT NULL,
		  `customerno` int(11) NOT NULL,
		  `conflictstatus` tinyint(1) NOT NULL DEFAULT '0',
		  `userid` int(11) NOT NULL,
		  `isdeleted` tinyint(1) NOT NULL,
		  `insertedBy` int(11) DEFAULT NULL,
		  `insertedOn` datetime DEFAULT NULL,
		  PRIMARY KEY (`fm_auditId`),
		  KEY `fenceid` (`fenceid`)
		);


	DELIMITER $$
	DROP TRIGGER IF EXISTS after_fenceman_insert $$
	CREATE TRIGGER `after_fenceman_insert` AFTER INSERT ON fenceman FOR EACH ROW BEGIN
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
		insertedBy = NEW.createdBy,
		insertedOn = NEW.createdOn;
	END;
	END $$
	DELIMITER ;



	DELIMITER $$
	DROP TRIGGER IF EXISTS before_fenceman_update $$
	CREATE TRIGGER `before_fenceman_update` BEFORE UPDATE ON fenceman FOR EACH ROW BEGIN
	BEGIN
		INSERT INTO fenceman_audit_trail
		SET
		fmid   = OLD.fmid,  
		fenceid  = OLD.fenceid,
		vehicleid   = OLD.vehicleid,  
		customerno   = OLD.customerno,  
		conflictstatus  = OLD.conflictstatus,
		userid = OLD.userid, 
		isdeleted  = OLD.isdeleted, 
		insertedBy = OLD.updatedBy,
		insertedOn = OLD.updatedOn;
	END;
	END $$
	DELIMITER ;




DELIMITER $$
DROP procedure IF EXISTS `fetch_vehicle_logs`$$
CREATE  PROCEDURE `fetch_vehicle_logs`(
	IN customernoParam INT,
	IN vehicleIdParam INT,
	IN startdateParam date,
	IN enddateParam date,
	IN limitParam INT
	)
	BEGIN

	 DECLARE limitCondition VARCHAR(10);
		
	    SET limitCondition = CONCAT(' LIMIT ', limitParam);
	    


			SET @STMT = CONCAT("SELECT vat.*,g.groupname,u.realname FROM vehicle_audit_trail vat LEFT JOIN `group` g on g.groupid = vat.groupid INNER JOIN user u on u.userid = vat.insertedBy WHERE vat.customerno =", customernoParam, " AND vat.vehicleid =", vehicleIdParam," AND date(vat.insertedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY insertedOn desc ",limitCondition);
	        PREPARE S FROM @STMT;
			EXECUTE S;
			DEALLOCATE PREPARE S; 
				
	    END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_checkpoint_logs`$$
CREATE  PROCEDURE `fetch_checkpoint_logs`(
	IN customernoParam INT,
	IN vehicleIdParam INT,
	IN startdateParam date,
	IN enddateParam date,
	IN limitParam INT
	)
	BEGIN

 	DECLARE limitCondition VARCHAR(10);
	
    SET limitCondition = CONCAT(' LIMIT ', limitParam);
    


		SET @STMT = CONCAT("SELECT cat.*,c.cname,u.realname,v.vehicleno FROM checkpointmanage_audit_trail cat LEFT JOIN `checkpoint` c on c.checkpointid = cat.checkpointid INNER JOIN user u on u.userid = cat.insertedBy INNER JOIN vehicle v on v.vehicleid = cat.vehicleid WHERE cat.customerno =", customernoParam, " AND cat.vehicleid =", vehicleIdParam," AND date(cat.insertedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY insertedOn desc ",limitCondition);
        PREPARE S FROM @STMT;
		EXECUTE S;
		DEALLOCATE PREPARE S; 
			
    END$$
DELIMITER ;



DELIMITER $$
DROP procedure IF EXISTS `fetch_fence_logs`$$
CREATE  PROCEDURE `fetch_fence_logs`(
	IN customernoParam INT,
	IN vehicleIdParam INT,
	IN startdateParam date,
	IN enddateParam date,
	IN limitParam INT
	)
	BEGIN

 	DECLARE limitCondition VARCHAR(10);
	
    SET limitCondition = CONCAT(' LIMIT ', limitParam);
    


		SET @STMT = CONCAT("SELECT fat.*,f.fencename,u.realname,v.vehicleno FROM fenceman_audit_trail fat LEFT JOIN `fence` f on f.fenceid = fat.fenceid INNER JOIN vehicle v on v.vehicleid = fat.vehicleid INNER JOIN user u on u.userid = fat.insertedBy WHERE fat.customerno =", customernoParam, " AND fat.vehicleid =", vehicleIdParam," AND date(fat.insertedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY insertedOn desc ",limitCondition);
        PREPARE S FROM @STMT;
		EXECUTE S;
		DEALLOCATE PREPARE S; 
			
    END$$
DELIMITER ;



UPDATE  dbpatches
SET     patchdate = '2018-03-06 17:50:00'
        ,isapplied =1
WHERE   patchid = 678;