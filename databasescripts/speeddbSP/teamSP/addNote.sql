/*
    Name		-	addNote
    Description 	-	Add note to ticket.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL addNote(450,'Test',50,'2017-06-19 15:30:00',@is_executed)
    Created by		-	Arvind
    Created on		- 	19 June, 2017
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

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