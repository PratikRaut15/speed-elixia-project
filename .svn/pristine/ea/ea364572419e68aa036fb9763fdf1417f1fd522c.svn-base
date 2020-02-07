/*
    Name		-	re_install_device
    Description 	-	Reinstall a device.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	
    Created by		-	Arvind
    Created on		- 	06 Dec,2016
    Change details 	-	
    1) 	Updated by	- 	Arvind
	Updated	on	- 	09 March,2017
        Reason		-	
*/

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