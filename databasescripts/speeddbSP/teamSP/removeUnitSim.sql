/*
    Name		-	remove_unit_sim
    Description 	-	remove bad device.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	
    Created by		-	Arvind
    Created on		- 	09 March,2017
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `remove_unit_sim`$$
CREATE PROCEDURE `remove_unit_sim`(
    IN todaysdateParam DATETIME
    ,IN customernoParam INT(11)
    ,IN unitidParam INT(11)
    ,IN eteamidParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN statusParam TINYINT(2)
    ,IN unsuccessProblemParam TINYINT(2)
    ,IN incompleteDateParam DATETIME
    ,IN rescheduleDateParam DATETIME
    ,IN bucketidParam INT(11)
    ,IN commentParam VARCHAR(50)
    ,OUT isexecutedOut TINYINT(2)
    ,OUT usernameOut VARCHAR(50)
    ,OUT realnameOut VARCHAR(50)
    ,OUT emailOut VARCHAR(50)
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT unitnumverOut VARCHAR(16)
    ,OUT simnumberOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150))

BEGIN
    DECLARE simcardidVar INT(11);
    DECLARE unitnoVar VARCHAR(11);
    DECLARE vehicleidVar INT(11);
    DECLARE groupidVar INT(11);
    DECLARE vehiclenoVar VARCHAR(40);
    DECLARE simcardnoVar VARCHAR(50);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
          /*  ROLLBACK;
            GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error; */
            SET isexecutedOut = 0;
        END;

    SELECT	simcardid 
    INTO 	simcardidVar 
    FROM 	devices 
    WHERE 	uid = unitidParam
    ORDER BY    deviceid DESC
    LIMIT 	1;

    SELECT	simcardno 
    INTO	simcardnoVar 
    FROM 	simcard 
    WHERE 	id = simcardidVar
    ORDER BY    id DESC
    LIMIT 	1;

    SELECT 	unitno 
    INTO 	unitnoVar 
    FROM 	unit 
    WHERE 	uid = unitidParam
    ORDER BY    uid DESC
    LIMIT 	1;

    SELECT 	vehicleid
                ,vehicleno
                ,groupid 
    INTO 	vehicleidVar
                ,vehiclenoVar
                ,groupidVar 
    FROM 	vehicle 
    WHERE 	uid = unitidParam
    ORDER BY    vehicleid DESC
    LIMIT 	1;

    IF statusParam = 2 THEN

        START TRANSACTION;
        BEGIN

            UPDATE  unit 
            SET     trans_statusid = 20
                    ,teamid = eteamidParam
                    ,comments = commentParam 
            WHERE 	uid= unitidParam;    

            UPDATE  simcard 
            SET     trans_statusid= 21
                    ,teamid=eteamidParam
                    , comments = commentParam 
            WHERE   id= simcardidVar;


        --  Daily report both unit remove bad
            DELETE FROM dailyreport 
            WHERE       customerno = customernoParam  
            AND         uid= unitidParam;

            INSERT INTO trans_history_new(`bucketid`
                    ,`oldunitid`
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
                    ,unitidParam
                    ,vehicleidVar
                    ,simcardidVar
                    ,'2'
                    ,'1'
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    , customernoParam);

        --  Customerno - Make it 1
            UPDATE  unit 
            SET     customerno=1
                    ,userid=0
                    , comments = commentParam 
            WHERE   uid= unitidParam;

            UPDATE  devices 
            SET     customerno=1
                    , expirydate='0000-00-00'
                    , device_invoiceno = ''
                    , inv_generatedate = '0000-00-00 00:00:00'
                    ,po_no=''
                    , po_date='0000-00-00'
                    , invoiceno=''
                    , installdate='0000-00-00' 
            WHERE   uid=unitidParam;


        --  unset lease on old device
            UPDATE  unit 
            SET     onlease=0 
            WHERE   uid = unitidParam;

        --  Populate Vehicles

            UPDATE  vehicle 
            SET     customerno=1 
            WHERE   uid = unitidParam;

            UPDATE  driver
            SET     customerno = 1 
            WHERE   vehicleid = vehicleidVar;

            UPDATE  eventalerts 
            SET     customerno = 1 
            WHERE   vehicleid = vehicleidVar;

            UPDATE  ignitionalert 
            SET     customerno = 1 
            WHERE   vehicleid = vehicleidVar;

            UPDATE  acalerts 
            SET     customerno = 1 
            WHERE   vehicleid = vehicleidVar;

            UPDATE  checkpointmanage
            SET     customerno = 1
                    ,isdeleted = 1 
            WHERE   vehicleid = vehicleidVar;

            UPDATE  fenceman 
            SET     customerno = 1
                    ,isdeleted = 1 
            WHERE   vehicleid = vehicleidVar;

            UPDATE  groupman 
            SET     customerno = 1
                    ,isdeleted = 1
            WHERE   vehicleid = vehicleidVar;

            UPDATE  reportman 
            SET     customerno = 1 
            WHERE   uid = unitidParam;
            
            UPDATE  bucket 
            SET     `status` = statusParam
                    ,`task_completion_timestamp` = todaysdateParam 
            WHERE   bucketid = bucketidParam ;
            
            SET isexecutedOut=1;

        END;
        COMMIT;

        SELECT  `name` 
        INTO    elixirOut 
        FROM    team 
        WHERE   teamid =  eteamidParam
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
        WHERE           c.customerno = customernoParam  
        AND             c.email <> '' 
        AND             c.isdeleted = 0 
        AND             (c.groupid=groupidVar  OR c.groupid ='0') 
        AND             (c.`role` = 'Administrator' OR c.`role` = 'Master') 
        ORDER BY        c.userid DESC
        LIMIT           1;

        SET vehiclenoOut=vehiclenoVar;
        SET unitnumverOut=unitnoVar;
        SET simnumberOut=simcardnoVar;

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
                        ,`remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
            VALUES (bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,2
                        ,2
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

            UPDATE  unit 
            SET     trans_statusid= 5 
            where   uid = unitidParam;

            UPDATE  simcard 
            SET     trans_statusid = 13 
            where   id = simcardidVar;

            SET isexecutedOut=1;

        END;
        COMMIT;
        
    ELSEIF statusParam = 6 THEN
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
            SELECT  incompleteDateParam
                    , `customerno`
                    , `created_by`
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
            WHERE   `bucketid`= bucketidParam
            ORDER BY bucketid DESC
            LIMIT   1;
            
            INSERT INTO trans_history_new(`bucketid`
                    ,`oldunitid`
                    ,`oldvehicleid`
                    ,`transtypeid`
                    ,`bucketstatusid`
                    ,`remark`
                    ,`teamid`
                    ,`createdby`
                    ,`createdon`
                    ,`customerno`)
            VALUES (bucketidParam
                    ,unitidParam
                    ,vehicleidVar
                    ,2
                    ,5
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoParam);

            SET isexecutedOut=1;
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
                    ,`priority`
                    ,`vehicleid`
                    ,`location`
                    ,`timeslotid`
                    ,`purposeid`
                    ,`details`
                    ,`coordinatorid`
                    , `create_timestamp`
                    ,`status`)
            SELECT	rescheduleDateParam
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
                    ,0
            FROM    `bucket`
            WHERE   `bucketid`=bucketidParam
            ORDER BY bucketid DESC
            LIMIT   1;
            
            INSERT INTO trans_history_new(`bucketid`
                    ,`oldunitid`
                    ,`oldvehicleid`
                    ,`transtypeid`
                    ,`bucketstatusid`
                    ,`remark`
                    ,`teamid`
                    ,`createdby`
                    ,`createdon`
                    ,`customerno`)
            VALUES (bucketidParam
                    ,unitidParam
                    ,vehicleidVar
                    ,'2'
                    ,'3'
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoParam);

            SET isexecutedOut=1;
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
                    ,vehicleidVar
                    ,2
                    ,4
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoParam);

            UPDATE  unit 
            SET     trans_statusid= 5 
            where   uid = unitidParam;

            UPDATE  simcard 
            SET     trans_statusid = 13 
            where   id = simcardidVar;
               
            SET isexecutedOut=1;
        END;
        COMMIT;

    END IF;
END$$
DELIMITER ;
