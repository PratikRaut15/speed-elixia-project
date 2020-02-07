
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'477', '2017-03-09 11:51:00', 'Arvind Thakur', 'Team operation SP change for bucket', '0'
);

ALTER TABLE `trans_history_new`
ADD COLUMN `bucketid` INT(11) DEFAULT 0 AFTER `transid`;

DELIMITER $$
DROP PROCEDURE IF EXISTS `register_device`$$
CREATE PROCEDURE `register_device`(
    IN todaysdateParam DATETIME
    ,IN commentsParam VARCHAR(100)
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
    ,IN leaseParam TINYINT(2)
    ,IN eteamidParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN statusParam TINYINT(2)
    ,IN unsuccessProblemParam TINYINT(2)
    ,IN unsuccessRemarkParam VARCHAR(100)
    ,IN incompleteDateParam DATETIME
    ,IN incompleteReasonIdParam TINYINT(2)
    ,IN rescheduleDateParam DATETIME
    ,IN rescheduleReasonIdParam TINYINT(2)
    ,IN cancelReasonParam VARCHAR(100)
    ,IN bucketidParam INT(11)
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
        DECLARE vehicleidVar INT(11);
        DECLARE useridVar INT(11);
        DECLARE panicVar TINYINT(1);
        DECLARE buzzerVar TINYINT(4);
        DECLARE mobiliserVar TINYINT(1);
        DECLARE incompleteReasonVar VARCHAR(100);
        DECLARE rescheduleReasonVar VARCHAR(100);

        SELECT `reason`
        INTO    incompleteReasonVar
        FROM    `nc_reason`
        WHERE   reasonid=incompleteReasonIdParam
        LIMIT   1;

        SELECT `reason`
        INTO    rescheduleReasonVar
        FROM    `nc_reason`
        WHERE   reasonid=rescheduleReasonIdParam
        LIMIT   1;

        SELECT  vehicleid INTO vehicleidVar  
        FROM    vehicle 
        WHERE   uid = unitidParam 
        AND     isdeleted = 0 
        ORDER BY vehicleid DESC 
        LIMIT   1;

        IF utypeParam = 23 THEN
            SET cstypeVar = 24;
        END IF;

        IF utypeParam = 22 THEN
            SET cstypeVar = 25;
        END IF;

        IF(vehiclenoParam = '' OR vehiclenoParam = '0') THEN
            SET vehiclenoParam = NULL;
        END IF;

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
        LIMIT   1;

        IF statusParam = 2 THEN
    
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

                    IF  vehiclenoParam IS NULL OR vehiclenoParam='' THEN

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
                        ,commentsParam
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
                        , remarks= unsuccessRemarkParam
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
                        ,unsuccessRemarkParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                UPDATE  unit 
                SET     trans_statusid= 5 
                where   uid = unitidParam;
        
                UPDATE  simcard 
                SET     trans_statusid = 13 
                where   id = simcardidParam;

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
                        ,reasonid = incompleteReasonIdParam
                        ,reschedule_timestamp = todaysdateParam 
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
                        ,incompleteReasonVar
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
                        ,reasonid = rescheduleReasonIdParam
                        ,reschedule_timestamp = todaysdateParam 
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
                        ,rescheduleReasonVar
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
                        , cancellation_reason = cancelReasonParam 
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
                        ,cancelReasonParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                UPDATE  unit 
                SET     trans_statusid= 5 
                where   uid = unitidParam;

                UPDATE  simcard 
                SET     trans_statusid = 13 
                where   id = simcardidParam;

                SET     isexecutedOut = 1;
            END;
            COMMIT;

        END IF;
	
    END;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `repair`$$
CREATE PROCEDURE `repair`(
    IN todaysdateParam DATETIME
    ,IN commentsParam VARCHAR(100)
    ,IN unitidParam INT(11)
    ,IN simcardidParam INT(11)
    ,IN eteamidParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN customernoParam INT(11)
    ,IN statusParam TINYINT(2)
    ,IN unsuccessProblemParam TINYINT(2)
    ,IN unsuccessRemarkParam VARCHAR(100)
    ,IN incompleteDateParam DATETIME
    ,IN incompleteReasonIdParam TINYINT(2)
    ,IN rescheduleDateParam DATETIME
    ,IN rescheduleReasonIdParam TINYINT(2)
    ,IN cancelReasonParam VARCHAR(100)
    ,IN bucketidParam INT(11)
    ,OUT isexecutedOut TINYINT(2)
    ,OUT usernameOut VARCHAR(50)
    ,OUT realnameOut VARCHAR(50)
    ,OUT emailOut VARCHAR(50)
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT unitnoOut VARCHAR(16)
    ,OUT simnumberOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
            ROLLBACK;
            /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error;  */  
            SET isexecutedOut = 0;
	END;
    BEGIN    
        DECLARE unitnoVar VARCHAR(11);
        DECLARE vehicleidVar INT(11);
        DECLARE vehiclenoVar VARCHAR(40);
        DECLARE groupidVar INT(11);
        DECLARE incompleteReasonVar VARCHAR(100);
        DECLARE rescheduleReasonVar VARCHAR(100);

        SELECT `reason`
        INTO    incompleteReasonVar
        FROM    `nc_reason`
        WHERE   reasonid=incompleteReasonIdParam
        LIMIT   1;

        SELECT `reason`
        INTO    rescheduleReasonVar
        FROM    `nc_reason`
        WHERE   reasonid=rescheduleReasonIdParam
        LIMIT   1;

        SELECT      unitno 
        INTO        unitnoVar 
        FROM        unit 
        WHERE       uid =unitidParam
        ORDER BY    uid DESC
        LIMIT       1;
		
        SELECT      vehicleid
                    ,vehicleno
                    ,groupid 
        INTO        vehicleidVar 
                    ,vehiclenoVar
                    ,groupidVar 
        FROM        vehicle 
        WHERE       uid =unitidParam
        ORDER BY    vehicleid DESC
        LIMIT       1;

        IF statusParam = 2 THEN

            START TRANSACTION;
            BEGIN

                UPDATE  unit 
                SET     trans_statusid= 5
                                , comments = commentsParam 
                WHERE   uid= unitidParam;

                UPDATE  simcard 
                SET     trans_statusid= 13
                                ,comments =commentsParam 
                WHERE   id=simcardidParam;

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
                        ,simcardidParam
                        ,7
                        ,1
                        ,commentsParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                UPDATE 	bucket 
                SET 	`status` = statusParam
                        ,`task_completion_timestamp` = todaysdateParam 
                WHERE	bucketid= bucketidParam ;

                SET isexecutedOut = 1;

            END;
            COMMIT;

        --  Send Email

            SELECT      simcardno 
            INTO        simnumberOut 
            FROM        simcard 
            WHERE       id = simcardidParam
            ORDER BY    id DESC
            LIMIT       1;

        --  $team = lteamidParam;
            SELECT      `name` 
            INTO        elixirOut 
            FROM        team 
            WHERE       teamid = eteamidParam
            ORDER BY    teamid DESC
            LIMIT       1;

            SELECT          c.username
                            ,c.realname
                            ,c.email 
            INTO            usernameOut
                            ,realnameOut
                            ,emailOut 
            FROM            `user` c 
            LEFT OUTER JOIN groupman p ON p.groupid = groupidVar 
            LEFT OUTER JOIN groupman ON c.userid <> groupman.userid 
            WHERE           c.customerno = " . customernoParam . " 
            AND             c.email <> '' 
            AND             (c.groupid=groupidVar or c.groupid ='0' ) 
            AND             (c.role = 'Administrator' OR c.role = 'Master')
            ORDER BY        c.userid DESC
            LIMIT           1;

            SET vehiclenoOut=vehiclenoVar;
            SET unitnoOut=unitnoVar;

        ELSEIF statusParam = 3 THEN

            START TRANSACTION;
            BEGIN

                UPDATE 	bucket 
                SET 	`status`=statusParam
                        ,`is_problem_of` = unsuccessProblemParam
                        ,`remarks`=unsuccessRemarkParam
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
                VALUES ( bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,'7'
                        ,'2'
                        ,commentsParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                UPDATE  unit 
                SET     trans_statusid= 5 
                where   uid = unitidParam;

                UPDATE  simcard 
                SET     trans_statusid = 13 
                where   id = simcardidParam;

                SET     isexecutedOut = 1;

            END;
            COMMIT;

        ELSEIF statusParam = 6 THEN

            START TRANSACTION;
            BEGIN
     --         incompleteReasonParam
                UPDATE	bucket 
                SET 	`status` = statusParam
                        ,`reschedule_date` = incompleteDateParam
                        ,reasonid = incompleteReasonIdParam
                        ,`reschedule_timestamp` = todaysdateParam 
                where 	bucketid = bucketidParam;

                INSERT INTO bucket(`apt_date`
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
                WHERE   `bucketid`=bucketidParam
                ORDER BY    bucketid DESC
                LIMIT       1;

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
                        ,'7'
                        ,'5'
                        ,incompleteReasonVar
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                SET     isexecutedOut = 1;

            END;
            COMMIT;
            
        ELSEIF statusParam = 1 THEN

            START TRANSACTION;
            BEGIN

                UPDATE  bucket 
                SET     `status` = statusParam 
                        ,reschedule_date = rescheduleDateParam
                        ,reasonid = rescheduleReasonIdParam
                        ,reschedule_timestamp = todaysdateParam 
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
                SELECT 	rescheduleDateParam
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
                FROM 	`bucket`
                WHERE	`bucketid`=bucketidParam
                ORDER BY    bucketid DESC
                LIMIT       1;

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
                        ,'7'
                        ,'3'
                        ,rescheduleReasonVar
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
                        , cancellation_reason = cancelReasonParam 
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
                        ,'7'
                        ,'4'
                        ,cancelReasonParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                UPDATE  unit 
                SET     trans_statusid= 5 
                where   uid = unitidParam;

                UPDATE  simcard 
                SET     trans_statusid = 13 
                where   id = simcardidParam;

                SET     isexecutedOut = 1;

            END;
            COMMIT;

        END IF;

    END;
END$$
DELIMITER ;

-- Remove bad unit and sim

DELIMITER $$
DROP PROCEDURE IF EXISTS `remove_unit_sim`$$
CREATE PROCEDURE `remove_unit_sim`(
    IN todaysdateParam DATETIME
    ,IN customernoParam INT(11)
    ,IN unitidParam INT(11)
    ,IN eteamidParam INT(11)
    ,IN commentsParam VARCHAR(50)
    ,IN lteamidParam INT(11)
    ,IN statusParam TINYINT(2)
    ,IN unsuccessProblemParam TINYINT(2)
    ,IN unsuccessRemarkParam VARCHAR(100)
    ,IN incompleteDateParam DATETIME
    ,IN incompleteReasonIdParam TINYINT(2)
    ,IN rescheduleDateParam DATETIME
    ,IN rescheduleReasonIdParam TINYINT(2)
    ,IN cancelReasonParam VARCHAR(100)
    ,IN bucketidParam INT(11)
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
    DECLARE incompleteReasonVar VARCHAR(100);
    DECLARE rescheduleReasonVar VARCHAR(100);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
          /*  ROLLBACK;
            GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error; */
            SET isexecutedOut = 0;
        END;

    SELECT `reason`
    INTO    incompleteReasonVar
    FROM    `nc_reason`
    WHERE   reasonid=incompleteReasonIdParam
    ORDER BY reasonid DESC
    LIMIT   1;

    SELECT `reason`
    INTO    rescheduleReasonVar
    FROM    `nc_reason`
    WHERE   reasonid=rescheduleReasonIdParam
    ORDER BY reasonid DESC
    LIMIT   1;

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
                    ,comments = commentsParam 
            WHERE 	uid= unitidParam;    

            UPDATE  simcard 
            SET     trans_statusid= 21
                    ,teamid=eteamidParam
                    , comments = commentsParam 
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
                    ,commentsParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    , customernoParam);

        --  Customerno - Make it 1
            UPDATE  unit 
            SET     customerno=1
                    ,userid=0
                    , comments = commentsParam 
            WHERE uid= unitidParam;

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
                        ,`remarks`=unsuccessRemarkParam
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
                        ,unsuccessRemarkParam
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
                    ,reasonid = incompleteReasonIdParam
                    ,reschedule_timestamp = todaysdateParam 
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
                    ,incompleteReasonVar
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
                    ,reasonid = rescheduleReasonIdParam
                    ,`reschedule_timestamp` = todaysdateParam 
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
                    ,rescheduleReasonVar
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
                    ,`cancellation_reason` = cancelReasonParam 
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
                    ,cancelReasonParam
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

-- Replace Simcard

DELIMITER $$
DROP PROCEDURE IF EXISTS `replace_sim`$$
CREATE PROCEDURE `replace_sim`(
    IN todaysdateParam DATETIME
    ,IN customernoParam INT(11)
    ,IN oldvehicleidParam INT(11)     
    ,IN unitidParam INT(11)    
    ,IN eteamidParam INT(11)
    ,IN newsimidParam INT(11)
    ,IN commentParam VARCHAR(50)
    ,IN lteamidParam INT(11)
    ,IN bucketidParam INT(11)
    ,OUT isexecutedOut TINYINT(2)
    ,OUT usernameOut varchar(50)
    ,OUT realnameOut varchar(50)
    ,OUT emailOut varchar(50)
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT oldsimcardnoOut VARCHAR(50)
    ,OUT newsimcardnoOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150)
    )

BEGIN
    DECLARE oldsimcardidVar INT;
    DECLARE simdeviceidVar INT;
    DECLARE oldsimcardnoVar VARCHAR(50);
    DECLARE newsimcardnoVar VARCHAR(50);
    DECLARE vehiclenoVar VARCHAR(50);
    DECLARE groupidVar INT(11);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error; */
            SET isexecutedOut = 0;
        END;

    SELECT      simcardid
                ,deviceid 
    INTO        oldsimcardidVar
                ,simdeviceidVar 
    FROM        devices 
    WHERE       uid = unitidParam
    ORDER BY    deviceid DESC
    LIMIT       1;

    SELECT      simcardno 
    INTO        oldsimcardnoVar 
    FROM        simcard 
    WHERE       id = oldsimcardidVar
    ORDER BY    id DESC
    LIMIT       1;
    
    SELECT      simcardno 
    INTO        newsimcardnoVar 
    FROM        simcard 
    WHERE       id = newsimidParam
    ORDER BY    id DESC
    LIMIT       1;

	-- select vehicleid;
    SELECT      vehicleno
                ,groupid 
    INTO        vehiclenoVar
                ,groupidVar 
    FROM        vehicle 
    WHERE       vehicleid = oldvehicleidParam
    AND         isdeleted = 0
    ORDER BY    vehicleid DESC
    LIMIT       1;

    START TRANSACTION;
    BEGIN

        UPDATE  unit 
        SET     trans_statusid = 5
        WHERE   uid = unitidParam;

    --  New Sim Card
        UPDATE  devices 
        SET     simcardid = newsimidParam 
        WHERE   simcardid = oldsimcardidVar 
        AND     deviceid = simdeviceidVar;

        UPDATE  simcard
        SET     customerno = customernoParam
                ,trans_statusid = 13
                ,teamid = 0
        WHERE   id = newsimidParam;

        UPDATE  simcard 
        SET     customerno = 1
                ,trans_statusid = 21
                ,teamid = eteamidParam
        WHERE   id = oldsimcardidVar;

        INSERT INTO trans_history_new(`bucketid` 
                ,`oldunitid`
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
                ,unitidParam
                ,oldvehicleidParam
                ,oldsimcardidVar
                ,newsimidParam
                ,3
                ,1
                ,commentParam
                ,eteamidParam
                ,lteamidParam
                ,todaysdateParam
                ,customernoParam);
		
        UPDATE 	bucket 
        SET 	`status` = 2
                ,`task_completion_timestamp` = todaysdateParam 
        WHERE	bucketid= bucketidParam ;
                
        SET isexecutedOut = 1;

    END;
    COMMIT;

    SELECT      `name` 
    INTO        elixirOut 
    FROM        team 
    WHERE       teamid = eteamidParam
    ORDER BY    teamid DESC
    LIMIT       1;

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
    AND             c.isdeleted=0 
    AND             (c.groupid=groupidVar OR c.groupid ='0' ) 
    AND             (c.`role` = 'Administrator' OR c.`role` = 'Master')
    ORDER BY        c.userid DESC
    LIMIT           1;
    
    SET vehiclenoOut=vehiclenoVar;
    SET oldsimcardnoOut=oldsimcardnoVar;
    SET newsimcardnoOut=newsimcardnoVar;

END$$
DELIMITER ;

-- Replace Device

DELIMITER $$
DROP PROCEDURE IF EXISTS `replace_device`$$
CREATE PROCEDURE `replace_device`(
    IN todaysdateParam DATETIME
    ,IN customernoParam INT(11)
    ,IN oldvehicleidParam INT(11)    
    ,IN oldunitidParam INT(11)
    ,IN eteamidParam INT(11)
    ,IN newunitidParam INT(11)
    ,IN commentParam VARCHAR(100)
    ,IN lteamidParam INT(11)
    ,IN bucketidParam INT(11)
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
    DECLARE newunitnoVar INT;
    DECLARE oldunitnoVar INT;
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

-- Replace both unit and sim

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
    ,IN commentsParam VARCHAR(100)
    ,IN lteamidParam INT(11)
    ,IN statusParam TINYINT(2)
    ,IN unsuccessProblemParam TINYINT(2)
    ,IN unsuccessRemarkParam VARCHAR(100)
    ,IN incompleteDateParam DATETIME
    ,IN incompleteReasonIdParam TINYINT(2)
    ,IN rescheduleDateParam DATETIME
    ,IN rescheduleReasonIdParam TINYINT(2)
    ,IN cancelReasonParam VARCHAR(100)
    ,IN bucketidParam INT(11)
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
    DECLARE oldunitnoVar VARCHAR(11);
    DECLARE oldvehiclenoVar VARCHAR(40);
    DECLARE simcardnumberVar VARCHAR(50);
    DECLARE newunitnoVar VARCHAR(11);
    DECLARE onleaseVar TINYINT(2);   
    DECLARE incompleteReasonVar VARCHAR(100);
    DECLARE rescheduleReasonVar VARCHAR(100);
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
    
    SELECT `reason`
    INTO    incompleteReasonVar
    FROM    `nc_reason`
    WHERE   reasonid=incompleteReasonIdParam
    LIMIT   1;

    SELECT `reason`
    INTO    rescheduleReasonVar
    FROM    `nc_reason`
    WHERE   reasonid=rescheduleReasonIdParam
    LIMIT   1;

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
                        ,comments = commentsParam
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
                        ,commentsParam
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
                        ,`remarks`=unsuccessRemarkParam
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
                    ,unsuccessRemarkParam
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
                    ,reschedule_date=incompleteDateParam
                    ,reasonid = incompleteReasonIdParam
                    ,reschedule_timestamp = todaysdateParam 
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
                    ,incompleteReasonVar
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
                    ,reasonid = rescheduleReasonIdParam
                    ,reschedule_timestamp = todaysdateParam 
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
                    ,rescheduleReasonVar
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
                    , cancellation_reason = cancelReasonParam 
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
                    ,cancelReasonParam
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
DROP PROCEDURE IF EXISTS `re_install_device`$$
CREATE PROCEDURE `re_install_device`(
    IN todaysdateParam DATETIME
    ,IN unitidParam INT(11)
    ,IN eteamidParam INT(11)
    ,IN newvehiclenoParam VARCHAR(40)
    ,IN commentParam VARCHAR(100)
    ,IN lteamidParam INT(11)
    ,IN statusParam TINYINT(2)
    ,IN unsuccessProblemParam TINYINT(2)
    ,IN unsuccessRemarkParam VARCHAR(100)
    ,IN incompleteDateParam DATETIME
    ,IN incompleteReasonIdParam TINYINT(2)
    ,IN rescheduleDateParam DATETIME
    ,IN rescheduleReasonIdParam TINYINT(2)
    ,IN cancelReasonParam VARCHAR(100)
    ,IN bucketidParam INT(11)
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
    DECLARE incompleteReasonVar VARCHAR(100);
    DECLARE rescheduleReasonVar VARCHAR(100);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error; */ 
            SET isexecutedOut = 0;
        END;    

        SELECT `reason`
        INTO    incompleteReasonVar
        FROM    `nc_reason`
        WHERE   reasonid=incompleteReasonIdParam
        LIMIT   1;

        SELECT `reason`
        INTO    rescheduleReasonVar
        FROM    `nc_reason`
        WHERE   reasonid=rescheduleReasonIdParam
        LIMIT   1;
        
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
                    ,`driverid`) 
                VALUES  (newvehiclenoParam
                    ,unitidParam
                    ,customernoVar
                    ,driveridVar);

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
                WHERE   vehicleid = oldvehicleidVar AND isdeleted = 0;

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
                WHERE   vehicleid=oldvehicleidVar AND isdeleted=0;

                UPDATE  fenceman 
                SET     vehicleid=newvehicleidVar
                        ,customerno=customernoVar
                WHERE   vehicleid=oldvehicleidVar AND isdeleted=0;

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
                        ,`remarks`=unsuccessRemarkParam
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
                    ,unsuccessRemarkParam
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
                    ,reasonid = incompleteReasonIdParam
                    ,`reschedule_timestamp` = todaysdateParam 
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
                    ,incompleteReasonVar
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
                    ,reasonid = rescheduleReasonIdParam
                    ,`reschedule_timestamp` = todaysdateParam 
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
                    ,rescheduleReasonVar
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
                    ,`cancellation_reason` = cancelReasonParam 
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
                    ,cancelReasonParam
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

DELIMITER $$
DROP PROCEDURE IF EXISTS `suspect_unit`$$
CREATE PROCEDURE `suspect_unit`(
     IN commentParam VARCHAR(100)
    ,IN unitidParam INT(11)
    ,IN simcardidParam INT(11)
    ,IN customernoParam INT(11)
    ,IN aptdateParam DATE
    ,IN conameParam VARCHAR(30)
    ,IN cophoneParam VARCHAR(15)
    ,IN priorityParam INT(4)
    ,IN locationParam VARCHAR(50)
    ,IN timeslotParam INT(4)
    ,IN purposeParam INT(4)
    ,IN detailsParam VARCHAR(100)
    ,IN coordinatorParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN todaysdateParam DATETIME
    ,OUT isexecutedOut TINYINT(2)
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT unitnoOut VARCHAR(16)
    ,OUT simcardnoOut VARCHAR(50)
    ,OUT usernameOut VARCHAR(50)
    ,OUT realnameOut VARCHAR(50)
    ,OUT emailOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150)
    ,OUT msgOut VARCHAR(100))
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
    DECLARE simcardnoVar VARCHAR(50);
    DECLARE unitnoVar VARCHAR(11);
    DECLARE vehicleidVar INT(11);
    DECLARE vehiclenoVar VARCHAR(40);
    DECLARE groupidVar INT(11);
    DECLARE concatstrVar VARCHAR(100);

    SELECT  simcardno 
    INTO    simcardnoVar 
    FROM    simcard 
    WHERE   id = simcardidParam
    LIMIT   1;
    
    SELECT  unitno 
    INTO    unitnoVar 
    FROM    unit 
    WHERE   uid = unitidParam
    LIMIT   1;

    SELECT  v.vehicleid 
    INTO    vehicleidVar 
    FROM    vehicle v
    INNER JOIN unit ON unit.uid = v.uid
    WHERE   v.uid = unitidParam
    LIMIT   1;
    
    SELECT  CONCAT('Suspected Unit #', unitnoVar ,' and Suspected Sim #', simcardnoVar)
    INTO    concatstrVar;

    IF vehicleidVar IS NOT NULL AND vehicleidVar > 0 THEN

        START TRANSACTION;	 
        BEGIN

            UPDATE  unit 
            SET     trans_statusid = 6
                    ,comments = commentParam 
            WHERE   uid = unitidParam;

            UPDATE  simcard 
            SET     trans_statusid = 14
                    , comments = commentParam 
            WHERE   id = simcardidParam;
            
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
                    , `comments`
                    , `vehicleid`)
            VALUES (customernoParam
                    ,unitidParam
                    ,lteamidParam
                    ,0
                    ,todaysdateParam
                    ,6
                    ,'Suspected'
                    ,simcardnoVar
                    ,''
                    ,''
                    ,commentParam
                    ,vehicleidVar);

            INSERT INTO trans_history (`customerno`
                        ,`simcard_id`
                        ,`teamid`
                        ,`type`
                        ,`trans_time`
                        ,`statusid`
                        ,`transaction`
                        ,`simcardno`
                        ,`invoiceno`
                        ,`expirydate`
                        ,`comments`
                        ,`vehicleid`)
            VALUES (customernoParam
                        ,simcardidParam
                        ,lteamidParam
                        ,1
                        ,todaysdateParam
                        , 14
                        ,'Suspected'
                        ,''
                        ,''
                        ,''
                        ,commentParam
                        ,vehicleidVar);

            INSERT INTO trans_history (`customerno`
                        ,`unitid`
                        ,`teamid`
                        ,`type`
                        ,`trans_time`
                        ,`statusid`
                        ,`transaction`
                        ,`simcardno`
                        ,`invoiceno`
                        ,`expirydate`
                        ,`comments`
                        ,`vehicleid`)
            VALUES (customernoParam
                        ,0
                        ,lteamidParam
                        ,2
                        ,todaysdateParam
                        ,0
                        ,concatstrVar
                        , ''
                        , ''
                        , ''
                        ,commentParam
                        ,vehicleidVar);

            IF conameParam <> '' THEN

                INSERT INTO contactperson_details (`typeid`
                        ,`person_name`
                        ,`cp_phone1`
                        , `customerno`
                        , `insertedby`
                        , `insertedon`)
                VALUES (3
                        ,conameParam
                        ,cophoneParam
                        ,customernoParam
                        ,lteamidParam
                        ,todaysdateParam);

                SELECT  LAST_INSERT_ID() 
                INTO    coordinatorParam;

            END IF;

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
                ,`create_timestamp`
                , status)
            VALUES (aptdateParam
                , customernoParam
                ,lteamidParam
                ,priorityParam
                ,vehicleidVar
                ,locationParam
                ,timeslotParam
                ,purposeParam
                ,detailsParam
                ,coordinatorParam
                ,todaysdateParam
                ,0);

            SET isexecutedOut = 1;
            SET msgOut = 'Suspect Successfully';

        END;
        COMMIT; 

    ELSE
        
        SET isexecutedOut = 0;
        SET msgOut = 'Vehicle not present';

    END IF;
    
    SELECT  vehicleno
            ,groupid 
    INTO    vehiclenoVar
            ,groupidVar 
    FROM    vehicle 
    WHERE   vehicleid = vehicleidVar
    LIMIT   1;

    SELECT  `name` 
    INTO    elixirOut 
    FROM    team 
    WHERE   teamid = lteamidParam
    LIMIT   1;

    SELECT  c.username
            ,c.realname
            ,c.email
    INTO    usernameOut
            ,realnameOut
            ,emailOut
    FROM    `user` c 
    LEFT OUTER JOIN groupman p ON p.groupid =groupidVar 
    LEFT OUTER JOIN groupman ON c.userid <> groupman.userid 
    WHERE   c.customerno = customernoParam 
    AND     c.email <> ''
    AND     c.isdeleted = 0 
    AND     (c.groupid= groupidVar OR c.groupid = 0) 
    AND     (c.`role` = 'Administrator' OR c.role = 'Master')
    GROUP BY c.userid 
    LIMIT   1;

    SET vehiclenoOut = vehiclenoVar;
    SET unitnoOut = unitnoVar;
    SET simcardnoOut = simcardnoVar;

   END;
END$$
DELIMITER ;  

DELIMITER $$
DROP PROCEDURE IF EXISTS `pullBucketList`$$
CREATE PROCEDURE `pullBucketList`(
     IN dateParam VARCHAR(100))
BEGIN
        
        SELECT  b.bucketid
                , b.customerno
                , c.customercompany
                , b.priority
                , v.vehicleno
                , v.uid
                , b.location
                , b.purposeid
                , cp.person_name
                , cp.cp_phone1
                , t.`name` as fe_name
                , b.vehicleno as vehno
                , b.vehicleid
                , b.details
                , sp.timeslot
                , b.created_by
                , te.`name` as created_by_name
                , b.apt_date
                , b.fe_id
                , b.status
        FROM    bucket b
        INNER JOIN customer c ON c.customerno = b.customerno
        LEFT OUTER JOIN vehicle v ON v.vehicleid = b.vehicleid
        LEFT OUTER JOIN contactperson_details cp ON cp.cpdetailid = b.coordinatorid
        LEFT OUTER JOIN team t ON t.teamid = b.fe_id
        LEFT OUTER JOIN team te ON te.teamid = b.created_by
        LEFT OUTER JOIN sp_timeslot sp ON sp.tsid = b.timeslotid                                        
        LEFT OUTER JOIN unit u ON u.uid = b.unitid
        LEFT OUTER JOIN simcard s ON s.id = b.simcardid
        WHERE   b.apt_date LIKE dateParam  
        ORDER BY sp.tsid;

END$$
DELIMITER ; 


DELIMITER $$
DROP PROCEDURE IF EXISTS `pullCoordinator`$$
CREATE PROCEDURE `pullCoordinator`(
     IN customernoParam INT(11)
     )
BEGIN

    SELECT  cpdetailid
            , person_name 
    FROM    contactperson_details 
    WHERE   customerno = customernoParam 
    AND     typeid = 3;

END$$
DELIMITER ; 




DELIMITER $$
DROP PROCEDURE IF EXISTS `editBucketOperation`$$
CREATE PROCEDURE `editBucketOperation`(
      IN statusParam INT(11)
     ,IN bucketidParam INT(11)
     ,IN dataParam VARCHAR(100)
     ,IN todaysdateParam DATETIME
     ,OUT isexecutedOut TINYINT(2))
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

    START TRANSACTION;	 
    BEGIN
        IF statusParam = 4 THEN

            UPDATE  bucket 
            SET     fe_id= dataParam
                    ,status=statusParam
                    ,fe_assigned_timestamp = todaysdateParam 
            where   bucketid= bucketidParam;

        END IF;

        IF statusParam = 5 THEN

            UPDATE  bucket 
            SET     status=statusParam 
                    ,cancelled_timestamp = todaysdateParam 
                    , cancellation_reason= dataParam 
            where   bucketid=bucketidParam;

        END IF;

        IF statusParam = 1 THEN

            UPDATE  bucket 
            SET     status =statusParam
                    , reschedule_date=dataParam
                    ,reschedule_timestamp = todaysdateParam 
            where   bucketid=bucketidParam;

            INSERT INTO bucket(`apt_date`
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
            SELECT   dataParam
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
            FROM    bucket 
            WHERE   bucketid=bucketidParam
            ORDER BY `bucketid` DESC
            LIMIT   1;

        END IF;

        SET isexecutedOut = 1;

    END;
    COMMIT;

END;

END$$
DELIMITER ;  


DELIMITER $$
DROP PROCEDURE IF EXISTS `editBucketCRM`$$
CREATE PROCEDURE `editBucketCRM`(
     IN statusParam TINYINT(2)
    ,IN customernoParam INT(11)
    ,IN vehicleidParam INT(11)
    ,IN createdbyParam INT(11)
    ,IN priorityidParam TINYINT(2)
    ,IN locationParam VARCHAR(50)
    ,IN timeslotParam TINYINT(2)
    ,IN purposeidParam TINYINT(2)
    ,IN detailsParam VARCHAR(100)
    ,IN dataParam VARCHAR(100)
    ,IN coordinatorParam INT(11)
    ,IN aptdateParam DATETIME
    ,IN conameParam VARCHAR(50)
    ,IN cophoneParam INT(11)
    ,IN bucketidParam INT(11)
    ,IN todaysdateParam DATETIME
    ,OUT isexecutedOut TINYINT(2))
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

    START TRANSACTION;	 
    BEGIN

        IF conameParam <> '' THEN

            INSERT INTO contactperson_details (`typeid` 
                    ,`person_name`
                    ,`cp_phone1`
                    , `customerno`
                    , `insertedby`
                    , `insertedon`)
            VALUES (3
                    ,conameParam
                    , cophoneParam
                    , customernoParam
                    , createdbyParam
                    , todaysdateParam);

            SELECT LAST_INSERT_ID() INTO coordinatorParam;

        END IF;

        IF statusParam = 0 THEN

            UPDATE  bucket 
            SET     apt_date = aptdateParam 
                    , coordinatorid = coordinatorParam
                    , priority = priorityidParam
                    , location = locationParam
                    , timeslotid = timeslotParam
                    , purposeid = purposeidParam
                    , details = detailsParam
                    , status = statusParam
                    , create_timestamp = todaysdateParam 
            where   bucketid = bucketidParam;

        END IF;

        IF statusParam = 5 THEN

            UPDATE  bucket 
            SET     status=statusParam
                    , cancelled_timestamp = todaysdateParam
                    , cancellation_reason = dataParam 
            where   bucketid=bucketidParam;

        END IF;

        IF statusParam = 1 THEN

            UPDATE  bucket 
            SET     status= statusParam
                    , reschedule_date= dataParam
                    , reschedule_timestamp = todaysdateParam 
            where   bucketid = bucketidParam;


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
                , `create_timestamp`, status)
            VALUES (dataParam,customernoParam
                ,createdbyParam
                , priorityidParam
                , vehicleidParam
                ,locationParam
                ,timeslotParam
                ,purposeidParam
                ,detailsParam
                ,coordinatorParam
                ,todaysdateParam
                ,0);

        END IF;
        
        SET isexecutedOut = 1;
    END;
    COMMIT;
    
END;
END$$
DELIMITER ; 


DELIMITER $$
DROP PROCEDURE IF EXISTS `pullReason`$$
CREATE PROCEDURE `pullReason`()
BEGIN

    SELECT  reasonid
            ,reason 
    FROM    nc_reason 

END;
END$$
DELIMITER ; 

DELIMITER $$
DROP PROCEDURE IF EXISTS unit_sim_veh_of_cust$$
CREATE PROCEDURE unit_sim_veh_of_cust(
	IN customernoParam INT(11)
	)
BEGIN
    SELECT      unit.uid
                ,unit.unitno
                ,simcard.id
                ,simcard.simcardno
                ,vehicle.vehicleid
                ,vehicle.vehicleno 
    FROM        vehicle
    INNER JOIN  unit ON unit.uid=vehicle.uid
    INNER JOIN  devices ON devices.uid=unit.uid
    LEFT OUTER JOIN simcard ON simcard.id=devices.simcardid
    WHERE       vehicle.customerno=customernoParam 
    AND         isdeleted = 0;

END$$
DELIMITER ;

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied =1
WHERE   patchid = 477;