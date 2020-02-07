INSERT INTO `uat_speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'547', '2018-03-20 12:00:00', 'Yash Kanakia', 'Changes in docket module in team', '0'
);


DELIMITER $$
DROP procedure IF EXISTS `get_buckets`$$
CREATE PROCEDURE `get_buckets`(
IN docketIdParam INT)
BEGIN
  SELECT u.unitno,b.unitid,b.simcardid,s.simcardno, b.bucketid, b.status, b.customerno,b.apt_date, c.customercompany, b.priority, v.vehicleno, b.location,b.details, b.purposeid, cp.person_name, cp.cp_phone1, t.name, b.vehicleno as vehno, b.vehicleid, sp.timeslot
                FROM speed.bucket b
                INNER JOIN speed.customer c ON c.customerno = b.customerno
                LEFT OUTER JOIN speed.vehicle v ON v.vehicleid = b.vehicleid
                LEFT OUTER JOIN speed.contactperson_details cp ON cp.cpdetailid = b.coordinatorid
                LEFT OUTER JOIN speed.team t ON t.teamid = b.fe_id
                LEFT OUTER JOIN speed.sp_timeslot sp ON sp.tsid = b.timeslotid                                        
                LEFT OUTER JOIN speed.unit u ON u.uid = b.unitid
                LEFT OUTER JOIN speed.simcard s ON s.id = b.simcardid
                WHERE docketid=docketIdParam AND b.status IN (0,4)
        ORDER BY b.bucketid ASC;
END$$

DELIMITER ;







DELIMITER $$
DROP procedure IF EXISTS `edit_bucket`$$
CREATE PROCEDURE `edit_bucket`(
     IN apt_dateParam date
    ,IN coordinatorParam INT(11)
    ,IN priorityidParam INT(4)
    ,IN locationParam VARCHAR(50)
    ,IN timeslotParam INT(4)
    ,IN purposeidParam INT(4)
    ,IN detailsParam VARCHAR(100)
    ,IN sstatusParam INT(4)
    ,IN todayParam datetime
    ,IN creasonParam VARCHAR(50)
    ,IN reschedule_dateParam date
    ,IN bucketidParam INT(11)
    )
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
        
    END;

    SELECT apt_date,priority,location,timeslotid,purposeid,details,coordinatorid,status,cancellation_reason,reschedule_date,cancelled_timestamp
    INTO @aptdate,@priority,@location,@timeslotid,@purposeid,@details,@coordinatorid,@status,@creason,@reschedule_date,@cancelled_timestamp 
    FROM speed.bucket
    WHERE bucketid = bucketidParam;
    
UPDATE speed.`bucket` 
SET `apt_date`=COALESCE(apt_dateParam,@aptdate),
`priority`=COALESCE(priorityidParam,@priority),
`location`=COALESCE(locationParam,@location),
`timeslotid`=COALESCE(timeslotParam,@timeslotid),
`purposeid`=COALESCE(purposeidParam,@purposeid),
`details`=COALESCE(detailsParam,@details),
`coordinatorid`=COALESCE(coordinatorParam,@coordinatorid),
`reschedule_date`=COALESCE(reschedule_dateParam,@location),
`status`=COALESCE(sstatusParam,@status),
`reschedule_timestamp`=COALESCE(todayParam,@reschedule_date),
`cancelled_timestamp`=COALESCE(todayParam,@cancelled_timestamp),
`cancellation_reason`=COALESCE(creasonParam,@creason)
WHERE `bucketid`=bucketidParam;


          
END$$
DELIMITER ;








DELIMITER $$
DROP procedure IF EXISTS `insert_bucket`$$
CREATE PROCEDURE `insert_bucket`(
    IN apt_dateParam date
    ,IN customernoParam INT(11)
    ,IN teamidParam INT(11)
    ,IN priorityParam INT(4)
    ,IN locationParam VARCHAR(50)
    ,IN timeslotParam INT(4)
    ,IN detailsParam VARCHAR(100)
    ,IN coordinatorParam INT(11)
    ,IN todayParam datetime
    ,IN vehnoParam VARCHAR(10)
    ,IN docketidParam INT(11)
    ,OUT bucketid INT
)
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
       /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        
        */
    END;
INSERT INTO speed.bucket (
         `apt_date` ,`customerno`,`created_by`, `priority`, `vehicleid`, `location`,
        `timeslotid`, `purposeid`, `details`, `coordinatorid`, `create_timestamp`, `status`, `vehicleno`,`docketid`)
VALUES (apt_dateParam, customernoParam, teamidParam, priorityParam, 0, locationParam,
        timeslotParam,1,detailsParam,coordinatorParam,todayParam,0,vehnoParam,docketidParam);
SET bucketid = LAST_INSERT_ID();
END$$
DELIMITER ;




DELIMITER $$
DROP procedure IF EXISTS `suspect_unit`$$
CREATE PROCEDURE `suspect_unit`(
     IN commentParam VARCHAR(50)
    ,IN unitidParam INT(11)
    ,IN simcardidParam INT(11)
    ,IN customernoParam INT(11)
    ,IN aptdateParam DATE
    ,IN priorityParam INT(4)
    ,IN locationParam VARCHAR(50)
    ,IN timeslotParam INT(4)
    ,IN purposeParam INT(4)
    ,IN detailsParam VARCHAR(100)
    ,IN coordinatorParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN todaysdateParam DATETIME
    ,IN docketidParam INT(11)
    ,OUT isexecutedOut TINYINT(2)
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT unitnoOut VARCHAR(11)
    ,OUT simcardnoOut VARCHAR(50)
    ,OUT usernameOut VARCHAR(50)
    ,OUT realnameOut VARCHAR(50)
    ,OUT emailOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150)
    ,OUT msgOut VARCHAR(50))
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
            ROLLBACK;
           
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
    FROM   speed.simcard 
    WHERE   id = simcardidParam
    LIMIT   1;
    
    SELECT  unitno 
    INTO    unitnoVar 
    FROM   speed.unit 
    WHERE   uid = unitidParam
    LIMIT   1;

    SELECT  v.vehicleid 
    INTO    vehicleidVar 
    FROM   speed.vehicle v
    INNER JOIN speed.unit ON unit.uid = v.uid
    WHERE   v.uid = unitidParam
    LIMIT   1;
    
    SELECT  CONCAT('Suspected Unit #', unitnoVar ,' and Suspected Sim #', coalesce(simcardnoVar,''))
    INTO    concatstrVar;

    IF vehicleidVar IS NOT NULL AND vehicleidVar > 0 THEN

        START TRANSACTION;   
        BEGIN

            UPDATE speed.unit 
            SET     trans_statusid = 6
                    ,comments = commentParam 
            WHERE   uid = unitidParam;

            UPDATE speed.simcard 
            SET     trans_statusid = 14
                    , comments = commentParam 
            WHERE   id = simcardidParam;
            
            INSERT INTO speed.trans_history (`customerno`
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

            INSERT INTO speed.trans_history (`customerno`
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

            INSERT INTO speed.trans_history (`customerno`
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


            INSERT INTO speed.bucket (`apt_date`
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
                , status
                ,`docketid`
                ,`unitid`
                ,`simcardid`)
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
                ,0
                ,docketidParam
                ,unitidParam
                ,simcardidParam);

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
    FROM   speed.vehicle 
    WHERE   vehicleid = vehicleidVar
    LIMIT   1;

    SELECT  `name` 
    INTO    elixirOut 
    FROM   speed.team 
    WHERE   teamid = lteamidParam
    LIMIT   1;

    SELECT  c.username
            ,c.realname
            ,c.email
    INTO    usernameOut
            ,realnameOut
            ,emailOut
    FROM   speed.`user` c 
    LEFT OUTER JOIN speed.groupman p ON p.groupid =groupidVar 
    LEFT OUTER JOIN speed.groupman ON c.userid <> groupman.userid 
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
DROP procedure IF EXISTS `insert_e_bucket`$$
CREATE PROCEDURE `insert_e_bucket`(
    IN reschedule_dateParam date
    ,IN customernoParam INT(11)
    ,IN unitidParam INT(11)
    ,IN simcardidParam INT(11)
    ,IN teamidParam INT(11)
    ,IN priorityParam INT(4)
    ,IN puproseidParam INT(4)
    ,IN locationParam VARCHAR(50)
    ,IN timeslotParam INT(4)
    ,IN detailsParam VARCHAR(100)
    ,IN coordinatorParam INT(11)
    ,IN todayParam datetime
    ,IN vehicleidParam INT(11)
    ,IN docketidParam INT(11)
    ,OUT bucketid INT
)
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
       /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
        
    END;
INSERT INTO speed.bucket (
         `apt_date` ,`customerno`,`created_by`, `priority`, `vehicleid`, `location`,
        `timeslotid`, `purposeid`, `details`, `coordinatorid`, `create_timestamp`, `status`,`docketid`,`unitid`,`simcardid`)
VALUES (reschedule_dateParam, customernoParam, teamidParam, priorityParam,vehicleidParam , locationParam,
        timeslotParam,puproseidParam,detailsParam,coordinatorParam,todayParam,0,docketidParam,unitidParam,simcardidParam);
SET bucketid = LAST_INSERT_ID();
END$$

DELIMITER ;





