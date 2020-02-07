INSERT INTO `speed`.`dbpatches` (`patchid`, `patchdate`, `appliedby`, `patchdesc`, `isapplied`) 
VALUES ('532', '2017-10-12 14:34:11', 'Mrudang Vora', 'Rivigo Push API', '0');
/*
	Name			-	api_update_device_details
	Description 	-
	Parameters		-
	Module			-	Speed
	Sub-Modules 	- 	API
	Sample Call		-	CALL api_update_device_details('HR55Y5879','D523004','13.9044466','79.37212','0','0','','1','0','0','51.0','24.0','','','','0','','523','1506423467000');
	Created by		-	Mrudang Vora
	Created on		- 	11 Oct, 2017
	Change details 	-
	1) 	Updated by	-
		Updated	on	-
		Reason		-
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `api_update_device_details`$$
CREATE PROCEDURE `api_update_device_details`(
	 IN vehicleNoParam VARCHAR(40)
	, IN unitNoParam VARCHAR(16)
	, IN devicelatParam DECIMAL(9,6)
	, IN devicelngParam DECIMAL(9,6)
	, IN altitudeParam INT UNSIGNED
	, IN dirChangeParam INT UNSIGNED
	, IN inbattParam INT UNSIGNED
	, IN ignitionParam TINYINT(1) UNSIGNED
	, IN gsmStrengthParam TINYINT UNSIGNED
	, IN odometerParam BIGINT
	, IN curspeedParam TINYINT UNSIGNED
	, IN analog1Param INT
	, IN analog2Param INT
	, IN analog3Param INT
	, IN analog4Param INT
	, IN digitalioParam SMALLINT
	, IN driverNameParam VARCHAR(50)
	, IN custnoParam INT
	, IN lastUpdatedParam DATETIME
)
BEGIN
	DECLARE isUpdated TINYINT;
    DECLARE varUid INT;
    DECLARE varVehicleId INT;
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
		/*
		GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
		@errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
		SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
		SELECT @full_error;
		*/
		SET isUpdated = 0;
	END;
	IF unitNoParam != '' THEN
		SET unitNoParam = REPLACE(unitNoParam, ' ','');
	    SELECT 	uid, vehicleid
	    INTO 	varUid, varVehicleId
	    FROM 	unit
	    WHERE 	REPLACE(unitno, ' ','') = unitNoParam
	    AND 	customerno = custnoParam
	    LIMIT 	1;
	ELSEIF unitNoParam = '' AND vehicleNoParam != '' THEN
		SET vehicleNoParam = REPLACE(vehicleNoParam, ' ','');
	    SELECT 	uid, vehicleid
	    INTO 	varUid, varVehicleId
	    FROM 	vehicle
	    WHERE 	REPLACE(vehicleno, ' ','') = vehicleNoParam
	    AND 	customerno = custnoParam
	    AND 	isdeleted = 0
	    LIMIT 	1;
	END IF;

	START TRANSACTION;
		UPDATE	devices
		SET 	devicelat = devicelatParam
				,devicelong = devicelngParam
				,altitude = altitudeParam
				,directionchange = dirChangeParam
                ,inbatt = inbattParam
                ,ignition = ignitionParam
                ,gsmstrength = gsmStrengthParam
				,powercut = 1
				,tamper = 0
				,`online/offline`= 0
				, lastupdated = lastUpdatedParam
		WHERE	uid = varUid;

		UPDATE 	vehicle
		SET 	lastupdated = lastUpdatedParam
				,nodata_alert = 0
				,odometer = odometerParam
				,curspeed = curspeedParam
		WHERE 	uid = varUid;

		UPDATE 	unit
		SET 	analog1 = analog1Param
				,analog2 = analog2Param
				,analog3 = analog3Param
				,analog4 = analog4Param
				,digitalio = digitalioParam
		WHERE 	uid = varUid;

		UPDATE 	driver
		SET 	driverName = driverNameParam
		WHERE 	vehicleid = varVehicleId;

		SET isUpdated = 1;
	COMMIT;

    SELECT 		u.uid, u.unitno, v.vehicleid, d.deviceid, dr.driverid, isUpdated
    FROM		unit u
    INNER JOIN	devices d on d.uid = u.uid
    INNER JOIN	vehicle v on v.uid = u.uid
    INNER JOIN	driver dr on dr.vehicleid = v.vehicleid
    WHERE		u.uid = varUid;

END$$
DELIMITER ;

UPDATE speed.dbpatches SET isapplied=1 WHERE patchid = 532;