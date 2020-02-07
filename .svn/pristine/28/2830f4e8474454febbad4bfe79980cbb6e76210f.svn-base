INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'491', '2017-04-12 15:00:00', 'Arvind Thakur', 'Team Ticket Changes', '0'
);


ALTER TABLE `sp_ticket`
ADD COLUMN  `raised_on_date` DATETIME DEFAULT '0000-00-00 00:00:00' AFTER `priority`;

ALTER TABLE sp_ticket 
DROP COLUMN estimateddate,
DROP COLUMN timeslot,
DROP COLUMN vehicleid;

ALTER TABLE sp_ticket_details
ADD COLUMN  send_mail_status TINYINT(2) DEFAULT '0';


insert into `sp_tickettype`(`tickettype`) values(`Investigation`);

alter table `sp_ticket_details`
add column  `eclosedate` DATE AFTER `create_on_time` DEFAULT '0000-00-00';

alter table `sp_ticket`
add column  `eclosedate_chng_count` INT(11) DEFAULT 0;


CREATE TABLE IF NOT EXISTS `ticket_status`(
	`id` INT(11) PRIMARY KEY,
	`status` VARCHAR(50),
	`isdeleted` TINYINT(2) DEFAULT 0);

insert into ticket_status(`id`,`status`) values(0,'Open');
insert into ticket_status(`id`,`status`) values(1,'In Progress');
insert into ticket_status(`id`,`status`) values(2,'Closed');
insert into ticket_status(`id`,`status`) values(3,'Pipeline');
insert into ticket_status(`id`,`status`) values(4,'On Hold');
insert into ticket_status(`id`,`status`) values(5,'Waiting for Client');
insert into ticket_status(`id`,`status`) values(6,'Resolved');
insert into ticket_status(`id`,`status`) values(7,'Reopen');


DELIMITER $$
DROP PROCEDURE IF EXISTS addTicket$$
CREATE PROCEDURE addTicket(
    IN titleParam VARCHAR(250)
    ,IN customernoParam INT(11)
    ,IN descParam VARCHAR(255)
    ,IN tickettypeParam INT(11)
    ,IN allottoParam INT(11)
    ,IN raiseondateParam DATETIME
    ,IN expecteddateParam DATE
    ,IN mailStatusParam TINYINT(2)
    ,IN ticketmailidParam VARCHAR(255)
    ,IN ccemailidParam VARCHAR(255)
    ,IN priorityParam INT(11)
    ,IN todaysdateParam DATETIME
    ,IN createdbyParam INT(11)
    ,IN lteamidParam INT(11)
    ,OUT isexecutedOut TINYINT(2)
    ,OUT ticketidOut INT(11)
    ,OUT tickettypenameOut VARCHAR(100)
    ,OUT prioritynameOut VARCHAR(100)
    ,OUT allottoemailOut VARCHAR(50)
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

        IF tickettypeParam <> '' THEN

            SELECT  tickettype 
            INTO    tickettypenameOut
            FROM    sp_tickettype 
            WHERE   typeid = tickettypeParam 
            AND     isdeleted = 0 ;

        END IF;

        IF priorityParam <> '' THEN

            SELECT  priority 
            INTO    prioritynameOut
            FROM    sp_priority 
            WHERE   prid = priorityParam 
            AND     isdeleted = 0 ;

        END IF;

        SELECT  email 
        INTO    allottoemailOut
        FROM    team 
        WHERE   teamid = allottoParam;

        START TRANSACTION;	 
        BEGIN

            INSERT INTO `sp_ticket`(`title`
                    ,`ticket_type`
                    ,`customerid` 
                    ,`eclosedate`
                    ,`send_mail_status`
                    ,`send_mail_to`
                    ,`send_mail_cc`
                    ,`priority`
                    ,`raised_on_date`
                    ,`create_on_date`
                    ,`create_by`)
            VALUES (titleParam
                    ,tickettypeParam
                    ,customernoParam
                    ,expecteddateParam
                    ,mailStatusParam
                    ,ticketmailidParam
                    ,ccemailidParam
                    ,priorityParam
                    ,raiseondateParam
                    ,todaysdateParam
                    ,createdbyParam);

            SELECT  LAST_INSERT_ID() 
            INTO    ticketidOut;

            INSERT INTO `sp_ticket_details`(`ticketid`
                ,`description`
                ,`allot_from`
                ,`allot_to`
                ,`status`
                ,`create_by`
                ,`create_on_time`
                ,`send_mail_status`)
            VALUES (ticketidOut
                ,descParam
                ,lteamidParam
                ,allottoParam
                ,0
                ,createdbyParam
                ,todaysdateParam
                ,mailStatusParam);

            SET isexecutedOut = 1;
        
        END;
        COMMIT; 
    END;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS editTicket$$
CREATE PROCEDURE editTicket(
    IN ticketidParam INT(11)
    ,IN customernoParam INT(11)
    ,IN ticketallotParam INT(11)
    ,IN ticketdescParam VARCHAR(255)
    ,IN ticketstatusParam TINYINT(1)
    ,IN createdbyParam INT(11)
    ,IN expecteddateParam DATE
    ,IN tickettypeParam INT(11)
    ,IN sendemailstatusParam TINYINT(1)
    ,IN toemailidParam VARCHAR(255)
    ,IN ccemailidParam VARCHAR(255)
    ,IN priorityidParam INT(11)
    ,IN todaysdateParam DATETIME
    ,IN noteParam VARCHAR(255)
    ,IN createdtypeParam TINYINT(1)
    ,OUT isexecutedOut TINYINT(1)
    ,OUT mailsendtoOut VARCHAR(255)
    ,OUT createbynameOut VARCHAR(150)
    ,OUT allottonameOut VARCHAR(150)
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

        SELECT  `email` 
                ,`name`
        INTO    mailsendtoOut
                ,createbynameOut
        FROM    `team` 
        WHERE   teamid = createdbyParam;

        SELECT `name`
        INTO    allottonameOut
        FROM    `team`
        WHERE   teamid = ticketallotParam;

        SET isexecutedOut = 0;

        START TRANSACTION;
        BEGIN

            INSERT INTO `sp_note`(`ticketid` ,
                        `note`,
                        `create_by`,
                        `sendemailto`,
                        `create_on_date`)
            VALUES (ticketidParam
                        ,noteParam
                        ,createdbyParam
                        ,mailsendtoOut
                        ,todaysdateParam);

            if createdtypeParam = 0 THEN

                INSERT INTO `sp_ticket_details`(`ticketid`,
                        `description`,
                        `allot_from`,
                        `allot_to`,
                        `status`,
                        `create_by`,
                        `create_on_time`,
                        `eclosedate`)
                VALUES (ticketidParam
                        ,ticketdescParam
                        ,createdbyParam
                        ,ticketallotParam
                        ,ticketstatusParam
                        ,createdbyParam
                        ,todaysdateParam
                        ,expecteddateParam);

            ELSE

                INSERT INTO `sp_ticket_details`(
                        `ticketid` ,
                        `description`,
                        `allot_from`,
                        `allot_to`,
                        `status`,
                        `create_by`,
                        `create_on_time`,
                        `eclosedate`,
                        `created_type`)
                VALUES (ticketidParam
                        ,ticketdescParam
                        ,createdbyParam
                        ,ticketallotParam
                        ,ticketstatusParam
                        ,createdbyParam
                        ,todaysdateParam
                        ,expecteddateParam
                        ,1);
            END IF; 

            SET isexecutedOut = 1;
        
        END;
        COMMIT; 

    END;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS pullemail$$
CREATE PROCEDURE pullemail(
    IN searchstringParam VARCHAR(50)
    ,IN customernoParam INT(11)
)

BEGIN

    SELECT  `eid`
            ,`email_id` 
    FROM    `report_email_list`
    WHERE   `customerno` IN (customernoParam,0) 
    AND     `email_id` LIKE searchstringParam;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS pullMyTicket$$
CREATE PROCEDURE pullMyTicket(
    IN teamidParam INT(11)
)

BEGIN

    select  *
    from    (select (CASE   WHEN stde.status=1 THEN 'Inprogress' 
                            WHEN stde.status= 2 THEN 'Closed' 
                            WHEN stde.status= 3 THEN 'Pipeline' 
                            WHEN stde.status= 4 THEN 'On Hold' 
                            WHEN stde.status= 5 THEN 'Waiting for Client' 
                            WHEN stde.status= 6 THEN 'Resolved' 
                            ELSE 'Open' END)as ticketstatus
                    , stde.uid
                    ,st.ticketid
                    , st.title
                    ,st.ticket_type
                    ,sttype.tickettype
                    ,st.sub_ticket_issue
                    ,st.customerid
                    ,st.eclosedate
                    , st.priority
                    ,sp.priority as prname
                    ,st.create_on_date
                    , st.create_by
                    , stde.status
                    ,stde.allot_to 
                    ,stde.description
            from    sp_ticket_details stde 
            left join   sp_ticket as st on st.ticketid = stde.ticketid 
            left join   sp_tickettype as sttype on sttype.typeid = st.ticket_type 
            left join   sp_priority as sp on sp.prid = st.priority   
            order by    stde.uid desc ) as main 
    group by    main.ticketid 
    having      main.allot_to = teamidParam 
    AND         main.status IN (0,1,3) 
    order by    main.eclosedate asc, main.priority asc, main.ticketid asc;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS pullTicketPriority$$
CREATE PROCEDURE pullTicketPriority()

BEGIN

    SELECT  prid
            ,priority 
    FROM    sp_priority
    WHERE   isdeleted = 0;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS pullTicketStatus$$
CREATE PROCEDURE pullTicketStatus()

BEGIN

    SELECT  id
            ,status 
    FROM    ticket_status
    WHERE   isdeleted = 0 AND id <> '7';

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS pullTicketType$$
CREATE PROCEDURE pullTicketType()

BEGIN

    SELECT  typeid
            ,tickettype 
    FROM    sp_tickettype
    WHERE   isdeleted = 0;

END$$
DELIMITER ;


UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied =1
WHERE   patchid = 491;