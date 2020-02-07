INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('524', '2017-07-20 19:02:00','Arvind Thakur','invoice vehicle mapping and vehicle kind in register device', '0');


CREATE TABLE IF NOT EXISTS `invoice_vehicle_mapping` (
    `id` INT(11) PRIMARY KEY auto_increment,
    `vehicleid` INT(11),
    `vehicleno` VARCHAR(40),
    `invoiceid` INT(11),
    `uid` INT(11),
    `createdon` DATETIME,
    `isdeleted` TINYINT(1) DEFAULT 0
);

DELIMITER $$
DROP PROCEDURE IF EXISTS `register_device`$$
CREATE PROCEDURE `register_device`(
    IN todaysdateParam DATETIME
    ,IN unitidParam INT(11)
    ,IN utypeParam INT(11)
    ,IN simcardidParam INT(11)
    ,IN customernoParam INT(11)
    ,IN ponoParam INT(11)
    ,IN podateParam DATE
    ,IN expirydateParam DATE
    ,IN installdateParam DATE
    ,IN invoicenoParam VARCHAR(50)
    ,IN vehiclenoParam VARCHAR(40)
    ,IN kindParam VARCHAR(40)
    ,IN leaseParam TINYINT(2)
    ,IN eteamidParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN statusParam TINYINT(2)
    ,IN unsuccessProblemParam TINYINT(2)
    ,IN incompleteDateParam DATETIME
    ,IN rescheduleDateParam DATETIME
    ,IN bucketidParam INT(11)
    ,IN commentParam VARCHAR(100)
    ,OUT isexecutedOut TINYINT(2)
    ,OUT usernameOut VARCHAR(50)
    ,OUT realnameOut VARCHAR(50)
    ,OUT emailOut VARCHAR(50)
    ,OUT unitnumberOut VARCHAR(16)
    ,OUT simcardnoOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150)
    ,OUT errormsgOut VARCHAR(100)
)

BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
            ROLLBACK;
           /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error;   */
            SET isexecutedOut = 0;
	END;
    BEGIN    
        DECLARE cstypeVar INT DEFAULT 13;
        DECLARE warrantyVar DATETIME DEFAULT DATE_ADD(CURRENT_DATE, INTERVAL 365 DAY);
        DECLARE vehicleidVar INT(11) DEFAULT 0;
        DECLARE useridVar INT(11);
        DECLARE panicVar TINYINT(1);
        DECLARE buzzerVar TINYINT(4);
        DECLARE mobiliserVar TINYINT(1);

        IF utypeParam = 23 THEN
            SET cstypeVar = 24;
        END IF;

        IF utypeParam = 22 THEN
            SET cstypeVar = 25;
        END IF;

        IF(vehiclenoParam = '' OR vehiclenoParam = '0') THEN
            SET vehiclenoParam = NULL;
        END IF;
        
        IF unitidParam <> '' OR unitidParam <> '0' THEN

            SELECT  unitno
                    ,is_panic
                    ,is_buzzer
                    ,is_mobiliser 
            INTO    unitnumberOut
                    ,panicVar
                    ,buzzerVar
                    ,mobiliserVar 
            FROM    unit 
            WHERE   uid = unitidParam
            ORDER BY uid DESC 
            LIMIT   1;

            SELECT  vehicleid 
            INTO    vehicleidVar  
            FROM    vehicle 
            WHERE   uid = unitidParam 
            AND     isdeleted = 0 
            ORDER BY vehicleid DESC 
            LIMIT   1;

        END IF;
        
        IF simcardidParam <> '' OR simcardidParam <> 0 THEN

            SELECT  simcardno 
            INTO    simcardnoOut 
            FROM    simcard 
            WHERE   id = simcardidParam
            ORDER BY id DESC 
            LIMIT   1;

        END IF;

        SELECT  userid 
        INTO    useridVar 
        FROM    `user` 
        WHERE   isdeleted=0 
        AND     customerno=customernoParam
        ORDER BY userid DESC 
        LIMIT   1;
        
        SET     isexecutedOut = 0;

        IF statusParam = 2 THEN
    
            IF vehicleidVar IS NOT NULL AND vehicleidVar <> 0 THEN

                START TRANSACTION;	 
                BEGIN

                    UPDATE  unit 
                    SET     customerno=customernoParam
                            , trans_statusid = utypeParam
                            ,teamid=0
                            , comments = commentParam 
                    WHERE   uid=unitidParam;

                    UPDATE  simcard 
                    SET     customerno=customernoParam
                            ,trans_statusid = cstypeVar
                            ,teamid=0
                            ,comments = commentParam 
                    WHERE   id=simcardidParam;

                    IF simcardidParam <> 0 THEN

                        UPDATE  devices 
                        SET     simcardid=0 
                        WHERE   simcardid=simcardidParam;

                    END IF;

                    UPDATE  devices 
                    SET     customerno=customernoParam
                            ,simcardid=simcardidParam
                            ,expirydate=expirydateParam
                            ,installdate=installdateParam
                            ,invoiceno=invoicenoParam
                            ,po_no=ponoParam
                            ,po_date=podateParam
                            ,warrantyexpiry=warrantyVar 
                    WHERE   uid=unitidParam;

                    IF  vehiclenoParam IS NULL OR vehiclenoParam='' THEN

                        UPDATE  vehicle 
                        SET     customerno=customernoParam 
                                ,kind = kindParam
                        WHERE   uid = unitidParam;

                    ELSE

                        UPDATE  vehicle 
                        SET     customerno=customernoParam
                                ,vehicleno=vehiclenoParam
                                ,kind = kindParam
                                ,stoppage_transit_time = todaysdateParam 
                        WHERE   uid = unitidParam;

                    END IF;

                    UPDATE  driver 
                    SET     customerno= customernoParam 
                    WHERE   vehicleid= vehicleidVar;

                    UPDATE  eventalerts 
                    SET     customerno= customernoParam 
                    WHERE   vehicleid=vehicleidVar;

                    UPDATE  ignitionalert 
                    SET     customerno= customernoParam 
                    WHERE   vehicleid= vehicleidVar;

                    UPDATE  acalerts 
                    SET     customerno= customernoParam 
                    WHERE   vehicleid= vehicleidVar;

                    --     for lease
                    IF leaseParam = 1 THEN
                        UPDATE  unit 
                        SET     onlease=leaseParam 
                        WHERE   uid =unitidParam;
                    END IF;

                    IF panicVar = 1 THEN
                        UPDATE  customer 
                        SET     use_panic=1 
                        WHERE   customerno=customernoParam;
                    END IF;

                    IF buzzerVar = 1 THEN
                        UPDATE  customer 
                        SET     use_buzzer=1 
                        WHERE   customerno=customernoParam;
                    END IF;

                    IF mobiliserVar = 1 THEN
                        UPDATE  customer 
                        SET     use_immobiliser=1 
                        WHERE   customerno=customernoParam;
                    END IF;

                    
                    INSERT INTO trans_history_new(`bucketid`
                        ,`newunitid`
                        ,`newvehicleid`
                        ,`newsimcardid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                    VALUES (bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,simcardidParam
                        ,'1'
                        ,'1'
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                --  daily report insert / vehiclewise_alert
                    INSERT INTO dailyreport(`customerno`
                        , `vehicleid`
                        , `uid`
                        ,`last_online_updated`)
                    VALUES (customernoParam
                        ,vehicleidVar
                        ,unitidParam
                        ,todaysdateParam);

                    INSERT INTO vehiclewise_alert (`customerno`
                            , `userid`
                            , `vehicleid`
                            ,`temp_active`
                            ,`ignition_active`
                            ,`speed_active`
                            ,`ac_active`
                            ,`powerc_active`
                            ,`tamper_active`
                            ,`harsh_break_active`
                            ,`high_acce_active`
                            ,`panic_active`
                            ,`door_active`)
                    SELECT  customerno
                            ,userid
                            ,vehicleid
                            ,temp_active
                            ,ignition_active
                            ,speed_active
                            ,ac_active
                            ,powerc_active
                            ,tamper_active
                            ,harsh_break_active
                            ,high_acce_active
                            ,panic_active
                            ,door_active 
                    FROM    vehiclewise_alert 
                    WHERE   userid = useridVar 
                    AND customerno= customernoParam 
                    LIMIT   1;                             
				
                    UPDATE  bucket 
                    SET     `status` = statusParam
                            ,`task_completion_timestamp` = todaysdateParam 
                    WHERE   bucketid= bucketidParam ;
                
                    SET isexecutedOut = 1;
                END;
                COMMIT; 
            
            ELSE
                SET errormsgOut = 'Vehicle Not Mapped';
                SET isexecutedOut = 0;
            END IF;
   
            SELECT      `name` INTO elixirOut 
            FROM        team 
            WHERE       teamid = eteamidParam
            ORDER BY    teamid DESC 
            LIMIT       1;

            SELECT      username
                        ,realname
                        ,email 
            INTO        usernameOut
                        ,realnameOut
                        ,emailOut 
            FROM        `user` 
            INNER JOIN  groupman ON groupman.userid  <> `user`.userid 
            WHERE       `user`.customerno = customernoParam 
            AND         `user`.email <> '' 
            AND         `user`.isdeleted = 0
            AND         (`user`.role = 'Administrator' OR `user`.role = 'Master') 
            ORDER BY    `user`.userid DESC
            LIMIT       1;

        ELSEIF statusParam = 3 THEN
    --      status : Unsuccessful
            START TRANSACTION;	 
            BEGIN

                UPDATE  bucket 
                SET     status= statusParam 
                        , is_problem_of = unsuccessProblemParam
                        , remarks= commentParam
                        ,task_completion_timestamp = todaysdateParam 
                where   bucketid=bucketidParam;

                INSERT INTO trans_history_new(`bucketid`
                        ,`newunitid`
                        ,`newvehicleid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                VALUES (bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,'1'
                        ,'2'
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                SET     isexecutedOut = 1;
            END;
            COMMIT;

        ELSEIF statusParam = 6 THEN
    --      status : Incomplete
            START TRANSACTION;	 
            BEGIN

                UPDATE  bucket  
                SET     status= statusParam 
                        ,reschedule_date=incompleteDateParam
                        ,reschedule_timestamp = todaysdateParam 
                        ,remarks = commentParam
                where   bucketid=bucketidParam;

                INSERT INTO bucket (`apt_date` 
                        ,`customerno`
                        ,`created_by`
                        , `priority`
                        , `vehicleid`
                        , `location`
                        , `timeslotid`
                        , `purposeid`
                        , `details`
                        , `coordinatorid`
                        , `create_timestamp`
                        , `status`)
                SELECT    incompleteDateParam
                        , customernoParam
                        , lteamidParam
                        , `priority`
                        , `vehicleid`
                        , `location`
                        , `timeslotid`
                        , `purposeid`
                        , `details`
                        , `coordinatorid`
                        , todaysdateParam
                        , '0'
                FROM    bucket
                WHERE   bucketid = bucketidParam
                LIMIT   1;


                INSERT INTO trans_history_new(`bucketid`
                        ,`newunitid`
                        ,`newvehicleid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                VALUES (bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,'1'
                        ,'5'
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                SET     isexecutedOut = 1;
            END;
            COMMIT;

        ELSEIF statusParam = 1 THEN
    --      status : Reschedule
            START TRANSACTION;	 
            BEGIN

                UPDATE  bucket 
                SET     status = statusParam 
                        ,reschedule_date = rescheduleDateParam
                        ,reschedule_timestamp = todaysdateParam 
                        ,remarks = commentParam
                WHERE   bucketid = bucketidParam;

                INSERT INTO bucket (`apt_date` 
                        ,`customerno`
                        ,`created_by`
                        , `priority`
                        , `vehicleid`
                        , `location`
                        , `timeslotid`
                        , `purposeid`
                        , `details`
                        , `coordinatorid`
                        , `create_timestamp`
                        , status)
                SELECT    rescheduleDateParam
                        , customernoParam
                        , lteamidParam
                        , `priority`
                        , `vehicleid`
                        , `location`
                        , `timeslotid`
                        , `purposeid`
                        , `details`
                        , `coordinatorid`
                        , `create_timestamp`
                        ,0
                FROM    `bucket`
                WHERE   `bucketid`=bucketidParam
                LIMIT   1;

                INSERT INTO trans_history_new(`bucketid`
                        ,`newunitid`
                        ,`newvehicleid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                VALUES (bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,1
                        ,3
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                SET isexecutedOut = 1;

            END;
            COMMIT;

        ELSEIF statusParam = 5 THEN
    --      status : Cancel
            START TRANSACTION;	 
            BEGIN

                UPDATE  bucket 
                SET     status = statusParam
                        , cancelled_timestamp = todaysdateParam
                        , cancellation_reason = commentParam 
                WHERE   bucketid = bucketidParam;

                INSERT INTO trans_history_new(`bucketid`
                        ,`oldunitid`
                        ,`oldvehicleid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                VALUES (bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,1
                        ,4
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                SET     isexecutedOut = 1;
            END;
            COMMIT;

        END IF;
	
    END;
END$$
DELIMITER ;


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
                    ,1);

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
                    ,`get_conversion`)
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
                    ,1);
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


DELIMITER $$
DROP PROCEDURE IF EXISTS `re_install_device`$$
CREATE PROCEDURE `re_install_device`(
    IN todaysdateParam DATETIME
    ,IN unitidParam INT(11)
    ,IN eteamidParam INT(11)
    ,IN newvehiclenoParam VARCHAR(40)
    ,IN kindParam VARCHAR(40)
    ,IN lteamidParam INT(11)
    ,IN statusParam TINYINT(2)
    ,IN unsuccessProblemParam TINYINT(2)
    ,IN incompleteDateParam DATETIME
    ,IN rescheduleDateParam DATETIME
    ,IN bucketidParam INT(11)
    ,IN commentParam VARCHAR(100)
    ,OUT isexecutedOut TINYINT(2)
    ,OUT newvehiclenoOut VARCHAR(40)
    ,OUT oldvehiclenoOut VARCHAR(40)
    ,OUT usernameOut VARCHAR(50)
    ,OUT realnameOut VARCHAR(50)
    ,OUT emailOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150)
    ,OUT errormsgOut VARCHAR(100))

    BEGIN
    DECLARE oldvehicleidVar INT(11);
    DECLARE newvehicleidVar INT(11);
    DECLARE customernoVar INT(11);
    DECLARE oldsimcardidVar INT(11);
    DECLARE groupidVar INT(11);
    DECLARE driveridVar INT(11);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error; */ 
            SET isexecutedOut = 0;
        END;    
        
        SELECT  vehicleid
                ,customerno 
                ,vehicleno
                ,groupid
                ,driverid
        INTO    oldvehicleidVar
                ,customernoVar 
                ,oldvehiclenoOut
                ,groupidVar
                ,driveridVar
        FROM    vehicle 
        WHERE   uid=unitidParam 
        AND     isdeleted=0
        ORDER BY vehicleid DESC
        LIMIT   1;
        
        SELECT  simcardid
        INTO    oldsimcardidVar
        FROM    devices
        WHERE   uid = unitidParam
        ORDER BY deviceid DESC
        LIMIT   1;

    IF statusParam = 2 THEN

        IF oldvehicleidVar IS NOT NULL AND oldvehicleidVar <> 0 THEN
            START TRANSACTION;
            BEGIN

                INSERT INTO vehicle(`vehicleno`
                    ,`uid`
                    ,`customerno`
                    ,`driverid`
                    ,`kind`) 
                VALUES  (newvehiclenoParam
                    ,unitidParam
                    ,customernoVar
                    ,driveridVar
                    ,kindParam);

                SELECT  LAST_INSERT_ID() 
                INTO    newvehicleidVar;

                UPDATE  unit 
                SET     vehicleid = newvehicleidVar
                        ,teamid = eteamidParam
                        ,trans_statusid = 5
                WHERE   vehicleid = oldvehicleidVar;

                UPDATE  driver 
                SET     vehicleid = newvehicleidVar
                        ,customerno = customernoVar
                WHERE   vehicleid = oldvehicleidVar 
                AND     isdeleted = 0;

                UPDATE  eventalerts 
                SET     vehicleid=newvehicleidVar
                        ,customerno=customernoVar
                WHERE   vehicleid=oldvehicleidVar;

                UPDATE  ignitionalert 
                SET     vehicleid=newvehicleidVar
                        ,customerno=customernoVar
                WHERE   vehicleid=oldvehicleidVar;

                UPDATE  acalerts 
                SET     vehicleid=newvehicleidVar
                        ,customerno=customernoVar
                WHERE   vehicleid=oldvehicleidVar;

                UPDATE  checkpointmanage 
                SET     vehicleid=newvehicleidVar
                        ,customerno=customernoVar
                WHERE   vehicleid=oldvehicleidVar 
                AND     isdeleted=0;

                UPDATE  fenceman 
                SET     vehicleid=newvehicleidVar
                        ,customerno=customernoVar
                WHERE   vehicleid=oldvehicleidVar 
                AND     isdeleted=0;

                UPDATE  vehicle 
                SET     isdeleted=1
                        ,uid=0 
                WHERE   vehicleid=oldvehicleidVar;

                INSERT INTO trans_history_new(`bucketid`
                        ,`oldunitid`
                        ,`oldvehicleid`
                        ,`newvehicleid`
                        ,`oldsimcardid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                VALUES(bucketidParam
                        ,unitidParam
                        ,oldvehicleidVar
                        ,newvehicleidVar
                        ,oldsimcardidVar
                        ,6
                        ,1
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        , customernoVar);

                UPDATE 	bucket 
                SET 	`status` = 2
                        ,`task_completion_timestamp` = todaysdateParam 
                WHERE	bucketid= bucketidParam ;

                SET     isexecutedOut = 1;
            END;
            COMMIT;

        ELSE
            SET isexecutedOut = 0;
            SET errormsgOut = 'Old vehicle not found';
        END IF;

        SELECT  `name` 
        INTO    elixirOut 
        FROM    team 
        WHERE   teamid = eteamidParam
        LIMIT   1;

        SELECT          c.username
                        ,c.realname
                        ,c.email 
        INTO            usernameOut
                        ,realnameOut
                        ,emailOut 
        FROM            `user` c 
        LEFT OUTER JOIN groupman p ON p.groupid = groupidVar 
        LEFT OUTER JOIN groupman ON c.userid <> groupman.userid 
        WHERE           c.customerno =  customernoVar 
        AND             c.email <> '' 
        AND             (c.groupid=groupidVar OR c.groupid ='0' ) 
        AND             (c.role = 'Administrator' OR c.role = 'Master')
        ORDER BY        c.`userid` DESC
        LIMIT           1;

        SET newvehiclenoOut=newvehiclenoParam;
        
    ELSEIF statusParam = 3 THEN
    
        START TRANSACTION;
        BEGIN
        
            UPDATE 	`bucket` 
            SET 	`status` = statusParam
                        ,`is_problem_of` = unsuccessProblemParam
                        ,`remarks` = commentParam
                        ,`task_completion_timestamp` = todaysdateParam 
            where 	`bucketid`=bucketidParam;
            
            INSERT INTO trans_history_new(`bucketid`
                    ,`oldunitid`
                    ,`oldvehicleid`
                    ,`transtypeid`
                    ,`bucketstatusid`
                    , `remark`
                    ,`teamid`
                    ,`createdby`
                    ,`createdon`
                    ,`customerno`)
            VALUES (bucketidParam
                    ,unitidParam
                    ,oldvehicleidVar
                    ,6
                    ,2
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoVar);

            UPDATE  unit 
            SET     trans_statusid= 5 
            where   uid = unitidParam;

            UPDATE  simcard 
            SET     trans_statusid = 13 
            where   id = oldsimcardidVar;
			
            SET     isexecutedOut = 1;
        END;
        COMMIT;
        
    ELSEIF statusParam = 6 THEN
    
        START TRANSACTION;
        BEGIN
        
            UPDATE  `bucket`  
            SET     `status` = statusParam 
                    ,`reschedule_date` = incompleteDateParam
                    ,`reschedule_timestamp` = todaysdateParam
                    ,remarks = commentParam
            where   `bucketid` = bucketidParam;
            
            INSERT INTO bucket (`apt_date`
                    ,`customerno`
                    ,`created_by`
                    , `priority`
                    , `vehicleid`
                    , `location`
                    , `timeslotid`
                    , `purposeid`
                    , `details`
                    , `coordinatorid`
                    , `create_timestamp`
                    , `status`)
            SELECT  incompleteDateParam
                    ,`customerno`
                    ,`created_by`
                    ,`priority`
                    ,`vehicleid`
                    ,`location`
                    ,`timeslotid`
                    ,`purposeid`
                    ,`details`
                    ,`coordinatorid`
                    ,todaysdateParam
                    , 0
            FROM    `bucket`
            WHERE   `bucketid` = bucketidParam
            ORDER BY `bucketid` DESC
            LIMIT   1;
        
            INSERT INTO trans_history_new(`bucketid`
                    ,`oldunitid`
                    ,`oldvehicleid`
                    ,`transtypeid`
                    ,`bucketstatusid`
                    , `remark`
                    ,`teamid`
                    ,`createdby`
                    ,`createdon`
                    ,`customerno`)
            VALUES (bucketidParam
                    ,unitidParam
                    ,oldvehicleidVar
                    ,6
                    ,5
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoVar);

            SET     isexecutedOut = 1;
        END;
        COMMIT;
        
    ELSEIF statusParam = 1 THEN
    
        START TRANSACTION;
        BEGIN
        
            UPDATE  `bucket` 
            SET     `status` = statusParam 
                    ,`reschedule_date` = rescheduleDateParam
                    ,`reschedule_timestamp` = todaysdateParam 
                    ,remarks = commentParam
            WHERE   `bucketid` = bucketidParam;
            
            INSERT INTO bucket (`apt_date` 
                    ,`customerno`
                    ,`created_by`
                    , `priority`
                    , `vehicleid`
                    , `location`
                    , `timeslotid`
                    , `purposeid`
                    , `details`
                    , `coordinatorid`
                    , `create_timestamp`
                    , `status`)
            SELECT rescheduleDateParam
                    ,`customerno`
                    ,`created_by`
                    , `priority`
                    , `vehicleid`
                    , `location`
                    , `timeslotid`
                    , `purposeid`
                    , `details`
                    , `coordinatorid`
                    , todaysdateParam
                    , 0  
            FROM    `bucket`
            WHERE   `bucketid` = bucketidParam
            ORDER BY `bucketid` DESC
            LIMIT   1;
            
            INSERT INTO trans_history_new(`bucketid`
                    ,`oldunitid`
                    ,`oldvehicleid`
                    ,`transtypeid`
                    ,`bucketstatusid`
                    , `remark`
                    ,`teamid`
                    ,`createdby`
                    ,`createdon`
                    ,`customerno`)
            VALUES (bucketidParam
                    ,unitidParam
                    ,oldvehicleidVar
                    ,6
                    ,3
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoVar);

            SET     isexecutedOut = 1;
        END;
        COMMIT;
        
    ELSEIF statusParam = 5 THEN
    
        START TRANSACTION;
        BEGIN
        
            UPDATE  `bucket` 
            SET     `status` = statusParam
                    ,`cancelled_timestamp` = todaysdateParam
                    ,`cancellation_reason` = commentParam 
            WHERE   `bucketid` = bucketidParam;
            
            INSERT INTO trans_history_new(`bucketid`
                    ,`oldunitid`
                    ,`oldvehicleid`
                    ,`transtypeid`
                    ,`bucketstatusid`
                    , `remark`
                    ,`teamid`
                    ,`createdby`
                    ,`createdon`
                    ,`customerno`)
            VALUES (bucketidParam
                    ,unitidParam
                    ,oldvehicleidVar
                    ,6
                    ,4
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoVar);

            UPDATE  unit 
            SET     trans_statusid= 5 
            where   uid = unitidParam;

            UPDATE  simcard 
            SET     trans_statusid = 13 
            where   id = oldsimcardidVar;
            
            SET     isexecutedOut = 1;
        END;
        COMMIT;
        
    END IF;

END$$
DELIMITER $$


UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 524;