INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'556', '2018-04-16 16:30:00', 'Yash Kanakia', 'Docket Enhancements', '0'
);

USE `elixiatech`;
DROP procedure IF EXISTS `fetch_buckets`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `fetch_buckets`(
IN docketIdsParam TEXT)
BEGIN

   SELECT b.bucketid, b.apt_date,t.name,b.docketid,b.create_timestamp,b.status as statusCheck,
    (CASE
    WHEN b.status=0
        THEN 'Open'
        WHEN b.status=1
        THEN 'Rescheduled'
        WHEN b.status=2
        THEN 'Successful'
        WHEN b.status=3
        THEN 'Unsuccessful'
        WHEN b.status=4
        THEN 'FE Assigned'
        WHEN b.status=5
        THEN 'Cancelled'
        WHEN b.status=6
        THEN 'Incomplete'
    END) as status,
    (CASE
        WHEN b.purposeid=1
        THEN 'Installation'
        WHEN b.purposeid=2
        THEN 'Repair'
        WHEN b.purposeid=3
        THEN 'Removal'
        WHEN b.purposeid=4
        THEN 'Replacement'
        WHEN b.purposeid=5
        THEN 'Reinstall'
    END) as purposeid,
    (CASE
        WHEN b.priority=1
        THEN 'High'
        WHEN b.priority=2
        THEN 'Medium'
        WHEN b.priority=1
        THEN 'Low'
    END)as priority
    from speed.bucket b
    LEFT JOIN team t on b.created_by = t.teamid
    where FIND_IN_SET(docketid,docketIdsParam);

END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `fetch_dockets`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `fetch_dockets`(
    IN teamIdParam INT(11),
    IN docketIdParam INT(11)
)
BEGIN
IF(docketIdParam = 0) THEN
    IF(teamIdParam = 0 OR teamIdParam = NULL) THEN
        SELECT d.docketid,d.customerno,(SELECT customercompany from customer where customerno = d.customerno) as customername,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,d.timestamp,d.i_type,d.response,
        (SELECT name from team where teamid = d.team_id LIMIT 1)as name,
        (SELECT name from team where teamid = d.create_by LIMIT 1)as create_name
        FROM docket d
        LEFT JOIN docket_purpose_type p ON p.purpose_id=d.purpose_id
        LEFT JOIN docket_interaction_type i ON i.interaction_id=d.interaction_id
        LEFT JOIN team t ON d.team_id = t.teamid
        where  d.team_id = teamIdParam
        ORDER BY d.docketid DESC  ;
    ELSEIF(teamIdParam = 28) THEN
     SELECT d.docketid,d.customerno,(SELECT customercompany from customer where customerno = d.customerno) as customername,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,d.timestamp,d.i_type,d.response,
        (SELECT team.name from team where team.teamid = d.team_id LIMIT 1)as name,
        (SELECT team.name from team where team.teamid = d.create_by LIMIT 1)as create_name
        FROM docket d
        LEFT JOIN docket_purpose_type p ON p.purpose_id=d.purpose_id
        LEFT JOIN docket_interaction_type i ON i.interaction_id=d.interaction_id
        LEFT JOIN team t ON d.team_id = t.teamid
        ORDER BY d.docketid DESC  ;
    ELSE
    SELECT d.docketid,d.customerno,(SELECT customercompany from customer where customerno = d.customerno) as customername,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,d.timestamp,d.i_type,d.response,
        (SELECT team.name from team where team.teamid = d.team_id LIMIT 1)as name,
        (SELECT team.name from team where team.teamid = d.create_by LIMIT 1)as create_name
        FROM docket d
        LEFT JOIN docket_purpose_type p ON p.purpose_id=d.purpose_id
        LEFT JOIN docket_interaction_type i ON i.interaction_id=d.interaction_id
        LEFT JOIN team t ON d.team_id = t.teamid
        where  d.team_id = teamIdParam
        ORDER BY d.docketid DESC  ;
    END IF;
ELSE
    SELECT d.docketid,d.customerno,(SELECT customercompany from customer where customerno = d.customerno) as customername,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,d.timestamp,d.i_type,d.response,
    (SELECT name from team where teamid = d.team_id LIMIT 1)as name,
    (SELECT name from team where teamid = d.create_by LIMIT 1)as create_name
    FROM docket d
    LEFT JOIN docket_purpose_type p ON p.purpose_id=d.purpose_id
    LEFT JOIN docket_interaction_type i ON i.interaction_id=d.interaction_id
    LEFT JOIN team t ON d.team_id = t.teamid
    WHERE docketid = docketIdParam
    ORDER BY d.docketid DESC  ;
END IF;
END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `get_buckets`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `get_buckets`(
IN docketIdParam INT)
BEGIN
  SELECT u.unitno,b.unitid,b.simcardid,s.simcardno,b.status as statusID, b.fe_id,b.bucketid, b.customerno,b.apt_date, c.customercompany, b.priority, v.vehicleno, b.location,b.details, b.purposeid, cp.person_name, cp.cp_phone1, t.name, b.vehicleno as vehno, b.vehicleid, sp.timeslot,
        (CASE
        WHEN b.status=0
        THEN 'FE Not Assigned'
        WHEN b.status=1
        THEN 'Rescheduled'
        WHEN b.status=2
        THEN 'Successful'
        WHEN b.status=3
        THEN 'Unsuccessful'
        WHEN b.status=4
        THEN (SELECT name from team where teamid = b.fe_id)
        WHEN b.status=5
        THEN 'Cancelled'
        WHEN b.status=6
        THEN 'Incomplete'
        END)as status
                FROM speed.bucket b
                INNER JOIN speed.customer c ON c.customerno = b.customerno
                LEFT OUTER JOIN speed.vehicle v ON v.vehicleid = b.vehicleid
                LEFT OUTER JOIN speed.contactperson_details cp ON cp.cpdetailid = b.coordinatorid
                LEFT OUTER JOIN speed.team t ON t.teamid = b.fe_id
                LEFT OUTER JOIN speed.sp_timeslot sp ON sp.tsid = b.timeslotid
                LEFT OUTER JOIN speed.unit u ON u.uid = b.unitid
                LEFT OUTER JOIN speed.simcard s ON s.id = b.simcardid
                WHERE docketid=docketIdParam
        ORDER BY b.bucketid ASC;
END$$

DELIMITER ;


USE `speed`;
DROP procedure IF EXISTS `re_install_device`;

DELIMITER $$
USE `speed`$$
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
    ,IN docketidParam INT
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

                UPDATE  bucket
                SET     `status` = 2
                        ,`task_completion_timestamp` = todaysdateParam
                WHERE   bucketid= bucketidParam ;

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

            UPDATE  `bucket`
            SET     `status` = statusParam
                        ,`is_problem_of` = unsuccessProblemParam
                        ,`remarks` = commentParam
                        ,`task_completion_timestamp` = todaysdateParam
            where   `bucketid`=bucketidParam;

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
                    , `status`
                    ,`docketid`)
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
                    ,docketidParam
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
                    , `status`
                    ,`docketid`)
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
                    ,docketidParam
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

DELIMITER ;

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
                        ,`docketid`)
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
                        ,`docketid`)
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
DROP procedure IF EXISTS `remove_unit_sim`;

DELIMITER $$
USE `speed`$$
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
    ,IN docketidParam INT
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

            SET isexecutedOut = 0;
        END;

    SELECT  simcardid
    INTO    simcardidVar
    FROM    devices
    WHERE   uid = unitidParam
    ORDER BY    deviceid DESC
    LIMIT   1;

    SELECT  simcardno
    INTO    simcardnoVar
    FROM    simcard
    WHERE   id = simcardidVar
    ORDER BY    id DESC
    LIMIT   1;

    SELECT  unitno
    INTO    unitnoVar
    FROM    unit
    WHERE   uid = unitidParam
    ORDER BY    uid DESC
    LIMIT   1;

    SELECT  vehicleid
                ,vehicleno
                ,groupid
    INTO    vehicleidVar
                ,vehiclenoVar
                ,groupidVar
    FROM    vehicle
    WHERE   uid = unitidParam
    ORDER BY    vehicleid DESC
    LIMIT   1;

    IF statusParam = 2 THEN

        START TRANSACTION;
        BEGIN

            UPDATE  unit
            SET     trans_statusid = 20
                    ,teamid = eteamidParam
                    ,comments = commentParam
            WHERE   uid= unitidParam;

            UPDATE  simcard
            SET     trans_statusid= 21
                    ,teamid=eteamidParam
                    , comments = commentParam
            WHERE   id= simcardidVar;



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



            UPDATE  unit
            SET     onlease=0
            WHERE   uid = unitidParam;



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

            UPDATE  bucket
            SET     `status`=statusParam
                        ,`is_problem_of` = unsuccessProblemParam
                        ,`remarks` = commentParam
                        ,`task_completion_timestamp` = todaysdateParam
            where   `bucketid`=bucketidParam;

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
                    , `status`
                    ,`docketid`)

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
                    ,docketidParam
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
                    ,`status`
                    ,`docketid`)
            SELECT  rescheduleDateParam
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
                    ,docketidParam
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

USE `speed`;
DROP procedure IF EXISTS `repair`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `repair`(
    IN todaysdateParam DATETIME
    ,IN unitidParam INT(11)
    ,IN simcardidParam INT(11)
    ,IN eteamidParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN customernoParam INT(11)
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
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT unitnoOut VARCHAR(16)
    ,OUT simnumberOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
            ROLLBACK;

        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;

            SET isexecutedOut = 0;
    END;
    BEGIN
        DECLARE unitnoVar VARCHAR(11);
        DECLARE vehicleidVar INT(11);
        DECLARE vehiclenoVar VARCHAR(40);
        DECLARE groupidVar INT(11);

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
                                , comments = commentParam
                WHERE   uid= unitidParam;

                UPDATE  simcard
                SET     trans_statusid= 13
                                ,comments =commentParam
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
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                UPDATE  bucket
                SET     `status` = statusParam
                        ,`task_completion_timestamp` = todaysdateParam
                WHERE   bucketid= bucketidParam ;

                SET isexecutedOut = 1;

            END;
            COMMIT;



            SELECT      simcardno
            INTO        simnumberOut
            FROM        simcard
            WHERE       id = simcardidParam
            ORDER BY    id DESC
            LIMIT       1;


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
            WHERE           c.customerno =  customernoParam
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

                UPDATE  bucket
                SET     `status`=statusParam
                        ,`is_problem_of` = unsuccessProblemParam
                        ,`remarks`= commentParam
                        ,`task_completion_timestamp` = todaysdateParam
                where   `bucketid`=bucketidParam;

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
                where   id = simcardidParam;

                SET     isexecutedOut = 1;

            END;
            COMMIT;

        ELSEIF statusParam = 6 THEN

            START TRANSACTION;
            BEGIN

                UPDATE  bucket
                SET     `status` = statusParam
                        ,`reschedule_date` = incompleteDateParam
                        ,`reschedule_timestamp` = todaysdateParam
                        ,remarks = commentParam
                where   bucketid = bucketidParam;

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
                        , `status`
                        ,`docketid`)
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
                        ,docketidParam
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
                SET     `status` = statusParam
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
                        ,`docketid`)
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
                        , todaysdateParam
                        , 0
                        ,docketidParam
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
                        ,'3'
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
                        ,unitidParam
                        ,vehicleidVar
                        ,'7'
                        ,'4'
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
                where   id = simcardidParam;

                SET     isexecutedOut = 1;

            END;
            COMMIT;

        END IF;

    END;
END$$

DELIMITER ;

