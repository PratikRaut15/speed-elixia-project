/*
    Name			-	insert_duplicate_unit
    Description 	-	Inserts duplicate unit data in all the required tables.
						NEEDS TO BE USED WITH CARE
    Parameters		-	unitnoParam
						, oldCustnoParam - Customer number in which this unit no is already present
						, newCustnoParam - Customer number in which we need to add this unit no
    Module			-	Team
    Sub-Modules 	- 	
    Sample Call		-	CALL insert_duplicate_unit('904414', 250, 212, @isDuplicateUnitAdded);
						SELECT @isDuplicateUnitAdded;
    Created by		-	Mrudang Vora
    Created on		- 	20 July, 2016
    Change details 	-	
    1) 	Updated by	- 	Mrudang
		Updated	on	- 	19 July 2018
		Reason		-	Updated unitno to VARCHAR(16)
	2)  Updated by	- 	
		Updated	on	- 	
		Reason		-	
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_duplicate_unit`$$
CREATE PROCEDURE `insert_duplicate_unit` (
	IN unitnoParam VARCHAR(16)
    , IN oldCustnoParam INT
	, IN newCustnoParam INT
    , OUT isDuplicateUnitAdded  INT
)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
        SET isDuplicateUnitAdded = 0;
	END;
	START TRANSACTION;
		/* UNIT TABLE OPERATIONS */
		SET @oldUid = (SELECT uid FROM unit WHERE unitno = unitnoParam AND customerno = oldCustnoParam LIMIT 1);

		INSERT INTO `unit` (`unitno`, `repairtat`, `customerno`, `vehicleid`, `analog1`, `analog2`, `analog3`, `analog4`, `digitalio`, `extra_digital`, `digitalioupdated`, `door_digitalioupdated`, `extra_digitalioupdated`, `extra2_digitalioupdated`, `command`, `setcom`, `commandkey`, `commandkeyval`, `acsensor`, `is_ac_opp`, `gensetsensor`, `is_genset_opp`, `transmitterno`, `doorsensor`, `is_door_opp`, `fuelsensor`, `tempsen1`, `tempsen2`, `tempsen3`, `tempsen4`, `n1`, `n2`, `n3`, `n4`, `humidity`, `is_panic`, `is_buzzer`, `is_mobiliser`, `is_twowaycom`, `is_portable`, `mobiliser_flag`, `is_freeze`, `unitprice`, `userid`, `msgkey`, `trans_statusid`, `type_value`, `temp1_intv`, `temp2_intv`, `temp3_intv`, `temp4_intv`, `teamid`, `remark`, `alterremark`, `issue_type`, `comments`, `comments_repair`, `issue`, `onlease`, `isRequiredThirdParty`) 
		SELECT `unitno`, `repairtat`, `customerno`, `vehicleid`, `analog1`, `analog2`, `analog3`, `analog4`, `digitalio`, `extra_digital`, `digitalioupdated`, `door_digitalioupdated`, `extra_digitalioupdated`, `extra2_digitalioupdated`, `command`, `setcom`, `commandkey`, `commandkeyval`, `acsensor`, `is_ac_opp`, `gensetsensor`, `is_genset_opp`, `transmitterno`, `doorsensor`, `is_door_opp`, `fuelsensor`, `tempsen1`, `tempsen2`, `tempsen3`, `tempsen4`, `n1`, `n2`, `n3`, `n4`, `humidity`, `is_panic`, `is_buzzer`, `is_mobiliser`, `is_twowaycom`, `is_portable`, `mobiliser_flag`, `is_freeze`, `unitprice`, `userid`, `msgkey`, `trans_statusid`, `type_value`, `temp1_intv`, `temp2_intv`, `temp3_intv`, `temp4_intv`, `teamid`, `remark`, `alterremark`, `issue_type`, `comments`, `comments_repair`, `issue`, `onlease`, `isRequiredThirdParty`
		FROM 	unit
		WHERE 	uid = @oldUid;

		SET @newUid = LAST_INSERT_ID();

		UPDATE unit		SET customerno = newCustnoParam			WHERE uid = @newUid;
		UPDATE unit		SET vehicleid = 0 						WHERE uid = @newUid;

		/* VEHICLE TABLE OPERATIONS */
		INSERT INTO `vehicle` (`vehicleno`, `extbatt`, `odometer`, `lastupdated`, `curspeed`, `driverid`, `customerno`, `uid`, `isdeleted`, `kind`, `userid`, `groupid`, `branchid`, `overspeed_limit`, `average`, `modelid`, `manufacturing_month`, `manufacturing_year`, `purchase_date`, `start_meter_reading`, `fueltype`, `is_insured`, `status_id`, `temp1_min`, `temp1_max`, `temp1_mute`, `temp2_min`, `temp2_max`, `temp2_mute`, `temp3_min`, `temp3_max`, `temp3_mute`, `temp4_min`, `temp4_max`, `temp4_mute`, `no_of_genset`, `genset1`, `genset2`, `transmitter1`, `transmitter2`, `other_upload1`, `other_upload2`, `other_upload3`, `other_upload4`, `timestamp`, `stoppage_odometer`, `stoppage_transit_time`, `nodata_alert`, `stoppage_flag`, `submission_date`, `registration_date`, `approval_date`, `additional_amount`, `description`, `sms_count`, `sms_lock`, `tel_count`, `tel_lock`, `fuel_balance`, `fuelcapacity`, `max_voltage`, `fuel_min_volt`, `fuel_max_volt`, `notes`, `rto_location`, `serial_number`, `expiry_date`, `owner_name`, `invcustid`, `sequenceno`) 
		SELECT	`vehicleno`, `extbatt`, `odometer`, `lastupdated`, `curspeed`, `driverid`, `customerno`, `uid`, `isdeleted`, `kind`, `userid`, `groupid`, `branchid`, `overspeed_limit`, `average`, `modelid`, `manufacturing_month`, `manufacturing_year`, `purchase_date`, `start_meter_reading`, `fueltype`, `is_insured`, `status_id`, `temp1_min`, `temp1_max`, `temp1_mute`, `temp2_min`, `temp2_max`, `temp2_mute`, `temp3_min`, `temp3_max`, `temp3_mute`, `temp4_min`, `temp4_max`, `temp4_mute`, `no_of_genset`, `genset1`, `genset2`, `transmitter1`, `transmitter2`, `other_upload1`, `other_upload2`, `other_upload3`, `other_upload4`, `timestamp`, `stoppage_odometer`, `stoppage_transit_time`, `nodata_alert`, `stoppage_flag`, `submission_date`, `registration_date`, `approval_date`, `additional_amount`, `description`, `sms_count`, `sms_lock`, `tel_count`, `tel_lock`, `fuel_balance`, `fuelcapacity`, `max_voltage`, `fuel_min_volt`, `fuel_max_volt`, `notes`, `rto_location`, `serial_number`, `expiry_date`, `owner_name`, `invcustid`, `sequenceno`
		FROM 	vehicle
		WHERE 	uid = @oldUid
		AND 	isdeleted = 0;

		SET @newVehid = LAST_INSERT_ID();

		UPDATE vehicle 		SET customerno = newCustnoParam 	WHERE vehicleid = @newVehid;
		UPDATE vehicle 		SET uid = @newUid 					WHERE vehicleid = @newVehid;
		UPDATE vehicle 		SET driverid = 0					WHERE vehicleid = @newVehid;

		UPDATE unit			SET vehicleid = @newVehid 			WHERE uid = @newUid;

		/* DRIVER TABLE OPERATIONS */
		SET @oldVehid = (SELECT vehicleid FROM vehicle WHERE uid = @oldUid);
		INSERT INTO `driver` (`drivername`, `driverlicno`, `driverphone`, `customerno`, `vehicleid`, `isdeleted`, `userid`, `birthdate`, `age`, `bloodgroup`, `licence_validity`, `licence_issue_auth`, `local_address`, `local_contact`, `local_contact_mob`, `emergency_contact1`, `emergency_contact2`, `emergency_contact_no1`, `emergency_contact_no2`, `native_address`, `native_contact`, `native_contact_mob`, `native_emergency_contact1`, `native_emergency_contact2`, `native_emergency_contact_no1`, `native_emergency_contact_no2`, `previous_employer`, `service_period`, `service_contact_person`, `service_contact_no`, `upload`, `username`, `password`, `userkey`, `trip_email`, `trip_phone`, `istripstarted`) 
		SELECT	`drivername`, `driverlicno`, `driverphone`, `customerno`, `vehicleid`, `isdeleted`, `userid`, `birthdate`, `age`, `bloodgroup`, `licence_validity`, `licence_issue_auth`, `local_address`, `local_contact`, `local_contact_mob`, `emergency_contact1`, `emergency_contact2`, `emergency_contact_no1`, `emergency_contact_no2`, `native_address`, `native_contact`, `native_contact_mob`, `native_emergency_contact1`, `native_emergency_contact2`, `native_emergency_contact_no1`, `native_emergency_contact_no2`, `previous_employer`, `service_period`, `service_contact_person`, `service_contact_no`, `upload`, `username`, `password`, `userkey`, `trip_email`, `trip_phone`, `istripstarted`
		FROM 	driver
		WHERE 	vehicleid = @oldVehid
		AND		isdeleted = 0;

		SET @newDriverId = LAST_INSERT_ID();

		UPDATE driver 		SET customerno = newCustnoParam 	WHERE driverid = @newDriverId;
		UPDATE driver 		SET vehicleid = @newVehid 			WHERE driverid = @newDriverId;
		UPDATE vehicle 		SET driverid = @newDriverId			WHERE vehicleid = @newVehid;

		/* IGNITION ALERT TABLE OPERATIONS */
		INSERT INTO `ignitionalert` (`vehicleid`, `last_status`, `last_check`, `count`, `idleignon_count`, `running_count`, `status`, `customerno`, `ignchgtime`, `ignontime`, `prev_odometer_reading`, `prev_odometer_time`) 
		SELECT `vehicleid`, `last_status`, `last_check`, `count`, `idleignon_count`, `running_count`, `status`, `customerno`, `ignchgtime`, `ignontime`, `prev_odometer_reading`, `prev_odometer_time`
		FROM	ignitionalert
		WHERE 	vehicleid = @oldVehid;

		SET @newIgalertId = LAST_INSERT_ID();

		UPDATE ignitionalert 	SET customerno = newCustnoParam WHERE igalertid = @newIgalertId;
		UPDATE ignitionalert	SET vehicleid = @newVehid 		WHERE igalertid = @newIgalertId;

		/* EVENT ALERT TABLE OPERATIONS */
		INSERT INTO `eventalerts` (`vehicleid`, `overspeed`, `tamper`, `powercut`, `temp`, `temp2`, `temp3`, `temp4`, `ac`, `customerno`, `ac_count`, `ac_time`, `door`, `door_time`) 
		SELECT `vehicleid`, `overspeed`, `tamper`, `powercut`, `temp`, `temp2`, `temp3`, `temp4`, `ac`, `customerno`, `ac_count`, `ac_time`, `door`, `door_time`
		FROM	eventalerts
		WHERE 	vehicleid = @oldVehid;

		SET @newEaId = LAST_INSERT_ID();

		UPDATE eventalerts 		SET customerno = newCustnoParam WHERE eaid = @newEaId;
		UPDATE eventalerts 		SET vehicleid = @newVehid 		WHERE eaid = @newEaId;

		/* DEVICES TABLE OPERATIONS */
		INSERT INTO `devices` (`customerno`, `devicekey`, `devicelat`, `devicelong`, `baselat`, `baselng`, `installlat`, `installlng`, `lastupdated`, `registeredon`, `altitude`, `directionchange`, `inbatt`, `hwv`, `swv`, `msgid`, `uid`, `status`, `ignition`, `powercut`, `tamper`, `gpsfixed`, `online/offline`, `gsmstrength`, `gsmregister`, `gprsregister`, `aci_status`, `satv`, `device_invoiceno`, `inv_generatedate`, `installdate`, `expirydate`, `invoiceno`, `po_no`, `po_date`, `warrantyexpiry`, `simcardid`, `inv_device_priority`, `inv_deferdate`) 
		SELECT 	`customerno`, `devicekey`, `devicelat`, `devicelong`, `baselat`, `baselng`, `installlat`, `installlng`, `lastupdated`, `registeredon`, `altitude`, `directionchange`, `inbatt`, `hwv`, `swv`, `msgid`, `uid`, `status`, `ignition`, `powercut`, `tamper`, `gpsfixed`, `online/offline`, `gsmstrength`, `gsmregister`, `gprsregister`, `aci_status`, `satv`, `device_invoiceno`, `inv_generatedate`, `installdate`, `expirydate`, `invoiceno`, `po_no`, `po_date`, `warrantyexpiry`, `simcardid`, `inv_device_priority`, `inv_deferdate`
		FROM	devices
		WHERE 	uid = @oldUid;

		SET @newDeviceId = LAST_INSERT_ID();

		UPDATE devices 			SET customerno = newCustnoParam WHERE deviceid = @newDeviceId;
		UPDATE devices 			SET uid = @newUid 				WHERE deviceid = @newDeviceId;
		UPDATE devices			SET	simcardid = 0				WHERE deviceid = @newDeviceId;

		/* SIMCARD TABLE OPERATIONS */
		SET @oldsimcardId = (SELECT simcardid FROM devices WHERE uid = @oldUid);

		INSERT INTO `simcard` (`simcardno`, `vendorid`, `trans_statusid`, `customerno`, `teamid`, `comments`) 
		SELECT 	`simcardno`, `vendorid`, `trans_statusid`, `customerno`, `teamid`, `comments`
		FROM	simcard
		WHERE	id = @oldsimcardId;

		SET @newSimcardId = LAST_INSERT_ID();

		UPDATE simcard 			SET customerno = newCustnoParam WHERE id = @newSimcardId;
		UPDATE devices			SET	simcardid = @newSimcardId	WHERE deviceid = @newDeviceId;

		/* DAILY REPORT OPERATIONS */
		INSERT INTO `dailyreport` (`customerno`, `vehicleid`, `uid`, `harsh_break`, `sudden_acc`, `towing`, `flag_harsh_break`, `flag_sudden_acc`, `flag_towing`, `first_odometer`, `last_odometer`, `max_odometer`, `average_distance`, `total_tracking_days`, `overspeed`, `topspeed`, `topspeed_lat`, `topspeed_long`, `fenceconflict`, `acusage`, `runningtime`, `idleignitiontime`, `first_lat`, `first_long`, `end_lat`, `end_long`, `last_online_updated`, `offline_data_time`, `topspeed_time`, `night_first_odometer`, `weekend_first_odometer`, `daily_date`) 
		(SELECT 	`customerno`, `vehicleid`, `uid`, `harsh_break`, `sudden_acc`, `towing`, `flag_harsh_break`, `flag_sudden_acc`, `flag_towing`, `first_odometer`, `last_odometer`, `max_odometer`, `average_distance`, `total_tracking_days`, `overspeed`, `topspeed`, `topspeed_lat`, `topspeed_long`, `fenceconflict`, `acusage`, `runningtime`, `idleignitiontime`, `first_lat`, `first_long`, `end_lat`, `end_long`, `last_online_updated`, `offline_data_time`, `topspeed_time`, `night_first_odometer`, `weekend_first_odometer`, `daily_date`
		FROM	dailyreport
		WHERE	uid = @oldUid
		ORDER BY daily_date DESC
		LIMIT 1);


		SET @newDailyreportId = LAST_INSERT_ID();

		UPDATE dailyreport		SET customerno = newCustnoParam WHERE dailyreport_id = @newDailyreportId;
		UPDATE dailyreport		SET	uid = @newUid				WHERE dailyreport_id = @newDailyreportId;
		UPDATE dailyreport		SET	vehicleid = @newVehid		WHERE dailyreport_id = @newDailyreportId;
		
        SET isDuplicateUnitAdded = 1;
    COMMIT;

END$$
DELIMITER ;