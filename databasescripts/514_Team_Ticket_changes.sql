INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('514', '2017-06-20 14:35:00','Arvind Thakur','Team Ticket Note API', '0');

-- ADD NOTE SP

DELIMITER $$
DROP PROCEDURE IF EXISTS addNote$$
CREATE PROCEDURE addNote(
    IN ticketidParam INT(11)
    ,IN noteParam VARCHAR(255)
    ,IN lteamidParam INT(11)
    ,IN todaysdateParam DATETIME
    ,OUT isexecutedOut TINYINT(1)
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

        SET isexecutedOut = 0;
    
        START TRANSACTION;
        BEGIN

            INSERT INTO sp_note (`ticketid`
                , `note`
                , `create_by`
                , `is_customer`
                , `create_on_date`) 
            VALUES (ticketidParam
                ,noteParam
                ,lteamidParam
                ,0
                ,todaysdateParam);

            SET isexecutedOut = 1;
        
        END;
        COMMIT; 

    END;

END$$
DELIMITER ;


-- PULL NOTE SP

DELIMITER $$
DROP PROCEDURE IF EXISTS pullNote$$
CREATE PROCEDURE pullNote(
    IN ticketidParam INT(11)
)

BEGIN

    SELECT  sn.note
            , team.`name`
            , `user`.realname
            , sn.create_on_date
    FROM    sp_note sn 
    LEFT JOIN team ON team.teamid = sn.create_by AND sn.is_customer = 0
    LEFT JOIN `user` ON `user`.userid = sn.create_by AND sn.is_customer = 1
    WHERE   sn.ticketid = ticketidParam 
    ORDER BY sn.noteid DESC;

END$$
DELIMITER ;


-- ADD TICKET

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
    ,IN platformParam TINYINT(2)
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
	    /*
			GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error; 
	*/	
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
                    ,`create_by`
                    ,`create_platform`)
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
                    ,createdbyParam
                    ,platformParam);

            SELECT  LAST_INSERT_ID() 
            INTO    ticketidOut;

            INSERT INTO `sp_ticket_details`(`ticketid`
                ,`description`
                ,`allot_from`
                ,`allot_to`
                ,`status`
                ,`create_by`
                ,`create_on_time`
                ,`send_mail_status`
                ,`userid`)
            VALUES (ticketidOut
                ,descParam
                ,lteamidParam
                ,allottoParam
                ,0
                ,createdbyParam
                ,todaysdateParam
                ,mailStatusParam
                ,0);

            SET isexecutedOut = 1;
        
        END;
        COMMIT; 
    END;
END$$
DELIMITER ;


--Edit Ticket

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
    
    DECLARE addcountVar INT(5) DEFAULT 0;
    DECLARE eclosedateVar DATE;

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

        SELECT  eclosedate 
        INTO    eclosedateVar
        FROM    sp_ticket
        WHERE   ticketid = ticketidParam;
        
        IF  expecteddateParam > eclosedateVar THEN
            SET addcountVar = 1;
        END IF;

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

            update  sp_ticket 
            set     priority= priorityidParam
                    , send_mail_status= sendemailstatusParam
                    , customerid= customernoParam
                    , eclosedate = expecteddateParam
                    , eclosedate_chng_count = eclosedate_chng_count + addcountVar  
            where   ticketid= ticketidParam;

            if createdtypeParam = 0 THEN

                INSERT INTO `sp_ticket_details`(`ticketid`,
                        `description`,
                        `allot_from`,
                        `allot_to`,
                        `status`,
                        `create_by`,
                        `create_on_time`,
                        `userid`,
                        `eclosedate`)
                VALUES (ticketidParam
                        ,ticketdescParam
                        ,createdbyParam
                        ,ticketallotParam
                        ,ticketstatusParam
                        ,createdbyParam
                        ,todaysdateParam
                        ,'0'
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
                        `userid`,
                        `created_type`)
                VALUES (ticketidParam
                        ,ticketdescParam
                        ,createdbyParam
                        ,ticketallotParam
                        ,ticketstatusParam
                        ,createdbyParam
                        ,todaysdateParam
                        ,expecteddateParam
                        ,'0'
                        ,1);
            END IF; 

            SET isexecutedOut = 1;
        
        END;
        COMMIT; 

    END;

END$$
DELIMITER ;

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 514;
