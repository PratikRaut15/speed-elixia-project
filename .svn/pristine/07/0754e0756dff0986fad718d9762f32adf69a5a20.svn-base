/*
    Name		-	pullTicketType
    Description 	-	Pull all ticket type.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL pullTicketType()
    Created by		-	Arvind
    Created on		- 	06 April, 2017
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/


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