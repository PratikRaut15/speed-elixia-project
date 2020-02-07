INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'554', '2018-04-12 19:30:00', 'Yash Kanakia', 'Docket Enhancements', '0'
);


USE `elixiatech`;
DROP procedure IF EXISTS `get_buckets`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `get_buckets`(
IN docketIdParam INT)
BEGIN
  SELECT u.unitno,b.unitid,b.simcardid,s.simcardno, b.fe_id,b.bucketid, b.customerno,b.apt_date, c.customercompany, b.priority, v.vehicleno, b.location,b.details, b.purposeid, cp.person_name, cp.cp_phone1, t.name, b.vehicleno as vehno, b.vehicleid, sp.timeslot,
		 (CASE 
        WHEN b.status=0
        THEN 'Not Assigned'
        WHEN b.status=4
        THEN (SELECT name from team where teamid = b.fe_id) 
		END)as status
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

USE `elixiatech`;
DROP procedure IF EXISTS `get_ticket_types`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `get_ticket_types`(
  IN issueTypeParam TINYINT(2)
    )
BEGIN
  IF((issueTypeParam=0)OR(issueTypeParam=null)) THEN
    SELECT  department_id,typeid,tickettype from sp_tickettype where isdeleted=0;
    END IF;
END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `suspect_unit`;

DELIMITER $$
USE `elixiatech`$$
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
  ,OUT bucketidOut INT
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
       /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
        
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
            
            SET bucketidOut = 0;
            SET isexecutedOut = 0;
            
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
      
            SET bucketidOut = LAST_INSERT_ID();
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
DROP procedure IF EXISTS `insert_ticket`$$

CREATE PROCEDURE `insert_ticket`(

    IN titleParam VARCHAR(250)

    ,IN typeidParam INT(11)

    ,IN pridParam INT(11)

    ,IN descParam VARCHAR(255)

    ,IN useridParam INT

    ,IN customerNoParam INT

    ,IN todaysdateParam DATETIME

    ,IN allottoParam INT(11)

    ,IN raiseondateParam DATETIME

    ,IN expecteddateParam DATE

    ,IN mailStatusParam TINYINT(2)

    ,IN ticketmailidParam VARCHAR(255)

    ,IN ccemailidParam VARCHAR(255)

    ,IN createdbyParam INT(11)

    ,IN relmgrParam INT(11)

    ,IN platformParam TINYINT(2)

    ,IN lteamidParam INT

    ,IN prodIdParam INT
  
    ,IN docketIdParam INT
    
    ,IN ecdToBeUpdatedByParam DATETIME

    ,OUT isexecutedOut INT

    ,OUT currentTicketId INT(11)

    ,OUT tickettypenameOut VARCHAR(100)
    
  ,OUT customercompanyOut VARCHAR(100) 
    
    ,OUT prioritynameOut VARCHAR(100)

    ,OUT allottoemailOut VARCHAR(50)
    
  ,OUT createbyOut VARCHAR(100)
    
    ,OUT departmentIdOut VARCHAR(25)
)
BEGIN

    DECLARE crmTeamId INT;

    DECLARE relManager INT;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION

    BEGIN

            ROLLBACK;

            
/*
            GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,

            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;

            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);

            SELECT @full_error;

            SET isexecutedOut = 0;
*/
            

    END;



    BEGIN

        IF(relmgrParam = 0 OR relmgrParam = '') THEN

            SET relmgrParam = null;

        END IF;

        IF typeidParam <> '' THEN

            SELECT  tickettype

            INTO    tickettypenameOut

            FROM    sp_tickettype

            WHERE   typeid = typeidParam

            AND     isdeleted = 0 ;

        END IF;



        IF pridParam <> '' THEN

            SELECT  priority

            INTO    prioritynameOut

            FROM    sp_priority

            WHERE   prid = pridParam

            AND     isdeleted = 0 ;

        END IF;



        SELECT  email

        INTO    allottoemailOut

        FROM    team

        WHERE   teamid = allottoParam;
        
        SELECT  customercompany

        INTO    customercompanyOut

        FROM    customer

        WHERE   customerno = customerNoParam;
        
    SELECT  name

        INTO    createbyOut

        FROM    team

        WHERE  teamid = createdbyParam;

    SELECT department_id
        
        INTO departmentIdOut
        
        FROM team 
        
        WHERE teamid = createdbyParam;


        IF (customerNoParam IS NOT NULL OR relmgrParam IS NULL) THEN

            SELECT

                c.rel_manager INTO relManager

            FROM customer c

            WHERE c.customerno = customerNoParam

            ;



        END IF;



        IF(coalesce(relManager,0) = 0)THEN

            SET relmgrParam = 28;

        ELSE

            SELECT

                t.teamid INTO crmTeamId

            FROM team t

            WHERE t.rid = relManager

            ;

            SET relmgrParam = crmTeamId;

        END IF;



        SET currentTicketId = 0;

        SET isexecutedOut = 0;

        START TRANSACTION;

        BEGIN



            INSERT INTO `sp_ticket`(

                    `title`

                    ,`ticket_type`

                    ,`customerid`

                    ,`send_mail_status`

                    ,`send_mail_to`

                    ,`send_mail_cc`

                    ,`priority`

                    ,`raised_on_date`

                    ,`create_on_date`

                    ,`create_by`

                    ,`create_platform`

                    ,`uid`

                    ,`crmid`

                    ,`prodId`
          
                    ,`eclosedate`

                    ,`docketid`
                    
                    ,`estimateddate`
                    )

            VALUES (

                    titleParam

                    ,typeidParam

                    ,customerNoParam

                    ,mailStatusParam

                    ,ticketmailidParam

                    ,ccemailidParam

                    ,pridParam

                    ,raiseondateParam

                    ,todaysdateParam

                    ,createdbyParam

                    ,platformParam

                    ,useridParam

                    ,relmgrParam

                    ,prodIdParam

          ,expecteddateParam

          ,docketIdParam
                    
                    ,ecdToBeUpdatedByParam
                    );



            SET currentTicketId = LAST_INSERT_ID();



            IF(currentTicketId != 0) THEN

                INSERT INTO `sp_ticket_details`(`ticketid`

                    ,`description`

                    ,`allot_from`

                    ,`allot_to`

                    ,`status`

                    ,`create_by`

                    ,`create_on_time`

                    ,`userid`

                    )

                VALUES (currentTicketId

                    ,descParam

                    ,lteamidParam

                    ,allottoParam

                    ,0

                    ,createdbyParam

                    ,todaysdateParam

                    ,useridParam

                    );

                SET isexecutedOut = 1;

            END IF;

        END;

        COMMIT;

    END;

END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `fetch_team_list`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `fetch_team_list`()
BEGIN

  SELECT teamid,name,department_id FROM team WHERE is_deleted = 0
  order by role_id asc;

END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `edit_bucket`;

DELIMITER $$
USE `elixiatech`$$
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
DECLARE statusCheck INT;
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

    SELECT apt_date,priority,location,timeslotid,purposeid,details,coordinatorid,status,cancellation_reason,reschedule_date,reschedule_timestamp,cancelled_timestamp
    INTO @aptdate,@priority,@location,@timeslotid,@purposeid,@details,@coordinatorid,@status,@creason,@reschedule_date,@reschedule_timestamp,@cancelled_timestamp 
    FROM speed.bucket
    WHERE bucketid = bucketidParam;
   
 SET statusCheck = sstatusParam;  
    
IF(statusCheck=0)    
THEN
UPDATE speed.`bucket` 
SET `apt_date`=apt_dateParam,
`priority`=priorityidParam,
`location`=locationParam,
`timeslotid`=timeslotParam,
`purposeid`=purposeidParam,
`details`=detailsParam,
`coordinatorid`=coordinatorParam,
`status`=sstatusParam,
`reschedule_date`=@reschedule_date,
`reschedule_timestamp`=@reschedule_timestamp,
`cancellation_reason`=@creason,
`cancelled_timestamp`=@cancelled_timestamp
WHERE `bucketid`=bucketidParam;
END IF;

IF(statusCheck=1)    
THEN
UPDATE speed.`bucket` 
SET 
`reschedule_date`=reschedule_dateParam,
`reschedule_timestamp`=todayParam,
`status`=sstatusParam,
`apt_date`= @aptdate,
`priority`=@priority,
`location`=@location,
`timeslotid`=@timeslotid,
`purposeid`=@purposeid,
`details`=@details,
`coordinatorid`=@coordinatorid,
`cancellation_reason`=@creason,
`cancelled_timestamp`=@cancelled_timestamp
WHERE `bucketid`=bucketidParam;
END IF;            

IF(statusCheck=5)    
THEN
UPDATE speed.`bucket` 
SET 
`cancellation_reason`=creasonParam,
`cancelled_timestamp`=todayParam,
`status`=sstatusParam,
`apt_date`= @aptdate,
`priority`=@priority,
`location`=@location,
`timeslotid`=@timeslotid,
`purposeid`=@purposeid,
`details`=@details,
`coordinatorid`=@coordinatorid,
`reschedule_date`=@reschedule_date,
`reschedule_timestamp`=@reschedule_timestamp
WHERE `bucketid`=bucketidParam;
END IF;  

SELECT @aptdate,@priority,@location,@timeslotid,@purposeid,@details,@coordinatorid,@status,@creason,@reschedule_date,@reschedule_timestamp,@cancelled_timestamp ;
END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_dockets` $$
CREATE PROCEDURE `fetch_dockets`(
    IN teamIdParam INT(11),
    IN docketIdParam INT(11)
)
BEGIN
IF(docketIdParam = 0) THEN
    IF(teamIdParam = 0 OR teamIdParam = NULL) THEN
        SELECT d.docketid,(SELECT customercompany from customer where customerno = d.customerno) as customername,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,d.timestamp,
        (SELECT name from team where teamid = d.team_id LIMIT 1)as name,
        (SELECT name from team where teamid = d.create_by LIMIT 1)as create_name 
        FROM docket d 
        LEFT JOIN docket_purpose_type p ON p.purpose_id=d.purpose_id
        LEFT JOIN docket_interaction_type i ON i.interaction_id=d.interaction_id
        LEFT JOIN team t ON d.team_id = t.teamid
        where  d.team_id = teamIdParam
        ORDER BY d.docketid DESC  ;
    ELSEIF(teamIdParam = 28) THEN
     SELECT d.docketid,(SELECT customercompany from customer where customerno = d.customerno) as customername,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,d.timestamp,
        (SELECT team.name from team where team.teamid = d.team_id LIMIT 1)as name,
        (SELECT team.name from team where team.teamid = d.create_by LIMIT 1)as create_name 
        FROM docket d 
        LEFT JOIN docket_purpose_type p ON p.purpose_id=d.purpose_id
        LEFT JOIN docket_interaction_type i ON i.interaction_id=d.interaction_id
        LEFT JOIN team t ON d.team_id = t.teamid
        ORDER BY d.docketid DESC  ;
    ELSE
    SELECT d.docketid,(SELECT customercompany from customer where customerno = d.customerno) as customername,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,d.timestamp,
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
    SELECT d.docketid,(SELECT customercompany from customer where customerno = d.customerno) as customername,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,d.timestamp,
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

DELIMITER $$
DROP procedure IF EXISTS `get_tickets_team`$$
CREATE PROCEDURE `get_tickets_team`(IN teamIdParam INT)
BEGIN
    select st.ticketid
          ,st.title
          ,st.ticket_type
          ,sttype.tickettype
          ,sp.priority as prname
          ,st.docketid
          ,st.customerid
          ,(SELECT customercompany from customer where customerno=st.customerid) as customercompany
          ,st.priority
          ,DATE_FORMAT(st.create_on_date ,'%d-%m-%Y') AS create_on_date
          ,(SELECT name from team where teamid = st.create_by) AS create_by_name
          ,st.create_by
          ,(SELECT name from team where teamid = std.allot_to) AS allot_to_name
          ,std.status
          ,(SELECT name from team where teamid = std.allot_from) AS allot_from_name
          ,ts.status as ticketStatus
          ,DATE_FORMAT(st.eclosedate,'%d-%m-%Y') AS eclosedate
          ,std.uid
          ,st.prodId
        FROM sp_ticket as st 
        INNER JOIN  sp_ticket_details std ON std.ticketid = st.ticketid 
            AND std.uid = (SELECT MAX(uid) from sp_ticket_details sds where sds.ticketid=st.ticketid)
        LEFT JOIN   team t ON t.teamid = std.allot_to
        LEFT JOIN   sp_tickettype as sttype on sttype.typeid = st.ticket_type 
        LEFT JOIN   sp_priority as sp on sp.prid = st.priority 
        LEFT JOIN ticket_status ts ON ts.id = std.status
        WHERE (st.create_by = teamIdParam)
        GROUP BY    std.ticketid
        order by    ticketStatus DESC,st.raised_on_date DESC, st.ticketid DESC;
END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `insert_email_id`;

DELIMITER $$
USE `elixiatech`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_email_id`(
    IN customernoParam int(11), 
    IN emailParam varchar(30), 
    IN dateParam datetime, 
    IN useridParam int(11),

    OUT lastInsertId INT
    )
BEGIN
            SET lastInsertId = 0;
            SELECT COUNT(eid),eid INTO @emailCount,@eidVar FROM report_email_list where email_id LIKE CONCAT('%',emailParam,'%') AND customerno = customernoParam LIMIT 1;
            IF(@emailCount > 0) THEN
                SET lastInsertId = @eidVar;
            ELSE
                INSERT INTO report_email_list(
                    customerno,
                    email_id,
                    created_on,
                    created_by
                )VALUES(
                   customernoParam, 
                   emailParam,
                   dateParam,
                   useridParam
                );
                SET lastInsertId = LAST_INSERT_ID();
            END IF;
            
SELECT lastInsertId;            
END$$

DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `fetch_dockets`$$
CREATE  PROCEDURE `fetch_dockets`(
    IN teamIdParam INT(11),
    IN docketIdParam INT(11)
)
BEGIN
IF(docketIdParam = 0) THEN
    IF(teamIdParam = 0 OR teamIdParam = NULL) THEN
        SELECT d.docketid,d.customerno,(SELECT customercompany from customer where customerno = d.customerno) as customername,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,d.timestamp,
        (SELECT name from team where teamid = d.team_id LIMIT 1)as name,
        (SELECT name from team where teamid = d.create_by LIMIT 1)as create_name 
        FROM docket d 
        LEFT JOIN docket_purpose_type p ON p.purpose_id=d.purpose_id
        LEFT JOIN docket_interaction_type i ON i.interaction_id=d.interaction_id
        LEFT JOIN team t ON d.team_id = t.teamid
        where  d.team_id = teamIdParam
        ORDER BY d.docketid DESC  ;
    ELSEIF(teamIdParam = 28) THEN
     SELECT d.docketid,d.customerno,(SELECT customercompany from customer where customerno = d.customerno) as customername,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,d.timestamp,
        (SELECT team.name from team where team.teamid = d.team_id LIMIT 1)as name,
        (SELECT team.name from team where team.teamid = d.create_by LIMIT 1)as create_name 
        FROM docket d 
        LEFT JOIN docket_purpose_type p ON p.purpose_id=d.purpose_id
        LEFT JOIN docket_interaction_type i ON i.interaction_id=d.interaction_id
        LEFT JOIN team t ON d.team_id = t.teamid
        ORDER BY d.docketid DESC  ;
    ELSE
    SELECT d.docketid,d.customerno,(SELECT customercompany from customer where customerno = d.customerno) as customername,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,d.timestamp,
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
    SELECT d.docketid,d.customerno,(SELECT customercompany from customer where customerno = d.customerno) as customername,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,d.timestamp,
    (SELECT name from team where teamid = d.team_id LIMIT 1)as name,
    (SELECT name from team where teamid = d.create_by LIMIT 1)as create_name 
    FROM docket d 
    LEFT JOIN docket_purpose_type p ON p.purpose_id=d.purpose_id
    LEFT JOIN docket_interaction_type i ON i.interaction_id=d.interaction_id
    LEFT JOIN team t ON d.team_id = t.teamid
    WHERE docketid = docketIdParam
    ORDER BY d.docketid DESC  ;
END IF;
ENDELECT team.name from team where team.teamid = d.team_id LIMIT 1)as name,
        (SELECT team.name from team where team.teamid = d.create_by LIMIT 1)as create_name 
        FROM docket d 
        LEFT JOIN docket_purpose_type p ON p.purpose_id=d.purpose_id
        LEFT JOIN docket_interaction_type i ON i.interaction_id=d.interaction_id
        LEFT JOIN team t ON d.team_id = t.teamid
		
        ORDER BY d.docketid DESC  ;
    ELSE
    SELECT customerno,d.docketid,(SELECT customercompany from customer where customerno = d.customerno) as customername,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,d.timestamp,
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
    SELECT customerno,d.docketid,(SELECT customercompany from customer where customerno = d.customerno) as customername,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,d.timestamp,
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




ALTER TABLE team ADD COLUMN department_id INT after password;
UPDATE team SET department_id = d_id;
ALTER TABLE team DROP COLUMN d_id;

ALTER TABLE sp_tickettype ADD COLUMN department_id INT AFTER tickettype;
UPDATE sp_tickettype set department_id = d_id;
ALTER TABLE sp_tickettype DROP COLUMN d_id;

ALTER TABLE department ADD COLUMN department_id INT AFTER d_id; 
UPDATE department SET department_id = d_id;
ALTER TABLE department DROP COLUMN d_id;
ALTER TABLE department MODIFY COLUMN department_id INT PRIMARY KEY;

ALTER TABLE team MODIFY COLUMN role_id INT AFTER management_points; 