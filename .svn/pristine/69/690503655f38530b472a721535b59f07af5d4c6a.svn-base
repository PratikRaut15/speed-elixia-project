
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'473', '2017-03-03 12:00:00', 'Arvind Thakur', 'Changes in replace_device and register_device SP', '0'
);


DELIMITER $$
DROP PROCEDURE IF EXISTS `replace_device`$$
CREATE PROCEDURE `replace_device`(
    IN todaysdateParam DATETIME
    ,IN customernoParam INT
    ,IN oldvehicleidParam INT    
    ,IN oldunitidParam INT
    ,IN eteamidParam INT
    ,IN newunitidParam INT
    ,IN commentParam VARCHAR(100)
    ,IN lteamidParam INT
    ,OUT isexecutedOut TINYINT
    ,OUT usernameOut VARCHAR(100)
    ,OUT realnameOut VARCHAR(100)
    ,OUT emailOut VARCHAR(100)
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT oldunitOut INT
    ,OUT newunitOut INT
    ,OUT simcardOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(100)
    ,OUT errormsgOut VARCHAR(50)
)
BEGIN
    DECLARE oldsimcardidVar INT;
    DECLARE groupidVar INT;
    DECLARE oldvehiclenoVar VARCHAR(40);
    DECLARE newunitnoVar INT;
    DECLARE oldunitnoVar INT;
    DECLARE oldvehicleidVar INT;
    DECLARE simcardnoVar VARCHAR(50);
    DECLARE onleaseVar TINYINT(2);
    DECLARE newvehicleidVar INT(11);
    DECLARE newdeviceidVar bigint(11);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error; */
            SET isexecutedOut = 0;
        END;
    
    SELECT      devices.simcardid
    INTO        oldsimcardidVar
    FROM        devices 
    WHERE       devices.uid =oldunitidParam
    ORDER BY    deviceid DESC
    LIMIT       1;
    
    SELECT      vehicleid
    INTO        newvehicleidVar
    FROM        vehicle
    WHERE       uid = newunitidParam
    AND         isdeleted = 0
    ORDER BY    vehicleid DESC
    LIMIT       1;
    
    SELECT      v.groupid 
    INTO        groupidVar
    FROM        vehicle v
    WHERE       v.vehicleid=oldvehicleidParam
    AND         v.isdeleted = 0
    ORDER BY    v.vehicleid DESC
    LIMIT       1;
    
    SELECT      unitno 
    INTO        newunitnoVar 
    FROM        unit 
    WHERE       uid =newunitidParam
    ORDER BY    uid DESC
    LIMIT       1;

    SELECT      unitno
    INTO        oldunitnoVar
    FROM        unit 
    WHERE       uid=oldunitidParam
    ORDER BY    uid DESC
    LIMIT       1;

    SELECT      onlease 
    INTO        onleaseVar
    FROM        unit 
    WHERE       uid = oldunitidParam
    ORDER BY    uid DESC
    LIMIT       1;
    
    SELECT      simcardno 
    INTO        simcardnoVar 
    FROM        simcard 
    WHERE       id = oldsimcardidVar
    ORDER BY    id DESC
    LIMIT       1;
    
    SELECT	deviceid 
    INTO 	newdeviceidVar 
    FROM 	devices 
    WHERE       uid=newunitidParam
    ORDER BY    deviceid DESC
    LIMIT       1;
    
    START TRANSACTION;
    BEGIN

        IF oldvehicleidParam <> 0 THEN

            UPDATE  unit 
            SET     customerno=customernoParam
                    , trans_statusid = 5
                    , teamid=0
                    , vehicleid = oldvehicleidParam
                    ,onlease = onleaseVar
            where   uid=newunitidParam;

            UPDATE  simcard 
            SET     trans_statusid=13
            WHERE   id=oldsimcardidVar;

            UPDATE  devices 
            SET     uid = newunitidParam
            WHERE   uid=oldunitidParam;

            UPDATE 	devices
            SET		uid = oldunitidParam
            WHERE	deviceid = newdeviceidVar;

--          Populate Vehicles    

--          old groupid set for new unit 
--          TODO
            UPDATE  vehicle 
            SET     uid=0
            WHERE   uid=newunitidParam;

            UPDATE  vehicle 
            SET     uid=newunitidParam
            WHERE   vehicleid = oldvehicleidParam AND customerno=customernoParam;

            SET newunitOut=newunitnoVar;

    --      Remove Old Unit    
            UPDATE  unit 
            SET     customerno=1
                    , userid=0
                    ,  trans_statusid = 20
                    ,teamid=eteamidParam
                    ,comments = commentParam
                    ,onlease = 0
                    , vehicleid = newvehicleidVar
            WHERE   uid=oldunitidParam;

    --      Daily report update replace unit for customer 
            UPDATE  dailyreport 
            SET     uid = newunitidParam
                    , first_odometer=0
                    , last_odometer=0
                    , max_odometer=0 
            WHERE   vehicleid = oldvehicleidParam 
            AND     customerno=customernoParam;

            INSERT INTO trans_history_new(`oldunitid`
                ,`newunitid`
                ,`oldvehicleid`
                ,`oldsimcardid`
                ,`transtypeid`
                ,`bucketstatusid`
                , `remark`
                ,`teamid`
                ,`createdby`
                ,`createdon`,`customerno`
                )
            VALUES (oldunitidParam
                ,newunitidParam
                ,oldvehicleidParam
                ,oldsimcardidVar
                ,4
                ,1
                ,commentParam
                ,eteamidParam
                ,lteamidParam
                ,todaysdateParam,customernoParam);

                        UPDATE 	vehicle
            SET		uid = oldunitidParam
                                        ,customerno = 1
            WHERE	vehicleid = newvehicleidVar;

            UPDATE  dailyreport 
            SET     uid = oldunitidParam
                    ,customerno = 1
            WHERE   vehicleid = newvehicleidVar;

            SET isexecutedOut=1;

        ELSE

            SET isexecutedOut=0;
            SET errormsgOut='Vehicle Not Found';

        END IF;
    END;
    COMMIT;

    SELECT      `name` 
    INTO        elixirOut 
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
    LEFT OUTER JOIN groupman p on p.groupid = groupidVar 
    LEFT OUTER JOIN groupman ON `user`.userid <> groupman.userid 
    WHERE       `user`.customerno = customernoParam 
    AND         `user`.email <> '' 
    AND         `user`.isdeleted=0 
    AND         (`user`.groupid= groupidVar OR `user`.groupid ='0') 
    AND         (`user`.role='Administrator' OR `user`.role = 'Master') 
    ORDER BY    `user`.userid DESC
    LIMIT       1;
        
    SET     vehiclenoOut = oldvehiclenoVar;
    SET     simcardOut = simcardnoVar;
    SET     oldunitOut = oldunitnoVar;
   
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `register_device`$$
CREATE PROCEDURE `register_device`(
    IN todaysdateParam DATETIME
    ,IN commentsParam VARCHAR(100)
    ,IN unitidParam INT
    ,IN utypeParam INT
    ,IN simcardidParam INT
    ,IN customernoParam INT
    ,IN ponoParam INT
    ,IN podateParam DATE
    ,IN expirydateParam DATE
    ,IN installdateParam DATE
    ,IN invoicenoParam VARCHAR(50)
    ,IN vehiclenoParam VARCHAR(50)
    ,IN leaseParam TINYINT(1)
    ,IN eteamidParam INT
    ,IN lteamidParam INT
    ,OUT isexecutedOut TINYINT
    ,OUT usernameOut VARCHAR(100)
    ,OUT realnameOut VARCHAR(100)
    ,OUT emailOut VARCHAR(100)
    ,OUT unitnumberOut INT
    ,OUT simcardnoOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(100)
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
    DECLARE lasttransVar INT DEFAULT 0;
    DECLARE vehicleidVar INT(11);
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

    SELECT  vehicleid INTO vehicleidVar  
    FROM    vehicle 
    WHERE   uid = unitidParam 
    AND     isdeleted = 0 
    ORDER BY vehicleid DESC 
    LIMIT   1;

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
        
    SELECT  simcardno 
    INTO    simcardnoOut 
    FROM    simcard 
    WHERE   id = simcardidParam
    ORDER BY id DESC
    LIMIT   1;

    SELECT  userid 
    INTO    useridVar 
    FROM    `user` 
    WHERE   isdeleted=0 
    AND     customerno=customernoParam 
    ORDER BY userid DESC
    LIMIT 1;

    IF vehicleidVar IS NOT NULL AND vehicleidVar <> 0 THEN

        START TRANSACTION;	 
        BEGIN

                UPDATE  unit 
                SET     customerno=customernoParam
                                , trans_statusid = utypeParam
                                ,teamid=0
                                , comments = commentsParam 
                WHERE   uid=unitidParam;

                UPDATE  simcard 
                SET     customerno=customernoParam
                                ,trans_statusid = cstypeVar
                                ,teamid=0
                                ,comments = commentsParam 
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

                IF  vehiclenoParam = NULL OR vehiclenoParam='' THEN
                        UPDATE  vehicle 
                        SET     customerno=customernoParam 
                        WHERE   uid = unitidParam;
                ELSE
                        UPDATE  vehicle 
                        SET     customerno=customernoParam
                                        ,vehicleno=vehiclenoParam
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

                
                INSERT INTO trans_history_new( 
                        `newunitid`
                        ,`newvehicleid`
                        ,`newsimcardid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`,`customerno`)
                VALUES (unitidParam
                        ,vehicleidVar
                        ,simcardidParam
                        ,1
                        ,1
                        ,commentsParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam, customernoParam);

               

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
                SELECT customerno
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
                        FROM vehiclewise_alert 
                        WHERE userid = useridVar AND customerno= customernoParam limit 1;                             

                SET isexecutedOut = 1;
        END;
        COMMIT;    
    ELSE

        SET errormsgOut='Failed.Vehicle demapped';

        SET isexecutedOut = 0;

    END IF;
    
    SELECT  `name` INTO elixirOut 
    FROM    team 
    WHERE   teamid = eteamidParam;

    SELECT  username
            ,realname
            ,email 
    INTO    usernameOut
            ,realnameOut
            ,emailOut 
    FROM    `user` 
    INNER JOIN groupman ON groupman.userid  <> `user`.userid 
    WHERE   `user`.customerno = customernoParam 
    AND     `user`.email <> '' 
    AND     (`user`.role = 'Administrator' 
    OR      `user`.role = 'Master') LIMIT 1;
	
END;
END$$
DELIMITER ;

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied =1
WHERE   patchid = 473;


