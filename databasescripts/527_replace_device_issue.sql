INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'527', '2017-08-02 11:15:00', 'Arvind Thakur', 'Replace unit issue solved in SP', '0'
);


DELIMITER $$
DROP PROCEDURE IF EXISTS `replace_both`$$
CREATE PROCEDURE `replace_both`(
     IN todaysdateParam DATETIME
    ,IN customernoParam INT(11)
    ,IN oldvehicleidParam INT(11)         
    ,IN oldunitidParam INT(11)
    ,IN eteamidParam INT(11)
    ,IN newunitidParam INT(11)
    ,IN newsimidParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN statusParam TINYINT(2)
    ,IN unsuccessProblemParam TINYINT(2)
    ,IN incompleteDateParam DATETIME
    ,IN rescheduleDateParam DATETIME
    ,IN bucketidParam INT(11)
    ,IN commentParam VARCHAR(100)
    ,OUT isexecutedOut TINYINT(2)
    ,OUT usernameOut varchar(50)
    ,OUT realnameOut varchar(50)
    ,OUT emailOut varchar(50)
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT oldunitOut VARCHAR(16)
    ,OUT oldsimOut VARCHAR(50)
    ,OUT newunitOut VARCHAR(16)
    ,OUT newsimOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150)
    ,OUT errormsgOut VARCHAR(100) 
    )

BEGIN
    DECLARE newsimcardnoVar VARCHAR(50);
    DECLARE oldsimcardidVar INT(11);
    DECLARE oldvehicleidVar INT(11);
    DECLARE groupidVar INT(11);
    DECLARE oldunitnoVar VARCHAR(16);
    DECLARE oldvehiclenoVar VARCHAR(40);
    DECLARE simcardnumberVar VARCHAR(50);
    DECLARE newunitnoVar VARCHAR(16);
    DECLARE onleaseVar TINYINT(2);   
    DECLARE newdeviceidVar BIGINT(11);
    DECLARE newvehicleidVar INT(11);
    DECLARE vehicleStringVar VARCHAR(20);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        /*GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error; */
        SET isexecutedOut = 0;
    END;

    SELECT      simcardno 
    INTO        newsimcardnoVar 
    FROM        simcard 
    WHERE       id =newsimidParam
    ORDER BY    id DESC
    LIMIT       1;

    SELECT      devices.simcardid
    INTO        oldsimcardidVar
    FROM        devices 
    WHERE       devices.uid =oldunitidParam
    ORDER BY    deviceid DESC
    LIMIT       1;
    
    SELECT      unitno 
    INTO        newunitnoVar 
    FROM        unit 
    WHERE       uid =newunitidParam
    ORDER BY    uid DESC
    LIMIT       1;

    SELECT      unitno
                ,onlease
    INTO        oldunitnoVar
                ,onleaseVar
    FROM        unit 
    WHERE       uid =  oldunitidParam
    ORDER BY    uid DESC
    LIMIT       1;
    
    SELECT      vehicleid
                ,vehicleno
                , groupid
    INTO        oldvehicleidVar
                ,oldvehiclenoVar
                ,groupidVar
    FROM        vehicle 
    WHERE       vehicleid =  oldvehicleidParam
    AND         isdeleted = 0
    ORDER BY    vehicleid DESC
    LIMIT       1;

    SELECT  simcardno 
    INTO    simcardnumberVar 
    FROM    simcard 
    WHERE   id = oldsimcardidVar
    ORDER BY id DESC
    LIMIT   1;

    SELECT	deviceid
    INTO 	newdeviceidVar
    FROM	devices
    WHERE	uid = newunitidParam
    LIMIT	1;
    
    SELECT 	vehicleid
    INTO	newvehicleidVar
    FROM 	vehicle
    WHERE 	uid = newunitidParam
    ORDER BY    vehicleid DESC
    LIMIT	1;
    
    SELECT 	concat('V',oldunitnoVar)
    INTO	vehicleStringVar;

    IF statusParam = 2 THEN

        START TRANSACTION;
        BEGIN
            IF oldvehicleidParam <> 0 THEN
            --  Remove Old Device    
                UPDATE 	unit 
                SET 	customerno=customernoParam
                        , trans_statusid = 5
                        , teamid=0
                        , vehicleid = oldvehicleidParam
                        ,onlease = onleaseVar
                where 	uid=newunitidParam;
                
                UPDATE  unit 
                SET      customerno=1
                        ,userid=0
                        ,trans_statusid = 20
                        ,teamid=eteamidParam
                        ,comments = commentParam
                        ,onlease = 0
                        ,vehicleid = newvehicleidVar
                WHERE   uid=oldunitidParam;
                
                UPDATE  simcard 
                SET     trans_statusid=13
                WHERE   id=oldsimcardidVar;

                UPDATE  devices 
                SET     uid = 0
                WHERE   uid=newunitidParam;

                UPDATE  devices 
                SET     uid = newunitidParam
                WHERE   uid=oldunitidParam;

            --  Populate Vehicles

                UPDATE  vehicle 
                SET     uid=0
                WHERE   uid=newunitidParam;

                UPDATE  vehicle 
                SET     uid=newunitidParam
                WHERE   vehicleid = oldvehicleidParam AND customerno=customernoParam;

            --  New Sim Card
                UPDATE  devices 
                SET     simcardid=newsimidParam 
                WHERE   simcardid=oldsimcardidVar;

                UPDATE  simcard
                SET     customerno=customernoParam
                        ,trans_statusid=13
                        ,teamid=0
                WHERE   id=newsimidParam;

                UPDATE  simcard 
                SET     customerno=1
                        ,trans_statusid=21
                        ,teamid=eteamidParam
                WHERE   id=oldsimcardidVar;

                INSERT INTO trans_history_new(`bucketid`
                        ,`oldunitid`
                        ,`newunitid`
                        ,`oldvehicleid`
                        ,`oldsimcardid`
                        ,`newsimcardid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                    VALUES (bucketidParam
                        ,oldunitidParam
                        ,newunitidParam
                        ,oldvehicleidParam
                        ,oldsimcardidVar
                        ,newsimidParam
                        ,5
                        ,1
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

            --  Replace daily reprt  
                UPDATE  dailyreport 
                SET     uid = newunitidParam
                        , first_odometer=0
                        , last_odometer=0
                        , max_odometer=0 
                WHERE   vehicleid = oldvehicleidParam 
                AND     customerno=customernoParam;

                UPDATE	devices
                SET 	uid = oldunitidParam
                        ,simcardid = oldsimcardidVar
                        ,customerno = 1
                WHERE	deviceid = newdeviceidVar;

                UPDATE	vehicle
                SET	uid = oldunitidParam
                        ,customerno = 1
                        ,vehicleno = vehicleStringVar
                WHERE	vehicleid = newvehicleidVar;
				
                UPDATE 	bucket 
                SET 	`status` = 2
                        ,`task_completion_timestamp` = todaysdateParam 
                WHERE	bucketid= bucketidParam ;
            
                SET isexecutedOut = 1;

            ELSE
                SET isexecutedOut = 0;
                SET errormsgOut= 'Vehicle Not found';
            END IF;
        END;
        COMMIT;

        SELECT  `name` 
        INTO    elixirOut 
        FROM    team 
        WHERE   teamid = eteamidParam
        ORDER BY teamid DESC
        LIMIT   1;

        SELECT  c.username
                ,c.realname
                ,c.email 
        INTO    usernameOut
                ,realnameOut
                ,emailOut
        FROM    `user` c 
        LEFT OUTER JOIN groupman p on p.groupid =  1 
        LEFT OUTER JOIN groupman on c.userid <> groupman.userid 
        WHERE   c.customerno = customernoParam
        AND     c.email <> ''
        AND     c.isdeleted=0 
        AND     (c.groupid=groupidVar OR c.groupid ='0' ) 
        AND     (c.role = 'Administrator' OR c.role = 'Master')
        ORDER BY c.userid DESC
        LIMIT 1;

        SET vehiclenoOut=oldvehiclenoVar;
        SET oldunitOut=oldunitnoVar;
        SET oldsimOut=simcardnumberVar;
        SET newunitOut=newunitnoVar;
        SET newsimOut=newsimcardnoVar;

    ELSEIF statusParam = 3 THEN
    
        START TRANSACTION;
        BEGIN
        
            UPDATE 	bucket 
            SET 	`status`=statusParam
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
                    ,oldunitidParam
                    ,oldvehicleidVar
                    ,5
                    ,2
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoParam);

                UPDATE  unit 
                SET     trans_statusid= 5 
                where   uid = oldunitidParam;

                UPDATE  simcard 
                SET     trans_statusid = 13 
                where   id = oldsimcardidVar;
                
            SET isexecutedOut = 1;
        END;
        COMMIT;
    ELSEIF statusParam = 6 THEN
    
        START TRANSACTION;
        BEGIN
        
            UPDATE  bucket  
            SET     status= statusParam 
                    ,reschedule_date = incompleteDateParam
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
            SELECT  incompleteDateParam
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
                    ,oldunitidParam
                    ,oldvehicleidVar
                    ,5
                    ,5
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    , customernoParam);
                    
            SET     isexecutedOut = 1;
        END;
        COMMIT;
        
    ELSEIF statusParam = 1 THEN
    
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
                    , `status`)
            SELECT  rescheduleDateParam
                    ,`customerno`
                    ,`created_by`
                    , `priority`
                    , `vehicleid`
                    , `location`
                    , `timeslotid`
                    , `purposeid`
                    , `details`
                    , `coordinatorid`
                    ,todaysdateParam
                    ,0
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
                    ,oldunitidParam
                    ,oldvehicleidVar
                    ,5
                    ,3
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoParam);

            SET     isexecutedOut = 1;
        END;
        COMMIT;

    ELSEIF statusParam = 5 THEN
    
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
                    ,oldunitidParam
                    ,oldvehicleidVar
                    ,5
                    ,4
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoParam);

            UPDATE  unit 
            SET     trans_statusid= 5 
            where   uid = oldunitidParam;

            UPDATE  simcard 
            SET     trans_statusid = 13 
            where   id = oldsimcardidVar;
            
            SET isexecutedOut = 1;
        END;
        COMMIT;
    END IF;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `replace_device`$$
CREATE PROCEDURE `replace_device`(
    IN todaysdateParam DATETIME
    ,IN customernoParam INT(11)
    ,IN oldvehicleidParam INT(11)    
    ,IN oldunitidParam INT(11)
    ,IN eteamidParam INT(11)
    ,IN newunitidParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN bucketidParam INT(11)
    ,IN commentParam VARCHAR(100)
    ,OUT isexecutedOut TINYINT(2)
    ,OUT usernameOut VARCHAR(50)
    ,OUT realnameOut VARCHAR(50)
    ,OUT emailOut VARCHAR(50)
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT oldunitOut VARCHAR(16)
    ,OUT newunitOut VARCHAR(16)
    ,OUT simcardOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150)
    ,OUT errormsgOut VARCHAR(100)
)

BEGIN
    DECLARE oldsimcardidVar INT;
    DECLARE groupidVar INT;
    DECLARE oldvehiclenoVar VARCHAR(40);
    DECLARE newunitnoVar VARCHAR(16);
    DECLARE oldunitnoVar VARCHAR(16);
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
                ,vehicleno
    INTO        groupidVar
                ,oldvehiclenoVar
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

            UPDATE  devices
            SET     uid = oldunitidParam
            WHERE   deviceid = newdeviceidVar;   

--          old groupid set for new unit 
--          TODO
            UPDATE  vehicle 
            SET     uid = 0
            WHERE   uid = newunitidParam;

            UPDATE  vehicle 
            SET     uid = newunitidParam
            WHERE   vehicleid = oldvehicleidParam AND customerno = customernoParam;

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

            INSERT INTO trans_history_new(`bucketid`
                    ,`oldunitid`
                    ,`newunitid`
                    ,`oldvehicleid`
                    ,`oldsimcardid`
                    ,`transtypeid`
                    ,`bucketstatusid`
                    , `remark`
                    ,`teamid`
                    ,`createdby`
                    ,`createdon`
                    ,`customerno`)
            VALUES (bucketidParam
                    ,oldunitidParam
                    ,newunitidParam
                    ,oldvehicleidParam
                    ,oldsimcardidVar
                    ,4
                    ,1
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoParam);

            UPDATE  vehicle
            SET     uid = oldunitidParam
                    ,customerno = 1
            WHERE   vehicleid = newvehicleidVar;

            UPDATE  dailyreport 
            SET     uid = oldunitidParam
                    ,customerno = 1
            WHERE   vehicleid = newvehicleidVar;
			
            UPDATE  bucket 
            SET     `status` = 2
                    ,`task_completion_timestamp` = todaysdateParam 
            WHERE   bucketid = bucketidParam ;
            
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

    SELECT          username
                    ,realname
                    ,email 
    INTO            usernameOut
                    ,realnameOut
                    ,emailOut
    FROM            `user` 
    LEFT OUTER JOIN groupman p ON p.groupid = groupidVar 
    LEFT OUTER JOIN groupman ON `user`.userid <> groupman.userid 
    WHERE           `user`.customerno = customernoParam 
    AND             `user`.email <> '' 
    AND             `user`.isdeleted=0 
    AND             (`user`.groupid= groupidVar OR `user`.groupid ='0') 
    AND             (`user`.role='Administrator' OR `user`.role = 'Master') 
    ORDER BY        `user`.userid DESC
    LIMIT           1;
        
    SET vehiclenoOut = oldvehiclenoVar;
    SET simcardOut = simcardnoVar;
    SET oldunitOut = oldunitnoVar;
    SET newunitOut=newunitnoVar;
   
END$$
DELIMITER ;

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied =1
WHERE   patchid = 527;