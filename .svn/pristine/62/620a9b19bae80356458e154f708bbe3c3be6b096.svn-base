INSERT INTO `dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('642', '2018-12-14 18:45:00','Manasvi Thakur','Update purchase unit SP', '0');

DELIMITER $$
DROP PROCEDURE IF EXISTS `purchase_unit`$$
CREATE PROCEDURE `purchase_unit`(
     IN unitnoParam varchar(16)
    ,IN commentParam varchar(50)
    ,IN acsParam TINYINT(1)
    ,IN acoppParam TINYINT(1)
    ,IN gssParam TINYINT(1)
    ,IN gssoppParam TINYINT(1)
    ,IN dosParam TINYINT(1)
    ,IN dooroppParam TINYINT(1)
    ,IN todayParam DATETIME
    ,IN transmitnoParam varchar(20)
    ,IN devicetypeParam TINYINT(2)
    ,IN fsParam TINYINT(2)
    ,IN fuelanalogParam INT(11)
    ,IN tempsenParam TINYINT(2)
    ,IN analog1Param INT(11)
    ,IN analog2Param INT(11)
    ,IN analog3Param INT(11)
    ,IN analog4Param INT(11)
    ,IN typevalueParam INT(11)
    ,IN panicParam TINYINT(1)
    ,IN buzzerParam TINYINT(1)
    ,IN immobilizerParam TINYINT(1)
    ,IN twowaycomParam TINYINT(1)
    ,IN portableParam TINYINT(1)
    ,IN acesensorParam TINYINT(1)
    ,IN acdigitaloppParam TINYINT(1)
    ,IN chalaannoParam varchar(20)
    ,IN lteamidParam INT(11)
    ,IN chalaandateParam DATE
    ,IN vendornoParam varchar(20)
    ,IN vendordateParam DATE
    ,IN tempReadTypeParam DATE
    ,OUT isexecutedOut TINYINT(2)
)

BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
            /*ROLLBACK;
			GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error; */
            SET isexecutedOut = 0;
	END;
    BEGIN

        DECLARE unitidVar INT(11);
        DECLARE devicekeyVar BIGINT;
        DECLARE expiryVar date;
        DECLARE vehiclenoVar VARCHAR(40);
        DECLARE vehicleidVar INT(11);
        DECLARE driverlicnoVar VARCHAR(40);
        DECLARE driveridVar INT(11);


        SELECT  CONCAT('V',unitnoParam)
        INTO    vehiclenoVar;

        SELECT  FLOOR(RAND() * 100000000) + 1000000000
        INTO 	devicekeyVar;

        WHILE(SELECT devicekey FROM `devices` WHERE devicekey = devicekeyVar) DO
                SELECT  FLOOR(RAND() * 100000000) + 1000000000
                INTO 	devicekeyVar;
        END WHILE;

        SELECT  date(DATE_ADD(todayParam, INTERVAL 1 YEAR))
        INTO    expiryVar;

        SELECT  CONCAT('LIC',unitnoParam)
        INTO    driverlicnoVar;

        SET     isexecutedOut = 0;

        START TRANSACTION;
        BEGIN

            IF devicetypeParam = 1 THEN

                INSERT INTO unit(`customerno`
                    ,`unitno`
                    ,`trans_statusid`
                    ,`comments`
                    ,`get_conversion`)
                VALUES (1
                    ,unitnoParam
                    ,1
                    ,commentParam
                    ,tempReadTypeParam);

            ELSE

                INSERT INTO unit(`customerno`
                    ,`unitno`
                    ,`acsensor`
                    ,`is_ac_opp`
                    ,`gensetsensor`
                    ,`is_genset_opp`
                    ,`doorsensor`
                    ,`is_door_opp`
                    ,`trans_statusid`
                    , `comments`
                    ,`digitalioupdated`
                    ,`transmitterno`
                    ,`get_conversion`
                    )
            VALUES (1
                    ,unitnoParam
                    ,acsParam
                    ,acoppParam
                    ,gssParam
                    ,gssoppParam
                    ,dosParam
                    ,dooroppParam
                    ,1
                    ,commentParam
                    ,todayParam
                    ,transmitnoParam
                    ,tempReadTypeParam);
            END IF;

            SELECT  LAST_INSERT_ID()
            INTO    unitidVar;

            -- update all io updated
            UPDATE  unit
            SET     digitalioupdated = NOW()
                    ,door_digitalioupdated = NOW()
                    ,extra_digitalioupdated = NOW()
                    ,extra2_digitalioupdated = NOW()
            WHERE   uid = unitidVar;

            -- fuel temprature
            IF devicetypeParam = 2 AND (fsParam = 1 AND fuelanalogParam <> 0) THEN

                UPDATE  unit
                SET     fuelsensor = fuelanalogParam
                WHERE   unitno = unitnoParam;

            END IF;

            -- Temperature Sensor 1,2,3,4
            IF devicetypeParam = 2 AND tempsenParam = 2 AND analog1Param <> 0 AND analog2Param <> 0 THEN

                UPDATE  unit
                SET     tempsen1 = analog1Param
                        ,tempsen2 = analog2Param
                WHERE   unitno = unitnoParam;

            ELSEIF devicetypeParam = 2 AND tempsenParam = 1 AND analog1Param <> 0 THEN

                UPDATE  unit
                SET     tempsen1 = analog1Param
                WHERE   unitno = unitnoParam;

            ELSEIF devicetypeParam = 2 AND tempsenParam = 3 AND analog1Param <> 0 AND analog2Param <> 0 AND analog3Param <> 0 THEN

                UPDATE  unit
                SET     tempsen1 = analog1Param
                        , tempsen2 = analog2Param
                        , tempsen3 = analog3Param
                WHERE   unitno = unitnoParam;

            ELSEIF devicetypeParam = 2 AND tempsenParam = 4 AND analog1Param <> 0 AND analog2Param <> 0 AND analog3Param <> 0 AND analog4Param <> 0 THEN

                UPDATE  unit
                SET     tempsen1 = analog1Param
                        ,tempsen2 = analog2Param
                        ,tempsen3 = analog3Param
                        ,tempsen4 = analog4Param
                WHERE   unitno = unitnoParam;

            END IF;

            -- SET Unit Type Value

            UPDATE  unit
            SET     type_value = typevalueParam
            WHERE   uid = unitidVar;

            -- Panic
            IF devicetypeParam = 2 AND panicParam = 1 THEN

                UPDATE  unit
                SET     is_panic = 1
                WHERE   unitno = unitnoParam ;

                UPDATE  customer
                SET     use_panic = 1
                WHERE   customerno = 1;

            END IF;

            -- Buzzer
            IF devicetypeParam = 2 AND buzzerParam = 1 THEN

                UPDATE  unit
                SET     is_buzzer = 1
                WHERE   unitno = unitnoParam;

                UPDATE  customer
                SET     use_buzzer = 1
                WHERE   customerno = 1;

            END IF;

            -- Immobilizer
            IF devicetypeParam = 2 AND immobilizerParam = 1 THEN

                UPDATE  unit
                SET     is_mobiliser = 1
                WHERE   unitno = unitnoParam;

                UPDATE  customer
                SET     use_immobiliser = 1
                WHERE   customerno = 1;

            END IF;

            -- Two Way communication
            IF devicetypeParam = 2 AND twowaycomParam = 1 THEN

                UPDATE  unit
                SET     is_twowaycom = 1
                WHERE   unitno = unitnoParam;

            END IF;

            -- Portable
            IF devicetypeParam = 2 AND portableParam = 1 THEN

                UPDATE  unit
                SET     is_portable = 1
                WHERE   unitno = unitnoParam;

            END IF;

            -- Populate Devices
            INSERT INTO `devices`(`customerno`
                    , `devicekey`
                    , `devicelat`
                    , `devicelong`
                    , `baselat`
                    , `baselng`
                    , `installlat`
                    , `installlng`
                    , `lastupdated`
                    , `registeredon`
                    , `altitude`
                    , `directionchange`
                    , `inbatt`
                    , `hwv`
                    , `swv`
                    , `msgid`
                    , `uid`
                    , `status`
                    , `ignition`
                    , `powercut`
                    , `tamper`
                    , `gpsfixed`
                    , `online/offline`
                    , `gsmstrength`
                    , `gsmregister`
                    , `gprsregister`
                    , `aci_status`
                    , `satv`
                    , `device_invoiceno`
                    , `inv_generatedate`
                    , `installdate`
                    , `expirydate`
                    , `invoiceno`
                    , `po_no`
                    , `po_date`
                    , `warrantyexpiry`
                    , `simcardid`
                    , `inv_device_priority`
                    , `inv_deferdate`
                    , `start_date`
                    , `end_date`)
            VALUES (1
                    ,devicekeyVar
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,todayParam
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,unitidVar
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,expiryVar
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,'');


            -- INSERT INTO devices (`customerno`
--                     ,`devicekey`
--                     ,`registeredon`
--                     ,`uid`
--                     ,`expirydate`)
--             VALUES (1,devicekeyVar,todayParam,unitidVar,expiryVar);

            -- Populate Vehicles
            INSERT INTO vehicle (vehicleno
                    , customerno
                    , uid
                    , kind)
            VALUES  ( vehiclenoVar
                    , '1'
                    , unitidVar
                    , 'Truck');


            SELECT  LAST_INSERT_ID()
            INTO    vehicleidVar;

            -- Update Unit
            UPDATE  unit
            SET     vehicleid = vehicleidVar
            WHERE   uid = unitidVar;

            -- Populate Drivers
            INSERT INTO driver (drivername
                    ,driverlicno
                    ,customerno
                    ,vehicleid)
            VALUES ('Not Allocated',driverlicnoVar,1,vehicleidVar);

            SELECT  LAST_INSERT_ID()
            INTO    driveridVar;

            -- Update Vehicle
            UPDATE  vehicle
            SET     driverid = driveridVar
            WHERE   vehicleid = vehicleidVar;


            -- Populate Event Alerts
            INSERT INTO eventalerts (vehicleid
                    ,overspeed
                    , tamper
                    , powercut
                    , temp
                    , ac
                    , customerno)
            VALUES (vehicleidVar
                    ,0
                    ,0
                    ,0
                    ,0
                    ,0
                    ,1);


            -- Populate Ignition Alert
            INSERT INTO ignitionalert (vehicleid
                    ,last_status
                    ,last_check
                    ,`count`
                    ,`status`
                    ,customerno)
                    VALUES (vehicleidVar
                    ,0
                    ,0
                    ,0
                    ,0
                    ,1);

            -- Populate AC Alert
            IF acesensorParam = 1 AND acdigitaloppParam <> 0 THEN

                INSERT INTO acalerts (last_ignition
                    , customerno
                    , vehicleid
                    , aci_status)
                VALUES (0
                    ,1
                    ,vehicleidVar
                    ,0);

            END IF;

            INSERT INTO trans_history (`customerno`
                ,`unitid`
                ,`teamid`
                , `type`
                , `trans_time`
                , `statusid`
                , `transaction`
                , `simcardno`
                , `invoiceno`
                , `expirydate`
                , `comments`)
            VALUES (1
                ,unitidVar
                ,lteamidParam
                , 0
                ,todayParam
                , 1
                , 'New Purchase'
                ,''
                ,''
                ,''
                ,commentParam);

            IF chalaannoParam <> '' THEN

                INSERT INTO chalaan (uid
                    , chalaan_no
                    , chalaan_date
                    , vendor_invno
                    , vendor_invdate
                    , insertedby
                    , insertedon)
                VALUES(unitidVar
                    ,chalaannoParam
                    ,chalaandateParam
                    ,vendornoParam
                    ,vendordateParam
                    ,lteamidParam
                    ,todayParam);

            END IF;

            SET isexecutedOut = 1;

        END;
        COMMIT;

    END;
END$$
DELIMITER ;

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 642;
