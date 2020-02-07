/*
    Name		-	pullTicketStatus
    Description 	-	Pull all ticket status.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL pullTicketStatus()
    Created by		-	Arvind
    Created on		- 	06 April, 2017
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/


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