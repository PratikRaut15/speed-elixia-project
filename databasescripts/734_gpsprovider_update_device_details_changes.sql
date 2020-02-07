SET @patchId = 733;
SET @patchDate = '2019-12-10 12:59:00';
SET @patchOwner = 'Sanjeet Shukla';
SET @patchDescription = 'uidParam, vehicleidParam Issue fixed';


INSERT INTO dbpatches(patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES (@patchId, @patchDate, @patchOwner, @patchDescription, '0');

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
    , IN analog2Param INT
    , IN digitalioParam SMALLINT
    , IN stoppageTransitTimeParam DATETIME
    , IN stoppageodometerParam BIGINT
    , IN stoppageflagParam TINYINT(1) UNSIGNED
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
    
    SET vehicleNoParam = REPLACE(vehicleNoParam, ' ','');
    
    SELECT  uid, vehicleid INTO varUid, varVehicleId
    FROM    vehicle
    WHERE   REPLACE(vehicleno, ' ','') = vehicleNoParam
    AND     customerno = custnoParam 
    AND     isdeleted = 0
    LIMIT   1;
    
    START TRANSACTION;
        UPDATE  devices
        SET     devicelat = devicelatParam
                ,devicelong = devicelngParam
                ,altitude = altitudeParam
                ,directionchange = dirChangeParam
                ,ignition = ignitionParam
                ,powercut = 1
                ,tamper = 0
                ,`online/offline`= 0
                , lastupdated = lastUpdatedParam
        WHERE   uid = varUid;

        UPDATE  vehicle
        SET     lastupdated = lastUpdatedParam
                ,nodata_alert = 0
                ,odometer = odometerParam
                ,curspeed = curspeedParam
        WHERE   uid = varUid;

        UPDATE  unit
        SET     analog1 = analog1Param
                ,analog2 = analog2Param
                ,digitalio = digitalioParam
        WHERE   uid = varUid;

        #IF (gpsfixedParam = 'A') THEN
        #BEGIN
            IF (odometerParam - stoppageodometerParam > 50 ) and stoppageflagParam = 0 and ignitionParam = 1 THEN
                BEGIN
                    UPDATE  vehicle
                    SET     stoppage_flag = 1
                            ,stoppage_odometer = odometerParam
                            ,stoppage_transit_time = lastUpdatedParam
                    WHERE   uid = varUid;
                    
                    UPDATE  stoppage_alerts
                    SET     alert_sent = 0
                    WHERE   vehicleid = varVehicleId
                    AND     customerno = custnoParam;
                    
                END;
            END IF;

            IF  ( (odometerParam - stoppageodometerParam < 50 ) and stoppageflagParam = 1) OR (ignitionParam = 0 AND stoppageflagParam = 1) THEN
                BEGIN
                    UPDATE  vehicle
                    SET     stoppage_flag = 0
                            ,stoppage_odometer = odometerParam
                            ,stoppage_transit_time = lastUpdatedParam
                    WHERE   uid = varUid;
                END;
            END IF;

            IF (odometerParam - stoppageodometerParam > 50 ) and stoppageflagParam = 1 and ignitionParam = 1 THEN
                BEGIN
                    UPDATE  vehicle
                    SET     stoppage_odometer = odometerParam
                    WHERE   uid = varUid;
                END;
            END IF;

            IF (odometerParam < stoppageodometerParam) THEN
                BEGIN
                    UPDATE  vehicle
                    SET     stoppage_odometer = odometerParam
                            ,stoppage_transit_time = lastUpdatedParam
                    WHERE   uid = varUid;
                END;
            END IF;
        #END;
        #END IF;

        SET isUpdated = 1;
    COMMIT;
    
    SELECT      u.uid, u.unitno, v.vehicleid, d.deviceid, dr.driverid, isUpdated
    FROM        unit u
    INNER JOIN  devices d on d.uid = u.uid
    INNER JOIN  vehicle v on v.uid = u.uid
    INNER JOIN  driver dr on dr.vehicleid = v.vehicleid
    WHERE       u.uid = varUid;
    
END$$
DELIMITER ;

UPDATE dbpatches SET isapplied = 1, updatedOn =  DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE) WHERE patchid = @patchId;
