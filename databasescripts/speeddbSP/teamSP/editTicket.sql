/*
    Name		-	editTicket
    Description 	-	Pull all ticket type.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL editTicket()
    Created by		-	Arvind
    Created on		- 	06 April, 2017
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/


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