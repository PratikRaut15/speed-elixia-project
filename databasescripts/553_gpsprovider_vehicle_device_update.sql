INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 553, '2016-04-11 10:58:01', 'Sanjeet Shukla','GPS provider cron - vehicle, unit and device updated ');


DELIMITER $$
DROP PROCEDURE IF EXISTS `gpsprovider_update_device_details`$$
CREATE PROCEDURE `gpsprovider_update_device_details`(
	 IN vehicleNoParam VARCHAR(40)
	, IN devicelatParam DECIMAL(9,6)
	, IN devicelngParam DECIMAL(9,6)
	, IN altitudeParam INT UNSIGNED
	, IN dirChangeParam INT UNSIGNED
	, IN ignitionParam TINYINT(1) UNSIGNED
	, IN odometerParam BIGINT
	, IN curspeedParam TINYINT UNSIGNED
	, IN analog1Param INT
	, IN digitalioParam SMALLINT
    , IN stoppageTransitTimeParam DATETIME
	, IN custnoParam INT
	, IN lastUpdatedParam DATETIME
)
BEGIN
	DECLARE isUpdated TINYINT;
    DECLARE varUid INT;
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
    
    SET vehicleNoParam = REPLACE(vehicleNoParam, ' ','');
    
    SELECT 	uid INTO varUid
    FROM 	vehicle
    WHERE 	REPLACE(vehicleno, ' ','') = vehicleNoParam
    AND 	customerno = custnoParam 
    AND 	isdeleted = 0
    LIMIT 	1;
    
	START TRANSACTION;
		UPDATE	devices
		SET 	devicelat = devicelatParam
				,devicelong = devicelngParam
				,altitude = altitudeParam
				,directionchange = dirChangeParam
                ,ignition = ignitionParam
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
				,digitalio = digitalioParam
		WHERE 	uid = varUid;

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


UPDATE 	dbpatches SET 	patchdate = '2018-04-11 16:45:01' , isapplied =1 WHERE 	patchid = 553;