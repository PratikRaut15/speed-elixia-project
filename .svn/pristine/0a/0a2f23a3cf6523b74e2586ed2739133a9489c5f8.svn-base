/*
    Name		-	addTicket
    Description 	-	Pull all ticket type.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL addTicket()
    Created by		-	Arvind
    Created on		- 	06 April, 2017
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/


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
