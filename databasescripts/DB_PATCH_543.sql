INSERT INTO `uat_speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'543', '2018-03-14 12:44:00', 'Kartik Joshi', 'Docket module for speed/team', '0'
);

CREATE TABLE `docket` (
  `docketid` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) DEFAULT NULL,
  `raiseondate` datetime DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `purpose_id` tinyint(2) DEFAULT NULL,
  `interaction_id` tinyint(2) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `create_by` int(11) DEFAULT NULL,
  `i_type` tinyint(2) DEFAULT NULL COMMENT '1 for inbound,0 for outbound',
  `response` tinyint(2) DEFAULT NULL COMMENT '1 for answered,0 for unanswered',
  PRIMARY KEY (`docketid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `docket_history` (
  `dhid` int(11) NOT NULL AUTO_INCREMENT,
  `docketid` int(11) NOT NULL,
  `customerno` int(11) DEFAULT NULL,
  `raiseondate` datetime DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `purpose_id` tinyint(2) DEFAULT NULL,
  `interaction_id` tinyint(2) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `create_by` int(11) DEFAULT NULL,
  `i_type` tinyint(2) DEFAULT NULL COMMENT '1 for inbound,0 for outbound',
  `response` tinyint(2) DEFAULT NULL COMMENT '1 for answered,0 for unanswered',
  PRIMARY KEY (`dhid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `docket_interaction_type` (
  `interaction_id` int(11) NOT NULL,
  `interaction_type` varchar(15) NOT NULL,
  PRIMARY KEY (`interaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `docket_interaction_type` (`interaction_id`, `interaction_type`) VALUES
(1, 'Call'),
(2, 'Chat'),
(3, 'Mail'),
(4, 'Visit');

CREATE TABLE `docket_purpose_type` (
  `purpose_id` int(11) NOT NULL AUTO_INCREMENT,
  `purpose_type` varchar(15) NOT NULL,
  PRIMARY KEY (`purpose_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

INSERT INTO `docket_purpose_type` (`purpose_id`, `purpose_type`) VALUES
(1, 'Software'),
(2, 'Operations'),
(3, 'Accounts'),
(4, 'Inquiry'),
(5, 'Others');

CREATE TABLE `uat_speed.bucket_purpose` (
  `purpose_id` int(11) NOT NULL,
  `purpose_type` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`purpose_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `bucket_purpose` (`purpose_id`, `purpose_type`) VALUES
(1, 'New Installation'),
(2, 'Repair'),
(3, 'Removal'),
(4, 'Replacement'),
(5, 'Reinstall');

ALTER TABLE sp_tickettype ADD COLUMN (issue_type TINYINT(2));

ALTER TABLE `uat_elixiatech`.`sp_ticket` 
ADD COLUMN `docketid` INT NULL DEFAULT 0 AFTER `prodId`;

ALTER TABLE `uat_elixiatech`.`sp_ticket_details`
 ADD COLUMN `prodId` INT ;

ALTER TABLE `uat_elixiatech`.`sp_ticket_details` 
ADD COLUMN `docketid` INT NULL DEFAULT 0 AFTER `prodId`;

ALTER TABLE `uat_speed.bucket` add column (docketid INT(11))

DELIMITER $$
DROP procedure IF EXISTS `update_ticket_team`$$
CREATE PROCEDURE `update_ticket_team`(
    IN customerNoParam INT
    ,IN priorityParam INT
    ,IN sendMailParam INT
    ,IN emailIdsParam VARCHAR(100)
    ,IN ecloseDateParam DATE
    ,IN addCountParam INT
    ,IN ticketIdParam INT
    ,IN ticketDescParam VARCHAR(255)
    ,IN allotFromParam INT
    ,IN allotToParam INT
    ,IN ticketStatusParam INT
    ,IN createdByParam INT
    ,IN createdOnTimeParam DATETIME
    ,IN createdTypeParam INT
    ,IN docketIdParam INT
    ,IN prodIdParam INT
    ,OUT ticketIdOut INT   
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
    START TRANSACTION;
        IF (customerNoParam = '' OR customerNoParam = 0) THEN
            SET customerNoParam = NULL;
             
        END IF;       
        IF (customerNoParam IS NOT NULL ) THEN
            update uat_elixiatech.sp_ticket 
            set priority=priorityParam , 
            send_mail_status=sendMailParam ,
            customerid=customerNoParam ,
            eclosedate=ecloseDateParam ,
            eclosedate_chng_count = eclosedate_chng_count + addCountParam,
            send_mail_to = emailIdsParam,
            prodId = prodIdParam
            where ticketid=ticketIdParam;
            IF (createdTypeParam=0) THEN
                INSERT INTO uat_elixiatech.`sp_ticket_details`(`ticketid`, `description`, `allot_from`, `allot_to`, `status`, `create_by`, `create_on_time`) 
                    VALUES (ticketIdParam, ticketDescParam, allotFromParam ,allotToParam, ticketStatusParam,createdByParam,createdOnTimeParam);
            ELSE
                INSERT INTO uat_elixiatech.`sp_ticket_details`(`ticketid` ,`description`,`allot_from`,`allot_to`,`status`,`create_by`,`create_on_time`,`eclosedate`,`created_type`,`docketid`,`prodId`)
                    VALUES (ticketIdParam, ticketDescParam,allotFromParam ,allotToParam, ticketStatusParam,createdByParam,createdOnTimeParam,ecloseDateParam,createdTypeParam);
            END IF;
        END IF;     
        SET ticketIdOut = ticketIdParam;
    COMMIT;
END$$DELIMITER ;

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
    FROM   uat_speed.simcard 
    WHERE   id = simcardidParam
    LIMIT   1;
    
    SELECT  unitno 
    INTO    unitnoVar 
    FROM   uat_speed.unit 
    WHERE   uid = unitidParam
    LIMIT   1;

    SELECT  v.vehicleid 
    INTO    vehicleidVar 
    FROM   uat_speed.vehicle v
    INNER JOIN uat_speed.unit ON unit.uid = v.uid
    WHERE   v.uid = unitidParam
    LIMIT   1;
    
    SELECT  CONCAT('Suspected Unit #', unitnoVar ,' and Suspected Sim #', coalesce(simcardnoVar,''))
    INTO    concatstrVar;

    IF vehicleidVar IS NOT NULL AND vehicleidVar > 0 THEN

        START TRANSACTION;   
        BEGIN

            UPDATE uat_speed.unit 
            SET     trans_statusid = 6
                    ,comments = commentParam 
            WHERE   uid = unitidParam;

            UPDATE uat_speed.simcard 
            SET     trans_statusid = 14
                    , comments = commentParam 
            WHERE   id = simcardidParam;
            
            INSERT INTO uat_speed.trans_history (`customerno`
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

            INSERT INTO uat_speed.trans_history (`customerno`
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

            INSERT INTO uat_speed.trans_history (`customerno`
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


            INSERT INTO uat_speed.bucket (`apt_date`
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
                ,`docketid`)
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
                ,docketidParam);

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
    FROM   uat_speed.vehicle 
    WHERE   vehicleid = vehicleidVar
    LIMIT   1;

    SELECT  `name` 
    INTO    elixirOut 
    FROM   uat_speed.team 
    WHERE   teamid = lteamidParam
    LIMIT   1;

    SELECT  c.username
            ,c.realname
            ,c.email
    INTO    usernameOut
            ,realnameOut
            ,emailOut
    FROM   uat_speed.`user` c 
    LEFT OUTER JOIN uat_speed.groupman p ON p.groupid =groupidVar 
    LEFT OUTER JOIN uat_speed.groupman ON c.userid <> groupman.userid 
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
END$$DELIMITER ;

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

    ,OUT isexecutedOut INT

    ,OUT currentTicketId INT(11)

    ,OUT tickettypenameOut VARCHAR(100)

    ,OUT prioritynameOut VARCHAR(100)

    ,OUT allottoemailOut VARCHAR(50)

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

                T.teamid INTO crmTeamId

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

END$$DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `insert_docket`$$
CREATE PROCEDURE `insert_docket`(
    IN customerNoParam INT(11),
    IN raiseOnDateTimeParam DATETIME,
    IN timestampParam DATETIME,
    IN purposeIdParam TINYINT(2),
    IN interactionIdParam TINYINT(2),
    IN createByParam INT(11),
    IN teamIdParam INT(11),
    IN iTypeParam TINYINT(2),
    IN responseParam TINYINT(2),
    OUT isExecutedOut TINYINT(2),
    OUT docketId INT(11)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
       BEGIN
           ROLLBACK;
             /*GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
           @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
           SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
           SELECT @full_error;  */
           SET isexecutedOut = 0;
       END;

        SET isexecutedOut = 0;
       
       START TRANSACTION;
       BEGIN
            INSERT INTO docket (customerno, raiseondate,timestamp,purpose_id,interaction_id,team_id,create_by,i_type,response)
            VALUES (customerNoParam,raiseOnDateTimeParam,timestampParam,purposeIdParam,interactionIdParam,teamIdParam,createByParam,iTypeParam, responseParam);
          SET isExecutedOut = 1;
          SET docketId = LAST_INSERT_ID();
       END;
       COMMIT;
END$$DELIMITER ;

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
INSERT INTO uat_speed.bucket (
         `apt_date` ,`customerno`,`created_by`, `priority`, `vehicleid`, `location`,
        `timeslotid`, `purposeid`, `details`, `coordinatorid`, `create_timestamp`, `status`, `vehicleno`,`docketid`)
VALUES (apt_dateParam, customernoParam, teamidParam, priorityParam, 0, locationParam,
        timeslotParam,1,detailsParam,coordinatorParam,todayParam,0,vehnoParam,docketidParam);
SET bucketid = LAST_INSERT_ID();
END$$DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `get_ticket_types`$$
CREATE PROCEDURE `get_ticket_types`(
  IN issueTypeParam TINYINT(2)
    )
BEGIN
  IF((issueTypeParam=0)OR(issueTypeParam=null)) THEN
    SELECT typeid,tickettype from sp_tickettype;
    ELSE
    SELECT typeid,tickettype from sp_tickettype WHERE issueType = issueTypeParam;
    END IF;
END$$DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `get_purpose_types`$$
CREATE PROCEDURE `get_purpose_types`()
BEGIN
  SELECT purpose_id,purpose_type from docket_purpose_type;
END$$DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `get_interaction_types`$$
CREATE PROCEDURE `get_interaction_types`()
BEGIN
  SELECT interaction_id,interaction_type from docket_interaction_type;
END$$DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `get_crm`$$
CREATE PROCEDURE `get_crm`()
BEGIN
  SELECT teamid,rid,name FROM team WHERE rid > 0;
END$$DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `get_buckets`$$
CREATE PROCEDURE `get_buckets`(
IN docketIdParam INT)
BEGIN
  SELECT u.unitno, s.simcardno, b.bucketid, b.status, b.customerno, c.customercompany, b.priority, v.vehicleno, b.location, b.purposeid, cp.person_name, cp.cp_phone1, t.name, b.vehicleno as vehno, b.vehicleid, sp.timeslot
                FROM uat_speed.bucket b
                INNER JOIN uat_speed.customer c ON c.customerno = b.customerno
                LEFT OUTER JOIN uat_speed.vehicle v ON v.vehicleid = b.vehicleid
                LEFT OUTER JOIN uat_speed.contactperson_details cp ON cp.cpdetailid = b.coordinatorid
                LEFT OUTER JOIN uat_speed.team t ON t.teamid = b.fe_id
                LEFT OUTER JOIN uat_speed.sp_timeslot sp ON sp.tsid = b.timeslotid                                        
                LEFT OUTER JOIN uat_speed.unit u ON u.uid = b.unitid
                LEFT OUTER JOIN uat_speed.simcard s ON s.id = b.simcardid
                WHERE docketid=docketIdParam 
        ORDER BY b.bucketid ASC;
END$$DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_tickets`$$
CREATE PROCEDURE `fetch_tickets`(
  IN docketIdParam INT,
  IN ticketIdParam INT
)
BEGIN
  if(docketIdParam=0) THEN 
    select st.ticketid

          ,st.title

          ,st.ticket_type

          ,sttype.tickettype

          ,sp.priority as prname

          ,st.sub_ticket_issue

          ,st.customerid
          ,(SELECT customercompany from customer where customerno=st.customerid) as customercompany
          ,st.priority

          ,DATE_FORMAT(st.create_on_date ,'%d-%m-%Y') AS create_on_date

          ,st.create_by

          ,st.created_type

          ,st.uid

                    ,st.send_mail_to

          ,st.create_platform 

          ,t.name AS allot_to

                    ,std.description

          ,std.status
          
                    ,std.allot_to
                    
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

        WHERE (st.ticketid = ticketIdParam OR ticketIdParam IS NULL)

        GROUP BY    std.ticketid

        order by    st.ticketid ASC;

  ELSE 
    select st.ticketid

          ,st.title

          ,st.ticket_type

          ,sttype.tickettype

          ,sp.priority as prname

          ,st.sub_ticket_issue

          ,st.customerid
          ,(SELECT customercompany from customer where customerno=st.customerid) as customercompany
          ,st.priority

          ,DATE_FORMAT(st.create_on_date ,'%d-%m-%Y') AS create_on_date

          ,st.create_by

          ,st.created_type

          ,st.uid

                    ,st.send_mail_to

          ,st.create_platform 

          ,t.name AS allot_to

                    ,std.description

          ,std.status
            
          ,std.allot_to
                    
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

        WHERE (st.docketid = docketIdParam OR docketIdParam IS NULL)

        GROUP BY    std.ticketid

        order by    st.ticketid ASC;
    END IF;

END$$DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_team_list`$$
CREATE PROCEDURE `fetch_team_list`()
BEGIN
  SELECT teamid,name FROM team WHERE member_type = 1; 
END$$DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_status`$$
CREATE PROCEDURE `fetch_status`()
BEGIN
  SELECT `id`,`status` from ticket_status;
END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_priorities`$$
CREATE PROCEDURE `fetch_priorities`()
BEGIN
  SELECT prid,priority FROM sp_priority WHERE isdeleted = 0; 
END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_dockets`$$
CREATE PROCEDURE `fetch_dockets`(
    IN teamIdParam INT(11),
    IN docketIdParam INT(11)
)
BEGIN
IF(docketIdParam = 0) THEN
    IF(teamIdParam = 0 OR teamIdParam = NULL) THEN
        SELECT d.docketid,d.customerno,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,
        (SELECT name from team where teamid = d.team_id LIMIT 1)as name,
        (SELECT name from team where teamid = d.create_by LIMIT 1)as create_name FROM docket d 
        LEFT JOIN docket_purpose_type p ON p.purpose_id=d.purpose_id
        LEFT JOIN docket_interaction_type i ON i.interaction_id=d.interaction_id
        LEFT JOIN team t ON d.team_id = t.teamid
    where  d.team_id = teamIdParam
        ORDER BY d.docketid DESC;
    ELSEIF(teamIdParam = 28) THEN
     SELECT d.docketid,d.customerno,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,
        (SELECT team.name from team where team.teamid = d.team_id LIMIT 1)as name,
        (SELECT team.name from team where team.teamid = d.create_by LIMIT 1)as create_name FROM docket d 
        LEFT JOIN docket_purpose_type p ON p.purpose_id=d.purpose_id
        LEFT JOIN docket_interaction_type i ON i.interaction_id=d.interaction_id
        LEFT JOIN team t ON d.team_id = t.teamid

        ORDER BY d.docketid DESC;
    ELSE
    SELECT d.docketid,d.customerno,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,
        (SELECT team.name from team where team.teamid = d.team_id LIMIT 1)as name,
        (SELECT team.name from team where team.teamid = d.create_by LIMIT 1)as create_name FROM docket d 
        LEFT JOIN docket_purpose_type p ON p.purpose_id=d.purpose_id
        LEFT JOIN docket_interaction_type i ON i.interaction_id=d.interaction_id
        LEFT JOIN team t ON d.team_id = t.teamid
        where  d.team_id = teamIdParam
        ORDER BY d.docketid DESC;
    END IF;
ELSE
    SELECT d.docketid,d.customerno,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,
    (SELECT name from team where teamid = d.team_id LIMIT 1)as name,
  (SELECT name from team where teamid = d.create_by LIMIT 1)as create_name FROM docket d 
    LEFT JOIN docket_purpose_type p ON p.purpose_id=d.purpose_id
    LEFT JOIN docket_interaction_type i ON i.interaction_id=d.interaction_id
    LEFT JOIN team t ON d.team_id = t.teamid
    WHERE docketid = docketIdParam
    ORDER BY d.docketid DESC;
END IF;
END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_customers`$$
CREATE PROCEDURE `fetch_customers`(
    IN termParam VARCHAR(20)
)
BEGIN
  SELECT DISTINCT (customerno),rel_manager,customercompany,
    (CASE WHEN rel_manager = 0 THEN
      "Altaf Shaikh"
    ELSE 
      team.name
    END) as name,
    (CASE WHEN rel_manager = 0 THEN
      "28"
    ELSE 
      team.teamid
    END) as allot_to
  FROM customer, team
  WHERE team.rid = customer.rel_manager 
    AND (customercompany LIKE CONCAT('%',termParam,'%')
            OR customerno LIKE CONCAT('%',termParam,'%'))
        ORDER BY customerno;
END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `edit_docket`$$
CREATE PROCEDURE `edit_docket`(
    IN customerNoParam INT(11),
    IN raiseOnDateTimeParam DATETIME,
    IN timestampParam DATETIME,
    IN purposeIdParam TINYINT(2),
    IN interactionIdParam TINYINT(2),
    IN createByParam INT(11),
    IN teamIdParam INT(11),
    IN iTypeParam TINYINT(2),
    IN responseParam TINYINT(2),
  IN docketIdParam INT,
    OUT isExecutedOut TINYINT(2)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
       BEGIN
           ROLLBACK;
             /*GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
           @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
           SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
           SELECT @full_error;  */
           SET isexecutedOut = 0;
       END;

        SET isexecutedOut = 0;
       
       START TRANSACTION;
       BEGIN
       UPDATE `docket` SET 
            `customerno`= customerNoParam,
            `timestamp`= timestampParam,
            `purpose_id`=purposeIdParam,
            `interaction_id`=interactionIdParam,
            `team_id`=teamIdParam
             WHERE docketid = docketidParam;
            
            INSERT INTO docket_history (docketid,customerno, raiseondate,timestamp,purpose_id,interaction_id,team_id,create_by,i_type,response)
            VALUES (docketIdParam,customerNoParam,raiseOnDateTimeParam,timestampParam,purposeIdParam,interactionIdParam,teamIdParam,createByParam,iTypeParam, responseParam);
          
            SET isExecutedOut = 1;
       END;
       COMMIT;
END$$
DELIMITER ;


UPDATE  uat_speed.dbpatches
SET isapplied =1
WHERE   patchid = 543;