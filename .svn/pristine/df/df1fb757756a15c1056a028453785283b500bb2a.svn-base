DROP PROCEDURE `listener_update_device_details`//
CREATE DEFINER=`UserSpeed`@`localhost` PROCEDURE `listener_update_device_details`(
     IN uidParam INT
    , IN vehicleidParam INT
    , IN devicelatParam DECIMAL(9,6)
    , IN devicelngParam DECIMAL(9,6)
    , IN altitudeParam INT UNSIGNED
    , IN dirChangeParam INT UNSIGNED
    , IN satvParam TINYINT UNSIGNED
    , IN inbattParam INT UNSIGNED
    , IN hwvParam VARCHAR(5)
    , IN swvParam VARCHAR(15)
    , IN msgidParam INT UNSIGNED
    , IN statusParam VARCHAR(2)
    , IN ignitionParam TINYINT(1)
    , IN powercutParam TINYINT(1)
    , IN tamperParam TINYINT(1) UNSIGNED
    , IN onoffParam TINYINT(1) UNSIGNED
    , IN gpsfixedParam VARCHAR(2)
    , IN gsmstrengthParam TINYINT UNSIGNED
    , IN gsmregisterParam TINYINT(1) UNSIGNED
    , IN gprsregisterParam TINYINT(1) UNSIGNED
    , IN extbattParam INT UNSIGNED
    , IN odometerParam BIGINT
    , IN stoppageodometerParam BIGINT
    , IN stoppageflagParam TINYINT(1) UNSIGNED
    , IN curspeedParam TINYINT UNSIGNED
    , IN analog1Param INT
    , IN analog2Param INT
    , IN analog3Param INT
    , IN analog4Param INT
    , IN digitalioParam SMALLINT
    , IN commandkeyParam TINYINT UNSIGNED
    , IN commandkeyvalParam VARCHAR(30)
    , IN alterremarkParam TEXT
    , IN custnoParam INT
    , IN todaysdate DATETIME
    , OUT isUpdated TINYINT(1)
)
BEGIN
    DECLARE useFuelSensorVar, fuelsensorVar TINYINT(1);
    DECLARE fuelBalanceVar, oldFuelBalanceVar, fuelMinVoltVar, fuelMaxVoltVar DECIMAL(6,2);
    DECLARE fuelMaxVoltCapacityVar, fuelPrevAnalogValue, fuelAnalogValue INT;
    DECLARE fuelAnalogColumnVar VARCHAR(20);
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

    IF onoffParam = 1 and (custnoParam= 69 or custnoParam = 73 or custnoParam = 81) THEN
        BEGIN
            INSERT INTO chk_offline(customerno,lastupdated,vehicleid,latitude,longitude)
            VALUES  (custnoParam,todaysdate,vehicleidParam, devicelatParam,devicelngParam);
        END;
    END IF;

    SELECT  use_fuel_sensor
    INTO    useFuelSensorVar
    FROM    customer
    WHERE   customerno = custnoParam
    LIMIT 1;

    START TRANSACTION;
        SET fuelBalanceVar = 0;
        IF useFuelSensorVar = 1 THEN
        BEGIN
            SELECT  fuelsensor, fuelMaxVoltCapacity, fuel_min_volt, fuel_max_volt, old_fuel_balance
            INTO    fuelsensorVar, fuelMaxVoltCapacityVar, fuelMinVoltVar, fuelMaxVoltVar, oldFuelBalanceVar
            FROM    unit u
            INNER JOIN vehicle v ON u.uid = v.uid
            WHERE   u.uid = uidParam
            AND     u.customerno = custnoParam
            AND     v.customerno = custnoParam
            LIMIT 1;
            /*SELECT fuelsensorVar, fuelMaxVoltCapacityVar, fuelMinVoltVar, fuelMaxVoltVar, oldFuelBalanceVar;*/
            IF fuelsensorVar IS NOT NULL AND fuelsensorVar >= 1 AND fuelsensorVar <= 4 THEN
                /*
                SET @fuelPrevAnalogValue = '';
                SET fuelAnalogColumnVar = CONCAT('analog', fuelsensorVar);
                SET @sqlQuery = CONCAT('SELECT ', fuelAnalogColumnVar, ' INTO @fuelPrevAnalogValue FROM unit WHERE uid = ', uidParam);
                PREPARE stmt FROM @sqlQuery;
                EXECUTE stmt;
                DEALLOCATE PREPARE stmt;
                SET fuelPrevAnalogValue = @fuelPrevAnalogValue;
                */
                IF fuelsensorVar = 1 THEN
                    SET fuelAnalogValue = analog1Param;
                ELSEIF fuelsensorVar = 2 THEN
                    SET fuelAnalogValue = analog2Param;
                ELSEIF fuelsensorVar = 3 THEN
                    SET fuelAnalogValue = analog3Param;
                ELSEIF fuelsensorVar = 4 THEN
                    SET fuelAnalogValue = analog4Param;
                END IF;
                IF  fuelMaxVoltCapacityVar IS NOT NULL AND fuelMinVoltVar IS NOT NULL AND fuelMaxVoltVar IS NOT NULL
                    AND fuelMinVoltVar > 0 AND fuelMaxVoltVar > 0 AND fuelMinVoltVar != fuelMaxVoltVar THEN
                    BEGIN
                        DECLARE floorNValue INT;
                        DECLARE voltageValue, floorFuelVoltageValue, voltageNthPart DECIMAL(6,2);
                        DECLARE fuelInaccuracyPercentVar TINYINT;

                        SET voltageValue = fuelAnalogValue / 100;
                        IF voltageValue >= fuelMaxVoltVar THEN
                            SET voltageValue = fuelMaxVoltVar;
                        ELSEIF voltageValue <= fuelMinVoltVar THEN
                            SET voltageValue = fuelMinVoltVar;
                        END IF;

                        SET fuelInaccuracyPercentVar = 2;
                        SET voltageNthPart = TRUNCATE((fuelMaxVoltVar - fuelMinVoltVar) / (100 / fuelInaccuracyPercentVar), 2);
                        SET floorNValue = FLOOR((voltageValue - fuelMinVoltVar) / voltageNthPart);
                        SET floorFuelVoltageValue = (floorNValue * voltageNthPart) + fuelMinVoltVar;
                        SET fuelBalanceVar = (fuelMaxVoltCapacityVar / (fuelMaxVoltVar - fuelMinVoltVar)) * (floorFuelVoltageValue - fuelMinVoltVar);
                        /*SELECT fuelAnalogValue, voltageNthPart, floorNValue,floorFuelVoltageValue,fuelBalanceVar;*/
                    END;
                END IF;

            END IF;
        END;
        END IF;

        IF gpsfixedParam = 'A' THEN
            BEGIN
                UPDATE  devices
                SET     devicelat = devicelatParam
                        ,devicelong = devicelngParam
                        ,altitude = altitudeParam
                        ,directionchange = dirChangeParam
                WHERE   uid = uidParam;

                UPDATE  vehicle
                SET     nodata_alert = 0
                        ,extbatt = extbattParam
                        ,odometer = odometerParam
                        ,curspeed = curspeedParam
                        ,fuel_balance = fuelBalanceVar
                WHERE   uid = uidParam;
            END;
        END IF;

        UPDATE  devices
        SET     satv = satvParam
                ,inbatt = inbattParam
                ,hwv = hwvParam
                ,swv = swvParam
                ,msgid = msgidParam
                ,`status` = statusParam
                ,ignition = ignitionParam
                ,powercut = powercutParam
                ,tamper = tamperParam
                ,`online/offline`= onoffParam
                , gpsfixed = gpsfixedParam
                , gsmstrength = gsmstrengthParam
                , gsmregister = gsmregisterParam
                , gprsregister = gprsregisterParam
                , lastupdated = todaysDate
        WHERE   uid = uidParam;

        UPDATE  vehicle
        SET     lastupdated = todaysdate
                ,fuel_balance = fuelBalanceVar
        WHERE   uid = uidParam;

        UPDATE  unit
        SET     analog1 = analog1Param
                ,analog2 = analog2Param
                ,analog3 = analog3Param
                ,analog4 = analog4Param
                ,digitalio = digitalioParam
                ,commandkey = commandkeyParam
                ,commandkeyval = commandkeyvalParam
                ,remark = 0
                ,alterremark = alterremarkParam
                ,issue_type = 0
        WHERE   uid = uidParam;

        IF gpsfixedParam = 'A' THEN
        BEGIN
            IF (odometerParam - stoppageodometerParam > 50 ) and stoppageflagParam = 0 and ignitionParam = 1 THEN
                BEGIN
                    UPDATE  vehicle
                    SET     stoppage_flag = 1
                            ,stoppage_odometer = odometerParam
                            ,stoppage_transit_time = todaysDate
                    WHERE   uid = uidParam;

                    UPDATE  stoppage_alerts
                    SET     alert_sent = 0
                    WHERE   vehicleid = vehicleidParam
                    AND     customerno = custnoParam;
                END;
            END IF;

            IF  ( (odometerParam - stoppageodometerParam < 50 ) and stoppageflagParam = 1) OR (ignitionParam = 0 AND stoppageflagParam = 1) THEN
                BEGIN
                    UPDATE  vehicle
                    SET     stoppage_flag = 0
                            ,stoppage_odometer = odometerParam
                            ,stoppage_transit_time = todaysDate
                    WHERE   uid = uidParam;
                END;
            END IF;

            IF (odometerParam - stoppageodometerParam > 50 ) and stoppageflagParam = 1 and ignitionParam = 1 THEN
                BEGIN
                    UPDATE  vehicle
                    SET     stoppage_odometer = odometerParam
                    WHERE   uid = uidParam;
                END;
            END IF;

            IF (odometerParam < stoppageodometerParam) THEN
                BEGIN
                    UPDATE  vehicle
                    SET     stoppage_odometer = odometerParam
                            ,stoppage_transit_time = todaysDate
                    WHERE   uid = uidParam;
                END;
            END IF;
        END;
        END IF;
        SET isUpdated = 1;
    COMMIT;
END
