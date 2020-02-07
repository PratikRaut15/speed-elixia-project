INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'567', '2018-06-05 13:00:00', 'Yash Kanakia', 'Team Enhancements', '0'
);

USE `speed`;
DROP procedure IF EXISTS `register_device`;

DELIMITER $$
USE `speed`$$
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
    ,IN end_dateParam DATE
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
    ,IN docketidParam INT
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
                            ,end_date=end_dateParam
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


                    INSERT INTO dailyreport(`customerno`
                        , `vehicleid`
                        , `uid`
                        ,`last_online_updated`
                        ,`daily_date`)
                    VALUES (customernoParam
                        ,vehicleidVar
                        ,unitidParam
                        ,todaysdateParam
                        ,installdateParam);

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
                        , `status`
                        ,`docketid`
                        ,`prevBucketId`)
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
                        ,docketidParam
                        ,bucketidParam
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
                        , `status`
                        ,`docketid`
                        ,`prevBucketId`)
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
                        ,docketidParam
                        ,bucketidParam
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


USE `speed`;
DROP procedure IF EXISTS `get_ledger_veh_mapping`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `get_ledger_veh_mapping`( 
    IN ledger_veh_mapidparam INT
    , IN customernoparam INT
    , IN ledgeridparam INT
    , IN vehiclenoparam VARCHAR(20)
)
BEGIN

    IF(ledger_veh_mapidparam = '' OR ledger_veh_mapidparam = '0') THEN
     SET ledger_veh_mapidparam = NULL;
    END IF;

    IF(customernoparam = '' OR customernoparam = '0') THEN
     SET customernoparam = NULL;
    END IF;

    IF(vehiclenoparam = '' OR vehiclenoparam = '0') THEN
     SET vehiclenoparam = NULL;
    END IF;

    IF(ledgeridparam = '' OR ledgeridparam = '0') THEN
     SET ledgeridparam = NULL;
    END IF;

    SELECT  l.ledger_veh_mapid
            ,l.ledgerid
            ,l.vehicleid
            ,l.customerno
            ,v.vehicleno
            ,l.createdby
            ,l.createdon
            ,l.updatedby
            ,l.updatedon
            ,v.uid
    FROM    ledger_veh_mapping as l
    INNER JOIN vehicle as v ON l.vehicleid = v.vehicleid
    INNER JOIN unit as u ON u.vehicleid = v.vehicleid 
    INNER JOIN devices as d ON d.uid = u.uid AND u.unitno NOT LIKE 'D%'
    INNER JOIN `simcard` s ON s.id = d.simcardid AND s.trans_statusid IN (13,14)  
    WHERE (l.ledger_veh_mapid  = ledger_veh_mapidparam OR ledger_veh_mapidparam IS NULL)
    AND     (l.customerno = customernoparam OR customernoparam IS NULL)
    AND     (v.customerno = customernoparam OR customernoparam IS NULL)
    AND     (l.ledgerid = ledgeridparam OR ledgeridparam IS NULL)
    AND     (v.vehicleno LIKE CONCAT('%', vehiclenoparam, '%') OR vehiclenoparam IS NULL)
    AND     l.isdeleted = 0
    ORDER BY v.vehicleno ASC;

END$$

DELIMITER ;



